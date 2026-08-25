<?php

namespace App\Services\Auth\UserService;

use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpEmail;
use App\Models\Auth\User;
use App\Models\Auth\DetailUser;
use App\Rules\UniqueData;

class RegisterUserService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $otpCode = (string) rand(100000, 999999);

        $cacheData = [
            'dto' => $dto,
            'otp' => $otpCode
        ];
        Cache::put("otp_register_{$dto['email']}", $cacheData, now()->addMinutes(10));

        Mail::to('nexavira26@gmail.com')->send(new SendOtpEmail($dto['full_name'] ?? 'User', $otpCode));

        $this->results['data'] = [
            'email' => $dto['email']
        ];
        $this->results['message'] = 'Registrasi berhasil. Silakan cek email Anda untuk kode OTP.';
    }

    public function rules($dto)
    {
        return [
            'email' => ['required', 'email', new UniqueData(new User)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', new UniqueData(new DetailUser)]
        ];
    }
}
