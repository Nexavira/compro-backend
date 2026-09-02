<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Auth\DetailUser\StoreDetailUserService;
use App\Services\Auth\DoLoginService;
use App\Services\Auth\DoLogoutService;
use App\Services\Auth\ForgotPasswordService;
use App\Services\Auth\GetUserSessionInformationService;
use App\Services\Auth\Permission\GetPermissionService;
use App\Services\Auth\PermissionRole\AddPermissionRoleService;
use App\Services\Auth\PermissionRole\RemovePermissionRoleService;
use App\Services\Auth\PermissionRole\UpdatePermissionRoleService;
use App\Services\Auth\RegisterUserService;
use App\Services\Auth\SendOtpService;
use App\Services\Auth\ResetPasswordService;
use App\Services\Auth\Role\DeleteRoleService;
use App\Services\Auth\Role\GetRoleService;
use App\Services\Auth\Role\StoreRoleService;
use App\Services\Auth\Role\UpdateRoleService;
use App\Services\Auth\RoleUser\AddRoleUserService;
use App\Services\Auth\RoleUser\RemoveRoleUserService;
use App\Services\Auth\User\StoreUserService;
use App\Services\Auth\VerifyOtpService;

class RegisterAuthService extends AppServiceProvider
{
    public function register(): void
    {
        // Auth
        $this->registerService('DoLoginService', DoLoginService::class);
        $this->registerService('DoLogoutService', DoLogoutService::class);
        $this->registerService('GetUserSessionInformationService', GetUserSessionInformationService::class);

        // Register
        $this->registerService('RegisterUserService', RegisterUserService::class);
        $this->registerService('VerifyOtpService', VerifyOtpService::class);
        $this->registerService('ForgotPasswordService', ForgotPasswordService::class);
        $this->registerService('SendOtpService', SendOtpService::class);
        $this->registerService('ResetPasswordService', ResetPasswordService::class);

        // Role
        $this->registerService('StoreRoleService', StoreRoleService::class);
        $this->registerService('UpdateRoleService', UpdateRoleService::class);
        $this->registerService('DeleteRoleService', DeleteRoleService::class);
        $this->registerService('GetRoleService', GetRoleService::class);

        // User
        $this->registerService('StoreUserService', StoreUserService::class);

        // Detail User
        $this->registerService('StoreDetailUserService', StoreDetailUserService::class);

        // Role User
        $this->registerService('AddRoleUserService', AddRoleUserService::class);
        $this->registerService('RemoveRoleUserService', RemoveRoleUserService::class);

        // Permission
        $this->registerService('GetPermissionService', GetPermissionService::class);


        // Permission Role
        $this->registerService('AddPermissionRoleService', AddPermissionRoleService::class);
        $this->registerService('RemovePermissionRoleService', RemovePermissionRoleService::class);
        $this->registerService('UpdatePermissionRoleService', UpdatePermissionRoleService::class);
    }
}
