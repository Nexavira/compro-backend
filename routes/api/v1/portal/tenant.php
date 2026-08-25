<?php

use App\Http\Controllers\Api\V1\Portal\Tenant\TenantController;
use Illuminate\Support\Facades\Route;

Route::post('', [TenantController::class, 'create']);
