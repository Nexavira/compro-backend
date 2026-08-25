<?php

namespace App\Models\Auth;

use App\Models\BaseModel;
use App\Models\System\File;
use Illuminate\Support\Facades\Storage;

class DetailUser extends BaseModel
{
    protected $table = 'auth_detail_users';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function photo()
    {
        return $this->belongsTo(File::class, 'photo_id', 'id');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (!$this->photo_id) {
            return null;
        }

        if (is_numeric($this->photo_id)) {
            return $this->photo?->url;
        }

        return Storage::disk('public')->url($this->photo_id);
    }

    public function setPhotoUploadAttribute($value)
    {
        $value = is_array($value) ? reset($value) : $value;

        if ($value && !is_numeric($value)) {
            $path = $value;
            $file = File::where('file_path', $path)->first();

            if (!$file) {
                $disk = 'public';
                $fileExists = Storage::disk($disk)->exists($path);

                $file = File::create([
                    'file_path'      => $path,
                    'file_name'      => basename($path),
                    'original_name'  => basename($path),
                    'file_extension' => pathinfo($path, PATHINFO_EXTENSION),
                    'mime_type'      => $fileExists ? Storage::disk($disk)->mimeType($path) : 'image/jpeg',
                    'file_size'      => $fileExists ? Storage::disk($disk)->size($path) : 0,
                    'storage_disk'   => $disk,
                    'is_public'      => 1,
                    'is_used'        => 1,
                ]);
            }

            $this->photo_id = $file->id;
        } elseif (empty($value)) {
            $this->photo_id = null;
        }
    }

    protected static function booted()
    {
        static::saved(function ($detailUser) {
            if ($detailUser->photo_id) {
                File::where('id', $detailUser->photo_id)
                    ->where(function ($query) use ($detailUser) {
                        $query->whereNull('related_id')
                            ->orWhere('related_id', '!=', $detailUser->id);
                    })
                    ->update([
                        'related_id'   => $detailUser->id,
                        'related_type' => get_class($detailUser),
                    ]);
            }
        });
    }
}
