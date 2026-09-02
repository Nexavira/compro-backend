<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Transaction\Payment\StorePaymentService;
use App\Services\Transaction\Payment\UploadPaymentProofService;
use App\Services\Transaction\Subscription\StoreSubscriptionService;

class RegisterTransactionService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('StoreSubscriptionService', StoreSubscriptionService::class);
        $this->registerService('StorePaymentService', StorePaymentService::class);


        $this->registerService('UploadPaymentProofService', UploadPaymentProofService::class);
    }
}
