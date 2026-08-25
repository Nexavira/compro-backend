<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Auth\DetailUserService\StoreDetailUserService;
use App\Services\Auth\UserService\RegisterUserService;
use App\Services\AuthService\DoLoginService;
use App\Services\AuthService\DoLogoutService;
use App\Services\Auth\UserService\StoreUserService;
use App\Services\Auth\UserService\VerifyOtpService;

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
    }
}
