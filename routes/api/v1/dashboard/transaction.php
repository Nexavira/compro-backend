<?php

use App\Http\Controllers\Api\V1\Dashboard\Transaction\PaymentController;
use App\Http\Controllers\Api\V1\Dashboard\Transaction\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::prefix('transaction')->group(function () {
        Route::prefix('subscription')->group(function () {
            Route::get('{tenant_uuid?}', [SubscriptionController::class, 'get']);
        });

        Route::prefix('payment')->group(function () {
            Route::get('{tenant_uuid?}', [PaymentController::class, 'get']);
            Route::patch('update-proof', [PaymentController::class, 'updateProof']);
        });
    });
});
