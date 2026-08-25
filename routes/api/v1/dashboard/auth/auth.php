<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('session', [App\Http\Controllers\Api\V1\Dashboard\Auth\AuthController::class, 'getUserSessionInformation']);
});
