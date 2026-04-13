{{-- Overlay --}}
<div x-show="openCreate"
    class="fixed inset-0 z-[999999] bg-black/50 backdrop-blur-sm transition-all duration-300"
    @click="openCreate = false"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
    style="display: none;">
</div>

{{-- Modal Panel --}}
<div x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-center justify-center overflow-y-auto p-4"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    x-cloak
    style="display: none;">

    <div class="relative w-full max-w-2xl rounded-2xl bg-white shadow-2xl dark:bg-gray-800">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="fas fa-plus-circle text-green-500 mr-2"></i>
                Ajukan Prestasi Baru
            </h3>
            <button type="button" @click="openCreate = false"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6">
            <form action="{{ route('student.achievements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Jenis Prestasi --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Jenis Prestasi <span class="text-red-500">*</span>
                        </label>
                        <select name="achievement_type_id"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            required>
                            <option value="">Pilih Jenis</option>
                            @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->description }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tingkat Prestasi --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Tingkat Prestasi <span class="text-red-500">*</span>
                        </label>
                        <select name="achievement_level_id"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            required>
                            <option value="">Pilih Tingkat</option>
                            @foreach($levels as $level)
                            <option value="{{ $level->id }}">{{ $level->description }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Judul Karya --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Judul Karya <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            name="title"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Masukkan judul karya"
                            required>
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Tanggal Prestasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            name="achievement_date"
                            id="datepicker"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Pilih tanggal"
                            autocomplete="off"
                            required>
                    </div>

                    {{-- Jenis Publikasi --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Jenis Publikasi
                        </label>
                        <select name="publication_type"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih Jenis</option>
                            <option value="Scopus">Scopus</option>
                            <option value="Sinta">Sinta</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    {{-- Penerbit --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Penerbit / Penyelenggara
                        </label>
                        <input type="text"
                            name="publisher"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Nama penerbit atau penyelenggara">
                    </div>

                    {{-- URL --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            URL
                        </label>
                        <input type="url"
                            name="url"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="https://...">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Deskripsi
                        </label>
                        <textarea name="description"
                            rows="3"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Deskripsikan prestasi Anda..."></textarea>
                    </div>

                    {{-- Upload File --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Upload File
                        </label>
                        <input type="file"
                            name="file"
                            accept=".pdf,.doc,.docx"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm file:mr-3 file:rounded file:border-0 file:bg-blue-50 file:px-3 file:py-1 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:file:bg-gray-600 dark:file:text-gray-300">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Format: PDF, DOC, DOCX (Max 2MB)
                        </p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="mt-6 flex justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <button type="button"
                        @click="openCreate = false"
                        class="rounded-lg bg-gray-200 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-green-600 px-5 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Prestasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Flatpickr --}}
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
        background: #10b981 !important;
        border-color: #10b981 !important;
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
        background: #10b981 !important;
        border-color: #10b981 !important;
        color: white !important;
    }

    .dark .flatpickr-calendar .flatpickr-day:hover {
        background: #374151 !important;
        color: white !important;
    }

    .dark .flatpickr-calendar .flatpickr-day.today {
        border-color: #34d399 !important;
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
</script>