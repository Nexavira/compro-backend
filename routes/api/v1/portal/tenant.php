<?php

use App\Http\Controllers\Api\V1\Portal\Tenant\TenantController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\V1\Portal\Tenant\TenantCategoryController;

Route::prefix('tenant')->group(function () {
    Route::post('', [TenantController::class, 'create']);
    Route::get('/categories', [TenantCategoryController::class, 'index']);
});
