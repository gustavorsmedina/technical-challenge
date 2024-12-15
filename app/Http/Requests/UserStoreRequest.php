<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string'],
            // 'document'  => ['required', 'unique:users,document', 'string'],
            'document'  => ['required', 'string'],
            'email'     => ['required', 'email'],
            'password'  => ['required', 'string'],
            'user_type' => ['required', 'in:common,merchant'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'The full name field is required.',
            'full_name.string'   => 'The full name field must be a string.',
            'document.required'  => 'The document field is required.',
            'document.string'    => 'The document field must be a string.',
            'document.unique'    => 'The document field must be unique.',
            'email.required'     => 'The email field is required.',
            'email.email'        => 'The email field must be a valid email.',
            'password.required'  => 'The password field is required.',
            'password.string'    => 'The password field must be a string.',
            'user_type.required' => 'The user type field is required.',
            'user_type.in'       => 'The user type field must be common or merchant.',
        ];
    }
}
