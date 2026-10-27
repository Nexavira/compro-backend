<?php

namespace App\Models\Auth;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends BaseModel
{
    protected $table = 'auth_roles';

    public function roleUser(): BelongsTo
    {
        return $this->belongsTo(RoleUser::class, 'role_id', 'id');
    }

    public function rolePermission(): HasMany
    {
        return $this->hasMany(PermissionRole::class, 'role_id', 'id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'auth_permission_role',
            'role_id',
            'permission_id'
        );
    }
}
