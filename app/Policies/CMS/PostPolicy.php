<?php

namespace App\Policies\CMS;

use App\Models\Auth\User;

use App\Models\CMS\Post;

use App\Policies\BasePolicy;

class PostPolicy extends BasePolicy

{

    public function viewAny(User $user): bool

    {

        return $user->can('admin_cms_post_view');

    }

    public function view(User $user): bool

    { 

        return $user->can('admin_cms_post_view');

    }

    public function create(User $user): bool  

    { 

        return $user->can('admin_cms_post_create'); 

    }

    public function update(User $user, Post $model): bool

    { 

        if ($this->isMasterRecord($model)) return false;

        return $user->can('admin_cms_post_edit');

    }

    public function delete(User $user, Post $model): bool

    { 

        if ($this->isMasterRecord($model)) return false;

        return $user->can('admin_cms_post_delete');

    }

}