{{-- MODAL TAMBAH MATA KULIAH --}}
<div x-data="{ openCreate: false }" @open-create-modal.window="openCreate = true" x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak
    @keydown.escape.window="openCreate = false"
    x-init="if (Object.keys(@js($errors->messages())).length > 0) openCreate = true">

    <div @click.away="openCreate = false"
        class="relative w-full max-w-2xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-white shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all overflow-hidden">

        {{-- Header Modal --}}
        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tambah Mata Kuliah</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan mata kuliah baru ke program studi</p>
            </div>
            <button @click="openCreate = false" type="button"
                class="shrink-0 inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        {{-- Error Alert Banner --}}
        @if ($errors->any())
            <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg dark:bg-red-900/20 dark:border-red-400">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-600 dark:text-red-400 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-bold text-red-700 dark:text-red-400 mb-2">Validasi Gagal!</p>
                        <ul class="text-xs text-red-600 dark:text-red-300 space-y-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form Area --}}
        <form action="{{ route('masterdata.courses.store') }}" method="POST" class="flex flex-col min-h-full">
            @csrf

            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 gap-x-6 gap-y-6 md:grid-cols-2">
                
                {{-- Kode MK (Auto) --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Kode Mata Kuliah
                    </label>
                    <input type="text" disabled placeholder="Dibuat otomatis oleh sistem"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 bg-gray-100 dark:bg-gray-900 dark:text-white dark:ring-gray-600 cursor-not-allowed opacity-60">
                </div>

                {{-- Nama Mata Kuliah (Full Width) --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Nama Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="course_name" required placeholder="Contoh: Pemrograman Web" 
                        value="{{ old('course_name') }}"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 @error('course_name') ring-red-500 @else ring-gray-300 @enderror focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white">
                    @error('course_name')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Area Organisasi (Box Khusus) --}}
                <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 md:col-span-2">
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-widest text-gray-400 italic">Organisasi Pengampu</h4>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        {{-- Fakultas --}}
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                                Fakultas <span class="text-red-500">*</span>
                            </label>
                            <select id="select_fakultas" required
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="">-- Pilih Fakultas --</option>
                                @foreach($faculties as $fak)
                                    <option value="{{ $fak->id }}" @selected(old('fakultas_id') == $fak->id)>{{ $fak->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Prodi Pengampu --}}
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                                Program Studi (Pengampu) <span class="text-red-500">*</span>
                            </label>
                            <select id="select_prodi" name="unit_id" required
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 @error('unit_id') ring-red-500 @else ring-gray-300 @enderror focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white">
                                <option value="">-- Pilih Prodi --</option>
                            </select>
                            @error('unit_id')
                                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Status --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="is_active" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="1" @selected(old('is_active') == '1' || !$errors->any())>Aktif</option>
                        <option value="0" @selected(old('is_active') == '0')>Nonaktif</option>
                    </select>
                </div>

            </div>
            </div>

            {{-- Action Buttons --}}
            <div class="shrink-0 flex flex-col sm:flex-row justify-end items-center border-t border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700 gap-3">
                <button type="button" @click="openCreate = false"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg bg-green-600 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-green-700 transition">
                    <i class="fas fa-save"></i> Simpan Mata Kuliah
                </button>
            </div>

        </form>
    </div>
</div>

{{-- SCRIPT: Handle Fakultas -> Prodi Cascade --}}
<script>
// Function to fetch and populate prodi
async function fetchAndPopulateProdi(fakultasId, selectProdi, preselectedProdiId = null) {
    if (!fakultasId) {
        selectProdi.innerHTML = '<option value="">-- Pilih Prodi --</option>';
        selectProdi.disabled = true;
        return;
    }

    try {
        const url = `{{ route('masterdata.courses.api.prodis') }}?fakultas_id=${fakultasId}`;
        console.log('📡 Fetching prodi from:', url);
        
        const response = await fetch(url, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP Error ${response.status}`);
        }

        const prodis = await response.json();
        console.log('✅ Prodis received:', prodis);
        
        if (!Array.isArray(prodis)) {
            throw new Error('Invalid response format');
        }

        // Reset prodi select
        selectProdi.innerHTML = '<option value="">-- Pilih Prodi --</option>';
        selectProdi.disabled = false;

        // Populate prodi options
        if (prodis.length === 0) {
            selectProdi.innerHTML = '<option value="">-- Tidak ada Prodi --</option>';
            selectProdi.disabled = true;
            return;
        }

        prodis.forEach(prodi => {
            const option = document.createElement('option');
            option.value = prodi.id;
            option.textContent = prodi.unit_name;
            selectProdi.appendChild(option);

            // Pre-select jika ada old value
            if (preselectedProdiId && prodi.id == preselectedProdiId) {
                option.selected = true;
            }
        });

        console.log('✅ Prodi options populated successfully');

    } catch (error) {
        console.error('❌ Error fetching prodi:', error);
        selectProdi.innerHTML = '<option value="">❌ Error loading prodi</option>';
        selectProdi.disabled = true;
    }
}

// Main initialization function
function initializeProdiFilter() {
    const selectFakultas = document.getElementById('select_fakultas');
    const selectProdi = document.getElementById('select_prodi');

    if (!selectFakultas || !selectProdi) {
        console.warn('⚠️ Prodi filter elements not found, will retry...');
        setTimeout(initializeProdiFilter, 500);
        return;
    }

    console.log('🔧 Initializing prodi filter...');

    // Handle change event
    selectFakultas.addEventListener('change', async function() {
        console.log('📝 Fakultas changed to:', this.value);
        await fetchAndPopulateProdi(this.value, selectProdi);
    });

    // Auto-populate on load if there's old fakultas value
    const currentFakultasId = selectFakultas.value;
    const preselectedProdiId = '{{ old("unit_id") }}' || null;

    if (currentFakultasId) {
        console.log('🔄 Auto-fetching prodi for existing fakultas:', currentFakultasId);
        console.log('📌 Pre-selected prodi ID:', preselectedProdiId);
        fetchAndPopulateProdi(currentFakultasId, selectProdi, preselectedProdiId || null);
    }
}

// Multiple event triggers untuk ensure initialization
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeProdiFilter);
} else {
    initializeProdiFilter();
}

// Also initialize on Alpine init for late-loaded modals
document.addEventListener('alpine:init', () => {
    setTimeout(initializeProdiFilter, 100);
});

// Final fallback
setTimeout(initializeProdiFilter, 1000);
</script>
