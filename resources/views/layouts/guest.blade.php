<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UCC Career Portal') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50">
        <div class="min-h-screen grid lg:grid-cols-[0.9fr_1.1fr]">
            <aside class="hidden lg:flex relative overflow-hidden bg-[#07195c] p-12 text-white">
                <div class="absolute -top-20 -right-20 h-72 w-72 rounded-full border-[28px] border-[#ffb900]/70"></div>
                <div class="absolute bottom-10 -left-24 h-56 w-56 rounded-full border-[20px] border-blue-300/20"></div>
                <div class="relative z-10 flex w-full flex-col justify-between">
                    <a href="/" class="inline-flex items-center gap-3 w-fit focus:outline-none focus:ring-2 focus:ring-white rounded-lg">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-sm font-black text-blue-950">UCC</span>
                        <span>
                            <span class="block text-lg font-bold leading-tight">Career Portal</span>
                            <span class="block text-xs text-blue-100">Uvers Career Center</span>
                        </span>
                    </a>
                    <div class="max-w-md">
                        <p class="text-sm font-bold tracking-wide text-blue-200">PORTAL RESMI KARIR UCC</p>
                        <h1 class="mt-4 text-4xl font-black leading-tight">Kelola pengajuan lowongan dengan alur yang jelas.</h1>
                        <p class="mt-5 text-blue-100 leading-relaxed">Masuk untuk melanjutkan pengajuan perusahaan atau meninjau lowongan yang membutuhkan tindakan Admin.</p>
                    </div>
                    <p class="text-xs text-blue-200">Poster Instagram dibuat dalam format JPG 1080x1350 setelah lowongan disetujui.</p>
                </div>
            </aside>
            <main class="flex min-h-screen flex-col justify-center px-5 py-10 sm:px-8 lg:px-16">
                <div class="mx-auto w-full max-w-md">
                    <a href="/" class="inline-flex items-center gap-2 text-sm font-bold text-blue-800 lg:hidden focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-lg">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-700 text-xs font-black text-white">UCC</span>
                        Career Portal
                    </a>
                    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        {{ $slot }}
                    </div>
                    <p class="mt-6 text-center text-xs text-slate-500">Uvers Career Center, portal pengajuan lowongan kerja.</p>
                </div>
            </main>
        </div>
    </body>
</html>
