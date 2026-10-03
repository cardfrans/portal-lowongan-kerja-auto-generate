<?php

namespace App\Policies;

use App\Models\JobVacancy;
use App\Models\User;

class JobVacancyPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JobVacancy $jobVacancy): bool
    {
        return $user->id === $jobVacancy->company_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isCompany();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobVacancy $jobVacancy): bool
    {
        return $user->id === $jobVacancy->company_id
            && in_array($jobVacancy->status, ['rejected', 'draft'], true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobVacancy $jobVacancy): bool
    {
        return $user->id === $jobVacancy->company_id
            && in_array($jobVacancy->status, ['pending', 'draft'], true);
    }
}
