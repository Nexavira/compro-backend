<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\GlobalTemplate\GetGlobalTemplateService;
use App\Services\GlobalTemplate\StoreGlobalTemplateService;

class RegisterGlobalTemplateService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('GetGlobalTemplateService', GetGlobalTemplateService::class);
        $this->registerService('StoreGlobalTemplateService', StoreGlobalTemplateService::class);
    }
}
