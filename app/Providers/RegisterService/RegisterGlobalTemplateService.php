<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\GlobalTemplate\GetGlobalTemplateService;

class RegisterGlobalTemplateService extends AppServiceProvider
{
    public function register(): void
    {

        $this->registerService('GetGlobalTemplateService', GetGlobalTemplateService::class);
    }
}
