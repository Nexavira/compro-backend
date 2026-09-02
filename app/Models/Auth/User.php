<?php

namespace App\Models\Auth;

use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantUser;
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
            'email_verified_at' => 'datetime: U',
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

    public function detailUser()
    {
        return $this->hasOne(DetailUser::class, 'user_id', 'id');
    }

    public function roleUser()
    {
        return $this->hasOne(RoleUser::class, 'user_id', 'id');
    }

    public function tenantUser()
    {
        return $this->hasOne(TenantUser::class, 'user_id', 'id');
    }

    public function getNameAttribute()
    {
        return "{$this->detailUser?->full_name}";
    }

    public function getTenants(Panel $panel): array|Collection
    {
        if ($this->roleUser && $this->roleUser->role_id == 1) {
            return Tenant::all();
        }

        $tenantIds = TenantUser::where('user_id', $this->id)->pluck('tenant_id');
        return Tenant::whereIn('id', $tenantIds)->get();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->roleUser && $this->roleUser->role_id == 1) {
            return true;
        }

        return TenantUser::where('user_id', $this->id)
            ->where('tenant_id', $tenant->id)
            ->exists();
    }
}
