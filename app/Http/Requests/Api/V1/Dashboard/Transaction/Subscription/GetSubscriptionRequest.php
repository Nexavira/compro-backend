<?php

namespace App\Http\Requests\Api\V1\Dashboard\Transaction\Subscription;

use App\Helpers\FormRequestApi;
use App\Models\Tenant\Tenant;
use App\Rules\ExistsUuid;

class GetSubscriptionRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tenant_uuid' => ['nullable', 'uuid', new ExistsUuid(new Tenant)],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1'],
            'search' => ['nullable', 'string', 'max:255'],
        ];
    }
}
