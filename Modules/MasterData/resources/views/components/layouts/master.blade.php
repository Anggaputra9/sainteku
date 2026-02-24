<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Sainteku - Master Data Dashboard</title>

    {{-- Prefer built assets under public/tailadmin if present, otherwise use template source --}}
    @if (file_exists(public_path('tailadmin/style.css')))
        <link rel="stylesheet" href="{{ asset('tailadmin/style.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('tailadmin-free-tailwind-dashboard-template-main/src/css/style.css') }}">
    @endif

    {{-- Font Awesome for icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- JS Alpine untuk interaksi --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body x-data="{
    page: 'ecommerce',
    'loaded': true,
    'darkMode': false,
    'stickyMenu': false,
    'sidebarToggle': true,
    'scrollTop': false,
    'selected': 'Master Data'
}" x-init="darkMode = JSON.parse(localStorage.getItem('darkMode'));
$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))" :class="{ 'dark bg-gray-900': darkMode === true }">
    {{-- PERBAIKAN: Tambahkan x-data di pembungkus paling luar agar Sidebar & Header bisa sinkron --}}
    <div x-data="{ sidebarToggle: false, selected: 'Master Data' }" class="flex h-screen overflow-hidden bg-gray-100 dark:bg-boxdark-2">
        {{-- Sidebar --}}
        @include('masterdata::components.partials.sidebar')

        {{-- 
        PEMBETULAN: 
        1. Tambah :class dinamis.
        2. Jika sidebarToggle TRUE, beri margin kiri lg:ml-[290px] (lebar sidebar).
        3. Jika FALSE, ml-0 (kembali 100% lebar).
    --}}
        <div :class="sidebarToggle ? 'lg:ml-[290px]' : 'ml-0'"
            class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto duration-300 ease-in-out">
            {{-- Header --}}
            @include('masterdata::components.partials.header')

            <main>
                <div class="p-4 mx-auto max-w-screen-2xl md:p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    {{-- Alpine sudah di-include via CDN; bundler JS belum dibuild, jadi tidak menyertakan index.js --}}
    {{-- Jika build sudah ada, sertakan bundle JS untuk interaktivitas (sidebar/hamburger) --}}
    @if (file_exists(public_path('tailadmin/bundle.js')))
        <script src="{{ asset('tailadmin/bundle.js') }}"></script>
    @endif
</body>

</html>
