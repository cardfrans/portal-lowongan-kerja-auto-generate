<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Daftar Lowongan Perusahaan</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola dan pantau status pengajuan kebutuhan tenaga kerja perusahaan Anda.</p>
            </div>
            <div>
                <a href="{{ route('company.vacancies.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 min-h-[44px]">
                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajukan Lowongan Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Metric Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pengajuan</p>
                <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Menunggu Review</p>
                <p class="text-3xl font-extrabold text-amber-600 mt-2">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Disetujui (Approved)</p>
                <p class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $stats['approved'] }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">Perlu Revisi (Rejected)</p>
                <p class="text-3xl font-extrabold text-rose-600 mt-2">{{ $stats['rejected'] }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Revisi Terkirim</p>
                <p class="text-3xl font-extrabold text-blue-600 mt-2">{{ $stats['revised'] }}</p>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-3 overflow-x-auto">
            <a href="{{ route('company.vacancies.index') }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition min-h-[40px] flex items-center {{ !request('status') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Semua
            </a>
            <a href="{{ route('company.vacancies.index', ['status' => 'pending']) }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition min-h-[40px] flex items-center {{ request('status') === 'pending' ? 'bg-amber-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Menunggu Review ({{ $stats['pending'] }})
            </a>
            <a href="{{ route('company.vacancies.index', ['status' => 'approved']) }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition min-h-[40px] flex items-center {{ request('status') === 'approved' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Disetujui ({{ $stats['approved'] }})
            </a>
            <a href="{{ route('company.vacancies.index', ['status' => 'rejected']) }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition min-h-[40px] flex items-center {{ request('status') === 'rejected' ? 'bg-rose-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Perlu Revisi ({{ $stats['rejected'] }})
            </a>
            <a href="{{ route('company.vacancies.index', ['status' => 'revised']) }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition min-h-[40px] flex items-center {{ request('status') === 'revised' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Revisi Terkirim ({{ $stats['revised'] }})
            </a>
        </div>

        <!-- Vacancies List -->
        @if ($vacancies->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <div class="w-16 h-16 bg-blue-50 text-blue-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Pengajuan Lowongan</h3>
                <p class="text-sm text-slate-500 max-w-md mx-auto mt-2">
                    {{ request('status') ? 'Tidak ada lowongan dengan filter status ini.' : 'Perusahaan Anda belum mengajukan kebutuhan lowongan kerja ke Career Center.' }}
                </p>
                <div class="mt-6">
                    <a href="{{ route('company.vacancies.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-lg bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm transition min-h-[44px]">
                        + Ajukan Lowongan Pertama
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                <!-- Desktop & Tablet Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-xs tracking-wider">
                                <th class="py-3.5 px-6">Posisi Pekerjaan</th>
                                <th class="py-3.5 px-6">Tanggal Pengajuan</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($vacancies as $job)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-slate-900 text-base">{{ $job->position }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5 truncate max-w-md">{{ Str::limit($job->address, 60) }}</div>
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
                                                Disetujui (Approved)
                                            </span>
                                        @elseif ($job->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                                Perlu Revisi (Rejected)
                                            </span>
                                            @if ($job->rejection_reason)
                                                <p class="mt-2 max-w-xs text-xs leading-relaxed text-rose-800">
                                                    <span class="font-bold">Catatan Admin:</span>
                                                    {{ Str::limit($job->rejection_reason, 120) }}
                                                </p>
                                            @endif
                                        @elseif ($job->status === 'revised')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                                Revisi Terkirim
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center gap-2 justify-end">
                                            <a href="{{ route('company.vacancies.show', $job) }}"
                                               class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-medium transition min-h-[36px] flex items-center">
                                                Detail
                                            </a>
                                            <a href="{{ route('company.vacancies.edit', $job) }}"
                                               class="px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100 text-xs font-medium transition min-h-[36px] flex items-center">
                                                Edit / Revisi
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card Stack (Anti-Slop Layout Mobile) -->
                <div class="md:hidden divide-y divide-slate-100">
                    @foreach ($vacancies as $job)
                        <div class="p-5 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $job->position }}</h3>
                                    <p class="text-xs text-slate-500 mt-1">{{ $job->created_at->format('d M Y') }}</p>
                                </div>
                                <div>
                                    @if ($job->status === 'pending')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                            Pending
                                        </span>
                                    @elseif ($job->status === 'approved')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Approved
                                        </span>
                                    @elseif ($job->status === 'rejected')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                            Revisi
                                        </span>
                                    @elseif ($job->status === 'revised')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                            Revisi Terkirim
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-xs text-slate-600 line-clamp-2">{{ $job->address }}</p>

                            @if ($job->status === 'rejected' && $job->rejection_reason)
                                <div class="rounded-lg border border-rose-200 bg-rose-50 p-3 text-xs leading-relaxed text-rose-900">
                                    <span class="font-bold">Catatan Admin:</span>
                                    {{ $job->rejection_reason }}
                                </div>
                            @endif

                            <div class="flex items-center gap-2 pt-2">
                                <a href="{{ route('company.vacancies.show', $job) }}"
                                   class="flex-1 text-center py-2.5 rounded-lg border border-slate-200 text-slate-700 font-medium text-xs hover:bg-slate-50 min-h-[44px] flex items-center justify-center">
                                    Lihat Detail
                                </a>
                                <a href="{{ route('company.vacancies.edit', $job) }}"
                                   class="flex-1 text-center py-2.5 rounded-lg bg-blue-600 text-white font-medium text-xs hover:bg-blue-700 min-h-[44px] flex items-center justify-center">
                                    Edit / Revisi
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($vacancies->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $vacancies->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
