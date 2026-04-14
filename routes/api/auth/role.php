<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'role'], function () {
    Route::get('{role_uuid?}', [App\Http\Controllers\API\Auth\RoleController::class, 'get']);
    Route::post('', [App\Http\Controllers\API\Auth\RoleController::class, 'store']);
    Route::put('{role_uuid}', [App\Http\Controllers\API\Auth\RoleController::class, 'update']);
    Route::delete('{role_uuid}', [App\Http\Controllers\API\Auth\RoleController::class, 'destroy']);
});