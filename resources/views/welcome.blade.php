<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UCC Job Portal & Automated Poster Generator</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body x-data="{ mobileOpen: false, activeStep: 1, openFaq: null }" class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col justify-between">
    <!-- Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-blue-700 text-white flex items-center justify-center font-black text-base shadow-xs">
                    UCC
                </span>
                <div>
                    <span class="font-bold text-slate-900 text-lg tracking-tight block leading-tight">Career Portal</span>
                    <span class="text-[11px] text-slate-400 font-medium block">Job Vacancy & Poster Generator</span>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-2" aria-label="Navigasi utama">
                <a href="#alur" class="px-3 py-2 rounded-lg text-slate-600 hover:text-slate-950 hover:bg-slate-100 font-medium text-sm transition min-h-[40px] flex items-center">Alur Pengajuan</a>
                <a href="#poster" class="px-3 py-2 rounded-lg text-slate-600 hover:text-slate-950 hover:bg-slate-100 font-medium text-sm transition min-h-[40px] flex items-center">Poster</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm transition min-h-[40px] flex items-center">
                        Buka Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-slate-700 hover:text-slate-900 font-medium text-sm hover:bg-slate-100 transition min-h-[40px] flex items-center">
                        Masuk (Login)
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm transition min-h-[40px] flex items-center shadow-xs">
                        Daftar Akun Perusahaan
                    </a>
                @endauth
            </nav>
            <button type="button"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                    aria-controls="mobile-navigation"
                    class="md:hidden inline-flex items-center justify-center w-11 h-11 rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    aria-label="Buka menu navigasi">
                <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="mobile-navigation" x-show="mobileOpen" x-cloak x-transition class="md:hidden border-t border-slate-200 bg-white px-4 py-4 space-y-2">
            <a @click="mobileOpen = false" href="#alur" class="flex items-center min-h-[44px] px-3 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">Alur Pengajuan</a>
            <a @click="mobileOpen = false" href="#poster" class="flex items-center min-h-[44px] px-3 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">Poster Instagram</a>
            @auth
                <a href="{{ route('dashboard') }}" class="flex items-center justify-center min-h-[44px] px-4 rounded-lg bg-blue-700 text-white font-semibold">Buka Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="flex items-center min-h-[44px] px-3 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">Masuk</a>
                <a href="{{ route('register') }}" class="flex items-center justify-center min-h-[44px] px-4 rounded-lg bg-blue-700 text-white font-semibold">Daftar Akun Perusahaan</a>
            @endauth
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-grow">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <div class="lg:col-span-7">
                    <p class="text-sm font-bold tracking-wide text-blue-800 mb-5">PORTAL RESMI KARIR UCC</p>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-950 tracking-tight leading-[1.1]">
                        Satu pengajuan untuk lowongan yang siap dipublikasikan.
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-slate-600 leading-relaxed max-w-2xl">
                        Perusahaan mitra mengirim detail lowongan melalui form terarah. Admin meninjau pengajuan, lalu poster Instagram berukuran 1080x1350 dapat dibuat dari data yang sudah disetujui.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-6 py-3.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-base transition text-center shadow-xs min-h-[48px] flex items-center justify-center">
                            Akses Dashboard Anda
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-base transition text-center shadow-xs min-h-[48px] flex items-center justify-center">
                            Daftarkan Perusahaan Anda
                        </a>
                        <a href="{{ route('login') }}" class="px-6 py-3.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-base transition text-center min-h-[48px] flex items-center justify-center">
                            Masuk ke Akun Mitra / Admin
                        </a>
                    @endauth
                    </div>
                    <div class="mt-7 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-500">
                        <a href="#alur" class="font-semibold text-blue-800 hover:text-blue-950 underline underline-offset-4">Lihat cara kerja</a>
                        <a href="#poster" class="font-semibold text-slate-700 hover:text-slate-950 underline underline-offset-4">Lihat format poster</a>
                    </div>
                </div>

                <div id="poster" class="lg:col-span-5" aria-label="Contoh struktur poster Instagram">
                    <div class="relative mx-auto max-w-[360px] aspect-[4/5] rounded-[2rem] bg-[#07195c] p-6 sm:p-8 text-white shadow-xl overflow-hidden">
                        <div class="absolute -top-12 -right-12 w-36 h-36 rounded-full border-[18px] border-[#ffb900] opacity-80"></div>
                        <div class="relative h-full flex flex-col">
                            <div class="flex items-center justify-between text-[10px] font-bold tracking-[0.18em] text-blue-100">
                                <span>UCC CAREER</span><span>1080 × 1350</span>
                            </div>
                            <div class="mt-auto">
                                <p class="text-sm font-semibold text-blue-100">LOWONGAN KERJA</p>
                                <h2 class="mt-2 text-3xl sm:text-4xl font-black leading-none">Data Analyst</h2>
                                <div class="mt-6 rounded-2xl bg-white p-4 text-slate-900">
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-blue-800">Informasi yang ditampilkan</p>
                                    <ul class="mt-3 space-y-2 text-xs leading-relaxed">
                                        <li class="flex gap-2"><span class="text-blue-700">01</span> Nama perusahaan</li>
                                        <li class="flex gap-2"><span class="text-blue-700">02</span> Kualifikasi posisi</li>
                                        <li class="flex gap-2"><span class="text-blue-700">03</span> Cara melamar</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section id="alur" class="mt-20 lg:mt-28 pt-12 border-t border-slate-200 scroll-mt-24">
                <div class="grid lg:grid-cols-12 gap-10">
                    <div class="lg:col-span-4">
                        <p class="text-sm font-bold text-blue-800">ALUR PENGAJUAN</p>
                        <h2 class="mt-3 text-3xl sm:text-4xl font-black tracking-tight text-slate-950">Jelas dari form sampai poster.</h2>
                        <p class="mt-4 text-slate-600 leading-relaxed">Setiap tahap memberi konteks yang berbeda untuk perusahaan dan Admin.</p>
                    </div>
                    <div class="lg:col-span-8">
                        <div class="grid sm:grid-cols-3 gap-3" role="tablist" aria-label="Tahap pengajuan">
                            @foreach ([
                                1 => ['title' => 'Isi form', 'text' => 'Lengkapi profil perusahaan, posisi, kualifikasi, dan cara melamar.'],
                                2 => ['title' => 'Admin meninjau', 'text' => 'Pengajuan diperiksa. Jika perlu perbaikan, catatan revisi tampil di akun perusahaan.'],
                                3 => ['title' => 'Poster dibuat', 'text' => 'Setelah disetujui, Admin memilih satu dari dua template dan mengunduh JPG.'],
                            ] as $step => $content)
                                <button type="button" role="tab" :aria-selected="(activeStep === {{ $step }}).toString()" @click="activeStep = {{ $step }}" :class="activeStep === {{ $step }} ? 'bg-blue-700 text-white border-blue-700' : 'bg-white text-slate-700 border-slate-200 hover:border-blue-300'" class="text-left rounded-xl border p-4 min-h-[112px] transition focus:outline-none focus:ring-2 focus:ring-blue-600">
                                    <span class="text-xs font-bold opacity-75">TAHAP 0{{ $step }}</span>
                                    <span class="block mt-2 font-bold">{{ $content['title'] }}</span>
                                </button>
                            @endforeach
                        </div>
                        <div class="mt-4 bg-white border border-slate-200 rounded-2xl p-6 min-h-[120px]" role="tabpanel">
                            @foreach ([
                                1 => ['title' => 'Pengajuan terstruktur', 'text' => 'Form memandu data yang dibutuhkan agar Admin dapat memeriksa pengajuan dengan informasi yang lengkap.'],
                                2 => ['title' => 'Review dengan catatan', 'text' => 'Status dan alasan penolakan dapat dilihat perusahaan. Revisi dikirim bersama alasan perubahan untuk ditinjau kembali.'],
                                3 => ['title' => 'Output siap publikasi', 'text' => 'Lowongan yang disetujui dapat dirender menjadi poster JPG berukuran 1080x1350 dengan pilihan template 1 atau 2.'],
                            ] as $step => $content)
                                <div x-show="activeStep === {{ $step }}" x-cloak>
                                    <h3 class="font-bold text-lg text-slate-900">{{ $content['title'] }}</h3>
                                    <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $content['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-20 lg:mt-24 grid lg:grid-cols-2 gap-8 items-start">
                <div class="bg-blue-950 text-white rounded-2xl p-8 sm:p-10">
                    <p class="text-sm font-bold text-blue-200">UNTUK PERUSAHAAN MITRA</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight">Mulai dari data yang benar.</h2>
                    <p class="mt-4 text-blue-100 leading-relaxed">Siapkan logo JPG atau PNG, detail posisi, kualifikasi, dan tujuan pengiriman lamaran sebelum mengisi pengajuan.</p>
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="inline-flex mt-7 items-center justify-center min-h-[48px] px-5 rounded-lg bg-white text-blue-950 font-bold hover:bg-blue-50 transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-950">
                        {{ auth()->check() ? 'Buka Dashboard' : 'Daftar Akun Perusahaan' }}
                    </a>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-slate-900">Pertanyaan yang sering muncul</h2>
                    <div class="mt-4 divide-y divide-slate-200">
                        @foreach ([
                            1 => ['q' => 'Kapan poster dapat dibuat?', 'a' => 'Poster dapat dibuat oleh Admin setelah pengajuan berstatus Approved.'],
                            2 => ['q' => 'Apa yang terjadi jika pengajuan ditolak?', 'a' => 'Perusahaan dapat melihat alasan penolakan, memperbaiki data, lalu mengirim alasan revisi bersama perubahan tersebut.'],
                            3 => ['q' => 'Berapa ukuran poster yang dihasilkan?', 'a' => 'Output poster berformat JPG dengan ukuran 1080x1350 pixel, sesuai rasio portrait Instagram.'],
                        ] as $id => $faq)
                            <div>
                                <button type="button" @click="openFaq = openFaq === {{ $id }} ? null : {{ $id }}" :aria-expanded="(openFaq === {{ $id }}).toString()" class="w-full flex items-center justify-between gap-4 py-4 text-left font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded">
                                    <span>{{ $faq['q'] }}</span>
                                    <span class="text-xl text-blue-700" aria-hidden="true" x-text="openFaq === {{ $id }} ? '−' : '+'"></span>
                                </button>
                                <p x-show="openFaq === {{ $id }}" x-cloak x-transition class="pb-4 pr-8 text-sm text-slate-600 leading-relaxed">{{ $faq['a'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-8 text-center text-sm text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <span class="font-bold text-slate-800">UCC Job Portal & Automated Poster Generator</span>
                <p class="text-xs text-slate-400 mt-0.5">Sistem Portal Lowongan Kerja Universitas</p>
            </div>
            <p class="text-xs text-slate-400">&copy; {{ date('Y') }} Career Center. Seluruh hak cipta dilindungi.</p>
        </div>
    </footer>
</body>
</html>
