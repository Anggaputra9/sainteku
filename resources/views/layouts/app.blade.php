<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'DASHBOARD' }} | UIN Prof. K.H. Saifuddin Zuhri</title>

    {{-- ================= FAVICON GLOBAL ================= --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/images/uin.svg') }}">

    <style>
        [x-cloak] {
            display: none !important;
        }

        #modal-root {
            position: relative;
            z-index: 10000000;
            pointer-events: none;
        }

        #modal-root > * {
            pointer-events: auto;
        }

        .app-modal-overlay {
            z-index: 10000000 !important;
        }

        .app-modal-overlay.app-modal-overlay--stacked {
            z-index: 10000001 !important;
        }

        .app-modal-overlay.app-modal-overlay--dimmed {
            z-index: 9999990 !important;
        }

        .app-modal-overlay.app-modal-overlay--topmost {
            z-index: 2147483646 !important;
        }

        /* Sidebar flyout — selalu di atas konten & footer */
        #sidebar-flyout-portal {
            position: fixed;
            inset: 0;
            z-index: 2147483000;
            pointer-events: none;
            overflow: visible;
        }

        #sidebar-flyout-portal .sidebar-flyout-panel {
            position: fixed !important;
            z-index: 2147483000 !important;
            pointer-events: auto;
            margin: 0;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    {{--
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    <!-- Theme Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                        'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {
                init() {
                    // Load saved sidebar state from localStorage (desktop only)
                    if (window.innerWidth >= 1280) {
                        const savedState = localStorage.getItem('sidebarExpanded');
                        this.isExpanded = savedState === 'true';
                    } else {
                        this.isExpanded = false;
                    }
                },
                // Initialize based on screen size
                isExpanded: false,
                isMobileOpen: false,
                isHovered: false,
                flyoutIndex: null,
                flyoutTop: 0,
                flyoutLeft: 0,
                flyoutMenu: null,
                flyoutHideTimeout: null,

                isCollapsedDesktop() {
                    return window.innerWidth >= 1280 && !this.isExpanded && !this.isMobileOpen;
                },

                showFlyout(index, el) {
                    if (!this.isCollapsedDesktop()) return;
                    const menus = window.__SIDEBAR_FLYOUT_MENUS || {};
                    if (!menus[index]) return;

                    clearTimeout(this.flyoutHideTimeout);
                    const rect = el.getBoundingClientRect();
                    const panelHeight = 280;
                    const maxTop = Math.max(8, window.innerHeight - panelHeight - 16);
                    this.flyoutTop = Math.min(Math.max(8, rect.top), maxTop);
                    this.flyoutLeft = rect.right + 6;
                    this.flyoutIndex = index;
                    this.flyoutMenu = menus[index];
                },

                keepFlyout() {
                    clearTimeout(this.flyoutHideTimeout);
                },

                scheduleHideFlyout() {
                    clearTimeout(this.flyoutHideTimeout);
                    this.flyoutHideTimeout = setTimeout(() => this.hideFlyout(), 280);
                },

                hideFlyout() {
                    clearTimeout(this.flyoutHideTimeout);
                    this.flyoutIndex = null;
                    this.flyoutMenu = null;
                },

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    this.hideFlyout();
                    // Save state to localStorage (desktop only)
                    if (window.innerWidth >= 1280) {
                        localStorage.setItem('sidebarExpanded', this.isExpanded);
                    }
                    // When toggling desktop sidebar, ensure mobile menu is closed
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                    this.hideFlyout();
                    // Don't modify isExpanded when toggling mobile menu
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                    if (val) this.hideFlyout();
                },

                setHovered(val) {
                    // Only allow hover effects on desktop when sidebar is collapsed
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>

    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                // Cukup html-nya aja yang dikasih class dark
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        // Function to initialize sidebar state
        function initSidebarState() {
            // Load saved sidebar state on page load
            if (window.innerWidth >= 1280) {
                const savedState = localStorage.getItem('sidebarExpanded');
                Alpine.store('sidebar').isExpanded = savedState === 'true';
            } else {
                Alpine.store('sidebar').isExpanded = false;
            }

            const checkMobile = () => {
                if (window.innerWidth < 1280) {
                    Alpine.store('sidebar').setMobileOpen(false);
                    Alpine.store('sidebar').isExpanded = false;
                } else {
                    Alpine.store('sidebar').isMobileOpen = false;
                    // Load saved state when resizing to desktop
                    const savedState = localStorage.getItem('sidebarExpanded');
                    Alpine.store('sidebar').isExpanded = savedState === 'true';
                }
            };
            window.addEventListener('resize', checkMobile);
        }
    </script>

</head>

<body x-data="{ 'loaded': true }" x-init="initSidebarState()">

    {{-- preloader --}}
    {{-- ================= SAFE WRAPPER PRELOADER BAWAAN ================= --}}
    <div id="preloader-wrapper" class="relative z-[99999] transition-opacity duration-500">
        {{-- Ini manggil komponen asli lu --}}
        <x-common.preloader />
    </div>

    <script>
        // Fungsi buat narik tirai preloader
        const hilangkanPreloader = () => {
            const wrapper = document.getElementById('preloader-wrapper');
            if (wrapper) {
                wrapper.style.opacity = '0'; // Bikin memudar
                setTimeout(() => wrapper.remove(), 500); // Hapus dari HTML setelah memudar
            }
        };

        // SKENARIO 1: Server lancar jaya
        // Begitu web selesai dimuat, tahan 0.8 detik (biar keren), lalu hilangkan
        window.addEventListener('load', () => {
            setTimeout(hilangkanPreloader, 800); 
        });

        // SKENARIO 2: Jurus Anti-Nyangkut (Infinite Load)
        // Kalau Alpine.js error atau sinyal macet, PAKSA hapus dalam waktu 3 detik!
        setTimeout(hilangkanPreloader, 3000);
    </script>
    {{-- ================= END SAFE WRAPPER ================= --}}
    {{-- preloader end --}}

    <div class="min-h-screen xl:flex">
        @include('layouts.backdrop')
        @include('layouts.sidebar')

        <div class="flex-1 transition-all duration-300 ease-in-out w-full min-w-0">
            <!-- app header start -->
            @include('layouts.app-header')
            <!-- app header end -->
            <main class="w-full p-4 mx-auto md:p-6" style="max-width: var(--breakpoint-2xl)">
                @yield('content')
            </main>
        </div>
    </div>

    <div id="modal-root"></div>
    @include('components.common.confirm-modal')
    <div id="sidebar-flyout-portal" aria-hidden="true"></div>
</body>

@stack('scripts')

</html>