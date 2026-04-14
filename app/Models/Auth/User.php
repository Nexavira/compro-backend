<?php

namespace App\Models\Auth;

use App\Traits\Blameable;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, Blameable, HasUuids;

    protected $table = 'auth_users';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    protected $with = ['roleUser.role.permissions'];

    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'created_by',
        'updated_by',
        'deleted_by',
        'updated_at',
        'deleted_at',
        'is_active',
        'version'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime:U',
            'updated_at' => 'datetime:U',
            'deleted_at' => 'datetime:U',
        ];
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'id');
    }

    public function roleUser()
    {
        return $this->hasOne(RoleUser::class, 'user_id', 'id');
    }

    public function getNameAttribute()
    {
        return "{$this->userDetail?->full_name}";
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (! $this->photo_id) {
            return null;
        }

        return Storage::disk('public')->url($this->photo_id);
    }
}
