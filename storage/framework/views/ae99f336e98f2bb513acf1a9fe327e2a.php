

<?php $__env->startSection('content'); ?>
    
    <div class="space-y-6" x-data="examRoomsApp" x-init="init()" x-cloak>

        
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-chalkboard-user text-indigo-500"></i> Ruang Ujian
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Monev Akademik /</li>
                        <li class="text-indigo-600 dark:text-indigo-400">Ujian</li>
                    </ol>
                </nav>
            </div>
            <button type="button" @click="openCreateModal()"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-indigo-700 transition">
                <i class="fa-solid fa-plus"></i> Buat Ruang Ujian
            </button>
        </div>

        
        <template x-if="alert.message">
            <div class="border-l-4 p-4 rounded-r-lg shadow-sm flex items-center gap-3"
                 :class="alert.type === 'error'
                    ? 'border-red-500 bg-red-50 text-red-700'
                    : 'border-green-500 bg-green-50 text-green-700'">
                <i class="fa-solid"
                   :class="alert.type === 'error' ? 'fa-circle-xmark' : 'fa-check-circle'"></i>
                <span class="text-sm font-bold" x-text="alert.message"></span>
            </div>
        </template>

        
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pencarian</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Judul / kode ruang..."
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        <option value="">Semua</option>
                        <option value="DRAFT" <?php if(request('status')==='DRAFT'): echo 'selected'; endif; ?>>Draft</option>
                        <option value="PUBLISHED" <?php if(request('status')==='PUBLISHED'): echo 'selected'; endif; ?>>Published</option>
                        <option value="CLOSED" <?php if(request('status')==='CLOSED'): echo 'selected'; endif; ?>>Closed</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">Filter</button>
                    <a href="<?php echo e(route('ujian.rooms.index')); ?>" class="flex-1 inline-flex justify-center rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Reset</a>
                </div>
            </form>
        </div>

        
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4 font-semibold w-1/3">Ruang Ujian</th>
                            <th class="px-6 py-4 font-semibold text-center">Mata Kuliah & Jadwal</th>
                            <th class="px-6 py-4 font-semibold text-center">Peserta</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white text-base"><?php echo e($room->title); ?></div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1 rounded-md bg-indigo-50 px-2 py-0.5 font-mono font-bold text-indigo-700 border border-indigo-200">
                                            <i class="fa-solid fa-key text-[10px]"></i> <?php echo e($room->room_code); ?>

                                        </span>
                                        <span><?php echo e($room->tabSwitchLabel()); ?></span>
                                        <span>· <?php echo e($room->duration_minutes); ?> mnt</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="font-bold text-gray-700 dark:text-gray-300"><?php echo e($room->proposal->course->course_name ?? '-'); ?></div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        <?php echo e($room->start_at->format('d M Y H:i')); ?> – <?php echo e($room->end_at->format('H:i')); ?>

                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="font-bold text-gray-900 dark:text-white tabular-nums"><?php echo e($room->attempts_count); ?></div>
                                    <div class="text-[11px] text-gray-500">
                                        <span class="text-emerald-600"><?php echo e($room->attempts_ongoing_count); ?> aktif</span>
                                        · <?php echo e($room->attempts_finished_count); ?> selesai
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php
                                        $badge = match($room->status) {
                                            'PUBLISHED' => 'bg-green-100 text-green-800 border-green-200',
                                            'DRAFT'     => 'bg-gray-100 text-gray-700 border-gray-200',
                                            'CLOSED'    => 'bg-red-100 text-red-700 border-red-200',
                                        };
                                    ?>
                                    <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border <?php echo e($badge); ?>"><?php echo e($room->status); ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" @click="openDetailModal('<?php echo e($room->uuid); ?>')"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 transition shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                        <i class="fa-solid fa-eye text-blue-500"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fa-solid fa-clipboard-list text-3xl mb-3 opacity-50"></i><br>
                                    Belum ada ruang ujian.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if($rooms->hasPages()): ?>
            <div class="px-2"><?php echo e($rooms->links()); ?></div>
        <?php endif; ?>

        
        <div x-show="openForm"
            class="fixed inset-0 z-[999990] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
            x-transition x-cloak>
            <div @click.away="openForm = false"
                class="relative w-full max-w-3xl rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[95vh] overflow-hidden">
                <div class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-chalkboard-user text-indigo-500"></i>
                        <span x-text="formMode === 'edit' ? 'Edit Ruang Ujian' : 'Buat Ruang Ujian'"></span>
                    </h3>
                    <button type="button" @click="openForm = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form @submit.prevent="submitForm()" class="flex-1 flex flex-col overflow-hidden">
                    <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Paket Soal (Approved) *</label>
                                <select x-model="form.proposal_id" :disabled="formMode === 'edit' && hasAttempts" required
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm disabled:bg-gray-100 dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                    <option value="">— Pilih paket soal —</option>
                                    <?php $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($p->id); ?>"><?php echo e($p->label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <p class="text-[10px] text-gray-500 mt-1" x-show="formMode === 'edit' && hasAttempts">
                                    Sudah ada peserta — paket soal dikunci.
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Judul *</label>
                                <input x-model="form.title" required maxlength="150"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Deskripsi</label>
                                <textarea x-model="form.description" rows="2" maxlength="1000"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white"></textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Mulai *</label>
                                <input type="datetime-local" x-model="form.start_at" required
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Berakhir *</label>
                                <input type="datetime-local" x-model="form.end_at" required
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Durasi (menit) *</label>
                                <input type="number" min="1" max="600" x-model.number="form.duration_minutes" required
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Kebijakan Tab Switch *</label>
                                <select x-model="form.tab_switch_policy"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                    <option value="strict">Tanpa Toleransi (auto-submit 1x)</option>
                                    <option value="limited">Limited (ada batas)</option>
                                    <option value="unlimited">Tanpa Batas</option>
                                </select>
                            </div>
                            <div x-show="form.tab_switch_policy === 'limited'">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Batas Tab Switch</label>
                                <input type="number" min="0" max="50" x-model.number="form.tab_switch_limit"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                        </div>

                        
                        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-gray-800/40 dark:border-gray-700">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fa-solid fa-sliders text-indigo-500"></i> Pengaturan Tambahan
                                </h4>
                            </div>

                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                
                                <li class="px-4 py-3 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_200px] gap-3 md:gap-6 items-center">
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-700 dark:text-gray-300">Acak Urutan Soal</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Tiap mahasiswa mendapat urutan soal yang berbeda</div>
                                    </div>
                                    <label class="md:justify-self-end grid grid-cols-[auto_72px] items-center gap-3 cursor-pointer select-none">
                                        <span class="relative inline-flex shrink-0">
                                            <input type="checkbox" x-model="form.shuffle_questions" class="sr-only peer">
                                            <span class="relative w-11 h-6 bg-gray-300 rounded-full peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></span>
                                        </span>
                                        <span class="text-sm font-semibold text-right tabular-nums"
                                              :class="form.shuffle_questions ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500'"
                                              x-text="form.shuffle_questions ? 'Aktif' : 'Nonaktif'"></span>
                                    </label>
                                </li>

                                
                                <li class="px-4 py-3 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_200px] gap-3 md:gap-6 items-center">
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-700 dark:text-gray-300">Tampilkan Sisa Waktu</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Mahasiswa melihat countdown waktu pengerjaan</div>
                                    </div>
                                    <label class="md:justify-self-end grid grid-cols-[auto_72px] items-center gap-3 cursor-pointer select-none">
                                        <span class="relative inline-flex shrink-0">
                                            <input type="checkbox" x-model="form.show_remaining_time" class="sr-only peer">
                                            <span class="relative w-11 h-6 bg-gray-300 rounded-full peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></span>
                                        </span>
                                        <span class="text-sm font-semibold text-right tabular-nums"
                                              :class="form.show_remaining_time ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500'"
                                              x-text="form.show_remaining_time ? 'Aktif' : 'Nonaktif'"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <template x-if="formError">
                            <div class="border-l-4 border-red-500 bg-red-50 p-3 text-sm text-red-700 rounded-r-lg" x-text="formError"></div>
                        </template>
                    </div>

                    <div class="shrink-0 border-t border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700 flex justify-end gap-3">
                        <button type="button" @click="openForm = false"
                            class="rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Batal</button>
                        <button type="submit" :disabled="submitting"
                            class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 disabled:opacity-60">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span x-text="submitting ? 'Menyimpan…' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        
        <div x-show="openDetail"
            class="fixed inset-0 z-[999990] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
            x-transition x-cloak>
            <div @click.away="openDetail = false"
                class="relative w-full max-w-4xl rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[95vh] overflow-hidden">
                <div class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <div class="pr-4">
                        <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100" x-text="detail.room?.title || 'Detail Ruang Ujian'"></h3>
                        <p class="text-xs text-gray-500 mt-1">Bagikan kode/QR ke mahasiswa untuk mulai ujian.</p>
                    </div>
                    <button type="button" @click="openDetail = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a]">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Kode Akses</div>
                            <div class="font-mono text-2xl font-bold tracking-[0.3em] text-indigo-700 dark:text-indigo-300" x-text="detail.room?.room_code"></div>
                            <div class="mt-3 flex items-center justify-center">
                                <div class="rounded-xl bg-white p-3 ring-1 ring-gray-200 dark:ring-gray-700">
                                    <div x-ref="qrCanvas"></div>
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-3">
                                Scan dengan kamera HP / arahkan ke
                                <a :href="detail.room?.join_url" class="font-bold text-indigo-600 underline">halaman join</a>
                            </p>
                        </div>

                        
                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#1e293b]">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</div>
                                <div class="mt-1">
                                    <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border"
                                          :class="detail.room?.status === 'PUBLISHED' ? 'bg-green-100 text-green-800 border-green-200'
                                                : detail.room?.status === 'CLOSED' ? 'bg-red-100 text-red-700 border-red-200'
                                                : 'bg-gray-100 text-gray-700 border-gray-200'"
                                          x-text="detail.room?.status"></span>
                                </div>
                            </div>
                            <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#1e293b]">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Durasi</div>
                                <div class="mt-1 font-bold text-gray-900 dark:text-white">
                                    <span x-text="detail.room?.duration_minutes"></span> menit
                                </div>
                            </div>
                            <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#1e293b]">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Mulai</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white" x-text="detail.room?.start_at_human || detail.room?.start_at"></div>
                            </div>
                            <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#1e293b]">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Berakhir</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white" x-text="detail.room?.end_at_human || detail.room?.end_at"></div>
                            </div>
                            <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#1e293b] sm:col-span-2">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Kebijakan Tab Switch</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white" x-text="detail.room?.tab_switch_label"></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-gray-800/40 dark:border-gray-700 flex items-center justify-between">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-users text-indigo-500"></i> Peserta
                                (<span x-text="detail.attempts?.length || 0"></span>)
                            </h4>
                            <button type="button" @click="reloadDetail()" class="text-xs text-indigo-600 hover:underline">
                                <i class="fa-solid fa-arrows-rotate"></i> Refresh
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-700 dark:bg-gray-700/30 dark:text-gray-300">
                                    <tr>
                                        <th class="px-4 py-3">Mahasiswa</th>
                                        <th class="px-4 py-3 text-center">Status</th>
                                        <th class="px-4 py-3 text-center">Jawaban</th>
                                        <th class="px-4 py-3 text-center">Pelanggaran</th>
                                        <th class="px-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="a in detail.attempts" :key="a.uuid">
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                            <td class="px-4 py-2.5">
                                                <div class="font-bold text-gray-900 dark:text-white" x-text="a.user_name"></div>
                                                <div class="text-xs text-gray-500" x-text="a.user_identity"></div>
                                            </td>
                                            <td class="px-4 py-2.5 text-center">
                                                <span class="inline-flex rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider border"
                                                    :class="a.status === 'ONGOING' ? 'bg-amber-100 text-amber-800 border-amber-200'
                                                          : a.status === 'SUBMITTED' ? 'bg-green-100 text-green-800 border-green-200'
                                                          : 'bg-red-100 text-red-700 border-red-200'"
                                                    x-text="a.status_label"></span>
                                            </td>
                                            <td class="px-4 py-2.5 text-center tabular-nums">
                                                <span x-text="`${a.answered}/${a.total_questions}`"></span>
                                            </td>
                                            <td class="px-4 py-2.5 text-center tabular-nums" x-text="a.tab_switch_count"></td>
                                            <td class="px-4 py-2.5 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a :href="`<?php echo e(url('ujian/attempt/result')); ?>/${a.uuid}`" target="_blank"
                                                       class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 border border-blue-200 hover:bg-blue-100">
                                                        <i class="fa-solid fa-eye"></i> Hasil
                                                    </a>
                                                    <template x-if="a.status === 'AUTO_SUBMITTED_VIOLATION' && detail.room?.status === 'PUBLISHED'">
                                                        <button type="button" @click="confirmResetViolation(a)"
                                                            class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700 border border-amber-200 hover:bg-amber-100">
                                                            <i class="fa-solid fa-rotate-left"></i> Reset
                                                        </button>
                                                    </template>
                                                    <button type="button" @click="confirmDeleteAttempt(a)"
                                                        class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 border border-red-200 hover:bg-red-100">
                                                        <i class="fa-solid fa-user-xmark"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="!detail.attempts || detail.attempts.length === 0">
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 text-sm">Belum ada peserta.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                <div class="shrink-0 border-t border-gray-200 bg-white px-4 sm:px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                        <button type="button" @click="confirmDelete()"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-red-50 px-4 py-2 text-sm font-bold text-red-700 border border-red-200 hover:bg-red-100">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            <template x-if="detail.room?.status === 'DRAFT'">
                                <button type="button" @click="changeStatus('publish')"
                                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-50 px-4 py-2 text-sm font-bold text-emerald-700 border border-emerald-200 hover:bg-emerald-100">
                                    <i class="fa-solid fa-paper-plane"></i> Publish
                                </button>
                            </template>
                            <template x-if="detail.room?.status === 'CLOSED'">
                                <button type="button" @click="openReopen()"
                                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-50 px-4 py-2 text-sm font-bold text-emerald-700 border border-emerald-200 hover:bg-emerald-100">
                                    <i class="fa-solid fa-rotate-right"></i> Buka Kembali
                                </button>
                            </template>
                            <template x-if="detail.room?.status === 'PUBLISHED'">
                                <button type="button" @click="changeStatus('close')"
                                    class="inline-flex items-center gap-2 rounded-lg bg-amber-50 px-4 py-2 text-sm font-bold text-amber-700 border border-amber-200 hover:bg-amber-100">
                                    <i class="fa-solid fa-circle-stop"></i> Tutup
                                </button>
                            </template>
                            <button type="button" @click="openEditFromDetail()"
                                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-indigo-700">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        
        <div x-show="openReopenModal"
            class="fixed inset-0 z-[999991] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
            x-transition x-cloak>
            <div @click.away="openReopenModal = false"
                class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 overflow-hidden">
                <div class="flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-rotate-right text-emerald-500"></i> Buka Kembali Ruang Ujian
                    </h3>
                    <button type="button" @click="openReopenModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <form @submit.prevent="submitReopen()" class="p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Tentukan kapan ruang ujian akan ditutup lagi. Durasi pengerjaan
                        boleh diubah; biarkan kosong untuk memakai durasi lama.
                    </p>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Berakhir Baru *</label>
                        <input type="datetime-local" x-model="reopenForm.end_at" required
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Durasi (menit) — opsional</label>
                        <input type="number" min="1" max="600" x-model.number="reopenForm.duration_minutes"
                            placeholder="Biarkan kosong untuk durasi lama"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                    </div>
                    <template x-if="reopenError">
                        <div class="border-l-4 border-red-500 bg-red-50 p-3 text-sm text-red-700 rounded-r-lg" x-text="reopenError"></div>
                    </template>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="openReopenModal = false"
                            class="rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Batal</button>
                        <button type="submit" :disabled="reopenSubmitting"
                            class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 disabled:opacity-60">
                            <i class="fa-solid fa-rotate-right"></i>
                            <span x-text="reopenSubmitting ? 'Memproses…' : 'Buka Kembali'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        
        <div x-show="confirmModal.open"
            class="fixed inset-0 z-[999992] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/50"
            x-transition x-cloak>
            <div @click.away="closeConfirmModal()"
                class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100" x-text="confirmModal.title"></h3>
                </div>
                <div class="p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
                    <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line" x-text="confirmModal.message"></p>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="closeConfirmModal()" :disabled="confirmModal.submitting"
                            class="rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 disabled:opacity-60 dark:bg-gray-700 dark:text-gray-200">
                            Batal
                        </button>
                        <button type="button" @click="executeConfirmAction()" :disabled="confirmModal.submitting"
                            class="rounded-xl bg-red-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-red-700 disabled:opacity-60">
                            <span x-text="confirmModal.submitting ? 'Memproses…' : 'Ya, Lanjutkan'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    </style>

    <script>
        // Daftarkan komponen lewat alpine:init biar urutan eksekusinya
        // konsisten — sama seperti modul Tashih (lihat tashih/index.blade.php).
        document.addEventListener('alpine:init', () => {
            Alpine.data('examRoomsApp', () => ({
                /* ====== state ====== */
                openForm: false,
                openDetail: false,
                openReopenModal: false,
                reopenForm: { end_at: '', duration_minutes: null },
                reopenError: '',
                reopenSubmitting: false,
                formMode: 'create',     // 'create' | 'edit'
                submitting: false,
                formError: '',
                hasAttempts: false,
                // Diisi via init() — tidak boleh panggil this.blankForm() di
                // property initializer karena `this` belum siap saat itu.
                form: {
                    proposal_id: '',
                    title: '',
                    description: '',
                    start_at: '',
                    end_at: '',
                    duration_minutes: 60,
                    tab_switch_policy: 'strict',
                    tab_switch_limit: 0,
                    shuffle_questions: false,
                    show_remaining_time: true,
                },
                detail: { room: null, attempts: [] },
                editingUuid: null,
                alert: { type: '', message: '' },
                confirmModal: {
                    open: false,
                    title: '',
                    message: '',
                    action: '',
                    data: null,
                    submitting: false,
                },

                csrf: document.querySelector('meta[name="csrf-token"]')?.content || '<?php echo e(csrf_token()); ?>',

                init() {
                    <?php if(session('success')): ?>
                        this.flash('success', <?php echo json_encode(session('success'), 15, 512) ?>);
                    <?php endif; ?>
                },

                blankForm() {
                    return {
                        proposal_id: '',
                        title: '',
                        description: '',
                        start_at: '',
                        end_at: '',
                        duration_minutes: 60,
                        tab_switch_policy: 'strict',
                        tab_switch_limit: 0,
                        shuffle_questions: false,
                        show_remaining_time: true,
                    };
                },

                flash(type, message) {
                    this.alert = { type, message };
                    setTimeout(() => { this.alert = { type: '', message: '' }; }, 4000);
                },

                /* ============ CREATE ============ */
                openCreateModal() {
                    this.formMode = 'create';
                    this.form = this.blankForm();
                    this.formError = '';
                    this.hasAttempts = false;
                    this.editingUuid = null;
                    this.openForm = true;
                },

                /* ============ DETAIL ============ */
                async openDetailModal(uuid) {
                    this.editingUuid = uuid;
                    this.detail = { room: null, attempts: [] };
                    this.openDetail = true;

                    try {
                        const res = await fetch(`<?php echo e(url('ujian/rooms')); ?>/${uuid}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (!res.ok) throw new Error('Gagal memuat detail');
                        const data = await res.json();
                        this.detail = data;
                        this.$nextTick(() => this.renderQR());
                    } catch (e) {
                        this.flash('error', e.message);
                        this.openDetail = false;
                    }
                },

                reloadDetail() {
                    if (this.editingUuid) this.openDetailModal(this.editingUuid);
                },

                /** Render QR code dari kode ruang */
                renderQR() {
                    if (!this.$refs.qrCanvas || !this.detail.room) return;
                    this.$refs.qrCanvas.innerHTML = '';
                    const url = `${window.location.origin}<?php echo e(route('ujian.attempt.scan', [], false)); ?>?code=${encodeURIComponent(this.detail.room.room_code)}`;
                    new QRCode(this.$refs.qrCanvas, {
                        text: url,
                        width: 160,
                        height: 160,
                        correctLevel: QRCode.CorrectLevel.M,
                    });
                },

                openEditFromDetail() {
                    if (!this.detail.room) return;
                    const r = this.detail.room;
                    this.formMode = 'edit';
                    this.editingUuid = r.uuid;
                    this.hasAttempts = (this.detail.attempts || []).length > 0;
                    this.form = {
                        proposal_id: r.proposal_id,
                        title: r.title,
                        description: r.description || '',
                        start_at: r.start_at?.replace(' ', 'T').slice(0,16) || '',
                        end_at:   r.end_at?.replace(' ', 'T').slice(0,16)   || '',
                        duration_minutes: r.duration_minutes,
                        tab_switch_policy: r.tab_switch_policy,
                        tab_switch_limit: r.tab_switch_limit,
                        shuffle_questions: !!r.shuffle_questions,
                        show_remaining_time: !!r.show_remaining_time,
                    };
                    this.formError = '';
                    this.openDetail = false;
                    this.openForm = true;
                },

                /* ============ SUBMIT FORM ============ */
                async submitForm() {
                    this.submitting = true;
                    this.formError = '';

                    const url = this.formMode === 'edit'
                        ? `<?php echo e(url('ujian/rooms')); ?>/${this.editingUuid}`
                        : `<?php echo e(route('ujian.rooms.store')); ?>`;
                    const method = this.formMode === 'edit' ? 'PUT' : 'POST';

                    try {
                        const res = await fetch(url, {
                            method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                            },
                            body: JSON.stringify(this.form),
                        });
                        const data = await res.json().catch(() => ({}));

                        if (!res.ok) {
                            const msg = data?.message || (data?.errors ? Object.values(data.errors).flat().join(', ') : 'Gagal menyimpan.');
                            throw new Error(msg);
                        }

                        this.openForm = false;
                        this.flash('success', data.message || 'Tersimpan');
                        // Reload halaman supaya tabel terupdate (paling simpel & aman)
                        setTimeout(() => window.location.reload(), 600);
                    } catch (e) {
                        this.formError = e.message;
                    } finally {
                        this.submitting = false;
                    }
                },

                confirmDelete() {
                    this.confirmModal = {
                        open: true,
                        title: 'Hapus Ruang Ujian',
                        message: 'Yakin ingin menghapus ruang ujian ini? Tindakan ini tidak dapat dibatalkan.',
                        action: 'delete-room',
                        data: null,
                        submitting: false,
                    };
                },

                async executeDelete() {
                    this.confirmModal.submitting = true;
                    try {
                        const res = await fetch(`<?php echo e(url('ujian/rooms')); ?>/${this.editingUuid}`, {
                            method: 'DELETE',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrf },
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) throw new Error(data?.message || 'Gagal menghapus.');
                        this.confirmModal.open = false;
                        this.openDetail = false;
                        this.flash('success', data.message || 'Ruang ujian dihapus.');
                        setTimeout(() => window.location.reload(), 600);
                    } catch (e) {
                        this.flash('error', e.message);
                        this.confirmModal.open = false;
                    } finally {
                        this.confirmModal.submitting = false;
                    }
                },

                confirmDeleteAttempt(attempt) {
                    if (!attempt?.uuid) return;
                    const name = attempt.user_name || 'peserta ini';
                    this.confirmModal = {
                        open: true,
                        title: 'Hapus Peserta Ujian',
                        message: `Yakin ingin menghapus ${name} dari ruang ujian ini?\n\nSemua jawaban dan log aktivitas peserta akan ikut terhapus. Tindakan ini tidak dapat dibatalkan.`,
                        action: 'delete-attempt',
                        data: attempt,
                        submitting: false,
                    };
                },

                async executeDeleteAttempt() {
                    const attempt = this.confirmModal.data;
                    if (!attempt?.uuid) return;
                    this.confirmModal.submitting = true;
                    try {
                        const res = await fetch(`<?php echo e(url('ujian/rooms')); ?>/${this.editingUuid}/attempts/${attempt.uuid}`, {
                            method: 'DELETE',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrf },
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) throw new Error(data?.message || 'Gagal menghapus peserta.');
                        this.confirmModal.open = false;
                        this.flash('success', data.message || 'Peserta ujian dihapus.');
                        this.reloadDetail();
                    } catch (e) {
                        this.flash('error', e.message);
                        this.confirmModal.open = false;
                    } finally {
                        this.confirmModal.submitting = false;
                    }
                },

                confirmResetViolation(attempt) {
                    if (!attempt?.uuid) return;
                    const name = attempt.user_name || 'peserta ini';
                    this.confirmModal = {
                        open: true,
                        title: 'Reset Pelanggaran',
                        message: `Yakin ingin me-reset pelanggaran untuk ${name}?\n\nPeserta akan dapat melanjutkan ujian dengan counter pelanggaran di-reset ke 0. Pastikan waktu ujian masih berlangsung.`,
                        action: 'reset-violation',
                        data: attempt,
                        submitting: false,
                    };
                },

                async executeResetViolation() {
                    const attempt = this.confirmModal.data;
                    if (!attempt?.uuid) return;
                    this.confirmModal.submitting = true;
                    try {
                        const res = await fetch(`<?php echo e(url('ujian/rooms')); ?>/${this.editingUuid}/attempts/${attempt.uuid}/reset-violation`, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrf },
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) throw new Error(data?.message || 'Gagal me-reset pelanggaran.');
                        this.confirmModal.open = false;
                        this.flash('success', data.message || 'Pelanggaran berhasil di-reset.');
                        this.reloadDetail();
                    } catch (e) {
                        this.flash('error', e.message);
                        this.confirmModal.open = false;
                    } finally {
                        this.confirmModal.submitting = false;
                    }
                },

                changeStatus(action) {
                    if (action === 'publish') {
                        this.confirmModal = {
                            open: true,
                            title: 'Publish Ruang Ujian',
                            message: `Yakin ingin mempublish ruang ujian ini?\n\nSetelah dipublish, mahasiswa dapat masuk menggunakan kode ruang. Pastikan semua pengaturan sudah benar.`,
                            action: 'publish',
                            data: null,
                            submitting: false,
                        };
                    } else if (action === 'close') {
                        this.confirmModal = {
                            open: true,
                            title: 'Tutup Ruang Ujian',
                            message: `Yakin ingin menutup ruang ujian ini?\n\nSetelah ditutup, mahasiswa tidak dapat lagi masuk atau melanjutkan ujian. Ujian yang sedang berlangsung akan otomatis disubmit.`,
                            action: 'close',
                            data: null,
                            submitting: false,
                        };
                    }
                },

                async executeChangeStatus() {
                    const action = this.confirmModal.action;
                    const url = `<?php echo e(url('ujian/rooms')); ?>/${this.editingUuid}/${action}`;

                    this.confirmModal.submitting = true;
                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                                'Content-Type': 'application/json'
                            },
                        });

                        const data = await res.json().catch(() => ({}));

                        if (!res.ok) throw new Error(data?.message || 'Gagal');

                        this.confirmModal.open = false;
                        this.flash('success', data.message);
                        this.reloadDetail();
                    } catch (e) {
                        console.error('Error in executeChangeStatus:', e);
                        this.flash('error', e.message);
                        this.confirmModal.open = false;
                    } finally {
                        this.confirmModal.submitting = false;
                    }
                },

                /* ============ CONFIRM MODAL EXECUTOR ============ */
                executeConfirmAction() {
                    const action = this.confirmModal.action;

                    if (action === 'delete-room') {
                        this.executeDelete();
                    } else if (action === 'delete-attempt') {
                        this.executeDeleteAttempt();
                    } else if (action === 'reset-violation') {
                        this.executeResetViolation();
                    } else if (action === 'publish' || action === 'close') {
                        this.executeChangeStatus();
                    } else {
                        console.error('Unknown action:', action);
                    }
                },

                closeConfirmModal() {
                    if (!this.confirmModal.submitting) {
                        this.confirmModal.open = false;
                    }
                },

                /* ============ REOPEN ============ */
                openReopen() {
                    // Default: end_at = sekarang + 60 menit (datetime-local format)
                    const d = new Date();
                    d.setMinutes(d.getMinutes() + 60 - d.getTimezoneOffset());
                    const iso = d.toISOString().slice(0, 16);
                    this.reopenForm = {
                        end_at: iso,
                        duration_minutes: this.detail.room?.duration_minutes || null,
                    };
                    this.reopenError = '';
                    this.openReopenModal = true;
                },

                async submitReopen() {
                    this.reopenSubmitting = true;
                    this.reopenError = '';
                    try {
                        const payload = { end_at: this.reopenForm.end_at };
                        if (this.reopenForm.duration_minutes) {
                            payload.duration_minutes = this.reopenForm.duration_minutes;
                        }
                        const res = await fetch(`<?php echo e(url('ujian/rooms')); ?>/${this.editingUuid}/reopen`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                            },
                            body: JSON.stringify(payload),
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) {
                            const msg = data?.message || (data?.errors ? Object.values(data.errors).flat().join(', ') : 'Gagal membuka ulang.');
                            throw new Error(msg);
                        }
                        this.openReopenModal = false;
                        this.flash('success', data.message || 'Ruang ujian dibuka kembali.');
                        this.reloadDetail();
                    } catch (e) {
                        this.reopenError = e.message;
                    } finally {
                        this.reopenSubmitting = false;
                    }
                },
            }));
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sainteku\Modules/Ujian\resources/views/rooms/index.blade.php ENDPATH**/ ?>