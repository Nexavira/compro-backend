<?php

namespace App\Models\CMS;

use App\Models\BaseModel;
use App\Models\System\File;
use App\Models\Tenant\TenantCategory;
use Illuminate\Support\Facades\Storage;

class GlobalTemplate extends BaseModel
{
    protected $table = 'cms_global_templates';

    protected $hidden = [
        'id',
        'tenant_category_id',
        'banner_image_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
        'version'
    ];

    public function tenantCategory()
    {
        return $this->belongsTo(TenantCategory::class, 'tenant_category_id', 'id');
    }

    public function bannerImage()
    {
        return $this->belongsTo(File::class, 'banner_image_id', 'id');
    }

    public function setBannerImageUploadAttribute($value)
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

            $this->banner_image_id = $file->id;
        } elseif (empty($value)) {
            $this->banner_image_id = null;
        }
    }
}
