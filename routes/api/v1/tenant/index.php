<?php

use App\Http\Controllers\Api\V1\Tenant\Cms\TenantTemplateController;
use App\Http\Controllers\Api\V1\Tenant\Cms\TenantTemplatePageController;
use App\Http\Middleware\VerifyTenantAccess;
use Illuminate\Support\Facades\Route;

Route::prefix('t')->group(function () {
    Route::prefix('{tenant_slug}')
        ->middleware(['auth:api', VerifyTenantAccess::class])
        ->group(function () {
            Route::prefix('cms')->group(function () {
                Route::prefix('page')->group(function () {
                    Route::get('{slug?}', [TenantTemplatePageController::class, 'get']);
                });
                Route::prefix('tenant-template')->group(function () {
                    Route::get('{tenant_template_uuid?}', [TenantTemplateController::class, 'get']);
                });
            });
        });
});
