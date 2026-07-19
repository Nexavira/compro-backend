<?php

namespace App\Helpers;

use App\Traits\Identifier;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;

class FormRequestApi extends LaravelFormRequest
{
    use Identifier;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
           'success' => false,
           'message' => $validator->errors()
        ], 422));
    }

}
