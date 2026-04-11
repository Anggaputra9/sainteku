@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Edit Prestasi Dosen
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Manajemen Achievement /</li>
                        <li><a href="{{ route('dosen.repository.index') }}" class="hover:text-blue-600">Repository Dosen</a> /</li>
                        <li class="text-blue-600 dark:text-blue-400">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Form Edit --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    <i class="far fa-pen-to-square mr-2 text-amber-500"></i>
                    Form Edit Prestasi Dosen
                </h3>
            </div>

            <form action="{{ route('dosen.repository.update', $achievement->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kategori --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Kategori Prestasi <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori_id" id="kategori_id" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ $achievement->kategori_id == $kat->id ? 'selected' : '' }}>
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
                        <select name="tingkat_id" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih Tingkat</option>
                            @foreach($tingkat as $t)
                            <option value="{{ $t->id }}" {{ $achievement->tingkat_id == $t->id ? 'selected' : '' }}>
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
                        <input type="text" name="judul" value="{{ old('judul', $achievement->judul) }}" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Masukkan judul karya/prestasi">
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $achievement->tanggal) }}" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>

                    {{-- Penyelenggara --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Penyelenggara/Penerbit
                        </label>
                        <input type="text" name="penyelenggara" value="{{ old('penyelenggara', $achievement->penyelenggara) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Nama penyelenggara/penerbit">
                    </div>

                    {{-- URL --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            URL
                        </label>
                        <input type="url" name="url" value="{{ old('url', $achievement->url) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="https://...">
                    </div>

                    {{-- FIELD DINAMIS (akan diisi JavaScript) --}}
                    <div id="dynamic-fields" class="md:col-span-2 space-y-4"></div>

                    {{-- Deskripsi --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" rows="4"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Deskripsikan prestasi Anda...">{{ old('deskripsi', $achievement->deskripsi) }}</textarea>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <a href="{{ route('dosen.repository.index') }}"
                        class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-600">
                        <i class="fas fa-save mr-1"></i>
                        Update Prestasi
                    </button>
                </div>
            </form>
        </div>

        {{-- Info File (Jika Ada) --}}
        @if($achievement->file_path)
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-paperclip mr-2 text-blue-500"></i>
                    File Pendukung
                </h3>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $achievement->file_name }}
                        </p>
                    </div>
                    <a href="{{ route('dosen.repository.download', $achievement->id) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 p-2 text-white hover:bg-blue-700 transition"
                        title="Download">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
                <p class="mt-2 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    File tidak dapat diubah melalui halaman ini. Hubungi admin jika perlu mengganti file.
                </p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- JavaScript untuk Field Dinamis --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ✅ GUNAKAN JSON_ENCODE BIAR AMAN
        const achievement = @json($achievement ?? []);

        const kategoriSelect = document.getElementById('kategori_id');
        const dynamicFields = document.getElementById('dynamic-fields');

        if (!kategoriSelect) return;

        function loadDynamicFields() {
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
                            <option value="scopus" ${achievement.jenis_publikasi == 'scopus' ? 'selected' : ''}>Scopus</option>
                            <option value="sinta" ${achievement.jenis_publikasi == 'sinta' ? 'selected' : ''}>Sinta</option>
                            <option value="lainnya" ${achievement.jenis_publikasi == 'lainnya' ? 'selected' : ''}>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Jurnal/Prosiding</label>
                        <input type="text" name="nama_jurnal" value="${achievement.nama_jurnal || ''}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Volume</label>
                            <input type="text" name="volume" value="${achievement.volume || ''}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor</label>
                            <input type="text" name="nomor" value="${achievement.nomor || ''}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Halaman</label>
                            <input type="text" name="halaman" value="${achievement.halaman || ''}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">ISSN</label>
                        <input type="text" name="issn" value="${achievement.issn || ''}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            `;
            } else if (kategoriText.includes('HKI') || kategoriText.includes('Paten')) {
                html = `
                <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700/50 space-y-4">
                    <h4 class="font-medium text-gray-900 dark:text-white">Detail HKI/Paten</h4>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Pendaftaran</label>
                        <input type="text" name="nomor_pendaftaran" value="${achievement.nomor_pendaftaran || ''}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status HKI</label>
                        <input type="text" name="status_hki" value="${achievement.status_hki || ''}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            `;
            } else if (kategoriText.includes('Buku')) {
                html = `
                <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700/50 space-y-4">
                    <h4 class="font-medium text-gray-900 dark:text-white">Detail Buku</h4>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">ISBN</label>
                        <input type="text" name="isbn" value="${achievement.isbn || ''}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Penerbit</label>
                        <input type="text" name="penerbit" value="${achievement.penerbit || ''}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Halaman</label>
                        <input type="number" name="jumlah_halaman" value="${achievement.jumlah_halaman || ''}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            `;
            }

            dynamicFields.innerHTML = html;
        }

        kategoriSelect.addEventListener('change', loadDynamicFields);

        if (kategoriSelect.value) {
            loadDynamicFields();
        }
    });
</script>
@endsection