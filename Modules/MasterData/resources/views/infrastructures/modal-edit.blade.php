<div x-data="{
    openEdit: false,
    url: '',
    itemData: { id: '', description: '', type: '', quantity: '' }
}"
    @open-edit-modal.window="
        openEdit = true; 
        url = $event.detail.url;
        itemData.id = $event.detail.id;
        itemData.description = $event.detail.description;
        itemData.type = $event.detail.type;
        itemData.quantity = $event.detail.quantity;
    "
    x-show="openEdit"
    class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak style="display: none;">

    <div @click.away="openEdit = false" x-show="openEdit"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative my-auto w-full max-w-2xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Infrastruktur</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Memperbarui informasi: <span class="font-semibold text-amber-600 dark:text-amber-400" x-text="itemData.description"></span></p>
            </div>
            <button type="button" @click="openEdit = false"
                class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        </div>

        <form :action="url" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">ID Infrastruktur</label>
                    <input type="text" name="id" x-model="itemData.id" readonly 
                        class="w-full rounded-lg border-0 bg-gray-50 px-4 py-2.5 text-gray-500 ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 cursor-not-allowed dark:bg-gray-900/50 dark:text-gray-400 dark:ring-gray-700" 
                        title="ID tidak dapat diubah">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Nama Barang / Ruangan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="description" x-model="itemData.description" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Kategori Tipe <span class="text-red-500">*</span>
                        </label>
                        <select name="inventory_type" x-model="itemData.type" required
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($inventoryTypes ?? [] as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Kuantitas (Jumlah) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantity" x-model="itemData.quantity" required min="0"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end mt-6">
                <button type="button" @click="openEdit = false"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-yellow-500 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-200 transition dark:focus:ring-yellow-900">
                    <i class="fas fa-xmark"></i>
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-save"></i>
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>