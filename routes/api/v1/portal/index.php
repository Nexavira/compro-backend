<?php

use App\Http\Controllers\Api\V1\Portal\Auth\AuthController;
use App\Http\Controllers\Api\V1\Portal\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Portal\Auth\Role\RoleController;
use App\Http\Controllers\Api\V1\Portal\Cms\GlobalTemplateController;
use App\Http\Controllers\Api\V1\Portal\Master\PackageController;
use Illuminate\Support\Facades\Route;

Route::prefix('portal')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('do-login', [AuthController::class, 'doLogin']);
        Route::post('send-otp', [AuthController::class, 'sendOtp']);
        Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

        Route::prefix('role')->group(function () {
            Route::get('{role_uuid?}', [RoleController::class, 'get']);
        });
    });

    Route::prefix('master')->group(function () {
        Route::prefix('package')->group(function () {
            Route::get('{package_uuid?}', [PackageController::class, 'get']);
        });
        Route::prefix('global-template')->group(function () {
            Route::get('{global_template_uuid?}', [GlobalTemplateController::class, 'get']);
        });
    });

    Route::prefix('cms')->group(function () {
        Route::prefix('global-template')->group(function () {
            Route::get('{global_template_uuid?}', [GlobalTemplateController::class, 'get']);
            Route::post('create', [GlobalTemplateController::class, 'create']);
        });

    });

    // Perlu Login
    Route::middleware('auth:api')->group(function () {
        require __DIR__ . '/auth.php';
        require __DIR__ . '/tenant.php';
        require __DIR__ . '/file.php';
        require __DIR__ . '/transaction.php';
        require __DIR__ . '/global-template.php';
    });
});
