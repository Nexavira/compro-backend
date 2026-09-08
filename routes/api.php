<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    require __DIR__ . '/api/v1/dashboard/index.php';
    require __DIR__ . '/api/v1/portal/index.php';
    require __DIR__ . '/api/v1/tenant/index.php';

    // Route::group(['middleware' => 'auth:api'], function () {
    //     require __DIR__ . '/api/v1/dashboard/auth/auth.php';
    //     require __DIR__ . '/api/v1/dashboard/auth/role.php';
    //     require __DIR__ . '/api/v1/dashboard/auth/permission.php';
    //     require __DIR__ . '/api/v1/dashboard/system/file.php';
    //     require __DIR__ . '/api/v1/dashboard/tenant/tenant.php';
    // });



    // Route::get('/templates', [App\Http\Controllers\Api\V1\Portal\Cms\TemplateController::class, 'index']);
    // Route::get('/templates/{slug}', [App\Http\Controllers\Api\V1\Portal\Cms\TemplateController::class, 'show']);

    Route::get('/global-templates', [App\Http\Controllers\Api\V1\Portal\Cms\GlobalTemplateController::class, 'get']);

    Route::group(['prefix' => 't/{tenant_slug}'], function () {
        Route::get('/pages', [App\Http\Controllers\Api\V1\Tenant\Cms\PageController::class, 'index']);
        Route::get('/pages/{slug}', [App\Http\Controllers\Api\V1\Tenant\Cms\PageController::class, 'show']);

        Route::get('/posts', [App\Http\Controllers\Api\V1\Tenant\Cms\PostController::class, 'index']);
        Route::get('/posts/{slug}', [App\Http\Controllers\Api\V1\Tenant\Cms\PostController::class, 'show']);
    });
});
