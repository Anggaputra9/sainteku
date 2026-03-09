<div x-data="{ openDelete: false, url: '', roleName: '' }"
    @open-delete-modal.window="openDelete = true; url = $event.detail.url; roleName = $event.detail.name"
    x-show="openDelete" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

    <div @click.away="openDelete = false"
        class="w-full max-w-[400px] transform rounded-xl bg-white p-8 text-center shadow-2xl dark:bg-gray-800 transition-all">

        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/30">
            <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
        </div>

        <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
        <p class="mb-8 text-sm text-gray-500 dark:text-gray-400">
            Apakah Anda yakin ingin menghapus role <span class="font-bold text-gray-900 dark:text-white" x-text="roleName"></span>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <div class="grid grid-cols-2 gap-3 mt-8">
            {{-- Kolom 1: Tombol Batal (Orange) --}}
            <button type="button" @click="openDelete = false"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-500 px-4 py-3 text-sm font-bold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                <i class="fas fa-arrow-left"></i>
                Batal
            </button>

            {{-- Kolom 2: Form Hapus (Merah) --}}
            <form :action="url" method="POST" class="m-0 p-0">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-3 text-sm font-bold text-white shadow-md hover:bg-red-700 transition focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900">
                    <i class="fa-solid fa-trash-can"></i>
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>