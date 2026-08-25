<?php

namespace App\Services\Auth\UserService;

use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\DB;

class RegisterUserService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        DB::beginTransaction();

        $userService = app('StoreUserService')->execute($dto);

        if (isset($userService['error'])) {
            DB::rollBack();
            $this->results = $userService;
            return;
        }

        $user = $userService['data'];
        $dto['user_id'] = $user->id;

        $detailUserService = app('StoreDetailUserService')->execute($dto);

        if (isset($detailUserService['error'])) {
            DB::rollBack();
            $this->results = $detailUserService;
            return;
        }

        DB::commit();

        $this->results['data'] = [
            'user' => $user,
            'detail' => $detailUserService['data']
        ];
        $this->results['message'] = "User and Detail User successfully registered";
    }

    public function rules($dto)
    {
        return [];
    }
}
