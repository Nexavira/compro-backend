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

        $cacheKey = "otp_reset_password_verified_{$user->email}";
        $isVerified = Cache::get($cacheKey);

        if (!$isVerified) {
            $this->results['error'] = true;
            $this->results['response_code'] = 400;
            $this->results['message'] = 'Sesi verifikasi telah kedaluwarsa atau Anda belum memverifikasi OTP. Silakan mulai ulang proses.';
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
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8']
        ];
    }
}
