<?php

namespace App\Policies\Auth;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Policies\BasePolicy;

class RolePolicy extends BasePolicy
{
    public function view(User $user): bool
    { 
        return $user->can('admin_auth_role_view');
    }

    public function create(User $user): bool  
    { 
        return $user->can('admin_auth_role_create'); 
    }
    
    public function update(User $user, Role $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_auth_role_edit');
    }
    
    public function delete(User $user, Role $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_auth_role_delete');
    }
}