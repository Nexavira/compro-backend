<?php

namespace App\Policies\System;

use App\Models\Auth\User;
use App\Policies\BasePolicy;

class ApiKeyPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('admin_system_api_key_view');
    }

    public function view(User $user): bool
    { 
        return $user->can('admin_system_api_key_view');
    }

    public function create(User $user): bool  
    { 
        return $user->can('admin_system_api_key_create'); 
    }
    
    public function update(User $user, ApiKeyPolicy $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_system_api_key_edit');
    }
    
    public function delete(User $user, ApiKeyPolicy $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_system_api_key_delete');
    }
}