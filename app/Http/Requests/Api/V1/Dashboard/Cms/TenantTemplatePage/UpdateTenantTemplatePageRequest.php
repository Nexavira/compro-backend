<?php

namespace App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplatePage;

use App\Helpers\FormRequestApi;

class UpdateTenantTemplatePageRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_template_page_uuid' => $this->tenant_template_page_uuid
        ]);
    }

    public function rules(): array
    {
        return [
            'tenant_template_page_uuid' => ['required', 'string', 'exists:cms_tenant_template_pages,uuid'],
            'tenant_template_uuid' => ['nullable', 'string', 'exists:cms_tenant_templates,uuid'],
            'title' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'template_data' => ['nullable', 'array'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
