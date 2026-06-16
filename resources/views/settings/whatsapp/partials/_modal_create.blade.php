<template x-teleport="#modal-root">
    <div x-show="openCreate"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center p-3 sm:p-6 overflow-y-auto backdrop-blur-sm bg-gray-900/40"
        x-transition x-cloak @keydown.escape.window="openCreate = false">
        <div @click.away="openCreate = false"
            class="relative w-full max-w-md rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[95vh] overflow-hidden">

            {{-- Header --}}
            <div class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-indigo-500"></i>
                    Tambah Session Baru
                </h3>
                <button type="button" @click="openCreate = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('settings.whatsapp.sessions.store') }}" class="flex-1 flex flex-col overflow-hidden">
                @csrf

                <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Nama Session *</label>
                        <input type="text" name="name" required pattern="[a-zA-Z0-9_-]+" maxlength="64"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white"
                            placeholder="notif-sainteku">
                        <p class="mt-2 text-[10px] text-gray-500">Huruf, angka, strip, underscore. Contoh: <code>notif-kampus</code></p>
                    </div>

                    <div class="rounded-xl border border-indigo-200 bg-indigo-50/40 p-4 text-xs text-indigo-800 dark:border-indigo-900/40 dark:bg-indigo-900/10 dark:text-indigo-200">
                        <p class="font-bold flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-qrcode"></i> Setelah session dibuat
                        </p>
                        <p>Modal QR akan terbuka otomatis. Scan dari WhatsApp di HP Anda (Perangkat Tertaut → Tautkan perangkat).</p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="shrink-0 flex items-center justify-end gap-3 border-t border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <button type="button" @click="openCreate = false"
                        class="rounded-lg bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">
                        Buat & Tampilkan QR
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>