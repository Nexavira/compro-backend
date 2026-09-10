<?php

namespace App\Services\Master\Package;

use App\Models\Master\Package;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Rules\UniqueData;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Str;

class UpdatePackageService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $package = Package::find($dto['package_id']);

        $package->name = $dto['name'] ?? $package->name;
        $package->code = $dto['code'] ?? $package->code;
        $package->website_type = $dto['website_type'] ?? $package->website_type;
        $package->tier = $dto['tier'] ?? $package->tier;
        $package->description = $dto['description'] ?? $package->description;
        $package->original_price = $dto['original_price'] ?? $package->original_price;
        $package->price = $dto['price'] ?? $package->price;
        $package->setup_fee = $dto['setup_fee'] ?? $package->setup_fee;
        $package->features = $dto['features'] ?? $package->features;
        $package->trial_days = $dto['trial_days'] ?? $package->trial_days;
        $package->billing_cycle = $dto['billing_cycle'] ?? $package->billing_cycle;
        $package->is_highlighted = $dto['is_highlighted'] ?? $package->is_highlighted;
        

        $this->prepareAuditUpdate($package);
        $package->save();

        $this->results['data'] = $package;
        $this->results['message'] = "Package successfully updated";
    }

    public function prepare($dto)
    {
        $dto['package_id'] = $this->findIdByUuid(Package::query(), $dto['package_uuid']);

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'package_uuid' => ['required', 'string', new ExistsUuid('mst_packages')],
            'name' => ['required', 'string', 'max:255', new UniqueData('tnt_tenants', 'name')],
            'code' => ['nullable', 'string', 'max:255', new UniqueData('tnt_tenants', 'code')],
            'website_type' => ['nullable', 'string'],
            'tier' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'setup_fee' => ['nullable', 'numeric', 'min:0'],
            'features' => ['nullable', 'json'],
            'trial_days' => ['nullable', 'integer', 'min:0'],
            'billing_cycle' => ['nullable', 'string'],
            'is_highlighted' => ['nullable', 'boolean'],
        ];
    }
}
