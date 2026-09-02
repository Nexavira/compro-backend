<?php

namespace App\Services\Auth;

use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpEmail;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Rules\UniqueData;

class RegisterUserService extends DefaultService implements ServiceInterface
{
    private const CUSTOMER_ROLE_CODE = 'owner';

    public function process($dto)
    {
        $customerRole = Role::query()
            ->where('code', self::CUSTOMER_ROLE_CODE)
            ->where('is_active', true)
            ->first();

        if (!$customerRole) {
            throw new \RuntimeException('Role customer tidak ditemukan.');
        }

        $dto['role_uuid'] = $customerRole->uuid;

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
            // 'password' => ['required', 'string', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string'],
        ];
    }
}
