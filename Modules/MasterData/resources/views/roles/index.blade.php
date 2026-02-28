@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Role</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola hak akses dan tingkatan pengguna dalam sistem.</p>
        </div>
        <a href="#" 
            class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition shadow-sm">
            <i class="fas fa-plus"></i>
            Tambah Role
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-100 bg-emerald-50 px-4 py-3 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-400">
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
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Nama Role</th>
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($roles as $role)
                        <tr class="bg-transparent">
                            <td class="px-4 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">
                                #{{ $role->id }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-bold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    {{ strtoupper($role->role_name) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $role->description ?? 'Tidak ada deskripsi' }}
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="#" class="text-amber-500 hover:text-amber-600 transition dark:text-amber-400" title="Edit Role">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="text-red-500 hover:text-red-600 transition dark:text-red-400" title="Hapus Role">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-shield-alt text-3xl mb-2 opacity-20"></i>
                                <p>Belum ada data role yang dikonfigurasi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection