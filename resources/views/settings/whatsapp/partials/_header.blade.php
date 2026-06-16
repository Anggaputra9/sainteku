<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i class="fa-brands fa-whatsapp text-indigo-500"></i> Pengaturan WhatsApp
        </h2>
        <nav>
            <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                <li>Pengaturan Aplikasi /</li>
                <li class="text-indigo-600 dark:text-indigo-400">WhatsApp Gateway</li>
            </ol>
        </nav>
    </div>
    <button type="button" @click="openCreate = true"
        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-indigo-700 transition">
        <i class="fa-solid fa-plus"></i> Tambah Session
    </button>
</div>