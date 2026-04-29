<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\TenantService\GetTenantService;
use App\Services\TenantService\StoreTenantService;

class RegisterTenantService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('GetTenantService', GetTenantService::class);
        $this->registerService('StoreTenantService', StoreTenantService::class);
    }
}
