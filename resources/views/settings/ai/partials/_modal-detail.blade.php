<template x-teleport="#modal-root">
    <div x-show="openDetail"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center p-3 sm:p-6 overflow-y-auto backdrop-blur-sm bg-gray-900/40"
    x-transition x-cloak>
    <div @click.away="openDetail=false"
        class="relative w-full max-w-4xl rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[95vh] overflow-hidden">

        {{-- Header --}}
        <div class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
            <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-brain text-indigo-500"></i>
                <span x-text="detail.name"></span>
            </h3>
            <button type="button" @click="openDetail=false" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">

            {{-- Status Badges --}}
            <div class="flex flex-wrap gap-2">
                <span x-show="detail.is_default"
                    class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold uppercase text-amber-800 border border-amber-200">
                    <i class="fa-solid fa-star"></i> Default
                </span>
                <span :class="detail.is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-700 border-gray-200'"
                    class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase border">
                    <span x-text="detail.is_active ? 'AKTIF' : 'NONAKTIF'"></span>
                </span>
            </div>

            {{-- Provider Info --}}
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-3">Provider & Model</h4>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500 uppercase font-semibold">Provider</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white font-medium" x-text="detail.provider_label"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase font-semibold">Model</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white font-medium" x-text="detail.model"></dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs text-gray-500 uppercase font-semibold">API Endpoint</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white font-mono text-xs break-all" x-text="detail.api_endpoint"></dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs text-gray-500 uppercase font-semibold">API Key</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white font-mono text-xs" x-text="detail.masked_api_key || '—'"></dd>
                    </div>
                </dl>
            </div>

            {{-- Model Parameters --}}
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-3">Parameter Model</h4>
                <dl class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500 uppercase font-semibold">Temperature</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white font-medium" x-text="detail.temperature"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase font-semibold">Max Tokens</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white font-medium" x-text="detail.max_tokens"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase font-semibold">Top P</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white font-medium" x-text="detail.top_p"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase font-semibold">Priority</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white font-medium" x-text="detail.priority"></dd>
                    </div>
                </dl>
            </div>

            {{-- Usage Stats --}}
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-3">Statistik Penggunaan</h4>
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500 uppercase font-semibold">Daily Usage</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white font-medium">
                            <span x-text="detail.daily_used"></span> /
                            <span x-text="detail.daily_limit > 0 ? detail.daily_limit : 'Unlimited'"></span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase font-semibold">Total Cost</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white font-medium">
                            $<span x-text="parseFloat(detail.total_cost || 0).toFixed(2)"></span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase font-semibold">Last Used</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white font-medium text-xs" x-text="detail.last_used_at || '—'"></dd>
                    </div>
                </dl>
            </div>

            {{-- Notes --}}
            <div x-show="detail.notes" class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Catatan</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400" x-text="detail.notes"></p>
            </div>

        </div>

        {{-- Footer Actions --}}
        <div class="shrink-0 flex items-center justify-between gap-3 border-t border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
            <div class="flex gap-2 flex-wrap">
                <form :action="`{{ route('settings.ai.index') }}/${detail.id}/test`" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-500 px-4 py-2 text-sm font-bold text-white hover:bg-green-600">
                        <i class="fa-solid fa-plug"></i> Test Connection
                    </button>
                </form>
                <form :action="`{{ route('settings.ai.index') }}/${detail.id}/set-default`" method="POST" class="inline">
                    @csrf
                    <button type="submit" x-show="!detail.is_default"
                        class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-bold text-white hover:bg-amber-600">
                        <i class="fa-solid fa-star"></i> Set Default
                    </button>
                </form>
                <form :action="`{{ route('settings.ai.index') }}/${detail.id}/reset-usage`" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-sm font-bold text-white hover:bg-blue-600">
                        <i class="fa-solid fa-rotate"></i> Reset Usage
                    </button>
                </form>
            </div>
            <div class="flex gap-2 flex-wrap">
                <button type="button" @click="openEditModal(detail)"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700">
                    <i class="fa-solid fa-pen"></i> Edit
                </button>
                <form :action="`{{ route('settings.ai.index') }}/${detail.id}`" method="POST" class="inline"
                    onsubmit="return confirm('Yakin ingin menghapus konfigurasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-bold text-white hover:bg-red-700">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</template>