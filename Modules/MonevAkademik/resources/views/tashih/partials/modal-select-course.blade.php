<div x-show="openSelectCourse"
    class="fixed inset-0 z-[999998] flex items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">

    <div
        class="relative w-full max-w-4xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

        {{-- Header dengan Tombol Kembali di Atas --}}
        <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Langkah 1: Pilih Mata Kuliah</h3>
                <p class="text-sm text-gray-500 mt-1">Pilih mata kuliah yang akan Anda buatkan soal ujiannya.</p>
            </div>

            {{-- Tombol Kembali Pindah Ke Sini --}}
            <button @click="openSelectCourse = false" type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-bold text-yellow-900 shadow-sm hover:bg-yellow-500 transition-all dark:bg-yellow-500 dark:text-yellow-950 dark:hover:bg-yellow-400">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>

        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 mb-6">
            @foreach($myCourses as $course)
                <div @click="courseId = '{{ $course->id }}'; courseName = '{{ $course->course_name }}'"
                    class="cursor-pointer rounded-xl border-2 p-5 transition-all"
                    :class="courseId === '{{ $course->id }}' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-800'">
                    <span
                        class="inline-flex rounded-md bg-gray-100 px-2 py-1 text-xs font-bold text-gray-600 mb-2 dark:bg-gray-700 dark:text-gray-300">{{ $course->id }}</span>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white leading-tight"
                        :class="courseId === '{{ $course->id }}' ? 'text-blue-700 dark:text-blue-400' : ''">
                        {{ $course->course_name }}
                    </h4>
                </div>
            @endforeach
        </div>

        {{-- Footer Sekarang Sisa Tombol Lanjutkan Saja --}}
        <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
            <button :disabled="!courseId"
                @click="openSelectCourse = false; openCreate = true; setTimeout(() => { initCreateModal(courseId) }, 100);"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-bold text-white transition disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-700 shadow-md">
                Lanjutkan ke Form <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
</div>