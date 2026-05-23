@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="emailSettingsApp()" x-init="init()" x-cloak>

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-envelope-open-text text-indigo-500"></i> Pengaturan Email
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Pengaturan Aplikasi /</li>
                        <li class="text-indigo-600 dark:text-indigo-400">Email / Mailer</li>
                    </ol>
                </nav>
            </div>
            <button type="button" @click="openCreate = true; resetForm()"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-indigo-700 transition">
                <i class="fa-solid fa-plus"></i> Tambah Konfigurasi
            </button>
        </div>

        {{-- ================= INFO PROVIDER ================= --}}
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-200">
            <p class="font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-info"></i> Daftar provider yang didukung & limit gratis (referensi):
            </p>
            <ul class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1.5 text-xs">
                @foreach($providers as $key => $info)
                    <li>
                        <span class="font-bold uppercase">{{ $info['label'] }}</span>
                        <span class="opacity-80">— {{ $info['note'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- ================= FILTERS ================= --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, email pengirim, provider..."
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
                        <option value="active" @selected(request('status')==='active')>Aktif</option>
                        <option value="inactive" @selected(request('status')==='inactive')>Nonaktif</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">Filter</button>
                    <a href="{{ route('admin.email-settings.index') }}" class="flex-1 inline-flex justify-center rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Reset</a>
                </div>
            </form>
        </div>

        {{-- ================= ALERTS ================= --}}
        @if (session('success'))
            <div class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-sm font-bold text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                <i class="fa-solid fa-circle-xmark text-red-500 text-xl mr-3"></i>
                <p class="text-sm font-bold text-red-700 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="flex items-start w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl mr-3 mt-0.5"></i>
                <div class="text-sm font-bold text-red-700 dark:text-red-400">
                    @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            </div>
        @endif

        {{-- ================= TABLE ================= --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Konfigurasi</th>
                            <th class="px-6 py-4 font-semibold">Provider</th>
                            <th class="px-6 py-4 font-semibold">Mode</th>
                            <th class="px-6 py-4 font-semibold">Pengirim</th>
                            <th class="px-6 py-4 font-semibold text-center">Limit Harian</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse($settings as $row)
                            <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        @if($row->is_default)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-800 border border-amber-200">
                                                <i class="fa-solid fa-star"></i> Default
                                            </span>
                                        @endif
                                        {{ $row->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($row->auth_mode === 'api' && $row->api_key)
                                            <span title="API Key (masked)"><i class="fa-solid fa-key"></i> {{ $row->masked_api_key }}</span>
                                        @elseif($row->host)
                                            <span><i class="fa-solid fa-server"></i> {{ $row->host }}:{{ $row->port }}</span>
                                        @else
                                            <span class="italic">Belum dikonfigurasi</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-md bg-indigo-50 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-indigo-700 border border-indigo-200">
                                        {{ $providers[$row->provider]['label'] ?? $row->provider }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($row->auth_mode === 'api')
                                        <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-key"></i> API Key
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-md bg-sky-50 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-sky-700 border border-sky-200">
                                            <i class="fa-solid fa-server"></i> SMTP
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 dark:text-white font-medium">{{ $row->from_name ?: '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $row->from_email ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($row->daily_limit > 0)
                                        <div class="font-bold text-gray-900 dark:text-white">
                                            {{ $row->daily_sent }} / {{ $row->daily_limit }}
                                        </div>
                                        @php
                                            $pct = min(100, round(($row->daily_sent / max(1,$row->daily_limit))*100));
                                            $barColor = $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-emerald-500');
                                        @endphp
                                        <div class="mt-1 h-1.5 w-24 mx-auto bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700">
                                            <div class="h-full {{ $barColor }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                    @else
                                        <span class="text-xs italic text-gray-400">unlimited</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($row->is_active)
                                        <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border bg-green-100 text-green-800 border-green-200">Aktif</span>
                                    @else
                                        <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border bg-gray-100 text-gray-700 border-gray-200">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2 flex-wrap">
                                        <button type="button" @click='openEditModal(@json($row))'
                                            class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 border border-blue-200 hover:bg-blue-100">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </button>
                                        <button type="button" @click='openTestModal(@json($row))'
                                            class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 border border-emerald-200 hover:bg-emerald-100">
                                            <i class="fa-solid fa-paper-plane"></i> Tes
                                        </button>
                                        @if(!$row->is_default)
                                            <form method="POST" action="{{ route('admin.email-settings.set-default', $row) }}">
                                                @csrf
                                                <button class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700 border border-amber-200 hover:bg-amber-100">
                                                    <i class="fa-solid fa-star"></i> Default
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.email-settings.destroy', $row) }}" onsubmit="return confirm('Hapus konfigurasi ini?')">
                                            @csrf @method('DELETE')
                                            <button class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 border border-red-200 hover:bg-red-100">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fa-solid fa-envelope-open text-3xl mb-3 opacity-50"></i><br>
                                    Belum ada konfigurasi email. Klik "Tambah Konfigurasi" untuk mulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- pagination --}}
        @if($settings->hasPages())
            <div class="px-2">{{ $settings->links() }}</div>
        @endif

        {{-- ================= MODAL CREATE / EDIT ================= --}}
        <div x-show="openCreate || openEdit"
            class="fixed inset-0 z-[999990] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
            x-transition x-cloak>
            <div @click.away="openCreate=false; openEdit=false"
                class="relative w-full max-w-3xl rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[95vh] overflow-hidden">
                <div class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-envelope-open-text text-indigo-500"></i>
                        <span x-text="openEdit ? 'Edit Konfigurasi Email' : 'Tambah Konfigurasi Email'"></span>
                    </h3>
                    <button type="button" @click="openCreate=false; openEdit=false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form :method="'POST'" :action="formAction" class="flex-1 flex flex-col overflow-hidden">
                    @csrf
                    <template x-if="openEdit"><input type="hidden" name="_method" value="PUT"></template>

                    <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a]">

                        {{-- Provider & Nama --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Nama Konfigurasi *</label>
                                <input name="name" x-model="form.name" required maxlength="100"
                                    placeholder="Contoh: Brevo Utama"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Provider *</label>
                                <select name="provider" x-model="form.provider" @change="onProviderChange()" required
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                    <option value="">-- Pilih Provider --</option>
                                    @foreach($providers as $key => $info)
                                        <option value="{{ $key }}">{{ $info['label'] }} @if($info['free_limit']) (~{{ $info['free_limit'] }}/hari free) @endif</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Mode Auth Selector (segmented control) --}}
                        <div x-show="form.provider"
                            class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase">Mode Autentikasi *</label>
                                <span class="text-[10px] text-gray-400">Pilih cara konek ke provider</span>
                            </div>
                            <input type="hidden" name="auth_mode" :value="form.auth_mode">
                            <div class="grid grid-cols-2 gap-2 p-1 rounded-xl bg-gray-100 dark:bg-gray-800">
                                <button type="button" @click="setAuthMode('smtp')"
                                    :disabled="!supportsMode('smtp')"
                                    :class="form.auth_mode === 'smtp'
                                        ? 'bg-white text-sky-700 shadow-sm dark:bg-sky-500/20 dark:text-sky-300'
                                        : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 disabled:opacity-40 disabled:cursor-not-allowed'"
                                    class="flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold transition-all">
                                    <i class="fa-solid fa-server"></i> SMTP
                                </button>
                                <button type="button" @click="setAuthMode('api')"
                                    :disabled="!supportsMode('api')"
                                    :class="form.auth_mode === 'api'
                                        ? 'bg-white text-emerald-700 shadow-sm dark:bg-emerald-500/20 dark:text-emerald-300'
                                        : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 disabled:opacity-40 disabled:cursor-not-allowed'"
                                    class="flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold transition-all">
                                    <i class="fa-solid fa-key"></i> API Key
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-gray-500" x-text="modeDescription"></p>
                        </div>

                        {{-- From --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">From Email</label>
                                <input type="email" name="from_email" x-model="form.from_email" maxlength="150"
                                    placeholder="noreply@uinsaizu.ac.id"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">From Name</label>
                                <input name="from_name" x-model="form.from_name" maxlength="150"
                                    placeholder="UIN Saifuddin Zuhri"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                        </div>

                        {{-- ================= SMTP FIELDS (mode SMTP) ================= --}}
                        <div x-show="form.provider && form.auth_mode === 'smtp'"
                            class="rounded-xl border border-sky-200 bg-sky-50/40 p-4 dark:border-sky-900/40 dark:bg-sky-900/10">
                            <h4 class="text-sm font-bold text-sky-700 dark:text-sky-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-server"></i> Konfigurasi SMTP
                            </h4>
                            <p class="text-[11px] text-gray-500 mb-3" x-text="`Preset diisi otomatis dari dokumentasi resmi ${currentProviderLabel}.`"></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Host *</label>
                                    <input name="host" x-model="form.host" placeholder="smtp-relay.brevo.com"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Port *</label>
                                    <input type="number" name="port" x-model="form.port" placeholder="587"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Encryption</label>
                                    <select name="encryption" x-model="form.encryption"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                        <option value="tls">TLS</option>
                                        <option value="ssl">SSL</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Username</label>
                                    <input name="username" x-model="form.username"
                                        :placeholder="currentSmtp?.username_hint || 'username'"
                                        :readonly="!!currentSmtp?.username_fixed"
                                        :class="currentSmtp?.username_fixed ? 'bg-gray-100 dark:bg-gray-800' : 'bg-white dark:bg-[#1e293b]'"
                                        class="w-full rounded-xl border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:text-white">
                                    <p class="mt-1 text-[10px] text-gray-500" x-show="currentSmtp?.username_hint" x-text="currentSmtp?.username_hint"></p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Password / SMTP Key
                                        <span x-show="!openEdit" class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password" x-model="form.password"
                                        :placeholder="openEdit ? '•••• (kosongkan jika tidak diubah)' : (currentSmtp?.password_hint || '')"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                    <p class="mt-1 text-[10px] text-gray-500" x-show="currentSmtp?.password_hint" x-text="currentSmtp?.password_hint"></p>
                                </div>
                            </div>
                        </div>

                        {{-- ================= API FIELDS (mode API) ================= --}}
                        <div x-show="form.provider && form.auth_mode === 'api'"
                            class="rounded-xl border border-emerald-200 bg-emerald-50/40 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/10">
                            <h4 class="text-sm font-bold text-emerald-700 dark:text-emerald-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-key"></i> Konfigurasi API Key
                            </h4>
                            <p class="text-[11px] text-gray-500 mb-3" x-text="`Endpoint: ${currentApi?.endpoint || '-'}`"></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">API Key
                                        <span x-show="!openEdit" class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="api_key" x-model="form.api_key"
                                        :placeholder="openEdit ? '•••• (kosongkan jika tidak diubah)' : (currentApi?.key_hint || 'API Key')"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                    <p class="mt-1 text-[10px] text-gray-500" x-show="currentApi?.key_hint" x-text="currentApi?.key_hint"></p>
                                </div>
                                <div x-show="currentApi?.need_domain" class="md:col-span-2">
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Mailgun Domain *</label>
                                    <input name="api_domain" x-model="form.api_domain" placeholder="mg.domain.com"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                </div>
                            </div>
                        </div>

                        {{-- ===== PENGATURAN PEMAKAIAN (table layout) ===== --}}
                        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-gray-800/40 dark:border-gray-700">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fa-solid fa-sliders text-indigo-500"></i> Pengaturan Pemakaian
                                </h4>
                            </div>
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                        <td class="px-4 py-3 w-1/3 align-middle">
                                            <div class="font-semibold text-gray-700 dark:text-gray-300">Limit Harian</div>
                                            <div class="text-xs text-gray-500 mt-0.5">Maks email per hari (0 = tanpa batas)</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" min="0" name="daily_limit" x-model="form.daily_limit"
                                                placeholder="0"
                                                class="w-full max-w-xs rounded-lg border-gray-300 bg-white px-3 py-2 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                        <td class="px-4 py-3 align-middle">
                                            <div class="font-semibold text-gray-700 dark:text-gray-300">Prioritas</div>
                                            <div class="text-xs text-gray-500 mt-0.5">Angka kecil = dipakai duluan untuk fallback</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" min="0" name="priority" x-model="form.priority"
                                                placeholder="0"
                                                class="w-full max-w-xs rounded-lg border-gray-300 bg-white px-3 py-2 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                        <td class="px-4 py-3 align-middle">
                                            <div class="font-semibold text-gray-700 dark:text-gray-300">Status Aktif</div>
                                            <div class="text-xs text-gray-500 mt-0.5">Konfigurasi bisa dipakai sistem</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="sr-only peer">
                                                <div class="relative w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                                <span class="ml-3 text-sm font-semibold" :class="form.is_active ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500'"
                                                    x-text="form.is_active ? 'Aktif' : 'Nonaktif'"></span>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                        <td class="px-4 py-3 align-middle">
                                            <div class="font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                                Jadikan Default
                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-[9px] font-bold uppercase text-amber-800 border border-amber-200">
                                                    <i class="fa-solid fa-star"></i> Utama
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">Konfigurasi yang dipakai sistem secara default</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_default" value="1" x-model="form.is_default" class="sr-only peer">
                                                <div class="relative w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                                <span class="ml-3 text-sm font-semibold" :class="form.is_default ? 'text-amber-600 dark:text-amber-400' : 'text-gray-500'"
                                                    x-text="form.is_default ? 'Ya, default' : 'Tidak'"></span>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Catatan</label>
                            <textarea name="notes" x-model="form.notes" rows="2" maxlength="1000"
                                class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white"></textarea>
                        </div>
                    </div>

                    {{-- ================= TAMPILAN FOOTER (Modal Create/Edit) ================= --}}
                    <div class="shrink-0 flex items-center justify-end gap-3 border-t border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                        <button type="button" @click="openCreate=false; openEdit=false"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:border dark:border-gray-600 transition">
                            <i class="fa-solid fa-times"></i> Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 shadow-md transition">
                            <i class="fa-solid fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ================= MODAL TEST EMAIL ================= --}}
        <div x-show="openTest"
            class="fixed inset-0 z-[999990] flex items-center justify-center p-4 backdrop-blur-sm bg-gray-900/40"
            x-transition x-cloak>
            <div @click.away="openTest=false"
                class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl dark:bg-[#0f172a] flex flex-col">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-emerald-500"></i> Tes Pengiriman Email
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Konfigurasi: <b x-text="testTarget.name"></b></p>
                </div>
                
                <form :action="testAction" method="POST" class="flex flex-col">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Kirim ke email</label>
                            <input type="email" name="to" required placeholder="kamu@email.com"
                                class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                        </div>
                    </div>

                    {{-- ================= TAMPILAN FOOTER (Modal Test) ================= --}}
                    <div class="shrink-0 flex items-center justify-end gap-3 border-t border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700 rounded-b-2xl">
                        <button type="button" @click="openTest=false"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:border dark:border-gray-600 transition">
                            <i class="fa-solid fa-times"></i> Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 shadow-md transition">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Tes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============== STYLE: scrollbar custom (selaras dengan MonevAkademik) ============== --}}
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

    <script>
        // Preset provider dari backend (host/port/encryption resmi tiap vendor)
        const EMAIL_PROVIDERS = @json($providers);

        function emailSettingsApp() {
            return {
                openCreate: false,
                openEdit: false,
                openTest: false,
                form: {},
                formAction: '',
                testTarget: {},
                testAction: '',

                init() {
                    this.resetForm();
                },

                /** Provider terpilih (objek lengkap) */
                get currentProvider() {
                    return EMAIL_PROVIDERS[this.form.provider] || null;
                },

                get currentProviderLabel() {
                    return this.currentProvider?.label || 'provider';
                },

                /** Konfig SMTP preset untuk provider terpilih */
                get currentSmtp() {
                    return this.currentProvider?.smtp || null;
                },

                /** Konfig API preset untuk provider terpilih */
                get currentApi() {
                    return this.currentProvider?.api || null;
                },

                get modeDescription() {
                    if (!this.currentProvider) return '';
                    if (this.form.auth_mode === 'smtp') {
                        return 'Pakai protokol SMTP standar (host, port, username, password). Kompatibel dengan PHPMailer.';
                    }
                    return 'Pakai HTTP API resmi provider. Lebih cepat dan tidak butuh port SMTP terbuka.';
                },

                /** Cek apakah mode tertentu didukung provider terpilih */
                supportsMode(mode) {
                    return (this.currentProvider?.auth_modes || ['smtp']).includes(mode);
                },

                /** Set mode auth + bersihkan field yang nggak relevan */
                setAuthMode(mode) {
                    if (!this.supportsMode(mode)) return;
                    this.form.auth_mode = mode;
                    this.applyPreset();
                },

                /** Saat provider berubah: pilih default mode + isi preset */
                onProviderChange() {
                    const p = this.currentProvider;
                    if (!p) {
                        this.form.auth_mode = 'smtp';
                        return;
                    }
                    // Pilih mode default provider, atau mode pertama yang didukung
                    if (!p.auth_modes.includes(this.form.auth_mode)) {
                        this.form.auth_mode = p.default_mode || p.auth_modes[0];
                    }
                    this.applyPreset();
                },

                /** Auto-fill host/port/encryption + username fixed dari preset */
                applyPreset() {
                    const p = this.currentProvider;
                    if (!p) return;

                    if (this.form.auth_mode === 'smtp' && p.smtp) {
                        // Hanya isi kalau field masih kosong (jangan timpa input user)
                        if (!this.form.host)       this.form.host = p.smtp.host || '';
                        if (!this.form.port)       this.form.port = p.smtp.port || '';
                        if (!this.form.encryption) this.form.encryption = p.smtp.encryption || 'tls';
                        // Username yang fixed (mis. SendGrid 'apikey') selalu di-set
                        if (p.smtp.username_fixed) this.form.username = p.smtp.username_fixed;
                    }
                },

                resetForm() {
                    this.form = {
                        name: '', provider: '', auth_mode: 'smtp',
                        from_email: '', from_name: '',
                        host: '', port: '', username: '', password: '', encryption: 'tls',
                        api_key: '', api_domain: '',
                        daily_limit: 0, priority: 0,
                        is_active: true, is_default: false, notes: ''
                    };
                    this.formAction = `{{ route('admin.email-settings.store') }}`;
                },

                openEditModal(row) {
                    this.form = {
                        name: row.name || '',
                        provider: row.provider || '',
                        auth_mode: row.auth_mode || 'smtp',
                        from_email: row.from_email || '',
                        from_name: row.from_name || '',
                        host: row.host || '',
                        port: row.port || '',
                        username: row.username || '',
                        password: '',
                        encryption: row.encryption || 'tls',
                        api_key: '',
                        api_domain: row.api_domain || '',
                        daily_limit: row.daily_limit ?? 0,
                        priority: row.priority ?? 0,
                        is_active: !!row.is_active,
                        is_default: !!row.is_default,
                        notes: row.notes || ''
                    };
                    this.formAction = `{{ url('admin/email-settings') }}/${row.id}`;
                    this.openEdit = true;
                },

                openTestModal(row) {
                    this.testTarget = row;
                    this.testAction = `{{ url('admin/email-settings') }}/${row.id}/test`;
                    this.openTest = true;
                }
            }
        }
    </script>
@endsection