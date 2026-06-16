<template x-teleport="#modal-root">
<div x-data="unitDetailModal()" @open-detail-modal.window="handleOpenDetail($event)"
    @cpl-deleted.window="handleCplDeleted($event)" @cpl-delete-failed.window="handleCplDeleteFailed($event)"
    @confirm-modal-opened.window="confirmModalOpen = true" @confirm-modal-closed.window="confirmModalOpen = false"
    x-show="openDetail"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div @click.away="closeDetailIfAllowed()"
        class="unit-detail-modal relative w-full max-w-2xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl transition-all overflow-hidden">

        {{-- HEADER --}}
        <div class="unit-detail-modal__header shrink-0 flex items-center justify-between border-b px-4 sm:px-8 py-3 sm:py-4 z-20 sticky top-0">
            <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                    <i class="fa-solid fa-building text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                </div>
                <div class="min-w-0 leading-tight">
                    <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl"
                        x-text="editMode ? 'Edit Unit' : 'Detail Unit'"></h3>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="unitData.name"></p>
                </div>
            </div>
            <div class="shrink-0 w-8 sm:w-9"></div>
        </div>

        {{-- MODE LIHAT --}}
        <div x-show="!editMode" class="flex flex-col flex-1 overflow-hidden">
            <div class="unit-detail-modal__scroll flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 custom-scrollbar">
                <div class="unit-detail-modal__card rounded-xl border">
                    <div class="unit-detail-modal__card-header px-5 py-3 border-b">
                        <h4 class="unit-detail-modal__card-title text-sm font-bold flex items-center gap-2">
                            <i class="fa-solid fa-building text-indigo-500"></i> Informasi Unit
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Kode Unit</div>
                            <div class="text-sm font-mono font-semibold text-indigo-700 dark:text-indigo-400" x-text="unitData.id"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Nama Unit</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="unitData.name"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Tipe Unit</div>
                            <span class="inline-flex rounded-md bg-purple-50 px-2.5 py-0.5 text-xs font-medium text-purple-700 border border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800"
                                x-text="unitData.type_name"></span>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Status</div>
                            <span x-show="unitData.active"
                                class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span> Aktif
                            </span>
                            <span x-show="!unitData.active"
                                class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span> Nonaktif
                            </span>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Induk Unit</div>
                            <template x-if="unitData.parent">
                                <div>
                                    <span class="text-xs font-mono font-semibold text-indigo-600 dark:text-indigo-400" x-text="unitData.parent"></span>
                                    <span class="text-sm text-gray-600 dark:text-gray-300" x-show="unitData.parent_name">
                                        — <span x-text="unitData.parent_name"></span>
                                    </span>
                                </div>
                            </template>
                            <span x-show="!unitData.parent" class="text-sm italic text-emerald-600 dark:text-emerald-400">
                                Pusat / Universitas (tidak ada induk)
                            </span>
                        </div>
                    </div>
                </div>

                <div class="unit-detail-modal__card rounded-xl border">
                    <div class="unit-detail-modal__card-header px-5 py-3 border-b">
                        <h4 class="unit-detail-modal__card-title text-sm font-bold flex items-center gap-2">
                            <i class="fa-solid fa-sitemap text-indigo-500"></i> Relasi & Penggunaan
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Unit Turunan</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                <span x-text="unitData.child_count"></span> unit
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Pengguna Terkait</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                <span x-text="unitData.user_count"></span> user
                            </div>
                        </div>
                        <div x-show="unitData.type == '3'">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">CPL</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                <span x-text="unitData.cpl_count"></span> data
                            </div>
                        </div>
                    </div>
                    <p class="px-5 pb-5 text-xs text-gray-500 dark:text-gray-400" x-show="!canDelete">
                        Unit tidak dapat dihapus karena masih memiliki unit turunan atau pengguna terkait.
                    </p>
                </div>

                <div x-show="unitData.type == '3'"
                    data-cpl-ui="v2"
                    class="cpl-section rounded-xl border">
                    <div class="cpl-section-header px-5 py-3 border-b">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="cpl-section-title text-sm font-bold flex items-center gap-2">
                                <i class="fa-solid fa-graduation-cap text-indigo-500"></i> CPL Program Studi
                            </h4>
                            <button type="button" @click="showCplForm = !showCplForm"
                                class="cpl-btn-add inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold border transition shadow-sm">
                                <i class="fa-solid fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>

                    <div class="cpl-section-body p-5 space-y-4">
                        <div x-show="!cplLoading && cplList.length > 0" x-cloak
                            class="cpl-bulk-toolbar flex flex-wrap items-center gap-2 sm:gap-3 rounded-xl border border-dashed px-3 py-2.5">
                            <label class="cpl-bulk-label inline-flex cursor-pointer items-center gap-2.5 text-xs font-semibold">
                                <input type="checkbox"
                                    class="cpl-checkbox h-4 w-4 shrink-0 rounded"
                                    :checked="allDeletableCplSelected()"
                                    @change="toggleSelectAllCpl($event.target.checked)">
                                <span>Pilih semua yang dapat dihapus</span>
                            </label>
                            <span x-show="cplSelectedIds.length > 0" x-cloak
                                class="cpl-badge-count inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold border">
                                <span x-text="cplSelectedIds.length"></span>
                                <span>terpilih</span>
                            </span>
                            <button type="button" x-show="cplSelectedIds.length > 0" x-cloak @click="requestBulkDeleteCpl()"
                                class="cpl-btn-delete ml-auto inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold border transition shadow-sm">
                                <i class="fa-solid fa-trash-alt"></i>
                                Hapus Terpilih
                            </button>
                        </div>
                        <div x-show="cplAlert.message" x-cloak
                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold"
                            :class="cplAlert.type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-green-50 text-green-700 border border-green-200'">
                            <i class="fa-solid" :class="cplAlert.type === 'error' ? 'fa-circle-xmark' : 'fa-check-circle'"></i>
                            <span x-text="cplAlert.message"></span>
                        </div>

                        <div x-show="showCplForm" x-cloak class="cpl-form-panel rounded-xl border p-4">
                            <div class="grid grid-cols-1 gap-3">
                                <div>
                                    <label class="cpl-form-label mb-1.5 block text-[10px] font-bold uppercase tracking-widest">
                                        Deskripsi CPL <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="cplForm.name" maxlength="255"
                                        class="cpl-input w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500"
                                        placeholder="Contoh: Mampu merancang solusi berbasis teknologi informasi">
                                </div>
                                <div>
                                    <label class="cpl-form-label mb-1.5 block text-[10px] font-bold uppercase tracking-widest">Status</label>
                                    <select x-model="cplForm.is_active"
                                        class="cpl-input w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-end gap-2">
                                <button type="button" @click="cancelCplForm()"
                                    class="cpl-btn-cancel rounded-lg px-4 py-2 text-xs font-bold">
                                    Batal
                                </button>
                                <button type="button" @click="saveCpl()" :disabled="cplSaving"
                                    class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 disabled:opacity-60">
                                    <span x-text="cplSaving ? 'Menyimpan...' : (cplFormMode === 'edit' ? 'Simpan Perubahan' : 'Simpan CPL')"></span>
                                </button>
                            </div>
                        </div>

                        <div x-show="cplLoading" class="cpl-loading py-6 text-center text-sm">
                            <i class="fa-solid fa-circle-notch fa-spin text-indigo-600"></i> Memuat CPL...
                        </div>

                        <div x-show="!cplLoading && cplList.length === 0" class="cpl-empty rounded-xl border border-dashed px-4 py-8 text-center">
                            <i class="fa-solid fa-graduation-cap text-2xl mb-2"></i>
                            <p class="cpl-empty-title text-sm font-medium">Belum ada CPL untuk prodi ini.</p>
                            <p class="cpl-empty-subtitle text-xs mt-1">Tambahkan CPL sebagai capaian pembelajaran lulusan prodi.</p>
                        </div>

                        <div x-show="!cplLoading && cplList.length > 0" class="space-y-2">
                            <template x-for="cpl in cplList" :key="cpl.id">
                                <div class="cpl-list-item flex items-start gap-3 rounded-xl border px-3 py-3 transition-colors sm:px-4"
                                    :class="cplSelectedIds.includes(cpl.id) ? 'is-selected' : ''">
                                    <div class="flex shrink-0 items-center pt-1">
                                        <input type="checkbox"
                                            :checked="cplSelectedIds.includes(cpl.id)"
                                            @change="toggleCplSelection(cpl.id, $event.target.checked)"
                                            :disabled="!cpl.can_delete"
                                            class="cpl-checkbox h-4 w-4 shrink-0 rounded disabled:opacity-40 disabled:cursor-not-allowed"
                                            :title="cpl.can_delete ? 'Pilih CPL' : 'CPL masih dipetakan ke CPMK'">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="cpl-id-badge inline-flex rounded-md px-2 py-0.5 text-xs font-bold font-mono" x-text="cpl.id"></span>
                                            <span x-show="cpl.is_active == '1'"
                                                class="cpl-badge-active inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold border">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span> Aktif
                                            </span>
                                            <span x-show="cpl.is_active != '1'"
                                                class="cpl-badge-inactive inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold border">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span> Nonaktif
                                            </span>
                                        </div>
                                        <p class="cpl-item-text text-sm leading-snug" x-text="cpl.name"></p>
                                    </div>
                                    <div class="flex shrink-0 items-center gap-0.5 self-center">
                                        <button type="button" @click="editCpl(cpl)"
                                            class="cpl-btn-icon cpl-btn-icon-edit rounded-lg p-2" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button type="button" @click="requestDeleteCpl(cpl)" :disabled="!cpl.can_delete"
                                            class="cpl-btn-icon cpl-btn-icon-delete rounded-lg p-2 disabled:opacity-40" title="Hapus">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="unit-detail-modal__footer shrink-0 border-t px-4 sm:px-6 py-4 z-20 sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2 sm:gap-4">
                    <button type="button" @click="openDetail = false"
                        class="unit-detail-modal__btn-secondary inline-flex shrink-0 items-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold sm:px-6 sm:py-2.5 sm:text-sm transition-all">
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
        <form x-show="editMode" :action="url" method="POST" class="flex flex-col flex-1 overflow-hidden m-0">
            @csrf
            @method('PUT')
            <div class="unit-detail-modal__scroll flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 custom-scrollbar">
                <div class="unit-detail-modal__card rounded-xl border">
                    <div class="unit-detail-modal__card-header px-5 py-3 border-b">
                        <h4 class="unit-detail-modal__card-title text-sm font-bold flex items-center gap-2">
                            <i class="fa-solid fa-building text-indigo-500"></i> Informasi Unit
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Kode Unit</label>
                            <input type="text" readonly x-model="unitData.id"
                                class="w-full rounded-xl border-gray-300 bg-gray-100 px-4 py-2.5 text-sm font-mono text-gray-500 cursor-not-allowed outline-none dark:bg-[#0f172a]/50 dark:text-gray-400 dark:border-gray-600">
                            <p class="mt-1.5 text-xs text-gray-500">ID unit bersifat unik dan tidak dapat diubah.</p>
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Nama Unit <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="unit_name" required maxlength="100" x-model="unitData.name"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Tipe Unit <span class="text-red-500">*</span>
                            </label>
                            <select name="unit_type_id" x-model="unitData.type" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Level Unit --</option>
                                @foreach($unitTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->description ?? 'Tipe ' . $type->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Induk Unit (Opsional)</label>
                            <select name="unit_parent" x-model="unitData.parent"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Tidak Ada (Sebagai Induk Tertinggi) --</option>
                                @foreach($parentUnits as $parent)
                                    <option value="{{ $parent->id }}" x-bind:disabled="'{{ $parent->id }}' === unitData.id">
                                        {{ $parent->id }} - {{ $parent->unit_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="relative inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" name="is_active" value="1" class="peer sr-only" x-model="unitData.active">
                                <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-gray-700"></div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Unit Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="unit-detail-modal__footer shrink-0 border-t px-4 sm:px-6 py-4 z-20 sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2">
                    <button type="button" @click="cancelEdit()"
                        class="unit-detail-modal__btn-secondary inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold sm:px-6 sm:py-2.5 sm:text-sm transition-all">
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
    document.addEventListener('alpine:init', () => {
        Alpine.data('unitDetailModal', () => ({
            openDetail: false,
            confirmModalOpen: false,
            editMode: false,
            canDelete: false,
            url: '',
            deleteUrl: '',
            unitName: '',
            editSnapshot: null,
            unitData: {
                id: '',
                name: '',
                type: '',
                parent: '',
                active: true,
                type_name: '',
                parent_name: null,
                child_count: 0,
                user_count: 0,
                cpl_count: 0,
                cpl_api_url: '',
                cpl_store_url: '',
                cpl_bulk_destroy_url: '',
            },
            cplList: [],
            cplSelectedIds: [],
            cplLoading: false,
            cplSaving: false,
            showCplForm: false,
            cplFormMode: 'create',
            cplForm: { id: '', name: '', is_active: '1', update_url: '' },
            cplAlert: { type: '', message: '' },

            deletableCplCount() {
                return this.cplList.filter(cpl => cpl.can_delete).length;
            },

            allDeletableCplSelected() {
                const ids = this.cplList.filter(cpl => cpl.can_delete).map(cpl => cpl.id);
                return ids.length > 0 && ids.every(id => this.cplSelectedIds.includes(id));
            },

            closeDetailIfAllowed() {
                if (!this.confirmModalOpen) {
                    this.openDetail = false;
                }
            },

            handleOpenDetail(event) {
                this.openDetail = true;
                this.confirmModalOpen = false;
                this.editMode = false;
                this.url = event.detail.url;
                this.deleteUrl = event.detail.deleteUrl;
                this.unitName = event.detail.unitName;
                this.canDelete = event.detail.canDelete ?? false;
                this.editSnapshot = null;
                this.unitData = { ...event.detail.unitData };
                this.resetCplForm();
                this.cplSelectedIds = [];
                if (this.unitData.type == '3') {
                    this.fetchCplList();
                }
            },

            async fetchCplList() {
                if (!this.unitData.cpl_api_url) {
                    return;
                }

                this.cplLoading = true;
                this.cplList = [];
                this.cplSelectedIds = [];

                try {
                    const response = await fetch(this.unitData.cpl_api_url, {
                        headers: { 'Accept': 'application/json' },
                    });

                    if (response.ok) {
                        this.cplList = await response.json();
                        this.unitData.cpl_count = this.cplList.length;
                        this.cplSelectedIds = this.cplSelectedIds.filter(id => this.cplList.some(cpl => cpl.id === id));
                    }
                } catch (error) {
                    console.error('Gagal memuat CPL', error);
                } finally {
                    this.cplLoading = false;
                }
            },

            resetCplForm() {
                this.showCplForm = false;
                this.cplFormMode = 'create';
                this.cplForm = { id: '', name: '', is_active: '1', update_url: '' };
                this.cplAlert = { type: '', message: '' };
            },

            toggleSelectAllCpl(checked) {
                if (checked) {
                    this.cplSelectedIds = this.cplList
                        .filter(cpl => cpl.can_delete)
                        .map(cpl => cpl.id);
                    return;
                }

                this.cplSelectedIds = [];
            },

            toggleCplSelection(id, checked) {
                if (checked) {
                    if (!this.cplSelectedIds.includes(id)) {
                        this.cplSelectedIds = [...this.cplSelectedIds, id];
                    }
                    return;
                }

                this.cplSelectedIds = this.cplSelectedIds.filter(item => item !== id);
            },

            cancelCplForm() {
                this.resetCplForm();
            },

            editCpl(cpl) {
                this.cplFormMode = 'edit';
                this.showCplForm = true;
                this.cplForm = {
                    id: cpl.id,
                    name: cpl.name,
                    is_active: cpl.is_active,
                    update_url: cpl.update_url,
                };
            },

            flashCpl(type, message) {
                this.cplAlert = { type, message };
                setTimeout(() => { this.cplAlert.message = ''; }, 3500);
            },

            async saveCpl() {
                if (!this.cplForm.name.trim()) {
                    this.flashCpl('error', 'Deskripsi CPL wajib diisi.');
                    return;
                }

                this.cplSaving = true;
                const isEdit = this.cplFormMode === 'edit';
                const url = isEdit ? this.cplForm.update_url : this.unitData.cpl_store_url;
                const method = isEdit ? 'PUT' : 'POST';

                try {
                    const response = await fetch(url, {
                        method,
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({
                            name: this.cplForm.name.trim(),
                            is_active: this.cplForm.is_active,
                        }),
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        this.flashCpl('error', result.message || 'Gagal menyimpan CPL.');
                        return;
                    }

                    this.flashCpl('success', result.message || 'CPL berhasil disimpan.');
                    this.resetCplForm();
                    await this.fetchCplList();
                } catch (error) {
                    console.error('Gagal menyimpan CPL', error);
                    this.flashCpl('error', 'Terjadi kesalahan saat menyimpan CPL.');
                } finally {
                    this.cplSaving = false;
                }
            },

            requestDeleteCpl(cpl) {
                if (!cpl.can_delete) {
                    this.flashCpl('error', 'CPL tidak dapat dihapus karena masih dipetakan ke CPMK.');
                    return;
                }

                window.dispatchEvent(new CustomEvent('open-cpl-delete-modal', {
                    bubbles: true,
                    detail: {
                        mode: 'single',
                        items: [{ id: cpl.id, name: cpl.name }],
                        deleteUrl: cpl.delete_url,
                        bulkUrl: this.unitData.cpl_bulk_destroy_url,
                    },
                }));
            },

            requestBulkDeleteCpl() {
                if (this.cplSelectedIds.length === 0) {
                    return;
                }

                const items = this.cplList
                    .filter(cpl => this.cplSelectedIds.includes(cpl.id) && cpl.can_delete)
                    .map(cpl => ({ id: cpl.id, name: cpl.name }));

                if (items.length === 0) {
                    this.flashCpl('error', 'Tidak ada CPL terpilih yang dapat dihapus.');
                    return;
                }

                if (!this.unitData.cpl_bulk_destroy_url) {
                    this.flashCpl('error', 'URL hapus bulk CPL tidak tersedia. Muat ulang halaman.');
                    return;
                }

                window.dispatchEvent(new CustomEvent('open-cpl-delete-modal', {
                    bubbles: true,
                    detail: {
                        mode: 'bulk',
                        items,
                        bulkUrl: this.unitData.cpl_bulk_destroy_url,
                    },
                }));
            },

            async handleCplDeleted(event) {
                const deletedIds = event.detail?.deletedIds || [];

                if (deletedIds.length > 0) {
                    this.cplList = this.cplList.filter(cpl => !deletedIds.includes(cpl.id));
                    this.unitData.cpl_count = this.cplList.length;
                    this.cplSelectedIds = this.cplSelectedIds.filter(id => !deletedIds.includes(id));
                } else {
                    this.cplSelectedIds = [];
                }

                this.flashCpl('success', event.detail?.message || 'CPL berhasil dihapus.');
                await this.fetchCplList();
            },

            handleCplDeleteFailed(event) {
                this.flashCpl('error', event.detail?.message || 'Gagal menghapus CPL.');
            },

            enterEditMode() {
                this.editSnapshot = JSON.parse(JSON.stringify(this.unitData));
                this.editMode = true;
            },

            cancelEdit() {
                if (this.editSnapshot) {
                    this.unitData = JSON.parse(JSON.stringify(this.editSnapshot));
                }
                this.editMode = false;
                this.editSnapshot = null;
            },

            confirmDelete() {
                window.dispatchEvent(new CustomEvent('open-delete-modal', {
                    bubbles: true,
                    detail: {
                        url: this.deleteUrl,
                        name: this.unitName,
                    },
                }));
            },
        }));
    });
</script>