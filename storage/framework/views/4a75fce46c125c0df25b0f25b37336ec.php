<div x-show="openCreate" class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">
    
    <div @click.away="openCreate = false" x-show="openCreate" class="relative w-full max-w-lg transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all mt-10">
        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tambah Mata Kuliah</h3>
            <button @click="openCreate = false" type="button" class="text-gray-400 hover:text-gray-900 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="<?php echo e(route('masterdata.courses.store')); ?>" method="POST" class="space-y-5 text-left" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
            <?php echo csrf_field(); ?>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">Kode MK</label>
                <input type="text" disabled placeholder="[Dibuat Otomatis oleh Sistem]" 
                    class="w-full rounded-lg border border-gray-300 bg-gray-100 py-2.5 px-4 text-sm text-gray-500 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600">
            </div>
            
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">Nama Mata Kuliah <span class="text-red-500">*</span></label>
                <input type="text" name="course_name" required placeholder="Contoh: Pemrograman Web" class="w-full rounded-lg border-gray-300 py-2.5 px-4 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
            </div>

            
            <div x-data="{ selectedFakultas: '', listProdi: [] }">
                <div class="mb-5">
                    <label class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">Fakultas <span class="text-red-500">*</span></label>
                    <select x-model="selectedFakultas" @change="
                            listProdi = [];
                            if(selectedFakultas) {
                                fetch(`<?php echo e(route('masterdata.courses.api.prodis')); ?>?fakultas_id=${selectedFakultas}`)
                                .then(res => res.json())
                                .then(data => listProdi = data);
                            }
                        " required class="w-full rounded-lg border-gray-300 py-2.5 px-4 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                        <option value="">-- Pilih Fakultas --</option>
                        <?php $__currentLoopData = $faculties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fak): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($fak->id); ?>"><?php echo e($fak->unit_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">Prodi Pengampu <span class="text-red-500">*</span></label>
                    <select name="unit_id" required :disabled="!selectedFakultas" class="w-full rounded-lg border-gray-300 py-2.5 px-4 focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:opacity-60 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                        <option value="">-- Pilih Prodi --</option>
                        <template x-for="prd in listProdi" :key="prd.id">
                            <option :value="prd.id" x-text="prd.unit_name"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">Status <span class="text-red-500">*</span></label>
                <select name="is_active" required class="w-full rounded-lg border-gray-300 py-2.5 px-4 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                <button type="button" @click="openCreate = false" class="rounded-lg bg-gray-200 px-5 py-2 text-sm font-bold text-gray-700 hover:bg-gray-300 transition">Batal</button>
                <button type="submit" x-bind:disabled="isSubmitting" x-bind:class="isSubmitting ? 'bg-blue-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'" class="rounded-lg px-5 py-2 text-sm font-semibold text-white transition flex items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin" x-show="isSubmitting" x-cloak></i>
                    <span x-text="isSubmitting ? 'Memproses...' : 'Simpan Data'"></span>
                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/courses/modal-create.blade.php ENDPATH**/ ?>