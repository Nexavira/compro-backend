<?php

namespace App\Http\Requests\Api\V1\Dashboard\Transaction\Payment;

use App\Helpers\FormRequestApi;
use App\Models\Transaction\Payment;
use App\Rules\ExistsUuid;

class UploadPaymentProofRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_uuid' => ['required', 'uuid', new ExistsUuid(new Payment)],
            'proof_of_payment' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ];
    }
}
