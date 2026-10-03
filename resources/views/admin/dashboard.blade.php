<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Dashboard Peninjauan Admin</h1>
                <p class="text-sm text-slate-500 mt-1">Tinjau kebutuhan lowongan kerja perusahaan dan generate poster Instagram resmi.</p>
            </div>
            @if ($stats['unread_logs'] > 0)
                <a href="{{ route('admin.logs.index') }}"
                   class="inline-flex items-center px-3.5 py-2 rounded-lg bg-purple-50 text-purple-800 border border-purple-200 text-xs font-semibold hover:bg-purple-100 transition min-h-[44px]">
                    <span class="w-2 h-2 rounded-full bg-purple-600 me-2"></span>
                    {{ $stats['unread_logs'] }} Alasan Edit Perusahaan Perlu Dibaca &rarr;
                </a>
            @endif
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
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}"
               class="bg-white p-5 rounded-xl border border-slate-200 hover:border-amber-400 transition block">
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Menunggu Review</p>
                <p class="text-3xl font-extrabold text-amber-600 mt-2">{{ $stats['pending'] }}</p>
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'approved']) }}"
               class="bg-white p-5 rounded-xl border border-slate-200 hover:border-emerald-400 transition block">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Disetujui (Approved)</p>
                <p class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $stats['approved'] }}</p>
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'rejected']) }}"
               class="bg-white p-5 rounded-xl border border-slate-200 hover:border-rose-400 transition block">
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">Ditolak (Rejected)</p>
                <p class="text-3xl font-extrabold text-rose-600 mt-2">{{ $stats['rejected'] }}</p>
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'revised']) }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition min-h-[40px] flex items-center {{ $status === 'revised' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Revisi Masuk ({{ $stats['revised'] }})
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'all']) }}"
               class="bg-white p-5 rounded-xl border border-slate-200 hover:border-slate-400 transition block">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pengajuan</p>
                <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $stats['total'] }}</p>
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-3 overflow-x-auto">
            <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition min-h-[40px] flex items-center {{ $status === 'pending' ? 'bg-amber-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Menunggu Review ({{ $stats['pending'] }})
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'approved']) }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition min-h-[40px] flex items-center {{ $status === 'approved' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Disetujui ({{ $stats['approved'] }})
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'rejected']) }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition min-h-[40px] flex items-center {{ $status === 'rejected' ? 'bg-rose-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Ditolak ({{ $stats['rejected'] }})
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'all']) }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition min-h-[40px] flex items-center {{ $status === 'all' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Semua Data ({{ $stats['total'] }})
            </a>
        </div>

        <!-- Vacancies Table -->
        @if ($vacancies->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Tidak Ada Antrean Pengajuan</h3>
                <p class="text-sm text-slate-500 max-w-md mx-auto mt-2">
                    {{ $status === 'pending' ? 'Semua pengajuan lowongan kerja telah selesai ditinjau.' : 'Tidak ada lowongan dengan filter status ini.' }}
                </p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                <!-- Desktop & Tablet Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-xs tracking-wider">
                                <th class="py-3.5 px-6">Perusahaan & Posisi</th>
                                <th class="py-3.5 px-6">Tanggal Pengajuan</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi Review / Poster</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($vacancies as $job)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-slate-900 text-base">{{ $job->position }}</div>
                                        <div class="text-xs font-medium text-slate-500 mt-0.5">{{ $job->company_name }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600">
                                        {{ $job->created_at->format('d M Y') }}
                                        <div class="text-xs text-slate-400">{{ $job->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($job->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                Menunggu Review
                                            </span>
                                        @elseif ($job->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                Disetujui
                                            </span>
                                        @elseif ($job->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                                Ditolak
                                            </span>
                                        @elseif ($job->status === 'revised')
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                                Revisi Masuk
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center gap-2 justify-end flex-wrap">
                                            <a href="{{ route('admin.vacancies.show', $job) }}"
                                               class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-medium transition min-h-[36px] flex items-center">
                                                Tinjau
                                            </a>

                                            @if ($job->status === 'approved')
                                                <a href="{{ route('admin.posters.preview', $job) }}"
                                                   class="px-3.5 py-1.5 rounded-lg bg-blue-700 hover:bg-blue-800 text-white text-xs font-semibold transition min-h-[36px] flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    Generate Poster
                                                </a>
                                            @else
                                                <form method="POST" action="{{ route('admin.vacancies.approve', $job) }}" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition min-h-[36px] flex items-center">
                                                        Approve
                                                    </button>
                                                </form>

                                                <button type="button"
                                                        @click="openReject('{{ route('admin.vacancies.reject', $job) }}', '{{ addslashes($job->position) }}', '{{ addslashes($job->company_name) }}')"
                                                        class="px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 text-xs font-semibold transition min-h-[36px] flex items-center">
                                                    Reject...
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card Stack -->
                <div class="md:hidden divide-y divide-slate-100">
                    @foreach ($vacancies as $job)
                        <div class="p-5 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $job->position }}</h3>
                                    <p class="text-xs font-medium text-slate-600 mt-0.5">{{ $job->company_name }}</p>
                                    <p class="text-xs text-slate-400 mt-1">{{ $job->created_at->format('d M Y') }}</p>
                                </div>
                                <div>
                                    @if (in_array($job->status, ['pending', 'revised']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                            Pending
                                        </span>
                                    @elseif ($job->status === 'approved')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Approved
                                        </span>
                                    @elseif ($job->status === 'rejected')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                            Rejected
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-2 flex-wrap">
                                <a href="{{ route('admin.vacancies.show', $job) }}"
                                   class="flex-1 text-center py-2.5 rounded-lg border border-slate-200 text-slate-700 font-medium text-xs hover:bg-slate-50 min-h-[44px] flex items-center justify-center">
                                    Tinjau Detail
                                </a>

                                @if ($job->status === 'approved')
                                    <a href="{{ route('admin.posters.preview', $job) }}"
                                       class="flex-1 text-center py-2.5 rounded-lg bg-blue-700 text-white font-semibold text-xs hover:bg-blue-800 min-h-[44px] flex items-center justify-center">
                                        Poster
                                    </a>
                                @else
                                    <form method="POST" action="{{ route('admin.vacancies.approve', $job) }}" class="flex-1">
                                        @csrf
                                        <button type="submit"
                                                class="w-full text-center py-2.5 rounded-lg bg-emerald-600 text-white font-semibold text-xs hover:bg-emerald-700 min-h-[44px] flex items-center justify-center">
                                            Approve
                                        </button>
                                    </form>

                                    <button type="button"
                                            @click="openReject('{{ route('admin.vacancies.reject', $job) }}', '{{ addslashes($job->position) }}', '{{ addslashes($job->company_name) }}')"
                                            class="flex-1 text-center py-2.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 font-semibold text-xs hover:bg-rose-100 min-h-[44px] flex items-center justify-center">
                                        Reject
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($vacancies->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $vacancies->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- ACCESSIBLE REJECT MODAL (R-32 Keyboard Accessible: Escape, focus ring, autofocus) -->
        <div x-show="showRejectModal"
             x-cloak
             role="dialog"
             aria-modal="true"
             aria-labelledby="modal-headline"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
                 @click="closeReject()"></div>

            <div class="min-h-screen px-4 text-center flex items-center justify-center">
                <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full p-6 sm:p-8 z-10 border border-slate-200">
                    <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900" id="modal-headline">
                                Alasan Penolakan Lowongan
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                <span x-text="companyName" class="font-semibold"></span> &bull; <span x-text="jobPosition"></span>
                            </p>
                        </div>
                        <button type="button"
                                @click="closeReject()"
                                class="text-slate-400 hover:text-slate-600 rounded-lg p-1.5 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400">
                            <span class="sr-only">Tutup Modal (Escape)</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form :action="rejectActionUrl" method="POST" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label for="rejection_reason" class="block text-sm font-semibold text-slate-800 mb-1.5">
                                Catatan Perbaikan untuk Perusahaan <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="rejection_reason"
                                      name="rejection_reason"
                                      x-ref="rejectionReasonInput"
                                      rows="4"
                                      required
                                      class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-rose-600 focus:ring-2 focus:ring-rose-500/20 outline-none transition"
                                      placeholder="Tuliskan catatan perbaikan (contoh: Kualifikasi pendidikan mohon diperjelas, dan sertakan link form pendaftaran)."></textarea>
                            <p class="text-xs text-slate-500 mt-1.5">Pesan ini akan ditampilkan pada dashboard perusahaan agar dapat direvisi.</p>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button"
                                    @click="closeReject()"
                                    class="px-4 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-medium text-sm hover:bg-slate-50 transition min-h-[44px]">
                                Batal (Esc)
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold text-sm transition focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 min-h-[44px]">
                                Konfirmasi Tolak (Reject)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
