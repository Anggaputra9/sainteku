@extends('layouts.app')

@section('content')
    <div class="mx-auto" x-data="coursesApp()" x-init="initData()" x-cloak>

        {{-- Header & Navigasi Breadcrumb --}}
        <div
            class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                 Master Data Mata Kuliah
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Master Data /</li>
                        <li class="text-blue-600 dark:text-blue-400">Mata Kuliah</li>
                    </ol>
                </nav>
            </div>

            <div>
                <button @click="$dispatch('open-create-modal')" type="button"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 sm:w-auto">
                <i class="fas fa-plus"></i>Tambah Mata Kuliah
                </button>
                @include('masterdata::courses.modal-create')
            </div>
        </div>


        {{-- Main Container --}}
        <div
            class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 min-h-[60vh] flex flex-col">

            {{-- AREA FILTER (Fakultas -> Prodi -> Search) --}}
            <div
                class="mb-6 space-y-4 bg-gray-50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- 1. Pilih Fakultas --}}
                    <div class="w-full">
                        <label
                            class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Fakultas</label>
                        <select x-model="filterFakultas" @change="fetchProdis(); fetchCourses();"
                            class="w-full rounded-lg border-[1.5px] border-gray-300 bg-white px-4 py-2.5 font-medium outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                            <option value="">-- Semua Fakultas --</option>
                            @foreach($faculties as $fak)
                                <option value="{{ $fak->id }}">{{ $fak->unit_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Pilih Prodi (Disabled sebelum Fakultas dipilih) --}}
                    <div class="w-full">
                        <label
                            class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Program
                            Studi</label>
                        <select x-model="filterProdi" @change="fetchCourses()" :disabled="!filterFakultas"
                            class="w-full rounded-lg border-[1.5px] border-gray-300 bg-white px-4 py-2.5 font-medium outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 disabled:bg-gray-100 disabled:opacity-60 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800">
                            <option value="">-- Semua Prodi --</option>
                            <template x-for="prd in prodisList" :key="prd.id">
                                <option :value="prd.id" x-text="prd.unit_name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- 3. Search Bar --}}
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" x-model="searchQuery" @input.debounce.500ms="fetchCourses()"
                        placeholder="Cari kode atau nama mata kuliah..."
                        class="w-full rounded-lg border-[1.5px] border-gray-300 bg-white pl-11 pr-4 py-2.5 font-medium outline-none focus:border-blue-500 transition dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            {{-- AREA KONTEN (TABEL) --}}
            <div class="flex-1 relative rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                {{-- State Loading --}}
                <div x-show="isLoading"
                    class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm">
                    <i class="fas fa-circle-notch fa-spin text-4xl mb-3 text-blue-600"></i>
                    <p class="font-bold text-sm tracking-widest text-blue-800 dark:text-blue-400 uppercase">Memuat Data...
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead
                            class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Kode MK</th>
                                <th class="px-6 py-4 font-semibold min-w-[200px]">Nama Mata Kuliah</th>
                                <th class="px-6 py-4 font-semibold min-w-[150px]">Prodi Pengampu</th>
                                <th class="px-6 py-4 font-semibold text-center">Status</th>
                                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">

                            {{-- State Kosong --}}
                            <tr x-show="coursesList.length === 0 && !isLoading">
                                <td colspan="5"
                                    class="px-6 py-12 text-center text-gray-500 bg-gray-50/50 dark:bg-gray-800/50">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-box-open text-4xl mb-3 opacity-30"></i>
                                        <p class="text-base font-medium italic">Mata kuliah tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>

                            {{-- Looping Data Tabel --}}
                            <template x-for="course in coursesList" :key="course.id">
                                <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white" x-text="course.id"></td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white leading-tight"
                                        x-text="course.course_name"></td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-block px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-md uppercase tracking-widest dark:bg-blue-900/30 dark:text-blue-400"
                                            x-text="course.prodi_name || 'UNIVERSAL'"></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span x-show="course.is_active == '1'"
                                            class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800 ring-1 ring-inset ring-green-600/20">Aktif</span>
                                        <span x-show="course.is_active == '0'"
                                            class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800 ring-1 ring-inset ring-red-600/20">Nonaktif</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- Tombol Edit Modal --}}
                                            <button type="button" @click="$dispatch('open-edit-modal', { 
                                                    url: '{{ url('masterdata/courses') }}/' + course.id,
                                                    id: course.id,
                                                    name: course.course_name,
                                                    unit_id: course.unit_id,
                                                    active: course.is_active == '1'
                                                })"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                                                title="Ubah Mata Kuliah">
                                                <i class="fa-solid fa-pencil"></i> Ubah
                                            </button>

                                            {{-- Tombol Delete Modal --}}
                                            <button type="button" @click="$dispatch('open-delete-modal', { 
                                                    url: '{{ url('masterdata/courses') }}/' + course.id,
                                                    name: course.course_name
                                                })"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
                                                title="Hapus Mata Kuliah">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- AREA PAGINATION (AJAX) --}}
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4"
                x-show="pagination.total > 0">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Menampilkan <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.from"></span>
                    - <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.to"></span>
                    dari <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.total"></span> data
                </span>
                <div class="flex gap-2">
                    <button @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url"
                        class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm transition dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        <i class="fa-solid fa-chevron-left mr-1"></i> Prev
                    </button>
                    <button @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url"
                        class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm transition dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        Next <i class="fa-solid fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>

        </div>
        {{-- End of Main Container --}}

    </div>
    {{-- End of Wrapper x-data="coursesApp()" --}}

    {{-- INCLUDE MODAL DI LUAR WRAPPER ALPINE UTAMA --}}
    @include('masterdata::courses.modal-edit')
    @include('masterdata::courses.delete-modal')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('coursesApp', () => ({
                filterFakultas: '',
                filterProdi: '',
                searchQuery: '',

                prodisList: [],
                coursesList: [],

                isLoading: false,
                pagination: {},

                initData() {
                    this.fetchCourses();
                },

                // 1. Ambil Prodi berdasarkan Fakultas
                async fetchProdis() {
                    this.filterProdi = ''; // Reset prodi saat fakultas berubah
                    this.prodisList = [];

                    if (!this.filterFakultas) return;

                    try {
                        const response = await fetch(`{{ route('masterdata.courses.api.prodis') }}?fakultas_id=${this.filterFakultas}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });
                        if (!response.ok) throw new Error(`Prodi API error: ${response.status}`);
                        this.prodisList = await response.json();
                    } catch (error) {
                        console.error("Gagal memuat prodi", error);
                    }
                },

                // 2. Ambil Data Mata Kuliah Server-Side
                async fetchCourses(page = 1) {
                    this.isLoading = true;
                    this.coursesList = []; // Kosongkan tabel saat loading

                    const params = new URLSearchParams({
                        page: page,
                        search: this.searchQuery,
                        fakultas_id: this.filterFakultas,
                        prodi_id: this.filterProdi
                    });

                    try {
                        const response = await fetch(`{{ route('masterdata.courses.api.data') }}?${params.toString()}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });
                        if (!response.ok) throw new Error('Network response was not ok');

                        const result = await response.json();
                        this.coursesList = result.data || [];

                        this.pagination = {
                            current_page: result.current_page,
                            from: result.from || 0,
                            to: result.to || 0,
                            total: result.total || 0,
                            prev_page_url: result.prev_page_url,
                            next_page_url: result.next_page_url
                        };
                    } catch (error) {
                        console.error("Gagal memuat mata kuliah", error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                changePage(page) {
                    this.fetchCourses(page);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }));
        });
    </script>
@endsection