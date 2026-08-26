<?php

use App\Http\Controllers\Api\V1\Portal\Auth\AuthController;
use App\Http\Controllers\Api\V1\Portal\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Portal\Auth\Role\RoleController;
use App\Http\Controllers\Api\V1\Portal\Master\PackageController;
use App\Http\Controllers\Api\V1\Portal\Tenant\TenantController;
use Illuminate\Support\Facades\Route;

Route::prefix('portal')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('verify-otp', [RegisterController::class, 'verifyOtp']);
        Route::post('do-login', [AuthController::class, 'doLogin']);

        Route::prefix('role')->group(function () {
            Route::get('{role_uuid?}', [RoleController::class, 'get']);
        });
    });

    Route::prefix('master')->group(function () {
        Route::prefix('package')->group(function () {
            Route::get('{package_uuid?}', [PackageController::class, 'get']);
        });
    });

    // Perlu Login
    Route::middleware('auth:api')->group(function () {
        Route::post('tenant', [TenantController::class, 'create']);
        Route::prefix('auth')->group(function () {
            require __DIR__ . '/auth.php';
        });

        Route::prefix('tenant')->group(function () {});

        Route::prefix('system')->group(function () {
            require __DIR__ . '/file.php';
        });
    });
});
