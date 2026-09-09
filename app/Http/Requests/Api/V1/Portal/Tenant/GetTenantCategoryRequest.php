<?php

namespace App\Http\Requests\Api\V1\Portal\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class GetTenantCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'sort_by' => ['nullable', 'string'],
            'sort_type' => ['nullable', 'string', 'in:asc,desc'],
            'with_pagination' => ['nullable', 'boolean'],
            'tenant_category_uuid' => ['nullable', 'string', 'exists:tnt_tenant_categories,uuid'],
        ];
    }
}
