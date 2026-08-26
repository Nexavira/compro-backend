<?php

namespace App\Services\Tenant;

use App\Models\Auth\User;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantUser;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class AddTenantUserService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);

        TenantUser::insert([
            'tenant_id' => $dto['tenant_id'],
            'user_id' => $dto['user_id'],
            'role_detail' => $dto['role_detail'] ?? null,
        ]);

        $this->results['data'] = [];
        $this->results['message'] = 'User successfully assigned to tenant';
    }

    public function prepare($dto)
    {
        if (isset($dto['tenant_uuid']) && !isset($dto['tenant_id'])) {
            $dto['tenant_id'] = $this->findIdByUuid(Tenant::query(), $dto['tenant_uuid']);
        }

        if (isset($dto['user_uuid']) && !isset($dto['user_id'])) {
            $dto['user_id'] = $this->findIdByUuid(User::query(), $dto['user_uuid']);
        }

        return $dto;
    }

    public function rules($dto)
    {
        return [
            //  'photo_id' => ['nullable', 'integer', new ExistsId(new File)],
            // 'photo_uuid' => ['nullable', 'uuid', new ExistsUuid(new File)],
        ];
    }
}
