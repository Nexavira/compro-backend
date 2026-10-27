<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\CMS\GlobalTemplate\GetGlobalTemplateService;
use App\Services\CMS\GlobalTemplate\StoreGlobalTemplateService;
use App\Services\CMS\TenantTemplate\ChangeActiveTenantTemplateService;
use App\Services\CMS\TenantTemplate\GetTenantTemplateService;
use App\Services\CMS\TenantTemplate\StoreTenantTemplateService;
use App\Services\CMS\TenantTemplate\UpdateTenantTemplateService;
use App\Services\CMS\TenantTemplate\CreateTenantTemplateService;
use App\Services\CMS\TenantTemplate\EditTenantTemplateService;

use App\Services\CMS\TenantTemplatePage\GetTenantTemplatePageService;
use App\Services\CMS\TenantTemplatePage\StoreTenantTemplatePageService;
use App\Services\CMS\TenantTemplatePage\UpdateTenantTemplatePageService;

class RegisterCMSService extends AppServiceProvider
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
        $this->registerService('ChangeActiveTenantTemplateService', ChangeActiveTenantTemplateService::class);

        // Tenant Page
        $this->registerService('GetTenantTemplatePageService', GetTenantTemplatePageService::class);
        $this->registerService('StoreTenantTemplatePageService', StoreTenantTemplatePageService::class);
        $this->registerService('UpdateTenantTemplatePageService', UpdateTenantTemplatePageService::class);
    }
}
