<?php

namespace App\Http\Resources\Api\V1\Portal\Cms\GlobalTemplate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetGlobalTemplateResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'tier' => $this->tier,
            'brand_settings' => $this->brand_settings,
            'is_active' => $this->is_active,
            'tenant_category' => $this->tenantCategory ? [
                'uuid' => $this->tenantCategory->uuid,
                'name' => $this->tenantCategory->name,
            ] : null,
        ];
    }
}
