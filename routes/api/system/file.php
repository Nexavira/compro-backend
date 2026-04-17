<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\System\FileController;

Route::prefix('file')->group(function () {
    Route::post('upload', [FileController::class, 'upload']);
});
