@extends('posters.layout')

@section('content')
<div class="w-[1080px] h-[1350px] bg-[#07195c] relative overflow-hidden font-sans">
    
    <!-- Background Decor (Circles & Dots) -->
    <div class="absolute -top-16 -left-16 w-64 h-64 border-[24px] border-white rounded-full opacity-90"></div>
    <div class="absolute top-4 left-4 w-64 h-64 border-[24px] border-[#ffb900] rounded-full opacity-90"></div>
    <div class="absolute bottom-[-100px] right-[-100px] w-96 h-96 border-[40px] border-white/10 rounded-full"></div>
    <div class="absolute bottom-[-50px] right-[-50px] w-96 h-96 border-[40px] border-[#ffb900]/20 rounded-full"></div>
    
    <!-- Dotted Pattern Top Right -->
    <div class="absolute top-12 right-12 grid grid-cols-4 gap-3 opacity-60">
        <div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div>
        <div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div>
        <div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div>
        <div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div><div class="w-3 h-3 bg-white rounded-full"></div>
    </div>

    <!-- Header Section -->
    <div class="relative w-full h-[260px] flex justify-center items-center pt-8">
        <!-- Typography LOWONGAN KERJA -->
        <div class="text-center flex flex-col items-center transform -rotate-3 z-20">
            <h2 class="text-4xl font-black text-white italic tracking-widest drop-shadow-md">LOWONGAN</h2>
            <h1 class="text-[6rem] font-black text-[#ffb900] uppercase tracking-tighter leading-[0.8] drop-shadow-[0_10px_10px_rgba(0,0,0,0.4)] stroke-white stroke-2">KERJA</h1>
        </div>

        @if ($logoDataUri)
            <img src="{{ $logoDataUri }}" alt="Logo {{ $job->company_name }}" class="absolute right-16 top-20 h-24 w-24 object-contain bg-white rounded-2xl p-3 z-20">
        @endif
    </div>

    <!-- Main White Board -->
    <div class="mx-10 bg-white rounded-[40px] px-10 pt-10 pb-10 shadow-2xl relative z-10 flex flex-col gap-6 min-h-[1000px]">
        
        <!-- Row 1: Nama Perusahaan -->
        <div class="border-[3px] border-[#a1b4df] rounded-[24px] px-8 pt-8 pb-4 relative mt-2">
            <!-- Badge -->
            <div class="absolute -top-7 left-6 bg-[#0c31a6] text-white flex items-center gap-3 px-6 py-2 rounded-full shadow-lg border-[5px] border-white">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M19 2H5C3.89 2 3 2.9 3 4V22H21V4C21 2.9 20.11 2 19 2ZM11 12H9V10H11V12ZM11 8H9V6H11V8ZM15 12H13V10H15V12ZM15 8H13V6H15V8ZM11 16H9V14H11V16ZM15 16H13V14H15V16Z"/></svg>
                <span class="font-bold text-xl tracking-wider">NAMA PERUSAHAAN</span>
            </div>
            <p class="text-[1.7rem] leading-tight font-black text-slate-900 tracking-wide uppercase break-words">{{ $job->company_name }}</p>
        </div>

        <!-- Row 2: Posisi -->
        <div class="border-[3px] border-[#a1b4df] rounded-[24px] px-8 pt-8 pb-4 relative">
            <!-- Badge -->
            <div class="absolute -top-7 left-6 bg-[#0c31a6] text-white flex items-center gap-3 px-6 py-2 rounded-full shadow-lg border-[5px] border-white">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z"/></svg>
                <span class="font-bold text-xl tracking-wider">POSISI</span>
            </div>
            <p class="text-[1.7rem] leading-tight font-black text-slate-900 tracking-wide break-words">{{ $job->position }}</p>
        </div>

        <!-- Row 3: Split Layout -->
        <div class="flex gap-8 flex-grow min-h-0">
            <!-- Kiri: Kualifikasi -->
            <div class="w-7/12 border-[3px] border-[#a1b4df] rounded-[24px] px-8 pt-10 pb-6 relative flex flex-col">
                <div class="absolute -top-7 left-6 bg-[#0c31a6] text-white flex items-center gap-3 px-6 py-2 rounded-full shadow-lg border-[5px] border-white z-20">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 3L1 9L12 15L21 10.09V17H23V9L12 3ZM5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z"/></svg>
                    <span class="font-bold text-xl tracking-wider">KUALIFIKASI</span>
                </div>
                <!-- PERBAIKAN 1: Tambahkan 'whitespace-pre-line' agar baris baru dari karakter bullet (•) tidak menyamping -->
                <div class="min-h-0 text-[1.15rem] leading-snug text-slate-900 space-y-1 break-words whitespace-pre-line">
                    {{ $job->qualifications }}
                </div>
            </div>

            <!-- Kanan: Info & Alamat -->
            <div class="w-5/12 flex flex-col gap-6 h-full min-h-0">
                <!-- Info Lainnya -->
                <!-- PERBAIKAN 2: Ubah pt-10 menjadi pt-6 agar teks lebih naik ke atas -->
                <div class="border-[3px] border-[#a1b4df] rounded-[24px] px-8 pt-8 pb-6 relative min-h-[190px]">
                    <div class="absolute -top-7 left-6 bg-[#0c31a6] text-white flex items-center gap-3 px-6 py-2 rounded-full shadow-lg border-[5px] border-white">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M11 7H13V9H11V7ZM11 11H13V17H11V11ZM12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z"/></svg>
                        <span class="font-bold text-xl tracking-wider">INFORMASI LAINNYA</span>
                    </div>
                    <div class="text-[1.1rem] leading-snug text-slate-900 font-medium whitespace-pre-line break-words mt-2">
                        {{ $job->other_info ?: 'Tidak ada informasi tambahan.' }}
                    </div>
                    @if ($job->application_method || $job->application_address || $job->application_deadline)
                        <div class="mt-3 border-t border-[#a1b4df] pt-2 text-[1rem] leading-snug text-slate-900 break-words">
                            <p class="font-bold">Cara Melamar:</p>
                            <p>{{ $job->application_method }}: {{ $job->application_address }}</p>
                            @if ($job->application_deadline)
                                <p>Batas akhir: {{ $job->application_deadline->format('d M Y') }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Alamat Instansi -->
                <div class="border-[3px] border-[#a1b4df] rounded-[24px] px-8 pt-6 pb-4 relative flex-grow">
                    <div class="absolute -top-7 left-6 bg-[#0c31a6] text-white flex items-center gap-3 px-6 py-2 rounded-full shadow-lg border-[5px] border-white">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9C9.5 7.62 10.62 6.5 12 6.5C13.38 6.5 14.5 7.62 14.5 9C14.5 10.38 13.38 11.5 12 11.5Z"/></svg>
                        <span class="font-bold text-xl tracking-wider">ALAMAT INSTANSI</span>
                    </div>
                    <p class="text-[1.2rem] leading-snug text-slate-900 font-bold mt-2">Alamat Instansi:</p>
                    <p class="text-[1.15rem] leading-snug text-slate-900 mt-1 break-words">{{ $job->address }}</p>
                    @if ($job->work_location)
                        <p class="text-[1.05rem] leading-snug text-slate-900 mt-2 break-words"><span class="font-bold">Penempatan:</span> {{ $job->work_location }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Kontak UCC -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 w-[75%] bg-[#082a97] rounded-full flex items-center p-3 shadow-[0_10px_20px_rgba(0,0,0,0.5)] border-[5px] border-[#1d43c2] z-20">
        <!-- Lingkaran Telepon Kuning -->
        <div class="bg-[#ffb900] w-[85px] h-[85px] rounded-full flex items-center justify-center border-[6px] border-[#082a97] shadow-inner ml-2 z-30">
            <svg class="w-10 h-10 fill-[#082a97]" viewBox="0 0 24 24"><path d="M6.62 10.79C8.06 13.62 10.38 15.94 13.21 17.38L15.41 15.18C15.69 14.9 16.08 14.82 16.43 14.93C17.55 15.3 18.75 15.5 20 15.5C20.55 15.5 21 15.95 21 16.5V20C21 20.55 20.55 21 20 21C10.61 21 3 13.39 3 4C3 3.45 3.45 3 4 3H7.5C8.05 3 8.5 3.45 8.5 4C8.5 5.25 8.7 6.45 9.07 7.57C9.18 7.92 9.1 8.31 8.82 8.59L6.62 10.79Z"/></svg>
        </div>
        
        <!-- Teks Kontak -->
        <div class="flex flex-col text-white ml-8 py-2">
            <p class="text-2xl font-bold tracking-wide mb-1">KONTAK UVERS CAREER CENTRE (UCC)</p>
            <div class="flex items-center gap-3">
                <!-- Icon WA -->
                <svg class="w-8 h-8 fill-[#ffb900]" viewBox="0 0 24 24"><path d="M17.472 14.382C17.182 14.237 15.762 13.542 15.498 13.444C15.234 13.348 15.045 13.302 14.856 13.59C14.665 13.882 14.135 14.512 13.985 14.704C13.834 14.897 13.684 14.922 13.392 14.776C13.104 14.632 12.164 14.324 11.05 13.332C10.184 12.558 9.598 11.602 9.426 11.31C9.256 11.018 9.408 10.86 9.554 10.716C9.684 10.588 9.845 10.378 9.99 10.204C10.137 10.03 10.185 9.904 10.282 9.712C10.378 9.518 10.33 9.35 10.258 9.204C10.185 9.06 9.598 7.606 9.356 7.024C9.122 6.456 8.882 6.532 8.705 6.52C8.544 6.51 8.355 6.51 8.163 6.51C7.971 6.51 7.66 6.582 7.398 6.874C7.135 7.164 6.398 7.864 6.398 9.294C6.398 10.72 7.425 12.098 7.57 12.292C7.717 12.486 9.605 15.548 12.632 16.742C13.35 17.026 13.911 17.196 14.35 17.32C15.074 17.55 15.733 17.518 16.248 17.438C16.824 17.348 18.006 16.726 18.252 16.046C18.497 15.366 18.497 14.786 18.423 14.664C18.35 14.544 18.158 14.478 17.867 14.334M12.003 21.926C10.334 21.926 8.761 21.478 7.411 20.71L7.14 20.55L3.896 21.402L4.767 18.224L4.59 17.944C3.722 16.516 3.262 14.82 3.262 13.024C3.262 8.144 7.23 4.176 12.11 4.176C14.478 4.176 16.666 5.098 18.337 6.772C20.01 8.444 20.932 10.634 20.932 13.002C20.93 17.88 16.962 21.848 12.083 21.848H12.003ZM12.003 2.19C6.035 2.19 1.182 7.042 1.182 13.01C1.182 14.918 1.678 16.758 2.585 18.37L1 24L6.75 22.492C8.307 23.324 10.12 23.766 11.996 23.766H12.003C17.971 23.766 22.825 18.914 22.825 12.946C22.825 10.05 21.698 7.338 19.648 5.286C17.599 3.236 14.887 2.11 11.99 2.11L12.003 2.19Z"/></svg>
                <p class="text-[1.7rem] font-bold tracking-wide">0899-4819-885</p>
            </div>
        </div>
    </div>
</div>
@endsection
