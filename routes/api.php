<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TenantHandlerApi;
use App\Models\CMS\Page;

Route::post('do-login', [App\Http\Controllers\API\Auth\AuthController::class, 'doLogin']);

Route::group(['middleware' => 'auth:api'], function () {
    require __DIR__ . '/api/auth/auth.php';
    require __DIR__ . '/api/auth/role.php';
    require __DIR__ . '/api/auth/permission.php';
    require __DIR__ . '/api/system/file.php';
    require __DIR__ . '/api/tenant/tenant.php';
});

Route::group(['prefix' => 'v1/t/{tenant_slug}'], function () {

    Route::get('/pages', [App\Http\Controllers\API\Cms\PageController::class, 'index']);
    Route::get('/pages/{slug}', [App\Http\Controllers\API\Cms\PageController::class, 'show']);

    Route::get('/posts', [App\Http\Controllers\API\Cms\PostController::class, 'index']);
    Route::get('/posts/{slug}', [App\Http\Controllers\API\Cms\PostController::class, 'show']);
});

Route::get('/v1/templates', [App\Http\Controllers\API\Cms\TemplateController::class, 'index']);
Route::get('/v1/templates/{slug}', [App\Http\Controllers\API\Cms\TemplateController::class, 'show']);

Route::get('/v1/packages', [App\Http\Controllers\API\Master\PackageController::class, 'get']);