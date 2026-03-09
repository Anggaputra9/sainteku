@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="{ 
    openReview: false, 
    reviewUrl: '', 
    docTitle: '', 
    docId: '' 
}">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Dashboard Reviewer
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Document Repository /</li>
                        <li class="text-blue-600 dark:text-blue-400">Persetujuan Dokumen</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Alert Sukses & Error --}}
        @if (session('success'))
            <div class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                <div class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                    <i class="fa-solid fa-check text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-green-800 dark:text-green-400">Sukses!</h5>
                    <p class="text-sm text-green-700 dark:text-green-500">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any() || session('error'))
            <div class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                <div class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400"></i>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-red-800 dark:text-red-400">Peringatan!</h5>
                    <p class="text-sm text-red-700 dark:text-red-500">{{ session('error') ?? $errors->first() }}</p>
                </div>
            </div>
        @endif

        {{-- TOMBOL FILTER STATUS --}}
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <a href="{{ request()->url() }}" 
                class="px-4 py-2 text-sm font-semibold rounded-full transition {{ $filterStatus == 'all' ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700' }}">
                Semua Dokumen
            </a>
            <a href="{{ request()->url() }}?status=pending" 
                class="px-4 py-2 text-sm font-semibold rounded-full transition {{ $filterStatus == 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-amber-600 border border-amber-200 hover:bg-amber-50 dark:bg-gray-800 dark:border-amber-900/50 dark:hover:bg-amber-900/30' }}">
                <i class="fa-solid fa-clock mr-1"></i> Perlu Direview
            </a>
            <a href="{{ request()->url() }}?status=approved" 
                class="px-4 py-2 text-sm font-semibold rounded-full transition {{ $filterStatus == 'approved' ? 'bg-green-500 text-white shadow-md' : 'bg-white text-green-600 border border-green-200 hover:bg-green-50 dark:bg-gray-800 dark:border-green-900/50 dark:hover:bg-green-900/30' }}">
                <i class="fa-solid fa-check-circle mr-1"></i> Disetujui
            </a>
            <a href="{{ request()->url() }}?status=rejected" 
                class="px-4 py-2 text-sm font-semibold rounded-full transition {{ $filterStatus == 'rejected' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50 dark:hover:bg-red-900/30' }}">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> Revisi
            </a>
        </div>
        {{-- Tabel Data Dokumen --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Info Dokumen</th>
                            <th class="px-6 py-4 font-semibold">Unit & Tipe</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($documents as $doc)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $doc->document_id }}</div>
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $doc->document_title }}</div>
                                    <div class="text-xs text-gray-400 mt-1">Oleh: {{ $doc->creator->name ?? 'Sistem' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-indigo-600 dark:text-indigo-400">{{ $doc->unit->unit_name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $doc->type->description ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColor = 'bg-gray-100 text-gray-800';
                                        if($doc->status == 3) $statusColor = 'bg-green-100 text-green-800';
                                        elseif($doc->status == 4) $statusColor = 'bg-red-100 text-red-800';
                                        elseif($doc->status == 2) $statusColor = 'bg-amber-100 text-amber-800';
                                        elseif($doc->status == 1) $statusColor = 'bg-blue-100 text-blue-800';
                                    @endphp
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusColor }}">
                                        {{ $doc->workflowStatus->description ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center gap-2">
                                        {{-- Tombol Lihat (Preview) --}}
                                        <a href="{{ route('DocumentRepository.download', $doc->id) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-100 transition shadow-sm border border-blue-200">
                                            <i class="fa-solid fa-eye"></i> Lihat
                                        </a>

                                        {{-- Tombol Review (Membuka Modal) --}}
                                        @if($doc->status != 3) 
                                        <button 
                                            @click="openReview = true; reviewUrl = '{{ route('DocumentRepository.review', $doc->id) }}'; docTitle = '{{ addslashes($doc->document_title) }}'; docId = '{{ $doc->document_id }}'"
                                            class="inline-flex items-center gap-1.5 rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700 transition shadow-sm">
                                            <i class="fa-solid fa-gavel"></i> Review
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">Belum ada dokumen yang perlu direview.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($documents->hasPages())
                <div class="border-t border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL REVIEW (Desain Premium) --}}
    <div x-show="openReview"
        class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

        <div @click.away="openReview = false" x-show="openReview"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-2xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all mt-10">

            {{-- Header Modal --}}
            <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/50">
                        <i class="fa-solid fa-clipboard-check text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="modal-title">Review Dokumen</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Silakan periksa dan berikan keputusan.</p>
                    </div>
                </div>
            </div>

            {{-- Form Area --}}
            <form :action="reviewUrl" method="POST">
                @csrf
                <div class="space-y-6">
                    <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Dokumen ID: <strong x-text="docId" class="text-gray-800 dark:text-gray-200"></strong> <br>
                            Judul: <em x-text="docTitle" class="font-medium text-gray-700 dark:text-gray-300"></em>
                        </p>
                    </div>

                    <div>
                        <label for="change_note" class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Catatan Review (Wajib jika menolak):</label>
                        <textarea id="change_note" name="change_note" rows="3"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                            placeholder="Tuliskan alasan penolakan atau catatan di sini..."></textarea>
                    </div>
                </div>

                {{-- Tombol Aksi Bawah --}}
                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end mt-8">
                    <button type="button" @click="openReview = false"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" name="action" value="reject"
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-red-700 transition">
                        <i class="fa-solid fa-xmark"></i> Tolak
                    </button>
                    <button type="submit" name="action" value="approve"
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-green-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                        <i class="fa-solid fa-check"></i> Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection