<div x-show="openDelete" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">
    <div @click.away="openDelete = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-800 text-center">
        
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 mb-4 dark:bg-red-900/30">
            <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 dark:text-red-400"></i>
        </div>

        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Mata Kuliah?</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Apakah Anda yakin ingin menghapus MK <strong class="text-gray-800 dark:text-gray-200">{{ $course->course_name }}</strong>?
        </p>

        <form action="{{ route('masterdata.courses.destroy', $course->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-center gap-3">
                <button type="button" @click="openDelete = false" class="rounded-lg bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 transition">Batal</button>
                <button type="submit" class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition">Ya, Hapus Data</button>
            </div>
        </form>
    </div>
</div>