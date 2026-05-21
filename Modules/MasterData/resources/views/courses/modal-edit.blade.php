<div x-data="{
        openEdit: false,
        isSubmitting: false,
        formUrl: '',
        editName: '',
        editFakultas: '',
        editUnit: '',
        editActive: '1',
        editProdis: [],
        async loadEditProdis() {
            this.editUnit = '';
            this.editProdis = [];
            if (!this.editFakultas) return;
            const response = await fetch(`{{ route('masterdata.courses.api.prodis') }}?fakultas_id=${this.editFakultas}`, { headers: { 'Accept': 'application/json' } });
            this.editProdis = await response.json();
        }
    }" @open-edit-modal.window="
        openEdit = true;
        isSubmitting = false;
        formUrl = $event.detail.url;
        editName = $event.detail.name;
        editFakultas = $event.detail.fakultas_id || '';
        editUnit = $event.detail.unit_id;
        editActive = $event.detail.active ? '1' : '0';
        fetch(`{{ route('masterdata.courses.api.prodis') }}?fakultas_id=${editFakultas}`, { headers: { 'Accept': 'application/json' } })
            .then(response => response.json())
            .then(data => editProdis = data)
            .catch(() => editProdis = []);
    " x-show="openEdit"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openEdit = false"
        class="relative w-full max-w-lg flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-white shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all overflow-hidden">

        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Mata Kuliah</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui informasi mata kuliah</p>
            </div>
            <button @click="openEdit = false" type="button"
                class="shrink-0 inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form :action="formUrl" method="POST" class="flex flex-col min-h-full" @submit="isSubmitting = true">
            @csrf
            @method('PUT')

            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama Mata Kuliah</label>
                    <input type="text" name="course_name" x-model="editName" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Fakultas</label>
                    <select x-model="editFakultas" @change="loadEditProdis" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="">-- Pilih Fakultas --</option>
                        @foreach($faculties as $fak)
                            <option value="{{ $fak->id }}">{{ $fak->unit_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Prodi Pengampu</label>
                    <select name="unit_id" x-model="editUnit" required :disabled="!editFakultas"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:opacity-70 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800">
                        <option value="">-- Pilih Prodi --</option>
                        <template x-for="prodi in editProdis" :key="prodi.id">
                            <option :value="prodi.id" x-text="prodi.unit_name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                    <select name="is_active" x-model="editActive" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="shrink-0 flex flex-col sm:flex-row justify-end items-center border-t border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700 gap-3">
                <button type="button" @click="openEdit = false"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" x-bind:disabled="isSubmitting"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg bg-amber-500 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-amber-600 transition disabled:opacity-50">
                    <span x-show="!isSubmitting"><i class="fas fa-save"></i> Simpan Perubahan</span>
                    <span x-show="isSubmitting">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>