<?php

namespace App\Http\Controllers\Api\V1\Portal\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Auth\DoLoginRequest;
use App\Http\Requests\Api\V1\Portal\Auth\DoLogoutRequest;
use App\Http\Requests\Api\V1\Portal\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\Portal\Auth\SendOtpRequest;
use App\Http\Requests\Api\V1\Portal\Auth\VerifyOtpRequest;
use App\Http\Requests\Api\V1\Portal\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\V1\Portal\Auth\GetUserSessionInformationRequest;
use App\Http\Resources\Api\V1\Portal\Auth\GetUserSessionInformationResource;

class AuthController extends Controller
{
    public function doLogin(DoLoginRequest $req)
    {
        $input_dto = [
            'email' => $req->email,
            'password' => $req->password,
        ];

        $do_login  = app('DoLoginService')->execute($input_dto);

        return response()->json([
            'success' => (isset($do_login['error']) ? false : true),
            'message' => $do_login['message'],
            'data' => $do_login['data'],
        ])->setStatusCode((isset($do_login['error']) ? 401 : 200));
    }

    public function doLogout(DoLogoutRequest $request)
    {
        $do_logout  = app('DoLogoutService')->execute([]);

        return response()->json([
            'success' => (isset($do_logout['error']) ? false : true),
            'message' => $do_logout['message'],
            'data' => $do_logout['data'],
        ]);
    }

    public function getUserSessionInformation(GetUserSessionInformationRequest $request)
    {
        $result = app('GetUserSessionInformationService')->execute($request->validated());

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetUserSessionInformationResource($result['data']) :
                GetUserSessionInformationResource::collection($result['data']);
        }

        return response()->json([
            'success' => (isset($result['error']) ? false : true),
            'message' => $result['message'],
            'data' => $data,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code'] ?? 200);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $result = app('ForgotPasswordService')->execute($request->validated());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'] ?? null,
        ], $result['response_code'] ?? 200);
    }

    public function sendOtp(SendOtpRequest $request)
    {
        $result = app('SendOtpService')->execute($request->validated());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'] ?? null,
        ], $result['response_code'] ?? 200);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $result = app('VerifyOtpService')->execute($request->validated());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'] ?? null,
        ], $result['response_code'] ?? 200);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = app('ResetPasswordService')->execute($request->validated());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'] ?? null,
        ], $result['response_code'] ?? 200);
    }
}
