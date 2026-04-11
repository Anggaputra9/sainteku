@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="coursesApp()" x-init="initData()" x-cloak>

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Master Data Mata Kuliah</h2>
            <nav>
                <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                    <li>Master Data /</li>
                    <li class="text-blue-600 dark:text-blue-400">Mata Kuliah</li>
                </ol>
            </nav>
        </div>
        
        <div x-data="{ openCreate: false }">
            <button @click="openCreate = true" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                <i class="fa-solid fa-plus"></i> Tambah Mata Kuliah
            </button>
            @include('masterdata::courses.modal-create')
        </div>
    </div>

    {{-- Multi-Step Filter (Fakultas -> Prodi -> Search) --}}
    <div class="space-y-4 bg-gray-50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- 1. Pilih Fakultas --}}
            <div class="w-full">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Fakultas</label>
                <select x-model="filterFakultas" @change="fetchProdis(); fetchCourses();"
                    class="w-full rounded-lg border-[1.5px] border-gray-300 bg-white px-4 py-2.5 font-medium outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">-- Semua Fakultas --</option>
                    @foreach($faculties as $fak)
                        <option value="{{ $fak->id }}">{{ $fak->unit_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 2. Pilih Prodi --}}
            <div class="w-full">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Program Studi</label>
                <select x-model="filterProdi" @change="fetchCourses()" :disabled="!filterFakultas"
                    class="w-full rounded-lg border-[1.5px] border-gray-300 bg-white px-4 py-2.5 font-medium outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:bg-gray-100 disabled:opacity-60 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800">
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
                class="w-full rounded-lg border-[1.5px] border-gray-300 bg-white pl-11 pr-4 py-2.5 font-medium outline-none focus:border-indigo-500 transition dark:bg-gray-900 dark:border-gray-600 dark:text-white">
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 relative min-h-[300px]">
        
        {{-- Loading Overlay --}}
        <div x-show="isLoading" class="absolute inset-0 z-10 flex items-center justify-center bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl">
            <i class="fa-solid fa-circle-notch fa-spin text-4xl text-indigo-500"></i>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Kode MK</th>
                        <th class="px-6 py-4 font-semibold">Nama Mata Kuliah</th>
                        <th class="px-6 py-4 font-semibold">Prodi Pengampu</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-if="coursesList.length === 0 && !isLoading">
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada data mata kuliah.</td></tr>
                    </template>

                    <template x-for="course in coursesList" :key="course.id">
                        <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/30">
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white" x-text="course.id"></td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white" x-text="course.course_name"></td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300" x-text="course.prodi_name || '-'"></td>
                            <td class="px-6 py-4 text-center">
                                <span x-show="course.is_active == '1'" class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800 ring-1 ring-inset ring-green-600/20">Aktif</span>
                                <span x-show="course.is_active == '0'" class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800 ring-1 ring-inset ring-red-600/20">Nonaktif</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div x-data="{ openEdit: false }">
                                        <button @click="openEdit = true" class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800 transition shadow-sm" title="Ubah Data">
                                            <i class="fa-solid fa-pencil"></i> Ubah
                                        </button>
                                        {{-- Catatan: Untuk openEdit di sistem SPA (Single Page), butuh penyesuaian modal khusus yang akan kita bahas jika Anda butuh --}}
                                    </div>
                                    <div x-data="{ openDelete: false }">
                                        <button @click="openDelete = true" class="inline-flex items-center gap-1.5 rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900 transition shadow-sm" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Custom Pagination Layout (AJAX) --}}
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 rounded-b-xl flex justify-between items-center" x-show="pagination.total > 0">
            <span class="text-sm text-gray-500">Menampilkan <span class="font-bold" x-text="pagination.from"></span> - <span class="font-bold" x-text="pagination.to"></span> dari <span class="font-bold" x-text="pagination.total"></span> data</span>
            <div class="flex gap-2">
                <button @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url" class="px-3 py-1 rounded border border-gray-300 bg-white text-sm hover:bg-gray-100 disabled:opacity-50">Sebelumnya</button>
                <button @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url" class="px-3 py-1 rounded border border-gray-300 bg-white text-sm hover:bg-gray-100 disabled:opacity-50">Selanjutnya</button>
            </div>
        </div>
    </div>
</div>

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

            // Ambil Prodi berdasarkan Fakultas
            async fetchProdis() {
                this.filterProdi = ''; // Reset prodi saat fakultas berubah
                this.prodisList = [];

                if (!this.filterFakultas) {
                    this.prodisList = [];
                    return;
                }

                try {
                    const response = await fetch(`{{ route('masterdata.courses.api.prodis') }}?fakultas_id=${this.filterFakultas}`);
                    if (!response.ok) {
                        throw new Error(`Prodi API error: ${response.status}`);
                    }
                    this.prodisList = await response.json();
                } catch (error) {
                    console.error("Gagal memuat prodi", error);
                    this.prodisList = [];
                }
            },

            // Ambil Data Mata Kuliah
            async fetchCourses(page = 1) {
                this.isLoading = true;
                
                const params = new URLSearchParams({
                    page: page,
                    search: this.searchQuery,
                    fakultas_id: this.filterFakultas,
                    prodi_id: this.filterProdi
                });

                try {
                    const response = await fetch(`{{ route('masterdata.courses.api.data') }}?${params.toString()}`);
                    
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const result = await response.json();
                    
                    this.coursesList = result.data || []; 
                    
                    this.pagination = {
                        current_page: result.current_page,
                        from: result.from,
                        to: result.to,
                        total: result.total,
                        prev_page_url: result.prev_page_url,
                        next_page_url: result.next_page_url
                    };
                } catch (error) {
                    console.error("Gagal memuat mata kuliah", error);
                    alert("Gagal memuat data! Rute API bertabrakan atau Controller error. Cek Inspect -> Network.");
                } finally {
                    this.isLoading = false; // Ini akan MEMAKSA loading berhenti meski terjadi error
                }
            },

            changePage(page) {
                this.fetchCourses(page);
            }
        }));
    });
</script>
@endsection