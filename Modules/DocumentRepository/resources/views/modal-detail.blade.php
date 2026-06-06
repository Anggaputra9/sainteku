<template x-teleport="#modal-root">
<div x-data="openDocumentDetailModal()" @open-detail-modal.window="handleOpenDetail($event)" x-show="openDetail"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div @click.away="!reviewMode && (openDetail = false)"
        class="relative w-full flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden"
        :class="reviewMode ? 'max-w-2xl' : 'max-w-3xl'">

        {{-- HEADER --}}
        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
            <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                    <i class="fa-solid text-sm text-blue-600 dark:text-blue-400 sm:text-base"
                        :class="reviewMode ? 'fa-gavel' : 'fa-file-lines'"></i>
                </div>
                <div class="min-w-0 leading-tight">
                    <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl"
                        x-text="reviewMode ? 'Review Dokumen' : 'Detail Dokumen'"></h3>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="docData.document_title"></p>
                </div>
            </div>
            <div class="shrink-0 w-8 sm:w-9"></div>
        </div>

        {{-- MODE LIHAT --}}
        <div x-show="!reviewMode" class="flex flex-col flex-1 overflow-hidden">
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-file-lines text-indigo-500"></i> Informasi Dokumen
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Kode Dokumen</div>
                            <div class="text-sm font-mono font-semibold text-indigo-700 dark:text-indigo-400" x-text="docData.document_id"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Versi</div>
                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-bold text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">
                                v<span x-text="docData.version"></span>
                            </span>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Judul</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="docData.document_title"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Tipe</div>
                            <div class="text-sm text-indigo-600 dark:text-indigo-400 font-semibold" x-text="docData.type_name"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Unit Pemilik</div>
                            <div class="text-sm text-gray-900 dark:text-white" x-text="docData.unit_name"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Pengunggah</div>
                            <div class="text-sm text-gray-900 dark:text-white" x-text="docData.creator_name"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Status</div>
                            <span class="inline-flex rounded-md px-2.5 py-1 text-xs font-bold border"
                                :class="{
                                    'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50': docData.status == 3,
                                    'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50': docData.status == 4,
                                    'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50': docData.status == 1 || docData.status == 2
                                }"
                                x-text="docData.status_label"></span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-calendar text-indigo-500"></i> Masa Berlaku
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Tanggal Berlaku</div>
                            <div class="text-sm text-gray-900 dark:text-white" x-text="docData.effective_date || '-'"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Tanggal Kadaluarsa</div>
                            <div class="text-sm text-gray-900 dark:text-white" x-text="docData.expired_date || 'Tidak ditentukan'"></div>
                        </div>
                    </div>
                </div>

                <div x-show="docData.revision_note" class="rounded-xl border border-red-200 bg-red-50 shadow-sm dark:border-red-500/30 dark:bg-red-500/10">
                    <div class="px-5 py-3 border-b border-red-100 dark:border-red-500/20">
                        <h4 class="text-sm font-bold text-red-700 dark:text-red-400 flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i> Catatan Revisi
                        </h4>
                    </div>
                    <div class="p-5">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200 leading-relaxed" x-text="docData.revision_note"></p>
                    </div>
                </div>

                <div x-show="docData.is_locked" class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/30 dark:bg-amber-500/10">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-lock text-amber-500 mt-0.5"></i>
                        <p class="text-sm text-amber-800 dark:text-amber-200">
                            Dokumen sedang dalam proses review. File belum dapat diakses hingga disetujui.
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
                        <button type="button" x-show="docData.can_review" @click="enterReviewMode()"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-indigo-700 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-gavel"></i> Review
                        </button>
                        <button type="button" x-show="docData.can_revise" @click="openRevise()"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-orange-500 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-orange-600 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-clock-rotate-left"></i> Revisi File
                        </button>
                        <a x-show="docData.can_download" :href="docData.download_url" target="_blank"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-blue-700 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-eye"></i> Lihat File
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODE REVIEW --}}
        <form x-show="reviewMode" :action="docData.review_url" method="POST" class="flex flex-col flex-1 overflow-hidden m-0">
            @csrf
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Dokumen ID: <strong class="text-gray-800 dark:text-gray-200" x-text="docData.document_id"></strong><br>
                        Judul: <em class="font-medium text-gray-700 dark:text-gray-300" x-text="docData.document_title"></em>
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-comment-dots text-indigo-500"></i> Catatan Review
                        </h4>
                    </div>
                    <div class="p-5">
                        <textarea name="change_note" rows="4"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white"
                            placeholder="Tuliskan alasan penolakan atau catatan di sini..."></textarea>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Wajib diisi jika menolak dokumen.</p>
                    </div>
                </div>
            </div>

            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2 sm:gap-4">
                    <button type="button" @click="reviewMode = false"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                    <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                        <button type="submit" name="action" value="reject"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-red-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-red-700 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-xmark"></i> Tolak
                        </button>
                        <button type="submit" name="action" value="approve"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-green-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-green-700 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-check"></i> Setujui
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</template>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('openDocumentDetailModal', () => ({
            openDetail: false,
            reviewMode: false,
            docData: {},

            handleOpenDetail(event) {
                this.docData = event.detail.doc || {};
                this.reviewMode = false;
                this.openDetail = true;
            },

            enterReviewMode() {
                this.reviewMode = true;
            },

            openRevise() {
                this.openDetail = false;
                window.dispatchEvent(new CustomEvent('open-revise-modal', {
                    bubbles: true,
                    detail: {
                        reviseUrl: this.docData.revise_url,
                        documentTitle: this.docData.document_title,
                    },
                }));
            },
        }));
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
</style>