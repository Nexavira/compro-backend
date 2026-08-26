<?php

namespace App\Services\Auth\User;

use App\Models\Auth\User;
use App\Rules\UniqueData;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class StoreUserService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $model = new User;

        $model->email = $dto['email'];
        $model->password = $dto['password'];

        $this->prepareAuditActive($model);
        $this->prepareAuditInsert($model);
        $model->save();

        $this->results['data'] = $model;
        $this->results['message'] = "User successfully stored";
    }

    public function prepare($dto)
    {
        if (isset($dto['password'])) {
            $dto['password'] = bcrypt($dto['password']);
        }

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'email' => ['required', new UniqueData(new User)],
            'email_verified_at' => ['nullable', 'date'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ];
    }
}
