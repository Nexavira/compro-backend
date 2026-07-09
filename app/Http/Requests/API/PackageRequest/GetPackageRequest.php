<?php

namespace App\Http\Requests\API\PackageRequest;

use App\Helpers\FormRequestApi;
use App\Models\Master\Package;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class GetPackageRequest extends FormRequestApi
{
    use Identifier;

    /**
     * Determine if the role is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'sort_by' => 'id',
            'sort_type' => 'asc'
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "package_uuid" => ['nullable', 'uuid', new ExistsUuid(new Package())] 
        ];
    }
}
