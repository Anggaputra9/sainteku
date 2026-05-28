<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <style>
        .flatpickr-calendar {
            z-index: 9999999 !important;
            /* Nilainya harus lebih besar dari z-[999999] milik modal */
        }
    </style>

    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <div class="space-y-6">

            
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Repository Dokumen
                    </h2>
                    <nav>
                        <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                            <li>Manajemen Dokumen /</li>
                            <li class="text-blue-600 dark:text-blue-400">Daftar Dokumen</li>
                        </ol>
                    </nav>
                </div>

                
                <div x-data="{ openCreate: false, fileName: '' }">
                    <?php if(Auth::user()->hasPermission(1, 'C')): ?>
                    <button @click="openCreate = true"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                        <i class="fas fa-upload"></i>
                        Unggah Dokumen Baru
                    </button>
                    <?php endif; ?>

                    
                    <?php echo $__env->make('documentrepository::modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            
            <?php if(session('success')): ?>
                <div
                    class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                    <div
                        class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                        <i class="fa-solid fa-check text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-green-800 dark:text-green-400">Sukses!</h5>
                        <p class="text-sm text-green-700 dark:text-green-500"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if(session('error') || $errors->any()): ?>
                <div
                    class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                    <div
                        class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-red-800 dark:text-red-400">Peringatan!</h5>
                        <p class="text-sm text-red-700 dark:text-red-500">
                            <?php echo e(session('error') ?? 'Gagal menyimpan. Pastikan semua data yang diinput sudah benar dan file tidak melebihi 10MB.'); ?>

                        </p>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <a href="<?php echo e(route('DocumentRepository.index')); ?>"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'all' ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700'); ?>">
                    Semua Dokumen
                </a>
                <a href="<?php echo e(route('DocumentRepository.index', ['status' => 'pending'])); ?>"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-amber-600 border border-amber-200 hover:bg-amber-50 dark:bg-gray-800 dark:border-amber-900/50 dark:hover:bg-amber-900/30'); ?>">
                    <i class="fa-solid fa-clock mr-1"></i> Menunggu Review
                </a>
                <a href="<?php echo e(route('DocumentRepository.index', ['status' => 'approved'])); ?>"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'approved' ? 'bg-green-500 text-white shadow-md' : 'bg-white text-green-600 border border-green-200 hover:bg-green-50 dark:bg-gray-800 dark:border-green-900/50 dark:hover:bg-green-900/30'); ?>">
                    <i class="fa-solid fa-check-circle mr-1"></i> Disetujui
                </a>
                <a href="<?php echo e(route('DocumentRepository.index', ['status' => 'rejected'])); ?>"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'rejected' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50 dark:hover:bg-red-900/30'); ?>">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> Perlu Revisi
                </a>
            </div>

            
            <div x-data="{ 
                openRevise: false, 
                reviseUrl: '', 
                reviseTitle: '', 
                reviseFileName: '',
                openEdit: false,
                editUrl: '',
                editData: {},
                openDelete: false,
                deleteUrl: '',
                deleteTitle: ''
            }">
                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                            <thead
                                class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-4 font-semibold w-2/5">Kode & Judul Dokumen</th>
                                    <th class="px-6 py-4 font-semibold">Tipe & Unit</th>
                                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                                    <th class="px-6 py-4 font-semibold text-center">Versi</th>
                                    <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <?php $__empty_1 = true; $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition items-start">
                                        <td class="px-6 py-4 align-top">
                                            <div class="font-bold text-gray-900 dark:text-white text-base">
                                                <?php echo e($doc->document_id); ?></div>
                                            <div class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-0.5">
                                                <?php echo e($doc->document_title); ?></div>
                                            <div class="text-xs text-gray-500 mt-1.5"><i
                                                    class="fa-regular fa-user mr-1"></i>
                                                <?php echo e($doc->creator->name ?? 'Sistem'); ?></div>
                                            
                                            
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                <?php if($doc->sifat_dokumen === 'Publik'): ?>
                                                    <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs font-bold bg-green-100 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50">
                                                        <i class="fa-solid fa-globe"></i> Publik
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200 dark:bg-gray-700/30 dark:text-gray-400 dark:border-gray-600/50">
                                                        <i class="fa-solid fa-lock"></i> Private
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <?php if($doc->is_ppid): ?>
                                                    <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50">
                                                        <i class="fa-solid fa-clipboard-check"></i> PPID
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            
                                            <?php if($doc->status == 4 && $doc->versions->isNotEmpty()): ?>
                                                <div
                                                    class="mt-4 w-full rounded-lg border border-red-200 bg-red-50/80 p-3.5 dark:border-red-500/30 dark:bg-red-500/10 shadow-sm">
                                                    <div class="flex items-start gap-3">
                                                        <i
                                                            class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 text-base"></i>
                                                        <div class="flex-col">
                                                            <span
                                                                class="block text-[11px] font-bold text-red-700 dark:text-red-400 uppercase tracking-widest mb-1 opacity-80">Catatan
                                                                Revisi:</span>
                                                            <p
                                                                class="text-sm font-medium text-red-800 dark:text-red-200 leading-relaxed">
                                                                <?php echo e($doc->versions->first()->change_note); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <div class="font-semibold text-indigo-600 dark:text-indigo-400">
                                                <?php echo e($doc->type->description ?? '-'); ?></div>
                                            <div class="text-xs text-gray-500 mt-1"><?php echo e($doc->unit->unit_name ?? '-'); ?>

                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center align-top">
                                            <?php
                                                $statusColor = 'bg-gray-100 text-gray-800';
                                                if ($doc->status == 3) {
                                                    $statusColor =
                                                        'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50';
                                                } elseif ($doc->status == 4) {
                                                    $statusColor =
                                                        'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50';
                                                } elseif ($doc->status == 2 || $doc->status == 1) {
                                                    $statusColor =
                                                        'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50';
                                                }
                                            ?>
                                            <span
                                                class="inline-flex rounded-md px-2.5 py-1 text-xs font-bold border <?php echo e($statusColor); ?>">
                                                <?php echo e($doc->workflowStatus->description ?? 'Menunggu...'); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center align-top">
                                            <span
                                                class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50">
                                                v<?php echo e($doc->version); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center align-top">
                                            <div class="flex flex-col gap-2 items-center">
                                                
                                                <?php if($doc->status == 4): ?>
                                                    
                                                    <?php if(Auth::user()->hasPermission(1, 'U') && $doc->created_by === auth()->id()): ?>
                                                    <button
                                                        @click="openRevise = true; reviseUrl = '<?php echo e(route('DocumentRepository.revise', $doc->id)); ?>'; reviseTitle = '<?php echo e(addslashes($doc->document_title)); ?>'"
                                                        class="inline-flex items-center gap-1.5 rounded-lg bg-orange-500 px-4 py-2 text-xs font-bold text-white hover:bg-orange-600 transition shadow-sm">
                                                        <i class="fa-solid fa-clock-rotate-left"></i> Revisi File
                                                    </button>
                                                    <?php endif; ?>
                                                <?php elseif($doc->status == 3): ?>
                                                    
                                                    <?php if(Auth::user()->hasPermission(1, 'R')): ?>
                                                    <a href="<?php echo e(route('DocumentRepository.download', $doc->id)); ?>"
                                                        target="_blank"
                                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm border border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                                        <i class="fa-solid fa-eye text-blue-500"></i> Lihat File
                                                    </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    
                                                    <button disabled
                                                        class="inline-flex items-center gap-1.5 rounded-lg bg-gray-50 px-4 py-2 text-xs font-bold text-gray-400 cursor-not-allowed border border-gray-200 dark:bg-gray-800/50 dark:text-gray-500 dark:border-gray-700">
                                                        <i class="fa-solid fa-lock"></i> Terkunci
                                                    </button>
                                                <?php endif; ?>

                                                
                                                <div class="flex gap-2">
                                                    
                                                    <?php if(Auth::user()->hasPermission(1, 'U') && $doc->created_by === auth()->id()): ?>
                                                    <button
                                                        @click="openEdit = true; editUrl = '<?php echo e(route('DocumentRepository.update', $doc->id)); ?>'; editData = <?php echo e(json_encode([
                                                            'document_title' => $doc->document_title,
                                                            'document_type_id' => $doc->document_type_id,
                                                            'unit_id' => $doc->unit_id,
                                                            'effective_date' => $doc->effective_date,
                                                            'expired_date' => $doc->expired_date,
                                                            'sifat_dokumen' => $doc->sifat_dokumen,
                                                            'is_ppid' => $doc->is_ppid ? true : false,
                                                        ])); ?>"
                                                        class="inline-flex items-center gap-1 rounded-lg bg-blue-500 px-3 py-1.5 text-xs font-bold text-white hover:bg-blue-600 transition shadow-sm"
                                                        title="Edit Dokumen">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                                    </button>
                                                    <?php endif; ?>

                                                    
                                                    <?php if(Auth::user()->hasPermission(1, 'D') && $doc->status != 3 && $doc->created_by === auth()->id()): ?>
                                                    <button
                                                        @click="openDelete = true; deleteUrl = '<?php echo e(route('DocumentRepository.destroy', $doc->id)); ?>'; deleteTitle = '<?php echo e(addslashes($doc->document_title)); ?>'"
                                                        class="inline-flex items-center gap-1 rounded-lg bg-red-500 px-3 py-1.5 text-xs font-bold text-white hover:bg-red-600 transition shadow-sm"
                                                        title="Hapus Dokumen">
                                                        <i class="fa-solid fa-trash"></i> Hapus
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                                                <div
                                                    class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                    <i class="fa-solid fa-folder-open text-3xl"></i>
                                                </div>
                                                <p class="text-sm font-medium">Belum ada dokumen di kategori ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div x-show="openRevise"
                        class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak
                        style="display: none;">

                        <div @click.away="openRevise = false" x-show="openRevise"
                            x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="transition ease-in duration-200 transform"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="relative w-full max-w-lg transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all mt-10">

                            
                            <div class="mb-6 border-b border-gray-100 pb-4 dark:border-gray-700">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-file-pen text-orange-500"></i> Unggah Revisi
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mengunggah file baru untuk
                                    dokumen:
                                    <br>
                                    <strong x-text="reviseTitle" class="text-gray-700 dark:text-gray-300"></strong>
                                </p>
                            </div>

                            
                            <form :action="reviseUrl" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="space-y-6">
                                    
                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                                            File Pengganti <span class="text-red-500">*</span>
                                        </label>
                                        <div
                                            class="mt-1 flex justify-center rounded-xl border border-dashed border-gray-900/25 px-6 py-8 dark:border-gray-600 hover:bg-gray-50 transition dark:hover:bg-gray-800/50">
                                            <div class="text-center">
                                                <i class="fa-solid fa-file-arrow-up text-4xl text-gray-300 dark:text-gray-600 mb-3"
                                                    :class="{ 'text-orange-500': reviseFileName }"></i>
                                                <div
                                                    class="mt-2 flex text-sm leading-6 text-gray-600 dark:text-gray-400 justify-center">
                                                    <label for="revise-upload"
                                                        class="relative cursor-pointer rounded-md bg-white font-semibold text-orange-500 focus-within:outline-none hover:text-orange-400 dark:bg-transparent dark:text-orange-400 dark:hover:text-orange-300">
                                                        <span x-show="!reviseFileName">Pilih File Revisi</span>
                                                        <span x-show="reviseFileName">Ganti File</span>
                                                        <input id="revise-upload" name="document_file" type="file"
                                                            class="sr-only" accept=".pdf,.doc,.docx" required
                                                            @change="reviseFileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''">
                                                    </label>
                                                </div>
                                                <p x-show="!reviseFileName" class="text-xs leading-5 text-gray-500 mt-1">
                                                    PDF, DOC, DOCX maksimal 10MB</p>
                                                <p x-show="reviseFileName" x-cloak
                                                    class="text-sm font-bold text-green-600 dark:text-green-400 mt-2 flex items-center justify-center gap-1">
                                                    <i class="fa-solid fa-check-circle"></i> <span
                                                        x-text="reviseFileName"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div
                                    class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end mt-8">
                                    <button type="button" @click="openRevise = false"
                                        class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-orange-500 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-orange-600 transition">
                                        <i class="fa-solid fa-upload"></i> Kirim Revisi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                
                <?php echo $__env->make('documentrepository::modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                
                <div x-show="openDelete"
                    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

                    <div @click.away="openDelete = false" x-show="openDelete"
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative w-full max-w-md transform rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

                        
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 dark:bg-red-900/30">
                            <i class="fa-solid fa-triangle-exclamation text-3xl text-red-600 dark:text-red-400"></i>
                        </div>

                        
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">
                            Hapus Dokumen?
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">
                            Anda yakin ingin menghapus dokumen:<br>
                            <strong class="text-gray-700 dark:text-gray-300" x-text="deleteTitle"></strong><br>
                            <span class="text-red-600 dark:text-red-400 font-semibold">Tindakan ini tidak dapat dibatalkan!</span>
                        </p>

                        
                        <form :action="deleteUrl" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            
                            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-center">
                                <button type="button" @click="openDelete = false"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                    <i class="fas fa-xmark"></i>
                                    Batal
                                </button>
                                <button type="submit"
                                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-red-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-red-700 transition">
                                    <i class="fas fa-trash"></i>
                                    Ya, Hapus
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                </div> 
            </div> 
        </div> 
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/DocumentRepository\resources/views/index.blade.php ENDPATH**/ ?>