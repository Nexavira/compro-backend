<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Tenant\AddTenantUserService;
use App\Services\Tenant\CreateTenantService;
use App\Services\Tenant\GetTenantService;
use App\Services\Tenant\StoreTenantService;
use App\Services\Tenant\TenantCategory\GetTenantCategoryService;
use App\Services\Tenant\Cms\PublicGetTenantPageService;

class RegisterTenantService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('GetTenantService', GetTenantService::class);
        $this->registerService('StoreTenantService', StoreTenantService::class);
        $this->registerService('CreateTenantService', CreateTenantService::class);
        $this->registerService('AddTenantUserService', AddTenantUserService::class);
        $this->registerService('GetTenantCategoryService', GetTenantCategoryService::class);
        $this->registerService('PublicGetTenantPageService', PublicGetTenantPageService::class);
    }
}
