<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 mb-1">
                    &larr; Kembali ke Dashboard Review
                </a>
                <h1 class="text-2xl font-bold text-slate-900">Review Pengajuan Lowongan</h1>
                <p class="text-sm text-slate-500 mt-0.5">{{ $jobVacancy->company_name }} &bull; Diajukan {{ $jobVacancy->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                @if ($jobVacancy->status === 'approved')
                    <a href="{{ route('admin.posters.preview', $jobVacancy) }}"
                       class="inline-flex items-center px-4 py-2.5 rounded-lg bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm transition min-h-[44px]">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Buka Template Poster
                    </a>
                @else
                    <form method="POST" action="{{ route('admin.vacancies.approve', $jobVacancy) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm transition min-h-[44px]">
                            Setujui (Approve)
                        </button>
                    </form>

                    <button type="button"
                            @click="openReject('{{ route('admin.vacancies.reject', $jobVacancy) }}', '{{ addslashes($jobVacancy->position) }}', '{{ addslashes($jobVacancy->company_name) }}')"
                            class="inline-flex items-center px-4 py-2.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 font-semibold text-sm transition min-h-[44px]">
                        Tolak (Reject)...
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div x-data="{
        showRejectModal: false,
        rejectActionUrl: '',
        jobPosition: '',
        companyName: '',
        openReject(url, pos, comp) {
            this.rejectActionUrl = url;
            this.jobPosition = pos;
            this.companyName = comp;
            this.showRejectModal = true;
            this.$nextTick(() => { this.$refs.rejectionReasonInput.focus(); });
        },
        closeReject() {
            this.showRejectModal = false;
            this.rejectActionUrl = '';
        }
    }"
    @keydown.escape.window="if (showRejectModal) closeReject()"
    class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Status Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-slate-500">Status Pengajuan Saat Ini:</span>
                @if ($jobVacancy->status === 'pending')
                    <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                        Menunggu Review
                    </span>
                @elseif ($jobVacancy->status === 'approved')
                    <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        Disetujui (Approved)
                    </span>
                @elseif ($jobVacancy->status === 'rejected')
                    <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200">
                        Ditolak (Perlu Revisi)
                    </span>
                @endif
            </div>

            @if ($jobVacancy->status === 'approved' && $jobVacancy->generated_poster_path)
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-md border border-emerald-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Poster Telah Di-generate
                </span>
            @endif
        </div>

        @if ($jobVacancy->rejection_reason)
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-5 text-rose-900">
                <p class="font-bold text-sm">Catatan Penolakan Sebelumnya:</p>
                <p class="text-sm mt-1 text-rose-800">{{ $jobVacancy->rejection_reason }}</p>
            </div>
        @endif

        <!-- Details Grid -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="p-6 sm:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Perusahaan Pengaju</p>
                        <p class="text-xl font-bold text-slate-900 mt-1">{{ $jobVacancy->company_name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Email Akun: {{ $jobVacancy->company->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Posisi Pekerjaan</p>
                        <p class="text-xl font-bold text-slate-900 mt-1">{{ $jobVacancy->position }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Kualifikasi & Persyaratan</h3>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-slate-800 text-sm leading-relaxed whitespace-pre-line">
                        {{ $jobVacancy->qualifications }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Informasi Tambahan & Benefit</h3>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            {{ $jobVacancy->other_info ?? 'Tidak dicantumkan.' }}
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

        <!-- Audit Trail / Edit Logs Section -->
        @if ($jobVacancy->editLogs->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-4">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Perubahan & Catatan dari Perusahaan
                </h3>

                <div class="space-y-4">
                    @foreach ($jobVacancy->editLogs as $log)
                        <div class="border rounded-xl p-4 {{ $log->status === 'unread' ? 'bg-purple-50/50 border-purple-200' : 'bg-slate-50 border-slate-200' }}">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                                <span>{{ $log->created_at->format('d M Y, H:i') }} WIB</span>
                                @if ($log->status === 'unread')
                                    <form method="POST" action="{{ route('admin.logs.read', $log) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-purple-700 font-bold hover:underline">
                                            Tandai Sudah Dibaca
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 font-medium">Telah dibaca</span>
                                @endif
                            </div>
                            <p class="text-sm font-semibold text-slate-900">Alasan Perubahan:</p>
                            <p class="text-sm text-slate-700 mt-0.5">"{{ $log->edit_reason }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- ACCESSIBLE REJECT MODAL -->
        <div x-show="showRejectModal"
             x-cloak
             role="dialog"
             aria-modal="true"
             aria-labelledby="modal-headline-detail"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
                 @click="closeReject()"></div>

            <div class="min-h-screen px-4 text-center flex items-center justify-center">
                <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full p-6 sm:p-8 z-10 border border-slate-200">
                    <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900" id="modal-headline-detail">
                            Alasan Penolakan Lowongan
                        </h3>
                        <button type="button"
                                @click="closeReject()"
                                class="text-slate-400 hover:text-slate-600 rounded-lg p-1.5 hover:bg-slate-100">
                            <span class="sr-only">Tutup (Escape)</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form :action="rejectActionUrl" method="POST" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label for="rejection_reason_detail" class="block text-sm font-semibold text-slate-800 mb-1.5">
                                Catatan Perbaikan untuk Perusahaan <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="rejection_reason_detail"
                                      name="rejection_reason"
                                      x-ref="rejectionReasonInput"
                                      rows="4"
                                      required
                                      class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-rose-600 focus:ring-2 focus:ring-rose-500/20 outline-none transition"
                                      placeholder="Tuliskan alasan penolakan secara jelas."></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button"
                                    @click="closeReject()"
                                    class="px-4 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-medium text-sm hover:bg-slate-50 transition min-h-[44px]">
                                Batal (Esc)
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold text-sm transition focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 min-h-[44px]">
                                Tolak Lowongan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
