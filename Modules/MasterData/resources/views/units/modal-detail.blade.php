<template x-teleport="#modal-root">
<div x-data="openUnitDetailModal()" @open-detail-modal.window="handleOpenDetail($event)" x-show="openDetail"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div @click.away="openDetail = false"
        class="relative w-full max-w-2xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

        {{-- HEADER --}}
        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
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
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
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

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
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

                <div x-show="unitData.type == '3'" class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700 space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-graduation-cap text-indigo-500"></i> CPL Program Studi
                            </h4>
                            <div class="flex items-center gap-2">
                                <button type="button" x-show="cplSelectedIds.length > 0" x-cloak @click="confirmBulkDeleteCpl()"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 border border-red-200 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800 transition">
                                    <i class="fa-solid fa-trash-alt"></i>
                                    Hapus Terpilih (<span x-text="cplSelectedIds.length"></span>)
                                </button>
                                <button type="button" @click="showCplForm = !showCplForm"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 border border-indigo-200 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800 transition">
                                    <i class="fa-solid fa-plus"></i> Tambah
                                </button>
                            </div>
                        </div>
                        <label x-show="!cplLoading && deletableCplCount > 0" x-cloak
                            class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                            <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-[#0f172a]"
                                :checked="allDeletableCplSelected"
                                @change="toggleSelectAllCpl($event.target.checked)">
                            Pilih semua yang dapat dihapus
                        </label>
                    </div>

                    <div class="p-5 space-y-4">
                        <div x-show="cplAlert.message" x-cloak
                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold"
                            :class="cplAlert.type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-green-50 text-green-700 border border-green-200'">
                            <i class="fa-solid" :class="cplAlert.type === 'error' ? 'fa-circle-xmark' : 'fa-check-circle'"></i>
                            <span x-text="cplAlert.message"></span>
                        </div>

                        <div x-show="showCplForm" x-cloak class="rounded-xl border border-indigo-100 bg-indigo-50/40 p-4 dark:border-indigo-900/40 dark:bg-indigo-900/10">
                            <div class="grid grid-cols-1 gap-3">
                                <div>
                                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                        Deskripsi CPL <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="cplForm.name" maxlength="255"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                        placeholder="Contoh: Mampu merancang solusi berbasis teknologi informasi">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</label>
                                    <select x-model="cplForm.is_active"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-end gap-2">
                                <button type="button" @click="cancelCplForm()"
                                    class="rounded-lg bg-gray-200 px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">
                                    Batal
                                </button>
                                <button type="button" @click="saveCpl()" :disabled="cplSaving"
                                    class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 disabled:opacity-60">
                                    <span x-text="cplSaving ? 'Menyimpan...' : (cplFormMode === 'edit' ? 'Simpan Perubahan' : 'Simpan CPL')"></span>
                                </button>
                            </div>
                        </div>

                        <div x-show="cplLoading" class="py-6 text-center text-sm text-gray-500">
                            <i class="fa-solid fa-circle-notch fa-spin text-indigo-600"></i> Memuat CPL...
                        </div>

                        <div x-show="!cplLoading && cplList.length === 0" class="rounded-xl border border-dashed border-gray-200 px-4 py-8 text-center dark:border-gray-700">
                            <i class="fa-solid fa-graduation-cap text-2xl text-gray-300 mb-2"></i>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum ada CPL untuk prodi ini.</p>
                            <p class="text-xs text-gray-400 mt-1">Tambahkan CPL sebagai capaian pembelajaran lulusan prodi.</p>
                        </div>

                        <div x-show="!cplLoading && cplList.length > 0" class="space-y-2">
                            <template x-for="cpl in cplList" :key="cpl.id">
                                <div class="flex items-start justify-between gap-3 rounded-xl border border-gray-200 bg-slate-50/60 px-4 py-3 dark:border-gray-700 dark:bg-[#0f172a]/40"
                                    :class="cplSelectedIds.includes(cpl.id) ? 'ring-2 ring-indigo-200 dark:ring-indigo-800' : ''">
                                    <div class="flex shrink-0 items-start pt-0.5">
                                        <input type="checkbox" :value="cpl.id" x-model="cplSelectedIds" :disabled="!cpl.can_delete"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 disabled:opacity-40 dark:border-gray-600 dark:bg-[#0f172a]"
                                            :title="cpl.can_delete ? 'Pilih CPL' : 'CPL masih dipetakan ke CPMK'">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="inline-flex rounded-md bg-teal-100 px-2 py-0.5 text-xs font-bold font-mono text-teal-700 dark:bg-teal-900/40 dark:text-teal-300" x-text="cpl.id"></span>
                                            <span x-show="cpl.is_active == '1'"
                                                class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-[10px] font-bold text-green-700 border border-green-200">Aktif</span>
                                            <span x-show="cpl.is_active != '1'"
                                                class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-bold text-red-700 border border-red-200">Nonaktif</span>
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-snug" x-text="cpl.name"></p>
                                    </div>
                                    <div class="flex shrink-0 items-center gap-1">
                                        <button type="button" @click="editCpl(cpl)"
                                            class="rounded-lg p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button type="button" @click="confirmDeleteCpl(cpl)" :disabled="!cpl.can_delete"
                                            class="rounded-lg p-2 text-red-600 hover:bg-red-50 disabled:opacity-40 dark:hover:bg-red-900/20" title="Hapus">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sticky bottom-0 backdrop-blur">
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
        <form x-show="editMode" :action="url" method="POST" class="flex flex-col flex-1 overflow-hidden m-0">
            @csrf
            @method('PUT')
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
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

    {{-- Modal konfirmasi hapus CPL --}}
    <div x-show="cplDeleteModal.open" x-cloak
        class="fixed inset-0 z-[10000001] flex items-center justify-center p-3 sm:p-6 overflow-y-auto backdrop-blur-sm bg-gray-900/50"
        x-transition:enter="transition ease-out duration-300" x-transition:opacity>
        <div @click.away="closeCplDeleteModal()"
            class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 overflow-hidden">
            <div class="border-b border-gray-200 bg-white px-4 py-3 sm:px-6 sm:py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-red-100 bg-red-50 dark:border-red-800/50 dark:bg-red-900/30">
                        <i class="fa-solid fa-triangle-exclamation text-sm text-red-600 dark:text-red-400 sm:text-base"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-lg">Konfirmasi Hapus CPL</h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm"
                            x-text="cplDeleteModal.mode === 'bulk' ? (cplDeleteModal.items.length + ' CPL terpilih') : cplDeleteModal.items[0]?.id"></p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">
                <p class="text-sm text-gray-600 dark:text-gray-300" x-show="cplDeleteModal.mode === 'single'">
                    Apakah Anda yakin ingin menghapus CPL
                    <span class="font-bold font-mono text-gray-900 dark:text-white" x-text="cplDeleteModal.items[0]?.id"></span>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div x-show="cplDeleteModal.mode === 'bulk'" class="space-y-3">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Apakah Anda yakin ingin menghapus
                        <span class="font-bold text-gray-900 dark:text-white" x-text="cplDeleteModal.items.length"></span>
                        CPL terpilih? Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="max-h-32 overflow-y-auto rounded-lg border border-gray-200 bg-white px-3 py-2 dark:border-gray-700 dark:bg-[#1e293b] custom-scrollbar">
                        <template x-for="item in cplDeleteModal.items" :key="item.id">
                            <div class="py-1 text-xs text-gray-600 dark:text-gray-300">
                                <span class="font-bold font-mono text-teal-700 dark:text-teal-300" x-text="item.id"></span>
                                <span x-show="item.name"> — <span x-text="item.name"></span></span>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="flex flex-row flex-nowrap items-center justify-end gap-2 pt-2">
                    <button type="button" @click="closeCplDeleteModal()" :disabled="cplDeleteModal.deleting"
                        class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 disabled:opacity-60 dark:bg-gray-700 dark:text-gray-200 sm:px-5 sm:py-2.5 sm:text-sm transition">
                        Batal
                    </button>
                    <button type="button" @click="executeCplDelete()" :disabled="cplDeleteModal.deleting"
                        class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-red-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-red-700 disabled:opacity-60 sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition">
                        <i class="fas" :class="cplDeleteModal.deleting ? 'fa-circle-notch fa-spin' : 'fa-trash-alt'"></i>
                        <span x-text="cplDeleteModal.deleting ? 'Menghapus...' : 'Hapus'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    function openUnitDetailModal() {
        return {
            openDetail: false,
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
            cplDeleteModal: { open: false, mode: 'single', items: [], deleting: false },

            get deletableCplCount() {
                return this.cplList.filter(cpl => cpl.can_delete).length;
            },

            get allDeletableCplSelected() {
                const ids = this.cplList.filter(cpl => cpl.can_delete).map(cpl => cpl.id);
                return ids.length > 0 && ids.every(id => this.cplSelectedIds.includes(id));
            },

            handleOpenDetail(event) {
                this.openDetail = true;
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
                this.closeCplDeleteModal();
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

            confirmDeleteCpl(cpl) {
                if (!cpl.can_delete) {
                    this.flashCpl('error', 'CPL tidak dapat dihapus karena masih dipetakan ke CPMK.');
                    return;
                }

                this.cplDeleteModal = {
                    open: true,
                    mode: 'single',
                    items: [{ id: cpl.id, name: cpl.name, delete_url: cpl.delete_url }],
                    deleting: false,
                };
            },

            confirmBulkDeleteCpl() {
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

                this.cplDeleteModal = {
                    open: true,
                    mode: 'bulk',
                    items,
                    deleting: false,
                };
            },

            closeCplDeleteModal() {
                this.cplDeleteModal = { open: false, mode: 'single', items: [], deleting: false };
            },

            async executeCplDelete() {
                if (this.cplDeleteModal.deleting || this.cplDeleteModal.items.length === 0) {
                    return;
                }

                this.cplDeleteModal.deleting = true;

                try {
                    let response;
                    let result;

                    if (this.cplDeleteModal.mode === 'bulk') {
                        response = await fetch(this.unitData.cpl_bulk_destroy_url, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify({
                                ids: this.cplDeleteModal.items.map(item => item.id),
                            }),
                        });
                    } else {
                        const item = this.cplDeleteModal.items[0];
                        response = await fetch(item.delete_url, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        });
                    }

                    result = await response.json();

                    if (!response.ok) {
                        this.flashCpl('error', result.message || 'Gagal menghapus CPL.');
                        return;
                    }

                    this.flashCpl('success', result.message || 'CPL berhasil dihapus.');
                    this.closeCplDeleteModal();
                    this.cplSelectedIds = [];
                    await this.fetchCplList();
                } catch (error) {
                    console.error('Gagal menghapus CPL', error);
                    this.flashCpl('error', 'Terjadi kesalahan saat menghapus CPL.');
                } finally {
                    this.cplDeleteModal.deleting = false;
                }
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
                this.openDetail = false;
            },
        };
    }
</script>