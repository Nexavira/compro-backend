<?php

namespace App\Models\Auth;

use App\Models\BaseModel;

class Role extends BaseModel
{
    protected $table = 'auth_roles';

    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class, 'role_id', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class, 
            'auth_permission_role', 
            'role_id', 
            'permission_id'
        ); 
    }
}
