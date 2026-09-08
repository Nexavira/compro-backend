<?php

namespace App\Http\Resources\Api\V1\Tenant\Cms\TenantPage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetTenantPageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'content_blocks' => $this->content_blocks,
            'meta' => $this->meta,
            'is_published' => $this->is_published,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
