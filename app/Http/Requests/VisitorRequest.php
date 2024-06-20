<?php

namespace App\Http\Requests;

use App\Models\Visitor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitorRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow all users to make this request, or implement your own authorization logic
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'], // changed 'Phone_number' to 'phone_number'
            'company' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Visitor::class)->ignore($this->route('visitor'))
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'The full name is required.',
            'phone_number.required' => 'The phone number is required.', // changed 'Phone_number' to 'phone_number'
            'company.required' => 'The company name is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'The email address must be a valid email format.',
            'email.unique' => 'The email address has already been taken.',
        ];
    }
}
