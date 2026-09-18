<?php

namespace App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplatePage;

use App\Helpers\FormRequestApi;

class GetTenantTemplatePageRequest extends FormRequestApi
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
            'search_param' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'min:1'],
            'page' => ['nullable', 'integer', 'min:1'],
            'sort_by' => ['nullable', 'string'],
            'sort_type' => ['nullable', 'string', 'in:asc,desc'],
            'with_pagination' => ['nullable', 'boolean'],
            'tenant_template_page_uuid' => ['nullable', 'string', 'exists:cms_tenant_template_pages,uuid'],
            'tenant_template_uuid' => ['nullable', 'string', 'exists:cms_tenant_templates,uuid'],
        ];
    }
}
