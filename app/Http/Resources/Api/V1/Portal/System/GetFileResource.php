<?php

namespace App\Http\Resources\Api\V1\Portal\System;

use Illuminate\Http\Resources\Json\JsonResource;

class GetFileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'original_name' => $this->original_name,
            'url' => $this->url,
        ];
    }
}
