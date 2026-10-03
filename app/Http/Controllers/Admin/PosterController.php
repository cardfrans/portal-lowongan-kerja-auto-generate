<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PosterController extends Controller
{
    /**
     * Display the poster template preview and generation interface.
     */
    public function preview(JobVacancy $jobVacancy): View
    {
        abort_unless($jobVacancy->status === 'approved', 403, 'Poster hanya dapat di-generate untuk lowongan yang telah disetujui (Approved).');

        return view('admin.posters.preview', compact('jobVacancy'));
    }

    /**
     * Render raw HTML of the selected template (used by preview iframe and Browsershot).
     */
    public function renderHtml(JobVacancy $jobVacancy, int $templateId): View
    {
        abort_unless(in_array($templateId, [1, 2], true), 404, 'Template tidak ditemukan.');
        $jobVacancy->loadMissing('company');

        $viewName = match ($templateId) {
            1 => 'posters.template1',
            2 => 'posters.template2',
        };

        return view($viewName, ['job' => $jobVacancy, 'logoDataUri' => $jobVacancy->company?->logoDataUri()]);
    }

    /**
     * Generate the Instagram 1080x1350 JPG poster using Browsershot.
     */
    public function generate(Request $request, JobVacancy $jobVacancy): RedirectResponse
    {
        abort_unless($jobVacancy->status === 'approved', 403, 'Lowongan belum disetujui.');

        $request->validate([
            'template_id' => ['required', 'integer', 'in:1,2'],
        ]);

        $templateId = (int) $request->template_id;

        // 1. Ensure destination directory exists
        $posterDir = storage_path('app/public/posters');
        if (! File::exists($posterDir)) {
            File::makeDirectory($posterDir, 0755, true);
        }

        $filename = 'poster-job-'.$jobVacancy->id.'-template-'.$templateId.'-'.time().'.jpg';
        $fullPath = $posterDir.DIRECTORY_SEPARATOR.$filename;
        $publicStoragePath = 'posters/'.$filename;

        // 2. Render Blade template to HTML string
        $viewName = match ($templateId) {
            1 => 'posters.template1',
            2 => 'posters.template2',
        };
        $jobVacancy->loadMissing('company');
        $html = view($viewName, ['job' => $jobVacancy, 'logoDataUri' => $jobVacancy->company?->logoDataUri()])->render();

        try {
            // 3. Configure Browsershot
            $browsershot = Browsershot::html($html)
                ->windowSize(1080, 1350)
                ->deviceScaleFactor(1)
                ->waitUntilNetworkIdle()
                ->showBackground()
                ->addChromiumArguments(['hide-scrollbars']);

            // Set Chrome path on Windows if available
            $chromePath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
            if (File::exists($chromePath)) {
                $browsershot->setChromePath($chromePath);
            }

            // Set Node and NPM binaries
            $nodePath = 'C:\\Program Files\\nodejs\\node.exe';
            $npmPath = 'C:\\Program Files\\nodejs\\npm.cmd';
            if (File::exists($nodePath)) {
                $browsershot->setNodeBinary($nodePath);
            }
            if (File::exists($npmPath)) {
                $browsershot->setNpmBinary($npmPath);
            }

            // Execute save
            $browsershot->setScreenshotType('jpeg', 90)->save($fullPath);

            // 4. Update job vacancy record
            $jobVacancy->update([
                'selected_template' => $templateId,
                'generated_poster_path' => $publicStoragePath,
            ]);

            return redirect()
                ->route('admin.posters.preview', $jobVacancy)
                ->with('success', 'Poster Instagram (1080x1350px) berhasil dibuat dalam format JPG.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('admin.posters.preview', $jobVacancy)
                ->with('error', 'Gagal men-generate poster dengan Browsershot: '.$e->getMessage());
        }
    }

    /**
     * Download the generated poster.
     */
    public function download(JobVacancy $jobVacancy): BinaryFileResponse|RedirectResponse
    {
        if (! $jobVacancy->generated_poster_path || ! Storage::disk('public')->exists($jobVacancy->generated_poster_path)) {
            return redirect()
                ->back()
                ->with('error', 'File poster belum di-generate atau tidak ditemukan di penyimpanan.');
        }

        $fullPath = storage_path('app/public/'.$jobVacancy->generated_poster_path);
        $downloadName = 'poster-'.Str::slug($jobVacancy->company_name.'-'.$jobVacancy->position).'.jpg';

        return response()->download($fullPath, $downloadName);
    }
}
