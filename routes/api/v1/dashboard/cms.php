<?php

use App\Http\Controllers\Api\V1\Dashboard\Cms\TenantTemplateController;
use App\Http\Controllers\Api\V1\Dashboard\Cms\TenantTemplatePageController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::prefix('cms')->group(function () {
        Route::prefix('tenant-template')->group(function () {
            Route::get('{tenant_template_uuid?}', [TenantTemplateController::class, 'get']);
            Route::post('create', [TenantTemplateController::class, 'create']);
            Route::patch('update', [TenantTemplateController::class, 'update']);
        });

        Route::prefix('tenant-template-page')->group(function () {
            Route::get('{tenant_template_page_uuid?}', [TenantTemplatePageController::class, 'get']);
            Route::post('create', [TenantTemplatePageController::class, 'create']);
            Route::patch('update', [TenantTemplatePageController::class, 'update']);
        });
    });
});
