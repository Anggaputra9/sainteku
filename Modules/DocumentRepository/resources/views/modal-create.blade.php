@php
    $kampus = ($units ?? collect())->first(fn($u) => $u->id === $u->unit_parent);
    $kampusId = $kampus->id ?? 'U001';
    $kampusName = $kampus->unit_name ?? 'UIN Prof. K.H. Saifuddin Zuhri';

    $fakultasList = ($units ?? collect())->filter(fn($u) => $u->unit_parent === $kampusId && $u->id !== $kampusId)->values();
    $listFakultasArr = $fakultasList->map(fn($f) => ['id' => $f->id, 'name' => $f->unit_name])->toArray();

    $fakultasIds = $fakultasList->pluck('id')->toArray();
    $prodiList = ($units ?? collect())->filter(fn($u) => in_array($u->unit_parent, $fakultasIds))->values();
    $listProdiArr = $prodiList->map(fn($p) => ['id' => $p->id, 'name' => $p->unit_name, 'parent' => $p->unit_parent])->toArray();
@endphp

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formCreateDocument', () => ({
            tingkatUnit: '',
            filterFakultas: '',
            unitPemilikId: '{{ old('unit_id') }}',
            fileName: '',
            kampusId: '{{ $kampusId }}',
            kampusName: '{{ $kampusName }}',
            listFakultas: @json($listFakultasArr),
            listProdi: @json($listProdiArr),
            init() {
                this.$watch('tingkatUnit', value => {
                    this.filterFakultas = '';
                    this.unitPemilikId = value === 'kampus' ? this.kampusId : '';
                });
            }
        }))
    })
</script>

{{-- MODAL TAMBAH DOKUMEN (Desain Premium) --}}
<div x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
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
        class="flex flex-col w-full max-w-4xl max-h-[90vh] rounded-2xl bg-white shadow-2xl dark:bg-gray-800 overflow-hidden">

        {{-- Header Modal --}}
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Unggah Dokumen Baru</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan dokumen fisik ke dalam repositori <span
                        class="font-semibold text-blue-600 dark:text-blue-400">Sainteku</span></p>
            </div>
        </div>

        {{-- Form Area --}}
        <form action="{{ route('DocumentRepository.store') }}" method="POST" enctype="multipart/form-data"
            class="flex flex-col flex-1 min-h-0" x-data="formCreateDocument">
            @csrf
            <input type="hidden" name="unit_id" :value="unitPemilikId" required>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                {{-- Judul Dokumen (Full Width) --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Judul Dokumen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="document_title" value="{{ old('document_title') }}" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Contoh: SK Rektor Tahun 2026 tentang Akademik">
                </div>

                {{-- Tipe Dokumen --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tipe Dokumen <span class="text-red-500">*</span>
                    </label>
                    <select name="document_type_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Tipe --</option>
                        @foreach ($documentTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->description }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Unit Pemilik --}}
                <div class="rounded-xl bg-blue-50/50 p-4 ring-1 ring-blue-100 dark:bg-blue-900/10 dark:ring-blue-900/30">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tingkat Unit Pemilik <span class="text-red-500">*</span>
                    </label>
                    <select name="tingkat_unit" x-model="tingkatUnit" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Tingkat Unit --</option>
                        <option value="kampus">Universitas / Institut</option>
                        <option value="fakultas">Fakultas</option>
                        <option value="prodi">Program Studi</option>
                    </select>

                    <div class="mt-3" x-show="tingkatUnit === 'kampus'" x-cloak x-transition>
                        <div class="rounded-lg border border-blue-200 bg-blue-100 px-4 py-3 text-sm text-blue-800 dark:border-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                            <span class="font-bold">Universitas Terpilih:</span> <span x-text="kampusName"></span>
                        </div>
                    </div>

                    <div class="mt-3" x-show="tingkatUnit === 'fakultas'" x-cloak x-transition>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Pilih Fakultas</label>
                        <select x-model="unitPemilikId" x-bind:required="tingkatUnit === 'fakultas'"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                            <option value="">-- Pilih Fakultas --</option>
                            <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                <option :value="fakultas.id" x-text="fakultas.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="mt-3 grid grid-cols-1 gap-3 rounded-lg bg-white p-3 ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-600" x-show="tingkatUnit === 'prodi'" x-cloak x-transition>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Filter Fakultas</label>
                            <select x-model="filterFakultas"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="">-- Tampilkan Semua Prodi --</option>
                                <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                    <option :value="fakultas.id" x-text="fakultas.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Pilih Prodi</label>
                            <select x-model="unitPemilikId" x-bind:required="tingkatUnit === 'prodi'"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="">-- Pilih Prodi --</option>
                                <template x-for="prodi in listProdi.filter(p => filterFakultas === '' || p.parent === filterFakultas)" :key="prodi.id">
                                    <option :value="prodi.id" x-text="prodi.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Area Tanggal (Box Khusus) --}}
                <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 md:col-span-2">
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-widest text-gray-400 italic">Masa Berlaku
                        Dokumen</h4>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        {{-- Tanggal Berlaku --}}
                        {{-- Tanggal Berlaku --}}
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tanggal
                                Berlaku <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i
                                        class="fa-regular fa-calendar"></i></span>
                                <input type="text" name="effective_date" value="{{ old('effective_date', now()->toDateString()) }}" required
                                    placeholder="Pilih Tanggal..." x-init="flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd F Y', allowInput: false, static: true })"
                                    class="w-full rounded-lg border-0 py-2.5 pl-10 pr-4 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 dark:text-white dark:ring-gray-600 cursor-pointer">
                            </div>
                        </div>

                        {{-- Tanggal Kadaluarsa --}}
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tanggal
                                Kadaluarsa</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i
                                        class="fa-regular fa-calendar"></i></span>
                                <input type="text" name="expired_date" value="{{ old('expired_date') }}"
                                    placeholder="Pilih Tanggal..." x-init="flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd F Y', allowInput: false, static: true })"
                                    class="w-full rounded-lg border-0 py-2.5 pl-10 pr-4 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 dark:text-white dark:ring-gray-600 cursor-pointer">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Area File Upload --}}
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
                                    {{-- Tambahkan @change agar Alpine menangkap nama filenya --}}
                                    <input id="file-upload" name="document_file" type="file" class="sr-only"
                                        accept=".pdf,.doc,.docx" required
                                        @change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''">
                                </label>
                            </div>
                            
                            {{-- Teks Bawaan (Hilang kalau ada file) --}}
                            <p x-show="!fileName" class="text-xs leading-5 text-gray-500 dark:text-gray-500 mt-2">PDF, DOC, DOCX maksimal 10MB</p>
                            
                            {{-- Teks Sukses (Muncul menampilkan nama file yang dipilih) --}}
                            <p x-show="fileName" x-cloak class="text-sm font-bold text-green-600 dark:text-green-400 mt-2 flex items-center justify-center gap-1">
                                <i class="fa-solid fa-check-circle"></i> <span x-text="fileName"></span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            </div>

            {{-- Tombol Aksi Bawah --}}
            <div
                class="shrink-0 flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800/80 sm:flex-row sm:justify-end">
                <button type="button" @click="openCreate = false"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
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
</style>
