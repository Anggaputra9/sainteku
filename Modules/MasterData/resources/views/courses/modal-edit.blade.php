<div x-data="{
        openEdit: false,
        isSubmitting: false,
        formUrl: '',
        editName: '',
        editUnit: '',
        editActive: '1'
    }" @open-edit-modal.window="
        openEdit = true;
        formUrl = $event.detail.url;
        editName = $event.detail.name;
        editUnit = $event.detail.unit_id;
        editActive = $event.detail.active ? '1' : '0';
    " x-show="openEdit" style="display: none;"
    class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
    <div @click.outside="openEdit = false" class="w-full max-w-lg rounded-xl bg-white p-6 shadow-2xl dark:bg-gray-800">
        <div class="mb-5 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Mata Kuliah</h3>
            <button @click="openEdit = false" type="button"
                class="text-gray-400 hover:text-gray-900 transition dark:hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form :action="formUrl" method="POST" class="space-y-5 text-left" @submit="isSubmitting = true">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama Mata
                    Kuliah</label>
                <input type="text" name="course_name" x-model="editName" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Prodi Pengampu</label>
                <select name="unit_id" x-model="editUnit" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">-- Pilih Prodi --</option>
                    @php
                        $allProdi = \Illuminate\Support\Facades\DB::table('mst_unit')->where('unit_type_id', 3)->where('is_active', '1')->get();
                    @endphp
                    @foreach($allProdi as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->unit_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                <select name="is_active" x-model="editActive" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                <button type="submit" x-bind:disabled="isSubmitting"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 transition disabled:opacity-50 flex items-center gap-2">
                    <span x-show="!isSubmitting">Simpan Perubahan</span>
                    <span x-show="isSubmitting" style="display: none;"><i class="fas fa-save"></i>
                        Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>