<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreJobVacancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isCompany();
    }

    /**
     * Sanitize input before validation to prevent XSS.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'company_name' => strip_tags(trim((string) $this->input('company_name'))),
            'business_area' => strip_tags(trim((string) $this->input('business_area'))),
            'province' => strip_tags(trim((string) $this->input('province'))),
            'company_website' => strip_tags(trim((string) $this->input('company_website'))),
            'contact_name' => strip_tags(trim((string) $this->input('contact_name'))),
            'contact_position' => strip_tags(trim((string) $this->input('contact_position'))),
            'contact_phone' => strip_tags(trim((string) $this->input('contact_phone'))),
            'company_email' => strip_tags(trim((string) $this->input('company_email'))),
            'position' => strip_tags(trim((string) $this->input('position'))),
            'required_count' => $this->input('required_count'),
            'qualifications' => strip_tags(trim((string) $this->input('qualifications'))),
            'job_description' => strip_tags(trim((string) $this->input('job_description'))),
            'other_info' => $this->filled('other_info') ? strip_tags(trim((string) $this->input('other_info'))) : null,
            'address' => strip_tags(trim((string) $this->input('address'))),
            'work_location' => strip_tags(trim((string) $this->input('work_location'))),
            'application_method' => strip_tags(trim((string) $this->input('application_method'))),
            'application_address' => strip_tags(trim((string) $this->input('application_address'))),
            'offered_salary' => strip_tags(trim((string) $this->input('offered_salary'))),
            'promotional_caption' => strip_tags(trim((string) $this->input('promotional_caption'))),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:100'],
            'business_area' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'company_website' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:100'],
            'contact_position' => ['required', 'string', 'max:100'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'company_email' => ['required', 'email', 'max:255'],
            'position' => ['required', 'string', 'max:50'],
            'required_count' => ['required', 'integer', 'min:1'],
            'qualifications' => ['required', 'string', 'min:10', 'max:450'],
            'job_description' => ['required', 'string', 'min:10', 'max:1000'],
            'other_info' => ['nullable', 'string', 'max:150'],
            'address' => ['required', 'string', 'min:5', 'max:255'],
            'work_location' => ['required', 'string', 'max:255'],
            'application_deadline' => ['required', 'date', 'after_or_equal:today'],
            'application_method' => ['required', 'string', 'in:Email,Website,Datang Langsung,Link Khusus,Lainnya'],
            'application_address' => ['required', 'string', 'max:255'],
            'offered_salary' => ['nullable', 'string', 'max:100'],
            'promotional_caption' => ['nullable', 'string', 'max:500'],
            'information_consent' => ['required', 'accepted'],
            'publication_consent' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Nama perusahaan wajib diisi.',
            'company_name.max' => 'Nama perusahaan maksimal 50 karakter agar muat di poster.',
            'position.required' => 'Posisi pekerjaan wajib diisi.',
            'position.max' => 'Posisi pekerjaan maksimal 50 karakter agar muat di poster.',
            'qualifications.required' => 'Kualifikasi dan persyaratan kerja wajib diisi.',
            'qualifications.max' => 'Kualifikasi maksimal 450 karakter agar layout poster tidak berantakan.',
            'other_info.max' => 'Informasi tambahan maksimal 150 karakter agar muat di poster.',
            'address.required' => 'Alamat atau lokasi penempatan wajib diisi.',
            'address.max' => 'Alamat maksimal 100 karakter agar muat di poster.',
        ];
    }
}
