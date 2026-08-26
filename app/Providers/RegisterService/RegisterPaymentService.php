<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Payment\StorePaymentService;

class RegisterPaymentService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('StorePaymentService', StorePaymentService::class);
    }
}
