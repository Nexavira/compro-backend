<?php

use App\Http\Controllers\Api\V1\Portal\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('register', [RegisterController::class, 'register']);
Route::post('verify-otp', [RegisterController::class, 'verifyOtp']);
