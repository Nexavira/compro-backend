<?php

namespace App\Http\Resources\Api\V1\Dashboard\Cms\TenantPage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetTenantPageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'template_data' => $this->template_data,
            'is_active' => $this->is_active,
            'version' => $this->version,
            'tenant_template' => $this->tenantTemplate ? [
                'uuid' => $this->tenantTemplate->uuid,
            ] : null,
        ];
    }
}
