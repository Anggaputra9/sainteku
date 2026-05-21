<div x-data="{
    openEdit: false,
    url: '',
    itemData: { 
        id: '', item_name: '', type: '', brand: '', unit_measure: '', 
        stock: '', price: '', status: '', unit_id: '', description: '' 
    }
}"
    @open-edit-modal.window="
        openEdit = true; 
        url = $event.detail.url;
        itemData.id = $event.detail.id;
        itemData.item_name = $event.detail.item_name;
        itemData.type = $event.detail.type;
        itemData.brand = $event.detail.brand;
        itemData.unit_measure = $event.detail.unit_measure;
        itemData.stock = $event.detail.stock;
        itemData.price = $event.detail.price;
        itemData.status = $event.detail.status;
        itemData.unit_id = $event.detail.unit_id;
        itemData.description = $event.detail.description;
    "
    x-show="openEdit"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openEdit = false"
        class="relative w-full max-w-4xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-white shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all overflow-hidden">

        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Infrastruktur</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Memperbarui informasi: <span class="font-semibold text-amber-600 dark:text-amber-400" x-text="itemData.item_name"></span></p>
            </div>
            <button type="button" @click="openEdit = false"
                class="shrink-0 inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form :action="url" method="POST" enctype="multipart/form-data" class="flex flex-col min-h-full">
            @csrf
            @method('PUT')

            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    {{-- Kiri: Informasi Dasar --}}
                    <div class="space-y-4 rounded-xl border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <h4 class="text-sm font-bold uppercase tracking-wide text-gray-700 dark:text-gray-300 border-b border-gray-200 pb-2 dark:border-gray-600">Info Utama</h4>
                    
                    <div class="flex gap-4">
                        <div class="w-1/3">
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">ID</label>
                            <input type="text" name="id" x-model="itemData.id" readonly 
                                class="w-full rounded-lg border-0 bg-gray-100 px-4 py-2.5 text-gray-500 ring-1 ring-gray-200 cursor-not-allowed dark:bg-gray-900/50 dark:ring-gray-700">
                        </div>
                        <div class="w-2/3">
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">
                                Nama Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="item_name" x-model="itemData.item_name" required
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">
                            Kategori Tipe <span class="text-red-500">*</span>
                        </label>
                        <select name="inventory_type" x-model="itemData.type" required
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($inventoryTypes ?? [] as $type)
                                <option value="{{ $type->id }}">{{ $type->description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Merk Barang</label>
                            <input type="text" name="brand" x-model="itemData.brand"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Satuan</label>
                            <select name="unit_measure" x-model="itemData.unit_measure"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="">-- Pilih Satuan --</option>
                                <option value="PCS">PCS</option>
                                <option value="UNIT">UNIT</option>
                                <option value="SET">SET</option>
                                <option value="LEMBAR">LEMBAR</option>
                                <option value="PAK">PAK</option>
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
                            <input type="number" name="stock" x-model="itemData.stock" required min="0"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Harga (Rp)</label>
                            <input type="number" name="price" x-model="itemData.price" min="0"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Unit Pemilik</label>
                            <select name="unit_id" x-model="itemData.unit_id"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="">-- Universitas / Umum --</option>
                                @foreach($units ?? [] as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Status</label>
                            <select name="status" x-model="itemData.status" required
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="1">🟢 Aktif / Baik</option>
                                <option value="0">🔴 Rusak / Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Ubah Foto (Opsional)</label>
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:cursor-pointer file:rounded-md file:border-0 file:bg-amber-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-amber-700 hover:file:bg-amber-100 dark:text-gray-400 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600">
                        <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">Biarkan kosong jika tidak ingin mengubah foto saat ini.</p>
                    </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Spesifikasi / Deskripsi</label>
                            <textarea name="description" x-model="itemData.description" rows="2"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:border-gray-600"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shrink-0 flex flex-col sm:flex-row justify-end items-center border-t border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700 gap-3">
                <button type="button" @click="openEdit = false"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg bg-amber-500 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-amber-600 transition">
                    <i class="fas fa-save"></i> Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>