<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:10240',
            ],
        ];

        if ($this->user()->isCompany()) {
            $rules += [
                'business_area' => ['required', 'string', 'max:100'],
                'province' => ['required', 'string', 'max:100'],
                'company_website' => ['required', 'string', 'max:255'],
                'company_email' => ['required', 'email', 'max:255'],
                'contact_name' => ['required', 'string', 'max:100'],
                'contact_position' => ['required', 'string', 'max:100'],
                'contact_phone' => ['required', 'string', 'max:30'],
            ];
        }

        return $rules;
    }
}
