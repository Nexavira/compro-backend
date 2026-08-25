<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'permission'], function () {
    Route::get('', [App\Http\Controllers\Api\V1\Dashboard\Auth\PermissionController::class, 'get']);
    Route::post('update-role-permission', [App\Http\Controllers\Api\V1\Dashboard\Auth\PermissionController::class, 'updateRolePermission']);
});