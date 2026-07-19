<?php

use App\Mail\WelcomeEmail;
use App\Mail\WelcomeTenantMail;
use App\Models\Auth\User;
use App\Models\Tenant\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

Route::get('/simulator-frontend', function () {

    $apiKey = request('api_key');
    $slug   = request('slug', 'beranda'); 

    if (!$apiKey) {
        return "<h3>Simulasi Frontend Headless</h3><p>Silakan masukkan API Key di URL. Contoh: <code>/simulator-frontend?api_key=nxv_xxxxxx&slug=beranda</code></p>";
    }

    $response = Http::withHeaders([
        'X-Tenant-API-Key' => $apiKey,
    ])->get("http://127.0.0.1:8000/api/v1/pages/{$slug}");

    if ($response->failed()) {
        return "Error dari API Backend: " . $response->body();
    }

    $pageData = $response->json('data');

    return view('frontend-simulator', ['page' => $pageData]);
});

Route::get('/test-email', function () {
    $emailTujuan = 'nexavira26@gmail.com';

    $tenant = Tenant::first() ?? new Tenant(['name' => 'Tenant Demo', 'code' => 'DEMO']);
    $user = User::first() ?? new User(['email' => $emailTujuan]);

    if (!$user->name) {
        $user->name = 'Admin Demo';
    }

    $resetUrl = url('/admin/password-reset/mock-token?email=' . urlencode($emailTujuan));

    Mail::to($emailTujuan)->send(new WelcomeEmail($tenant, $user, $resetUrl));

    return "Cek inbox/spam! Email sedang dikirim ke {$emailTujuan} (melalui queue)";
});

Route::get('/test-email-tenant', function () {
    $emailTujuan = 'nexavira26@gmail.com';

    $tenant = Tenant::first() ?? new Tenant(['name' => 'Tenant Demo', 'code' => 'DEMO']);
    $user = User::first() ?? new User(['email' => $emailTujuan]);

    if (!$user->name) {
        $user->name = 'Admin Demo';
    }

    $resetUrl = url('/admin/password-reset/mock-token?email=' . urlencode($emailTujuan));

    Mail::to($emailTujuan)->send(new WelcomeTenantMail($tenant, $user, $resetUrl));

    return "Cek inbox/spam! Email onboarding tenant berhasil dikirim ke {$emailTujuan} secara langsung (synchronous)";
});

Route::get('/admin/password-reset/{token}', function (Request $request, $token) {
    $email = $request->query('email');

    if (!$email) {
        abort(404, 'Email parameter required.');
    }

    return view('auth.reset-password', [
        'token' => $token,
        'email' => $email
    ]);
})->name('password.reset');

Route::post('/admin/password-reset', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ], [
        'password.required' => 'Password baru wajib diisi.',
        'password.min' => 'Password minimal harus 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak terdaftar di sistem kami.']);
    }

    $isMockToken = ($request->token === 'mock-token' && app()->environment('local', 'testing'));

    if (!$isMockToken && !Password::broker()->tokenExists($user, $request->token)) {
        return back()->withErrors(['token' => 'Token reset password tidak valid atau sudah kedaluwarsa. Silakan minta link reset baru.']);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    if (!$isMockToken) {
        Password::broker()->deleteToken($user);
    }

    return back()->with('success', 'Password Anda berhasil diperbarui!');
});
