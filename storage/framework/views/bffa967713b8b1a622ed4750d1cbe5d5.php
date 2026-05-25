

<?php $__env->startSection('content'); ?>
    <div class="space-y-6" x-data="emailSettingsApp()" x-init="init()" x-cloak>

        
        <div
            class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
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

        
        <div
            class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-200">
            <p class="font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-info"></i> Daftar provider yang didukung & limit gratis (referensi):
            </p>
            <ul class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1.5 text-xs">
                <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <span class="font-bold uppercase"><?php echo e($info['label']); ?></span>
                        <span class="opacity-80">— <?php echo e($info['note']); ?></span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>

        
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                <div class="md:col-span-2">
                    <label
                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pencarian</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                        placeholder="Nama, email pengirim, provider..."
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Provider</label>
                    <select name="provider"
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        <option value="">Semua</option>
                        <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(request('provider') === $key): echo 'selected'; endif; ?>><?php echo e($info['label']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Status</label>
                    <select name="status"
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        <option value="">Semua</option>
                        <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>Aktif</option>
                        <option value="inactive" <?php if(request('status') === 'inactive'): echo 'selected'; endif; ?>>Nonaktif</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button
                        class="flex-1 inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">Filter</button>
                    <a href="<?php echo e(route('settings.email.index')); ?>"
                        class="flex-1 inline-flex justify-center rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Reset</a>
                </div>
            </form>
        </div>

        
        <?php if(session('success')): ?>
            <div
                class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-sm font-bold text-green-700 dark:text-green-400"><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div
                class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                <i class="fa-solid fa-circle-xmark text-red-500 text-xl mr-3"></i>
                <p class="text-sm font-bold text-red-700 dark:text-red-400"><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div
                class="flex items-start w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl mr-3 mt-0.5"></i>
                <div class="text-sm font-bold text-red-700 dark:text-red-400">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><?php echo e($error); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        
        <div
            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4 font-semibold w-1/3">Konfigurasi</th>
                            <th class="px-6 py-4 font-semibold text-center">Provider & Mode</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php $__empty_1 = true; $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                        <?php if($row->is_default): ?>
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-800 border border-amber-200">
                                                <i class="fa-solid fa-star"></i> Default
                                            </span>
                                        <?php endif; ?>
                                        <?php echo e($row->name); ?>

                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <?php echo e($row->from_name ?: '—'); ?> &lt;<?php echo e($row->from_email ?: 'belum diisi'); ?>&gt;
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-gray-700 dark:text-gray-300">
                                        <?php echo e($providers[$row->provider]['label'] ?? $row->provider); ?>

                                    </span>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        <?php if($row->auth_mode === 'api'): ?>
                                            <i class="fa-solid fa-key"></i> API Key
                                        <?php else: ?>
                                            <i class="fa-solid fa-server"></i> SMTP
                                        <?php endif; ?>
                                        <?php if($row->daily_limit > 0): ?>
                                            · <?php echo e($row->daily_sent); ?>/<?php echo e($row->daily_limit); ?>/hari
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <?php $statusClass = $row->is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-700 border-gray-200'; ?>
                                    <span
                                        class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border <?php echo e($statusClass); ?>">
                                        <?php echo e($row->is_active ? 'AKTIF' : 'NONAKTIF'); ?>

                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <button type="button" @click='openDetailModal(<?php echo json_encode($row, 15, 512) ?>)'
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 transition shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                        <i class="fa-solid fa-eye text-blue-500"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-folder-open text-3xl mb-3 opacity-50"></i><br>
                                    Belum ada konfigurasi email.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <?php if($settings->hasPages()): ?>
            <div class="px-2"><?php echo e($settings->links()); ?></div>
        <?php endif; ?>

        
        <div x-show="openCreate || openEdit"
            class="fixed inset-0 z-[999990] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
            x-transition x-cloak>
            <div @click.away="openCreate=false; openEdit=false"
                class="relative w-full max-w-3xl rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[95vh] overflow-hidden">
                <div
                    class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-envelope-open-text text-indigo-500"></i>
                        <span x-text="openEdit ? 'Edit Konfigurasi Email' : 'Tambah Konfigurasi Email'"></span>
                    </h3>
                    <button type="button" @click="openCreate=false; openEdit=false"
                        class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form :method="'POST'" :action="formAction" class="flex-1 flex flex-col overflow-hidden">
                    <?php echo csrf_field(); ?>
                    <template x-if="openEdit"><input type="hidden" name="_method" value="PUT"></template>

                    <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a]">

                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Nama Konfigurasi
                                    *</label>
                                <input name="name" x-model="form.name" required maxlength="100"
                                    placeholder="Contoh: Brevo Utama"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Provider *</label>
                                <select name="provider" x-model="form.provider" @change="onProviderChange()" required
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                    <option value="">-- Pilih Provider --</option>
                                    <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($info['label']); ?> <?php if($info['free_limit']): ?>
                                        (~<?php echo e($info['free_limit']); ?>/hari free) <?php endif; ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        
                        <div x-show="form.provider" x-cloak
                            class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase">Mode Autentikasi
                                    *</label>
                                <span class="text-[10px] text-gray-400">Pilih cara konek ke provider</span>
                            </div>
                            <input type="hidden" name="auth_mode" :value="form.auth_mode">
                            <div class="inline-flex p-1 rounded-lg bg-gray-100 dark:bg-gray-800 gap-1">
                                <button type="button" @click="setAuthMode('smtp')" :disabled="!supportsMode('smtp')"
                                    :class="form.auth_mode === 'smtp'
                                            ? 'bg-white text-sky-700 shadow-sm dark:bg-sky-500/20 dark:text-sky-300'
                                            : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 disabled:opacity-40 disabled:cursor-not-allowed'"
                                    class="h-9 inline-flex items-center justify-center gap-2 rounded-md px-4 text-xs font-bold uppercase tracking-wide transition-all">
                                    <i class="fa-solid fa-server text-[11px]"></i> SMTP
                                </button>
                                <button type="button" @click="setAuthMode('api')" :disabled="!supportsMode('api')"
                                    :class="form.auth_mode === 'api'
                                            ? 'bg-white text-emerald-700 shadow-sm dark:bg-emerald-500/20 dark:text-emerald-300'
                                            : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 disabled:opacity-40 disabled:cursor-not-allowed'"
                                    class="h-9 inline-flex items-center justify-center gap-2 rounded-md px-4 text-xs font-bold uppercase tracking-wide transition-all">
                                    <i class="fa-solid fa-key text-[11px]"></i> API Key
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-gray-500" x-text="modeDescription"></p>
                        </div>

                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
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

                        
                        <div x-show="form.provider && form.auth_mode === 'smtp'" x-cloak
                            class="rounded-xl border border-sky-200 bg-sky-50/40 p-4 dark:border-sky-900/40 dark:bg-sky-900/10">
                            <h4 class="text-sm font-bold text-sky-700 dark:text-sky-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-server"></i> Konfigurasi SMTP
                            </h4>
                            <p class="text-[11px] text-gray-500 mb-3"
                                x-text="`Preset diisi otomatis dari dokumentasi resmi ${currentProviderLabel}.`"></p>
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
                                    <label
                                        class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Encryption</label>
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
                                    <p class="mt-1 text-[10px] text-gray-500" x-show="currentSmtp?.username_hint"
                                        x-text="currentSmtp?.username_hint"></p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Password / SMTP
                                        Key
                                        <span x-show="!openEdit" class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password" x-model="form.password"
                                        :placeholder="openEdit ? '•••• (kosongkan jika tidak diubah)' : (currentSmtp?.password_hint || '')"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                    <p class="mt-1 text-[10px] text-gray-500" x-show="currentSmtp?.password_hint"
                                        x-text="currentSmtp?.password_hint"></p>
                                </div>
                            </div>
                        </div>

                        
                        <div x-show="form.provider && form.auth_mode === 'api'" x-cloak
                            class="rounded-xl border border-emerald-200 bg-emerald-50/40 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/10">
                            <h4
                                class="text-sm font-bold text-emerald-700 dark:text-emerald-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-key"></i> Konfigurasi API Key
                            </h4>
                            <p class="text-[11px] text-gray-500 mb-3" x-text="`Endpoint: ${currentApi?.endpoint || '-'}`">
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">API Key
                                        <span x-show="!openEdit" class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="api_key" x-model="form.api_key"
                                        :placeholder="openEdit ? '•••• (kosongkan jika tidak diubah)' : (currentApi?.key_hint || 'API Key')"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                    <p class="mt-1 text-[10px] text-gray-500" x-show="currentApi?.key_hint"
                                        x-text="currentApi?.key_hint"></p>
                                </div>
                                <div x-show="currentApi?.need_domain" class="md:col-span-2">
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Mailgun Domain
                                        *</label>
                                    <input name="api_domain" x-model="form.api_domain" placeholder="mg.domain.com"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                </div>
                            </div>
                        </div>

                        
                        <div
                            class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-700 dark:bg-[#1e293b]">
                            <div
                                class="px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-gray-800/40 dark:border-gray-700">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fa-solid fa-sliders text-indigo-500"></i> Pengaturan Pemakaian
                                </h4>
                            </div>

                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                
                                <li
                                    class="px-4 py-3 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_220px] gap-3 md:gap-6 items-center">
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-700 dark:text-gray-300">Limit Harian</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Maks email per hari (0 = tanpa batas)
                                        </div>
                                    </div>
                                    <div class="md:justify-self-end w-full md:w-[220px]">
                                        <input type="number" min="0" name="daily_limit" x-model="form.daily_limit"
                                            placeholder="0"
                                            class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                    </div>
                                </li>

                                
                                <li
                                    class="px-4 py-3 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_220px] gap-3 md:gap-6 items-center">
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-700 dark:text-gray-300">Prioritas</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Angka kecil = dipakai duluan untuk
                                            fallback</div>
                                    </div>
                                    <div class="md:justify-self-end w-full md:w-[220px]">
                                        <input type="number" min="0" name="priority" x-model="form.priority" placeholder="0"
                                            class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                    </div>
                                </li>

                                
                                <li
                                    class="px-4 py-3 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_220px] gap-3 md:gap-6 items-center">
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-700 dark:text-gray-300">Status Aktif</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Konfigurasi bisa dipakai sistem</div>
                                    </div>
                                    <label
                                        class="md:justify-self-end grid grid-cols-[auto_72px] items-center gap-3 cursor-pointer select-none">
                                        <span class="relative inline-flex shrink-0">
                                            <input type="checkbox" name="is_active" value="1" x-model="form.is_active"
                                                class="sr-only peer">
                                            <span
                                                class="relative w-11 h-6 bg-gray-300 rounded-full peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></span>
                                        </span>
                                        <span class="text-sm font-semibold text-right tabular-nums"
                                            :class="form.is_active ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500'"
                                            x-text="form.is_active ? 'Aktif' : 'Nonaktif'"></span>
                                    </label>
                                </li>

                                
                                <li
                                    class="px-4 py-3 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_220px] gap-3 md:gap-6 items-center">
                                    <div class="min-w-0">
                                        <div
                                            class="font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2 flex-wrap">
                                            <span>Jadikan Default</span>
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-[9px] font-bold uppercase text-amber-800 border border-amber-200">
                                                <i class="fa-solid fa-star"></i> Utama
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5">Konfigurasi yang dipakai sistem secara
                                            default</div>
                                    </div>
                                    <label
                                        class="md:justify-self-end grid grid-cols-[auto_72px] items-center gap-3 cursor-pointer select-none">
                                        <span class="relative inline-flex shrink-0">
                                            <input type="checkbox" name="is_default" value="1" x-model="form.is_default"
                                                class="sr-only peer">
                                            <span
                                                class="relative w-11 h-6 bg-gray-300 rounded-full peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></span>
                                        </span>
                                        <span class="text-sm font-semibold text-right tabular-nums"
                                            :class="form.is_default ? 'text-amber-600 dark:text-amber-400' : 'text-gray-500'"
                                            x-text="form.is_default ? 'Default' : 'Tidak'"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Catatan</label>
                            <textarea name="notes" x-model="form.notes" rows="2" maxlength="1000"
                                class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white"></textarea>
                        </div>
                    </div>

                    <div
                        class="shrink-0 border-t border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700 flex justify-end gap-3">
                        <button type="button" @click="openCreate=false; openEdit=false"
                            class="rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Batal</button>
                        <button
                            class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        
        <div x-show="openDetail"
            class="fixed inset-0 z-[999990] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
            x-transition x-cloak>
            <div @click.away="openDetail = false"
                class="relative w-full max-w-2xl rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[95vh] overflow-hidden">
                <div
                    class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <div>
                        <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <i class="fa-solid fa-envelope-open-text text-indigo-500"></i>
                            <span x-text="detail.name"></span>
                            <template x-if="detail.is_default">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-800 border border-amber-200">
                                    <i class="fa-solid fa-star"></i> Default
                                </span>
                            </template>
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Detail konfigurasi email & aksi pengelolaan.</p>
                    </div>
                    
                    <button type="button" @click="openDetail = false; openTestFromDetail()"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 border border-emerald-200 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400 dark:hover:bg-emerald-900/50 transition shadow-sm">
                        <i class="fa-solid fa-paper-plane"></i> Tes Email
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Provider</div>
                            <div class="mt-1 font-bold text-gray-900 dark:text-white" x-text="detailProviderLabel"></div>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Mode Autentikasi
                            </div>
                            <div class="mt-1 font-bold text-gray-900 dark:text-white">
                                <template x-if="detail.auth_mode === 'api'"><span><i
                                            class="fa-solid fa-key text-emerald-500"></i> API Key</span></template>
                                <template x-if="detail.auth_mode !== 'api'"><span><i
                                            class="fa-solid fa-server text-sky-500"></i> SMTP</span></template>
                            </div>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Pengirim</div>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                <span x-text="detail.from_name || '—'"></span>
                                <div class="text-xs text-gray-500" x-text="detail.from_email || 'belum diisi'"></div>
                            </div>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Pemakaian Harian
                            </div>
                            <div class="mt-1 text-sm font-bold text-gray-900 dark:text-white">
                                <span x-show="detail.daily_limit > 0">
                                    <span x-text="`${detail.daily_sent || 0} / ${detail.daily_limit}`"></span>
                                </span>
                                <span x-show="!detail.daily_limit" class="italic text-gray-400 font-normal">Tanpa
                                    batas</span>
                            </div>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</div>
                            <div class="mt-1">
                                <template x-if="detail.is_active">
                                    <span
                                        class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border bg-green-100 text-green-800 border-green-200">Aktif</span>
                                </template>
                                <template x-if="!detail.is_active">
                                    <span
                                        class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border bg-gray-100 text-gray-700 border-gray-200">Nonaktif</span>
                                </template>
                            </div>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Prioritas</div>
                            <div class="mt-1 font-bold text-gray-900 dark:text-white tabular-nums"
                                x-text="detail.priority ?? 0"></div>
                        </div>
                    </div>

                    
                    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Detail Koneksi</h4>
                        <template x-if="detail.auth_mode === 'api'">
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <div><span class="font-semibold">API Key:</span> <span class="font-mono"
                                        x-text="detail.masked_api_key || '—'"></span></div>
                                <div x-show="detail.api_domain"><span class="font-semibold">Domain:</span> <span
                                        class="font-mono" x-text="detail.api_domain"></span></div>
                            </div>
                        </template>
                        <template x-if="detail.auth_mode !== 'api'">
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <div><span class="font-semibold">Host:</span> <span class="font-mono"
                                        x-text="(detail.host || '—') + (detail.port ? ':' + detail.port : '')"></span></div>
                                <div><span class="font-semibold">Encryption:</span> <span class="font-mono uppercase"
                                        x-text="detail.encryption || '—'"></span></div>
                                <div><span class="font-semibold">Username:</span> <span class="font-mono"
                                        x-text="detail.username || '—'"></span></div>
                            </div>
                        </template>
                    </div>

                    <div x-show="detail.notes"
                        class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-1">Catatan</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="detail.notes"></p>
                    </div>
                </div>

                
                <div
                    class="shrink-0 border-t border-gray-200 bg-white px-4 sm:px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                        <form :action="`<?php echo e(url('settings/email')); ?>/${detail.id}`" method="POST"
                            onsubmit="return confirm('Hapus konfigurasi ini?')" class="sm:order-1">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-red-50 px-4 py-2 text-sm font-bold text-red-700 border border-red-200 hover:bg-red-100">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                        <div class="flex flex-wrap items-center justify-end gap-2 sm:order-2">
                            <form :action="`<?php echo e(url('settings/email')); ?>/${detail.id}/set-default`" method="POST"
                                x-show="!detail.is_default">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-lg bg-amber-50 px-4 py-2.5 text-sm font-bold text-amber-700 border border-amber-200 hover:bg-amber-100 transition shadow-sm">
                                    <i class="fa-solid fa-star"></i> Jadikan Default
                                </button>
                            </form>

                            
                            <button type="button" @click="openDetail = false"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition-all hover:bg-gray-200 focus:ring-4 focus:ring-gray-100 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                                <i class="fa-solid fa-times"></i> Tutup
                            </button>

                            <button type="button" @click="openDetail = false; openEditFromDetail()"
                                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div x-show="openTest"
            class="fixed inset-0 z-[999990] flex items-center justify-center p-4 backdrop-blur-sm bg-gray-900/40"
            x-transition x-cloak>
            <div @click.away="openTest=false"
                class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl dark:bg-[#0f172a]">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-emerald-500"></i> Tes Pengiriman Email
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Konfigurasi: <b x-text="testTarget.name"></b></p>
                </div>
                <form :action="testAction" method="POST" class="p-6 space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Kirim ke email</label>
                        <input type="email" name="to" required placeholder="kamu@email.com"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="openTest=false"
                            class="rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Batal</button>
                        <button
                            class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Tes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
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
        const EMAIL_PROVIDERS = <?php echo json_encode($providers, 15, 512) ?>;

        function emailSettingsApp() {
            return {
                openCreate: false,
                openEdit: false,
                openTest: false,
                openDetail: false,
                form: {},
                formAction: '',
                testTarget: {},
                testAction: '',
                detail: {},

                init() {
                    this.resetForm();
                },

                get detailProviderLabel() {
                    if (!this.detail || !this.detail.provider) return '—';
                    return EMAIL_PROVIDERS[this.detail.provider]?.label || this.detail.provider;
                },

                get currentProvider() {
                    return EMAIL_PROVIDERS[this.form.provider] || null;
                },

                get currentProviderLabel() {
                    return this.currentProvider?.label || 'provider';
                },

                get currentSmtp() {
                    return this.currentProvider?.smtp || null;
                },

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

                supportsMode(mode) {
                    return (this.currentProvider?.auth_modes || ['smtp']).includes(mode);
                },

                setAuthMode(mode) {
                    if (!this.supportsMode(mode)) return;
                    this.form.auth_mode = mode;
                    this.applyPreset();
                },

                onProviderChange() {
                    const p = this.currentProvider;
                    if (!p) {
                        this.form.auth_mode = 'smtp';
                        return;
                    }
                    if (!p.auth_modes.includes(this.form.auth_mode)) {
                        this.form.auth_mode = p.default_mode || p.auth_modes[0];
                    }
                    this.applyPreset();
                },

                applyPreset() {
                    const p = this.currentProvider;
                    if (!p) return;

                    if (this.form.auth_mode === 'smtp' && p.smtp) {
                        if (!this.form.host) this.form.host = p.smtp.host || '';
                        if (!this.form.port) this.form.port = p.smtp.port || '';
                        if (!this.form.encryption) this.form.encryption = p.smtp.encryption || 'tls';
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
                    this.formAction = `<?php echo e(route('settings.email.store')); ?>`;
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
                    this.formAction = `<?php echo e(url('settings/email')); ?>/${row.id}`;
                    this.openEdit = true;
                },

                openTestModal(row) {
                    this.testTarget = row;
                    this.testAction = `<?php echo e(url('settings/email')); ?>/${row.id}/test`;
                    this.openTest = true;
                },

                openDetailModal(row) {
                    this.detail = row || {};
                    this.openDetail = true;
                },

                openEditFromDetail() {
                    if (!this.detail || !this.detail.id) return;
                    this.openEditModal(this.detail);
                },

                openTestFromDetail() {
                    if (!this.detail || !this.detail.id) return;
                    this.openTestModal(this.detail);
                }
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sainteku\resources\views/settings/email/index.blade.php ENDPATH**/ ?>