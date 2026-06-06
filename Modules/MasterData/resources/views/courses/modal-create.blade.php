<template x-teleport="#modal-root">
    <div x-data="openCourseCreateModal()" @open-create-modal.window="openCreate = true" x-show="openCreate"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak
        x-init="if (Object.keys(@js($errors->messages())).length > 0) openCreate = true">

        <div @click.away="openCreate = false"
            class="relative w-full max-w-2xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

            <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                        <i class="fa-solid fa-book-open text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl">Tambah Mata Kuliah</h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm">Tambahkan mata kuliah baru ke program studi</p>
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

            <form action="{{ route('masterdata.courses.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-book-open text-indigo-500"></i> Informasi Mata Kuliah
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Kode Mata Kuliah</label>
                                <input type="text" disabled placeholder="Dibuat otomatis oleh sistem"
                                    class="w-full rounded-xl border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed outline-none dark:bg-[#0f172a]/50 dark:text-gray-400 dark:border-gray-600">
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Nama Mata Kuliah <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="course_name" required maxlength="100" placeholder="Contoh: Pemrograman Web"
                                    value="{{ old('course_name') }}"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600 @error('course_name') border-red-500 @enderror">
                                @error('course_name')
                                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-sitemap text-indigo-500"></i> Organisasi Pengampu
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Fakultas <span class="text-red-500">*</span>
                                </label>
                                <select x-model="createFakultas" @change="loadCreateProdis()" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Pilih Fakultas --</option>
                                    @foreach($faculties as $fak)
                                        <option value="{{ $fak->id }}" @selected(old('fakultas_id') == $fak->id)>{{ $fak->unit_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Program Studi <span class="text-red-500">*</span>
                                </label>
                                <select name="unit_id" x-model="createProdi" required :disabled="!createFakultas"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 disabled:opacity-60 dark:bg-[#0f172a] dark:text-white dark:border-gray-600 @error('unit_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Prodi --</option>
                                    <template x-for="prodi in createProdis" :key="prodi.id">
                                        <option :value="prodi.id" x-text="prodi.unit_name"></option>
                                    </template>
                                </select>
                                @error('unit_id')
                                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="is_active" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="1" @selected(old('is_active') == '1' || !$errors->any())>Aktif</option>
                                    <option value="0" @selected(old('is_active') == '0')>Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                    <div class="flex flex-row flex-nowrap items-center justify-end gap-2 sm:gap-3">
                        <button type="button" @click="openCreate = false"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-indigo-700 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-save"></i> Simpan Mata Kuliah
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    function openCourseCreateModal() {
        return {
            openCreate: false,
            createFakultas: @js(old('fakultas_id', '')),
            createProdi: @js(old('unit_id', '')),
            createProdis: [],

            init() {
                if (this.createFakultas) {
                    this.loadCreateProdis(true);
                }
            },

            async loadCreateProdis(keepProdi = false) {
                if (!keepProdi) {
                    this.createProdi = '';
                }
                this.createProdis = [];

                if (!this.createFakultas) {
                    return;
                }

                try {
                    const response = await fetch(`{{ route('masterdata.courses.api.prodis') }}?fakultas_id=${this.createFakultas}`, {
                        headers: { 'Accept': 'application/json' },
                    });
                    if (response.ok) {
                        this.createProdis = await response.json();
                    }
                } catch (error) {
                    console.error('Gagal memuat prodi', error);
                }
            },
        };
    }
</script>