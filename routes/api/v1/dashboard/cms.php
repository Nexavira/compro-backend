<?php

use App\Http\Controllers\Api\V1\Dashboard\Cms\TenantTemplateController;
use App\Http\Controllers\Api\V1\Dashboard\Cms\TenantPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::prefix('cms')->group(function () {
        Route::prefix('tenant-template')->group(function () {
            Route::get('{tenant_template_uuid?}', [TenantTemplateController::class, 'get']);
            Route::post('create', [TenantTemplateController::class, 'create']);
            Route::patch('update', [TenantTemplateController::class, 'update']);
        });

        Route::prefix('tenant-page')->group(function () {
            Route::get('{tenant_page_uuid?}', [TenantPageController::class, 'get']);
            Route::post('create', [TenantPageController::class, 'create']);
            Route::patch('update', [TenantPageController::class, 'update']);
        });
    });
});
