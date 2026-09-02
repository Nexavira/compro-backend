<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\Auth\SendOtpService;
use App\Services\Auth\VerifyOtpService;
use Illuminate\Support\Facades\Cache;

$email = 'dimasaung@gmail.com';
$type = 'forgot_password';

// 1. Send OTP
echo "Simulating SendOtpService...\n";
$sendDto = [
    'email' => $email,
    'type' => $type
];

// mock User to avoid database errors if not found
$user = \App\Models\Auth\User::where('email', $email)->first();
if (!$user) {
    echo "User not found, but continuing for cache test...\n";
    // We will just put it in cache manually as if the service did it
    $cacheKey = "otp_{$type}_{$email}";
    $otpCode = '112233';
    Cache::put($cacheKey, $otpCode, now()->addMinutes(10));
    echo "Manually put OTP $otpCode in cache key $cacheKey\n";
} else {
    $sendService = app('SendOtpService');
    $sendService->execute($sendDto);
    $cacheKey = "otp_{$type}_{$email}";
    $otpCode = Cache::get($cacheKey);
    echo "SendOtpService executed. OTP in cache: $otpCode\n";
}

// 2. Verify OTP
echo "\nSimulating VerifyOtpService...\n";
$verifyDto = [
    'email' => $email,
    'type' => $type,
    'otp_code' => $otpCode
];

$verifyService = app('VerifyOtpService');
$result = $verifyService->execute($verifyDto);
echo "Verify result: \n";
print_r($result);
