<template x-teleport="#modal-root">
<div x-data="openCourseDetailModal()" @open-detail-modal.window="handleOpenDetail($event)"
    @cpmk-deleted.window="handleCpmkDeleted($event)" @cpmk-delete-failed.window="handleCpmkDeleteFailed($event)"
    @confirm-modal-opened.window="confirmModalOpen = true"
    @confirm-modal-closed.window="confirmModalOpen = false; suppressDetailCloseUntil = Date.now() + 400"
    x-show="openDetail"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div @click.away="closeDetailIfAllowed()"
        class="course-detail-modal relative w-full max-w-2xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl transition-all overflow-hidden">

        {{-- HEADER --}}
        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
            <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                    <i class="fa-solid fa-book-open text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                </div>
                <div class="min-w-0 leading-tight">
                    <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl"
                        x-text="editMode ? 'Edit Mata Kuliah' : 'Detail Mata Kuliah'"></h3>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="courseData.name"></p>
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
                            <i class="fa-solid fa-book-open text-indigo-500"></i> Informasi Mata Kuliah
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Kode MK</div>
                            <div class="text-sm font-mono font-semibold text-indigo-700 dark:text-indigo-400" x-text="courseData.id"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Nama Mata Kuliah</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="courseData.name"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Fakultas</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="courseData.fakultas_name || '-'"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Prodi Pengampu</div>
                            <span class="inline-flex rounded-md bg-purple-50 px-2.5 py-0.5 text-xs font-medium text-purple-700 border border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800"
                                x-text="courseData.prodi_name || 'UNIVERSAL'"></span>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Status</div>
                            <span x-show="courseData.active"
                                class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span> Aktif
                            </span>
                            <span x-show="!courseData.active"
                                class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span> Nonaktif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-link text-indigo-500"></i> Relasi Data
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-5">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Pengajuan Soal</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                <span x-text="courseData.proposal_count"></span> data
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Bank Soal</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                <span x-text="courseData.question_count"></span> soal
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">CPMK</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                <span x-text="courseData.cpmk_count"></span> data
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Pemetaan CPL</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                <span x-text="courseData.mapping_count"></span> data
                            </div>
                        </div>
                    </div>
                    <p class="px-5 pb-5 text-xs text-gray-500 dark:text-gray-400" x-show="!canDelete">
                        Mata kuliah tidak dapat dihapus karena masih terhubung dengan pengajuan soal atau bank soal.
                    </p>
                </div>

                <div data-cpmk-ui="v1" class="cpmk-section rounded-xl border">
                    <div class="cpmk-section-header px-5 py-3 border-b flex items-center justify-between gap-3">
                        <h4 class="cpmk-section-title text-sm font-bold flex items-center gap-2">
                            <i class="fa-solid fa-bullseye text-indigo-500"></i> CPMK Mata Kuliah
                        </h4>
                        <button type="button" @click="showCpmkForm = !showCpmkForm"
                            class="cpmk-btn-add inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold border transition">
                            <i class="fa-solid fa-plus"></i> Tambah
                        </button>
                    </div>

                    <div class="cpmk-section-body p-5 space-y-4">
                        <div x-show="!cpmkLoading && cpmkList.length > 0" x-cloak
                            class="cpmk-bulk-toolbar flex flex-wrap items-center gap-2 sm:gap-3 rounded-xl border border-dashed px-3 py-2.5">
                            <label class="cpmk-bulk-label inline-flex cursor-pointer items-center gap-2.5 text-xs font-semibold">
                                <input type="checkbox"
                                    class="cpmk-checkbox h-4 w-4 shrink-0 rounded"
                                    :checked="allDeletableCpmkSelected()"
                                    @change="toggleSelectAllCpmk($event.target.checked)">
                                <span>Pilih semua yang dapat dihapus</span>
                            </label>
                            <span x-show="cpmkSelectedIds.length > 0" x-cloak
                                class="cpmk-badge-count inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold border">
                                <span x-text="cpmkSelectedIds.length"></span>
                                <span>terpilih</span>
                            </span>
                            <button type="button" x-show="cpmkSelectedIds.length > 0" x-cloak @click="requestBulkDeleteCpmk()"
                                class="cpmk-btn-delete ml-auto inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold border transition">
                                <i class="fa-solid fa-trash-alt"></i>
                                Hapus Terpilih
                            </button>
                        </div>

                        <div x-show="cpmkAlert.message" x-cloak
                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold"
                            :class="cpmkAlert.type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-green-50 text-green-700 border border-green-200'">
                            <i class="fa-solid" :class="cpmkAlert.type === 'error' ? 'fa-circle-xmark' : 'fa-check-circle'"></i>
                            <span x-text="cpmkAlert.message"></span>
                        </div>

                        <div x-show="showCpmkForm" x-cloak class="cpmk-form-panel rounded-xl border p-4">
                            <div class="grid grid-cols-1 gap-3">
                                <div>
                                    <label class="cpmk-form-label mb-1.5 block text-[10px] font-bold uppercase tracking-widest">
                                        Deskripsi CPMK <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="cpmkForm.name" maxlength="100"
                                        class="cpmk-input w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500"
                                        placeholder="Contoh: Memahami konsep dasar jaringan komputer">
                                </div>
                                <div>
                                    <label class="cpmk-form-label mb-1.5 block text-[10px] font-bold uppercase tracking-widest">Status</label>
                                    <select x-model="cpmkForm.is_active"
                                        class="cpmk-input w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-end gap-2">
                                <button type="button" @click="cancelCpmkForm()"
                                    class="cpmk-btn-cancel rounded-lg px-4 py-2 text-xs font-bold">
                                    Batal
                                </button>
                                <button type="button" @click="saveCpmk()" :disabled="cpmkSaving"
                                    class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 disabled:opacity-60">
                                    <span x-text="cpmkSaving ? 'Menyimpan...' : (cpmkFormMode === 'edit' ? 'Simpan Perubahan' : 'Simpan CPMK')"></span>
                                </button>
                            </div>
                        </div>

                        <div x-show="cpmkLoading" class="cpmk-loading py-6 text-center text-sm">
                            <i class="fa-solid fa-circle-notch fa-spin text-indigo-600"></i> Memuat CPMK...
                        </div>

                        <div x-show="!cpmkLoading && cpmkList.length === 0" class="cpmk-empty rounded-xl border border-dashed px-4 py-8 text-center">
                            <i class="fa-solid fa-bullseye text-2xl mb-2"></i>
                            <p class="cpmk-empty-title text-sm font-medium">Belum ada CPMK untuk mata kuliah ini.</p>
                            <p class="cpmk-empty-subtitle text-xs mt-1">Tambahkan CPMK agar dosen bisa memetakan butir soal.</p>
                        </div>

                        <div x-show="!cpmkLoading && cpmkList.length > 0" class="space-y-2">
                            <template x-for="cpmk in cpmkList" :key="cpmk.id">
                                <div class="cpmk-list-item flex items-start gap-3 rounded-xl border px-3 py-3 transition-colors sm:px-4"
                                    :class="cpmkSelectedIds.includes(cpmk.id) ? 'is-selected' : ''">
                                    <div class="flex shrink-0 items-center pt-1">
                                        <input type="checkbox"
                                            :checked="cpmkSelectedIds.includes(cpmk.id)"
                                            @change="toggleCpmkSelection(cpmk.id, $event.target.checked)"
                                            :disabled="!cpmk.can_delete"
                                            class="cpmk-checkbox h-4 w-4 shrink-0 rounded disabled:opacity-40 disabled:cursor-not-allowed"
                                            :title="cpmk.can_delete ? 'Pilih CPMK' : 'CPMK masih digunakan di soal atau pemetaan'">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="cpmk-id-badge inline-flex rounded-md px-2 py-0.5 text-xs font-bold font-mono" x-text="cpmk.id"></span>
                                            <span x-show="cpmk.is_active == '1'"
                                                class="cpmk-badge-active inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold border">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span> Aktif
                                            </span>
                                            <span x-show="cpmk.is_active != '1'"
                                                class="cpmk-badge-inactive inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold border">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span> Nonaktif
                                            </span>
                                        </div>
                                        <p class="cpmk-item-text text-sm leading-snug" x-text="cpmk.name"></p>
                                    </div>
                                    <div class="flex shrink-0 items-center gap-0.5 self-center">
                                        <button type="button" @click="editCpmk(cpmk)"
                                            class="cpmk-btn-icon cpmk-btn-icon-edit rounded-lg p-2" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button type="button"
                                            @click="openCpmkDeleteConfirm(cpmk)"
                                            :disabled="!cpmk.can_delete"
                                            class="cpmk-btn-icon cpmk-btn-icon-delete rounded-lg p-2 disabled:opacity-40" title="Hapus">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mapping-section rounded-xl border">
                    <div class="mapping-section-header px-5 py-3 border-b flex items-center justify-between gap-3">
                        <h4 class="mapping-section-title text-sm font-bold flex items-center gap-2">
                            <i class="fa-solid fa-diagram-project text-indigo-500"></i> Pemetaan CPL ↔ CPMK
                        </h4>
                        <button type="button" @click="saveMapping()" :disabled="mappingSaving || mappingLoading"
                            class="mapping-btn-save inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold transition disabled:opacity-60">
                            <i class="fa-solid fa-save"></i>
                            <span x-text="mappingSaving ? 'Menyimpan...' : 'Simpan Pemetaan'"></span>
                        </button>
                    </div>

                    <div class="mapping-section-body p-5 space-y-4">
                        <div x-show="mappingAlert.message" x-cloak
                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold"
                            :class="mappingAlert.type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-green-50 text-green-700 border border-green-200'">
                            <i class="fa-solid" :class="mappingAlert.type === 'error' ? 'fa-circle-xmark' : 'fa-check-circle'"></i>
                            <span x-text="mappingAlert.message"></span>
                        </div>

                        <div x-show="mappingLoading" class="mapping-loading py-6 text-center text-sm">
                            <i class="fa-solid fa-circle-notch fa-spin text-indigo-600"></i> Memuat pemetaan...
                        </div>

                        <div x-show="!mappingLoading && mappingCpmks.length === 0" class="mapping-empty rounded-xl border border-dashed px-4 py-8 text-center">
                            <i class="fa-solid fa-diagram-project text-2xl mb-2"></i>
                            <p class="mapping-empty-title text-sm font-medium">Tambahkan CPMK terlebih dahulu sebelum memetakan ke CPL.</p>
                        </div>

                        <div x-show="!mappingLoading && mappingCpmks.length > 0 && mappingCpls.length === 0" class="mapping-warn rounded-xl border border-dashed px-4 py-6 text-center">
                            <p class="mapping-warn-title text-sm font-medium">Belum ada CPL di prodi <span class="font-bold" x-text="courseData.prodi_name"></span>.</p>
                            <p class="mapping-warn-subtitle text-xs mt-1">Kelola CPL lewat Master Data → Unit → Detail Prodi.</p>
                        </div>

                        <div x-show="!mappingLoading && mappingCpmks.length > 0 && mappingCpls.length > 0" class="space-y-3">
                            <div class="mapping-legend hidden sm:flex items-center justify-between gap-2 rounded-lg border border-dashed px-3 py-2 text-[10px] font-bold uppercase tracking-widest">
                                <span>CPMK</span>
                                <span>CPL Prodi — centang untuk memetakan</span>
                            </div>
                            <template x-for="cpmk in mappingCpmks" :key="cpmk.id">
                                <div class="mapping-row rounded-xl border p-4">
                                    <div class="mapping-row-header flex flex-wrap items-start justify-between gap-2 mb-3">
                                        <div class="min-w-0 flex-1">
                                            <span class="mapping-cpmk-id inline-flex rounded-md px-2 py-0.5 text-xs font-bold font-mono" x-text="cpmk.id"></span>
                                            <p class="mapping-cpmk-name mt-1 text-sm leading-snug" x-text="cpmk.name"></p>
                                        </div>
                                        <span class="mapping-count-badge inline-flex shrink-0 items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold border">
                                            <span x-text="(mappingState[cpmk.id] || []).length"></span>
                                            <span>CPL</span>
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <template x-for="cpl in mappingCpls" :key="cpmk.id + '-' + cpl.id">
                                            <label class="mapping-cpl-chip flex items-start gap-2.5 rounded-lg border px-3 py-2.5 cursor-pointer transition-colors"
                                                :class="isCplMapped(cpmk.id, cpl.id) ? 'is-checked' : ''">
                                                <input type="checkbox"
                                                    :checked="isCplMapped(cpmk.id, cpl.id)"
                                                    @change="toggleCplMapping(cpmk.id, cpl.id, $event.target.checked)"
                                                    class="mapping-checkbox mt-0.5 h-4 w-4 shrink-0 rounded">
                                                <span class="min-w-0 text-xs leading-snug">
                                                    <span class="mapping-cpl-id font-bold font-mono" x-text="cpl.id"></span>
                                                    <span class="mapping-cpl-name block mt-0.5" x-text="cpl.name"></span>
                                                </span>
                                            </label>
                                        </template>
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
                            <i class="fa-solid fa-book-open text-indigo-500"></i> Informasi Mata Kuliah
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Kode MK</label>
                            <input type="text" readonly x-model="courseData.id"
                                class="w-full rounded-xl border-gray-300 bg-gray-100 px-4 py-2.5 text-sm font-mono text-gray-500 cursor-not-allowed outline-none dark:bg-[#0f172a]/50 dark:text-gray-400 dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Nama Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="course_name" required maxlength="100" x-model="courseData.name"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Fakultas <span class="text-red-500">*</span>
                            </label>
                            <select x-model="editFakultas" @change="loadEditProdis()" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Fakultas --</option>
                                @foreach($faculties as $fak)
                                    <option value="{{ $fak->id }}">{{ $fak->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Prodi Pengampu <span class="text-red-500">*</span>
                            </label>
                            <select name="unit_id" x-model="courseData.unit_id" required :disabled="!editFakultas"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 disabled:opacity-60 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Prodi --</option>
                                <template x-for="prodi in editProdis" :key="prodi.id">
                                    <option :value="prodi.id" x-text="prodi.unit_name"></option>
                                </template>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="is_active" x-model="editActive" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
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
    function openCourseDetailModal() {
        return {
            openDetail: false,
            confirmModalOpen: false,
            suppressDetailCloseUntil: 0,
            editMode: false,
            canDelete: false,
            url: '',
            deleteUrl: '',
            courseName: '',
            editFakultas: '',
            editActive: '1',
            editProdis: [],
            editSnapshot: null,
            courseData: {
                id: '',
                name: '',
                unit_id: '',
                fakultas_id: '',
                active: true,
                prodi_name: '',
                fakultas_name: '',
                proposal_count: 0,
                question_count: 0,
                cpmk_count: 0,
                mapping_count: 0,
                cpmk_api_url: '',
                cpmk_store_url: '',
                cpmk_bulk_destroy_url: '',
                mapping_api_url: '',
                mapping_sync_url: '',
            },
            cpmkList: [],
            cpmkSelectedIds: [],
            cpmkLoading: false,
            cpmkSaving: false,
            showCpmkForm: false,
            cpmkFormMode: 'create',
            cpmkForm: { id: '', name: '', is_active: '1', update_url: '' },
            cpmkAlert: { type: '', message: '' },
            mappingCpmks: [],
            mappingCpls: [],
            mappingState: {},
            mappingLoading: false,
            mappingSaving: false,
            mappingAlert: { type: '', message: '' },

            closeDetailIfAllowed() {
                if (Date.now() < this.suppressDetailCloseUntil) {
                    return;
                }

                if (!this.confirmModalOpen) {
                    this.openDetail = false;
                }
            },

            cpmkPostUpdateUrl(url) {
                const normalized = this.normalizeApiUrl(url);
                if (!normalized) {
                    return '';
                }

                return normalized.endsWith('/update') ? normalized : `${normalized.replace(/\/$/, '')}/update`;
            },

            mappingPostSyncUrl(url) {
                const normalized = this.normalizeApiUrl(url);
                if (!normalized) {
                    return '';
                }

                if (normalized.endsWith('/sync')) {
                    return normalized;
                }

                return normalized.endsWith('/mapping')
                    ? `${normalized}/sync`
                    : normalized.replace(/\/mapping\/?$/, '/mapping/sync');
            },

            normalizeApiUrl(url) {
                if (!url) {
                    return '';
                }

                if (url.startsWith('/')) {
                    return url;
                }

                try {
                    const parsed = new URL(url, window.location.origin);
                    return parsed.pathname + parsed.search;
                } catch (error) {
                    return url;
                }
            },

            resolveCpmkBulkDestroyUrl(cpmk = null) {
                const courseId = cpmk?.course_id || this.courseData?.id;
                if (courseId) {
                    return `/masterdata/courses/${courseId}/cpmk/bulk-delete`;
                }

                const candidates = [
                    cpmk?.bulk_destroy_url,
                    cpmk?.delete_url?.replace(/\/[^/]+\/delete$/, '/bulk-delete'),
                    this.cpmkList.find(item => item.bulk_destroy_url)?.bulk_destroy_url,
                    this.courseData?.cpmk_bulk_destroy_url,
                    this.courseData?.cpmk_api_url?.replace(/\/api\/data\/?$/, '/bulk-delete'),
                ];

                for (const candidate of candidates) {
                    const normalized = this.normalizeApiUrl(candidate);
                    if (normalized) {
                        return normalized;
                    }
                }

                return '';
            },

            allDeletableCpmkSelected() {
                const ids = this.cpmkList.filter(cpmk => cpmk.can_delete).map(cpmk => cpmk.id);
                return ids.length > 0 && ids.every(id => this.cpmkSelectedIds.includes(id));
            },

            toggleSelectAllCpmk(checked) {
                if (checked) {
                    this.cpmkSelectedIds = this.cpmkList
                        .filter(cpmk => cpmk.can_delete)
                        .map(cpmk => cpmk.id);
                    return;
                }

                this.cpmkSelectedIds = [];
            },

            toggleCpmkSelection(id, checked) {
                if (checked) {
                    if (!this.cpmkSelectedIds.includes(id)) {
                        this.cpmkSelectedIds = [...this.cpmkSelectedIds, id];
                    }
                    return;
                }

                this.cpmkSelectedIds = this.cpmkSelectedIds.filter(item => item !== id);
            },

            handleOpenDetail(event) {
                if (!event.detail?.courseData?.id) {
                    return;
                }

                this.openDetail = true;
                this.confirmModalOpen = false;
                this.editMode = false;
                this.url = event.detail.url;
                this.deleteUrl = event.detail.deleteUrl;
                this.courseName = event.detail.courseName;
                this.canDelete = event.detail.canDelete ?? false;
                this.editSnapshot = null;
                const rawCourseData = event.detail.courseData || {};
                this.courseData = {
                    ...rawCourseData,
                    cpmk_api_url: this.normalizeApiUrl(rawCourseData.cpmk_api_url),
                    cpmk_store_url: this.normalizeApiUrl(rawCourseData.cpmk_store_url),
                    cpmk_bulk_destroy_url: this.normalizeApiUrl(rawCourseData.cpmk_bulk_destroy_url),
                    mapping_api_url: this.normalizeApiUrl(rawCourseData.mapping_api_url),
                    mapping_sync_url: this.normalizeApiUrl(rawCourseData.mapping_sync_url),
                };
                this.editFakultas = this.courseData.fakultas_id || '';
                this.editActive = this.courseData.active ? '1' : '0';
                this.resetCpmkForm();
                this.mappingAlert = { type: '', message: '' };
                this.loadEditProdis(true);
                this.fetchCpmkList();
            },

            async fetchCpmkList() {
                if (!this.courseData.cpmk_api_url) {
                    return;
                }

                this.cpmkLoading = true;

                try {
                    const response = await fetch(this.courseData.cpmk_api_url, {
                        headers: { 'Accept': 'application/json' },
                    });

                    if (response.ok) {
                        const rows = await response.json();
                        this.cpmkList = rows.map((row) => ({
                            ...row,
                            update_url: this.normalizeApiUrl(row.update_url),
                            delete_url: this.normalizeApiUrl(row.delete_url),
                            bulk_destroy_url: this.normalizeApiUrl(row.bulk_destroy_url),
                        }));
                        this.courseData.cpmk_count = this.cpmkList.length;
                        this.cpmkSelectedIds = this.cpmkSelectedIds.filter(id => this.cpmkList.some(cpmk => cpmk.id === id));

                        if (this.cpmkList.length > 0) {
                            this.fetchMappingData();
                        } else {
                            this.mappingCpmks = [];
                            this.mappingCpls = [];
                            this.mappingState = {};
                            this.mappingLoading = false;
                        }
                    }
                } catch (error) {
                    console.error('Gagal memuat CPMK', error);
                } finally {
                    this.cpmkLoading = false;
                }
            },

            resetCpmkForm() {
                this.showCpmkForm = false;
                this.cpmkFormMode = 'create';
                this.cpmkForm = { id: '', name: '', is_active: '1', update_url: '' };
                this.cpmkAlert = { type: '', message: '' };
            },

            cancelCpmkForm() {
                this.resetCpmkForm();
            },

            editCpmk(cpmk) {
                this.cpmkFormMode = 'edit';
                this.showCpmkForm = true;
                this.cpmkForm = {
                    id: cpmk.id,
                    name: cpmk.name,
                    is_active: cpmk.is_active,
                    update_url: cpmk.update_url,
                };
            },

            flashCpmk(type, message) {
                this.cpmkAlert = { type, message };
                setTimeout(() => { this.cpmkAlert.message = ''; }, 3500);
            },

            async saveCpmk() {
                if (!this.cpmkForm.name.trim()) {
                    this.flashCpmk('error', 'Deskripsi CPMK wajib diisi.');
                    return;
                }

                this.cpmkSaving = true;
                const isEdit = this.cpmkFormMode === 'edit';
                const url = isEdit ? this.cpmkPostUpdateUrl(this.cpmkForm.update_url) : this.courseData.cpmk_store_url;
                const method = 'POST';

                try {
                    const response = await fetch(url, {
                        method,
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({
                            name: this.cpmkForm.name.trim(),
                            is_active: this.cpmkForm.is_active,
                        }),
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        this.flashCpmk('error', result.message || 'Gagal menyimpan CPMK.');
                        return;
                    }

                    this.flashCpmk('success', result.message || 'CPMK berhasil disimpan.');
                    this.resetCpmkForm();
                    await this.fetchCpmkList();
                    await this.fetchMappingData();
                } catch (error) {
                    console.error('Gagal menyimpan CPMK', error);
                    this.flashCpmk('error', 'Terjadi kesalahan saat menyimpan CPMK.');
                } finally {
                    this.cpmkSaving = false;
                }
            },

            cpmkBulkDeleteUrl(courseId) {
                const id = String(courseId || this.courseData?.id || '').trim();
                return id ? `/masterdata/courses/${id}/cpmk/bulk-delete` : '';
            },

            dispatchCpmkDeleteModal({ mode, items, courseId }) {
                const resolvedCourseId = String(courseId || this.courseData?.id || '').trim();

                if (!resolvedCourseId) {
                    this.flashCpmk('error', 'Kode mata kuliah tidak tersedia. Tutup modal lalu buka detail lagi.');
                    return;
                }

                const bulkUrl = this.cpmkBulkDeleteUrl(resolvedCourseId);

                window.dispatchEvent(new CustomEvent('open-cpmk-delete-modal', {
                    bubbles: true,
                    detail: {
                        mode,
                        courseId: resolvedCourseId,
                        items: items.map((item) => ({
                            ...item,
                            course_id: resolvedCourseId,
                        })),
                        bulkUrl,
                    },
                }));
            },

            openCpmkDeleteConfirm(cpmk) {
                if (!cpmk.can_delete) {
                    this.flashCpmk('error', 'CPMK tidak dapat dihapus karena masih digunakan di soal atau pemetaan CPL.');
                    return;
                }

                this.dispatchCpmkDeleteModal({
                    mode: 'single',
                    courseId: this.courseData.id,
                    items: [{ id: cpmk.id, name: cpmk.name }],
                });
            },

            requestBulkDeleteCpmk() {
                if (this.cpmkSelectedIds.length === 0) {
                    return;
                }

                const items = this.cpmkList
                    .filter(cpmk => this.cpmkSelectedIds.includes(cpmk.id) && cpmk.can_delete)
                    .map(cpmk => ({ id: cpmk.id, name: cpmk.name }));

                if (items.length === 0) {
                    this.flashCpmk('error', 'Tidak ada CPMK terpilih yang dapat dihapus.');
                    return;
                }

                this.dispatchCpmkDeleteModal({
                    mode: 'bulk',
                    courseId: this.courseData.id,
                    items,
                });
            },

            async handleCpmkDeleted(event) {
                const deletedIds = event.detail?.deletedIds || [];

                if (deletedIds.length > 0) {
                    this.cpmkList = this.cpmkList.filter(cpmk => !deletedIds.includes(cpmk.id));
                    this.courseData.cpmk_count = this.cpmkList.length;
                    this.cpmkSelectedIds = this.cpmkSelectedIds.filter(id => !deletedIds.includes(id));
                } else {
                    this.cpmkSelectedIds = [];
                }

                this.flashCpmk('success', event.detail?.message || 'CPMK berhasil dihapus.');
            },

            handleCpmkDeleteFailed(event) {
                this.flashCpmk('error', event.detail?.message || 'Gagal menghapus CPMK.');
            },

            async fetchMappingData() {
                if (!this.courseData.mapping_api_url) {
                    return;
                }

                this.mappingLoading = true;
                this.mappingCpmks = [];
                this.mappingCpls = [];
                this.mappingState = {};

                try {
                    const response = await fetch(this.courseData.mapping_api_url, {
                        headers: { 'Accept': 'application/json' },
                    });

                    if (response.ok) {
                        const result = await response.json();
                        this.mappingCpmks = result.cpmks || [];
                        this.mappingCpls = result.cpls || [];
                        this.courseData.mapping_count = result.mapping_count || 0;

                        const state = {};
                        (result.cpmks || []).forEach((cpmk) => {
                            state[cpmk.id] = [...(cpmk.cpl_ids || [])];
                        });
                        this.mappingState = state;
                    }
                } catch (error) {
                    console.error('Gagal memuat pemetaan', error);
                } finally {
                    this.mappingLoading = false;
                }
            },

            isCplMapped(cpmkId, cplId) {
                return (this.mappingState[cpmkId] || []).includes(cplId);
            },

            toggleCplMapping(cpmkId, cplId, checked) {
                const current = [...(this.mappingState[cpmkId] || [])];

                if (checked) {
                    if (!current.includes(cplId)) {
                        current.push(cplId);
                    }
                } else {
                    const index = current.indexOf(cplId);
                    if (index >= 0) {
                        current.splice(index, 1);
                    }
                }

                this.mappingState = {
                    ...this.mappingState,
                    [cpmkId]: current,
                };
            },

            flashMapping(type, message) {
                this.mappingAlert = { type, message };
                setTimeout(() => { this.mappingAlert.message = ''; }, 3500);
            },

            async saveMapping() {
                if (!this.courseData.mapping_sync_url) {
                    return;
                }

                this.mappingSaving = true;

                const mappings = Object.keys(this.mappingState).map((cpmkId) => ({
                    cpmk_id: cpmkId,
                    cpl_ids: this.mappingState[cpmkId] || [],
                }));

                try {
                    const response = await fetch(this.mappingPostSyncUrl(this.courseData.mapping_sync_url), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ mappings }),
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        this.flashMapping('error', result.message || 'Gagal menyimpan pemetaan.');
                        return;
                    }

                    this.flashMapping('success', result.message || 'Pemetaan berhasil disimpan.');
                    await this.fetchMappingData();
                } catch (error) {
                    console.error('Gagal menyimpan pemetaan', error);
                    this.flashMapping('error', 'Terjadi kesalahan saat menyimpan pemetaan.');
                } finally {
                    this.mappingSaving = false;
                }
            },

            async loadEditProdis(keepUnit = false) {
                if (!keepUnit) {
                    this.courseData.unit_id = '';
                }
                this.editProdis = [];

                if (!this.editFakultas) {
                    return;
                }

                try {
                    const response = await fetch(`{{ route('masterdata.courses.api.prodis') }}?fakultas_id=${this.editFakultas}`, {
                        headers: { 'Accept': 'application/json' },
                    });
                    if (response.ok) {
                        this.editProdis = await response.json();
                    }
                } catch (error) {
                    console.error('Gagal memuat prodi', error);
                }
            },

            enterEditMode() {
                this.editSnapshot = JSON.parse(JSON.stringify(this.courseData));
                this.editFakultas = this.courseData.fakultas_id || '';
                this.editActive = this.courseData.active ? '1' : '0';
                this.loadEditProdis(true);
                this.editMode = true;
            },

            cancelEdit() {
                if (this.editSnapshot) {
                    this.courseData = JSON.parse(JSON.stringify(this.editSnapshot));
                    this.editFakultas = this.courseData.fakultas_id || '';
                    this.editActive = this.courseData.active ? '1' : '0';
                }
                this.editMode = false;
                this.editSnapshot = null;
            },

            confirmDelete() {
                window.dispatchEvent(new CustomEvent('open-delete-modal', {
                    bubbles: true,
                    detail: {
                        url: this.deleteUrl,
                        name: this.courseName,
                    },
                }));
                this.openDetail = false;
            },
        };
    }
</script>