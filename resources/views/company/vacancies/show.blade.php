<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('company.vacancies.index') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 mb-1">
                    &larr; Kembali ke Daftar Lowongan
                </a>
                <h1 class="text-2xl font-bold text-slate-900">{{ $jobVacancy->position }}</h1>
                <p class="text-sm text-slate-500 mt-0.5">{{ $jobVacancy->company_name }} &bull; Diajukan {{ $jobVacancy->created_at->format('d F Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('company.vacancies.edit', $jobVacancy) }}"
                   class="inline-flex items-center px-4 py-2.5 rounded-lg bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm transition min-h-[44px]">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit / Revisi Data
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Status Banner -->
        @if ($jobVacancy->status === 'pending')
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-amber-900">Status: Menunggu Peninjauan Admin (Pending)</h2>
                    <p class="text-sm text-amber-800 mt-0.5">
                        Pengajuan lowongan kerja Anda sedang dalam antrean review oleh tim Career Center. Jika disetujui, poster resmi akan segera dibuat dan dipublikasikan.
                    </p>
                </div>
            </div>
        @elseif ($jobVacancy->status === 'approved')
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-emerald-900">Status: Disetujui (Approved)</h2>
                    <p class="text-sm text-emerald-800 mt-0.5">
                        Lowongan kerja Anda telah disetujui oleh Admin Career Center untuk dipublikasikan ke portal resmi UCC.
                    </p>
                </div>
            </div>
        @elseif ($jobVacancy->status === 'rejected')
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-base font-bold text-rose-900">Status: Perlu Revisi / Ditolak (Rejected)</h2>
                    <p class="text-sm text-rose-800 mt-1 font-semibold">Catatan Alasan dari Admin:</p>
                    <div class="bg-white/80 border border-rose-200 rounded-lg p-3 text-sm text-rose-950 mt-1 font-medium">
                        {{ $jobVacancy->rejection_reason }}
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('company.vacancies.edit', $jobVacancy) }}"
                           class="inline-flex items-center text-xs font-bold text-rose-700 hover:text-rose-900 underline">
                            Klik di sini untuk merevisi data lowongan ini &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @elseif ($jobVacancy->status === 'revised')
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-start gap-4">
                <div>
                    <h2 class="text-base font-bold text-blue-900">Status: Revisi Terkirim</h2>
                    <p class="text-sm text-blue-800 mt-1">Perubahan dan alasan revisi Anda sudah dikirim ke Admin untuk ditinjau ulang.</p>
                    @if ($jobVacancy->company_revision_reason)
                        <p class="text-sm text-blue-900 mt-2"><span class="font-semibold">Alasan Revisi:</span> {{ $jobVacancy->company_revision_reason }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Main Details Card -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="p-6 sm:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Perusahaan Pengaju</p>
                        <p class="text-lg font-bold text-slate-900 mt-1">{{ $jobVacancy->company_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Posisi Pekerjaan</p>
                        <p class="text-lg font-bold text-slate-900 mt-1">{{ $jobVacancy->position }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider text-xs mb-3">Kualifikasi & Persyaratan</h3>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-slate-800 text-sm leading-relaxed whitespace-pre-line">
                        {{ $jobVacancy->qualifications }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Informasi Tambahan & Benefit</h3>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            {{ $jobVacancy->other_info ?? 'Tidak ada catatan tambahan.' }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Alamat Penempatan</h3>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            {{ $jobVacancy->address }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
            <h3 class="text-base font-bold text-slate-900">Informasi Pengajuan Lengkap</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div><p class="text-xs font-semibold text-slate-400 uppercase">Bidang Perusahaan</p><p class="mt-1 text-slate-800">{{ $jobVacancy->business_area ?: 'Belum diisi' }}</p></div>
                <div><p class="text-xs font-semibold text-slate-400 uppercase">Provinsi</p><p class="mt-1 text-slate-800">{{ $jobVacancy->province ?: 'Belum diisi' }}</p></div>
                <div><p class="text-xs font-semibold text-slate-400 uppercase">Jumlah Kebutuhan</p><p class="mt-1 text-slate-800">{{ $jobVacancy->required_count ?: 'Belum diisi' }}</p></div>
                <div><p class="text-xs font-semibold text-slate-400 uppercase">Batas Akhir Lamaran</p><p class="mt-1 text-slate-800">{{ $jobVacancy->application_deadline?->format('d F Y') ?: 'Belum diisi' }}</p></div>
                <div><p class="text-xs font-semibold text-slate-400 uppercase">Cara Melamar</p><p class="mt-1 text-slate-800">{{ $jobVacancy->application_method ?: 'Belum diisi' }}{{ $jobVacancy->application_address ? ': '.$jobVacancy->application_address : '' }}</p></div>
                <div><p class="text-xs font-semibold text-slate-400 uppercase">Lokasi Penempatan</p><p class="mt-1 text-slate-800">{{ $jobVacancy->work_location ?: 'Sesuai alamat perusahaan' }}</p></div>
            </div>
            @if ($jobVacancy->job_description)
                <div class="border-t border-slate-100 pt-4"><p class="text-xs font-semibold text-slate-400 uppercase">Deskripsi Pekerjaan</p><p class="mt-1 text-sm leading-relaxed text-slate-700 whitespace-pre-line">{{ $jobVacancy->job_description }}</p></div>
            @endif
        </div>

        <!-- Edit Logs / Audit Trail for Company -->
        @if ($jobVacancy->editLogs->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Alasan Perubahan Data (Audit Trail)
                </h3>
                <div class="space-y-3">
                    @foreach ($jobVacancy->editLogs as $log)
                        <div class="border-l-2 border-blue-500 pl-4 py-1 text-sm">
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <span>{{ $log->created_at->format('d M Y, H:i') }} WIB</span>
                                &bull;
                                <span class="capitalize font-medium {{ $log->status === 'read' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $log->status === 'read' ? 'Sudah Ditinjau Admin' : 'Pesan Baru' }}
                                </span>
                            </div>
                            <p class="text-slate-800 font-medium mt-1">"{{ $log->edit_reason }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
