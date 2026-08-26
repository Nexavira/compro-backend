<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Package\GetPackageService;

class RegisterPackageService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('GetPackageService', GetPackageService::class);
    }
}
