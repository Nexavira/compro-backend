<?php

namespace App\Http\Requests\Api\V1\Tenant\Cms\TenantTemplate;

use App\Helpers\FormRequestApi;

class ChangeActiveTenantTemplateRequest extends FormRequestApi
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
            'tenant_template_uuid' => $this->route('tenant_template_uuid') ?? $this->tenant_template_uuid
        ]);
    }

    public function rules(): array
    {
        return [
            'tenant_slug' => ['required', 'string', 'max:255'],
            'tenant_template_uuid' => ['nullable', 'UUID', 'exists:cms_tenant_templates,uuid'],
            'active_template' => ['required', 'boolean'],
        ];
    }
}
