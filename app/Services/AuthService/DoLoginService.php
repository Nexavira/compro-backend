<?php
namespace App\Services\AuthService;

use App\Exceptions\CustomException;
use App\Models\Auth\User;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\Auth;

class DoLoginService extends DefaultService implements ServiceInterface {

    public function process($dto)
    {
        if (!Auth::attempt(['email' => $dto['email'], 'password' => $dto['password'], 'is_active' => true, 'deleted_at' => null])) {
            throw new \Exception("Invalid email or password", 401);
        }
        $user = User::find(Auth::id());

        $this->results['data'] = [
            'user' => [
                'user_uuid' => $user->uuid,
                'email' => $user->email,
                'role' => [
                    'uuid' => $user->userRole->role->uuid,
                    'name' => $user->userRole->role->name,
                ]
            ],
            'token' => $user->createToken('MyApp')->accessToken
        ];
        $this->results['message'] = "User successfully logged in";

    }

    public function rules ($dto) {
        return [
            'email' => ['required'],
            'password' => ['required']
        ];
    }

}
