<div x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

    <div @click.away="openCreate = false"
        class="flex flex-col w-full max-w-4xl max-h-[90vh] rounded-2xl bg-white shadow-2xl dark:bg-gray-800 overflow-hidden">

        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Ajukan Prestasi Baru</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftarkan prestasi yang telah diraih <span
                        class="font-semibold text-yellow-500 dark:text-yellow-400">Sainteku</span></p>
            </div>
        </div>

        <form action="{{ route('student.achievements.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0">
            @csrf

            <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                {{-- Jenis Prestasi --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Jenis Prestasi <span class="text-red-500">*</span>
                    </label>
                    <select name="achievement_type_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Jenis Prestasi --</option>
                        @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('achievement_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->description }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tingkat Prestasi --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tingkat Prestasi <span class="text-red-500">*</span>
                    </label>
                    <select name="achievement_level_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Tingkat Prestasi --</option>
                        @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ old('achievement_level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->description }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Judul Karya --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Judul Karya <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Masukkan judul karya / prestasi">
                </div>

                {{-- Tanggal Prestasi --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tanggal Prestasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="achievement_date" id="datepicker" required value="{{ old('achievement_date') }}"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Pilih tanggal" autocomplete="off">
                </div>

                {{-- Jenis Publikasi --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Jenis Publikasi
                    </label>
                    <select name="publication_type"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Jenis Publikasi --</option>
                        <option value="Scopus" {{ old('publication_type') == 'Scopus' ? 'selected' : '' }}>Scopus</option>
                        <option value="Sinta" {{ old('publication_type') == 'Sinta' ? 'selected' : '' }}>Sinta</option>
                        <option value="Lainnya" {{ old('publication_type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                {{-- Penerbit / Penyelenggara --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Penerbit / Penyelenggara
                    </label>
                    <input type="text" name="publisher" value="{{ old('publisher') }}"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Nama penerbit atau penyelenggara kegiatan">
                </div>

                {{-- URL --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        URL (Link)
                    </label>
                    <input type="url" name="url" value="{{ old('url') }}"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="https://example.com/sertifikat">
                </div>

                {{-- Deskripsi --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Deskripsi Prestasi
                    </label>
                    <textarea name="description" rows="4"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Ceritakan secara singkat tentang prestasi yang diraih...">{{ old('description') }}</textarea>
                </div>

                {{-- Upload File --}}
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
            </div>

            <div class="shrink-0 flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800/80 sm:flex-row sm:justify-end">
                <button type="button" @click="openCreate = false"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    <i class="fas fa-xmark"></i>
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-save"></i>
                    Simpan Prestasi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Flatpickr --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
</script>