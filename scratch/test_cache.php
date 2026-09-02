<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Cache;

$email = 'dimasaung@gmail.com';
$type = 'forgot_password';
$cacheKey = "otp_{$type}_{$email}";

$otpCode = (string) rand(100000, 999999);
echo "Generated OTP: {$otpCode}\n";
Cache::put($cacheKey, $otpCode, now()->addMinutes(10));

$cachedData = Cache::get($cacheKey);
$storedOtp = $type === 'register' ? $cachedData['otp'] : $cachedData;

echo "Stored OTP: {$storedOtp}\n";
if ((string)$storedOtp !== $otpCode) {
    echo "MISMATCH!\n";
} else {
    echo "MATCH!\n";
}
