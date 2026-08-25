<?php

namespace App\Http\Controllers\Api\V1\Portal\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Auth\RegisterUserRequest;
use App\Http\Requests\Api\V1\Portal\Auth\VerifyOtpRequest;
use App\Mail\SendOtpEmail;
use App\Models\Auth\User;
use App\Models\Auth\UserDetail;
use App\Models\System\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $result = app('StoreDetailUserService')->execute($request->all());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['response_code']);
        // try {
        //     DB::beginTransaction();

        //     $otpCode = (string) rand(100000, 999999);

        //     $user = User::create([
        //         'uuid' => Str::uuid(),
        //         'email' => $request->email,
        //         'password' => Hash::make($request->password),
        //         'email_verified_at' => null,
        //         'is_active' => 0, // Inactive until OTP is verified
        //     ]);

        //     UserDetail::create([
        //         'uuid' => Str::uuid(),
        //         'user_id' => $user->id,
        //         'full_name' => $request->full_name,
        //         'phone_number' => $request->phone_number,
        //         'is_active' => 1
        //     ]);


        //     Cache::put("otp_register_{$request->email}", $otpCode, now()->addMinutes(10));

        //     ActivityLog::create([
        //         'uuid' => Str::uuid(),
        //         'log_name' => 'OTP_Generated',
        //         'description' => "OTP generated and sent for registration of {$request->email}.",
        //         'subject_id' => $user->id,
        //         'subject_type' => get_class($user),
        //         'properties' => [
        //             'email' => $request->email,
        //             'event' => 'registration_otp',
        //             'expires_at' => now()->addMinutes(10)->toDateTimeString()
        //         ]
        //     ]);

        //     DB::commit();

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Registrasi berhasil. Silakan cek email Anda untuk kode OTP.',
        //         'data' => [
        //             'email' => $user->email
        //         ]
        //     ], 201);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Terjadi kesalahan saat registrasi.',
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
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
