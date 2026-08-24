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
                $price_suffix = '/bulan';
                $cta_label = 'Mulai Trial ' . ucfirst($this->tier);
                break;
            case 'annually':
                $price_suffix = '/tahun';
                $cta_label = 'Mulai Trial ' . ucfirst($this->tier);
                break;
            case 'custom':
                $price_suffix = '/proyek';
                $cta_label = 'Konsultasi Sekarang';
                break;
            default:
                $price_suffix = '/proyek';
                $cta_label = 'Pilih Paket';
        }

        if ($this->trial_days == 0) {
            $cta_label = $this->tier === 'custom' ? 'Konsultasi Sekarang' : 'Pilih Paket ' . ucfirst($this->tier);
        }

        $formatted_price = 'Rp ' . number_format($this->price, 0, ',', '.');
        if ($this->price >= 1000000) {
            $formatted_price = 'Rp ' . rtrim(rtrim(number_format($this->price / 1000000, 1, ',', '.'), '0'), ',') . 'jt';
        }

        $formatted_original_price = $this->original_price
            ? 'Rp ' . number_format($this->original_price, 0, ',', '.')
            : null;

        $trial_badge = $this->trial_days > 0 ? "Trial Gratis {$this->trial_days} Hari" : null;

        return [
            'uuid' => $this->uuid,
            'title' => $this->name,
            'tier' => $this->tier,
            'website_type' => $this->website_type,
            'billing_cycle' => $this->billing_cycle,
            'description' => $this->description,
            'price_prefix' => $this->tier === 'custom' ? 'Mulai dari' : '',
            'price' => $formatted_price,
            'original_price' => $formatted_original_price,
            'price_suffix' => $price_suffix,
            'trial_badge' => $trial_badge,
            'cta' => $cta_label,
            'features' => is_string($this->features) ? json_decode($this->features) : $this->features,
            'variant' => $this->is_highlighted ? 'highlighted' : 'default',
        ];
    }
}
