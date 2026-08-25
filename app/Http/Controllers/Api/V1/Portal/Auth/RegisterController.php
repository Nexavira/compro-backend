<?php

namespace App\Http\Controllers\Api\V1\Portal\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Auth\RegisterUserRequest;
use App\Http\Requests\Api\V1\Portal\Auth\VerifyOtpRequest;
use App\Models\Auth\User;
use App\Models\System\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $result = app('RegisterUserService')->execute($request->all());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['response_code']);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.'
            ], 404);
        }

        $cachedOtp = Cache::get("otp_register_{$request->email}");

        if (!$cachedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP telah kadaluarsa atau tidak ditemukan. Silakan minta kode baru.'
            ], 400);
        }

        if ($cachedOtp !== $request->otp_code) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid.'
            ], 400);
        }

        // OTP is valid
        $user->update([
            'email_verified_at' => Carbon::now(),
            'is_active' => 1,
        ]);

        Cache::forget("otp_register_{$request->email}");

        ActivityLog::create([
            'uuid' => Str::uuid(),
            'log_name' => 'OTP_Verified',
            'description' => "OTP verified successfully for {$request->email}.",
            'subject_id' => $user->id,
            'subject_type' => get_class($user),
            'properties' => [
                'email' => $request->email,
                'event' => 'registration_verified'
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil diverifikasi. Silakan login.'
        ]);
    }
}
