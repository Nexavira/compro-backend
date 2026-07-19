<?php

namespace App\Policies\AccessControl;

use App\Models\Auth\User;
use App\Policies\BasePolicy;

class UserPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('admin_access_control_user_view');
    }

    public function view(User $user): bool
    { 
        return $user->can('admin_access_control_user_view');
    }

    public function create(User $user): bool  
    { 
        return $user->can('admin_access_control_user_create'); 
    }

    public function update(User $user, User $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_access_control_user_edit');
    }

    public function delete(User $user, User $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_access_control_user_delete');
    }
}