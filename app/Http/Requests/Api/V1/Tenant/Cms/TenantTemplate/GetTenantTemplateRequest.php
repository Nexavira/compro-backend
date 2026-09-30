<?php

namespace App\Http\Requests\Api\V1\Tenant\Cms\TenantTemplate;

use App\Helpers\FormRequestApi;

class GetTenantTemplateRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        $tenant = app()->bound('tenant') ? app('tenant') : null;
        if ($tenant && $this->user()) {
            return $this->user()->canAccessTenant($tenant);
        }

        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_slug' => $this->route('tenant_slug') ?? $this->tenant_slug,
            'tenant_template_uuid' => $this->tenant_template_uuid
        ]);
    }

    public function rules(): array
    {
        return [
            'tenant_slug' => ['required', 'string', 'max:255'],
            'search_param' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'min:1'],
            'page' => ['nullable', 'integer', 'min:1'],
            'sort_by' => ['nullable', 'string'],
            'sort_type' => ['nullable', 'string', 'in:asc,desc'],
            'with_pagination' => ['nullable', 'boolean'],
            'tenant_template_uuid' => ['nullable', 'string', 'exists:cms_tenant_templates,uuid'],
            'tenant_uuid' => ['nullable', 'string', 'exists:tnt_tenants,uuid'],
        ];
    }
}
