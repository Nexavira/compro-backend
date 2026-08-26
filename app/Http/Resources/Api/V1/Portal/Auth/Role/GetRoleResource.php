<?php

namespace App\Http\Resources\Api\V1\Portal\Auth\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetRoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description
        ];
    }
}
