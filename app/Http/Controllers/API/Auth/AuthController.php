<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function doLogin (Request $req) {
        $input_dto = [
            'email' => $req->email,
            'password' => $req->password,
        ];

        $do_login  = app('DoLoginService')->execute($input_dto);

        return response()->json([
            'success' => ( isset($do_login['error']) ? false : true ),
            'message' => $do_login['message'],
            'data' => $do_login['data'],
        ])->setStatusCode(( isset($do_login['error']) ? 401 : 200 ));
    }

    public function doLogout () {
        $do_logout  = app('DoLogoutService')->execute([]);

        return response()->json([
            'success' => ( isset($do_logout['error']) ? false : true ),
            'message' => $do_logout['message'],
            'data' => $do_logout['data'],
        ]);
    }

    public function getUserSessionInformation (Request $request) {
        /** @var User $user */
        $user = $request->user();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        //Photo
        $photo = null;
        if (isset($user->photo)) {
            $photo = [
                "uuid" => $user->photo->uuid,
                "original_file_name" => $user->photo->original_name,
                "url" => $user->photo->url
            ];
        }

        return [
            'uuid' => $user->uuid,
            'email' => $user->email,
            'name' => $user->userDetail->full_name,
            'photo' => $photo,
            'role' => [
                'uuid' => $user->roleUser->role->uuid,
                'name' => $user->roleUser->role->name,
            ],
            'user_information' => [
                'name' => $user->userDetail->full_name,
                'phone_number' => $user->userDetail->phone_number,
            ]
        ];
    }
}
