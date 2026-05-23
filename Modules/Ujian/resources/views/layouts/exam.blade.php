<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Ujian' }} | UIN Prof. K.H. Saifuddin Zuhri</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/images/uin.svg') }}">

    <style>
        [x-cloak] { display: none !important; }
        body { overscroll-behavior: contain; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Apply theme yang sudah dipilih user (light/dark) tanpa flash --}}
    <script>
        (function () {
            const saved = localStorage.getItem('theme');
            const sys = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const t = saved || sys;
            if (t === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
</head>

<body class="bg-slate-100 dark:bg-[#0b1220] min-h-screen flex flex-col">
    {{--
        Layout khusus halaman pengerjaan ujian.
        Sengaja tidak include sidebar/header app utama supaya peserta
        fokus ke soal & tidak gampang berpindah halaman.
    --}}
    @yield('content')

    @stack('scripts')
</body>
</html>
