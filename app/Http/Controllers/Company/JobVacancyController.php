<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobVacancyRequest;
use App\Http\Requests\UpdateJobVacancyRequest;
use App\Models\JobEditLog;
use App\Models\JobVacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of vacancies submitted by the authenticated company.
     */
    public function index(Request $request): View
    {
        $query = $request->user()->jobVacancies()->latest();

        if ($request->filled('status') && in_array($request->status, ['draft', 'pending', 'approved', 'rejected', 'revised'], true)) {
            $query->where('status', $request->status);
        }

        $vacancies = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => $request->user()->jobVacancies()->count(),
            'pending' => $request->user()->jobVacancies()->where('status', 'pending')->count(),
            'approved' => $request->user()->jobVacancies()->where('status', 'approved')->count(),
            'rejected' => $request->user()->jobVacancies()->where('status', 'rejected')->count(),
            'revised' => $request->user()->jobVacancies()->where('status', 'revised')->count(),
        ];

        return view('company.vacancies.index', compact('vacancies', 'stats'));
    }

    /**
     * Show the form for creating a new vacancy.
     */
    public function create(Request $request): View
    {
        $defaultCompanyName = $request->user()->name;

        return view('company.vacancies.create', compact('defaultCompanyName'));
    }

    /**
     * Store a newly created vacancy.
     */
    public function store(StoreJobVacancyRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['information_consent'], $data['publication_consent']);
        $data['status'] = 'pending';
        $data['information_consent'] = true;
        $data['publication_consent'] = true;

        $vacancy = $request->user()->jobVacancies()->create($data);

        return redirect()
            ->route('company.vacancies.show', $vacancy)
            ->with('success', 'Pengajuan lowongan kerja berhasil dikirim dan sedang menunggu peninjauan admin.');
    }

    /**
     * Display the specified vacancy.
     */
    public function show(Request $request, JobVacancy $vacancy): View
    {
        $this->authorize('view', $vacancy);
        $vacancy->load('editLogs');

        return view('company.vacancies.show', ['jobVacancy' => $vacancy]);
    }

    /**
     * Show the form for editing the specified vacancy.
     */
    public function edit(Request $request, JobVacancy $vacancy): View
    {
        $this->authorize('update', $vacancy);

        return view('company.vacancies.edit', ['jobVacancy' => $vacancy]);
    }

    /**
     * Update the specified vacancy and save snapshot to job_edit_logs.
     */
    public function update(UpdateJobVacancyRequest $request, JobVacancy $vacancy): RedirectResponse
    {
        $this->authorize('update', $vacancy);

        // 1. Take snapshot of old data
        $oldData = [
            'company_name' => $vacancy->company_name,
            'business_area' => $vacancy->business_area,
            'position' => $vacancy->position,
            'required_count' => $vacancy->required_count,
            'qualifications' => $vacancy->qualifications,
            'job_description' => $vacancy->job_description,
            'other_info' => $vacancy->other_info,
            'address' => $vacancy->address,
            'work_location' => $vacancy->work_location,
            'application_deadline' => $vacancy->application_deadline?->toDateString(),
            'application_method' => $vacancy->application_method,
            'application_address' => $vacancy->application_address,
            'previous_status' => $vacancy->status,
        ];

        // 2. Create edit log
        JobEditLog::create([
            'job_vacancy_id' => $vacancy->id,
            'edit_reason' => $request->edit_reason,
            'old_data' => $oldData,
            'status' => 'unread',
        ]);

        // 3. Update job vacancy and reset status to pending
        $data = $request->validated();
        unset($data['information_consent'], $data['publication_consent']);
        $data['status'] = $vacancy->status === 'rejected' ? 'revised' : 'pending';
        $data['company_revision_reason'] = $request->edit_reason;
        $data['rejection_reason'] = $vacancy->rejection_reason;

        $vacancy->update($data);

        $statusLabel = $data['status'] === 'revised' ? 'Revisi Masuk' : 'Pending';

        return redirect()
            ->route('company.vacancies.show', $vacancy)
            ->with('success', "Perubahan lowongan kerja berhasil disimpan. Status saat ini {$statusLabel} dan alasan revisi telah dikirim ke Admin.");
    }

    /**
     * Remove the specified vacancy from storage.
     */
    public function destroy(Request $request, JobVacancy $vacancy): RedirectResponse
    {
        $this->authorize('delete', $vacancy);

        $vacancy->delete();

        return redirect()
            ->route('company.vacancies.index')
            ->with('success', 'Lowongan kerja berhasil dihapus.');
    }
}
