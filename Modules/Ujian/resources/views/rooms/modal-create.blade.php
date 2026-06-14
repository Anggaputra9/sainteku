@php
    $inputClass = 'w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 placeholder:text-gray-400 dark:border-gray-600 dark:bg-[#0f172a] dark:text-white dark:placeholder:text-gray-500';
    $datetimeClass = 'datetime-input';
    $toggleTrackClass = "relative w-11 h-6 bg-gray-300 rounded-full outline-none dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-gray-500 peer-checked:bg-indigo-600";
    $settingRowClass = 'setting-row px-5 py-3.5 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_220px] gap-3 md:gap-6 items-center transition-colors duration-150';
@endphp

<template x-teleport="#modal-root">
<div x-data="openRoomCreateModal()" @open-create-modal.window="openCreate = true; resetForm()" x-show="openCreate"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center p-3 sm:p-6 overflow-y-auto backdrop-blur-sm bg-gray-900/40 dark:bg-black/60"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div @click.away="openCreate = false"
        class="relative w-full max-w-3xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

        {{-- HEADER --}}
        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
            <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                    <i class="fa-solid fa-chalkboard-user text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                </div>
                <div class="min-w-0 leading-tight">
                    <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl">Buat Ruang Ujian</h3>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm">Konfigurasi ruang ujian dari paket soal yang sudah disetujui</p>
                </div>
            </div>
            <div class="shrink-0 w-8 sm:w-9"></div>
        </div>

        <form @submit.prevent="submitForm()" class="flex flex-col flex-1 overflow-hidden">
            <div class="flex-1 overflow-y-auto custom-scrollbar p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a]">

                {{-- Section: Paket Soal --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-file-circle-check text-indigo-500 dark:text-indigo-400"></i> Paket Soal
                        </h4>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                    Fakultas <span class="text-red-500">*</span>
                                </label>
                                <select x-model="filterFakultas" @change="onFakultasChange()" required class="{{ $inputClass }}">
                                    <option value="">— Pilih fakultas —</option>
                                    <template x-for="f in facultiesList" :key="f.id">
                                        <option :value="f.id" x-text="f.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                    Program Studi <span class="text-red-500">*</span>
                                </label>
                                <select x-model="filterProdi" @change="onProdiChange()" :disabled="!filterFakultas" required
                                    class="{{ $inputClass }} disabled:cursor-not-allowed disabled:opacity-60">
                                    <option value="">— Pilih program studi —</option>
                                    <template x-for="p in prodisList" :key="p.id">
                                        <option :value="p.id" x-text="p.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                    Mata Kuliah <span class="text-red-500">*</span>
                                </label>
                                <select x-model="filterCourse" @change="onCourseChange()" :disabled="!filterProdi" required
                                    class="{{ $inputClass }} disabled:cursor-not-allowed disabled:opacity-60">
                                    <option value="">— Pilih mata kuliah —</option>
                                    <template x-for="c in coursesList" :key="c.id">
                                        <option :value="c.id" x-text="c.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                Paket Soal (Approved) <span class="text-red-500">*</span>
                            </label>
                            <select x-model="form.proposal_id" :disabled="!filterCourse" required
                                class="{{ $inputClass }} disabled:cursor-not-allowed disabled:opacity-60">
                                <option value="">— Pilih paket soal —</option>
                                <template x-for="p in filteredProposals" :key="p.id">
                                    <option :value="p.id" x-text="p.short_label"></option>
                                </template>
                            </select>
                            <p class="mt-1.5 text-[11px] text-gray-500 dark:text-gray-400" x-show="filterCourse && filteredProposals.length === 0" x-cloak>
                                Tidak ada paket soal yang disetujui untuk mata kuliah ini.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Section: Informasi Ruang --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-indigo-500 dark:text-indigo-400"></i> Informasi Ruang
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                Judul <span class="text-red-500">*</span>
                            </label>
                            <input x-model="form.title" required maxlength="150" placeholder="Contoh: UTS Pemrograman Web 2026"
                                class="{{ $inputClass }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Deskripsi</label>
                            <textarea x-model="form.description" rows="2" maxlength="1000" placeholder="Catatan untuk peserta ujian (opsional)"
                                class="{{ $inputClass }}"></textarea>
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                Durasi Ujian (menit) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" min="1" max="600" x-model.number="form.duration_minutes" required
                                class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" x-model="form.start_at" required class="{{ $inputClass }} {{ $datetimeClass }}">
                            <p class="mt-1.5 text-[11px] text-gray-500 dark:text-gray-400">
                                Ujian dimulai pada jadwal ini. Mahasiswa dapat masuk mulai 15 menit sebelum waktu mulai.
                            </p>
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                Kebijakan Tab Switch <span class="text-red-500">*</span>
                            </label>
                            <select x-model="form.tab_switch_policy" class="{{ $inputClass }}">
                                <option value="strict">Tanpa Toleransi (auto-submit 1x)</option>
                                <option value="limited">Limited (ada batas)</option>
                                <option value="unlimited">Tanpa Batas</option>
                            </select>
                        </div>
                        <div x-show="form.tab_switch_policy === 'limited'" x-cloak class="md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Batas Tab Switch</label>
                            <input type="number" min="0" max="50" x-model.number="form.tab_switch_limit"
                                class="{{ $inputClass }}">
                        </div>
                    </div>
                </div>

                {{-- Section: Pengaturan Tambahan --}}
                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-indigo-500 dark:text-indigo-400"></i> Pengaturan Tambahan
                        </h4>
                    </div>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li class="{{ $settingRowClass }}">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-700 dark:text-gray-300">Acak Urutan Soal</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tiap mahasiswa mendapat urutan soal yang berbeda</div>
                            </div>
                            <label class="md:justify-self-end grid grid-cols-[auto_72px] items-center gap-3 cursor-pointer select-none">
                                <span class="relative inline-flex shrink-0">
                                    <input type="checkbox" x-model="form.shuffle_questions" class="sr-only peer">
                                    <span class="{{ $toggleTrackClass }}"></span>
                                </span>
                                <span class="text-sm font-semibold text-right tabular-nums"
                                    :class="form.shuffle_questions ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'"
                                    x-text="form.shuffle_questions ? 'Aktif' : 'Nonaktif'"></span>
                            </label>
                        </li>
                        <li class="{{ $settingRowClass }}">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-700 dark:text-gray-300">Tampilkan Sisa Waktu</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Mahasiswa melihat countdown waktu pengerjaan</div>
                            </div>
                            <label class="md:justify-self-end grid grid-cols-[auto_72px] items-center gap-3 cursor-pointer select-none">
                                <span class="relative inline-flex shrink-0">
                                    <input type="checkbox" x-model="form.show_remaining_time" class="sr-only peer">
                                    <span class="{{ $toggleTrackClass }}"></span>
                                </span>
                                <span class="text-sm font-semibold text-right tabular-nums"
                                    :class="form.show_remaining_time ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'"
                                    x-text="form.show_remaining_time ? 'Aktif' : 'Nonaktif'"></span>
                            </label>
                        </li>
                        <li class="{{ $settingRowClass }}">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-700 dark:text-gray-300">Koreksi Otomatis dengan AI</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">AI mengoreksi jawaban setelah mahasiswa submit</div>
                            </div>
                            <label class="md:justify-self-end grid grid-cols-[auto_72px] items-center gap-3 cursor-pointer select-none">
                                <span class="relative inline-flex shrink-0">
                                    <input type="checkbox" x-model="form.auto_grading_enabled" class="sr-only peer">
                                    <span class="{{ $toggleTrackClass }}"></span>
                                </span>
                                <span class="text-sm font-semibold text-right tabular-nums"
                                    :class="form.auto_grading_enabled ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'"
                                    x-text="form.auto_grading_enabled ? 'Aktif' : 'Nonaktif'"></span>
                            </label>
                        </li>
                    </ul>
                </div>

                <template x-if="formError">
                    <div class="flex items-start gap-3 border-l-4 border-red-500 bg-red-50 p-4 rounded-r-lg dark:bg-red-900/20 dark:border-red-400">
                        <i class="fa-solid fa-circle-exclamation text-red-600 dark:text-red-400 mt-0.5 shrink-0"></i>
                        <p class="text-sm font-semibold text-red-700 dark:text-red-300" x-text="formError"></p>
                    </div>
                </template>
            </div>

            {{-- FOOTER --}}
            <div class="shrink-0 border-t border-gray-200 bg-white/95 backdrop-blur-md px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2 sm:gap-3">
                    <button type="button" @click="openCreate = false"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 shadow-sm hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" :disabled="submitting"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 disabled:opacity-60 disabled:cursor-not-allowed sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-save"></i>
                        <span x-text="submitting ? 'Menyimpan…' : 'Simpan'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</template>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }

    .setting-row:hover { background-color: rgb(249 250 251); }
    .dark .setting-row:hover { background-color: rgba(51, 65, 85, 0.55); }

    .datetime-input { color-scheme: light; }
    input[type="datetime-local"].datetime-input::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 1;
    }
    .dark .datetime-input { color-scheme: dark; }
    .dark input[type="datetime-local"].datetime-input::-webkit-calendar-picker-indicator {
        filter: brightness(0) invert(1);
        opacity: 1;
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('openRoomCreateModal', () => ({
            openCreate: false,
            submitting: false,
            formError: '',
            proposalsAll: @json($proposals),
            proposalDefaults: @json($proposalDefaults),
            filterFakultas: '',
            filterProdi: '',
            filterCourse: '',

            get facultiesList() {
                const map = new Map();
                this.proposalsAll.forEach((p) => {
                    if (p.fakultas_id) {
                        map.set(String(p.fakultas_id), { id: String(p.fakultas_id), name: p.fakultas_name });
                    }
                });
                return [...map.values()].sort((a, b) => a.name.localeCompare(b.name));
            },

            get prodisList() {
                if (!this.filterFakultas) return [];
                const map = new Map();
                this.proposalsAll
                    .filter((p) => String(p.fakultas_id) === String(this.filterFakultas))
                    .forEach((p) => {
                        if (p.prodi_id) {
                            map.set(String(p.prodi_id), { id: String(p.prodi_id), name: p.prodi_name });
                        }
                    });
                return [...map.values()].sort((a, b) => a.name.localeCompare(b.name));
            },

            get coursesList() {
                if (!this.filterProdi) return [];
                const map = new Map();
                this.proposalsAll
                    .filter((p) => String(p.prodi_id) === String(this.filterProdi))
                    .forEach((p) => {
                        if (p.course_id) {
                            map.set(String(p.course_id), { id: String(p.course_id), name: p.course_name });
                        }
                    });
                return [...map.values()].sort((a, b) => a.name.localeCompare(b.name));
            },

            get filteredProposals() {
                if (!this.filterCourse) return [];
                return this.proposalsAll
                    .filter((p) => String(p.course_id) === String(this.filterCourse))
                    .map((p) => ({
                        ...p,
                        short_label: p.exam_type + (p.period_name ? ` (${p.period_name})` : ''),
                    }));
            },

            onFakultasChange() {
                this.filterProdi = '';
                this.filterCourse = '';
                this.form.proposal_id = '';
            },

            onProdiChange() {
                this.filterCourse = '';
                this.form.proposal_id = '';
            },

            onCourseChange() {
                this.form.proposal_id = '';
                const proposals = this.filteredProposals;
                if (proposals.length === 1) {
                    this.form.proposal_id = String(proposals[0].id);
                }
            },

            applyFilterDefaults() {
                const d = this.proposalDefaults || {};
                this.filterFakultas = d.fakultas_id ? String(d.fakultas_id) : '';
                this.filterProdi = d.prodi_id ? String(d.prodi_id) : '';
                this.filterCourse = d.course_id ? String(d.course_id) : '';
                if (d.proposal_id) {
                    this.form.proposal_id = String(d.proposal_id);
                }
            },

            form: {
                proposal_id: '',
                title: '',
                description: '',
                start_at: '',
                duration_minutes: 60,
                tab_switch_policy: 'strict',
                tab_switch_limit: 0,
                shuffle_questions: false,
                show_remaining_time: true,
                auto_grading_enabled: false,
            },
            csrf: document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',

            blankForm() {
                return {
                    proposal_id: '',
                    title: '',
                    description: '',
                    start_at: '',
                    duration_minutes: 60,
                    tab_switch_policy: 'strict',
                    tab_switch_limit: 0,
                    shuffle_questions: false,
                    show_remaining_time: true,
                    auto_grading_enabled: false,
                };
            },

            resetForm() {
                this.form = this.blankForm();
                this.formError = '';
                this.applyFilterDefaults();
            },

            async submitForm() {
                this.submitting = true;
                this.formError = '';

                try {
                    const res = await fetch('{{ route('ujian.rooms.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf,
                        },
                        body: JSON.stringify(this.form),
                    });
                    const data = await res.json().catch(() => ({}));

                    if (!res.ok) {
                        throw new Error(data?.message || (data?.errors ? Object.values(data.errors).flat().join(', ') : 'Gagal menyimpan.'));
                    }

                    this.openCreate = false;
                    window.dispatchEvent(new CustomEvent('rooms-data-changed', {
                        bubbles: true,
                        detail: { message: data.message || 'Ruang ujian berhasil dibuat.' },
                    }));
                } catch (e) {
                    this.formError = e.message;
                } finally {
                    this.submitting = false;
                }
            },
        }));
    });
</script>