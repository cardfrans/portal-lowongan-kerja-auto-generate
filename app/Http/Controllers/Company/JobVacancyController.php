<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobVacancyRequest;
use App\Http\Requests\UpdateJobVacancyRequest;
use App\Models\JobEditLog;
use App\Models\JobVacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of vacancies submitted by the authenticated company.
     */
    public function index(Request $request): View
    {
        $vacancyQuery = $request->user()->jobVacancies();
        $query = (clone $vacancyQuery)->select([
            'id',
            'company_id',
            'position',
            'address',
            'status',
            'rejection_reason',
            'admin_reject_reason',
            'created_at',
        ])->latest();

        if ($request->filled('status') && in_array($request->status, ['draft', 'pending', 'approved', 'rejected', 'revised'], true)) {
            $query->where('status', $request->status);
        }

        $vacancies = $query->paginate(10)->withQueryString();

        $stats = (clone $vacancyQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total' => $stats->sum(),
            'pending' => $stats->get('pending', 0),
            'approved' => $stats->get('approved', 0),
            'rejected' => $stats->get('rejected', 0),
            'revised' => $stats->get('revised', 0),
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
        $vacancy->load([
            'editLogs' => fn ($query) => $query->select([
                'id',
                'job_vacancy_id',
                'edit_reason',
                'status',
                'created_at',
            ]),
        ]);

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
     * Update the specified vacancy and record the edit reason.
     */
    public function update(UpdateJobVacancyRequest $request, JobVacancy $vacancy): RedirectResponse
    {
        $this->authorize('update', $vacancy);

        $data = $request->validated();
        unset($data['information_consent'], $data['publication_consent']);
        $data['status'] = $vacancy->status === 'rejected' ? 'revised' : 'pending';
        $data['company_revision_reason'] = $request->edit_reason;
        $data['rejection_reason'] = $vacancy->rejection_reason;

        DB::transaction(function () use ($vacancy, $request, $data): void {
            JobEditLog::create([
                'job_vacancy_id' => $vacancy->id,
                'edit_reason' => $request->edit_reason,
                'old_data' => [],
                'status' => 'unread',
            ]);

            $vacancy->update($data);
        });

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
