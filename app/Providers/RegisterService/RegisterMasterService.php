<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Master\Package\GetPackageService;

class RegisterMasterService extends AppServiceProvider
{
    public function register(): void
    {
        // Package
        // $this->registerService('GetPackageService', GetPackageService::class);
    }
}
