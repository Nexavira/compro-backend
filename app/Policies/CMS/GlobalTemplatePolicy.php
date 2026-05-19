<?php

namespace App\Policies\CMS;

use App\Models\Auth\User;
use App\Models\GlobalTemplate;
use App\Policies\BasePolicy;

class GlobalTemplatePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('admin_cms_global_template_view');
    }

    public function view(User $user): bool
    { 
        return $user->can('admin_cms_global_template_view');
    }

    public function create(User $user): bool  
    { 
        return $user->can('admin_cms_global_template_create'); 
    }
    
    public function update(User $user, GlobalTemplate $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_cms_global_template_edit');
    }
    
    public function delete(User $user, GlobalTemplate $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_cms_global_template_delete');
    }
}