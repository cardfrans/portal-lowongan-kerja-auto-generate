<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-7">
        <p class="text-sm font-bold text-blue-800">SELAMAT DATANG KEMBALI</p>
        <h1 class="mt-2 text-2xl font-black tracking-tight text-slate-950">Masuk ke Career Portal</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">Lanjutkan pengajuan lowongan atau buka dashboard Admin Anda.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Email" class="text-slate-700 font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Password" class="text-slate-700 font-semibold" />

            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-blue-800 hover:text-blue-950 underline underline-offset-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif

            <x-primary-button class="justify-center rounded-lg bg-blue-700 px-5 py-3 hover:bg-blue-800 focus:ring-blue-500">
                Masuk
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 border-t border-slate-200 pt-5 text-center">
        <p class="text-sm text-slate-600">Belum memiliki akun perusahaan?</p>
        <a href="{{ route('register') }}" class="mt-3 inline-flex min-h-[44px] w-full items-center justify-center rounded-lg border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-bold text-blue-800 transition hover:border-blue-300 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
            Daftar Akun Perusahaan
        </a>
    </div>
</x-guest-layout>
