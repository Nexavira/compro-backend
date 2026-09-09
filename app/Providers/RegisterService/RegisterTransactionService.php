<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\Transaction\Payment\StorePaymentService;
use App\Services\Transaction\Payment\GetPaymentService;
use App\Services\Transaction\Payment\UploadPaymentProofService;
use App\Services\Transaction\Subscription\StoreSubscriptionService;
use App\Services\Transaction\Subscription\GetSubscriptionService;

class RegisterTransactionService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('GetSubscriptionService', GetSubscriptionService::class);
        $this->registerService('StoreSubscriptionService', StoreSubscriptionService::class);
        
        $this->registerService('GetPaymentService', GetPaymentService::class);
        $this->registerService('StorePaymentService', StorePaymentService::class);


        $this->registerService('UploadPaymentProofService', UploadPaymentProofService::class);
    }
}
