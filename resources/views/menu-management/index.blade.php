@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl border bg-white p-6 dark:bg-gray-900 dark:border-gray-800">
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Manajemen Menu / Sidebar</h1>
        <p class="text-sm text-gray-500">Khusus Administrator. Atur nama, route, icon, parent, modul, urutan, status.</p>
    </div>

    @if(session('success')) <div class="rounded-xl bg-green-50 p-4 text-green-700">{{ session('success') }}</div> @endif

    <div class="rounded-2xl border bg-white p-6 dark:bg-gray-900 dark:border-gray-800">
        <form method="POST" action="{{ route('menu-management.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            @csrf
            <input name="menu_name" placeholder="Nama menu" class="rounded-lg border p-2 dark:bg-gray-800" required>
            <input name="menu_link" placeholder="Route name" class="rounded-lg border p-2 dark:bg-gray-800">
            <input name="menu_icon" placeholder="fa-solid fa-..." class="rounded-lg border p-2 dark:bg-gray-800">
            <select name="parent_id" class="rounded-lg border p-2 dark:bg-gray-800"><option value="">Parent/root</option>@foreach($parents as $p)<option value="{{ $p->id }}">{{ $p->menu_name }}</option>@endforeach</select>
            <select name="module_id" class="rounded-lg border p-2 dark:bg-gray-800"><option value="">Tanpa modul</option>@foreach($modules as $m)<option value="{{ $m->id }}">{{ $m->description }}</option>@endforeach</select>
            <input name="order_no" type="number" value="0" class="rounded-lg border p-2 dark:bg-gray-800">
            <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked> Aktif</label>
            <button class="rounded-lg bg-indigo-600 text-white px-4 py-2">Tambah</button>
        </form>
    </div>

    <div class="rounded-2xl border bg-white overflow-x-auto dark:bg-gray-900 dark:border-gray-800">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800"><tr><th class="p-3 text-left">Menu</th><th>Route</th><th>Parent</th><th>Modul</th><th>Urut</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($menus as $menu)
                <tr class="border-t dark:border-gray-800">
                    <form method="POST" action="{{ route('menu-management.update', $menu) }}">
                        @csrf @method('PUT')
                        <td class="p-2"><input name="menu_name" value="{{ $menu->menu_name }}" class="w-full rounded border p-2 dark:bg-gray-800"><input name="menu_icon" value="{{ $menu->menu_icon }}" class="w-full rounded border p-2 mt-1 dark:bg-gray-800"></td>
                        <td class="p-2"><input name="menu_link" value="{{ $menu->menu_link }}" class="w-full rounded border p-2 dark:bg-gray-800"></td>
                        <td class="p-2"><select name="parent_id" class="rounded border p-2 dark:bg-gray-800"><option value="">Root</option>@foreach($parents as $p) @if($p->id !== $menu->id)<option value="{{ $p->id }}" @selected($menu->parent_id==$p->id)>{{ $p->menu_name }}</option>@endif @endforeach</select></td>
                        <td class="p-2"><select name="module_id" class="rounded border p-2 dark:bg-gray-800"><option value="">-</option>@foreach($modules as $m)<option value="{{ $m->id }}" @selected($menu->module_id==$m->id)>{{ $m->description }}</option>@endforeach</select></td>
                        <td class="p-2"><input name="order_no" type="number" value="{{ $menu->order_no }}" class="w-20 rounded border p-2 dark:bg-gray-800"></td>
                        <td class="p-2"><input type="checkbox" name="is_active" value="1" @checked($menu->is_active)></td>
                        <td class="p-2 flex gap-2"><button class="rounded bg-blue-600 text-white px-3 py-2">Simpan</button>
                    </form>
                    <form method="POST" action="{{ route('menu-management.destroy', $menu) }}" onsubmit="return confirm('Hapus menu ini?')">@csrf @method('DELETE')<button class="rounded bg-red-600 text-white px-3 py-2">Hapus</button></form></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection