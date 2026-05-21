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
        Alpine.data('formCreateInfrastructure', () => ({
            tingkatUnit: '',
            filterFakultas: '',
            unitPemilikId: '',
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

<div x-data="{ openCreate: false }"
    @open-create-modal.window="openCreate = true"
    x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openCreate = false"
        class="relative w-full max-w-4xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-white shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all overflow-hidden">

        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tambah Infrastruktur / Inventaris</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Masukkan detail aset, spesifikasi, dan ketersediaan barang.</p>
            </div>
            <button type="button" @click="openCreate = false"
                class="shrink-0 inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('masterdata.infrastructures.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col min-h-full" x-data="formCreateInfrastructure">
            @csrf
            <input type="hidden" name="unit_id" :value="unitPemilikId">

            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    {{-- Kiri: Informasi Dasar --}}
                    <div class="space-y-4 rounded-xl border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                    <h4 class="text-sm font-bold uppercase tracking-wide text-gray-700 dark:text-gray-300 border-b border-gray-200 pb-2 dark:border-gray-600">Info Utama</h4>
                    
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">
                            Nama Barang / Ruangan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="item_name" required value="{{ old('item_name') }}"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                            placeholder="Contoh: Proyektor Epson X500">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">
                            Kategori Tipe <span class="text-red-500">*</span>
                        </label>
                        <select name="inventory_type" required
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($inventoryTypes ?? [] as $type)
                                <option value="{{ $type->id }}" {{ old('inventory_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Merk Barang</label>
                            <input type="text" name="brand" value="{{ old('brand') }}"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                                placeholder="Cth: Epson, Olympic">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Satuan</label>
                            <select name="unit_measure"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="">-- Pilih Satuan --</option>
                                <option value="PCS" {{ old('unit_measure') == 'PCS' ? 'selected' : '' }}>PCS</option>
                                <option value="UNIT" {{ old('unit_measure') == 'UNIT' ? 'selected' : '' }}>UNIT</option>
                                <option value="SET" {{ old('unit_measure') == 'SET' ? 'selected' : '' }}>SET</option>
                                <option value="LEMBAR" {{ old('unit_measure') == 'LEMBAR' ? 'selected' : '' }}>LEMBAR</option>
                                <option value="PAK" {{ old('unit_measure') == 'PAK' ? 'selected' : '' }}>PAK</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Detail & Ketersediaan --}}
                <div class="space-y-4 rounded-xl border border-gray-100 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                    <h4 class="text-sm font-bold uppercase tracking-wide text-gray-700 dark:text-gray-300 border-b border-gray-200 pb-2 dark:border-gray-600">Manajemen Aset</h4>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">
                                Jumlah Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stock" required min="0" value="{{ old('stock', 0) }}"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Harga (Rp)</label>
                            <input type="number" name="price" min="0" value="{{ old('price', 0) }}"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 rounded-xl bg-blue-50/50 p-4 ring-1 ring-blue-100 dark:bg-blue-900/10 dark:ring-blue-900/30">
                            <h4 class="mb-4 text-xs font-bold uppercase tracking-widest text-blue-500 italic">Unit Pemilik</h4>

                            <div class="space-y-4">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tingkat Unit</label>
                                    <select x-model="tingkatUnit"
                                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                        <option value="">-- Universitas / Umum --</option>
                                        <option value="kampus">Universitas / Institut</option>
                                        <option value="fakultas">Fakultas</option>
                                        <option value="prodi">Program Studi</option>
                                    </select>
                                </div>

                                <div x-show="tingkatUnit === 'kampus'" x-cloak x-transition>
                                    <div class="rounded-lg border border-blue-200 bg-blue-100 px-4 py-3 text-sm text-blue-800 dark:border-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                        <span class="font-bold">Universitas Terpilih:</span> <span x-text="kampusName"></span>
                                    </div>
                                </div>

                                <div x-show="tingkatUnit === 'fakultas'" x-cloak x-transition>
                                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Pilih Fakultas</label>
                                    <select x-model="unitPemilikId" x-bind:required="tingkatUnit === 'fakultas'"
                                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                        <option value="">-- Pilih Fakultas --</option>
                                        <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                            <option :value="fakultas.id" x-text="fakultas.name"></option>
                                        </template>
                                    </select>
                                </div>

                                <div x-show="tingkatUnit === 'prodi'" x-cloak x-transition
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 rounded-lg bg-white p-4 ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-600">
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
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Status</label>
                            <select name="status" required
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>🟢 Aktif / Baik</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>🔴 Rusak / Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Foto Barang</label>
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:cursor-pointer file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 dark:text-gray-400 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600">
                        <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">Format: JPG, PNG, WEBP (Maks: 2MB)</p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Spesifikasi / Deskripsi</label>
                        <textarea name="description" rows="2"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                            placeholder="Catatan tambahan terkait barang ini...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            </div>

            <div class="shrink-0 flex flex-col sm:flex-row justify-end items-center border-t border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700 gap-3">
                <button type="button" @click="openCreate = false"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg bg-green-600 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-green-700 transition">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>