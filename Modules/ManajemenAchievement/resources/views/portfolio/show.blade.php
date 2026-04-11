@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        {{-- Profile Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->identity_id ?? '-' }} • {{ $user->user_type ?? 'User' }}</p>

                    {{-- Statistics --}}
                    <div class="flex flex-wrap gap-6 mt-4">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Total Prestasi</span>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $statistics['total'] }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Prestasi Mahasiswa</span>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ $statistics['mahasiswa'] }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Prestasi Dosen</span>
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $statistics['dosen'] }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Tahun Aktif</span>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ count($statistics['per_tahun']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Tahun --}}
        @if($tahunList->isNotEmpty())
        <div class="flex flex-wrap items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <i class="fas fa-calendar text-gray-500"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Tahun:</span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('portfolio.show', $user->id) }}"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition {{ !request('tahun') ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700' }}">
                    Semua
                </a>
                @foreach($tahunList as $tahun)
                <a href="{{ route('portfolio.show', [$user->id, 'tahun' => $tahun]) }}"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition {{ request('tahun') == $tahun ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700' }}">
                    {{ $tahun }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Achievement Timeline --}}
        <div class="space-y-6">
            @forelse($achievementsByYear as $tahun => $items)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-calendar text-blue-500"></i>
                        Tahun {{ $tahun }}
                        <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">({{ $items->count() }} prestasi)</span>
                    </h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($items as $achievement)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                {{-- Badge Type --}}
                                <div class="flex items-center gap-2 mb-2">
                                    @if($achievement['type'] == 'mahasiswa')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        <i class="fas fa-graduation-cap"></i>
                                        Mahasiswa
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        <i class="fas fa-chalkboard-user"></i>
                                        Dosen
                                    </span>
                                    @endif

                                    <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                        {{ $achievement['tingkat'] }}
                                    </span>
                                </div>

                                {{-- Judul --}}
                                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1">
                                    {{ $achievement['judul'] }}
                                </h4>

                                {{-- Kategori --}}
                                <p class="text-sm text-indigo-600 dark:text-indigo-400 mb-2">
                                    {{ $achievement['kategori'] }}
                                </p>

                                {{-- Deskripsi --}}
                                @if($achievement['deskripsi'])
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    {{ Str::limit($achievement['deskripsi'], 150) }}
                                </p>
                                @endif

                                {{-- Penerbit & URL --}}
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-gray-500">
                                    @if($achievement['penerbit'])
                                    <span><i class="fas fa-building mr-1"></i> {{ $achievement['penerbit'] }}</span>
                                    @endif
                                    @if($achievement['url'])
                                    <a href="{{ $achievement['url'] }}" target="_blank" class="text-blue-600 hover:underline">
                                        <i class="fas fa-link mr-1"></i> Link
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ date('d M Y', strtotime($achievement['tanggal'])) }}
                                </span>
                                @if($achievement['file_path'])
                                <a href="{{ $achievement['type'] == 'mahasiswa' ? route('student.achievements.download', $achievement['id']) : route('dosen.repository.download', $achievement['id']) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-blue-600 border border-blue-200 hover:bg-blue-50 transition dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800 dark:hover:bg-gray-700"
                                    title="Download File">
                                    <i class="fas fa-file-pdf"></i>
                                    File
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                    <div class="h-20 w-20 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        <i class="fas fa-folder-open text-4xl"></i>
                    </div>
                    <p class="text-base font-medium">Belum ada prestasi yang ditampilkan</p>
                    <p class="text-sm">User ini belum memiliki prestasi yang disetujui.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection