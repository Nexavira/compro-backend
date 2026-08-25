<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    require __DIR__ . '/auth.php';
});

Route::prefix('master')->group(function () {
    require __DIR__ . '/package.php';
});

Route::prefix('tenant')->group(function () {
    require __DIR__ . '/tenant.php';
});
