<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TenantHandlerApi;
use App\Models\CMS\Page;

Route::post('do-login', [App\Http\Controllers\API\Auth\AuthController::class, 'doLogin']);

// Tenant Profiles (Custom Domains & Subdomains)
Route::middleware([TenantHandlerApi::class])->group(function () {
    Route::get('/check', function () {
        $tenant = app('tenant');
        return "Welcome to the profile of: " . $tenant->name;
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    require __DIR__ . '/api/auth/auth.php';
    require __DIR__ . '/api/auth/role.php';
    require __DIR__ . '/api/auth/permission.php';
    require __DIR__ . '/api/system/file.php';
    require __DIR__ . '/api/tenant/tenant.php';
});


Route::middleware(['tenant.api'])->group(function () {
    
    // Endpoint: GET /api/v1/pages/{slug}
    Route::get('/v1/pages/{slug}', function (Request $request, $slug) {
        
        // Ambil halaman HANYA milik tenant yang API Key-nya lolos pengecekan
        $page = Page::where('tenant_id', $request->current_tenant_id)
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->first();

        if (!$page) {
            return response()->json(['error' => 'Halaman tidak ditemukan'], 404);
        }

        // Kembalikan data dalam format JSON murni
        return response()->json([
            'success' => true,
            'data'    => [
                'title'  => $page->title,
                'slug'   => $page->slug,
                // Di sinilah JSON struktur Lego dari Filament akan dikirimkan
                'blocks' => $page->content_blocks, 
            ]
        ]);
    });

});