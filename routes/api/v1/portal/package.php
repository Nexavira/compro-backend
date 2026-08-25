<?php

use App\Http\Controllers\Api\V1\Portal\Master\PackageController;
use Illuminate\Support\Facades\Route;

Route::prefix('package')->group(function () {
    Route::get('{package_uuid?}', [PackageController::class, 'get']);
});
