<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\AuthService\PermissionRoleService\AddPermissionRoleService;
use App\Services\AuthService\PermissionRoleService\RemovePermissionRoleService;
use App\Services\AuthService\PermissionRoleService\UpdatePermissionRoleService;
use App\Services\AuthService\PermissionService\GetPermissionService;

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
