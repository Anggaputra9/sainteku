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

<template x-teleport="#modal-root">
<div x-data="openInfrastructureDetailModal()" @open-detail-modal.window="handleOpenDetail($event)" x-show="openDetail"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div @click.away="openDetail = false"
        class="relative w-full max-w-4xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
            <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                    <i class="fa-solid fa-boxes-stacked text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                </div>
                <div class="min-w-0 leading-tight">
                    <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl"
                        x-text="editMode ? 'Edit Infrastruktur' : 'Detail Infrastruktur'"></h3>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="itemData.item_name"></p>
                </div>
            </div>
            <div class="shrink-0 w-8 sm:w-9"></div>
        </div>

        {{-- MODE LIHAT --}}
        <div x-show="!editMode" class="flex flex-col flex-1 overflow-hidden">
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-box text-indigo-500"></i> Informasi Barang
                        </h4>
                    </div>
                    <div class="p-5">
                        <div class="flex flex-col sm:flex-row gap-5">
                            <template x-if="itemData.photo_url">
                                <button type="button" @click="previewPhoto()"
                                    class="group relative mx-auto sm:mx-0 h-28 w-28 shrink-0 overflow-hidden rounded-xl ring-2 ring-indigo-100 dark:ring-indigo-900/50">
                                    <img :src="itemData.photo_url" :alt="itemData.item_name" class="h-full w-full object-cover transition group-hover:scale-110">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 transition group-hover:opacity-100">
                                        <i class="fa-solid fa-magnifying-glass text-white"></i>
                                    </div>
                                </button>
                            </template>
                            <div class="grid flex-1 grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Kode</div>
                                    <div class="text-sm font-mono font-semibold text-indigo-700 dark:text-indigo-400" x-text="itemData.id"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Nama Barang</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="itemData.item_name"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Merk</div>
                                    <div class="text-sm text-gray-900 dark:text-white" x-text="itemData.brand || 'Tanpa Merk'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Kategori</div>
                                    <span class="inline-flex rounded-md bg-purple-50 px-2.5 py-0.5 text-xs font-medium text-purple-700 border border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800"
                                        x-text="itemData.type_description"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-warehouse text-indigo-500"></i> Manajemen Aset
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Stok</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                <span x-text="itemData.stock"></span>
                                <span class="text-gray-500" x-text="itemData.unit_measure || 'PCS'"></span>
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Harga</div>
                            <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                Rp <span x-text="itemData.price_formatted"></span>
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Unit Pemilik</div>
                            <div class="text-sm text-gray-900 dark:text-white" x-text="itemData.unit_name || 'Universitas (Umum)'"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Status</div>
                            <span x-show="itemData.status == '1'"
                                class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span> Baik
                            </span>
                            <span x-show="itemData.status != '1'"
                                class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span> Rusak
                            </span>
                        </div>
                        <div class="md:col-span-2" x-show="itemData.description">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Deskripsi</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line" x-text="itemData.description"></div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-link text-indigo-500"></i> Relasi Data
                        </h4>
                    </div>
                    <div class="p-5">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Riwayat Peminjaman</div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            <span x-text="itemData.loan_count"></span> transaksi
                        </div>
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400" x-show="!canDelete">
                            Infrastruktur tidak dapat dihapus karena masih memiliki riwayat peminjaman.
                        </p>
                    </div>
                </div>
            </div>

            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2 sm:gap-4">
                    <button type="button" @click="openDetail = false"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                        <button type="button" x-show="canDelete" @click="confirmDelete()"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-red-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-red-700 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                        <button type="button" @click="enterEditMode()"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-blue-700 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODE EDIT --}}
        <form x-show="editMode" :action="url" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden m-0">
            @csrf
            @method('PUT')
            <input type="hidden" name="unit_id" :value="unitPemilikId">

            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-box text-indigo-500"></i> Informasi Barang
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Kode</label>
                            <input type="text" readonly x-model="itemData.id"
                                class="w-full rounded-xl border-gray-300 bg-gray-100 px-4 py-2.5 text-sm font-mono text-gray-500 cursor-not-allowed outline-none dark:bg-[#0f172a]/50 dark:text-gray-400 dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Nama Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="item_name" required x-model="itemData.item_name"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Kategori Tipe <span class="text-red-500">*</span>
                            </label>
                            <select name="inventory_type" x-model="itemData.inventory_type" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($inventoryTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Merk</label>
                            <input type="text" name="brand" x-model="itemData.brand"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Satuan</label>
                            <select name="unit_measure" x-model="itemData.unit_measure"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Satuan --</option>
                                <option value="PCS">PCS</option>
                                <option value="UNIT">UNIT</option>
                                <option value="SET">SET</option>
                                <option value="LEMBAR">LEMBAR</option>
                                <option value="PAK">PAK</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stock" required min="0" x-model="itemData.stock"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Harga (Rp)</label>
                            <input type="number" name="price" min="0" x-model="itemData.price"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" x-model="itemData.status" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="1">Baik / Aktif</option>
                                <option value="0">Rusak / Nonaktif</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Ubah Foto (Opsional)</label>
                            <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:cursor-pointer file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-gray-700 dark:file:text-gray-200">
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Deskripsi</label>
                            <textarea name="description" rows="2" x-model="itemData.description"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"></textarea>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-sitemap text-indigo-500"></i> Unit Pemilik
                        </h4>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Tingkat Unit</label>
                            <select x-model="tingkatUnit"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Universitas / Umum --</option>
                                <option value="kampus">Universitas / Institut</option>
                                <option value="fakultas">Fakultas</option>
                                <option value="prodi">Program Studi</option>
                            </select>
                        </div>
                        <div x-show="tingkatUnit === 'kampus'" x-cloak>
                            <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                <span class="font-bold">Universitas:</span> <span x-text="kampusName"></span>
                            </div>
                        </div>
                        <div x-show="tingkatUnit === 'fakultas'" x-cloak>
                            <select x-model="unitPemilikId" x-bind:required="tingkatUnit === 'fakultas'"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Fakultas --</option>
                                <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                    <option :value="fakultas.id" x-text="fakultas.name"></option>
                                </template>
                            </select>
                        </div>
                        <div x-show="tingkatUnit === 'prodi'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <select x-model="filterFakultas"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Semua Fakultas --</option>
                                <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                    <option :value="fakultas.id" x-text="fakultas.name"></option>
                                </template>
                            </select>
                            <select x-model="unitPemilikId" x-bind:required="tingkatUnit === 'prodi'"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Prodi --</option>
                                <template x-for="prodi in listProdi.filter(p => filterFakultas === '' || p.parent === filterFakultas)" :key="prodi.id">
                                    <option :value="prodi.id" x-text="prodi.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2">
                    <button type="button" @click="cancelEdit()"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-blue-700 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</template>

<script>
    function openInfrastructureDetailModal() {
        return {
            openDetail: false,
            editMode: false,
            canDelete: false,
            url: '',
            deleteUrl: '',
            itemName: '',
            editSnapshot: null,
            tingkatUnit: '',
            filterFakultas: '',
            unitPemilikId: '',
            kampusId: @js($kampusId),
            kampusName: @js($kampusName),
            listFakultas: @json($listFakultasArr),
            listProdi: @json($listProdiArr),
            itemData: {
                id: '',
                item_name: '',
                inventory_type: '',
                type_description: '',
                brand: '',
                unit_measure: '',
                stock: 0,
                price: 0,
                price_formatted: '0',
                status: '1',
                unit_id: '',
                unit_name: '',
                description: '',
                photo_url: null,
                loan_count: 0,
            },

            init() {
                this.$watch('tingkatUnit', value => {
                    if (!this.editMode) return;
                    this.filterFakultas = '';
                    this.unitPemilikId = value === 'kampus' ? this.kampusId : (value === '' ? '' : this.unitPemilikId);
                    if (value === '' || value === 'kampus') {
                        if (value === 'kampus') this.unitPemilikId = this.kampusId;
                        if (value === '') this.unitPemilikId = '';
                    }
                });
            },

            handleOpenDetail(event) {
                this.openDetail = true;
                this.editMode = false;
                this.url = event.detail.url;
                this.deleteUrl = event.detail.deleteUrl;
                this.itemName = event.detail.itemName;
                this.canDelete = event.detail.canDelete ?? false;
                this.editSnapshot = null;
                this.itemData = { ...event.detail.itemData };
                this.initUnitFromId(this.itemData.unit_id);
            },

            initUnitFromId(unitId) {
                this.tingkatUnit = '';
                this.filterFakultas = '';
                this.unitPemilikId = '';

                if (!unitId) return;

                if (unitId === this.kampusId) {
                    this.tingkatUnit = 'kampus';
                    this.unitPemilikId = unitId;
                    return;
                }

                if (this.listFakultas.find(f => f.id === unitId)) {
                    this.tingkatUnit = 'fakultas';
                    this.unitPemilikId = unitId;
                    return;
                }

                const prodi = this.listProdi.find(p => p.id === unitId);
                if (prodi) {
                    this.tingkatUnit = 'prodi';
                    this.filterFakultas = prodi.parent;
                    this.unitPemilikId = unitId;
                }
            },

            enterEditMode() {
                this.editSnapshot = JSON.parse(JSON.stringify({
                    itemData: this.itemData,
                    tingkatUnit: this.tingkatUnit,
                    filterFakultas: this.filterFakultas,
                    unitPemilikId: this.unitPemilikId,
                }));
                this.initUnitFromId(this.itemData.unit_id);
                this.editMode = true;
            },

            cancelEdit() {
                if (this.editSnapshot) {
                    this.itemData = JSON.parse(JSON.stringify(this.editSnapshot.itemData));
                    this.tingkatUnit = this.editSnapshot.tingkatUnit;
                    this.filterFakultas = this.editSnapshot.filterFakultas;
                    this.unitPemilikId = this.editSnapshot.unitPemilikId;
                }
                this.editMode = false;
                this.editSnapshot = null;
            },

            previewPhoto() {
                if (!this.itemData.photo_url) return;
                window.dispatchEvent(new CustomEvent('open-image-from-detail', {
                    bubbles: true,
                    detail: { url: this.itemData.photo_url, title: this.itemData.item_name },
                }));
            },

            confirmDelete() {
                window.dispatchEvent(new CustomEvent('open-delete-modal', {
                    bubbles: true,
                    detail: { url: this.deleteUrl, name: this.itemName },
                }));
                this.openDetail = false;
            },
        };
    }
</script>