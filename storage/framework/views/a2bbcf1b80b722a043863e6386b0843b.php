<div x-show="openDetail" class="fixed inset-0 z-[999990] flex items-center justify-center p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">

    
    <div
        class="relative w-full max-w-5xl transform rounded-2xl bg-gray-50 shadow-2xl ring-1 ring-gray-200 dark:bg-[#1e293b] dark:ring-gray-700 transition-all flex flex-col max-h-[90vh] overflow-hidden">

        
        <div
            class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-10 dark:bg-[#0f172a] dark:border-gray-700 shadow-md">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-blue-500"></i> Detail Pengajuan Soal
                </h3>
            </div>
        </div>

        
        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar bg-gray-50 dark:bg-[#0f172a]/50">
            <template x-if="selectedProposal">
                <div class="space-y-6">

                    
                    <div
                        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700/50 dark:bg-[#1e293b]">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <span
                                    class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Mata
                                    Kuliah</span>
                                <strong class="text-base text-gray-900 dark:text-gray-100"
                                    x-text="selectedProposal.course.course_name"></strong>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"
                                    x-text="selectedProposal.course_id"></div>
                            </div>
                            <div>
                                <span
                                    class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Dosen
                                    Pengaju</span>
                                <strong class="text-base text-gray-900 dark:text-gray-100"
                                    x-text="selectedProposal.creator.name"></strong>
                            </div>
                            <div>
                                <span
                                    class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Jenis
                                    Ujian</span>
                                <strong class="text-base text-gray-900 dark:text-gray-100"
                                    x-text="selectedProposal.exam_type"></strong>
                            </div>
                            <div>
                                <span
                                    class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Status
                                    Pengajuan</span>
                                <span class="inline-flex rounded-md px-3 py-1.5 text-xs font-bold border uppercase"
                                    :class="{
                                        'bg-green-100 text-green-800 border-green-200 dark:bg-green-500/20 dark:text-green-400 dark:border-green-500/30': selectedProposal.status === 'APPROVED',
                                        'bg-orange-100 text-orange-800 border-orange-200 dark:bg-orange-500/20 dark:text-orange-400 dark:border-orange-500/30': selectedProposal.status === 'REVISED',
                                        'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-500/20 dark:text-blue-400 dark:border-blue-500/30': !['APPROVED', 'REVISED'].includes(selectedProposal.status)
                                    }" x-text="selectedProposal.status">
                                </span>
                            </div>
                        </div>
                    </div>

                    
                    <template x-if="selectedProposal.reviews && selectedProposal.reviews.length > 0">
                        <div
                            class="rounded-xl border border-orange-200 bg-orange-50 p-5 shadow-sm dark:bg-orange-900/20 dark:border-orange-500/30">
                            <h4
                                class="text-sm font-bold text-orange-800 dark:text-orange-400 flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-clock-rotate-left"></i> Catatan Revisi Terakhir
                            </h4>
                            <p class="text-sm text-orange-700 dark:text-orange-300 italic"
                                x-text="'&quot;' + selectedProposal.reviews[0].comment + '&quot;'"></p>
                        </div>
                    </template>

                    
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-list-ol text-gray-400 dark:text-gray-500"></i> Daftar Butir Soal
                        </h3>
                        <div class="space-y-4">
                            <template x-for="eq in selectedProposal.exam_questions" :key="eq.id">
                                <div
                                    class="rounded-xl border-l-4 border-l-indigo-500 border-y border-r border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:bg-[#1e293b] dark:border-y-gray-700/50 dark:border-r-gray-700/50">
                                    <div
                                        class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4 border-b border-gray-100 pb-4 dark:border-gray-700">
                                        <strong class="text-gray-900 dark:text-gray-100 text-lg"
                                            x-text="'Soal No. ' + eq.order_no"></strong>
                                        <div class="flex flex-wrap gap-2">
                                            <span
                                                class="inline-flex items-center rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 border border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/30">
                                                <i class="fa-solid fa-bullseye mr-1.5 opacity-70"></i> CPMK: <span
                                                    x-text="eq.question.cpmk_id"></span>
                                            </span>
                                            <span
                                                class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700 border border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                                                <i class="fa-solid fa-weight-hanging mr-1.5 opacity-70"></i> Bobot:
                                                <span x-text="eq.weight"></span>%
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed"
                                        x-text="eq.question.question_text"></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    
                    <template
                        x-if="userId === selectedProposal.created_by && ['SUBMITTED', 'REVISED'].includes(selectedProposal.status)">
                        <div
                            class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-6 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4 dark:bg-[#1e293b] dark:border-gray-700/50">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100">Kelola Pengajuan</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Anda dapat mengubah butir soal
                                    atau membatalkan pengajuan ini.</p>
                            </div>
                            <div class="flex gap-3">
                                <?php if(Auth::user()->hasPermission(3, 'U')): ?>
                                <button type="button" @click="openEditModal()"
                                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 dark:hover:bg-blue-500">
                                    <i class="fas fa-edit"></i> Edit Pengajuan
                                </button>
                                <?php endif; ?>
                                <?php if(Auth::user()->hasPermission(3, 'D')): ?>
                                <button type="button" @click="openDelete = true"
                                    class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-red-600 dark:hover:bg-red-500">
                                    <i class="fas fa-trash-alt"></i> Batalkan
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </template>

                    
                    <template x-if="isReviewer && selectedProposal.status === 'SUBMITTED'">
                        <div
                            class="mt-10 rounded-2xl border-2 border-dashed border-indigo-200 bg-indigo-50/50 p-8 text-center shadow-sm dark:bg-indigo-900/10 dark:border-indigo-500/30">
                            <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Validasi Kaprodi</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Silakan baca soal dengan saksama.
                                Setujui untuk diujikan atau minta revisi.</p>
                            <div class="flex justify-center gap-4">
                                <button @click="openApprove = true; setTimeout(() => { initAlpineCanvas(); }, 200);"
                                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-8 py-3 text-sm font-bold text-white shadow-md hover:bg-green-700 dark:hover:bg-green-500">
                                    <i class="fas fa-check-circle"></i> Setujui & TTD
                                </button>
                                <button @click="openRevise = true"
                                    class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-8 py-3 text-sm font-bold text-white shadow-md hover:bg-red-600 dark:hover:bg-red-500">
                                    <i class="fas fa-times-circle"></i> Minta Revisi
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        
        <div
            class="shrink-0 border-t border-gray-200 bg-gray-50 px-6 py-4 z-10 dark:bg-[#1e293b] dark:border-gray-700 shadow-inner flex justify-between items-center">

            
            <div>
                <template x-if="selectedProposal && selectedProposal.status === 'APPROVED'">
                    <?php if(Auth::user()->hasPermission(3, 'E')): ?>
                    <a :href="'<?php echo e(url('monev-akademik/tashih/print')); ?>/' + selectedProposal.uuid" target="_blank"
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-indigo-700 transition focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900">
                        <i class="fas fa-print"></i> Cetak Kertas Ujian
                    </a>
                    <?php endif; ?>
                </template>
            </div>

            <button @click="openDetail = false" type="button"
                class="inline-flex justify-center items-center gap-2 rounded-lg bg-yellow-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>

    </div>

    
    <div x-show="openDelete" class="fixed inset-0 z-[999995] flex items-center justify-center bg-black/60 p-4" x-cloak
        style="display: none;">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 text-center shadow-2xl dark:bg-[#1e293b]">
            <div
                class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 mb-4 dark:bg-red-500/20">
                <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 dark:text-red-400"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Batalkan Pengajuan?</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Pengajuan ini akan dihapus permanen.</p>
            <form
                :action="'<?php echo e(url('monev-akademik/tashih/destroy')); ?>/' + (selectedProposal ? selectedProposal.uuid : '')"
                method="POST" class="flex justify-center gap-3">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="button" @click="openDelete = false"
                    class="rounded-lg bg-gray-100 px-5 py-2 text-sm font-bold text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition">Batal</button>
                <button type="submit"
                    class="rounded-lg bg-red-600 px-5 py-2 text-sm font-bold text-white hover:bg-red-700 transition">Ya,
                    Hapus</button>
            </form>
        </div>
    </div>

    
    <div x-show="openRevise" class="fixed inset-0 z-[999995] flex items-center justify-center bg-black/60 p-4" x-cloak
        style="display: none;">
        <div class="w-full max-w-lg rounded-2xl bg-white p-8 shadow-2xl dark:bg-[#1e293b]">
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-500 mr-2"></i> Kirim Catatan Revisi
            </h3>
            <form
                :action="'<?php echo e(url('monev-akademik/tashih')); ?>/' + (selectedProposal ? selectedProposal.uuid : '') + '/revise'"
                method="POST">
                <?php echo csrf_field(); ?>
                <textarea name="comment" rows="5"
                    class="w-full rounded-lg border-gray-300 bg-gray-50 px-5 py-3 mb-6 outline-none focus:border-red-500 dark:bg-[#0f172a] dark:border-gray-700 dark:text-white dark:focus:border-red-500 transition"
                    required placeholder="Instruksi revisi..."></textarea>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="openRevise = false"
                        class="rounded-lg bg-gray-100 px-5 py-2 text-sm font-bold text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition">Batal</button>
                    <button type="submit"
                        class="rounded-lg bg-red-600 px-6 py-2 text-sm font-bold text-white hover:bg-red-700 transition">Kirim
                        Revisi</button>
                </div>
            </form>
        </div>
    </div>

    
    <div x-show="openApprove" class="fixed inset-0 z-[999995] flex items-center justify-center bg-black/60 p-4" x-cloak
        style="display: none;">
        <div class="w-full max-w-lg rounded-2xl bg-white p-8 shadow-2xl dark:bg-[#1e293b]">
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fa-solid fa-file-signature text-green-500 mr-2"></i> Persetujuan Pengajuan
            </h3>
            <form
                :action="'<?php echo e(url('monev-akademik/tashih')); ?>/' + (selectedProposal ? selectedProposal.uuid : '') + '/approve'"
                method="POST" id="approveForm">
                <?php echo csrf_field(); ?>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Anda memvalidasi bahwa seluruh butir soal ini
                    telah sesuai standar.</p>

                <?php if(empty(Auth::user()->signature)): ?>
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-red-600 dark:text-red-400 mb-2">Tanda Tangan Digital
                            Anda</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 overflow-hidden relative dark:bg-[#0f172a] dark:border-gray-700"
                            style="touch-action: none;">
                            <canvas id="alpineSignaturePad" class="w-full h-[200px] cursor-crosshair bg-white"></canvas>
                        </div>
                        <div class="text-center mt-2">
                            <button type="button"
                                class="text-xs font-bold text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 underline transition"
                                onclick="clearAlpineCanvas()">Hapus & Ulangi</button>
                        </div>
                        <input type="hidden" name="signature_base64" id="alpine_signature_base64" required>
                    </div>
                <?php else: ?>
                    <div
                        class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-center mb-6 dark:bg-blue-500/10 dark:border-blue-500/30">
                        <p class="text-sm font-bold text-blue-800 dark:text-blue-400">Tanda Tangan Tersimpan di Sistem</p>
                    </div>
                <?php endif; ?>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="openApprove = false"
                        class="rounded-lg bg-gray-100 px-5 py-2 text-sm font-bold text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition">Batal</button>
                    <button type="submit" onclick="prepareAlpineSignature()"
                        class="rounded-lg bg-green-600 px-6 py-2 text-sm font-bold text-white hover:bg-green-700 transition">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php if(empty(Auth::user()->signature)): ?>
    <script>
        let aCanvas, aCtx, aIsDrawing = false;

        function initAlpineCanvas() {
            aCanvas = document.getElementById('alpineSignaturePad');
            if (!aCanvas) return;

            aCtx = aCanvas.getContext('2d');
            const rect = aCanvas.parentElement.getBoundingClientRect();
            aCanvas.width = rect.width;
            aCanvas.height = 200;

            aCtx.fillStyle = "white";
            aCtx.fillRect(0, 0, aCanvas.width, aCanvas.height);

            aCanvas.onmousedown = (e) => { aIsDrawing = true; aCtx.beginPath(); aCtx.moveTo(e.offsetX, e.offsetY); };
            aCanvas.onmousemove = (e) => { if (aIsDrawing) { aCtx.lineWidth = 3; aCtx.lineCap = 'round'; aCtx.strokeStyle = '#0f172a'; aCtx.lineTo(e.offsetX, e.offsetY); aCtx.stroke(); } };
            aCanvas.onmouseup = () => aIsDrawing = false;
            aCanvas.onmouseout = () => aIsDrawing = false;

            aCanvas.addEventListener('touchstart', (e) => { e.preventDefault(); const t = e.touches[0]; const r = aCanvas.getBoundingClientRect(); aIsDrawing = true; aCtx.beginPath(); aCtx.moveTo(t.clientX - r.left, t.clientY - r.top); }, { passive: false });
            aCanvas.addEventListener('touchmove', (e) => { e.preventDefault(); if (aIsDrawing) { const t = e.touches[0]; const r = aCanvas.getBoundingClientRect(); aCtx.lineWidth = 3; aCtx.lineCap = 'round'; aCtx.lineTo(t.clientX - r.left, t.clientY - r.top); aCtx.stroke(); } }, { passive: false });
            aCanvas.addEventListener('touchend', () => aIsDrawing = false);
        }

        function clearAlpineCanvas() {
            aCtx.clearRect(0, 0, aCanvas.width, aCanvas.height);
            aCtx.fillStyle = "white";
            aCtx.fillRect(0, 0, aCanvas.width, aCanvas.height);
            document.getElementById('alpine_signature_base64').value = "";
        }
        function prepareAlpineSignature() { if (aCanvas) document.getElementById('alpine_signature_base64').value = aCanvas.toDataURL('image/png'); }
    </script>
<?php endif; ?><?php /**PATH C:\laragon\www\sainteku\Modules/MonevAkademik\resources/views/tashih/partials/modal-detail.blade.php ENDPATH**/ ?>