<?php

namespace App\Http\Requests\Api\V1\Portal\Transaction;

use App\Helpers\FormRequestApi;

use App\Models\Transaction\Payment;
use App\Rules\ExistsUuid;

class UploadPaymentProofRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'payment_uuid' => $this->route('payment_uuid'),
        ]);
    }

    public function rules(): array
    {
        return [
            'payment_uuid' => ['required', 'uuid', new ExistsUuid(new Payment)],
            'proof_of_payment' => ['required', 'file', 'max:51200', 'mimes:jpg,jpeg,png,pdf'],
        ];
    }
}
