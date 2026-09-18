<?php

namespace App\Http\Requests\Api\V1\Tenant\Cms\TenantTemplatePage;

use App\Helpers\FormRequestApi;

class GetTenantTemplatePageRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tenant_slug' => ['required', 'string', 'max:255'],
        ];
    }
}
