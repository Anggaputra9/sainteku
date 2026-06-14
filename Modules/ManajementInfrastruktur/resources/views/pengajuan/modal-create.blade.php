{{-- MODAL TAMBAH PENGAJUAN INFRASTRUKTUR (Desain Premium & Kalender Visual) --}}
<template x-teleport="#modal-root">
    <div x-show="openCreate"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center bg-black/60 p-4 overflow-y-auto backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openCreate = false" x-show="openCreate"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="flex flex-col w-full max-w-4xl max-h-[90vh] rounded-2xl bg-white shadow-2xl dark:bg-gray-800 overflow-hidden">

        {{-- Header Modal --}}
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Formulir Peminjaman Fasilitas</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Silakan lengkapi data peminjaman di bawah ini untuk <span class="font-semibold text-blue-600 dark:text-blue-400">Sainteku</span></p>
            </div>
        </div>

        {{-- Form Area --}}
        <form action="{{ route('manajementinfrastruktur.pengajuan.store') }}" method="POST" class="flex flex-col flex-1 min-h-0">
            @csrf

            <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                
                {{-- Barang / Ruangan --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Barang / Ruangan <span class="text-red-500">*</span>
                    </label>
                    <select name="inventory_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Fasilitas --</option>
                        @foreach($inventories as $inv)
                            <option value="{{ $inv->id }}">{{ $inv->item_name }} (Tersedia: {{ $inv->stock }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Jumlah Pinjam --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Jumlah Pinjam <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="quantity" required min="1" value="1"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                </div>

                {{-- Area Tanggal (Box Khusus Identik) --}}
                <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 md:col-span-2">
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-widest text-gray-400 italic">Jadwal Penggunaan</h4>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        {{-- Waktu Mulai --}}
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Waktu Mulai</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-regular fa-calendar"></i></span>
                                <input type="text" name="start_date" required placeholder="Pilih Waktu Mulai..." 
                                    x-init="flatpickr($el, { enableTime: true, dateFormat: 'Y-m-d H:i', altInput: true, altFormat: 'd F Y, H:i', allowInput: false, static: true })"
                                    class="w-full rounded-lg border-0 py-2.5 pl-10 pr-4 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 dark:text-white dark:ring-gray-600 cursor-pointer">
                            </div>
                        </div>

                        {{-- Waktu Selesai --}}
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Waktu Selesai</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-regular fa-calendar"></i></span>
                                <input type="text" name="end_date" required placeholder="Pilih Waktu Selesai..." 
                                    x-init="flatpickr($el, { enableTime: true, dateFormat: 'Y-m-d H:i', altInput: true, altFormat: 'd F Y, H:i', allowInput: false, static: true })"
                                    class="w-full rounded-lg border-0 py-2.5 pl-10 pr-4 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 dark:text-white dark:ring-gray-600 cursor-pointer">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Tujuan / Keterangan Kegiatan --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tujuan / Keterangan Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="purpose" required rows="3"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Contoh: Rapat Koordinasi BEM di Ruang Rapat Lt. 2..."></textarea>
                </div>

            </div>
            </div>

            {{-- Tombol Aksi Bawah --}}
            <div class="shrink-0 flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800/80 sm:flex-row sm:justify-end">
                <button type="button" @click="openCreate = false"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    <i class="fas fa-xmark"></i>
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fa-solid fa-paper-plane"></i>
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
</template>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #475569;
    }
</style>