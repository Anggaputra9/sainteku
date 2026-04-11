@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        {{-- Header & Tombol Kembali --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Detail Prestasi Dosen
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Manajemen Achievement /</li>
                        <li><a href="{{ route('admin.dosen.index') }}" class="hover:text-blue-600">Admin Prestasi Dosen</a> /</li>
                        <li class="text-blue-600 dark:text-blue-400">Detail</li>
                    </ol>
                </nav>
            </div>

            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

        {{-- Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kolom Kiri: Detail Utama --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Card Detail --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Informasi Prestasi
                        </h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full">
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400 w-1/3">Pengaju</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    {{ $achievement->user->name ?? '-' }} ({{ $achievement->user->id ?? '-' }})
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Unit</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">{{ $achievement->unit_id ?? '-' }}</td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $achievement->kategori->nama ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Judul Karya</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white font-semibold">{{ $achievement->judul ?? '-' }}</td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    <i class="far fa-calendar mr-1 text-gray-400"></i>
                                    {{ $achievement->tanggal ? date('d F Y', strtotime($achievement->tanggal)) : '-' }}
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tingkat</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                        {{ $achievement->tingkat->nama ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                            @if($achievement->penyelenggara)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Penyelenggara/Penerbit</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">{{ $achievement->penyelenggara }}</td>
                            </tr>
                            @endif
                            @if($achievement->url)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">URL</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    <a href="{{ $achievement->url }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ $achievement->url }} <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            @endif

                            {{-- Detail Publikasi (Jurnal/Prosiding) --}}
                            @if($achievement->kategori && (str_contains($achievement->kategori->nama, 'Jurnal') || str_contains($achievement->kategori->nama, 'Prosiding')))
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Publikasi</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">{{ ucfirst($achievement->jenis_publikasi ?? '-') }}</td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Nama Jurnal</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">{{ $achievement->nama_jurnal ?? '-' }}</td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Volume/Nomor/Halaman</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    Vol. {{ $achievement->volume ?? '-' }}, No. {{ $achievement->nomor ?? '-' }}, Hal. {{ $achievement->halaman ?? '-' }}
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">ISSN</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">{{ $achievement->issn ?? '-' }}</td>
                            </tr>
                            @endif

                            {{-- Detail HKI/Paten --}}
                            @if($achievement->kategori && (str_contains($achievement->kategori->nama, 'HKI') || str_contains($achievement->kategori->nama, 'Paten')))
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Pendaftaran</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">{{ $achievement->nomor_pendaftaran ?? '-' }}</td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Status HKI</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">{{ $achievement->status_hki ?? '-' }}</td>
                            </tr>
                            @endif

                            {{-- Detail Buku --}}
                            @if($achievement->kategori && str_contains($achievement->kategori->nama, 'Buku'))
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">ISBN</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">{{ $achievement->isbn ?? '-' }}</td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Penerbit</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">{{ $achievement->penerbit ?? '-' }}</td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Halaman</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">{{ $achievement->jumlah_halaman ?? '-' }}</td>
                            </tr>
                            @endif

                            @if($achievement->deskripsi)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">{{ $achievement->deskripsi }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Status</td>
                                <td class="py-3">
                                    @php
                                    $statusColor = 'bg-gray-100 text-gray-800';
                                    if ($achievement->status == 'approved') {
                                    $statusColor = 'bg-green-100 text-green-800 border-green-200';
                                    } elseif ($achievement->status == 'rejected') {
                                    $statusColor = 'bg-red-100 text-red-800 border-red-200';
                                    } elseif ($achievement->status == 'pending') {
                                    $statusColor = 'bg-amber-100 text-amber-800 border-amber-200';
                                    }
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-3 py-1 text-xs font-bold border {{ $statusColor }}">
                                        @if($achievement->status == 'pending')
                                        <i class="far fa-clock mr-1"></i> Pending
                                        @elseif($achievement->status == 'approved')
                                        <i class="far fa-circle-check mr-1"></i> Disetujui
                                        @else
                                        <i class="far fa-circle-xmark mr-1"></i> Ditolak
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: File & Aksi --}}
            <div class="space-y-6">
                {{-- Card File --}}
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
                                @if($achievement->file_path)
                                @php
                                $size = Storage::exists($achievement->file_path) ? round(Storage::size($achievement->file_path) / 1024, 2) : 0;
                                @endphp
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $size }} KB</p>
                                @endif
                            </div>
                            <a href="{{ route('dosen.repository.download', $achievement->id) }}"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 p-2 text-white hover:bg-blue-700 transition"
                                title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Card Aksi (khusus pending) --}}
                @if($achievement->status == 'pending')
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-cog mr-2 text-blue-500"></i>
                            Aksi Verifikasi
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Form Setujui --}}
                        <form action="{{ route('admin.dosen.approve', $achievement->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-3 text-sm font-semibold text-white hover:bg-green-700 transition shadow-md"
                                onclick="return confirm('Yakin ingin menyetujui prestasi ini?')">
                                <i class="fas fa-check-circle"></i>
                                Setujui Prestasi
                            </button>
                        </form>

                        {{-- FORM TOLAK --}}
                        <form action="{{ route('admin.dosen.reject', $achievement->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Catatan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="rejection_note" rows="3" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    placeholder="Jelaskan alasan penolakan..."></textarea>
                            </div>
                            <button type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white hover:bg-red-700 transition shadow-md"
                                onclick="return confirm('Yakin ingin menolak prestasi ini?')">
                                <i class="fas fa-times-circle"></i>
                                Tolak Prestasi
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Card Info Timeline (untuk approved/rejected) --}}
                @if($achievement->status != 'pending')
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-clock mr-2 text-blue-500"></i>
                            Timeline Verifikasi
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($achievement->approved_at)
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Diverifikasi pada</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ date('d F Y H:i', strtotime($achievement->approved_at)) }}
                            </p>
                        </div>
                        @endif
                        @if($achievement->approved_by)
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Diverifikasi oleh</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $achievement->approver->name ?? 'Admin' }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Card Info Timeline Umum --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-history mr-2 text-blue-500"></i>
                            Timeline
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Diajukan pada</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $achievement->created_at->format('d F Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Terakhir diperbarui</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $achievement->updated_at->format('d F Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Catatan Penolakan Khusus (ditampilkan besar) --}}
        @if($achievement->status == 'rejected' && $achievement->catatan_penolakan)
        <div class="rounded-xl border border-red-200 bg-red-50 p-6 shadow-sm dark:border-red-800/30 dark:bg-red-900/10">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                        <i class="fa-solid fa-circle-exclamation text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-base font-bold text-red-800 dark:text-red-400 mb-1">Catatan Penolakan</h4>
                    <p class="text-sm text-red-700 dark:text-red-300 leading-relaxed">
                        {{ $achievement->catatan_penolakan }}
                    </p>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection