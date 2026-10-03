<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('company.vacancies.index') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 mb-1">
                    &larr; Kembali ke Daftar Lowongan
                </a>
                <h1 class="text-2xl font-bold text-slate-900">Form Pengajuan Lowongan Kerja</h1>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-10 shadow-xs">
            <div class="border-b border-slate-100 pb-6 mb-6">
                <h2 class="text-lg font-bold text-slate-900">Informasi Kebutuhan Posisi</h2>
                <p class="text-sm text-slate-500 mt-1">
                    Isi detail lowongan kerja dengan akurat. Informasi ini akan ditinjau oleh Admin Career Center dan digunakan untuk pembuatan poster resmi.
                </p>
            </div>

            <form method="POST" action="{{ route('company.vacancies.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="business_area" class="block text-sm font-semibold text-slate-800 mb-1.5">Bidang Perusahaan *</label>
                        <select id="business_area" name="business_area" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                            <option value="">Pilih bidang perusahaan</option>
                            @foreach (['Teknologi Informasi', 'Manufaktur', 'Keuangan', 'Pendidikan', 'Kesehatan', 'Perdagangan', 'Jasa', 'Lainnya'] as $area)
                                <option value="{{ $area }}" @selected(old('business_area') === $area)>{{ $area }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="province" class="block text-sm font-semibold text-slate-800 mb-1.5">Provinsi *</label>
                        <select id="province" name="province" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                            <option value="">Pilih provinsi</option>
                            @foreach (['Kepulauan Riau', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Bali', 'Sumatera Utara', 'Lainnya'] as $province)
                                <option value="{{ $province }}" @selected(old('province') === $province)>{{ $province }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="company_website" class="block text-sm font-semibold text-slate-800 mb-1.5">Website / Media Sosial *</label>
                        <input id="company_website" name="company_website" value="{{ old('company_website') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" placeholder="https://contoh.co.id">
                    </div>
                    <div>
                        <label for="company_email" class="block text-sm font-semibold text-slate-800 mb-1.5">Email Perusahaan *</label>
                        <input id="company_email" name="company_email" type="email" value="{{ old('company_email') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label for="contact_name" class="block text-sm font-semibold text-slate-800 mb-1.5">Nama Narahubung *</label>
                        <input id="contact_name" name="contact_name" value="{{ old('contact_name') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label for="contact_position" class="block text-sm font-semibold text-slate-800 mb-1.5">Jabatan Narahubung *</label>
                        <input id="contact_position" name="contact_position" value="{{ old('contact_position') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label for="contact_phone" class="block text-sm font-semibold text-slate-800 mb-1.5">Kontak / WhatsApp *</label>
                        <input id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    </div>
                </div>

                <!-- Nama Perusahaan -->
                <div x-data="{ count: '{{ old('company_name', $defaultCompanyName) }}'.length, max: 50 }">
                    <label for="company_name" class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Nama Perusahaan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="company_name"
                           name="company_name"
                           value="{{ old('company_name', $defaultCompanyName) }}"
                           required
                           maxlength="50"
                           x-on:input="count = $el.value.length"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 outline-none transition @error('company_name') border-rose-500 @enderror"
                           placeholder="Contoh: PT Sumber Makmur Sentosa">
                    <div class="flex items-center justify-between mt-1.5">
                        @error('company_name')
                            <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @else
                            <span></span>
                        @enderror
                        <p class="text-xs ml-auto" :class="count > max - 10 ? 'text-rose-500 font-semibold' : 'text-slate-400'" x-text="count + '/' + max"></p>
                    </div>
                </div>

                <!-- Posisi Pekerjaan -->
                <div x-data="{ count: '{{ old('position') }}'.length, max: 50 }">
                    <label for="position" class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Posisi yang Dibutuhkan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="position"
                           name="position"
                           value="{{ old('position') }}"
                           required
                           maxlength="50"
                           x-on:input="count = $el.value.length"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 outline-none transition @error('position') border-rose-500 @enderror"
                           placeholder="Contoh: Senior Frontend Developer">
                    <div class="flex items-center justify-between mt-1.5">
                        @error('position')
                            <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @else
                            <span></span>
                        @enderror
                        <p class="text-xs ml-auto" :class="count > max - 10 ? 'text-rose-500 font-semibold' : 'text-slate-400'" x-text="count + '/' + max"></p>
                    </div>
                </div>

                <div>
                    <label for="required_count" class="block text-sm font-semibold text-slate-800 mb-1.5">Jumlah Kebutuhan *</label>
                    <input id="required_count" name="required_count" type="number" min="1" value="{{ old('required_count', 1) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                </div>

                <!-- Kualifikasi & Persyaratan -->
                <div x-data="{ count: `{{ old('qualifications') }}`.length, max: 450 }">
                    <label for="qualifications" class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Kualifikasi & Persyaratan Kerja <span class="text-rose-500">*</span>
                    </label>
                    <p class="text-xs text-slate-500 mb-2">Tuliskan dalam format poin-poin agar mudah dibaca di poster. Maks. 450 karakter (sekitar 8-10 poin singkat).</p>
                    <textarea id="qualifications"
                              name="qualifications"
                              rows="6"
                              required
                              maxlength="450"
                              x-on:input="count = $el.value.length"
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 outline-none transition @error('qualifications') border-rose-500 @enderror"
                              placeholder="• Pendidikan minimal S1 Teknik Informatika&#10;• Pengalaman kerja minimal 2 tahun&#10;• Menguasai Laravel dan Vue.js&#10;• Mampu bekerja sama dalam tim">{{ old('qualifications') }}</textarea>
                    <div class="flex items-center justify-between mt-1.5">
                        @error('qualifications')
                            <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @else
                            <span></span>
                        @enderror
                        <p class="text-xs ml-auto" :class="count > max - 50 ? 'text-rose-500 font-semibold' : 'text-slate-400'" x-text="count + '/' + max"></p>
                    </div>
                </div>

                <div>
                    <label for="job_description" class="block text-sm font-semibold text-slate-800 mb-1.5">Deskripsi Pekerjaan *</label>
                    <textarea id="job_description" name="job_description" rows="4" maxlength="1000" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('job_description') }}</textarea>
                </div>

                <!-- Informasi Tambahan / Benefit -->
                <div x-data="{ count: `{{ old('other_info') }}`.length, max: 150 }">
                    <label for="other_info" class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Informasi Tambahan & Benefit <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea id="other_info"
                              name="other_info"
                              rows="3"
                              maxlength="150"
                              x-on:input="count = $el.value.length"
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 outline-none transition @error('other_info') border-rose-500 @enderror"
                              placeholder="Contoh: Skema kerja Hybrid (WFH & WFO). Gaji kompetitif + asuransi kesehatan swasta. Batas lamaran: 30 Oktober 2026.">{{ old('other_info') }}</textarea>
                    <div class="flex items-center justify-between mt-1.5">
                        @error('other_info')
                            <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @else
                            <span></span>
                        @enderror
                        <p class="text-xs ml-auto" :class="count > max - 20 ? 'text-rose-500 font-semibold' : 'text-slate-400'" x-text="count + '/' + max"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="application_deadline" class="block text-sm font-semibold text-slate-800 mb-1.5">Batas Akhir Lamaran *</label>
                        <input id="application_deadline" name="application_deadline" type="date" value="{{ old('application_deadline') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <p class="block text-sm font-semibold text-slate-800 mb-2">Cara Melamar *</p>
                        <div class="space-y-2">
                            @foreach (['Email', 'Website', 'Datang Langsung', 'Link Khusus', 'Lainnya'] as $method)
                                <label class="flex items-center gap-2 text-sm text-slate-700">
                                    <input type="radio" name="application_method" value="{{ $method }}" required @checked(old('application_method') === $method) class="border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span>{{ $method }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label for="application_address" class="block text-sm font-semibold text-slate-800 mb-1.5">Alamat / Tujuan Lamaran *</label>
                        <input id="application_address" name="application_address" value="{{ old('application_address') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label for="offered_salary" class="block text-sm font-semibold text-slate-800 mb-1.5">Gaji Ditawarkan</label>
                        <input id="offered_salary" name="offered_salary" value="{{ old('offered_salary') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    </div>
                </div>

                <div class="space-y-4 border-t border-slate-100 pt-6">
                    <div>
                        <label for="promotional_caption" class="block text-sm font-semibold text-slate-800 mb-1.5">Pesan Promosi / Caption</label>
                        <textarea id="promotional_caption" name="promotional_caption" rows="3" maxlength="500" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('promotional_caption') }}</textarea>
                    </div>
                </div>

                <!-- Alamat Perusahaan -->
                <div x-data="{ count: `{{ old('address') }}`.length, max: 100 }">
                    <label for="address" class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Alamat Lengkap Perusahaan <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="address"
                              name="address"
                              rows="2"
                              required
                              maxlength="100"
                              x-on:input="count = $el.value.length"
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 outline-none transition @error('address') border-rose-500 @enderror"
                              placeholder="Contoh: Gedung Menara Mandiri Lt. 12, Jl. Jend. Sudirman Kav. 54-55, Jakarta Selatan">{{ old('address') }}</textarea>
                    <div class="flex items-center justify-between mt-1.5">
                        @error('address')
                            <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @else
                            <span></span>
                        @enderror
                        <p class="text-xs ml-auto" :class="count > max - 15 ? 'text-rose-500 font-semibold' : 'text-slate-400'" x-text="count + '/' + max"></p>
                    </div>
                </div>

                <div>
                    <label for="work_location" class="block text-sm font-semibold text-slate-800 mb-1.5">Lokasi Penempatan *</label>
                    <input id="work_location" name="work_location" value="{{ old('work_location') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" placeholder="Contoh: Refinery Unit IV Cilacap">
                </div>

                <div class="space-y-3 border-t border-slate-100 pt-6">
                    <label class="flex items-start gap-3 text-sm text-slate-700">
                        <input type="checkbox" name="information_consent" value="1" required class="mt-1 rounded border-slate-300">
                        <span>Informasi yang diberikan benar dan dapat digunakan Uvers Career Center.</span>
                    </label>
                    <label class="flex items-start gap-3 text-sm text-slate-700">
                        <input type="checkbox" name="publication_consent" value="1" required class="mt-1 rounded border-slate-300">
                        <span>Saya menyetujui publikasi maksimal 3 hari kerja.</span>
                    </label>
                </div>

                <!-- Form Action Buttons -->
                <div class="border-t border-slate-100 pt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('company.vacancies.index') }}"
                       class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-medium text-sm hover:bg-slate-50 transition min-h-[44px] flex items-center">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-lg bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm transition focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 min-h-[44px]">
                        Kirim Pengajuan Lowongan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
