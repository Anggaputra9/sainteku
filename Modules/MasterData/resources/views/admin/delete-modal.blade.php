<div x-data="{ openDelete: false, url: '', userName: '' }"
    @open-delete-modal.window="openDelete = true; url = $event.detail.url; userName = $event.detail.name"
    x-show="openDelete"
    class="fixed inset-0 z-[999999] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/50"
    x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

    <div @click.away="openDelete = false"
        class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 overflow-hidden">

        {{-- HEADER --}}
        <div class="border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i> Konfirmasi Hapus
            </h3>
        </div>

        {{-- BODY & FOOTER --}}
        <div class="p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Apakah Anda yakin ingin menghapus user <span class="font-bold text-gray-900 dark:text-white"
                    x-text="userName"></span>? Tindakan ini tidak dapat dibatalkan.
            </p>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="openDelete = false"
                    class="rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 transition">
                    Batal
                </button>

                <form :action="url" method="POST" class="m-0 p-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-red-700 transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>