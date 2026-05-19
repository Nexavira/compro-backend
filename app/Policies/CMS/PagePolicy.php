<?php

namespace App\Policies\CMS;

use App\Models\Auth\User;
use App\Models\CMS\Page;
use App\Policies\BasePolicy;

class PagePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('admin_cms_page_view');
    }

    public function view(User $user): bool
    { 
        return $user->can('admin_cms_page_view');
    }

    public function create(User $user): bool  
    { 
        return $user->can('admin_cms_page_create'); 
    }
    
    public function update(User $user, Page $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_cms_page_edit');
    }
    
    public function delete(User $user, Page $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_cms_page_delete');
    }
}