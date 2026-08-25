<?php

namespace App\Services\Auth\UserService;

use App\Models\System\ActivityLog;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpEmail;
use Illuminate\Support\Str;

class RegisterUserService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $otpCode = (string) rand(100000, 999999);

        $userService = app('StoreUserService')->execute($dto, true);
        if (isset($userService['error'])) {
            $this->results = $userService;
            return;
        }

        $user = $userService['data'];
        $dto['user_id'] = $user->id;

        $detailUserService = app('StoreDetailUserService')->execute($dto, true);
        if (isset($detailUserService['error'])) {
            $this->results = $detailUserService;
            return;
        }

        Cache::put("otp_register_{$dto['email']}", $otpCode, now()->addMinutes(10));

        ActivityLog::create([
            'uuid' => Str::uuid(),
            'log_name' => 'OTP_Generated',
            'description' => "OTP generated and sent for registration of {$dto['email']}.",
            'subject_id' => $user->id,
            'subject_type' => get_class($user),
            'properties' => [
                'email' => $dto['email'],
                'event' => 'registration_otp',
                'expires_at' => now()->addMinutes(10)->toDateTimeString()
            ]
        ]);

        // Mail::to($dto['email'])->send(new SendOtpEmail($dto['full_name'] ?? 'User', $otpCode));
        Mail::to('nexavira26@gmail.com')->send(new SendOtpEmail($dto['full_name'] ?? 'User', $otpCode));

        $this->results['data'] = $user;
        $this->results['message'] = 'User successfully registered';
    }
}
