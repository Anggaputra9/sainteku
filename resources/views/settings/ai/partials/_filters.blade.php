<div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
        <div class="md:col-span-2">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pencarian</label>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Nama, provider, model..."
                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Provider</label>
            <select name="provider"
                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                <option value="">Semua</option>
                @foreach($providers as $key => $info)
                    <option value="{{ $key }}" @selected(request('provider') === $key)>{{ $info['label'] }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Status</label>
            <select name="status"
                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                <option value="">Semua</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button
                class="flex-1 inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">Filter</button>
            <a href="{{ route('settings.ai.index') }}"
                class="flex-1 inline-flex justify-center rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Reset</a>
        </div>
    </form>
</div>
