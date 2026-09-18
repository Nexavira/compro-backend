<?php

namespace App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplatePage;

use App\Helpers\FormRequestApi;

class StoreTenantTemplatePageRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tenant_template_uuid' => ['required', 'string', 'exists:cms_tenant_templates,uuid'],
            'title' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'template_data' => ['nullable', 'array'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
