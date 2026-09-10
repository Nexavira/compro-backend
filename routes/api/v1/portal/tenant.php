<?php

use App\Http\Controllers\Api\V1\Portal\Tenant\TenantController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\V1\Portal\Tenant\TenantCategoryController;

Route::prefix('tenant')->group(function () {
    Route::get('/categories', [TenantCategoryController::class, 'index']);
    Route::put('/{tenant_uuid}/information', [TenantController::class, 'editTenantInformation']);
    Route::post('', [TenantController::class, 'create']);
});
