<?php

namespace App\Http\Resources\Api\V1\Dashboard\Cms\TenantTemplate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetTenantTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'is_active' => $this->is_active,
            'template_settings' => $this->template_settings,
            'version' => $this->version,
            'tenant' => $this->tenant ? [
                'uuid' => $this->tenant->uuid,
                'name' => $this->tenant->name,
                'slug' => $this->tenant->slug,
            ] : null,
            'global_template' => $this->globalTemplate ? [
                'uuid' => $this->globalTemplate->uuid,
                'title' => $this->globalTemplate->title,
                'tier' => $this->globalTemplate->tier,
            ] : null,
        ];
    }
}
