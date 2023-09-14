<?php

namespace App\Http\Requests;

use App\Declarations\ApiError;
use Illuminate\Foundation\Http\FormRequest;

class StoreCarCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'chargePerMinute' => 'required',
            'chargedAt' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ApiError::fieldIsRequired('Name'),
            'chargePerMinute.required' => ApiError::fieldIsRequired('chargePerMinute'),
            'chargedAt.required' => ApiError::fieldIsRequired('chargedAt')
        ];
    }
}
