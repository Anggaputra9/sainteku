@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-10">
        @php
            $statusColors = [
                'SUBMITTED'                 => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'icon' => 'fa-circle-check'],
                'AUTO_SUBMITTED_TIME'       => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700',   'icon' => 'fa-hourglass-end'],
                'AUTO_SUBMITTED_VIOLATION'  => ['bg' => 'bg-red-100',     'text' => 'text-red-700',     'icon' => 'fa-ban'],
            ];
            $c = $statusColors[$attempt->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'fa-info-circle'];
        @endphp

        <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
            <div class="inline-flex h-20 w-20 items-center justify-center rounded-full {{ $c['bg'] }}">
                <i class="fa-solid {{ $c['icon'] }} text-4xl {{ $c['text'] }}"></i>
            </div>
            <h2 class="mt-5 text-2xl font-bold text-gray-900 dark:text-white">{{ $attempt->statusLabel() }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $room->title }}</p>

            @if ($attempt->status === 'AUTO_SUBMITTED_VIOLATION')
                <p class="mt-4 text-sm text-red-600 max-w-md mx-auto">
                    Ujian disubmit secara otomatis karena pelanggaran kebijakan tab/fokus.
                    Hubungi dosen Anda jika ini terjadi karena masalah teknis.
                </p>
            @elseif ($attempt->status === 'AUTO_SUBMITTED_TIME')
                <p class="mt-4 text-sm text-amber-700 max-w-md mx-auto">
                    Waktu pengerjaan habis. Jawaban yang sudah diisi sudah tersimpan.
                </p>
            @else
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-300 max-w-md mx-auto">
                    Terima kasih, jawaban Anda sudah berhasil disubmit.
                </p>
            @endif

            <div class="mt-8 grid grid-cols-2 gap-3 max-w-md mx-auto text-sm">
                <div class="rounded-xl bg-gray-50 p-3 dark:bg-gray-800/50">
                    <div class="text-xs text-gray-500">Soal Terjawab</div>
                    <div class="text-2xl font-black text-gray-800 dark:text-gray-200">
                        {{ $attempt->answered_count }} / {{ $totalQuestions }}
                    </div>
                </div>
                <div class="rounded-xl bg-gray-50 p-3 dark:bg-gray-800/50">
                    <div class="text-xs text-gray-500">Submitted</div>
                    <div class="text-base font-bold text-gray-800 dark:text-gray-200">
                        {{ $attempt->submitted_at?->format('d M Y H:i') ?? '-' }}
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                {{-- Pakai uuid attempt biar URL hasil tidak gampang ditebak --}}
                <a href="{{ route('ujian.attempt.result', ['attempt' => $attempt->uuid]) }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">
                    <i class="fa-solid fa-clipboard-check"></i> Lihat Jawaban Saya
                </a>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection
