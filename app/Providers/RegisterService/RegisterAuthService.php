<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;

class RegisterAuthService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('DoLoginService', \App\Services\AuthService\DoLoginService::class);
        $this->registerService('DoLogoutService', \App\Services\AuthService\DoLogoutService::class);
    }
}