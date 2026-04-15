<?php

namespace App\Http\Resources\API\Permission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetPermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'guard_name' => $this->guard_name,
            'prefix_label' => $this->prefix_label,
            'module_label' => $this->module_label,
            'action_label' => $this->action_label,
            'code' => $this->code  
        ];
    }
}
