@extends('posters.layout')

@section('content')
<div class="w-[1080px] h-[1350px] bg-slate-100 text-slate-900 flex flex-col justify-between box-border">
    <!-- Top Warm Hero Section -->
    <div class="bg-amber-400 p-20 flex flex-col justify-between border-b-4 border-amber-500">
        <div class="flex items-center justify-between mb-8">
            <span class="text-xl font-black text-amber-950 tracking-wider">CAREER OPPORTUNITY</span>
            <div class="flex items-center gap-4">
                @if ($logoDataUri)
                    <img src="{{ $logoDataUri }}" alt="Logo {{ $job->company_name }}" class="h-20 w-20 object-contain">
                @endif
                <span class="text-lg font-bold text-amber-900">UCC JOB PORTAL</span>
            </div>
        </div>

        <div>
            <h1 class="text-6xl font-black text-slate-950 leading-tight">
                {{ $job->position }}
            </h1>
            <p class="text-3xl font-bold text-amber-950 mt-4">
                {{ $job->company_name }}
            </p>
        </div>
    </div>

    <!-- Main Content Body -->
    <div class="p-20 flex-grow flex flex-col justify-between">
        <!-- Qualifications Box -->
        <div class="bg-white rounded-2xl p-10 border border-slate-200 shadow-sm">
            <h2 class="text-3xl font-bold text-slate-900 mb-6">
                Kualifikasi & Persyaratan
            </h2>
            <div class="text-2xl text-slate-700 leading-relaxed space-y-4 whitespace-pre-line">
                {{ $job->qualifications }}
            </div>
        </div>

        <!-- 2-Column Info & Location Section -->
        <div class="grid grid-cols-2 gap-8 mt-8 pt-8 border-t-2 border-slate-200">
            <div class="bg-white rounded-xl p-8 border border-slate-200">
                <h3 class="text-lg font-bold text-slate-500 mb-2">Informasi Tambahan</h3>
                <p class="text-2xl text-slate-800 font-medium leading-normal">
                    {{ $job->other_info ?? 'Sesuai dengan ketentuan standar perusahaan.' }}
                </p>
            </div>
            <div class="bg-white rounded-xl p-8 border border-slate-200">
                <h3 class="text-lg font-bold text-slate-500 mb-2">Lokasi Penempatan</h3>
                <p class="text-2xl text-slate-800 font-medium leading-normal">
                    {{ $job->address }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
