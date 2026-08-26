<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\TenantCategory\GetTenantCategoryService;

class RegisterTenantCategoryService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('GetTenantCategoryService', GetTenantCategoryService::class);
    }
}
