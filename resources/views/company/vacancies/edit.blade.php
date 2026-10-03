<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('company.vacancies.show', $jobVacancy) }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 mb-1">
                    &larr; Kembali ke Detail Lowongan
                </a>
                <h1 class="text-2xl font-bold text-slate-900">Perbarui Data Lowongan Kerja</h1>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Notice Box for Edit Re-trigger -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-amber-900 flex items-start gap-4">
            <svg class="w-6 h-6 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="text-sm">
                <p class="font-bold text-amber-950">Perhatian Pengajuan Perubahan:</p>
                <p class="mt-1 leading-relaxed text-amber-800">
                    Menyimpan perubahan akan mengirimkan lowongan kembali untuk ditinjau Admin. Anda wajib mengisi alasan perubahan di bagian bawah.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-10 shadow-xs">
            <form method="POST" action="{{ route('company.vacancies.update', $jobVacancy) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach ([
                        'business_area' => 'Bidang Perusahaan',
                        'province' => 'Provinsi',
                        'company_website' => 'Website / Media Sosial',
                        'company_email' => 'Email Perusahaan',
                        'contact_name' => 'Nama Narahubung',
                        'contact_position' => 'Jabatan Narahubung',
                        'contact_phone' => 'Kontak / WhatsApp',
                    ] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block text-sm font-semibold text-slate-800 mb-1.5">{{ $label }} *</label>
                            @if (in_array($field, ['business_area', 'province'], true))
                                @php
                                    $options = $field === 'business_area'
                                        ? ['Teknologi Informasi', 'Manufaktur', 'Keuangan', 'Pendidikan', 'Kesehatan', 'Perdagangan', 'Jasa', 'Lainnya']
                                        : ['Kepulauan Riau', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Bali', 'Sumatera Utara', 'Lainnya'];
                                @endphp
                                <select id="{{ $field }}" name="{{ $field }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                                    <option value="">Pilih {{ strtolower($label) }}</option>
                                    @foreach ($options as $option)
                                        <option value="{{ $option }}" @selected(old($field, $jobVacancy->{$field}) === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $jobVacancy->{$field}) }}" {{ $field === 'company_email' ? 'type=email' : '' }} required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Nama Perusahaan -->
                <div x-data="{ count: 0, max: 50 }" x-init="count = $refs.companyName.value.length">
                    <label for="company_name" class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Nama Perusahaan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="company_name"
                           name="company_name"
                           x-ref="companyName"
                           value="{{ old('company_name', $jobVacancy->company_name) }}"
                           required
                           maxlength="50"
                           x-on:input="count = $el.value.length"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 outline-none transition @error('company_name') border-rose-500 @enderror">
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
                <div x-data="{ count: 0, max: 50 }" x-init="count = $refs.position.value.length">
                    <label for="position" class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Posisi yang Dibutuhkan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="position"
                           name="position"
                           x-ref="position"
                           value="{{ old('position', $jobVacancy->position) }}"
                           required
                           maxlength="50"
                           x-on:input="count = $el.value.length"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 outline-none transition @error('position') border-rose-500 @enderror">
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
                    <input id="required_count" name="required_count" type="number" min="1" value="{{ old('required_count', $jobVacancy->required_count ?? 1) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                </div>

                <!-- Kualifikasi -->
                <div x-data="{ count: 0, max: 450 }" x-init="count = $refs.qualifications.value.length">
                    <label for="qualifications" class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Kualifikasi & Persyaratan Kerja <span class="text-rose-500">*</span>
                    </label>
                    <p class="text-xs text-slate-500 mb-2">Maks. 450 karakter (sekitar 8-10 poin singkat) agar muat di poster.</p>
                    <textarea id="qualifications"
                              name="qualifications"
                              x-ref="qualifications"
                              rows="6"
                              required
                              maxlength="450"
                              x-on:input="count = $el.value.length"
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 outline-none transition @error('qualifications') border-rose-500 @enderror">{{ old('qualifications', $jobVacancy->qualifications) }}</textarea>
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
                    <textarea id="job_description" name="job_description" rows="4" maxlength="1000" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('job_description', $jobVacancy->job_description) }}</textarea>
                </div>

                <!-- Informasi Tambahan -->
                <div x-data="{ count: 0, max: 150 }" x-init="count = $refs.otherInfo.value.length">
                    <label for="other_info" class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Informasi Tambahan & Benefit
                    </label>
                    <textarea id="other_info"
                              name="other_info"
                              x-ref="otherInfo"
                              rows="3"
                              maxlength="150"
                              x-on:input="count = $el.value.length"
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 outline-none transition @error('other_info') border-rose-500 @enderror">{{ old('other_info', $jobVacancy->other_info) }}</textarea>
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
                        <input id="application_deadline" name="application_deadline" type="date" value="{{ old('application_deadline', optional($jobVacancy->application_deadline)->format('Y-m-d')) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <p class="block text-sm font-semibold text-slate-800 mb-2">Cara Melamar *</p>
                        <div class="space-y-2">
                            @foreach (['Email', 'Website', 'Datang Langsung', 'Link Khusus', 'Lainnya'] as $method)
                                <label class="flex items-center gap-2 text-sm text-slate-700">
                                    <input type="radio" name="application_method" value="{{ $method }}" required @checked(old('application_method', $jobVacancy->application_method) === $method) class="border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span>{{ $method }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label for="application_address" class="block text-sm font-semibold text-slate-800 mb-1.5">Alamat / Tujuan Lamaran *</label>
                        <input id="application_address" name="application_address" value="{{ old('application_address', $jobVacancy->application_address) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label for="offered_salary" class="block text-sm font-semibold text-slate-800 mb-1.5">Gaji Ditawarkan</label>
                        <input id="offered_salary" name="offered_salary" value="{{ old('offered_salary', $jobVacancy->offered_salary) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    </div>
                </div>

                <div class="space-y-4 border-t border-slate-100 pt-6">
                    <div>
                        <label for="promotional_caption" class="block text-sm font-semibold text-slate-800 mb-1.5">Pesan Promosi / Caption</label>
                        <textarea id="promotional_caption" name="promotional_caption" rows="3" maxlength="500" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('promotional_caption', $jobVacancy->promotional_caption) }}</textarea>
                    </div>
                </div>

                <!-- Alamat -->
                <div x-data="{ count: 0, max: 100 }" x-init="count = $refs.address.value.length">
                    <label for="address" class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Alamat Lengkap Perusahaan <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="address"
                              name="address"
                              x-ref="address"
                              rows="2"
                              required
                              maxlength="100"
                              x-on:input="count = $el.value.length"
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 outline-none transition @error('address') border-rose-500 @enderror">{{ old('address', $jobVacancy->address) }}</textarea>
                    <div class="flex items-center justify-between mt-1.5">
                        @error('address')
                            <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @else
                            <span></span>
                        @enderror
                        <p class="text-xs ml-auto" :class="count > max - 15 ? 'text-rose-500 font-semibold' : 'text-slate-400'" x-text="count + '/' + max"></p>
                    </div>
                </div>

                <!-- MANDATORY: Alasan Edit -->
                <div class="bg-blue-50/70 border border-blue-200 rounded-xl p-5">
                    <label for="edit_reason" class="block text-sm font-bold text-blue-950 mb-1.5">
                        Alasan Perubahan / Catatan Edit <span class="text-rose-500">*</span>
                    </label>
                    <p class="text-xs text-blue-800 mb-2">
                        Jelaskan bagian apa yang diperbaiki (misal: "Memperbaiki typo pada kualifikasi minimal IPK" atau "Mengubah lokasi kantor"). Catatan ini akan dibaca oleh Admin.
                    </p>
                    <input type="text"
                           id="edit_reason"
                           name="edit_reason"
                           value="{{ old('edit_reason') }}"
                           required
                           class="w-full rounded-lg border border-blue-300 bg-white px-4 py-2.5 text-sm focus:border-blue-700 focus:ring-2 focus:ring-blue-500/20 outline-none transition @error('edit_reason') border-rose-500 @enderror"
                           placeholder="Contoh: Typo di bagian kualifikasi pendidikan, seharusnya D4/S1.">
                    @error('edit_reason')
                        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="work_location" class="block text-sm font-semibold text-slate-800 mb-1.5">Lokasi Penempatan *</label>
                    <input id="work_location" name="work_location" value="{{ old('work_location', $jobVacancy->work_location) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
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

                <!-- Buttons -->
                <div class="border-t border-slate-100 pt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('company.vacancies.show', $jobVacancy) }}"
                       class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-medium text-sm hover:bg-slate-50 transition min-h-[44px] flex items-center">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-lg bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm transition focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 min-h-[44px]">
                        Simpan Perubahan & Kirim ke Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
