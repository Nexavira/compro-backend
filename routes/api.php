<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('do-login', [App\Http\Controllers\API\Auth\AuthController::class, 'doLogin']);

Route::group(['middleware' => 'auth:api'], function () {
    require __DIR__ . '/api/auth/auth.php';
    require __DIR__ . '/api/auth/role.php';
    require __DIR__ . '/api/auth/permission.php';
});
