<?php

use App\Http\Controllers\Api\V1\Portal\Master\PackageController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'master'], function () {
    Route::prefix('package')->group(function () {
        Route::put('/{uuid}', [PackageController::class, 'update']);
    });
});