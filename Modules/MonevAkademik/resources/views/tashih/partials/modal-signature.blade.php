@if(empty(Auth::user()->signature))
    {{-- Modal TTD di atas semua modal lain (teleport terakhir + z-index stacked) --}}
    <template x-teleport="#modal-root">
        <div x-show="openSignature"
            class="app-modal-overlay app-modal-overlay--stacked fixed inset-0 flex items-center justify-center p-4 sm:p-6 backdrop-blur-sm bg-gray-900/60"
            style="z-index: 10000001 !important;"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

            <div @click.away="openSignature = false"
                class="relative w-full max-w-lg transform rounded-2xl bg-white shadow-2xl dark:bg-[#1e293b] dark:border dark:border-gray-700 overflow-hidden flex flex-col max-h-[90dvh]">

                <div
                    class="shrink-0 bg-blue-600 px-5 sm:px-6 py-3 sm:py-4 flex justify-between items-center dark:bg-blue-700">
                    <h3 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-signature"></i> Tanda Tangan Digital
                    </h3>
                    <button type="button" @click="openSignature = false" class="text-white hover:text-blue-200 transition p-1">
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
                            class="w-full sm:w-1/3 inline-flex justify-center items-center gap-2 rounded-xl bg-gray-200 px-5 sm:px-6 py-2.5 sm:py-3 text-sm font-bold text-gray-700 hover:bg-gray-300 transition-all dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="button"
                            @click="submitWithSignature(selectedProposal?.uuid, '{{ url('monev-akademik/tashih') }}')"
                            class="w-full sm:w-2/3 inline-flex justify-center items-center gap-2 rounded-xl bg-blue-600 px-5 sm:px-6 py-2.5 sm:py-3 text-sm font-bold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 hover:shadow-lg focus:ring-4 focus:ring-blue-500/30 transition-all">
                            <i class="fas fa-save"></i> Simpan & Sahkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
@endif