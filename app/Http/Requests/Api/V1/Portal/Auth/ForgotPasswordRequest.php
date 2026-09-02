<?php

namespace App\Http\Requests\Api\V1\Portal\Auth;

use App\Helpers\FormRequestApi;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:auth_users,email'],
<<<<<<< HEAD
=======
            'otp_code' => ['required', 'string', 'min:6'],
>>>>>>> 31070bc1c8510597d8d8554d7e2537c82e530ef8
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ];
    }
}
