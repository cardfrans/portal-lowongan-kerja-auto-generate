<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('admin.vacancies.show', $jobVacancy) }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 mb-1">
                    &larr; Kembali ke Review Lowongan
                </a>
                <h1 class="text-2xl font-bold text-slate-900">Preview & Generate Poster Instagram (1080x1350)</h1>
                <p class="text-sm text-slate-500 mt-0.5">{{ $jobVacancy->company_name }} &bull; {{ $jobVacancy->position }}</p>
            </div>

            @if ($jobVacancy->generated_poster_path)
                <div>
                    <a href="{{ route('admin.posters.download', $jobVacancy) }}"
                       class="inline-flex items-center px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm transition min-h-[44px]">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh Poster (JPG)
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div x-data="{
        selectedTemplate: {{ in_array($jobVacancy->selected_template, [1, 2], true) ? $jobVacancy->selected_template : 1 }},
        isGenerating: false,
        get previewUrl() {
            return '{{ url('/admin/posters/' . $jobVacancy->id . '/render') }}/' + this.selectedTemplate;
        }
    }"
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Template Selection & Controls -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Status Box -->
                @if ($jobVacancy->generated_poster_path)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 text-emerald-900 flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm">
                            <p class="font-bold">Poster Telah Dihasilkan</p>
                            <p class="text-xs text-emerald-700 mt-0.5">Template terakhir yang digunakan: Desain {{ $jobVacancy->selected_template }}. Anda dapat memilih opsi lain dan men-generate ulang sewaktu-waktu.</p>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Pilih Template Poster</h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Pilih salah satu dari 3 konsep desain poster yang sesuai dengan karakter posisi lowongan ini:
                        </p>
                    </div>

                    <!-- Template Options -->
                    <div class="space-y-3">
                        <!-- Option 1 -->
                        <div @click="selectedTemplate = 1"
                             :class="selectedTemplate === 1 ? 'border-blue-600 bg-blue-50/40 ring-2 ring-blue-500/20' : 'border-slate-200 hover:border-slate-300'"
                             class="p-4 rounded-xl border cursor-pointer transition flex items-start justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-blue-700 text-white text-xs font-bold flex items-center justify-center">1</span>
                                    <h3 class="font-bold text-slate-900 text-sm">Minimalist Corporate</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                                        Gaya bersih dan formal dengan latar terang, tipografi terstruktur, dan aksen biru korporat.
                                </p>
                            </div>
                            <div class="ms-3 mt-0.5">
                                <input type="radio" name="template_picker" :checked="selectedTemplate === 1" class="text-blue-600 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Option 2 -->
                        <div @click="selectedTemplate = 2"
                             :class="selectedTemplate === 2 ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'"
                             class="p-4 rounded-xl border cursor-pointer transition flex items-start justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-900 text-white text-xs font-bold flex items-center justify-center">2</span>
                                    <h3 class="font-bold text-slate-900 text-sm">Modern Dark Mode</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                                        Gaya kontemporer dengan latar slate gelap solid dan aksen tipografi emerald yang kontras.
                                </p>
                            </div>
                            <div class="ms-3 mt-0.5">
                                <input type="radio" name="template_picker" :checked="selectedTemplate === 2" class="text-emerald-600 focus:ring-emerald-500">
                            </div>
                        </div>

                    </div>

                    <!-- Form Action -->
                    <form method="POST"
                          action="{{ route('admin.posters.generate', $jobVacancy) }}"
                          @submit="isGenerating = true"
                          class="pt-4 border-t border-slate-100 space-y-4">
                        @csrf
                        <input type="hidden" name="template_id" :value="selectedTemplate">

                        <button type="submit"
                                :disabled="isGenerating"
                                class="w-full py-3 px-4 rounded-xl bg-blue-700 hover:bg-blue-800 disabled:bg-blue-400 text-white font-bold text-sm transition flex items-center justify-center gap-2 shadow-xs min-h-[48px]">
                            <template x-if="!isGenerating">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Generate Poster Instagram (1080x1350)
                                </span>
                            </template>
                            <template x-if="isGenerating">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses Render Gambar (Browsershot)...
                                </span>
                            </template>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Interactive Live Preview (Scaled to fit viewport) -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h3 class="font-bold text-slate-800 text-sm">Live Preview Poster</h3>
                            <span class="text-xs text-slate-400">&bull; Rasio 4:5 (1080 &times; 1350px)</span>
                        </div>
                        <a :href="previewUrl" target="_blank" class="text-xs font-semibold text-blue-700 hover:underline flex items-center gap-1">
                            Buka Ukuran Penuh (New Tab) &rarr;
                        </a>
                    </div>

                    <!-- Scaled Preview Container -->
                    <div class="w-full flex justify-center bg-slate-100 rounded-xl p-4 overflow-hidden border border-slate-200">
                        <div class="relative shadow-lg rounded-xl overflow-hidden border border-slate-300"
                             style="width: 432px; height: 540px;">
                            <iframe :src="previewUrl"
                                    class="border-0 pointer-events-none"
                                    style="width: 1080px; height: 1350px; transform: scale(0.4); transform-origin: top left;"
                                    title="Live Template Preview"></iframe>
                        </div>
                    </div>

                    <p class="text-center text-xs text-slate-500">
                        Preview di atas menampilkan auto-fill nyata dari data requirement yang diajukan oleh perusahaan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
