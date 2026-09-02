<?php

namespace App\Services\Auth;

use App\Models\System\ActivityLog;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $user = auth()->user();

        if (!$user) {
            $this->results['error'] = true;
            $this->results['response_code'] = 401;
            $this->results['message'] = 'Unauthorized.';
            return;
        }

        if (!Hash::check($dto['old_password'], $user->password)) {
            $this->results['error'] = true;
            $this->results['response_code'] = 400;
            $this->results['message'] = 'Password lama yang Anda masukkan salah.';
            return;
        }

        $cacheKey = "otp_reset_password_{$user->email}";
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

        $user->password = Hash::make($dto['password']);
        $this->prepareAuditUpdate($user);
        $user->save();

        ActivityLog::create([
            'uuid' => Str::uuid(),
            'log_name' => 'Password_Reset',
            'description' => "User {$user->email} reset their password successfully from authenticated session.",
            'subject_id' => $user->id,
            'subject_type' => get_class($user),
            'properties' => [
                'email' => $user->email,
                'event' => 'password_reset_success'
            ]
        ]);

        Cache::forget($cacheKey);

        $this->results['data'] = $user;
        $this->results['message'] = 'Password berhasil diubah.';
    }

    public function rules($dto)
    {
        return [
            'otp_code' => ['required', 'string', 'min:6'],
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8']
        ];
    }
}
