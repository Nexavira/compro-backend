<?php

namespace App\Models\Auth;

use App\Models\Tenant\Tenant;
use App\Traits\Blameable;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

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
        'version'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime:U',
            'updated_at' => 'datetime:U',
            'deleted_at' => 'datetime:U',
            'is_active' => 'boolean',
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

    public function getTenants(Panel $panel): array|Collection
    {
        if ($this->roleUser && $this->roleUser->role_id == 1) {
            return Tenant::all();
        }

        if ($this->userDetail && $this->userDetail->tenant) {
            return collect([$this->userDetail->tenant]);
        }

        return collect();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->roleUser && $this->roleUser->role_id == 1) {
            return true;
        }

        return $this->userDetail && $this->userDetail->tenant_id == $tenant->id;
    }
}
