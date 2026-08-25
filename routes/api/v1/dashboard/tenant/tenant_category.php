<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'category'], function () {
    Route::get('{tenant_category_uuid?}', [App\Http\Controllers\Api\V1\Dashboard\Tenant\TenantCategoryController::class, 'get']);
});