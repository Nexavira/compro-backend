<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/simulator-frontend', function () {
    // Tangkap parameter dari URL browser
    $apiKey = request('api_key');
    $slug   = request('slug', 'beranda'); // default cari slug 'beranda'

    if (!$apiKey) {
        return "<h3>Simulasi Frontend Headless</h3><p>Silakan masukkan API Key di URL. Contoh: <code>/simulator-frontend?api_key=nxv_xxxxxx&slug=beranda</code></p>";
    }

    // ✨ INI ADALAH SIMULASI FETCH() DI NUXT.JS / NEXT.JS
    // Frontend melakukan request HTTP ke Backend dengan membawa Header Rahasia
    $response = Http::withHeaders([
        'X-Tenant-API-Key' => $apiKey,
    ])->get("http://127.0.0.1:8000/api/v1/pages/{$slug}");

    if ($response->failed()) {
        return "Error dari API Backend: " . $response->body();
    }

    // Ambil data JSON hasil respon API
    $pageData = $response->json('data');

    // Lempar JSON tersebut ke file Blade untuk di-render menjadi visual
    return view('frontend-simulator', ['page' => $pageData]);
});