<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
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
            //            'payer' => 'required|exists:users,id',
            'payer' => ['required'],
            'payee' => ['required'],
            'value' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'payer.required' => 'The payer field is required.',
            'payee.required' => 'The payee field is required.',
            'value.required' => 'The value field is required.',
            'value.numeric'  => 'The value field must be a number.',
            'value.min'      => 'The value field must be at least 0.',
        ];
    }
}