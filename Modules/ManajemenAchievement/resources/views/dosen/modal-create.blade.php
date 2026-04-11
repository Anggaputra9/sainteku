<div x-show="openCreateDosen"
    class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
    style="display: none;">

    <div @click.away="openCreateDosen = false"
        x-show="openCreateDosen"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-lg transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all mt-10">

        {{-- Header Modal --}}
        <div class="mb-6 border-b border-gray-100 pb-4 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fas fa-plus-circle text-blue-500"></i>
                Ajukan Prestasi Dosen
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Isi form berikut untuk mengajukan prestasi baru
            </p>
        </div>

        {{-- Form --}}
        <form action="{{ route('dosen.repository.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-4">
                {{-- Kategori Prestasi --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Kategori Prestasi <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori_id" id="kategori_id_modal"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-500"
                        required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tingkat Prestasi --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tingkat Prestasi <span class="text-red-500">*</span>
                    </label>
                    <select name="tingkat_id"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-500"
                        required>
                        <option value="">Pilih Tingkat</option>
                        @foreach($tingkat as $t)
                        <option value="{{ $t->id }}" {{ old('tingkat_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Judul Karya --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Judul Karya <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="judul"
                        value="{{ old('judul') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-500"
                        placeholder="Masukkan judul karya"
                        required>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tanggal Prestasi <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                        name="tanggal"
                        value="{{ old('tanggal') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-500"
                        required>
                </div>

                {{-- Penyelenggara --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Penyelenggara/Penerbit
                    </label>
                    <input type="text"
                        name="penyelenggara"
                        value="{{ old('penyelenggara') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-500"
                        placeholder="Nama penyelenggara/penerbit">
                </div>

                {{-- URL --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        URL
                    </label>
                    <input type="url"
                        name="url"
                        value="{{ old('url') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-500"
                        placeholder="https://...">
                </div>

                {{-- FIELD DINAMIS (akan diisi JavaScript) --}}
                <div id="dynamic-fields-modal" class="space-y-4"></div>

                {{-- Deskripsi --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi"
                        rows="3"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-500"
                        placeholder="Deskripsi prestasi...">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Upload File --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Upload File
                    </label>
                    <input type="file"
                        name="file"
                        accept=".pdf,.doc,.docx"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:file:bg-gray-600 dark:file:text-gray-300">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Format: PDF, DOC, DOCX (Max 5MB)
                    </p>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end mt-6">
                <button type="button"
                    @click="openCreateDosen = false"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-save"></i>
                    Simpan Prestasi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript untuk Field Dinamis --}}
<script>
    document.addEventListener('alpine:init', () => {
        // Tunggu sampai DOM siap
        setTimeout(() => {
            const kategoriSelect = document.getElementById('kategori_id_modal');
            const dynamicFields = document.getElementById('dynamic-fields-modal');

            if (!kategoriSelect) return;

            function loadDynamicFields() {
                const kategoriId = kategoriSelect.value;
                const kategoriText = kategoriSelect.options[kategoriSelect.selectedIndex]?.text || '';

                let html = '';

                if (kategoriText.includes('Jurnal') || kategoriText.includes('Prosiding')) {
                    html = `
                    <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700/50 space-y-4">
                        <h4 class="font-medium text-gray-900 dark:text-white">Detail Publikasi</h4>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Publikasi</label>
                            <select name="jenis_publikasi" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Jenis</option>
                                <option value="scopus">Scopus</option>
                                <option value="sinta">Sinta</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Jurnal/Prosiding</label>
                            <input type="text" name="nama_jurnal" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Volume</label>
                                <input type="text" name="volume" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor</label>
                                <input type="text" name="nomor" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Halaman</label>
                                <input type="text" name="halaman" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">ISSN</label>
                            <input type="text" name="issn" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                `;
                } else if (kategoriText.includes('HKI') || kategoriText.includes('Paten')) {
                    html = `
                    <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700/50 space-y-4">
                        <h4 class="font-medium text-gray-900 dark:text-white">Detail HKI/Paten</h4>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Pendaftaran</label>
                            <input type="text" name="nomor_pendaftaran" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status HKI</label>
                            <input type="text" name="status_hki" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                `;
                } else if (kategoriText.includes('Buku')) {
                    html = `
                    <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700/50 space-y-4">
                        <h4 class="font-medium text-gray-900 dark:text-white">Detail Buku</h4>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">ISBN</label>
                            <input type="text" name="isbn" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Penerbit</label>
                            <input type="text" name="penerbit" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Halaman</label>
                            <input type="number" name="jumlah_halaman" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                `;
                }

                dynamicFields.innerHTML = html;
            }

            kategoriSelect.addEventListener('change', loadDynamicFields);

            // Load fields jika sudah ada pilihan (misalnya old value)
            if (kategoriSelect.value) {
                loadDynamicFields();
            }
        }, 100);
    });
</script>