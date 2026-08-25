<?php

namespace App\Services\AuthService\PermissionRoleService;

use App\Models\Auth\Permission;
use App\Models\Auth\PermissionRole;
use App\Models\Auth\Role;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class UpdatePermissionRoleService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $role_permission = PermissionRole::where('role_id', $dto['role_id'])->where('permission_id', $dto['permission_id'])->first();

        if ($role_permission != null) {
            app('RemovePermissionRoleService')->process([
                'role_uuid' => $dto['role_uuid'],
                'permission_uuid' => $dto['permission_uuid'],
            ]);
        } else {
            app('AddPermissionRoleService')->process([
                'role_uuid' => $dto['role_uuid'],
                'permission_uuid' => $dto['permission_uuid'],
            ]);
        }
        $this->results['message'] = 'Role Permission successfully updated.';
        $this->results['data'] = true;
    }

    public function prepare($dto)
    {
        $dto['role_id'] = $this->findIdByUuid(Role::query(), $dto['role_uuid']);
        $dto['permission_id'] = $this->findIdByUuid(Permission::query(), $dto['permission_uuid']);

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'role_uuid' => ['required', 'uuid', new ExistsUuid(new Role)],
            'permission_uuid' => ['required', 'uuid', new ExistsUuid(new Permission)],
        ];
    }
}
