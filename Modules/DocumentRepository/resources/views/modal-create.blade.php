@php
    $kampus = ($units ?? collect())->first(fn($u) => $u->id === $u->unit_parent);
    $kampusId = $kampus->id ?? 'UIN';
    $kampusName = $kampus->unit_name ?? 'UIN Prof. K.H. Saifuddin Zuhri';

    $fakultasList = ($units ?? collect())->filter(fn($u) => $u->unit_parent === $kampusId && $u->id !== $kampusId)->values();
    $listFakultasArr = $fakultasList->map(fn($f) => ['id' => $f->id, 'name' => $f->unit_name])->toArray();

    $fakultasIds = $fakultasList->pluck('id')->toArray();
    $prodiList = ($units ?? collect())->filter(fn($u) => in_array($u->unit_parent, $fakultasIds))->values();
    $listProdiArr = $prodiList->map(fn($p) => ['id' => $p->id, 'name' => $p->unit_name, 'parent' => $p->unit_parent])->toArray();
@endphp

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formCreateDocument', () => ({
            tingkatUnit: '',
            filterFakultas: '',
            unitPemilikId: '{{ old('unit_id') }}',
            sifatDokumen: '{{ old('sifat_dokumen', 'Private') }}',
            fileName: '',
            kampusId: '{{ $kampusId }}',
            kampusName: '{{ $kampusName }}',
            listFakultas: @json($listFakultasArr),
            listProdi: @json($listProdiArr),
            init() {
                this.$watch('tingkatUnit', value => {
                    this.filterFakultas = '';
                    this.unitPemilikId = value === 'kampus' ? this.kampusId : '';
                });
            }
        }))
    })
</script>

<template x-teleport="#modal-root">
    <div x-data="{ openCreate: false }" @open-create-modal.window="openCreate = true" x-show="openCreate"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak
        x-init="if (Object.keys(@js($errors->messages())).length > 0) openCreate = true">

        <div @click.away="openCreate = false"
            class="relative w-full max-w-4xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

            <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                        <i class="fa-solid fa-file-arrow-up text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl">Unggah Dokumen Baru</h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm">Tambahkan dokumen ke repositori Sainteku</p>
                    </div>
                </div>
                <div class="shrink-0 w-8 sm:w-9"></div>
            </div>

            @if ($errors->any())
                <div class="mx-4 mt-4 rounded-xl border-l-4 border-red-500 bg-red-50 p-4 dark:bg-red-900/20 dark:border-red-400 sm:mx-6">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-600 dark:text-red-400 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-bold text-red-700 dark:text-red-400 mb-2">Validasi Gagal!</p>
                            <ul class="text-xs text-red-600 dark:text-red-300 space-y-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('DocumentRepository.store') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col flex-1 min-h-0" x-data="formCreateDocument">
                @csrf
                <input type="hidden" name="unit_id" :value="unitPemilikId" required>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-file-lines text-indigo-500"></i> Informasi Dokumen
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Judul Dokumen <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="document_title" value="{{ old('document_title') }}" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white"
                                    placeholder="Contoh: SK Rektor Tahun 2026 tentang Akademik">
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Tipe Dokumen <span class="text-red-500">*</span>
                                </label>
                                <select name="document_type_id" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                    <option value="">-- Pilih Tipe --</option>
                                    @foreach ($documentTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-eye text-indigo-500"></i> Visibilitas Dokumen
                            </h4>
                        </div>
                        <div class="p-5 space-y-4">
                            <input type="hidden" name="sifat_dokumen" :value="sifatDokumen" required>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <button type="button" @click="sifatDokumen = 'Publik'"
                                    class="rounded-xl border p-4 text-left transition"
                                    :class="sifatDokumen === 'Publik'
                                        ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500/20 dark:border-indigo-400 dark:bg-indigo-900/30'
                                        : 'border-gray-200 bg-gray-50 hover:border-gray-300 dark:border-gray-600 dark:bg-[#0f172a] dark:hover:border-gray-500'">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg"
                                            :class="sifatDokumen === 'Publik' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-800/50 dark:text-indigo-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'">
                                            <i class="fa-solid fa-globe"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">Public</p>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Semua pengguna dapat melihat dokumen ini di repositori.</p>
                                        </div>
                                    </div>
                                </button>
                                <button type="button" @click="sifatDokumen = 'Private'"
                                    class="rounded-xl border p-4 text-left transition"
                                    :class="sifatDokumen === 'Private'
                                        ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500/20 dark:border-indigo-400 dark:bg-indigo-900/30'
                                        : 'border-gray-200 bg-gray-50 hover:border-gray-300 dark:border-gray-600 dark:bg-[#0f172a] dark:hover:border-gray-500'">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg"
                                            :class="sifatDokumen === 'Private' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-800/50 dark:text-indigo-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'">
                                            <i class="fa-solid fa-lock"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">Private</p>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hanya Anda yang dapat melihat dan mengakses dokumen ini.</p>
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-4 dark:border-amber-800/50 dark:bg-amber-900/20">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_ppid" value="1" {{ old('is_ppid') ? 'checked' : '' }}
                                        class="mt-1 h-5 w-5 rounded border-gray-300 text-amber-600 focus:ring-2 focus:ring-amber-500 dark:border-gray-600 dark:bg-gray-900">
                                    <div class="flex-1">
                                        <span class="block text-sm font-bold text-gray-900 dark:text-white">
                                            <i class="fa-solid fa-clipboard-check text-amber-600 dark:text-amber-400 mr-1"></i>
                                            Dokumen PPID
                                        </span>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            Tandai jika dokumen termasuk kategori informasi publik Pejabat Pengelola Informasi dan Dokumentasi.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-building text-indigo-500"></i> Unit Pemilik
                            </h4>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Tingkat Unit <span class="text-red-500">*</span>
                                </label>
                                <select name="tingkat_unit" x-model="tingkatUnit" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                    <option value="">-- Pilih Tingkat Unit --</option>
                                    <option value="kampus">Universitas / Institut</option>
                                    <option value="fakultas">Fakultas</option>
                                    <option value="prodi">Program Studi</option>
                                </select>
                            </div>

                            <div x-show="tingkatUnit === 'kampus'" x-cloak x-transition>
                                <div class="rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm text-indigo-800 dark:border-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                    <span class="font-bold">Universitas Terpilih:</span> <span x-text="kampusName"></span>
                                </div>
                            </div>

                            <div x-show="tingkatUnit === 'fakultas'" x-cloak x-transition>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Pilih Fakultas</label>
                                <select x-model="unitPemilikId" x-bind:required="tingkatUnit === 'fakultas'"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                    <option value="">-- Pilih Fakultas --</option>
                                    <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                        <option :value="fakultas.id" x-text="fakultas.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 gap-3" x-show="tingkatUnit === 'prodi'" x-cloak x-transition>
                                <div>
                                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Filter Fakultas</label>
                                    <select x-model="filterFakultas"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                        <option value="">-- Tampilkan Semua Prodi --</option>
                                        <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                            <option :value="fakultas.id" x-text="fakultas.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Pilih Prodi</label>
                                    <select x-model="unitPemilikId" x-bind:required="tingkatUnit === 'prodi'"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                        <option value="">-- Pilih Prodi --</option>
                                        <template x-for="prodi in listProdi.filter(p => filterFakultas === '' || p.parent === filterFakultas)" :key="prodi.id">
                                            <option :value="prodi.id" x-text="prodi.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-calendar text-indigo-500"></i> Masa Berlaku
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Tanggal Berlaku <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-regular fa-calendar"></i></span>
                                    <input type="text" name="effective_date" value="{{ old('effective_date', now()->toDateString()) }}" required
                                        placeholder="Pilih Tanggal..." x-init="flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd F Y', allowInput: false, static: true })"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 py-2.5 pl-10 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white cursor-pointer">
                                </div>
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Tanggal Kadaluarsa</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-regular fa-calendar"></i></span>
                                    <input type="text" name="expired_date" value="{{ old('expired_date') }}"
                                        placeholder="Pilih Tanggal..." x-init="flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd F Y', allowInput: false, static: true })"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 py-2.5 pl-10 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-file-pdf text-indigo-500"></i> File Dokumen
                            </h4>
                        </div>
                        <div class="p-5">
                            <div class="flex justify-center rounded-xl border border-dashed border-gray-300 px-6 py-10 dark:border-gray-600 hover:bg-gray-50 transition dark:hover:bg-gray-800/50">
                                <div class="text-center">
                                    <i class="fa-solid fa-file-pdf text-4xl text-gray-300 dark:text-gray-600 mb-4" :class="{'text-indigo-500': fileName}"></i>
                                    <div class="flex text-sm leading-6 text-gray-600 dark:text-gray-400 justify-center">
                                        <label for="file-upload"
                                            class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                            <span x-show="!fileName">Klik untuk memilih file</span>
                                            <span x-show="fileName">Ubah File</span>
                                            <input id="file-upload" name="document_file" type="file" class="sr-only"
                                                accept=".pdf,.doc,.docx" required
                                                @change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''">
                                        </label>
                                    </div>
                                    <p x-show="!fileName" class="text-xs text-gray-500 mt-2">PDF, DOC, DOCX maksimal 10MB</p>
                                    <p x-show="fileName" x-cloak class="text-sm font-bold text-green-600 dark:text-green-400 mt-2 flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-check-circle"></i> <span x-text="fileName"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 flex flex-col-reverse gap-3 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 dark:bg-[#1e293b]/95 dark:border-gray-700 sm:flex-row sm:justify-end">
                    <button type="button" @click="openCreate = false"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        <i class="fas fa-xmark"></i> Batal
                    </button>
                    <button type="submit"
                        class="inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-indigo-700 transition">
                        <i class="fas fa-cloud-arrow-up"></i> Unggah Dokumen
                    </button>
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
</style>