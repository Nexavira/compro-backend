<?php

namespace App\Http\Resources\API\Package;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetPackageResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        switch ($this->billing_cycle) {
            case 'monthly':
                $price_suffix = '/ month';
                $cta_label = 'Choose monthly plan';
                break;
            case 'annually':
                $price_suffix = '/ year';
                $cta_label = 'Choose annual plan';
                break;
            case 'custom':
                $price_suffix = '/ project';
                $cta_label = 'Request consultation';
                break;
            default:
                $price_suffix = '/project';
                $cta_label = 'Choose plan';
        }

        return [
            'uuid' => $this->uuid,
            'title' => $this->name,
            'description' => $this->description,
            'pricePrefix' => 'Starting at',
            'price' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'priceSuffix' => $price_suffix,
            'cta' => $cta_label,
            'features' => json_decode($this->features),
            'variant' => $this->is_highlighted ? 'highlighted' : 'default',
        ];
    }
}
