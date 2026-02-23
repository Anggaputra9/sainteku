{{-- Button Component for Sainteku --}}
{{-- 
  Usage:
  <x-masterdata::buttons.action type="add" />
  <x-masterdata::buttons.action type="edit" />
  <x-masterdata::buttons.action type="delete" />
  etc.
--}}

@php
$buttonConfig = [
    'add' => [
        'label' => 'Tambah',
        'icon' => 'fa-plus',
        'color' => 'success',
        'class' => 'bg-emerald-500 hover:bg-emerald-600 text-white'
    ],
    'edit' => [
        'label' => 'Ubah',
        'icon' => 'fa-pencil',
        'color' => 'warning',
        'class' => 'bg-amber-500 hover:bg-amber-600 text-white'
    ],
    'delete' => [
        'label' => 'Hapus',
        'icon' => 'fa-trash',
        'color' => 'danger',
        'class' => 'bg-red-500 hover:bg-red-600 text-white'
    ],
    'save' => [
        'label' => 'Simpan',
        'icon' => 'fa-floppy-disk',
        'color' => 'primary',
        'class' => 'bg-blue-600 hover:bg-blue-700 text-white'
    ],
    'cancel' => [
        'label' => 'Batal',
        'icon' => 'fa-xmark',
        'color' => 'danger',
        'class' => 'bg-red-500 hover:bg-red-600 text-white'
    ],
    'search' => [
        'label' => 'Cari',
        'icon' => 'fa-magnifying-glass',
        'color' => 'info',
        'class' => 'bg-blue-400 hover:bg-blue-500 text-white'
    ],
    'refresh' => [
        'label' => 'Muat Ulang',
        'icon' => 'fa-rotate',
        'color' => 'primary',
        'class' => 'bg-blue-600 hover:bg-blue-700 text-white'
    ],
    'detail' => [
        'label' => 'Detail',
        'icon' => 'fa-circle-info',
        'color' => 'info',
        'class' => 'bg-blue-400 hover:bg-blue-500 text-white'
    ],
    'print' => [
        'label' => 'Cetak',
        'icon' => 'fa-print',
        'color' => 'secondary',
        'class' => 'bg-gray-500 hover:bg-gray-600 text-white'
    ],
    'filter' => [
        'label' => 'Filter',
        'icon' => 'fa-filter',
        'color' => 'light',
        'class' => 'bg-gray-200 hover:bg-gray-300 text-gray-800'
    ],
    'back' => [
        'label' => 'Kembali',
        'icon' => 'fa-arrow-left',
        'color' => 'warning',
        'class' => 'bg-yellow-400 hover:bg-yellow-500 text-gray-800 border border-yellow-500'
    ],
    'next' => [
        'label' => 'Lanjut',
        'icon' => 'fa-arrow-right',
        'color' => 'primary',
        'class' => 'bg-blue-600 hover:bg-blue-700 text-white'
    ],
    'export' => [
        'label' => 'Ekspor CSV',
        'icon' => 'fa-download',
        'color' => 'dark',
        'class' => 'bg-gray-800 hover:bg-gray-900 text-white'
    ],
    'settings' => [
        'label' => 'Pengaturan',
        'icon' => 'fa-gear',
        'color' => 'secondary',
        'class' => 'bg-gray-500 hover:bg-gray-600 text-white'
    ],
    'logout' => [
        'label' => 'Keluar',
        'icon' => 'fa-sign-out',
        'color' => 'danger',
        'class' => 'bg-red-500 hover:bg-red-600 text-white'
    ],
];

$config = $buttonConfig[$type] ?? $buttonConfig['add'];
@endphp

<button type="{{ $htmlType ?? 'button' }}" {{ $attributes->merge(['class' => $config['class'] . ' inline-flex items-center gap-2 rounded px-4 py-2 font-medium transition']) }}>
  <i class="fas {{ $config['icon'] }}"></i>
  {{ $config['label'] }}
</button>
