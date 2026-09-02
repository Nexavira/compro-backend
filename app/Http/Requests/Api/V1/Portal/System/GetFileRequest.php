<?php

namespace App\Http\Requests\Api\V1\Portal\System;

use App\Helpers\FormRequestApi;
use App\Models\System\File;
use App\Models\Tenant\Tenant;
use App\Rules\ExistsUuid;

class GetFileRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'file_uuid' => $this->file_uuid,
            'tenant_uuid' => $this->tenant_uuid,
        ]);
    }

    public function rules(): array
    {
        return [
            'file_uuid' => ['nullable', 'uuid', new ExistsUuid(new File)],
            'tenant_uuid' => ['nullable', 'uuid', new ExistsUuid(new Tenant)],
        ];
    }
}
