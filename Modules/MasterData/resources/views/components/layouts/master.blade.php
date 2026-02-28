<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sainteku - Master Data Dashboard</title>

    {{-- CSS TailAdmin --}}
    @if (file_exists(public_path('tailadmin/style.css')))
        <link rel="stylesheet" href="{{ asset('tailadmin/style.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('tailadmin-free-tailwind-dashboard-template-main/src/css/style.css') }}">
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        ::selection {
            background-color: #feeb04;
            color: #000;
        }

        /* Pastikan tidak ada overflow liar */
        body {
            overflow: hidden;
        }
    </style>
</head>

<body x-data="{ 
        sidebarToggle: true, 
        darkMode: localStorage.getItem('darkMode') === 'true',
        selected: 'Master Data'
    }" x-init="$watch('darkMode', value => localStorage.setItem('darkMode', value))"
    :class="{ 'dark bg-[#050505]': darkMode, 'bg-gray-50': !darkMode }" class="font-sans text-body">

    <div class="flex h-screen overflow-hidden">
        {{-- 1. SIDEBAR (Paling Depan) --}}
        @include('masterdata::components.partials.sidebar')

        {{-- 2. CONTENT AREA (Di belakang sidebar tapi bergeser) --}}
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden transition-all duration-300 ease-in-out"
            {{-- INI KUNCINYA: Jika sidebar terbuka, kasih margin 290px, jika mini kasih 90px --}}
            :class="sidebarToggle ? 'lg:ml-[290px]' : 'lg:ml-[90px]'">

            {{-- HEADER --}}
            @include('masterdata::components.partials.header')

            {{-- MAIN CONTENT --}}
            <main class="min-h-screen">
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @if (file_exists(public_path('tailadmin/bundle.js')))
        <script src="{{ asset('tailadmin/bundle.js') }}"></script>
    @endif
</body>

</html>