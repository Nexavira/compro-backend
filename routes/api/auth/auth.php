<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('session', [App\Http\Controllers\API\Auth\AuthController::class, 'getUserSessionInformation']);
});
