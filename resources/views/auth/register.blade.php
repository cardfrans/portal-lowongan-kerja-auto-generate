<x-guest-layout>
    <div class="mb-7">
        <p class="text-sm font-bold text-blue-800">AKUN PERUSAHAAN</p>
        <h1 class="mt-2 text-2xl font-black tracking-tight text-slate-950">Buat akun mitra</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">Daftarkan perusahaan untuk mengirim pengajuan lowongan ke UCC.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama Perusahaan" class="text-slate-700 font-semibold" />
            <x-text-input id="name" class="block mt-1 w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="organization" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="logo" value="Logo Perusahaan (Opsional)" class="text-slate-700 font-semibold" />
            <input id="logo" name="logo" type="file" accept="image/jpeg,image/png,image/jpg" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 file:mr-3 file:rounded-md file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:font-semibold file:text-blue-800" />
            <p class="mt-1 text-xs text-slate-500">Boleh dikosongkan. JPG atau PNG, maksimal 10 MB.</p>
            <x-input-error :messages="$errors->get('logo')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Email Perusahaan" class="text-slate-700 font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <x-input-label for="business_area" value="Bidang Perusahaan" class="text-slate-700 font-semibold" />
                <select id="business_area" name="business_area" required class="mt-1 block w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-500">
                    <option value="">Pilih bidang perusahaan</option>
                    @foreach (['Teknologi Informasi', 'Manufaktur', 'Keuangan', 'Pendidikan', 'Kesehatan', 'Perdagangan', 'Jasa', 'Lainnya'] as $area)
                        <option value="{{ $area }}" @selected(old('business_area') === $area)>{{ $area }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('business_area')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="province" value="Provinsi" class="text-slate-700 font-semibold" />
                <select id="province" name="province" required class="mt-1 block w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-500">
                    <option value="">Pilih provinsi</option>
                    @foreach (['Kepulauan Riau', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Bali', 'Sumatera Utara', 'Lainnya'] as $province)
                        <option value="{{ $province }}" @selected(old('province') === $province)>{{ $province }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('province')" class="mt-2" />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            @foreach ([
                'company_website' => ['Website / Media Sosial', 'url'],
                'company_email' => ['Email Perusahaan', 'email'],
                'contact_name' => ['Nama Narahubung', 'text'],
                'contact_position' => ['Jabatan Narahubung', 'text'],
                'contact_phone' => ['Kontak / WhatsApp', 'text'],
            ] as $field => [$label, $type])
                <div class="{{ $field === 'contact_phone' ? 'sm:col-span-2' : '' }}">
                    <x-input-label for="{{ $field }}" :value="$label" class="text-slate-700 font-semibold" />
                    <x-text-input id="{{ $field }}" class="block mt-1 w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-500" type="{{ $type }}" name="{{ $field }}" :value="old($field)" required />
                    <x-input-error :messages="$errors->get($field)" class="mt-2" />
                </div>
            @endforeach
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
            <x-primary-button class="justify-center rounded-lg bg-blue-700 px-5 py-3 hover:bg-blue-800 focus:ring-blue-500">
                Buat Akun
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 border-t border-slate-200 pt-5 text-center">
        <p class="text-sm text-slate-600">Sudah memiliki akun?</p>
        <a href="{{ route('login') }}" class="mt-3 inline-flex min-h-[44px] w-full items-center justify-center rounded-lg border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-bold text-blue-800 transition hover:border-blue-300 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
            Masuk ke Akun
        </a>
    </div>
</x-guest-layout>
