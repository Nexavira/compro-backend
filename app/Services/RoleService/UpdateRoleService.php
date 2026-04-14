<?php
namespace App\Services\RoleService;

use App\Models\Auth\Role;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Rules\UniqueData;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class UpdateRoleService extends DefaultService implements ServiceInterface {

    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $role = Role::find($dto['role_id']);

        $role->name = $dto['name'] ?? $role->name;
        $role->code = $dto['code'] ?? $role->code;
        $role->description = $dto['description'] ?? $role->description;

        $this->prepareAuditUpdate($role);
        $role->save();

        $this->results['data'] = $role;
        $this->results['message'] = "Role successfully updated";
    }

    public function prepare ($dto) {
        if (isset($dto['role_uuid'])) {
            $dto['role_id'] = $this->findIdByUuid(Role::query(), $dto['role_uuid']);
        }

        return $dto;
    }

    public function rules ($dto) {
        return [
            'role_uuid' => ['required','uuid', new ExistsUuid(new Role)],
            'name' => ['required', new UniqueData('auth_roles','name',$dto['role_uuid'])],
            'code' => ['nullable', new UniqueData('auth_roles','code',$dto['role_uuid'])],
            'description' => ['nullable'],
        ];
    }

}
