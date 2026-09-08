<?php

namespace App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplate;

use App\Helpers\FormRequestApi;

class UpdateTenantTemplateRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_template_uuid' => $this->tenant_template_uuid
        ]);
    }

    public function rules(): array
    {
        return [
            'tenant_template_uuid' => ['required', 'string', 'exists:cms_tenant_templates,uuid'],
            'global_template_uuid' => ['nullable', 'string', 'exists:cms_global_templates,uuid'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
            'template_settings' => ['nullable', 'array'],
            'pages' => ['nullable', 'array'],
            'pages.*.uuid' => ['nullable', 'string', 'exists:cms_pages,uuid'],
            'pages.*.title' => ['required_with:pages', 'string'],
            'pages.*.slug' => ['required_with:pages', 'string'],
            'pages.*.template_data' => ['nullable', 'array'],
            'pages.*.is_active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
