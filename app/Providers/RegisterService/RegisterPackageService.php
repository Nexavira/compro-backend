<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Master\Package\GetPackageService;
use App\Services\Master\Package\UpdatePackageService;

class RegisterPackageService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('GetPackageService', GetPackageService::class);
        $this->registerService('UpdatePackageService', UpdatePackageService::class);
    }
}
