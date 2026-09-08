<?php

use App\Http\Controllers\Api\V1\Tenant\Cms\TenantPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('t')->group(function () {
    Route::prefix('{tenant_slug}')->group(function () {
        Route::prefix('cms')->group(function () {
            Route::prefix('page')->group(function () {
                Route::get('{slug}', [TenantPageController::class, 'get']);
            });
        });
    });
});
