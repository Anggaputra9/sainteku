@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        {{-- Header & Tombol Tambah --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Prestasi Mahasiswa
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Manajemen Achievement /</li>
                        <li class="text-blue-600 dark:text-blue-400">Prestasi Saya</li>
                    </ol>
                </nav>
            </div>

            {{-- Tombol Tambah Prestasi --}}
            <div x-data="{ openCreate: false }">
                <button @click="openCreate = true"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                    <i class="fas fa-plus-circle"></i>
                    Ajukan Prestasi Baru
                </button>
                @include('manajemenachievement::modal-create', ['types' => $types, 'levels' => $levels])
            </div>
        </div>

        {{-- Pesan Sukses --}}
        @if (session('success'))
        <div class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
            <div class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                <i class="fas fa-check text-green-600 dark:text-green-400"></i>
            </div>
            <div>
                <h5 class="text-sm font-semibold text-green-800 dark:text-green-400">Sukses!</h5>
                <p class="text-sm text-green-700 dark:text-green-500">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        {{-- TOMBOL FILTER STATUS --}}
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('student.achievements.index') }}"
                class="px-4 py-2 text-sm font-semibold rounded-full transition {{ !request('status') ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700' }}">
                Semua Prestasi
            </a>
            <a href="{{ route('student.achievements.index', ['status' => 'pending']) }}"
                class="px-4 py-2 text-sm font-semibold rounded-full transition {{ request('status') == 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-amber-600 border border-amber-200 hover:bg-amber-50 dark:bg-gray-800 dark:border-amber-900/50 dark:hover:bg-amber-900/30' }}">
                <i class="fas fa-clock mr-1"></i> Pending
            </a>
            <a href="{{ route('student.achievements.index', ['status' => 'approved']) }}"
                class="px-4 py-2 text-sm font-semibold rounded-full transition {{ request('status') == 'approved' ? 'bg-green-500 text-white shadow-md' : 'bg-white text-green-600 border border-green-200 hover:bg-green-50 dark:bg-gray-800 dark:border-green-900/50 dark:hover:bg-green-900/30' }}">
                <i class="fas fa-check-circle mr-1"></i> Disetujui
            </a>
            <a href="{{ route('student.achievements.index', ['status' => 'rejected']) }}"
                class="px-4 py-2 text-sm font-semibold rounded-full transition {{ request('status') == 'rejected' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50 dark:hover:bg-red-900/30' }}">
                <i class="fas fa-times-circle mr-1"></i> Ditolak
            </a>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4 font-semibold w-2/5">Judul & Deskripsi</th>
                            <th class="px-6 py-4 font-semibold">Jenis & Tingkat</th>
                            <th class="px-6 py-4 font-semibold text-center">Tanggal</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($achievements as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4 align-top">
                                <div class="font-bold text-gray-900 dark:text-white text-base">
                                    {{ $item->title }}
                                </div>
                                @if($item->description)
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">
                                    <i class="fas fa-note-sticky mr-1"></i>
                                    {{ Str::limit($item->description, 80) }}
                                </div>
                                @endif
                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                    <i class="fas fa-user mr-1"></i> Diajukan: {{ $item->created_at->format('d/m/Y') }}
                                </div>
                                @if ($item->status == 'rejected' && $item->rejection_note)
                                <div class="mt-4 w-full rounded-lg border border-red-200 bg-red-50/80 p-3.5 shadow-sm dark:border-red-500/30 dark:bg-red-500/10">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-base"></i>
                                        <div class="flex-col">
                                            <span class="block text-[11px] font-bold text-red-700 dark:text-red-400 uppercase tracking-widest mb-1">Catatan Revisi:</span>
                                            <p class="text-sm font-medium text-red-800 dark:text-red-200 leading-relaxed">
                                                {{ $item->rejection_note }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ $item->type->description ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <i class="fas fa-signal mr-1"></i> {{ $item->level->description ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                <span class="text-sm font-medium">
                                    {{ $item->achievement_date->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                @php
                                $statusColor = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                if ($item->status == 'approved') {
                                $statusColor = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                                } elseif ($item->status == 'rejected') {
                                $statusColor = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                                } elseif ($item->status == 'pending') {
                                $statusColor = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
                                }
                                @endphp
                                <span class="inline-flex rounded-md px-2.5 py-1 text-xs font-bold {{ $statusColor }}">
                                    @if($item->status == 'pending')
                                    <i class="fas fa-clock mr-1"></i> Pending
                                    @elseif($item->status == 'approved')
                                    <i class="fas fa-check-circle mr-1"></i> Disetujui
                                    @else
                                    <i class="fas fa-times-circle mr-1"></i> Ditolak
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('student.achievements.show', $item->id) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 transition">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                                    <div class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <i class="fas fa-file-alt text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-medium">Belum ada prestasi yang diajukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($achievements->hasPages())
            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                {{ $achievements->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection