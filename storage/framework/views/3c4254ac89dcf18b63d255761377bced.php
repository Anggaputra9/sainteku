
<div x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openCreate = false" x-show="openCreate"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-4xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all mt-10">

        
        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Unggah Dokumen Baru</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan dokumen fisik ke dalam repositori <span
                        class="font-semibold text-blue-600 dark:text-blue-400">Sainteku</span></p>
            </div>
            <button @click="openCreate = false" type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        </div>

        
        <form action="<?php echo e(route('DocumentRepository.store')); ?>" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Judul Dokumen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="document_title" value="<?php echo e(old('document_title')); ?>" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Contoh: SK Rektor Tahun 2026 tentang Akademik">
                </div>

                
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tipe Dokumen <span class="text-red-500">*</span>
                    </label>
                    <select name="document_type_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Tipe --</option>
                        <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>"
                                <?php echo e(old('document_type_id') == $type->id ? 'selected' : ''); ?>>
                                <?php echo e($type->description); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Unit Pemilik <span class="text-red-500">*</span>
                    </label>
                    <select name="unit_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Unit --</option>
                        <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($unit->id); ?>" <?php echo e(old('unit_id') == $unit->id ? 'selected' : ''); ?>>
                                <?php echo e($unit->unit_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 md:col-span-2">
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-widest text-gray-400 italic">Masa Berlaku
                        Dokumen</h4>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        
                        
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tanggal
                                Berlaku</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i
                                        class="fa-regular fa-calendar"></i></span>
                                <input type="text" name="effective_date" value="<?php echo e(old('effective_date')); ?>"
                                    placeholder="Pilih Tanggal..." x-init="flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd F Y', allowInput: false, static: true })"
                                    class="w-full rounded-lg border-0 py-2.5 pl-10 pr-4 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 dark:text-white dark:ring-gray-600 cursor-pointer">
                            </div>
                        </div>

                        
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tanggal
                                Kadaluarsa</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i
                                        class="fa-regular fa-calendar"></i></span>
                                <input type="text" name="expired_date" value="<?php echo e(old('expired_date')); ?>"
                                    placeholder="Pilih Tanggal..." x-init="flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd F Y', allowInput: false, static: true })"
                                    class="w-full rounded-lg border-0 py-2.5 pl-10 pr-4 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 dark:text-white dark:ring-gray-600 cursor-pointer">
                            </div>
                        </div>

                    </div>
                </div>

                
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        File Dokumen Fisik <span class="text-red-500">*</span>
                    </label>
                    <div
                        class="mt-1 flex justify-center rounded-xl border border-dashed border-gray-900/25 px-6 py-10 dark:border-gray-600 hover:bg-gray-50 transition dark:hover:bg-gray-800/50 relative">
                        <div class="text-center">
                            <i class="fa-solid fa-file-pdf text-4xl text-gray-300 dark:text-gray-600 mb-4" :class="{'text-blue-500': fileName}"></i>
                            <div class="mt-4 flex text-sm leading-6 text-gray-600 dark:text-gray-400 justify-center">
                                <label for="file-upload"
                                    class="relative cursor-pointer rounded-md bg-white font-semibold text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-600 focus-within:ring-offset-2 hover:text-blue-500 dark:bg-transparent dark:text-blue-400 dark:hover:text-blue-300">
                                    <span x-show="!fileName">Klik untuk memilih file</span>
                                    <span x-show="fileName">Ubah File</span>
                                    
                                    <input id="file-upload" name="document_file" type="file" class="sr-only"
                                        accept=".pdf,.doc,.docx" required
                                        @change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''">
                                </label>
                            </div>
                            
                            
                            <p x-show="!fileName" class="text-xs leading-5 text-gray-500 dark:text-gray-500 mt-2">PDF, DOC, DOCX maksimal 10MB</p>
                            
                            
                            <p x-show="fileName" x-cloak class="text-sm font-bold text-green-600 dark:text-green-400 mt-2 flex items-center justify-center gap-1">
                                <i class="fa-solid fa-check-circle"></i> <span x-text="fileName"></span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            
            <div
                class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end mt-8">
                <button type="button" @click="openCreate = false"
                    class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-200 transition dark:focus:ring-yellow-900">
                    <i class="fas fa-xmark"></i>
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-cloud-arrow-up"></i>
                    Unggah Dokumen
                </button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/DocumentRepository\resources/views/modal-create.blade.php ENDPATH**/ ?>