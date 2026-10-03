<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RejectJobVacancyRequest;
use App\Models\JobEditLog;
use App\Models\JobVacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display admin overview and pending review queue.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');

        $query = JobVacancy::with('company')->latest();

        if (in_array($status, ['pending', 'approved', 'rejected', 'revised'])) {
            $query->where('status', $status);
        }

        $vacancies = $query->paginate(10)->withQueryString();

        $stats = [
            'pending' => JobVacancy::whereIn('status', ['pending', 'revised'])->count(),
            'approved' => JobVacancy::where('status', 'approved')->count(),
            'rejected' => JobVacancy::where('status', 'rejected')->count(),
            'revised' => JobVacancy::where('status', 'revised')->count(),
            'total' => JobVacancy::count(),
            'unread_logs' => JobEditLog::where('status', 'unread')->count(),
        ];

        return view('admin.dashboard', compact('vacancies', 'stats', 'status'));
    }

    /**
     * Display vacancy detail for administrative review.
     */
    public function show(JobVacancy $jobVacancy): View
    {
        $jobVacancy->load(['company', 'editLogs']);

        return view('admin.vacancies.show', compact('jobVacancy'));
    }

    /**
     * Approve a job vacancy.
     */
    public function approve(JobVacancy $jobVacancy): RedirectResponse
    {
        $jobVacancy->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'admin_reject_reason' => null,
            'company_revision_reason' => null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Lowongan kerja berhasil disetujui (Approved). Tombol Generate Poster sekarang aktif.');
    }

    /**
     * Reject a job vacancy with a required reason.
     */
    public function reject(RejectJobVacancyRequest $request, JobVacancy $jobVacancy): RedirectResponse
    {
        $jobVacancy->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'admin_reject_reason' => $request->rejection_reason,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Pengajuan lowongan berhasil ditolak (Rejected) beserta catatan alasan perbaikan untuk perusahaan.');
    }

    /**
     * List all audit edit logs submitted by companies.
     */
    public function logs(Request $request): View
    {
        $logs = JobEditLog::with(['jobVacancy.company'])
            ->latest()
            ->paginate(15);

        return view('admin.logs.index', compact('logs'));
    }

    /**
     * Mark an edit log as read.
     */
    public function markLogAsRead(JobEditLog $log): RedirectResponse
    {
        $log->update(['status' => 'read']);

        return redirect()
            ->back()
            ->with('success', 'Catatan alasan edit telah ditandai sebagai dibaca.');
    }
}
