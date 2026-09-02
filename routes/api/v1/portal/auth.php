<?php

use App\Http\Controllers\Api\V1\Portal\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('do-logout', [AuthController::class, 'doLogout']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::get('user-session-information', [AuthController::class, 'getUserSessionInformation']);
});
