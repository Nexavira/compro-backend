<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\System\FileController;

Route::prefix('system')->group(function () {
    Route::post('file/upload', [FileController::class, 'upload']);
});
