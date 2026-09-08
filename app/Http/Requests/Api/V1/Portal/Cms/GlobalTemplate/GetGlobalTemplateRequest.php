<?php

namespace App\Http\Requests\Api\V1\Portal\Cms\GlobalTemplate;

use App\Helpers\FormRequestApi;

class GetGlobalTemplateRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'global_template_uuid' => $this->global_template_uuid
        ]);
    }

    public function rules(): array
    {
        return [
            'global_template_uuid' => ['nullable', 'string', 'exists:cms_global_templates,uuid'],
        ];
    }
}
