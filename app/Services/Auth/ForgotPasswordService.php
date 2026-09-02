<?php

namespace App\Services\Auth;

use App\Models\Auth\User;
use App\Models\System\ActivityLog;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $cacheKey = "otp_forgot_password_{$dto['email']}";
        $cachedOtp = Cache::get($cacheKey);

        if (!$cachedOtp) {
            $this->results['error'] = true;
            $this->results['response_code'] = 400;
            $this->results['message'] = 'Kode OTP telah kedaluwarsa atau tidak valid. Silakan minta kode baru.';
            return;
        }

        if ((string)$cachedOtp !== (string)$dto['otp_code']) {
            $this->results['error'] = true;
            $this->results['response_code'] = 400;
            $this->results['message'] = 'Kode OTP yang Anda masukkan salah.';
            return;
        }

        $user = User::where('email', $dto['email'])->first();
        if (!$user) {
            $this->results['error'] = true;
            $this->results['response_code'] = 404;
            $this->results['message'] = 'Pengguna tidak ditemukan.';
            return;
        }

        $user->password = Hash::make($dto['password']);
        $this->prepareAuditUpdate($user);
        $user->save();

        ActivityLog::create([
            'uuid' => Str::uuid(),
            'log_name' => 'Password_Forgot',
            'description' => "User {$user->email} reset their password via Forgot Password successfully.",
            'subject_id' => $user->id,
            'subject_type' => get_class($user),
            'properties' => [
                'email' => $user->email,
                'event' => 'password_forgot_success'
            ]
        ]);

        Cache::forget($cacheKey);

        $this->results['data'] = $user;
        $this->results['message'] = 'Password berhasil diubah. Silakan login menggunakan password baru Anda.';
    }

    public function rules($dto)
    {
        return [
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'string', 'min:6'],
            'password' => ['required', 'string', 'min:8']
        ];
    }
}
