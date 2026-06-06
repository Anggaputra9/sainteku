<template x-teleport="#modal-root">
<div x-data="openDocumentReviseModal()" @open-revise-modal.window="handleOpenRevise($event)" x-show="openRevise"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div @click.away="openRevise = false"
        class="relative w-full max-w-lg flex flex-col transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm">
            <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-orange-100 bg-orange-50 dark:border-orange-800/50 dark:bg-orange-900/30">
                    <i class="fa-solid fa-file-pen text-sm text-orange-600 dark:text-orange-400 sm:text-base"></i>
                </div>
                <div class="min-w-0 leading-tight">
                    <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl">Unggah Revisi</h3>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="reviseTitle"></p>
                </div>
            </div>
            <div class="shrink-0 w-8 sm:w-9"></div>
        </div>

        <form :action="reviseUrl" method="POST" enctype="multipart/form-data" class="flex flex-col">
            @csrf
            <div class="p-5 sm:p-6 space-y-5">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-file-arrow-up text-indigo-500"></i> File Pengganti
                        </h4>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-center rounded-xl border border-dashed border-gray-300 px-6 py-8 dark:border-gray-600 hover:bg-gray-50 transition dark:hover:bg-gray-800/50">
                            <div class="text-center">
                                <i class="fa-solid fa-file-arrow-up text-4xl text-gray-300 dark:text-gray-600 mb-3"
                                    :class="{ 'text-orange-500': reviseFileName }"></i>
                                <div class="flex text-sm leading-6 text-gray-600 dark:text-gray-400 justify-center">
                                    <label for="revise-upload"
                                        class="relative cursor-pointer rounded-md font-semibold text-orange-500 hover:text-orange-400 dark:text-orange-400">
                                        <span x-show="!reviseFileName">Pilih File Revisi</span>
                                        <span x-show="reviseFileName">Ganti File</span>
                                        <input id="revise-upload" name="document_file" type="file" class="sr-only"
                                            accept=".pdf,.doc,.docx" required
                                            @change="reviseFileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''">
                                    </label>
                                </div>
                                <p x-show="!reviseFileName" class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX maksimal 10MB</p>
                                <p x-show="reviseFileName" x-cloak
                                    class="text-sm font-bold text-green-600 dark:text-green-400 mt-2 flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-check-circle"></i> <span x-text="reviseFileName"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 dark:bg-[#1e293b]/95 dark:border-gray-700">
                <div class="flex flex-row flex-nowrap items-center justify-end gap-2 sm:gap-3">
                    <button type="button" @click="openRevise = false"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-orange-500 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-orange-600 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-upload"></i> Kirim Revisi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</template>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('openDocumentReviseModal', () => ({
            openRevise: false,
            reviseUrl: '',
            reviseTitle: '',
            reviseFileName: '',

            handleOpenRevise(event) {
                this.reviseUrl = event.detail.reviseUrl || '';
                this.reviseTitle = event.detail.documentTitle || '';
                this.reviseFileName = '';
                this.openRevise = true;
            },
        }));
    });
</script>