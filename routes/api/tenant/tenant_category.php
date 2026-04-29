<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'category'], function () {
    Route::get('{tenant_category_uuid?}', [App\Http\Controllers\API\Tenant\TenantCategoryController::class, 'get']);
});