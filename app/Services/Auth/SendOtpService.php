<?php

namespace App\Services\Auth;

use App\Models\Auth\User;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpEmail;

class SendOtpService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {

        $type = $dto['type'];
        $email = $dto['email'] ?? null;

        if ($type === 'reset_password') {
            $user = auth('api')->user();
            if (!$user) {
                $this->results['error'] = true;
                $this->results['response_code'] = 401;
                $this->results['message'] = 'Unauthorized.';
                return;
            }
            $email = $user->email;
        }

        if (!$email) {
            $this->results['error'] = true;
            $this->results['response_code'] = 400;
            $this->results['message'] = 'Email dibutuhkan.';
            return;
        }

        $cacheKey = "otp_{$type}_{$email}";
        $cachedData = Cache::get($cacheKey);

        $otpCode = (string) rand(100000, 999999);
        $name = 'User';

        if ($type === 'register') {
            if (!$cachedData || !isset($cachedData['dto'])) {
                $this->results['error'] = true;
                $this->results['response_code'] = 400;
                $this->results['message'] = 'Sesi registrasi tidak ditemukan atau sudah kedaluwarsa. Silakan mendaftar ulang.';
                return;
            }

            $cacheData = [
                'dto' => $cachedData['dto'],
                'otp' => $otpCode
            ];
            Cache::put($cacheKey, $cacheData, now()->addMinutes(10));
            $name = $cachedData['dto']['full_name'] ?? 'User';
        } else if ($type === 'forgot_password') {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->results['error'] = true;
                $this->results['response_code'] = 404;
                $this->results['message'] = 'Pengguna dengan email ini tidak ditemukan.';
                return;
            }
            Cache::put($cacheKey, $otpCode, now()->addMinutes(10));
            $name = $user->name ?? 'User';
        } else if ($type === 'reset_password') {
            Cache::put($cacheKey, $otpCode, now()->addMinutes(10));
            $name = auth('api')->user()->name ?? 'User';
        }

        Mail::to('nexavira26@gmail.com')->send(new SendOtpEmail($name, $otpCode));

        $this->results['data'] = [
            'email' => $email,
            'type' => $type
        ];
        $this->results['message'] = 'Kode OTP baru telah dikirim ke email Anda.';
    }

    public function rules($dto)
    {
        return [
            'email' => ['required_unless:type,reset_password', 'email'],
            'type' => ['required', 'string', 'in:register,forgot_password,reset_password']
        ];
    }
}
