<?php

namespace App\Http\Requests\Api\V1\Portal\Auth;

use App\Helpers\FormRequestApi;

class DoLoginRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
