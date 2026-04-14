<?php

namespace App\Services\AuthService\PermissionRoleService;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Auth\PermissionRole;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class AddPermissionRoleService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);

        PermissionRole::insert([
            'permission_id' => $dto['permission_id'],
            'role_id' => $dto['role_id'],
        ]);

        $this->results['data'] = [];
        $this->results['message'] = 'Permission successfully added to role';
    }

    public function prepare($dto)
    {
        $dto['permission_id'] = $this->findIdByUuid(Permission::query(), $dto['permission_uuid']);
        $dto['role_id'] = $this->findIdByUuid(Role::query(), $dto['role_uuid']);

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'permission_uuid' => ['required', 'uuid', new ExistsUuid(new Permission)],
            'role_uuid' => ['required', 'uuid', new ExistsUuid(new Role)],
        ];
    }
}
