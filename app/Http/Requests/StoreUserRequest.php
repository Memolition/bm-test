<?php

namespace App\Http\Requests;

use App\Declarations\ApiError;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //TODO: Provide authorization levels
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required',
            'password' => 'required|min:5',
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => ApiError::fieldIsRequired('Username'),
            'password.required' => ApiError::fieldIsRequired('Password'),
            'password.min' => ApiError::fieldMinimumNotMet('Password', 5),
            'name.required' => ApiError::fieldIsRequired('Name')
        ];
    }
}
