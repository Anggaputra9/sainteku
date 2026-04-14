<div x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

    <div @click.away="openCreate = false"
        class="relative w-full max-w-4xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Ajukan Prestasi Baru</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftarkan prestasi yang telah diraih <span
                        class="font-semibold text-yellow-500 dark:text-yellow-400">Sainteku</span></p>
            </div>
            <button @click="openCreate = false"
                class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        </div>

        <form action="<?php echo e(route('student.achievements.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Jenis Prestasi <span class="text-red-500">*</span>
                    </label>
                    <select name="achievement_type_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Jenis Prestasi --</option>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->id); ?>" <?php echo e(old('achievement_type_id') == $type->id ? 'selected' : ''); ?>>
                            <?php echo e($type->description); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tingkat Prestasi <span class="text-red-500">*</span>
                    </label>
                    <select name="achievement_level_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Tingkat Prestasi --</option>
                        <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($level->id); ?>" <?php echo e(old('achievement_level_id') == $level->id ? 'selected' : ''); ?>>
                            <?php echo e($level->description); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Judul Karya <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required value="<?php echo e(old('title')); ?>"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Masukkan judul karya / prestasi">
                </div>

                
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tanggal Prestasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="achievement_date" id="datepicker" required value="<?php echo e(old('achievement_date')); ?>"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Pilih tanggal" autocomplete="off">
                </div>

                
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Jenis Publikasi
                    </label>
                    <select name="publication_type"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Jenis Publikasi --</option>
                        <option value="Scopus" <?php echo e(old('publication_type') == 'Scopus' ? 'selected' : ''); ?>>Scopus</option>
                        <option value="Sinta" <?php echo e(old('publication_type') == 'Sinta' ? 'selected' : ''); ?>>Sinta</option>
                        <option value="Lainnya" <?php echo e(old('publication_type') == 'Lainnya' ? 'selected' : ''); ?>>Lainnya</option>
                    </select>
                </div>

                
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Penerbit / Penyelenggara
                    </label>
                    <input type="text" name="publisher" value="<?php echo e(old('publisher')); ?>"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Nama penerbit atau penyelenggara kegiatan">
                </div>

                
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        URL (Link)
                    </label>
                    <input type="url" name="url" value="<?php echo e(old('url')); ?>"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="https://example.com/sertifikat">
                </div>

                
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Deskripsi Prestasi
                    </label>
                    <textarea name="description" rows="4"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Ceritakan secara singkat tentang prestasi yang diraih..."><?php echo e(old('description')); ?></textarea>
                </div>

                
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Upload File Pendukung
                    </label>
                    <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50">
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="w-full cursor-pointer rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 dark:bg-gray-900 dark:text-white dark:ring-gray-600 dark:file:bg-blue-900/50 dark:file:text-blue-400">
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Format: PDF, DOC, DOCX, JPG, PNG (Max 2MB)
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-save"></i>
                    Simpan Prestasi
                </button>
            </div>
        </form>
    </div>
</div>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    .flatpickr-calendar {
        z-index: 9999999 !important;
    }

    /* Light mode */
    .flatpickr-calendar {
        background: white !important;
        border-color: #e5e7eb !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }

    .flatpickr-calendar .flatpickr-months {
        background: #f9fafb !important;
    }

    .flatpickr-calendar .flatpickr-monthDropdown-months,
    .flatpickr-calendar .flatpickr-current-month input.cur-year {
        color: #374151 !important;
    }

    .flatpickr-calendar .flatpickr-weekday {
        color: #6b7280 !important;
    }

    .flatpickr-calendar .flatpickr-day {
        color: #374151 !important;
    }

    .flatpickr-calendar .flatpickr-day.selected {
        background: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: white !important;
    }

    .flatpickr-calendar .flatpickr-day:hover {
        background: #f3f4f6 !important;
    }

    /* Dark mode */
    .dark .flatpickr-calendar {
        background: #1f2937 !important;
        border-color: #374151 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3) !important;
    }

    .dark .flatpickr-calendar .flatpickr-months {
        background: #111827 !important;
    }

    .dark .flatpickr-calendar .flatpickr-months .flatpickr-prev-month,
    .dark .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
        fill: #9ca3af !important;
    }

    .dark .flatpickr-calendar .flatpickr-months .flatpickr-prev-month:hover,
    .dark .flatpickr-calendar .flatpickr-months .flatpickr-next-month:hover {
        fill: #f3f4f6 !important;
    }

    .dark .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months,
    .dark .flatpickr-calendar .flatpickr-current-month input.cur-year {
        color: #f3f4f6 !important;
        background: #374151 !important;
    }

    .dark .flatpickr-calendar .flatpickr-weekday {
        color: #9ca3af !important;
    }

    .dark .flatpickr-calendar .flatpickr-day {
        color: #d1d5db !important;
    }

    .dark .flatpickr-calendar .flatpickr-day.selected {
        background: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: white !important;
    }

    .dark .flatpickr-calendar .flatpickr-day:hover {
        background: #374151 !important;
        color: white !important;
    }

    .dark .flatpickr-calendar .flatpickr-day.today {
        border-color: #60a5fa !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('datepicker');
        if (dateInput) {
            flatpickr(dateInput, {
                dateFormat: "Y-m-d",
                maxDate: "today",
                allowInput: false,
                disableMobile: true,
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
                    },
                    months: {
                        shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
                    }
                }
            });
        }
    });
</script><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/ManajemenAchievement\resources/views/modal-create.blade.php ENDPATH**/ ?>