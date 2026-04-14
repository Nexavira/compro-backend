<?php
namespace App\Services\RoleService;

use App\Models\Auth\Role;
use App\Rules\UniqueData;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class StoreRoleService extends DefaultService implements ServiceInterface {

    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $role = new Role();

        $role->name = $dto['name'];
        $role->code = $dto['code'] ?? null;
        $role->description = $dto['description'] ?? null;

        $this->prepareAuditActive($role);
        $this->prepareAuditInsert($role);
        $role->save();

        $this->results['data'] = $role;
        $this->results['message'] = "Role successfully stored";
    }

    public function prepare ($dto) {
        return $dto;
    }

    public function rules ($dto) {
        return [
            'name' => ['required', new UniqueData('auth_roles','name' ?? null)],
            'code' => ['nullable', new UniqueData('auth_roles','code' ?? null)],
            'description' => ['nullable'],
        ];
    }

}
