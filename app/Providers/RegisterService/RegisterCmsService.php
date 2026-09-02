<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Cms\GlobalTemplate\GetGlobalTemplateService;
use App\Services\Cms\GlobalTemplate\StoreGlobalTemplateService;

class RegisterCmsService extends AppServiceProvider
{
    public function register(): void
    {
        // Global Template
        $this->registerService('GetGlobalTemplateService', GetGlobalTemplateService::class);
        $this->registerService('StoreGlobalTemplateService', StoreGlobalTemplateService::class);
    }
}
