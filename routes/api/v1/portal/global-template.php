<?php

use App\Http\Controllers\Api\V1\Portal\CMS\GlobalTemplateController;
use Illuminate\Support\Facades\Route;

Route::prefix('cms')->group(function () {
    Route::prefix('global-template')->group(function () {
        Route::post('', [GlobalTemplateController::class, 'create']);
    });
});