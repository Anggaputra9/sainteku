<div x-show="openEdit" class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">
    
    <div @click.away="openEdit = false" x-show="openEdit" class="relative w-full max-w-lg transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all mt-10">
        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Mata Kuliah</h3>
            <button @click="openEdit = false" type="button" class="text-gray-400 hover:text-gray-900 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        {{-- Tambahkan x-data="{ isSubmitting: false }" dan @submit --}}
<form action="{{ route('masterdata.courses.update', $course->id) }}" method="POST" class="space-y-5 text-left" 
      x-data="{ isSubmitting: false }" 
      @submit="isSubmitting = true">
    @csrf
    @method('PUT')
    
    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
        <button type="button" @click="openEdit = false" class="...">Batal</button>
        
        {{-- Ubah tombol Submit untuk mendeteksi isSubmitting --}}
        <button type="submit" 
            x-bind:disabled="isSubmitting"
            x-bind:class="isSubmitting ? 'bg-blue-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
            class="rounded-lg px-5 py-2 text-sm font-semibold text-white transition flex items-center gap-2">
            
            {{-- Ikon Loading berputar (Muncul saat isSubmitting true) --}}
            <i class="fa-solid fa-spinner fa-spin" x-show="isSubmitting" x-cloak></i>
            
            {{-- Teks berubah saat diklik --}}
            <span x-text="isSubmitting ? 'Memproses...' : 'Perbarui Data'"></span>
        </button>
    </div>
</form>
    </div>
</div>