<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Cms\GlobalTemplate\GetGlobalTemplateService;
use App\Services\Cms\GlobalTemplate\StoreGlobalTemplateService;

use App\Services\Cms\TenantTemplate\GetTenantTemplateService;
use App\Services\Cms\TenantTemplate\StoreTenantTemplateService;
use App\Services\Cms\TenantTemplate\UpdateTenantTemplateService;
use App\Services\Cms\TenantTemplate\CreateTenantTemplateService;
use App\Services\Cms\TenantTemplate\EditTenantTemplateService;

use App\Services\Cms\TenantTemplatePage\GetTenantTemplatePageService;
use App\Services\Cms\TenantTemplatePage\StoreTenantTemplatePageService;
use App\Services\Cms\TenantTemplatePage\UpdateTenantTemplatePageService;

class RegisterCmsService extends AppServiceProvider
{
    public function register(): void
    {
        // Global Template
        $this->registerService('GetGlobalTemplateService', GetGlobalTemplateService::class);
        $this->registerService('StoreGlobalTemplateService', StoreGlobalTemplateService::class);

        // Tenant Template
        $this->registerService('GetTenantTemplateService', GetTenantTemplateService::class);
        $this->registerService('StoreTenantTemplateService', StoreTenantTemplateService::class);
        $this->registerService('UpdateTenantTemplateService', UpdateTenantTemplateService::class);
        $this->registerService('CreateTenantTemplateService', CreateTenantTemplateService::class);
        $this->registerService('EditTenantTemplateService', EditTenantTemplateService::class);

        // Tenant Page
        $this->registerService('GetTenantTemplatePageService', GetTenantTemplatePageService::class);
        $this->registerService('StoreTenantTemplatePageService', StoreTenantTemplatePageService::class);
        $this->registerService('UpdateTenantTemplatePageService', UpdateTenantTemplatePageService::class);
    }
}
