<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobEditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_vacancy_id',
        'edit_reason',
        'old_data',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'old_data' => 'array',
        ];
    }

    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id');
    }
}
