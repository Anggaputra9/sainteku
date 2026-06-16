<template x-teleport="#modal-root">
<div x-data="openPeriodDetailModal()" @open-detail-modal.window="handleOpenDetail($event)" x-show="openDetail"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div @click.away="openDetail = false"
        class="relative w-full max-w-2xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
            <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                    <i class="fa-solid fa-calendar-days text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                </div>
                <div class="min-w-0 leading-tight">
                    <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl"
                        x-text="editMode ? 'Edit Tahun Akademik' : 'Detail Tahun Akademik'"></h3>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="periodLabel"></p>
                </div>
            </div>
            <div class="shrink-0 w-8 sm:w-9"></div>
        </div>

        <div x-show="!editMode" class="flex flex-col flex-1 overflow-hidden">
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days text-indigo-500"></i> Informasi Periode
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">ID Periode</div>
                            <div class="text-sm font-mono font-semibold text-indigo-700 dark:text-indigo-400" x-text="periodData.id"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Tahun Akademik</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="periodData.name"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Semester</div>
                            <span class="inline-flex rounded-md bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800"
                                x-text="periodData.semester"></span>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Status</div>
                            <span x-show="periodData.active"
                                class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span> Aktif
                            </span>
                            <span x-show="!periodData.active"
                                class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span> Nonaktif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-file-lines text-indigo-500"></i> Penggunaan
                        </h4>
                    </div>
                    <div class="p-5">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Pengajuan Ujian Terkait</div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            <span x-text="periodData.proposal_count"></span> pengajuan
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400" x-show="!canDelete">
                            Periode tidak dapat dihapus karena masih digunakan oleh pengajuan ujian.
                        </p>
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

        <form x-show="editMode" :action="url" method="POST" class="flex flex-col flex-1 overflow-hidden m-0">
            @csrf
            @method('PUT')
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days text-indigo-500"></i> Informasi Periode
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">ID Periode</label>
                            <input type="text" readonly x-model="periodData.id"
                                class="w-full rounded-xl border-gray-300 bg-gray-100 px-4 py-2.5 text-sm font-mono text-gray-500 cursor-not-allowed outline-none dark:bg-[#0f172a]/50 dark:text-gray-400 dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Tahun Akademik <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" required maxlength="15" pattern="\d{4}/\d{4}" x-model="periodData.name"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Semester <span class="text-red-500">*</span>
                            </label>
                            <select name="semester" x-model="periodData.semester" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="Gasal">Gasal</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="relative inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" name="is_active" value="1" class="peer sr-only" x-model="periodData.active">
                                <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-gray-700"></div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Periode Aktif</span>
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
</div>
</template>

<script>
    function openPeriodDetailModal() {
        return {
            openDetail: false,
            editMode: false,
            canDelete: false,
            url: '',
            deleteUrl: '',
            periodLabel: '',
            editSnapshot: null,
            periodData: {
                id: '',
                name: '',
                semester: '',
                active: true,
                proposal_count: 0,
            },

            handleOpenDetail(event) {
                this.openDetail = true;
                this.editMode = false;
                this.url = event.detail.url;
                this.deleteUrl = event.detail.deleteUrl;
                this.periodLabel = event.detail.periodLabel;
                this.canDelete = event.detail.canDelete ?? false;
                this.editSnapshot = null;
                this.periodData = { ...event.detail.periodData };
            },

            enterEditMode() {
                this.editSnapshot = JSON.parse(JSON.stringify(this.periodData));
                this.editMode = true;
            },

            cancelEdit() {
                if (this.editSnapshot) {
                    this.periodData = JSON.parse(JSON.stringify(this.editSnapshot));
                }
                this.editMode = false;
                this.editSnapshot = null;
            },

            confirmDelete() {
                window.dispatchEvent(new CustomEvent('open-delete-modal', {
                    bubbles: true,
                    detail: {
                        url: this.deleteUrl,
                        name: this.periodLabel,
                    },
                }));
                this.openDetail = false;
            },
        };
    }
</script>