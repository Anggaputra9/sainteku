
<div x-show="openDetail"
    class="fixed inset-0 z-[999990] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div
        class="relative w-full max-w-5xl transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all flex flex-col max-h-[90dvh] sm:max-h-[95vh] overflow-hidden">

        
        <div
            class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
            <div class="pr-4">
                <h3
                    class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2 sm:gap-3">
                    <div
                        class="flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-xl bg-blue-50 border border-blue-100 dark:bg-blue-900/30 dark:border-blue-800/50 shrink-0">
                        <i class="fa-solid fa-file-lines text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                    </div>
                    <span class="leading-tight">Detail Pengajuan Soal</span>
                </h3>
            </div>

            <button @click="openDetail = false" type="button"
                class="shrink-0 inline-flex items-center justify-center rounded-lg p-1.5 sm:p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
                <i class="fas fa-times text-xl sm:text-2xl"></i>
            </button>
        </div>

        <form method="POST" id="bulkReviewForm" class="flex-1 flex flex-col overflow-hidden relative">
            <?php echo csrf_field(); ?>

            
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 custom-scrollbar bg-slate-50 dark:bg-[#0f172a]"
                id="modalDetailScroll">
                <template x-if="selectedProposal">
                    <div class="space-y-4 sm:space-y-6">

                        
                        <div
                            class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm dark:border-gray-700/50 dark:bg-[#1e293b] relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-500"></div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 pl-2">
                                <div>
                                    <span
                                        class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Mata
                                        Kuliah</span>
                                    <strong class="text-sm sm:text-base text-gray-900 dark:text-gray-100 leading-tight"
                                        x-text="selectedProposal.course.course_name"></strong>
                                </div>
                                <div>
                                    <span
                                        class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Dosen
                                        Pengaju</span>
                                    <strong class="text-sm sm:text-base text-gray-900 dark:text-gray-100 leading-tight"
                                        x-text="selectedProposal.creator.name"></strong>
                                </div>
                                <div class="sm:col-span-2 lg:col-span-1">
                                    <span
                                        class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Status</span>
                                    <span
                                        class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] sm:text-[11px] font-bold border uppercase tracking-wide mt-1"
                                        :class="{
                                            'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20': selectedProposal.status === 'APPROVED',
                                            'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20': selectedProposal.status === 'REVISED',
                                            'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20': !['APPROVED', 'REVISED'].includes(selectedProposal.status)
                                        }">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 sm:mr-2"
                                            :class="{'bg-emerald-500': selectedProposal.status === 'APPROVED', 'bg-orange-500': selectedProposal.status === 'REVISED', 'bg-blue-500': !['APPROVED', 'REVISED'].includes(selectedProposal.status)}"></span>
                                        <span x-text="selectedProposal.status"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        
                        <template
                            x-if="selectedProposal.reviews && selectedProposal.reviews.length > 0 && selectedProposal.reviews[0].comment">
                            <div
                                class="rounded-2xl border border-amber-200 bg-amber-50/80 p-4 sm:p-5 shadow-sm dark:bg-amber-900/10 dark:border-amber-500/30 flex gap-3 sm:gap-4 items-start">
                                <div
                                    class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400 shrink-0">
                                    <i class="fa-solid fa-bullhorn text-xs sm:text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-bold text-amber-800 dark:text-amber-400 mb-1">
                                        Catatan Umum / Kesimpulan Terakhir</h4>
                                    <p class="text-xs sm:text-sm text-amber-700 dark:text-amber-300 leading-relaxed"
                                        x-text="selectedProposal.reviews[0].comment"></p>
                                </div>
                            </div>
                        </template>

                        
                        <div class="mt-6 sm:mt-8 flex items-center gap-3">
                            <h3
                                class="text-xs sm:text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-widest">
                                Daftar Butir Soal</h3>
                            <hr class="flex-1 border-gray-200 dark:border-gray-700">
                        </div>

                        <div class="space-y-4 sm:space-y-6 mt-4">
                            <template x-for="(eq, index) in selectedProposal.exam_questions" :key="index">
                                <div
                                    class="p-4 sm:p-6 lg:p-8 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#1e293b] shadow-sm transition hover:shadow-md">

                                    <div
                                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-5 pb-4 sm:pb-5 border-b border-gray-100 dark:border-gray-700/50 gap-3 sm:gap-4">
                                        <div class="flex items-center gap-2 sm:gap-3">
                                            <div class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-600 text-white font-bold text-xs sm:text-sm shadow-sm shrink-0"
                                                x-text="eq.order_no"></div>
                                            <span
                                                class="text-sm sm:text-base font-bold text-gray-800 dark:text-gray-200">Pertanyaan</span>
                                        </div>
                                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                                            <span
                                                class="inline-flex items-center gap-1.5 text-[10px] sm:text-xs font-bold px-2.5 py-1 sm:px-3 sm:py-1.5 bg-slate-100 dark:bg-slate-800 rounded-lg text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                <i class="fas fa-weight-hanging text-slate-400"></i> Bobot: <span
                                                    x-text="eq.weight + '%'"></span>
                                            </span>
                                            <span
                                                class="inline-flex items-center gap-1.5 text-[10px] sm:text-xs font-bold px-2.5 py-1 sm:px-3 sm:py-1.5 bg-slate-100 dark:bg-slate-800 rounded-lg text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                <i class="fas fa-bullseye text-slate-400"></i> CPMK: <span
                                                    x-text="eq.question.cpmk_id"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line text-sm sm:text-base leading-relaxed"
                                        x-text="eq.question.question_text"></p>

                                    
                                    <template x-if="eq.question.image_path">
                                        <div class="mt-4 sm:mt-5">
                                            <p
                                                class="text-[10px] sm:text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                                                <i class="fas fa-image mr-1"></i> Gambar Lampiran
                                            </p>
                                            <div
                                                class="inline-block p-1.5 sm:p-2 rounded-xl border border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 w-full sm:w-auto">
                                                <img :src="'/storage/' + eq.question.image_path" alt="Ilustrasi Soal"
                                                    class="max-h-48 sm:max-h-64 w-full sm:w-auto object-contain rounded-lg bg-white dark:bg-gray-900">
                                            </div>
                                        </div>
                                    </template>

                                    
                                    <template
                                        x-if="selectedProposal.logs && selectedProposal.logs.filter(l => l.order_no == eq.order_no).length > 0">
                                        <div
                                            class="mt-6 sm:mt-8 pt-5 sm:pt-6 border-t border-gray-100 dark:border-gray-700/50">
                                            <h5
                                                class="text-[10px] sm:text-[11px] font-bold uppercase tracking-widest text-gray-400 mb-3 sm:mb-4 flex items-center gap-2">
                                                <i class="fas fa-history"></i> Riwayat Revisi Soal
                                            </h5>
                                            <div
                                                class="space-y-3 sm:space-y-4 pl-2 border-l-2 border-gray-200 dark:border-gray-700 ml-1 sm:ml-2">
                                                <template
                                                    x-for="log in selectedProposal.logs.filter(l => l.order_no == eq.order_no)"
                                                    :key="log.id">
                                                    <div class="relative pl-4 sm:pl-5">
                                                        <div class="absolute -left-[21px] sm:-left-[25px] top-1.5 h-3 w-3 sm:h-3.5 sm:w-3.5 rounded-full border-2 border-white dark:border-[#1e293b]"
                                                            :class="log.type === 'Komentar Kaprodi' ? 'bg-orange-500' : 'bg-blue-500'">
                                                        </div>
                                                        <div
                                                            class="bg-gray-50 dark:bg-[#0f172a]/50 rounded-xl p-3 sm:p-3.5 border border-gray-100 dark:border-gray-700/50">
                                                            <div
                                                                class="flex justify-between items-center mb-1 sm:mb-1.5">
                                                                <span
                                                                    class="text-[11px] sm:text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1.5">
                                                                    <i class="fas fa-user-circle text-gray-400"></i>
                                                                    <span x-text="log.user.name"></span>
                                                                </span>
                                                            </div>
                                                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed"
                                                                :class="log.type === 'Komentar Kaprodi' ? 'font-medium text-orange-800 dark:text-orange-400' : 'italic'"
                                                                x-text="log.message"></p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    
                                    <template x-if="isReviewer && selectedProposal.status === 'SUBMITTED'">
                                        <div
                                            class="mt-5 sm:mt-6 rounded-xl bg-amber-50/50 p-4 sm:p-5 border border-amber-200 dark:bg-amber-900/10 dark:border-amber-500/20">
                                            <label
                                                class="text-[11px] sm:text-xs font-bold text-amber-800 dark:text-amber-500 mb-2 sm:mb-3 flex items-center gap-2">
                                                <i class="fas fa-pen-to-square"></i> Beri Catatan Revisi untuk Soal Ini
                                            </label>
                                            <textarea :name="'question_comments[' + eq.order_no + ']'" rows="2"
                                                placeholder="Cth: Pertanyaan kurang jelas..."
                                                class="w-full text-xs sm:text-sm rounded-xl border-amber-200 bg-white px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 dark:bg-[#0f172a] dark:border-gray-700 dark:text-gray-200 dark:focus:ring-amber-900/50 transition-all resize-y shadow-sm"></textarea>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        
                        <template x-if="isReviewer && selectedProposal.status === 'SUBMITTED'">
                            <div
                                class="mt-8 sm:mt-10 rounded-2xl border border-indigo-100 bg-white shadow-md overflow-hidden dark:bg-[#1e293b] dark:border-indigo-900/30">
                                <div
                                    class="bg-indigo-50 dark:bg-indigo-900/20 px-4 sm:px-6 py-3 sm:py-4 border-b border-indigo-100 dark:border-indigo-900/50">
                                    <h4
                                        class="text-sm sm:text-base font-bold text-indigo-800 dark:text-indigo-400 flex items-center gap-2">
                                        <i class="fa-solid fa-clipboard-check"></i> Form Keputusan Akhir Kaprodi
                                    </h4>
                                </div>

                                <div class="p-4 sm:p-6 lg:p-8">
                                    <div>
                                        <label
                                            class="block text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Catatan
                                            Umum / Kesimpulan</label>
                                        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mb-2 sm:mb-3">
                                            Tuliskan kesimpulan akhir untuk dosen. (Opsional jika sudah memberi catatan
                                            per-soal).</p>
                                        <textarea name="comment" rows="3"
                                            class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-indigo-900/50 transition-all resize-y shadow-sm"
                                            placeholder="Misal: Secara keseluruhan sudah bagus..."></textarea>
                                    </div>

                                    <div class="mt-4 sm:mt-5">
                                        <?php if(empty(Auth::user()->signature)): ?>
                                            <div
                                                class="flex items-start gap-2.5 sm:gap-3 rounded-xl bg-orange-50 p-3 sm:p-4 border border-orange-200 dark:bg-orange-900/10 dark:border-orange-500/30">
                                                <i class="fas fa-exclamation-circle text-orange-500 mt-0.5 text-sm"></i>
                                                <div>
                                                    <h5
                                                        class="text-xs sm:text-sm font-bold text-orange-800 dark:text-orange-400">
                                                        Tanda Tangan Belum Diatur</h5>
                                                    <p
                                                        class="text-[10px] sm:text-xs text-orange-700 dark:text-orange-500 mt-1">
                                                        Anda akan diminta untuk menggambar Tanda Tangan Digital setelah
                                                        menekan tombol Setujui & Sahkan.</p>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div
                                                class="flex items-start gap-2.5 sm:gap-3 rounded-xl bg-emerald-50 p-3 sm:p-4 border border-emerald-200 dark:bg-emerald-900/10 dark:border-emerald-500/30">
                                                <i class="fas fa-check-circle text-emerald-500 mt-0.5 text-sm"></i>
                                                <div>
                                                    <h5
                                                        class="text-xs sm:text-sm font-bold text-emerald-800 dark:text-emerald-400">
                                                        Tanda Tangan Tersimpan</h5>
                                                    <p
                                                        class="text-[10px] sm:text-xs text-emerald-700 dark:text-emerald-500 mt-1">
                                                        Tanda tangan digital Anda sudah siap dan akan dilampirkan otomatis
                                                        pada dokumen PDF.</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>
                </template>
            </div>

            
            <div
                class="shrink-0 border-t border-gray-200 bg-white px-4 sm:px-6 py-4 sm:py-5 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sticky bottom-0">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4">

                    <div class="w-full sm:w-auto flex justify-start">
                        <template x-if="selectedProposal && selectedProposal.status === 'APPROVED'">
                            <div class="w-full sm:w-auto">
                                <?php if(Auth::user()->hasPermission(3, 'E')): ?>
                                    <a :href="'<?php echo e(url('monev-akademik/tashih/print')); ?>/' + selectedProposal.uuid"
                                        target="_blank"
                                        class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-emerald-600 px-5 sm:px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-700 hover:shadow-lg focus:ring-4 focus:ring-emerald-500/30 transition-all">
                                        <i class="fas fa-print"></i> Cetak Kertas Ujian
                                    </a>
                                <?php endif; ?>
                            </div>
                        </template>
                    </div>

                    <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-2.5 sm:gap-3">
                        <template
                            x-if="selectedProposal && userId == selectedProposal.created_by && ['SUBMITTED', 'REVISED'].includes(selectedProposal.status)">
                            <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-3 w-full">
                                <?php if(Auth::user()->hasPermission(3, 'D')): ?>
                                    <button type="button"
                                        @click="openDetail = false; setTimeout(() => { openDelete = true }, 300);"
                                        class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-white border border-red-200 text-red-600 px-5 sm:px-6 py-2.5 text-sm font-bold hover:bg-red-50 hover:border-red-300 dark:bg-[#0f172a] dark:border-red-900/50 dark:text-red-500 dark:hover:bg-red-900/20 focus:ring-4 focus:ring-red-500/10 transition-all">
                                        <i class="fas fa-trash-alt"></i> Batalkan
                                    </button>
                                <?php endif; ?>

                                <?php if(Auth::user()->hasPermission(3, 'U')): ?>
                                    <button type="button" @click="openEditModal()"
                                        class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-blue-600 px-5 sm:px-8 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 hover:shadow-lg focus:ring-4 focus:ring-blue-500/30 transition-all">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                <?php endif; ?>
                            </div>
                        </template>

                        <template x-if="isReviewer && selectedProposal && selectedProposal.status === 'SUBMITTED'">
                            <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-3 w-full">
                                <button type="button"
                                    @click="submitToRevise(selectedProposal.uuid, '<?php echo e(url('monev-akademik/tashih')); ?>')"
                                    class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-white border border-amber-300 text-amber-700 px-5 sm:px-6 py-2.5 text-sm font-bold hover:bg-amber-50 hover:border-amber-400 dark:bg-[#0f172a] dark:border-amber-700/50 dark:text-amber-500 dark:hover:bg-amber-900/20 focus:ring-4 focus:ring-amber-500/10 transition-all">
                                    <i class="fas fa-rotate-left"></i> Kembalikan (Revisi)
                                </button>

                                <?php if(empty(Auth::user()->signature)): ?>
                                    <button type="button" @click="$dispatch('open-signature')"
                                        class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-indigo-600 px-5 sm:px-8 py-2.5 text-sm font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 hover:shadow-lg focus:ring-4 focus:ring-indigo-500/30 transition-all">
                                        <i class="fas fa-file-signature"></i> Setujui & Sahkan
                                    </button>
                                <?php else: ?>
                                    <button type="button"
                                        @click="submitToApprove(selectedProposal.uuid, '<?php echo e(url('monev-akademik/tashih')); ?>')"
                                        class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-indigo-600 px-5 sm:px-8 py-2.5 text-sm font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 hover:shadow-lg focus:ring-4 focus:ring-indigo-500/30 transition-all">
                                        <i class="fas fa-file-signature"></i> Setujui & Sahkan
                                    </button>
                                <?php endif; ?>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<div x-show="openDelete"
    class="fixed inset-0 z-[999998] flex items-center justify-center p-4 sm:p-6 backdrop-blur-sm bg-gray-900/60"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openDelete = false"
        class="relative w-full max-w-md transform rounded-2xl bg-white p-5 sm:p-8 shadow-2xl dark:bg-[#1e293b] dark:border dark:border-gray-700 text-center flex flex-col max-h-[90dvh]">

        <div
            class="mx-auto mb-4 sm:mb-5 flex h-14 w-14 sm:h-16 sm:w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 shrink-0">
            <i class="fas fa-exclamation-triangle text-2xl sm:text-3xl text-red-600 dark:text-red-500"></i>
        </div>

        <h3 class="mb-2 text-lg sm:text-xl font-bold text-gray-900 dark:text-white shrink-0">Batalkan Pengajuan?</h3>

        <div class="overflow-y-auto custom-scrollbar mb-5 sm:mb-6">
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                Apakah Anda yakin ingin membatalkan dan menghapus pengajuan soal ini? Data yang sudah dihapus tidak
                dapat dikembalikan.
            </p>
        </div>

        <form method="POST" id="formDeleteProposal" class="shrink-0"
            :action="'<?php echo e(url('monev-akademik/tashih/destroy')); ?>/' + (selectedProposal ? selectedProposal.uuid : '')">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>

            <div class="flex flex-col sm:flex-row justify-center gap-2.5 sm:gap-3">
                <button type="button" @click="openDelete = false"
                    class="inline-flex justify-center items-center rounded-xl border border-gray-300 bg-white px-5 sm:px-6 py-2.5 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all w-full sm:w-auto">
                    Batal
                </button>
                <button type="submit" :disabled="!selectedProposal"
                    class="inline-flex justify-center items-center rounded-xl bg-red-600 px-5 sm:px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-red-600/20 hover:bg-red-700 hover:shadow-lg focus:ring-4 focus:ring-red-500/30 transition-all w-full sm:w-auto">
                    Ya, Hapus Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<?php if(empty(Auth::user()->signature)): ?>
    
    <div x-data="{ openSignature: false }"
        @open-signature.window="openSignature = true; setTimeout(() => { initAlpineCanvas(); }, 300);"
        x-show="openSignature"
        class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6 backdrop-blur-sm bg-gray-900/60"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

        <div @click.away="openSignature = false"
            class="relative w-full max-w-lg transform rounded-2xl bg-white shadow-2xl dark:bg-[#1e293b] dark:border dark:border-gray-700 overflow-hidden flex flex-col max-h-[90dvh]">

            <div
                class="shrink-0 bg-indigo-600 px-5 sm:px-6 py-3 sm:py-4 flex justify-between items-center dark:bg-indigo-700">
                <h3 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-signature"></i> Tanda Tangan Digital
                </h3>
                <button @click="openSignature = false" class="text-white hover:text-indigo-200 transition p-1">
                    <i class="fas fa-times text-lg sm:text-xl"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-5 sm:p-8 flex flex-col">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-4 text-center shrink-0">
                    Silakan gambar tanda tangan Anda di dalam kotak di bawah ini untuk mengesahkan dokumen pengajuan.
                </p>

                <div class="shrink-0 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 overflow-hidden relative dark:bg-[#0f172a] dark:border-gray-600 group"
                    style="touch-action: none;">
                    <canvas id="alpineSignaturePad"
                        class="w-full h-[180px] sm:h-[200px] cursor-crosshair bg-white"></canvas>
                </div>

                <div class="text-right mt-2 sm:mt-3 shrink-0">
                    <button type="button"
                        class="text-[10px] sm:text-[11px] font-bold text-gray-500 hover:text-red-500 transition uppercase tracking-wider p-2"
                        onclick="clearAlpineCanvas()">
                        <i class="fas fa-eraser"></i> Bersihkan Kanvas
                    </button>
                </div>

                <input type="hidden" name="signature_base64" id="alpine_signature_base64" form="bulkReviewForm">

                <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row gap-2.5 sm:gap-3 shrink-0">
                    <button type="button" @click="openSignature = false"
                        class="w-full sm:w-1/3 inline-flex justify-center items-center gap-2 rounded-xl bg-gray-100 px-5 sm:px-6 py-2.5 sm:py-3 text-sm font-bold text-gray-700 hover:bg-gray-200 transition-all dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button type="button"
                        @click="submitWithSignature(selectedProposal?.uuid, '<?php echo e(url('monev-akademik/tashih')); ?>')"
                        class="w-full sm:w-2/3 inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-5 sm:px-6 py-2.5 sm:py-3 text-sm font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 hover:shadow-lg focus:ring-4 focus:ring-indigo-500/30 transition-all">
                        <i class="fas fa-save"></i> Simpan & Sahkan
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    function submitToRevise(uuid, baseUrl) {
        let f = document.getElementById('bulkReviewForm');
        f.action = baseUrl + '/' + uuid + '/revise';
        f.submit();
    }

    // Dipanggil kalau user SUDAH PUNYA TTD
    function submitToApprove(uuid, baseUrl) {
        let f = document.getElementById('bulkReviewForm');
        f.action = baseUrl + '/' + uuid + '/approve';
        f.submit();
    }

    // Dipanggil dari dalam Modal TTD
    function submitWithSignature(uuid, baseUrl) {
        if (!uuid) return;

        // 1. Cek pakai flag, bukan pakai fungsi ToDataURL (Lebih ngebut & presisi!)
        if (!hasSignature) {
            if (typeof toastr !== 'undefined') {
                toastr.warning('Tanda tangan digital wajib digambar!');
            } else {
                alert('Tanda tangan digital wajib digambar!');
            }
            return;
        }

        // 2. Kalau udah dicoret, baru kita ekstrak gambarnya
        prepareAlpineSignature();

        // 3. Submit form
        let f = document.getElementById('bulkReviewForm');
        f.action = baseUrl + '/' + uuid + '/approve';
        f.submit();
    }
</script>


<?php if(empty(Auth::user()->signature)): ?>
    <script>
        let aCanvas, aCtx, aIsDrawing = false;
        let hasSignature = false; // FLAG SENSOR CORETAN BARU

        function initAlpineCanvas() {
            aCanvas = document.getElementById('alpineSignaturePad');
            if (!aCanvas) return;
            aCtx = aCanvas.getContext('2d');
            const rect = aCanvas.parentElement.getBoundingClientRect();
            aCanvas.width = rect.width;
            aCanvas.height = 200;
            aCtx.fillStyle = "white"; aCtx.fillRect(0, 0, aCanvas.width, aCanvas.height);

            hasSignature = false; // Reset flag saat modal dibuka

            // MOUSE EVENTS
            aCanvas.onmousedown = (e) => { aIsDrawing = true; aCtx.beginPath(); aCtx.moveTo(e.offsetX, e.offsetY); };
            aCanvas.onmousemove = (e) => {
                if (aIsDrawing) {
                    hasSignature = true; // Sensor mendeteksi ada coretan
                    aCtx.lineWidth = 3; aCtx.lineCap = 'round'; aCtx.strokeStyle = '#0f172a'; aCtx.lineTo(e.offsetX, e.offsetY); aCtx.stroke();
                }
            };
            aCanvas.onmouseup = () => aIsDrawing = false;
            aCanvas.onmouseout = () => aIsDrawing = false;

            // TOUCH EVENTS (HP)
            aCanvas.addEventListener('touchstart', (e) => { e.preventDefault(); const t = e.touches[0]; const r = aCanvas.getBoundingClientRect(); aIsDrawing = true; aCtx.beginPath(); aCtx.moveTo(t.clientX - r.left, t.clientY - r.top); }, { passive: false });
            aCanvas.addEventListener('touchmove', (e) => {
                e.preventDefault();
                if (aIsDrawing) {
                    hasSignature = true; // Sensor mendeteksi ada coretan jari
                    const t = e.touches[0]; const r = aCanvas.getBoundingClientRect(); aCtx.lineWidth = 3; aCtx.lineCap = 'round'; aCtx.lineTo(t.clientX - r.left, t.clientY - r.top); aCtx.stroke();
                }
            }, { passive: false });
            aCanvas.addEventListener('touchend', () => aIsDrawing = false);
        }

        function clearAlpineCanvas() {
            if (!aCtx) return;
            aCtx.clearRect(0, 0, aCanvas.width, aCanvas.height);
            aCtx.fillStyle = "white"; aCtx.fillRect(0, 0, aCanvas.width, aCanvas.height);
            document.getElementById('alpine_signature_base64').value = "";
            hasSignature = false; // Reset flag coretan
        }

        function prepareAlpineSignature() {
            if (aCanvas) document.getElementById('alpine_signature_base64').value = aCanvas.toDataURL('image/png');
        }
    </script>
<?php endif; ?><?php /**PATH C:\laragon\www\sainteku\Modules/MonevAkademik\resources/views/tashih/partials/modal-detail.blade.php ENDPATH**/ ?>