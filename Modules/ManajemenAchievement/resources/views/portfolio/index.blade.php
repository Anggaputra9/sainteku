@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Portofolio Prestasi</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih dosen atau mahasiswa untuk melihat portofolio prestasi mereka</p>
    </div>

    {{-- FILTER BUTTONS --}}
    <div class="mb-6 flex flex-wrap items-center gap-2">
        <a href="{{ route('portfolio.index') }}"
            class="px-4 py-2 text-sm font-semibold rounded-full transition {{ !request('type') ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700' }}">
            <i class="fas fa-users mr-1"></i>
            Semua
        </a>
        <a href="{{ route('portfolio.index', ['type' => 'dosen']) }}"
            class="px-4 py-2 text-sm font-semibold rounded-full transition {{ request('type') == 'dosen' ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700' }}">
            <i class="fas fa-chalkboard-user mr-1"></i>
            Dosen
        </a>
        <a href="{{ route('portfolio.index', ['type' => 'mahasiswa']) }}"
            class="px-4 py-2 text-sm font-semibold rounded-full transition {{ request('type') == 'mahasiswa' ? 'bg-green-500 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700' }}">
            <i class="fas fa-user-graduate mr-1"></i>
            Mahasiswa
        </a>
    </div>

    {{-- GRID CARD --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($users as $user)
        <a href="{{ route('portfolio.show', $user->id) }}"
            class="group rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-blue-700">
            <div class="flex items-center gap-4">
                {{-- Avatar --}}
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white font-bold text-lg shadow-sm">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                        {{ $user->name }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $user->user_type ?? 'User' }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        <i class="fas fa-trophy mr-1"></i>
                        {{ $user->achievements()->where('status', 'approved')->count() }} prestasi
                    </p>
                </div>

                {{-- Arrow --}}
                <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500 transition-colors"></i>
            </div>
        </a>
        @empty
        <div class="col-span-full py-12 text-center">
            <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                <div class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                    <i class="fas fa-users text-3xl"></i>
                </div>
                <p class="text-sm font-medium">Tidak ada user dengan prestasi</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection