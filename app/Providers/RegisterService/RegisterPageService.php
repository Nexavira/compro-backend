<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Page\GetPageService;
use App\Services\Page\StorePageService;
use App\Services\Page\UpdatePageService;

class RegisterPageService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('GetPageService', GetPageService::class);
        $this->registerService('StorePageService', StorePageService::class);
        $this->registerService('UpdatePageService', UpdatePageService::class);
    }
}
