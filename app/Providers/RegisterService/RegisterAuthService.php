<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\AuthService\DoLoginService;
use App\Services\AuthService\DoLogoutService;

class RegisterAuthService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('DoLoginService', DoLoginService::class);
        $this->registerService('DoLogoutService', DoLogoutService::class);
    }
}
