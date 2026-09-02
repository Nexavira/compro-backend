<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Portal\System\FileController;

Route::prefix('file')->group(function () {
    Route::prefix('file')->group(function () {
        Route::post('upload', [FileController::class, 'upload']);
        Route::get('{file_uuid?}', [FileController::class, 'get']);
    });
});
