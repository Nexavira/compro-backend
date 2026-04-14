<?php

namespace App\Policies;

use App\Models\Auth\Role;
use App\Models\Auth\User;

class BasePolicy
{
    protected function isMasterRecord($model): bool
    {
        if ($model instanceof Role) {
            return $model->code === 'master_admin';
        }

        if ($model instanceof User) {
            return $model->roleUser->role?->code === 'master_admin';
        }

        return false;
    }
}