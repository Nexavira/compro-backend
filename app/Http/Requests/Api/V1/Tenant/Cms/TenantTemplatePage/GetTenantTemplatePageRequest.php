<?php

namespace App\Http\Requests\Api\V1\Tenant\Cms\TenantTemplatePage;

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
            'tenant_slug' => $this->route('tenant_slug') ?? $this->tenant_slug,
            'slug' => $this->route('slug') ?? $this->slug,
        ]);
    }

    public function rules(): array
    {
        return [
            'tenant_slug' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
        ];
    }
}
