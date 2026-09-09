<?php

namespace App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplate;

use App\Helpers\FormRequestApi;

class StoreTenantTemplateRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tenant_uuid' => ['required', 'string', 'exists:tnt_tenants,uuid'],
            'global_template_uuid' => ['required', 'string', 'exists:cms_global_templates,uuid'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
            'template_settings' => ['nullable', 'array'],
            'pages' => ['nullable', 'array'],
            'pages.*.title' => ['required_with:pages', 'string'],
            'pages.*.slug' => ['required_with:pages', 'string'],
            'pages.*.template_data' => ['nullable', 'array'],
        ];
    }
}
