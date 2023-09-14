<?php

namespace App\Http\Requests;

use App\Declarations\ApiError;
use Illuminate\Foundation\Http\FormRequest;

class StoreCarsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'plate' => 'required',
            'categoryId' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'plate.required' => ApiError::fieldIsRequired('Plate'),
            'categoryId.required' => ApiError::fieldIsRequired('categoryId'),
        ];
    }
}
