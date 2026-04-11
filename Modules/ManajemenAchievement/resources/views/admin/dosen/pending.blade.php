@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Prestasi Dosen Pending
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li><a href="{{ route('admin.dosen.index') }}" class="hover:text-blue-600">Admin Prestasi Dosen</a></li>
                        <li class="text-amber-600 dark:text-amber-400">Pending</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.dosen.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

        {{-- Filter Tahun --}}
        <div class="flex flex-wrap items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <i class="fas fa-calendar text-gray-500"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tahun:</span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.dosen.pending') }}"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition {{ !request('tahun') ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700' }}">
                    Semua
                </a>
                @foreach($tahunList as $tahun)
                <a href="{{ route('admin.dosen.pending', ['tahun' => $tahun]) }}"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition {{ request('tahun') == $tahun ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700' }}">
                    {{ $tahun }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4 font-semibold w-2/5">Judul & Pengaju</th>
                            <th class="px-6 py-4 font-semibold">Kategori & Tingkat</th>
                            <th class="px-6 py-4 font-semibold text-center">Tanggal</th>
                            <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($achievements as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4 align-top">
                                <div class="font-bold text-gray-900 dark:text-white text-base">
                                    {{ $item->judul }}
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1.5">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $item->user->name ?? 'Unknown' }}</span>
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-clock"></i>
                                    Diajukan: {{ $item->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ $item->kategori->nama ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-signal mr-1"></i>
                                    {{ $item->tingkat->nama ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center align-top">
                                <span class="text-sm font-medium">
                                    {{ date('d/m/Y', strtotime($item->tanggal)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center align-top">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.dosen.show', $item->id) }}"
                                        class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600 transition shadow-md">
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                                    <div class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <i class="fas fa-clock text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-medium">Tidak ada prestasi dosen pending.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($achievements->hasPages())
            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                {{ $achievements->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection