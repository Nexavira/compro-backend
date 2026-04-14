<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\RoleService\StoreRoleService;
use App\Services\RoleService\UpdateRoleService;
use App\Services\RoleService\DeleteRoleService;
use App\Services\RoleService\GetRoleService;
use App\Services\AuthService\RoleUserService\AddRoleUserService;
use App\Services\AuthService\RoleUserService\RemoveRoleUserService;

class RegisterRoleService extends AppServiceProvider
{
    public function register(): void
    {
        /** Role */
        $this->registerService('StoreRoleService', StoreRoleService::class);
        $this->registerService('UpdateRoleService', UpdateRoleService::class);
        $this->registerService('DeleteRoleService', DeleteRoleService::class);
        $this->registerService('GetRoleService', GetRoleService::class);

        /** Role User */
        $this->registerService('AddRoleUserService', AddRoleUserService::class);
        $this->registerService('RemoveRoleUserService', RemoveRoleUserService::class);
    }
}
