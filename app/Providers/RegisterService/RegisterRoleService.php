<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Auth\Role\DeleteRoleService;
use App\Services\Auth\Role\GetRoleService;
use App\Services\Auth\Role\StoreRoleService;
use App\Services\Auth\Role\UpdateRoleService;
use App\Services\Auth\RoleUser\AddRoleUserService;
use App\Services\Auth\RoleUser\RemoveRoleUserService;

class RegisterRoleService extends AppServiceProvider
{
    public function register(): void
    {

        $this->registerService('StoreRoleService', StoreRoleService::class);
        $this->registerService('UpdateRoleService', UpdateRoleService::class);
        $this->registerService('DeleteRoleService', DeleteRoleService::class);
        $this->registerService('GetRoleService', GetRoleService::class);

        $this->registerService('AddRoleUserService', AddRoleUserService::class);
        $this->registerService('RemoveRoleUserService', RemoveRoleUserService::class);
    }
}
