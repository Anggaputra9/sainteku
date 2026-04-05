@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="{ activeTab: 'saya' }">
        <div class="space-y-6">

            {{-- Header --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Dashboard Tashih Soal
                    </h2>
                    <nav>
                        <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                            <li>Monev Akademik /</li>
                            <li class="text-blue-600 dark:text-blue-400">Tashih Soal</li>
                        </ol>
                    </nav>
                </div>
            </div>

            {{-- Pesan Sukses / Error --}}
            @if (session('success'))
                <div
                    class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                    <div
                        class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                        <i class="fa-solid fa-check text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-green-800 dark:text-green-400">Sukses!</h5>
                        <p class="text-sm text-green-700 dark:text-green-500">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Tabs Navigation --}}
            <div class="flex flex-wrap items-center gap-2 mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                <button @click="activeTab = 'saya'"
                    :class="activeTab === 'saya' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700'"
                    class="px-5 py-2 text-sm font-semibold rounded-full transition flex items-center gap-2">
                    <i class="fa-solid fa-file-pen"></i> Pengajuan Saya
                </button>

                @if($isReviewer)
                    <button @click="activeTab = 'review'"
                        :class="activeTab === 'review' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50 dark:hover:bg-red-900/30'"
                        class="px-5 py-2 text-sm font-semibold rounded-full transition flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check"></i> Antrean Review
                        <span
                            class="ml-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-white text-red-600 rounded-full dark:bg-red-200">{{ $reviewQueue->count() }}</span>
                    </button>
                @endif
            </div>

            {{-- TAB 1: PENGAJUAN SAYA --}}
            <div x-show="activeTab === 'saya'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Buat Pengajuan Baru</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                    @foreach($myCourses as $course)
                        <div
                            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 flex flex-col justify-between hover:shadow-md transition">
                            <div>
                                <span
                                    class="inline-flex rounded-md bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-900/30 dark:text-blue-400 mb-2">{{ $course->id }}</span>
                                <h4 class="text-base font-bold text-gray-900 dark:text-white leading-tight mb-4">
                                    {{ $course->course_name }}</h4>
                            </div>
                            <a href="{{ route('monevakademik.tashih.create', $course->id) }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
                                <i class="fas fa-plus"></i> Buat Soal
                            </a>
                        </div>
                    @endforeach
                </div>

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Riwayat Pengajuan</h3>
                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                            <thead
                                class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Mata Kuliah</th>
                                    <th class="px-6 py-4 font-semibold text-center">Jenis</th>
                                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                                    <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($myProposals as $prop)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                            {{ $prop->course->course_name }}</td>
                                        <td class="px-6 py-4 text-center font-semibold">{{ $prop->exam_type }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $statusClass = $prop->status == 'APPROVED' ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400' : ($prop->status == 'REVISED' ? 'bg-orange-100 text-orange-800 border-orange-200 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400');
                                            @endphp
                                            <span
                                                class="inline-flex rounded-md px-2.5 py-1 text-xs font-bold border {{ $statusClass }}">
                                                {{ $prop->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('monevakademik.tashih.show', $prop->uuid) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm border border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                                <i class="fa-solid fa-eye text-blue-500"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat pengajuan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TAB 2: ANTREAN REVIEW --}}
            @if($isReviewer)
                <div x-show="activeTab === 'review'" style="display: none;" x-cloak
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <div
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                                <thead
                                    class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold">Dosen Pengaju</th>
                                        <th class="px-6 py-4 font-semibold">Mata Kuliah</th>
                                        <th class="px-6 py-4 font-semibold text-center">Jenis</th>
                                        <th class="px-6 py-4 font-semibold text-center">Tanggal</th>
                                        <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($reviewQueue as $queue)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                            <td class="px-6 py-4 font-semibold">{{ $queue->creator->name ?? 'Dosen' }}</td>
                                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                                {{ $queue->course->course_name }}</td>
                                            <td class="px-6 py-4 text-center font-semibold">{{ $queue->exam_type }}</td>
                                            <td class="px-6 py-4 text-center">{{ $queue->created_at->format('d M Y') }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('monevakademik.tashih.show', $queue->uuid) }}"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-orange-500 px-3 py-1.5 text-xs font-bold text-white hover:bg-orange-600 transition shadow-sm">
                                                    <i class="fa-solid fa-magnifying-glass"></i> Review
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada antrean review
                                                saat ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection