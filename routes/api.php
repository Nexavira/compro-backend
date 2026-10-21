<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    require __DIR__ . '/api/v1/dashboard/index.php';
    require __DIR__ . '/api/v1/portal/index.php';
    require __DIR__ . '/api/v1/tenant/index.php';
    
});
