<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Auth\Permission\GetPermissionService;
use App\Services\Auth\PermissionRole\AddPermissionRoleService;
use App\Services\Auth\PermissionRole\RemovePermissionRoleService;
use App\Services\Auth\PermissionRole\UpdatePermissionRoleService;

class RegisterPermissionService extends AppServiceProvider
{
    public function register(): void
    {

        $this->registerService('GetPermissionService', GetPermissionService::class);

        $this->registerService('AddPermissionRoleService', AddPermissionRoleService::class);
        $this->registerService('RemovePermissionRoleService', RemovePermissionRoleService::class);
        $this->registerService('UpdatePermissionRoleService', UpdatePermissionRoleService::class);
    }
}
