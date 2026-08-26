<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Subscription\StoreSubscriptionService;

class RegisterSubscriptionService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('StoreSubscriptionService', StoreSubscriptionService::class);
    }
}
