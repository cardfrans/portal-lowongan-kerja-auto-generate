<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectJobVacancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'rejection_reason' => strip_tags(trim((string) $this->input('rejection_reason'))),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi agar perusahaan mengetahui hal yang perlu diperbaiki.',
            'rejection_reason.min' => 'Alasan penolakan minimal 5 karakter.',
        ];
    }
}
