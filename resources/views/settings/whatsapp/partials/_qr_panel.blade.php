<template x-teleport="#modal-root">
    <div x-show="openQrPanel"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center p-3 sm:p-6 overflow-y-auto backdrop-blur-sm bg-gray-900/40"
        x-transition x-cloak @keydown.escape.window="closeQr()">
        <div @click.away="closeQr()"
            class="relative w-full max-w-md rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[95vh] overflow-hidden">

            {{-- Header --}}
            <div class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <div class="min-w-0 pr-4">
                    <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-qrcode text-indigo-500 shrink-0"></i>
                        <span class="truncate" x-text="'Pairing: ' + qrLabel"></span>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        WhatsApp → Perangkat Tertaut → Tautkan perangkat
                    </p>
                </div>
                <button type="button" @click="closeQr()" class="shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar p-6 bg-slate-50 dark:bg-[#0f172a]">
                <div class="mx-auto max-w-[300px] text-center">
                    <template x-if="qrImage">
                        <div>
                            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-600 dark:bg-white">
                                <img :src="'data:image/png;base64,' + qrImage" alt="QR Code WhatsApp"
                                    class="mx-auto h-auto w-full aspect-square object-contain"
                                    style="max-width: 280px;">
                            </div>
                            <p class="mt-3 text-[10px] text-gray-500 dark:text-gray-400">
                                Scan dalam ~2 menit sebelum QR kedaluwarsa · refresh otomatis 5 detik
                            </p>
                        </div>
                    </template>
                    <template x-if="!qrImage && !qrError">
                        <div class="flex flex-col items-center justify-center py-16 min-h-[300px] text-gray-400">
                            <i class="fa-solid fa-spinner fa-spin text-3xl text-indigo-500"></i>
                            <p class="mt-4 text-sm font-medium text-gray-600 dark:text-gray-300"
                                x-text="qrReconnecting ? 'Memulai pairing ulang...' : 'Memuat QR...'"></p>
                        </div>
                    </template>
                    <template x-if="qrError">
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-6 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300 min-h-[200px] flex flex-col items-center justify-center gap-3">
                            <p class="font-semibold text-center" x-text="qrError"></p>
                            <button type="button" @click="reconnectSession().then(() => fetchQr())"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700">
                                <i class="fa-solid fa-rotate"></i> Scan Ulang
                            </button>
                        </div>
                    </template>

                    <div class="mt-5 inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-bold uppercase tracking-wider"
                        :class="qrStatus === 'connected'
                            ? 'border-green-200 bg-green-100 text-green-800'
                            : 'border-amber-200 bg-amber-100 text-amber-800'"
                        x-show="qrStatus">
                        <i class="fa-solid fa-circle-check" x-show="qrStatus === 'connected'"></i>
                        <i class="fa-solid fa-circle-notch fa-spin" x-show="qrStatus && qrStatus !== 'connected'"></i>
                        <span x-text="qrStatus"></span>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="shrink-0 flex items-center justify-end gap-3 border-t border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <button type="button" @click="closeQr()"
                    class="rounded-lg bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</template>