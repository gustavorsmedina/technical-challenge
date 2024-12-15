<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'full_name' => ['string'],
            'document'  => ['string'],
            'email'     => ['email'],
            'password'  => ['string'],
            'user_type' => ['in:common,merchant'],
            'status'    => ['in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.string' => 'The full name field must be a string.',
            'document.string'  => 'The document field must be a string.',
            'email.email'      => 'The email field must be a valid email.',
            'password.string'  => 'The password field must be a string.',
            'user_type.in'     => 'The user type field must be common or merchant.',
            'status.in'        => 'The status field must be active or inactive.',
        ];
    }
}
