<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

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
     * Render raw HTML of the selected template used by the preview iframe.
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
     * Prepare a Vercel-compatible poster for download.
     */
    public function generate(Request $request, JobVacancy $jobVacancy): RedirectResponse
    {
        abort_unless($jobVacancy->status === 'approved', 403, 'Lowongan belum disetujui.');

        $request->validate([
            'template_id' => ['required', 'integer', 'in:1,2'],
        ]);

        $templateId = (int) $request->template_id;

        $filename = 'poster-job-'.$jobVacancy->id.'-template-'.$templateId.'.svg';

        $jobVacancy->update([
            'selected_template' => $templateId,
            'generated_poster_path' => 'posters/'.$filename,
        ]);

        return redirect()
            ->route('admin.posters.preview', $jobVacancy)
            ->with('success', 'Poster Instagram (1080x1350px) siap diunduh dalam format SVG.');
    }

    /**
     * Download the generated poster.
     */
    public function download(JobVacancy $jobVacancy): Response|RedirectResponse
    {
        if (! $jobVacancy->generated_poster_path || ! in_array($jobVacancy->selected_template, [1, 2], true)) {
            return redirect()
                ->back()
                ->with('error', 'Poster belum di-generate.');
        }

        $downloadName = 'poster-'.Str::slug($jobVacancy->company_name.'-'.$jobVacancy->position).'.svg';

        return response($this->posterSvg($jobVacancy), 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$downloadName.'"',
            'Cache-Control' => 'no-store',
        ]);
    }

    private function posterSvg(JobVacancy $jobVacancy): string
    {
        $escape = static fn (?string $value): string => htmlspecialchars(trim((string) $value), ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $company = $escape($jobVacancy->company_name);
        $position = $escape($jobVacancy->position);
        $qualifications = $escape($jobVacancy->qualifications ?: 'Kualifikasi belum dicantumkan.');
        $otherInfo = $escape($jobVacancy->other_info ?: 'Informasi tambahan belum dicantumkan.');
        $address = $escape($jobVacancy->address ?: 'Alamat belum dicantumkan.');
        $location = $escape($jobVacancy->work_location ?: '');
        $application = $escape(trim(implode(': ', array_filter([
            $jobVacancy->application_method,
            $jobVacancy->application_address,
        ]))));
        $deadline = $jobVacancy->application_deadline?->format('d M Y');

        $lines = static function (string $text, int $maxLength): array {
            $result = [];
            foreach (preg_split('/\R/', html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8')) ?: [] as $line) {
                $result = [...$result, ...str_split($line, $maxLength)];
            }

            return array_slice($result ?: [''], 0, 12);
        };

        $qualificationLines = $lines($qualifications, 38);
        $infoLines = $lines($otherInfo, 30);
        $addressLines = $lines($address, 28);
        $positionLines = $lines($position, 28);
        $dark = $jobVacancy->selected_template === 2;
        $background = $dark ? '#0f172a' : '#07195c';
        $panel = $dark ? '#1e293b' : '#ffffff';
        $text = $dark ? '#f8fafc' : '#0f172a';
        $muted = $dark ? '#cbd5e1' : '#334155';
        $accent = $dark ? '#34d399' : '#ffb900';

        $textBlock = static function (array $items, int $x, int $y, int $lineHeight, string $color) use ($escape): string {
            return collect($items)->map(
                fn (string $line, int $index): string => '<text x="'.$x.'" y="'.($y + ($index * $lineHeight)).'" fill="'.$color.'" font-size="25" font-family="Arial, sans-serif">'. $escape($line) .'</text>'
            )->implode('');
        };

        $positionBlock = collect($positionLines)->map(
            fn (string $line, int $index): string => '<text x="90" y="'.(330 + ($index * 48)).'" fill="'.$accent.'" font-size="38" font-weight="700" font-family="Arial, sans-serif">'.$escape($line).'</text>'
        )->implode('');

        return '<svg xmlns="http://www.w3.org/2000/svg" width="1080" height="1350" viewBox="0 0 1080 1350">
            <rect width="1080" height="1350" fill="'.$background.'"/>
            <circle cx="980" cy="80" r="180" fill="'.$accent.'" opacity=".18"/>
            <circle cx="90" cy="1290" r="230" fill="'.$accent.'" opacity=".12"/>
            <text x="90" y="105" fill="'.$accent.'" font-size="28" font-weight="700" letter-spacing="4" font-family="Arial, sans-serif">LOWONGAN PEKERJAAN</text>
            <text x="90" y="180" fill="#ffffff" font-size="52" font-weight="800" font-family="Arial, sans-serif">UCC CAREER NETWORK</text>
            <text x="90" y="250" fill="'.$muted.'" font-size="28" font-family="Arial, sans-serif">'.$company.'</text>
            <rect x="60" y="285" width="960" height="215" rx="28" fill="'.$panel.'"/>
            <text x="90" y="315" fill="'.$muted.'" font-size="20" font-weight="700" font-family="Arial, sans-serif">POSISI YANG DIBUTUHKAN</text>
            '.$positionBlock.'
            <rect x="60" y="535" width="960" height="385" rx="28" fill="'.$panel.'"/>
            <text x="90" y="595" fill="'.$accent.'" font-size="30" font-weight="700" font-family="Arial, sans-serif">KUALIFIKASI</text>
            '.$textBlock($qualificationLines, 90, 650, 34, $text).'
            <rect x="60" y="955" width="465" height="300" rx="28" fill="'.$panel.'"/>
            <text x="90" y="1015" fill="'.$accent.'" font-size="27" font-weight="700" font-family="Arial, sans-serif">INFORMASI</text>
            '.$textBlock($infoLines, 90, 1065, 32, $text).'
            <rect x="555" y="955" width="465" height="300" rx="28" fill="'.$panel.'"/>
            <text x="585" y="1015" fill="'.$accent.'" font-size="27" font-weight="700" font-family="Arial, sans-serif">LOKASI & LAMARAN</text>
            '.$textBlock($addressLines, 585, 1065, 32, $text).'
            '.($location !== '' ? '<text x="585" y="1195" fill="'.$muted.'" font-size="22" font-family="Arial, sans-serif">Penempatan: '.$location.'</text>' : '').'
            '.($application !== '' ? '<text x="585" y="1225" fill="'.$muted.'" font-size="20" font-family="Arial, sans-serif">'.$application.'</text>' : '').'
            '.($deadline ? '<text x="585" y="1250" fill="'.$muted.'" font-size="20" font-family="Arial, sans-serif">Batas akhir: '.$escape($deadline).'</text>' : '').'
        </svg>';
    }
}
