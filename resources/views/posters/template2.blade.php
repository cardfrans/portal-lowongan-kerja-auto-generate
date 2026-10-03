@extends('posters.layout')

@section('content')
<div class="w-[1080px] h-[1350px] bg-slate-950 text-slate-100 flex flex-col p-16 box-border">
    <!-- Header -->
    <div>
        <div class="flex items-center justify-between border-b border-slate-800 pb-8">
            <span class="text-xl font-bold tracking-wider text-emerald-400">LOWONGAN PEKERJAAN</span>
            <span class="text-lg font-medium text-slate-400">UCC CAREER NETWORK</span>
        </div>

        <div class="mt-8 flex items-start gap-6">
            @if ($logoDataUri)
                <img src="{{ $logoDataUri }}" alt="Logo {{ $job->company_name }}" class="h-28 w-28 shrink-0 object-contain bg-white rounded-xl p-3">
            @endif
            <div class="min-w-0">
                <p class="text-2xl leading-tight font-bold text-emerald-400 mb-3 break-words">{{ $job->company_name }}</p>
                <h1 class="text-4xl font-black text-white leading-tight tracking-tight break-words">
                    {{ $job->position }}
                </h1>
            </div>
        </div>
    </div>

    <!-- Requirements Card -->
    <div class="flex-1 min-h-0 bg-slate-900 border border-slate-800 rounded-2xl p-8 my-6 flex flex-col">
        <h2 class="text-2xl leading-tight font-bold text-white mb-5">
            Kualifikasi yang Dibutuhkan
        </h2>
        <div class="min-h-0 text-[1.45rem] text-slate-300 leading-snug break-words whitespace-pre-line">
            {{ $job->qualifications }}
        </div>
    </div>

    <!-- Footer Card -->
    <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl flex flex-col gap-5">
        @if($job->other_info)
        <div>
            <p class="text-xl font-bold text-emerald-400 mb-2">Informasi Tambahan:</p>
            <p class="text-[1.35rem] text-slate-200 leading-snug break-words whitespace-pre-line">{{ $job->other_info }}</p>
        </div>
        @endif
        @if ($job->application_method || $job->application_address || $job->application_deadline)
            <div>
                <p class="text-xl font-bold text-emerald-400 mb-2">Cara Melamar:</p>
                <p class="text-[1.25rem] text-slate-200 leading-snug break-words">{{ $job->application_method }}: {{ $job->application_address }}</p>
                @if ($job->application_deadline)
                    <p class="text-[1.1rem] text-slate-300 mt-1">Batas akhir: {{ $job->application_deadline->format('d M Y') }}</p>
                @endif
            </div>
        @endif

        <div class="border-t border-slate-800 pt-5 grid grid-cols-2 gap-8">
            <div class="min-w-0">
                <p class="text-lg font-semibold text-slate-400 mb-1">Lokasi:</p>
                <p class="text-lg text-slate-200 leading-snug break-words whitespace-pre-line">{{ $job->address }}</p>
                @if ($job->work_location)
                    <p class="text-base text-slate-300 mt-2 leading-snug break-words"><span class="font-semibold">Penempatan:</span> {{ $job->work_location }}</p>
                @endif
            </div>
            <div class="min-w-0 text-right">
                <p class="text-lg font-semibold text-slate-400 mb-1">Aplikasi Online:</p>
                <p class="text-lg font-bold text-emerald-400 break-words">ucc.ac.id/career</p>
            </div>
        </div>
    </div>
</div>
@endsection
