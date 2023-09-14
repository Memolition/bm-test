<?php

namespace App\Http\Requests;

use App\Declarations\ApiError;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegistryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'carId' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'inAt.required' => ApiError::fieldIsRequired('inAt'),
            'carId.required' => ApiError::fieldIsRequired('carId'),
            'userId.required' => ApiError::fieldIsRequired('userId'),
        ];
    }
}
