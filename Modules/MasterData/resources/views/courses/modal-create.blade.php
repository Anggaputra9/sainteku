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
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm"
                            x-text="createMode === 'bulk' ? 'Tambah banyak mata kuliah sekaligus' : 'Tambahkan mata kuliah baru ke program studi'">
                        </p>
                    </div>
                </div>
                <div class="shrink-0 w-8 sm:w-9"></div>
            </div>

            <div class="shrink-0 flex gap-2 border-b border-gray-200 bg-white px-4 sm:px-8 py-2 dark:border-gray-700 dark:bg-[#1e293b]">
                <button type="button" @click="createMode = 'manual'"
                    class="rounded-lg px-4 py-2 text-xs font-bold transition sm:text-sm"
                    :class="createMode === 'manual' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'">
                    Manual
                </button>
                <button type="button" @click="createMode = 'bulk'"
                    class="rounded-lg px-4 py-2 text-xs font-bold transition sm:text-sm"
                    :class="createMode === 'bulk' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'">
                    Bulk
                </button>
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

            <form x-show="createMode === 'manual'" action="{{ route('masterdata.courses.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
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
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>

            <div x-show="createMode === 'bulk'" x-cloak class="flex flex-col flex-1 overflow-hidden">
                <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-sliders text-indigo-500"></i> Pengaturan Batch
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Fakultas <span class="text-red-500">*</span>
                                </label>
                                <select x-model="bulkFakultas" @change="loadBulkProdis()" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Pilih Fakultas --</option>
                                    @foreach($faculties as $fak)
                                        <option value="{{ $fak->id }}">{{ $fak->unit_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Program Studi <span class="text-red-500">*</span>
                                </label>
                                <select x-model="bulkProdi" required :disabled="!bulkFakultas"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 disabled:opacity-60 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Pilih Prodi --</option>
                                    <template x-for="prodi in bulkProdis" :key="prodi.id">
                                        <option :value="prodi.id" x-text="prodi.unit_name"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select x-model="bulkIsActive"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-2">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-list text-indigo-500"></i> Input Bulk
                            </h4>
                            <div class="flex flex-wrap items-center gap-2">
                                <a :href="templateUrl" target="_blank"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
                                    <i class="fa-solid fa-download"></i> Template
                                </a>
                                <label
                                    class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 dark:border-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                    <i class="fa-solid fa-file-excel"></i> Excel/CSV
                                    <input type="file" class="hidden" accept=".csv,.xlsx,.xls" @change="handleBulkFile($event)">
                                </label>
                            </div>
                        </div>
                        <div class="p-5 space-y-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Satu baris = satu mata kuliah (nama saja). Maksimal 100 baris. Kode otomatis: <span class="font-mono text-gray-700 dark:text-gray-300">{PRODI}{3 digit}</span>
                            </p>
                            <textarea x-model="bulkText" rows="8"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 text-sm font-mono outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                placeholder="Pemrograman Web&#10;Basis Data&#10;Jaringan Komputer"></textarea>
                        </div>
                    </div>

                    <template x-if="bulkResult">
                        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="mb-3 flex flex-wrap items-center gap-3 text-sm font-bold">
                                <span class="text-emerald-600 dark:text-emerald-400">
                                    <i class="fa-solid fa-check-circle"></i>
                                    <span x-text="bulkResult.success_count"></span> berhasil
                                </span>
                                <span class="text-red-600 dark:text-red-400" x-show="bulkResult.failed_count > 0">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    <span x-text="bulkResult.failed_count"></span> gagal
                                </span>
                            </div>
                            <div class="max-h-40 overflow-y-auto custom-scrollbar" x-show="bulkResult.log && bulkResult.log.length > 0">
                                <table class="w-full text-left text-xs text-gray-600 dark:text-gray-400">
                                    <thead class="text-[10px] uppercase text-gray-400">
                                        <tr>
                                            <th class="pb-2 pr-2">Nama</th>
                                            <th class="pb-2">Alasan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(row, idx) in bulkResult.log" :key="idx">
                                            <tr class="border-t border-gray-100 dark:border-gray-700">
                                                <td class="py-1.5 pr-2" x-text="row.name"></td>
                                                <td class="py-1.5 text-red-600 dark:text-red-400" x-text="row.reason"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <p class="mt-2 text-xs text-amber-700 dark:text-amber-400" x-show="bulkResult.failed_count > 0">
                                Baris gagal tetap ada di textarea. Perbaiki lalu simpan lagi.
                            </p>
                        </div>
                    </template>
                </div>

                <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                    <div class="flex flex-row flex-nowrap items-center justify-end gap-2 sm:gap-3">
                        <button type="button" @click="openCreate = false"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="button" @click="submitBulk()" :disabled="bulkImporting"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-indigo-700 disabled:opacity-60 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas" :class="bulkImporting ? 'fa-circle-notch fa-spin' : 'fa-save'"></i>
                            <span x-text="bulkImporting ? 'Proses...' : 'Simpan'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    function openCourseCreateModal() {
        return {
            openCreate: false,
            createMode: 'manual',
            createFakultas: @js(old('fakultas_id', '')),
            createProdi: @js(old('unit_id', '')),
            createProdis: [],
            bulkFakultas: '',
            bulkProdi: '',
            bulkProdis: [],
            bulkText: '',
            bulkIsActive: '1',
            bulkImporting: false,
            bulkResult: null,
            bulkStoreUrl: @json(route('masterdata.courses.bulk.store')),
            templateUrl: @json(route('masterdata.courses.bulk.template')),

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

            async loadBulkProdis() {
                this.bulkProdi = '';
                this.bulkProdis = [];

                if (!this.bulkFakultas) {
                    return;
                }

                try {
                    const response = await fetch(`{{ route('masterdata.courses.api.prodis') }}?fakultas_id=${this.bulkFakultas}`, {
                        headers: { 'Accept': 'application/json' },
                    });
                    if (response.ok) {
                        this.bulkProdis = await response.json();
                    }
                } catch (error) {
                    console.error('Gagal memuat prodi', error);
                }
            },

            async handleBulkFile(event) {
                const file = event.target.files?.[0];
                event.target.value = '';
                if (!file) return;

                if (file.name.toLowerCase().endsWith('.csv')) {
                    const text = await file.text();
                    this.appendBulkLines(this.parseCsvToLines(text));
                    return;
                }

                if (typeof XLSX === 'undefined') {
                    alert('Library Excel belum dimuat. Muat ulang halaman.');
                    return;
                }

                const buffer = await file.arrayBuffer();
                const workbook = XLSX.read(buffer, { type: 'array' });
                const sheet = workbook.Sheets[workbook.SheetNames[0]];
                const rows = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });
                this.appendBulkLines(this.rowsToBulkLines(rows));
            },

            parseCsvToLines(text) {
                const rows = text.split(/\r?\n/).map(line => line.split(/[,;]\s*/));
                return this.rowsToBulkLines(rows);
            },

            rowsToBulkLines(rows) {
                const lines = [];
                rows.forEach((row, index) => {
                    const cells = (row || []).map(cell => String(cell).trim()).filter(Boolean);
                    if (cells.length === 0) return;

                    const header = cells.map(c => c.toLowerCase());
                    if (index === 0 && header.includes('nama')) {
                        return;
                    }

                    lines.push(cells[0]);
                });

                return lines;
            },

            appendBulkLines(lines) {
                if (!lines.length) return;
                const chunk = lines.join('\n');
                this.bulkText = this.bulkText.trim() ? `${this.bulkText.trim()}\n${chunk}` : chunk;
            },

            validateBulkSetup() {
                if (!this.bulkFakultas) return 'Pilih fakultas.';
                if (!this.bulkProdi) return 'Pilih program studi.';
                if (!this.bulkText.trim()) return 'Input bulk masih kosong.';
                return null;
            },

            async submitBulk() {
                const error = this.validateBulkSetup();
                if (error) {
                    alert(error);
                    return;
                }

                this.bulkImporting = true;

                try {
                    const response = await fetch(this.bulkStoreUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({
                            unit_id: this.bulkProdi,
                            is_active: this.bulkIsActive,
                            bulk_text: this.bulkText,
                        }),
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        alert(result.message || 'Gagal import bulk mata kuliah.');
                        return;
                    }

                    this.bulkResult = result;
                    this.bulkText = result.failed_text || '';

                    window.dispatchEvent(new CustomEvent('courses-bulk-imported', {
                        bubbles: true,
                        detail: result,
                    }));

                    if (result.success_count > 0 && result.failed_count === 0) {
                        this.openCreate = false;
                        this.resetBulk();
                    }
                } catch (e) {
                    console.error(e);
                    alert('Terjadi kesalahan saat import bulk.');
                } finally {
                    this.bulkImporting = false;
                }
            },

            resetBulk() {
                this.createMode = 'manual';
                this.bulkText = '';
                this.bulkIsActive = '1';
                this.bulkResult = null;
                this.bulkImporting = false;
            },
        };
    }
</script>