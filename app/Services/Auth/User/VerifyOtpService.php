<?php

namespace App\Services\Auth\User;

use App\Models\System\ActivityLog;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VerifyOtpService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $cacheKey = "otp_register_{$dto['email']}";
        $cachedData = Cache::get($cacheKey);

        if (!$cachedData) {
            $this->results['error'] = true;
            $this->results['response_code'] = 400;
            $this->results['message'] = 'Kode OTP telah kedaluwarsa atau tidak valid. Silakan registrasi ulang.';
            return;
        }

        if ((string)$cachedData['otp'] !== (string)$dto['otp_code']) {
            $this->results['error'] = true;
            $this->results['response_code'] = 400;
            $this->results['message'] = 'Kode OTP yang Anda masukkan salah.';
            return;
        }

        $originalDto = $cachedData['dto'];

        $userService = app('StoreUserService')->execute($originalDto, true);
        if (isset($userService['error'])) {
            $this->results = $userService;
            return;
        }

        $user = $userService['data'];
        $originalDto['user_id'] = $user->id;

        $detailUserService = app('StoreDetailUserService')->execute($originalDto, true);
        if (isset($detailUserService['error'])) {
            $this->results = $detailUserService;
            return;
        }

        $roleUserService = app('AddRoleUserService')->execute([
            'user_uuid' => $user->uuid,
            'role_uuid' => $originalDto['role_uuid']
        ], true);
        if (isset($roleUserService['error'])) {
            $this->results = $roleUserService;
            return;
        }

        $user->email_verified_at = now()->timestamp;

        $this->prepareAuditActive($user);
        $this->prepareAuditInsert($user);
        $user->save();

        ActivityLog::create([
            'uuid' => Str::uuid(),
            'log_name' => 'OTP_Verified',
            'description' => "OTP verified successfully for {$originalDto['email']}. User is now active.",
            'subject_id' => $user->id,
            'subject_type' => get_class($user),
            'properties' => [
                'email' => $originalDto['email'],
                'event' => 'registration_otp_verified'
            ]
        ]);

        Cache::forget($cacheKey);

        $this->results['data'] = $user;
        $this->results['message'] = 'Verifikasi OTP berhasil. Akun Anda telah aktif.';
    }

    public function rules($dto)
    {
        return [
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'string', 'min:6']
        ];
    }
}
