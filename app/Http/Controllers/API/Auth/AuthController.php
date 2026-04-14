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
            'success' => ( isset($do_login['error']) ? false : true ),
            'message' => $do_logout['message'],
            'data' => $do_logout['data'],
        ]);
    }

    public function getUserSessionInformation () {
        if(!auth()->user()) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        //Photo
        $photo = null;
        if (isset(auth()->user()->photo)) {
            $photo = [
                "uuid" => auth()->user()->photo->uuid,
                "original_file_name" => auth()->user()->photo->original_name,
                "url" => auth()->user()->photo->url
            ];
        }

        return [
            'uuid' => auth()->user()->uuid,
            'email' => auth()->user()->email,
            'name' => auth()->user()->userInformation->name,
            'photo' => $photo,
            'role' => [
                'uuid' => auth()->user()->userRole->role->uuid,
                'name' => auth()->user()->userRole->role->name,
            ],
            'user_information' => [
                'name' => auth()->user()->userInformation->name,
                'phone_number' => auth()->user()->userInformation->phone_number,
            ]
        ];
    }
}
