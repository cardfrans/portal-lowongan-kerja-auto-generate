<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 mb-1">
                    &larr; Kembali ke Dashboard Review
                </a>
                <h1 class="text-2xl font-bold text-slate-900">Audit Trail Alasan Edit Perusahaan</h1>
                <p class="text-sm text-slate-500 mt-0.5">Daftar rekaman perubahan data lowongan yang diajukan oleh perusahaan.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($logs->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Riwayat Perubahan</h3>
                <p class="text-sm text-slate-500 max-w-md mx-auto mt-2">
                    Belum ada perusahaan yang melakukan perubahan/edit pada data lowongan kerja.
                </p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-xs tracking-wider">
                                <th class="py-3.5 px-6">Tanggal & Jam</th>
                                <th class="py-3.5 px-6">Perusahaan & Lowongan</th>
                                <th class="py-3.5 px-6">Alasan Perubahan (Pesan)</th>
                                <th class="py-3.5 px-6">Snapshot Data Lama</th>
                                <th class="py-3.5 px-6 text-right">Status / Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($logs as $log)
                                <tr class="hover:bg-slate-50/70 transition {{ $log->status === 'unread' ? 'bg-purple-50/20' : '' }}">
                                    <td class="py-4 px-6 whitespace-nowrap text-slate-600">
                                        <div class="font-medium text-slate-900">{{ $log->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-slate-400">{{ $log->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($log->jobVacancy)
                                            <a href="{{ route('admin.vacancies.show', $log->jobVacancy) }}"
                                               class="font-bold text-blue-700 hover:text-blue-900">
                                                {{ $log->jobVacancy->position }}
                                            </a>
                                            <div class="text-xs text-slate-500 mt-0.5">{{ $log->jobVacancy->company_name }}</div>
                                        @else
                                            <span class="text-slate-400 italic">Lowongan telah dihapus</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 max-w-sm">
                                        <div class="font-semibold text-slate-900 text-sm">"{{ $log->edit_reason }}"</div>
                                    </td>
                                    <td class="py-4 px-6 text-xs text-slate-500 max-w-xs">
                                        <details class="cursor-pointer">
                                            <summary class="font-medium text-blue-600 hover:underline">Lihat Snapshot JSON</summary>
                                            <pre class="mt-2 p-2 bg-slate-900 text-slate-100 rounded text-[11px] overflow-x-auto max-h-32">{{ json_encode($log->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </details>
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        @if ($log->status === 'unread')
                                            <form method="POST" action="{{ route('admin.logs.read', $log) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="px-3 py-1.5 rounded-lg bg-purple-100 hover:bg-purple-200 text-purple-800 text-xs font-bold transition min-h-[36px]">
                                                    Tandai Dibaca
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center text-xs font-semibold text-emerald-600">
                                                <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Sudah Dibaca
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($logs->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
