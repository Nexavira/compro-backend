<?php

namespace App\Http\Controllers\Api\V1\Portal\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Auth\RegisterUserRequest;
use App\Http\Requests\Api\V1\Portal\Auth\VerifyOtpRequest;

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
        $result = app('VerifyOtpService')->execute($request->all());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['response_code']);
    }
}
