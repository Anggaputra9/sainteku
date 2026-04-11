@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Edit Prestasi
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Manajemen Achievement /</li>
                        <li><a href="{{ route('student.achievements.index') }}" class="hover:text-blue-600">Prestasi Saya</a> /</li>
                        <li class="text-blue-600 dark:text-blue-400">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Form Edit --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    <i class="far fa-pen-to-square mr-2 text-amber-500"></i>
                    Form Edit Prestasi
                </h3>
            </div>

            <form action="{{ route('student.achievements.update', $achievement->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jenis Prestasi -->
                    <div>
                        <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jenis Prestasi <span class="text-red-500">*</span>
                        </label>
                        <select name="achievement_type_id" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="">Pilih Jenis</option>
                            @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ $achievement->achievement_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->description }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tingkat -->
                    <div>
                        <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tingkat Prestasi <span class="text-red-500">*</span>
                        </label>
                        <select name="achievement_level_id" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="">Pilih Tingkat</option>
                            @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ $achievement->achievement_level_id == $level->id ? 'selected' : '' }}>
                                {{ $level->description }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Judul -->
                    <div class="md:col-span-2">
                        <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Judul Karya <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $achievement->title) }}" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            placeholder="Masukkan judul karya/prestasi">
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="achievement_date" value="{{ old('achievement_date', $achievement->achievement_date) }}" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    </div>

                    <!-- Jenis Publikasi -->
                    <div>
                        <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jenis Publikasi
                        </label>
                        <select name="publication_type"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="">Pilih Jenis</option>
                            <option value="Scopus" {{ $achievement->publication_type == 'Scopus' ? 'selected' : '' }}>Scopus</option>
                            <option value="Sinta" {{ $achievement->publication_type == 'Sinta' ? 'selected' : '' }}>Sinta</option>
                            <option value="Lainnya" {{ $achievement->publication_type == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Penerbit -->
                    <div class="md:col-span-2">
                        <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Penerbit/Penyelenggara
                        </label>
                        <input type="text" name="publisher" value="{{ old('publisher', $achievement->publisher) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            placeholder="Nama penerbit/penyelenggara">
                    </div>

                    <!-- URL -->
                    <div class="md:col-span-2">
                        <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            URL
                        </label>
                        <input type="url" name="url" value="{{ old('url', $achievement->url) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            placeholder="https://...">
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Deskripsi
                        </label>
                        <textarea name="description" rows="4"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            placeholder="Deskripsikan prestasi Anda...">{{ old('description', $achievement->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <a href="{{ route('student.achievements.index') }}"
                        class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-600">
                        <i class="fas fa-save mr-1"></i>
                        Update Prestasi
                    </button>
                </div>
            </form>
        </div>

        {{-- Info File (Jika Ada) --}}
        @if($achievement->file_path)
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-paperclip mr-2 text-blue-500"></i>
                    File Pendukung
                </h3>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $achievement->file_name }}
                        </p>
                    </div>
                    <a href="{{ route('student.achievements.download', $achievement->id) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 p-2 text-white hover:bg-blue-700 transition"
                        title="Download">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
                <p class="mt-2 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    File tidak dapat diubah melalui halaman ini. Hubungi admin jika perlu mengganti file.
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection