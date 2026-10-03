<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'company_name',
        'business_area',
        'position',
        'required_count',
        'qualifications',
        'job_description',
        'other_info',
        'address',
        'work_location',
        'province',
        'company_website',
        'contact_name',
        'contact_position',
        'contact_phone',
        'company_email',
        'application_deadline',
        'application_method',
        'application_address',
        'offered_salary',
        'status',
        'rejection_reason',
        'admin_reject_reason',
        'company_revision_reason',
        'selected_template',
        'generated_poster_path',
        'supporting_image_path',
        'promotional_caption',
        'information_consent',
        'publication_consent',
    ];

    protected function casts(): array
    {
        return [
            'application_deadline' => 'date',
            'information_consent' => 'boolean',
            'publication_consent' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function editLogs(): HasMany
    {
        return $this->hasMany(JobEditLog::class, 'job_vacancy_id')->latest();
    }
}
