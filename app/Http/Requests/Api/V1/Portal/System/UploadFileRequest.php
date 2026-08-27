<?php

namespace App\Http\Requests\Api\V1\Portal\System;

use App\Models\Tenant\Tenant;
use App\Rules\ExistsUuid;
use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:51200'],
            'tenant_uuid' => ['nullable', 'uuid', new ExistsUuid(new Tenant)],
            'tenant_id' => ['nullable', 'integer'],
            'related_id' => ['nullable', 'integer'],
            'related_type' => ['nullable', 'string'],
            'is_public' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
