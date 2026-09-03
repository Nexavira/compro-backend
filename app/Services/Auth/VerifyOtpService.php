<?php

namespace App\Services\Auth;

use App\Models\System\ActivityLog;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VerifyOtpService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $type = $dto['type'];
        $email = $dto['email'] ?? null;

        if ($type === 'reset_password') {
            $user = auth('api')->user();
            if ($user) {
                $email = $user->email;
            }
        }

        if (!$email) {
            $this->results['error'] = true;
            $this->results['response_code'] = 400;
            $this->results['message'] = 'Email dibutuhkan.';
            return;
        }

        $cacheKey = "otp_{$type}_{$email}";
        $cachedData = Cache::get($cacheKey);

        if (!$cachedData) {
            $this->results['error'] = true;
            $this->results['response_code'] = 400;
            $this->results['message'] = 'Kode OTP telah kedaluwarsa atau tidak valid. Silakan minta kode baru.';
            return;
        }

        $storedOtp = $type === 'register' ? $cachedData['otp'] : $cachedData;

        if ((string)$storedOtp !== (string)$dto['otp_code']) {
            $this->results['error'] = true;
            $this->results['response_code'] = 400;
            $this->results['message'] = 'Kode OTP yang Anda masukkan salah.';
            return;
        }

        if ($type === 'register') {
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
        } else {
            Cache::put("otp_{$type}_verified_{$email}", true, now()->addMinutes(15));
            Cache::forget($cacheKey);

            $this->results['data'] = ['email' => $email, 'type' => $type];
            $this->results['message'] = 'Verifikasi OTP berhasil. Silakan masukkan password baru Anda.';
        }
    }

    public function rules($dto)
    {
        return [
            'email' => ['required_unless:type,reset_password', 'email'],
            'otp_code' => ['required', 'string', 'min:6'],
            'type' => ['required', 'string', 'in:register,forgot_password,reset_password']
        ];
    }
}
