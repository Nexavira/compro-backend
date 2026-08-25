<?php

namespace App\Http\Requests\Api\V1\Portal\Master\PackageRequest;

use App\Helpers\FormRequestApi;
use App\Models\Master\Package;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class GetPackageRequest extends FormRequestApi
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
            'sort_by' => 'id',
            'sort_type' => 'asc'
        ]);
    }

    public function rules()
    {
        return [
            "package_uuid" => ['nullable', 'bail', 'uuid', new ExistsUuid(new Package)]
        ];
    }
}
