<?php

namespace App\Policies\Auth;

use App\Models\Auth\User;
use App\Policies\BasePolicy;

class UserPolicy extends BasePolicy
{
    public function view(User $user): bool
    { 
        return $user->can('admin_auth_user_view');
    }

    public function create(User $user): bool  
    { 
        return $user->can('admin_auth_user_create'); 
    }
    
    public function update(User $user, User $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_auth_user_edit');
    }
    
    public function delete(User $user, User $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_auth_user_delete');
    }
}