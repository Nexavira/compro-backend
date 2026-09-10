<?php

namespace App\Http\Requests\Api\V1\Portal\Master\PackageRequest;

use App\Helpers\FormRequestApi;
use App\Models\Master\Package;
use App\Rules\ExistsUuid;
use App\Rules\UniqueData;
use App\Traits\Identifier;

class UpdatePackageRequest extends FormRequestApi
{
    use Identifier;

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'package_uuid' => $this->package_uuid,
        ]);
    }

    public function rules()
    {
        return [
            "package_uuid" => ['required', 'bail', 'uuid', new ExistsUuid(new Package)],
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
