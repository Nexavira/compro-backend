<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'nexavira26@gmail.com'; // or whatever email they used
$keys = \Illuminate\Support\Facades\Cache::get('otp_forgot_password_' . $email);
var_dump($keys);
