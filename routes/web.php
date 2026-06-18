<?php

use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
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

Route::get('/test-email', function () {
    $emailTujuan = 'nexavira26@gmail.com';

    $tenant = \App\Models\Tenant\Tenant::first() ?? new \App\Models\Tenant\Tenant(['name' => 'Tenant Demo', 'code' => 'DEMO']);
    $user = \App\Models\Auth\User::first() ?? new \App\Models\Auth\User(['email' => $emailTujuan]);
    
    if (!$user->name) {
        $user->name = 'Admin Demo';
    }

    $resetUrl = url('/admin/password-reset/mock-token?email=' . urlencode($emailTujuan));

    Mail::to($emailTujuan)->send(new WelcomeEmail($tenant, $user, $resetUrl));

    return "Cek inbox/spam! Email sedang dikirim ke {$emailTujuan} (melalui queue)";
});

Route::get('/test-email-tenant', function () {
    $emailTujuan = 'nexavira26@gmail.com';

    // Ambil data pertama jika ada, atau buat instance dummy untuk testing
    $tenant = \App\Models\Tenant\Tenant::first() ?? new \App\Models\Tenant\Tenant(['name' => 'Tenant Demo', 'code' => 'DEMO']);
    $user = \App\Models\Auth\User::first() ?? new \App\Models\Auth\User(['email' => $emailTujuan]);

    // Set default nama jika tidak ada
    if (!$user->name) {
        $user->name = 'Admin Demo';
    }

    $resetUrl = url('/admin/password-reset/mock-token?email=' . urlencode($emailTujuan));

    // WelcomeTenantMail tidak mengimplementasikan ShouldQueue, sehingga dikirim langsung secara synchronous
    Mail::to($emailTujuan)->send(new \App\Mail\WelcomeTenantMail($tenant, $user, $resetUrl));

    return "Cek inbox/spam! Email onboarding tenant berhasil dikirim ke {$emailTujuan} secara langsung (synchronous)";
});

// GET: Tampilkan Form Reset Password
Route::get('/admin/password-reset/{token}', function (\Illuminate\Http\Request $request, $token) {
    $email = $request->query('email');
    
    if (!$email) {
        abort(404, 'Email parameter required.');
    }

    return view('auth.reset-password', [
        'token' => $token,
        'email' => $email
    ]);
})->name('password.reset');

// POST: Proses Reset Password langsung update ke DB
Route::post('/admin/password-reset', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ], [
        'password.required' => 'Password baru wajib diisi.',
        'password.min' => 'Password minimal harus 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    $user = \App\Models\Auth\User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak terdaftar di sistem kami.']);
    }

    // Periksa validitas token reset password
    // Ijinkan 'mock-token' di local environment agar mempermudah testing email
    $isMockToken = ($request->token === 'mock-token' && app()->environment('local', 'testing'));

    if (!$isMockToken && !\Illuminate\Support\Facades\Password::broker()->tokenExists($user, $request->token)) {
        return back()->withErrors(['token' => 'Token reset password tidak valid atau sudah kedaluwarsa. Silakan minta link reset baru.']);
    }

    // Update password user
    $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
    $user->save();

    // Hapus token jika bukan mock
    if (!$isMockToken) {
        \Illuminate\Support\Facades\Password::broker()->deleteToken($user);
    }

    return back()->with('success', 'Password Anda berhasil diperbarui!');
});
