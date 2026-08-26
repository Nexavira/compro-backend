<?php

namespace App\Http\Resources\Api\V1\Dashboard\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetTenantResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'custom_domain' => $this->custom_domain,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
