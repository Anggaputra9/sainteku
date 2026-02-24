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
    <div x-data="{ page: 'ecommerce', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }" 
      x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')); $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))" 
      :class="{ 'dark bg-gray-900': darkMode === true }">

    <div class="flex h-screen overflow-hidden">

        @include('masterdata::components.partials.sidebar')
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            
            @include('masterdata::components.partials.header')
            <main>
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    {{ $slot }}
                </div>
            </main>
            </div>
        </div>
    </div>

    {{-- Alpine sudah di-include via CDN; bundler JS belum dibuild, jadi tidak menyertakan index.js --}}
    {{-- Jika build sudah ada, sertakan bundle JS untuk interaktivitas (sidebar/hamburger) --}}
    @if (file_exists(public_path('tailadmin/bundle.js')))
        <script src="{{ asset('tailadmin/bundle.js') }}"></script>
    @endif
</body>

</html>
