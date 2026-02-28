@extends('layouts.app')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Unit / Prodi</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola struktur fakultas, jurusan, dan program studi di
                lingkungan Sainteku.</p>
        </div>
        <a href="{{ route('masterdata.units.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition">
            <i class="fas fa-plus"></i>
            Tambah Unit
        </a>
    </div>

    @if (session('success'))
        <div
            class="mb-6 rounded-lg border border-emerald-100 bg-emerald-50 px-4 py-3 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-400">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50/50 text-left text-sm dark:bg-gray-700/30">
                    <tr class="border-b border-gray-100 dark:border-gray-700/50">
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">ID</th>
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Nama Unit
                        </th>
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Tipe</th>
                        <th
                            class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider text-center">
                            Status</th>
                        <th
                            class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($units as $unit)
                        <tr class="bg-transparent">
                            <td class="px-4 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">#{{ $unit->id }}
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900 dark:text-white">{{ $unit->unit_name }}
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span
                                    class="inline-flex rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/30 dark:text-purple-300">
                                    {{ $unit->unit_type_id }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if ($unit->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Non-aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('masterdata.units.edit', $unit->id) }}"
                                        class="inline-flex items-center gap-2 rounded bg-amber-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-600 transition shadow-sm">
                                        <i class="fas fa-edit"></i>
                                        <span>Ubah</span>
                                    </a>

                                    <form method="POST" action="{{ route('masterdata.units.destroy', $unit->id) }}"
                                        class="inline" onsubmit="return confirm('Yakin ingin menghapus unit ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 rounded bg-red-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-600 transition shadow-sm">
                                            <i class="fas fa-trash"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-building text-3xl mb-2 opacity-20"></i>
                                <p>Belum ada data unit yang terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $units->links() }}
    </div>
@endsection
