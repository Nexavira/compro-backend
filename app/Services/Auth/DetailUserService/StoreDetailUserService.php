<?php

namespace App\Services\Auth\DetailUserService;

use App\Models\Auth\DetailUser;
use App\Models\Auth\User;
use App\Models\System\File;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Rules\UniqueData;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class StoreDetailUserService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $model = new DetailUser;

        $model->photo_id = $dto['photo_id'];
        $model->user_id = $dto['user_id'];
        $model->full_name = $dto['full_name'];
        $model->phone_number = $dto['phone_number'];

        $this->prepareAuditActive($model);
        $this->prepareAuditInsert($model);
        $model->save();

        $this->results['data'] = $model;
        $this->results['message'] = "Detail User successfully stored";
    }

    public function prepare($dto)
    {
        if (isset($dto['photo_uuid'])) {
            $dto['photo_id'] = $this->findIdByUuid(File::query(), $dto['photo_uuid']);
        }

        if (isset($dto['user_uuid'])) {
            $dto['user_id'] = $this->findIdByUuid(User::query(), $dto['user_uuid']);
        }

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'photo_id' => ['nullable', 'integer', new ExistsId(new File)],
            'photo_uuid' => ['nullable', 'uuid', new ExistsUuid(new File)],
            'user_id' => ['nullable', 'integer', new ExistsId(new User)],
            'user_uuid' => ['required_without:user_id', 'uuid', new ExistsUuid(new User)],
            'full_name' => ['required'],
            'phone_number' => ['required', 'string', new UniqueData(new DetailUser)]
        ];
    }
}
