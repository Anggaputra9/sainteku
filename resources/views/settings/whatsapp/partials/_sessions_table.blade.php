<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-mobile-screen text-indigo-500"></i> Daftar Session WhatsApp
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-4 font-semibold">Session</th>
                    <th class="px-6 py-4 font-semibold text-center">Nomor WA</th>
                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                    <th class="px-6 py-4 font-semibold text-center">Dibuat</th>
                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <template x-if="sessions.length === 0">
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fa-brands fa-whatsapp text-3xl mb-3 opacity-50"></i><br>
                            Belum ada session. Klik <strong>Tambah Session</strong> untuk mulai.
                        </td>
                    </tr>
                </template>
                <template x-for="session in sessions" :key="session.id">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-white text-base" x-text="session.name || '-'"></div>
                            <div class="text-xs text-gray-500 mt-1 font-mono">
                                ID: <span x-text="truncateId(session.id)"></span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center font-semibold text-gray-700 dark:text-gray-300"
                            x-text="session.phone || '—'"></td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border"
                                :class="sessionStatusClass(session)"
                                x-text="sessionStatusLabel(session)"></span>
                        </td>

                        <td class="px-6 py-4 text-center text-xs text-gray-500 dark:text-gray-400"
                            x-text="formatDate(session.created_at)"></td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <template x-if="!session.connected">
                                    <button type="button"
                                        @click="openQr(session.id, session.name || 'Session')"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 transition shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                        <i class="fa-solid fa-qrcode text-indigo-500"></i>
                                        <span x-text="qrButtonLabel(session)"></span>
                                    </button>
                                </template>
                                <button type="button"
                                    @click="confirmDeleteSession(session.id, session.name || 'Session', session.phone || null)"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 border border-red-200 hover:bg-red-100 transition shadow-sm dark:bg-red-900/20 dark:text-red-400 dark:border-red-800">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>