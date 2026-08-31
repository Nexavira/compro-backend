<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\TenantTemplate\GetTenantTemplateService;
use App\Services\TenantTemplate\StoreTenantTemplateService;
use App\Services\TenantTemplate\UpdateTenantTemplateService;

class RegisterTenantTemplateService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('GetTenantTemplateService', GetTenantTemplateService::class);
        $this->registerService('StoreTenantTemplateService', StoreTenantTemplateService::class);
        $this->registerService('UpdateTenantTemplateService', UpdateTenantTemplateService::class);
    }
}
