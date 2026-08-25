<?php

namespace App\Http\Resources\Api\V1\Dashboard\TenantCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetTenantCategoryResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
