<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-paper-plane text-indigo-500"></i> Kirim Pesan Uji
        </h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pastikan minimal satu session sudah <strong>CONNECTED</strong> sebelum menguji.</p>
    </div>

    <form method="POST" action="{{ route('settings.whatsapp.test') }}" class="p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Nomor Tujuan</label>
                <input type="text" name="phone" value="{{ auth()->user()->phone_number }}"
                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white"
                    placeholder="6281234567890" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Pesan (opsional)</label>
                <div class="flex gap-2">
                    <input type="text" name="message"
                        class="flex-1 rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white"
                        placeholder="Tes WhatsApp dari Sainteku">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-green-50 px-4 py-2.5 text-sm font-bold text-green-700 border border-green-200 hover:bg-green-100 transition shadow-sm dark:bg-green-900/20 dark:text-green-400 dark:border-green-800">
                        <i class="fa-solid fa-paper-plane"></i> Kirim
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>