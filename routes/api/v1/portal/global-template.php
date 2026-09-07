<?php

use App\Http\Controllers\Api\V1\Portal\Cms\GlobalTemplateController;
use Illuminate\Support\Facades\Route;


Route::prefix('global-template')->group(function () {
    Route::post('', [GlobalTemplateController::class, 'create']);
});
