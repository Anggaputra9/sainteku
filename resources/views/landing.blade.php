<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SAINTEKU | UIN SAIZU PURWOKERTO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Fakultas Sains dan Teknologi UIN Saifuddin Zuhri Purwokerto" name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="assets/images/uin.png">

    <script src="https://cdn.tailwindcss.com" data-cfasync="false"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        saintek: {
                            primary: '#FEEB04',
                            dark: '#CBB800',
                            text: '#856B2B',
                            soft: 'rgba(254,235,4,0.15)',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --saintek-primary: #FEEB04;
            --saintek-dark: #CBB800;
            --saintek-text: #856B2B;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Card hover */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 25px 40px -12px rgba(254, 235, 4, 0.2);
        }

        /* Alert popup animation */
        @keyframes slideDown {
            from {
                transform: translateX(-50%) translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }

            to {
                transform: translateX(-50%) translateY(-100%);
                opacity: 0;
            }
        }

        .alert-popup {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 320px;
            max-width: 500px;
            text-align: center;
            animation: slideDown 0.3s ease-out;
        }

        /* Hamburger */
        .hamburger-btn span {
            transition: all 0.3s ease;
        }

        .hamburger-btn.active span:nth-child(1) {
            transform: translateY(8.5px) rotate(45deg);
        }

        .hamburger-btn.active span:nth-child(2) {
            opacity: 0;
            transform: scale(0);
        }

        .hamburger-btn.active span:nth-child(3) {
            transform: translateY(-8.5px) rotate(-45deg);
        }

        /* Mobile menu */
        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 25px rgba(0, 0, 0, 0.1);
            z-index: 1002;
            transition: right 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .mobile-menu.open {
            right: 0;
        }

        .mobile-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Modal tertutup tidak menangkap klik */
        #loginModal.hidden,
        #forgotPasswordModal.hidden {
            display: none !important;
            pointer-events: none !important;
        }

        /* Focus ring */
        input:focus,
        select:focus {
            outline: none;
            border-color: var(--saintek-primary) !important;
            box-shadow: 0 0 0 3px rgba(254, 235, 4, 0.2) !important;
        }

        /* Modal backdrop blur */
        .modal-backdrop {
            backdrop-filter: blur(4px);
        }

        /* Back to top */
        #back-to-top {
            display: none;
        }
    </style>
</head>

<body class="text-slate-800 bg-white" data-bs-spy="scroll">

    {{-- ============================
    NAVBAR
    ============================= --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

            {{-- Brand --}}
            <a href="#" class="text-3xl font-bold text-slate-900 tracking-tight">Sainteku</a>

            {{-- Desktop Nav --}}
            <ul class="hidden lg:flex items-center gap-1">
                <li><a href="#event"
                        class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-saintek-text transition-colors">{{ __('messages.event') }}</a>
                </li>
                <li><a href="#prestasi"
                        class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-saintek-text transition-colors">{{ __('messages.achievements') }}</a>
                </li>
                <li><a href="#blog"
                        class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-saintek-text transition-colors">{{ __('messages.blog') }}</a>
                </li>
            </ul>

            {{-- Desktop Right Actions --}}
            <div class="hidden lg:flex items-center gap-3">
                {{-- Language Dropdown --}}
                <div class="relative">
                    <button id="langToggle" onclick="document.getElementById('langMenu').classList.toggle('hidden')"
                        class="flex items-center gap-1.5 text-sm font-medium px-3 py-1.5 border border-gray-300 rounded bg-white hover:bg-gray-50 transition-colors">
                        <i class="ri-global-line text-base"></i>
                        <span>{{ session('locale') == 'en' ? 'English' : 'Indonesia' }}</span>
                        <i class="ri-arrow-down-s-line text-base"></i>
                    </button>
                    <ul id="langMenu"
                        class="hidden absolute right-0 mt-1 w-40 bg-white border border-gray-200 rounded shadow-md py-1 z-50">
                        <li>
                            <a href="{{ route('language.switch', 'id') }}"
                                class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ session('locale') == 'id' ? 'font-semibold bg-gray-100' : '' }}">
                                <span>🇮🇩</span> Indonesia
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('language.switch', 'en') }}"
                                class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ session('locale') == 'en' ? 'font-semibold bg-gray-100' : '' }}">
                                <span>🇬🇧</span> English
                            </a>
                        </li>
                    </ul>
                </div>

                <button onclick="document.getElementById('loginModal').classList.remove('hidden')"
                    class="flex items-center gap-1.5 px-4 py-2 bg-saintek-primary text-black text-sm font-semibold rounded hover:bg-saintek-dark transition-colors">
                    <i class="ri-user-3-line"></i> {{ __('messages.login') }}
                </button>
            </div>

            {{-- Hamburger --}}
            <button
                class="hamburger-btn lg:hidden flex flex-col justify-center items-center gap-1.5 w-10 h-10 rounded-lg"
                id="hamburgerBtn" type="button">
                <span class="block w-6 h-0.5 bg-slate-800 rounded"></span>
                <span class="block w-6 h-0.5 bg-slate-800 rounded"></span>
                <span class="block w-6 h-0.5 bg-slate-800 rounded"></span>
            </button>
        </div>
    </nav>

    {{-- ============================
    MOBILE MENU OVERLAY
    ============================= --}}
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

    {{-- ============================
    MOBILE SIDEBAR
    pt-16 = tinggi navbar biar ga ketutupan
    ============================= --}}
    <div class="mobile-menu" id="mobileMenu">
        {{-- Header sidebar --}}
        <div class="flex items-center justify-between px-5 border-b border-gray-100"
            style="padding-top: 4.5rem; padding-bottom: 1.25rem;">
            <h5 class="text-lg font-bold text-slate-900">Menu</h5>
            <button
                class="w-9 h-9 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-2xl leading-none transition-colors"
                id="closeMenuBtn">&times;</button>
        </div>

        {{-- Body sidebar --}}
        <div class="flex-1 flex flex-col px-5 py-5 gap-4">
            {{-- Nav links --}}
            <ul class="space-y-2">
                <li>
                    <a href="#event"
                        class="mobile-nav-link flex items-center gap-3 px-4 py-3 bg-slate-50 rounded-xl text-base font-medium text-slate-700 hover:bg-yellow-50 hover:text-saintek-text transition-all">
                        <i class="ri-calendar-event-line text-lg"></i> {{ __('messages.event') }}
                    </a>
                </li>
                <li>
                    <a href="#prestasi"
                        class="mobile-nav-link flex items-center gap-3 px-4 py-3 bg-slate-50 rounded-xl text-base font-medium text-slate-700 hover:bg-yellow-50 hover:text-saintek-text transition-all">
                        <i class="ri-trophy-line text-lg"></i> {{ __('messages.achievements') }}
                    </a>
                </li>
                <li>
                    <a href="#blog"
                        class="mobile-nav-link flex items-center gap-3 px-4 py-3 bg-slate-50 rounded-xl text-base font-medium text-slate-700 hover:bg-yellow-50 hover:text-saintek-text transition-all">
                        <i class="ri-article-line text-lg"></i> {{ __('messages.blog') }}
                    </a>
                </li>
            </ul>

            <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>

            {{-- Language --}}
            <div class="bg-slate-50 rounded-2xl p-4">
                <p class="text-xs font-semibold text-slate-500 mb-3 uppercase tracking-wide">Language</p>
                <div class="space-y-1">
                    <a href="{{ route('language.switch', 'id') }}"
                        class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-yellow-50 hover:text-saintek-text transition-all {{ session('locale') == 'id' ? 'bg-white shadow-sm font-semibold' : '' }}">
                        <span>🇮🇩</span> Indonesia
                    </a>
                    <a href="{{ route('language.switch', 'en') }}"
                        class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-yellow-50 hover:text-saintek-text transition-all {{ session('locale') == 'en' ? 'bg-white shadow-sm font-semibold' : '' }}">
                        <span>🇬🇧</span> English
                    </a>
                </div>
            </div>

            <div class="mt-auto">
                <button onclick="document.getElementById('loginModal').classList.remove('hidden'); closeMobileMenu();"
                    class="w-full flex items-center justify-center gap-2 bg-saintek-primary hover:bg-saintek-dark text-black font-semibold text-base py-3.5 rounded-2xl transition-all">
                    <i class="ri-user-3-line"></i> {{ __('messages.login') }}
                </button>
            </div>
        </div>
    </div>

    {{-- ============================
    ALERT POPUP
    ============================= --}}
    <div id="loginAlert" class="alert-popup rounded-xl shadow-xl px-5 py-3 hidden" role="alert">
        <i id="alertIcon" class="ri-checkbox-circle-line mr-2 text-lg align-middle"></i>
        <span id="alertMessage"></span>
        <button type="button" class="ml-3 text-lg leading-none opacity-60 hover:opacity-100"
            onclick="hideAlert()">&times;</button>
    </div>

    {{-- ============================
    HERO SECTION
    ============================= --}}
    <section class="pt-40 pb-24 bg-gradient-to-br from-white to-amber-50" id="hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-semibold leading-tight mb-4 capitalize">
                        {{ __('messages.faculty_name') }}
                    </h1>
                    <p class="text-xl text-slate-500 leading-relaxed mb-8">
                        {!! __('messages.faculty_desc', ['study_programs' => '<strong>' . __('messages.study_programs') . '</strong>']) !!}
                    </p>

                    <form action="#" class="bg-white p-4 rounded-2xl shadow-md">
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                            <div class="sm:col-span-5">
                                <input type="search"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-saintek-primary"
                                    placeholder="{{ __('messages.search_placeholder') }}">
                            </div>
                            <div class="sm:col-span-4">
                                <select
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-saintek-primary bg-white">
                                    <option value="">{{ __('messages.interest_placeholder') }}</option>
                                    <option value="Informatika">{{ __('messages.informatics') }}</option>
                                    <option value="Arsitektur">{{ __('messages.architecture') }}</option>
                                    <option value="Ilmu Lingkungan">{{ __('messages.environmental') }}</option>
                                    <option value="Ilmu Perpustakaan">{{ __('messages.library') }}</option>
                                </select>
                            </div>
                            <div class="sm:col-span-3">
                                <button
                                    class="w-full flex items-center justify-center gap-1.5 bg-saintek-primary hover:bg-saintek-dark text-black font-semibold py-3 px-4 rounded-lg text-sm transition-colors"
                                    type="button">
                                    <i class="ri-search-2-line"></i> {{ __('messages.find_button') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <ul class="flex flex-wrap items-center gap-2 mt-5 text-sm">
                        <li class="text-red-500 font-semibold flex items-center gap-1"><i
                                class="ri-price-tag-3-line"></i> {{ __('messages.featured_programs') }}</li>
                        <li><a href="javascript:void(0)"
                                class="underline text-slate-600 hover:text-saintek-text">{{ __('messages.informatics') }},</a>
                        </li>
                        <li><a href="javascript:void(0)"
                                class="underline text-slate-600 hover:text-saintek-text">{{ __('messages.architecture') }},</a>
                        </li>
                        <li><a href="javascript:void(0)"
                                class="underline text-slate-600 hover:text-saintek-text">{{ __('messages.environmental') }},</a>
                        </li>
                        <li><a href="javascript:void(0)"
                                class="underline text-slate-600 hover:text-saintek-text">{{ __('messages.library') }}</a>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-center mt-8 lg:mt-0">
                    <img src="assets/images/image11.png" alt="Hero Illustration" class="w-full max-w-lg">
                </div>
            </div>
        </div>
    </section>

    {{-- ============================
    ABOUT SECTION
    ============================= --}}
    <section class="py-24 bg-slate-50" id="tentang">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                {{-- Image --}}
                <div class="relative">
                    <img src="assets/images/about.jpg" alt="" class="w-full rounded-2xl shadow-2xl">

                    {{-- Dean card --}}
                    <div
                        class="absolute bottom-4 left-4 bg-white rounded-2xl shadow-xl p-3 flex items-center gap-3 max-w-xs">
                        <img src="assets/images/image.png" alt="" class="w-12 h-12 rounded-full object-cover shrink-0">
                        <div>
                            <h6 class="font-bold text-sm text-slate-800 leading-tight">Prof. Dr. Kholid Mawardi, M. Hum.
                            </h6>
                            <p class="text-xs text-slate-500 mt-0.5">{{ __('messages.dean') }}</p>
                            <div class="flex gap-0.5 mt-1 text-yellow-400 text-xs">
                                <i class="ri-star-s-fill"></i><i class="ri-star-s-fill"></i><i
                                    class="ri-star-s-fill"></i><i class="ri-star-s-fill"></i><i
                                    class="ri-star-s-fill"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Students card --}}
                    <div class="absolute top-4 right-4 bg-white rounded-2xl shadow-xl p-3 flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center shrink-0">
                            <i class="ri-briefcase-2-line text-slate-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800 leading-tight"><span
                                    class="text-slate-700 font-bold">1000+</span> {{ __('Mahasiswa') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div>
                    <h2 class="text-3xl lg:text-4xl font-semibold leading-snug mb-5">{{ __('messages.about_title') }}
                    </h2>
                    <p class="text-slate-500 text-lg mb-4 leading-relaxed">{{ __('messages.about_desc1') }}</p>
                    <p class="text-slate-500 text-lg mb-6 leading-relaxed">{{ __('messages.about_desc2') }}</p>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-start gap-3">
                            <i class="ri-checkbox-circle-fill text-saintek-text text-2xl shrink-0 mt-0.5"></i>
                            <span class="text-base"><strong>{{ __('messages.about_list1') }}</strong> (Informatika,
                                Arsitektur, Ilmu Lingkungan, Ilmu Perpustakaan)</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="ri-checkbox-circle-fill text-saintek-text text-2xl shrink-0 mt-0.5"></i>
                            <span class="text-base">{{ __('messages.about_list2') }}</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="ri-checkbox-circle-fill text-saintek-text text-2xl shrink-0 mt-0.5"></i>
                            <span class="text-base">{{ __('messages.about_list3') }}</span>
                        </div>
                    </div>

                    <a href="#!"
                        class="inline-flex items-center gap-2 bg-saintek-primary hover:bg-saintek-dark text-black font-semibold px-8 py-3 rounded-full transition-colors text-base">
                        {{ __('messages.explore_button') }} <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================
    PROGRAM STUDI SECTION
    ============================= --}}
    <section class="py-24" id="program-studi">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl lg:text-4xl font-semibold mb-4 text-saintek-text">{{ __('messages.our_programs') }}
                </h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto">{{ __('messages.programs_desc') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $programs = [
                        ['icon' => 'ri-computer-line', 'key' => 'informatics'],
                        ['icon' => 'ri-building-2-line', 'key' => 'architecture'],
                        ['icon' => 'ri-leaf-line', 'key' => 'environmental'],
                        ['icon' => 'ri-book-open-line', 'key' => 'library'],
                    ];
                @endphp
                @foreach($programs as $prog)
                    <div
                        class="card-hover bg-white border border-gray-100 rounded-3xl shadow-sm p-8 text-center flex flex-col items-center">
                        <div class="w-20 h-20 bg-yellow-50 rounded-2xl flex items-center justify-center mb-6">
                            <i class="{{ $prog['icon'] }} text-4xl text-saintek-text"></i>
                        </div>
                        <h4 class="font-semibold text-lg mb-3">{{ __('messages.' . $prog['key']) }}</h4>
                        <p class="text-slate-500 text-sm mb-5 leading-relaxed">
                            {{ __('messages.' . $prog['key'] . '_desc') }}
                        </p>
                        <a href="#"
                            class="text-saintek-text font-medium text-sm hover:underline mt-auto flex items-center gap-1">{{ __('messages.detail') }}
                            <i class="ri-arrow-right-line"></i></a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================
    CTA BANNER
    ============================= --}}
    <section class="bg-saintek-primary py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h4 class="text-black text-2xl lg:text-3xl font-semibold mb-2">{{ __('messages.cta_title') }}</h4>
                    <p class="text-black/70 text-lg">{{ __('messages.cta_desc') }}</p>
                </div>
                <div class="lg:shrink-0">
                    <a href="#!"
                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-3.5 rounded-full transition-colors text-base shadow-lg">
                        {{ __('messages.register_button') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================
    PRESTASI SECTION
    ============================= --}}
    <section class="py-24" id="prestasi">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">{{ __('messages.achievements_title') }}</h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto">{{ __('messages.achievements_desc') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $achievements = [
                        ['img' => 'img-10.jpg', 'dept' => 'informatics', 'team' => 'Tim Rajawali', 'title' => 'Juara 1 Gemastik XIV', 'desc' => 'Aplikasi Smart Campus untuk efisiensi energi kampus.', 'avatars' => ['avatar-2.jpg', 'avatar-3.jpg', 'avatar-4.jpg']],
                        ['img' => 'img-9.jpg', 'dept' => 'architecture', 'team' => 'Tim Garuda', 'title' => 'Juara 2 Lomba Desain Arsitektur', 'desc' => 'Desain hunian vertikal ramah lingkungan.', 'avatars' => ['avatar-5.jpg', 'avatar-6.jpg', 'avatar-7.jpg']],
                        ['img' => 'img-8.jpg', 'dept' => 'environmental', 'team' => 'Tim Hijau', 'title' => 'Juara Harapan 1 KTI', 'desc' => 'Pengolahan sampah organik menjadi energi.', 'avatars' => ['avatar-8.jpg', 'avatar-9.jpg', 'avatar-10.jpg']],
                    ];
                @endphp
                @foreach($achievements as $item)
                    <div class="card-hover bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100">
                        <div class="relative">
                            <img src="assets/images/small/{{ $item['img'] }}" class="w-full h-48 object-cover"
                                alt="{{ $item['title'] }}">
                            <div class="absolute top-3 right-3">
                                <span
                                    class="bg-white text-slate-800 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">2024</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-9 h-9 bg-slate-100 rounded-full flex items-center justify-center shrink-0">
                                    <i class="ri-trophy-line text-saintek-text"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-800">{{ __('messages.' . $item['dept']) }}</p>
                                    <p class="text-xs text-slate-500">{{ $item['team'] }}</p>
                                </div>
                            </div>
                            <h5 class="font-bold text-base mb-2">{{ $item['title'] }}</h5>
                            <p class="text-slate-500 text-sm mb-4">{{ $item['desc'] }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    @foreach($item['avatars'] as $av)
                                        <img src="assets/images/users/{{ $av }}"
                                            class="w-7 h-7 rounded-full border-2 border-white object-cover" alt="">
                                    @endforeach
                                </div>
                                <a href="#"
                                    class="text-saintek-text text-sm font-medium hover:underline flex items-center gap-1">{{ __('messages.more') }}
                                    <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="#"
                    class="inline-flex items-center gap-2 border-2 border-saintek-primary text-saintek-text font-semibold px-8 py-3 rounded-full hover:bg-saintek-primary hover:text-black transition-colors">
                    {{ __('messages.view_all') }}
                </a>
            </div>
        </div>
    </section>

    {{-- ============================
    FASILITAS SECTION
    ============================= --}}
    <section class="py-24" id="fasilitas">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">{{ __('messages.facilities_title') }}</h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto">{{ __('messages.facilities_desc') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $facilities = [
                        ['img' => 'img-3.jpg', 'title' => 'Lab. Terpadu Informatika', 'desc' => '40 Unit PC Spesifikasi Tinggi, VR, IoT', 'badge' => 'informatics'],
                        ['img' => 'img-4.jpg', 'title' => 'Studio Arsitektur', 'desc' => 'Ruang desain, workshop, peralatan lengkap', 'badge' => 'architecture'],
                        ['img' => 'img-5.jpg', 'title' => 'Lab. Ilmu Lingkungan', 'desc' => 'Alat ukur kualitas air, udara, dan tanah', 'badge' => 'environmental'],
                    ];
                @endphp
                @foreach($facilities as $fac)
                    <div
                        class="card-hover bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 flex flex-col">
                        <img src="assets/images/small/{{ $fac['img'] }}" class="w-full h-44 object-cover"
                            alt="{{ $fac['title'] }}">
                        <div class="p-5 flex flex-col flex-1">
                            <h5 class="font-bold text-base mb-1.5">{{ $fac['title'] }}</h5>
                            <p class="text-slate-500 text-sm mb-3">{{ $fac['desc'] }}</p>
                            <span
                                class="mt-auto inline-block bg-yellow-50 text-saintek-text text-xs font-semibold px-3 py-1 rounded-full self-start">{{ __('messages.' . $fac['badge']) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="#"
                    class="inline-flex items-center gap-2 bg-saintek-primary hover:bg-saintek-dark text-black font-semibold px-8 py-3 rounded-full transition-colors">
                    {{ __('messages.explore_facilities') }}
                </a>
            </div>
        </div>
    </section>

    {{-- ============================
    BLOG SECTION
    ============================= --}}
    @include('partials.blog-section')

    {{-- ============================
    NEWSLETTER BANNER
    ============================= --}}
    <section class="bg-saintek-primary py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h4 class="text-black text-2xl lg:text-3xl font-semibold mb-2">{{ __('messages.newsletter_title') }}
                    </h4>
                    <p class="text-black/70 text-lg">{{ __('messages.newsletter_desc') }}</p>
                </div>
                <div class="lg:shrink-0">
                    <button
                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-3.5 rounded-full transition-colors shadow-lg">
                        {{ __('messages.subscribe_button') }} <i class="ri-arrow-right-line"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================
    FOOTER
    ============================= --}}
    <footer class="bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <div class="lg:col-span-4">
                    <span class="text-white text-3xl font-bold">Sainteku</span>
                    <p class="text-slate-400 text-sm mt-4 leading-relaxed">{{ __('messages.footer_desc') }}</p>
                    <div class="flex gap-2 mt-5">
                        @foreach(['ri-facebook-fill', 'ri-twitter-fill', 'ri-instagram-fill', 'ri-youtube-fill'] as $icon)
                            <a href="javascript: void(0);"
                                class="w-9 h-9 bg-slate-800 hover:bg-saintek-primary hover:text-black text-white rounded-full flex items-center justify-center transition-colors text-sm">
                                <i class="{{ $icon }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-8 grid grid-cols-2 sm:grid-cols-3 gap-8">
                    <div>
                        <h5 class="text-white font-semibold mb-4">{{ __('messages.quick_links') }}</h5>
                        <ul class="space-y-2 text-sm text-slate-400">
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.about_us') }}</a>
                            </li>
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.accreditation') }}</a>
                            </li>
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.lecturers') }}</a>
                            </li>
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.student_affairs') }}</a>
                            </li>
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.alumni') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="text-white font-semibold mb-4">{{ __('messages.study_programs_footer') }}</h5>
                        <ul class="space-y-2 text-sm text-slate-400">
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.informatics') }}</a>
                            </li>
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.architecture') }}</a>
                            </li>
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.environmental') }}</a>
                            </li>
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.library') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="text-white font-semibold mb-4">{{ __('messages.help') }}</h5>
                        <ul class="space-y-2 text-sm text-slate-400">
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.faq') }}</a>
                            </li>
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.contact_us') }}</a>
                            </li>
                            <li><a href="#"
                                    class="hover:text-saintek-primary transition-colors">{{ __('messages.sitemap') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div
                class="border-t border-slate-800 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-slate-500 text-sm">
                    <script>document.write(new Date().getFullYear())</script> © Sainteku —
                    {{ __('UIN Prof. K.H. Saifuddin Zuhri') }}
                </p>
                <ul class="flex gap-5 text-sm text-slate-500">
                    <li><a href="#"
                            class="hover:text-saintek-primary transition-colors">{{ __('messages.privacy_policy') }}</a>
                    </li>
                    <li><a href="#" class="hover:text-saintek-primary transition-colors">{{ __('messages.terms') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    {{-- Back to top --}}
    <button onclick="topFunction()" id="back-to-top"
        class="fixed bottom-8 right-8 w-11 h-11 bg-saintek-primary hover:bg-saintek-dark text-black rounded-full flex items-center justify-center shadow-lg transition-all z-40">
        <i class="ri-arrow-up-line text-lg"></i>
    </button>

    {{-- ============================
    LOGIN MODAL
    ============================= --}}
    <div class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-[1050] flex items-center justify-center p-4"
        id="loginModal">
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl w-full max-w-4xl flex max-h-[90vh]"
            onclick="event.stopPropagation()">

            {{-- Left panel --}}
            <div class="hidden md:flex flex-col items-center justify-center w-5/12 shrink-0 relative overflow-hidden"
                style="background: linear-gradient(145deg, #FEEB04 0%, #CBB800 100%); min-height: 520px;">
                <div class="text-center relative z-10 px-8 py-6">
                    <img src="{{ asset('assets/images/uin.png') }}" alt="Logo UIN Saizu"
                        class="w-20 h-auto mx-auto mb-4">
                    <h2 class="font-bold text-black text-3xl mb-1">Sainteku</h2>
                    <p class="text-black/80 text-sm">{{ __('messages.faculty_name') }}</p>
                    <p class="text-black/80 text-sm mb-6">{{ __('UIN Prof. K.H. Saifuddin Zuhri Purwokerto') }}</p>
                    <p class="italic text-black/80 text-sm max-w-xs leading-relaxed">"{{ __('messages.quote') }}"</p>
                </div>
            </div>

            {{-- Right panel --}}
            <div class="flex-1 overflow-y-auto">
                {{-- Mobile header --}}
                <div class="md:hidden p-5 text-center border-b border-gray-100 bg-slate-50">
                    <h2 class="text-2xl font-bold text-black">Sainteku</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('messages.faculty_name') }}</p>
                </div>

                <div class="p-6 max-w-md mx-auto">
                    <div class="mb-4">
                        <button onclick="document.getElementById('loginModal').classList.add('hidden')"
                            class="inline-flex items-center text-sm text-slate-500 hover:text-slate-800 transition-colors">
                            <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 20 20" fill="none">
                                <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            {{ __('messages.back_to_dashboard') }}
                        </button>
                    </div>

                    <div class="mb-6">
                        <h1 class="font-semibold text-2xl text-slate-800 mb-1">{{ __('messages.sign_in') }}</h1>
                        <p class="text-sm text-slate-500">{{ __('messages.enter_credentials') }}</p>
                    </div>

                    <form method="POST" action="/login" id="loginForm">
                        @csrf
                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                                @foreach($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                {{ __('messages.email_label') }}<span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:border-saintek-primary"
                                id="credential" name="credential" placeholder="info@gmail.com"
                                value="{{ old('credential') }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                {{ __('messages.password_label') }}<span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password"
                                    class="w-full px-3 py-2.5 pr-10 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:border-saintek-primary"
                                    id="password" name="password"
                                    placeholder="{{ __('messages.password_placeholder') }}" required>
                                <button type="button" id="togglePasswordBtn"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg id="icon-eye-show" width="18" height="18" viewBox="0 0 20 20" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z"
                                            fill="#98A2B3" />
                                    </svg>
                                    <svg id="icon-eye-hide" width="18" height="18" viewBox="0 0 20 20" fill="none"
                                        style="display:none;">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z"
                                            fill="#98A2B3" />
                                    </svg>
                                </button>
                            </div>
                            <small class="text-slate-400 text-xs mt-1 block">{{ __('messages.password_hint') }}</small>
                        </div>

                        <div class="flex items-center justify-between mb-5">
                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded accent-saintek-primary" id="remember"
                                    name="remember">
                                {{ __('messages.remember_me') }}
                            </label>
                            <button type="button"
                                onclick="document.getElementById('loginModal').classList.add('hidden'); document.getElementById('forgotPasswordModal').classList.remove('hidden');"
                                class="text-sm text-black hover:underline">
                                {{ __('messages.forgot_password') }}
                            </button>
                        </div>

                        <button type="submit"
                            class="w-full bg-saintek-primary hover:bg-saintek-dark text-black font-semibold py-2.5 rounded-lg text-sm transition-colors">
                            {{ __('messages.login') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================
    FORGOT PASSWORD MODAL
    ============================= --}}
    <div class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[1050] flex items-center justify-center overflow-y-auto p-4"
        id="forgotPasswordModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-4">
                <h5 class="font-bold text-lg text-slate-800">{{ __('messages.forgot_password_title') }}</h5>
                <button onclick="document.getElementById('forgotPasswordModal').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors text-xl leading-none">&times;</button>
            </div>

            <div id="forgotPasswordAlert" class="hidden text-sm rounded-lg px-4 py-3 mb-4"></div>
            <p class="text-sm text-slate-500 mb-5">{{ __('messages.forgot_password_desc') }}</p>

            <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ __('messages.email_label') }}<span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-saintek-primary"
                        name="email" id="forgot_email" placeholder="nama@email.com" required>
                </div>

                <div class="flex gap-3">
                    <button type="button"
                        class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-sm rounded-lg transition-colors"
                        onclick="document.getElementById('forgotPasswordModal').classList.add('hidden')">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-saintek-primary hover:bg-saintek-dark text-black font-semibold text-sm rounded-lg transition-colors">
                        {{ __('messages.send_reset_link') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Close lang dropdown on outside click
            document.addEventListener('click', function (e) {
                const langMenu = document.getElementById('langMenu');
                const langToggle = document.getElementById('langToggle');
                if (langMenu && !langToggle.contains(e.target) && !langMenu.contains(e.target)) {
                    langMenu.classList.add('hidden');
                }
            });

            // Close modal on backdrop click
            const loginModal = document.getElementById('loginModal');
            const forgotPasswordModal = document.getElementById('forgotPasswordModal');

            if (loginModal) {
                loginModal.addEventListener('click', function (e) {
                    if (e.target === this) this.classList.add('hidden');
                });
            }
            if (forgotPasswordModal) {
                forgotPasswordModal.addEventListener('click', function (e) {
                    if (e.target === this) this.classList.add('hidden');
                });
            }

            // Password toggle
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('password');
            const iconEyeShow = document.getElementById('icon-eye-show');
            const iconEyeHide = document.getElementById('icon-eye-hide');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function () {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        iconEyeShow.style.display = 'none';
                        iconEyeHide.style.display = 'block';
                    } else {
                        passwordInput.type = 'password';
                        iconEyeShow.style.display = 'block';
                        iconEyeHide.style.display = 'none';
                    }
                });
            }

            // Login form AJAX
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = '{{ __("messages.login") }}';
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '{{ __("messages.processing") }}...';

                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                        }
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                showAlert('success', data.message);
                                setTimeout(() => { window.location.href = data.redirect || '/dashboard'; }, 1500);
                            } else {
                                showAlert('error', data.message || '{{ __("messages.login_failed") }}');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                            }
                        })
                        .catch(() => {
                            showAlert('error', '{{ __("messages.error_occurred") }}');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                });
            }

            // Forgot password AJAX
            const forgotForm = document.getElementById('forgotPasswordForm');
            const forgotAlert = document.getElementById('forgotPasswordAlert');

            if (forgotForm) {
                forgotForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = '{{ __("messages.send_reset_link") }}';
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '{{ __("messages.sending") }}...';

                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                        }
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (!forgotAlert) return;
                            forgotAlert.classList.remove('hidden');
                            if (data.success) {
                                forgotAlert.className = 'text-sm rounded-lg px-4 py-3 mb-4 bg-green-50 border border-green-200 text-green-700';
                                forgotAlert.innerHTML = data.message;
                                forgotForm.reset();
                                setTimeout(() => {
                                    document.getElementById('forgotPasswordModal').classList.add('hidden');
                                    forgotAlert.classList.add('hidden');
                                }, 3000);
                            } else {
                                forgotAlert.className = 'text-sm rounded-lg px-4 py-3 mb-4 bg-red-50 border border-red-200 text-red-700';
                                forgotAlert.innerHTML = data.message || '{{ __("messages.email_not_found") }}';
                            }
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        })
                        .catch(() => {
                            if (!forgotAlert) return;
                            forgotAlert.classList.remove('hidden');
                            forgotAlert.className = 'text-sm rounded-lg px-4 py-3 mb-4 bg-red-50 border border-red-200 text-red-700';
                            forgotAlert.innerHTML = '{{ __("messages.error_occurred") }}';
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                });
            }

            // Laravel errors → open login modal
            @if($errors->any())
                if (loginModal) loginModal.classList.remove('hidden');
                showAlert('error', '{{ $errors->first() }}');
            @endif

                // Laravel session status → open forgot modal
                @if(session('status'))
                    const fm = document.getElementById('forgotPasswordModal');
                    const fa = document.getElementById('forgotPasswordAlert');
                    if (fm && fa) {
                        fm.classList.remove('hidden');
                        fa.classList.remove('hidden');
                        fa.className = 'text-sm rounded-lg px-4 py-3 mb-4 bg-green-50 border border-green-200 text-green-700';
                        fa.innerHTML = '{{ session("status") }}';
                    }
                @endif

            // Hamburger / mobile menu
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const menuOverlay = document.getElementById('mobileMenuOverlay');
            const closeMenuBtn = document.getElementById('closeMenuBtn');

            function openMobileMenu() {
                mobileMenu.classList.add('open');
                menuOverlay.classList.add('active');
                hamburgerBtn.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenu() {
                mobileMenu.classList.remove('open');
                menuOverlay.classList.remove('active');
                hamburgerBtn.classList.remove('active');
                document.body.style.overflow = '';
            }
            window.closeMobileMenu = closeMobileMenu;

            if (hamburgerBtn) hamburgerBtn.addEventListener('click', openMobileMenu);
            if (closeMenuBtn) closeMenuBtn.addEventListener('click', closeMobileMenu);
            if (menuOverlay) menuOverlay.addEventListener('click', closeMobileMenu);

            document.querySelectorAll('.mobile-nav-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    closeMobileMenu();
                    const targetId = this.getAttribute('href');
                    if (targetId && targetId !== '#') {
                        e.preventDefault();
                        const el = document.querySelector(targetId);
                        if (el) el.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) closeMobileMenu();
            });

            // Back to top
            window.addEventListener('scroll', function () {
                const btn = document.getElementById('back-to-top');
                if (btn) btn.style.display = window.scrollY > 300 ? 'flex' : 'none';
            });
        });

        function topFunction() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showAlert(type, message) {
            const alert = document.getElementById('loginAlert');
            const alertIcon = document.getElementById('alertIcon');
            const alertMessage = document.getElementById('alertMessage');
            if (!alert || !alertIcon || !alertMessage) return;

            alertMessage.textContent = message;
            if (type === 'success') {
                alertIcon.className = 'ri-checkbox-circle-line mr-2 text-lg align-middle';
                alert.className = 'alert-popup rounded-xl shadow-xl px-5 py-3 bg-green-50 border border-green-200 text-green-800 flex items-center';
            } else {
                alertIcon.className = 'ri-error-warning-line mr-2 text-lg align-middle';
                alert.className = 'alert-popup rounded-xl shadow-xl px-5 py-3 bg-red-50 border border-red-200 text-red-800 flex items-center';
            }
            alert.classList.remove('hidden');
            setTimeout(() => { hideAlert(); }, 5000);
        }

        function hideAlert() {
            const alert = document.getElementById('loginAlert');
            if (alert) {
                alert.style.animation = 'slideUp 0.3s ease-in';
                setTimeout(() => { alert.classList.add('hidden'); alert.style.animation = ''; }, 300);
            }
        }
    </script>
</body>

</html>