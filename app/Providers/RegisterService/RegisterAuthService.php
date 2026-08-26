<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Auth\DetailUser\StoreDetailUserService;
use App\Services\Auth\RoleUser\AddRoleUserService;
use App\Services\Auth\User\RegisterUserService;
use App\Services\Auth\User\StoreUserService;
use App\Services\Auth\User\VerifyOtpService;
use App\Services\Auth\DoLoginService;
use App\Services\Auth\DoLogoutService;

class RegisterAuthService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('DoLoginService', DoLoginService::class);
        $this->registerService('DoLogoutService', DoLogoutService::class);

        $this->registerService('StoreUserService', StoreUserService::class);
        $this->registerService('StoreDetailUserService', StoreDetailUserService::class);
        $this->registerService('RegisterUserService', RegisterUserService::class);
        $this->registerService('VerifyOtpService', VerifyOtpService::class);
        $this->registerService('AddRoleUserService', AddRoleUserService::class);
    }
}
