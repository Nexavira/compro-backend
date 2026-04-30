<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tenant'], function () {
    require __DIR__ . '/tenant_category.php';

    Route::get('{tenant_uuid?}', [App\Http\Controllers\API\Tenant\TenantController::class, 'get']);
    Route::post('', [App\Http\Controllers\API\Tenant\TenantController::class, 'store']);
});