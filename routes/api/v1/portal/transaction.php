<?php

use App\Http\Controllers\Api\V1\Portal\Transaction\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('transaction')->group(function () {
    Route::patch('upload-payment-proof/{payment_uuid}', [TransactionController::class, 'uploadPaymentProof']);
});
