{{-- Overlay --}}
<template x-teleport="#modal-root">
    <div x-show="openCreateDosen"
    class="app-modal-overlay fixed inset-0 bg-black/50 overflow-y-auto backdrop-blur-sm transition-all duration-300"
    @click="openCreateDosen = false"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
    style="display: none;">
</div>
</template>

{{-- Modal Panel --}}
<div x-show="openCreateDosen"
    class="fixed inset-0 flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300"
    x-transition:opacity
    x-cloak
    style="display: none;">

    <div @click.away="openCreateDosen = false"
        class="relative w-full max-w-4xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-plus-circle text-blue-500 mr-2"></i>
                    Ajukan Prestasi Dosen
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftarkan prestasi dosen ke sistem <span
                        class="font-semibold text-yellow-500 dark:text-yellow-400">Sainteku</span></p>
            </button>
        </div>

        <form action="{{ route('dosen.repository.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                {{-- Kategori --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Kategori Prestasi <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori_id" id="kategori_id"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tingkat --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tingkat <span class="text-red-500">*</span>
                    </label>
                    <select name="tingkat_id"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        required>
                        <option value="">-- Pilih Tingkat --</option>
                        @foreach($tingkat as $t)
                        <option value="{{ $t->id }}" {{ old('tingkat_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Judul --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Judul Karya <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" required value="{{ old('judul') }}"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Masukkan judul karya / prestasi">
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tanggal Prestasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tanggal" id="datepicker_dosen" required value="{{ old('tanggal') }}"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Pilih tanggal" autocomplete="off">
                </div>

                {{-- Penyelenggara --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Penyelenggara / Penerbit
                    </label>
                    <input type="text" name="penyelenggara" value="{{ old('penyelenggara') }}"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Nama penyelenggara atau penerbit">
                </div>

                {{-- URL --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        URL
                    </label>
                    <input type="url" name="url" value="{{ old('url') }}"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="https://...">
                </div>

                {{-- Dynamic Fields --}}
                <div id="dynamic-fields-modal" class="md:col-span-2 space-y-4"></div>

                {{-- Deskripsi --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Deskripsikan prestasi yang diraih...">{{ old('deskripsi') }}</textarea>
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
                            Format: PDF, DOC, DOCX, JPG, PNG (Max 5MB)
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-save"></i>
                    Ajukan Prestasi
                </button>
            </div>
        </form>
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
        // Datepicker untuk dosen
        const dateInputDosen = document.getElementById('datepicker_dosen');
        if (dateInputDosen) {
            flatpickr(dateInputDosen, {
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

        // Dynamic fields untuk dosen
        const kategoriSelect = document.getElementById('kategori_id');
        const dynamicFields = document.getElementById('dynamic-fields-modal');

        if (kategoriSelect && dynamicFields) {
            function loadDynamicFields() {
                const kategoriText = kategoriSelect.options[kategoriSelect.selectedIndex]?.text || '';
                let html = '';

                if (kategoriText.includes('Jurnal') || kategoriText.includes('Prosiding')) {
                    html = `
                        <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 space-y-4">
                            <h4 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-3">Detail Publikasi</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Publikasi</label>
                                    <select name="jenis_publikasi" class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="scopus">Scopus</option>
                                        <option value="sinta">Sinta</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Jurnal/Prosiding</label>
                                    <input type="text" name="nama_jurnal" class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Volume</label>
                                    <input type="text" name="volume" class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor</label>
                                    <input type="text" name="nomor" class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Halaman</label>
                                    <input type="text" name="halaman" class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">ISSN/ISBN</label>
                                    <input type="text" name="issn" class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                </div>
                            </div>
                        </div>
                    `;
                } else if (kategoriText.includes('HKI') || kategoriText.includes('Paten')) {
                    html = `
                        <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 space-y-4">
                            <h4 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-3">Detail HKI/Paten</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Pendaftaran</label>
                                    <input type="text" name="nomor_pendaftaran" class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status HKI</label>
                                    <input type="text" name="status_hki" class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                </div>
                            </div>
                        </div>
                    `;
                } else if (kategoriText.includes('Buku')) {
                    html = `
                        <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 space-y-4">
                            <h4 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-3">Detail Buku</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">ISBN</label>
                                    <input type="text" name="isbn" class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Penerbit</label>
                                    <input type="text" name="penerbit" class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Halaman</label>
                                    <input type="number" name="jumlah_halaman" class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                </div>
                            </div>
                        </div>
                    `;
                }

                dynamicFields.innerHTML = html;
            }

            kategoriSelect.addEventListener('change', loadDynamicFields);
            if (kategoriSelect.value) loadDynamicFields();
        }
    });
</script>