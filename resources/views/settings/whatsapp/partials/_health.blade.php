<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status Service</p>
        <p class="mt-2 text-lg font-bold flex items-center gap-2"
            :class="healthIsUp ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
            <i class="fa-solid" :class="healthIsUp ? 'fa-circle-check' : 'fa-circle-xmark'"></i>
            <span x-text="healthIsUp ? 'Online' : 'Offline'"></span>
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Session Connected</p>
        <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white">
            <span x-text="health.sessions_connected ?? 0"></span><span class="text-sm font-medium text-gray-500"> / <span x-text="health.sessions_total ?? 0"></span></span>
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Uptime</p>
        <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white font-mono" x-text="displayUptime"></p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 flex items-center justify-between gap-3">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Antrian Pesan</p>
            <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white" x-text="health.queue_pending ?? 0"></p>
        </div>
        <div class="flex flex-col items-end gap-1">
            <button type="button" @click="refreshPageData()" :disabled="pageRefreshing"
                class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 transition shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 disabled:opacity-60">
                <i class="fa-solid fa-rotate text-indigo-500" :class="pageRefreshing && 'animate-spin'"></i>
                <span x-text="pageRefreshing ? 'Memuat…' : 'Refresh'"></span>
            </button>
            <span class="text-[10px] text-gray-400 dark:text-gray-500 flex items-center gap-1" x-show="liveClock">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                <span x-text="'Live · ' + liveClock"></span>
            </span>
        </div>
    </div>
</div>

<div x-show="!healthIsUp" x-cloak
    class="flex items-start w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg text-sm">
    <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl mr-3 mt-0.5"></i>
    <div class="text-red-700 dark:text-red-400">
        <p class="font-bold">Whatsar tidak terjangkau</p>
        <p class="mt-1 text-xs opacity-90">Jalankan <code class="bg-red-100 dark:bg-red-900/30 px-1 rounded">sudo bash scripts/whatsar-install.sh</code> atau cek <code class="bg-red-100 dark:bg-red-900/30 px-1 rounded">systemctl status whatsar</code></p>
    </div>
</div>

<div x-show="healthIsUp && (health.sessions_connected ?? 0) === 0" x-cloak
    class="flex items-start w-full border-l-4 border-amber-500 bg-amber-50 p-4 shadow-sm dark:bg-gray-800 dark:border-amber-400 rounded-r-lg text-sm">
    <i class="fa-solid fa-qrcode text-amber-500 text-xl mr-3 mt-0.5"></i>
    <div class="text-amber-800 dark:text-amber-300">
        <p class="font-bold">Belum ada session yang terhubung</p>
        <p class="mt-1 text-xs opacity-90">Klik <strong>Tambah Session</strong>, lalu scan QR dari WhatsApp di HP Anda.</p>
    </div>
</div>