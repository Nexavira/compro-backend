<?php

use App\Http\Controllers\Api\V1\Portal\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('do-logout', [AuthController::class, 'doLogout']);
Route::get('user-session-information', [AuthController::class, 'getUserSessionInformation']);
