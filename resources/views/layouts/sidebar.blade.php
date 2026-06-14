<style>
    /* Sembunyiin scrollbar buat Chrome, Safari, Edge, Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Sembunyiin scrollbar buat Firefox & IE */
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Jurus Sakti Anti Nge-Glitch / Kedap-kedip pas reload */
    [x-cloak] {
        display: none !important;
    }
</style>

{{-- ================= OVERLAY GELAP (Backdrop) ala Anyeoung Gift ================= --}}
<template x-teleport="body">
    <div x-show="$store.sidebar.isMobileOpen"
        class="fixed inset-0 z-[999900] bg-black/60 backdrop-blur-sm xl:hidden transition-opacity"
        @click="$store.sidebar.isMobileOpen = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        x-cloak></div>
</template>

{{-- ================= SIDEBAR UTAMA ================= --}}
<aside id="sidebar" x-cloak
    class="fixed xl:sticky flex flex-col top-0 left-0 bg-white/95 backdrop-blur-xl supports-[backdrop-filter]:bg-white/80 dark:bg-gray-900/95 dark:supports-[backdrop-filter]:bg-gray-900/80 dark:border-gray-800 h-screen border-r border-gray-100 transition-all duration-300 ease-in-out z-[999999] shadow-[4px_0_24px_rgba(0,0,0,0.05)] w-[84px] -translate-x-full xl:translate-x-0"
    x-data="{
        open: null,
        toggle(i) {
            this.open = this.open === i ? null : i
        }
    }" :class="{
        'w-[280px] px-4': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen,
        'w-[84px] px-4': !$store.sidebar.isExpanded,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }">

    {{-- ================= LOGO & TEKS KAMPUS ================= --}}
    <div
        class="pt-8 pb-6 flex flex-col items-center justify-center w-full border-b border-gray-100/50 dark:border-gray-800/50 mb-4 relative overflow-hidden">

        <a href="/" class="flex flex-col items-center justify-center group shrink-0 outline-none w-full">

            {{-- Gambar Logo --}}
            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isMobileOpen"
                class="dark:hidden rounded-full aspect-square object-cover transition-transform duration-300 group-hover:scale-105 shadow-sm shrink-0"
                src="/images/logo/logo.svg" width="65">

            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isMobileOpen"
                class="hidden dark:block rounded-full aspect-square object-cover transition-transform duration-300 group-hover:scale-105 shadow-sm shrink-0"
                src="/images/logo/logo.svg" width="65">

            <img x-show="!$store.sidebar.isExpanded && !$store.sidebar.isMobileOpen"
                class="rounded-full aspect-square object-cover transition-transform duration-300 group-hover:scale-110 shadow-sm shrink-0"
                src="/images/logo/logo.svg" width="40">

            {{-- Teks Full: UIN PROF... --}}
            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isMobileOpen" x-transition.opacity.duration.300ms
                class="mt-3 px-2 text-center text-[10px] font-extrabold text-gray-700 dark:text-gray-300 uppercase tracking-[0.1em] leading-tight shrink-0 whitespace-nowrap">
                UIN PROF K.H.<br>SAIFUDDIN ZUHRI
            </span>
        </a>
    </div>

    {{-- ================= NAVIGATION ================= --}}
    <div class="flex flex-col overflow-y-auto no-scrollbar flex-1 pb-8">
        <nav>
            <div class="flex flex-col gap-2">

                <div>
                    {{-- Judul Seksi Kalcer --}}
                    <h2 x-show="$store.sidebar.isExpanded || $store.sidebar.isMobileOpen"
                        class="mb-3 px-3 text-[10px] font-extrabold tracking-[0.2em] text-gray-400 dark:text-gray-500 uppercase whitespace-nowrap truncate">
                        Menu Utama
                    </h2>

                    <ul class="flex flex-col gap-1.5">

                        @foreach(($sidebarMenus ?? collect()) as $i => $menu)
                            @php
                                $hasChildren = $menu->children->count() > 0;
                                $isActive = false;

                                if ($hasChildren) {
                                    foreach ($menu->children as $child) {
                                        if ($child->menu_link && (Route::is($child->menu_link) || Route::is($child->menu_link . '*'))) {
                                            $isActive = true;
                                            break;
                                        }
                                    }

                                    // Special case: Activate Monev Akademik when accessing ujian/rooms
                                    if (!$isActive && request()->is('ujian/rooms*')) {
                                        // Check if this is Monev Akademik menu by checking children
                                        foreach ($menu->children as $child) {
                                            if ($child->menu_link && str_contains($child->menu_link, 'monevakademik')) {
                                                $isActive = true;
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    if ($menu->menu_link && (Route::is($menu->menu_link) || Route::is($menu->menu_link . '*'))) {
                                        $isActive = true;
                                    }
                                }
                            @endphp

                            <li x-init="{{ $isActive && $hasChildren ? "if (open === null) open = $i;" : "" }}">

                                {{-- ================= PARENT WITH CHILD ================= --}}
                                @if($hasChildren)
                                    <button @click="toggle({{ $i }})"
                                        class="group w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 active:scale-[0.98] outline-none overflow-hidden"
                                        :class="(open === {{ $i }} || {{ $isActive ? 'true' : 'false' }}) 
                                                            ? 'bg-indigo-50/80 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400 font-semibold' 
                                                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200 font-medium'">

                                        <span
                                            class="flex shrink-0 items-center justify-center w-6 h-6 transition-transform duration-300"
                                            :class="(open === {{ $i }} || {{ $isActive ? 'true' : 'false' }}) ? 'scale-110' : 'group-hover:scale-110'">
                                            <i class="{{ $menu->menu_icon }} text-lg"></i>
                                        </span>

                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isMobileOpen"
                                            class="text-sm truncate whitespace-nowrap">
                                            {{ $menu->menu_name }}
                                        </span>

                                        <svg x-show="$store.sidebar.isExpanded || $store.sidebar.isMobileOpen"
                                            class="ml-auto shrink-0 w-4 h-4 transition-transform duration-300 ease-in-out"
                                            :class="{ 'rotate-180 text-indigo-500': open === {{ $i }}, 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300': open !== {{ $i }} }"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    {{-- SUBMENU --}}
                                    <div x-show="open === {{ $i }} && ($store.sidebar.isExpanded || $store.sidebar.isMobileOpen)"
                                        x-transition:enter="transition-all ease-out duration-300"
                                        x-transition:enter-start="opacity-0 max-h-0 translate-y-[-10px]"
                                        x-transition:enter-end="opacity-100 max-h-[500px] translate-y-0"
                                        x-transition:leave="transition-all ease-in duration-200"
                                        x-transition:leave-start="opacity-100 max-h-[500px] translate-y-0"
                                        x-transition:leave-end="opacity-0 max-h-0 translate-y-[-10px]" class="overflow-hidden"
                                        style="display: none;">

                                        <ul
                                            class="mt-1 mb-2 ml-[1.35rem] pl-3 border-l-[1.5px] border-gray-100 dark:border-gray-800 space-y-1 overflow-hidden">
                                            @foreach($menu->children as $child)
                                                @php
                                                    $childActive = $child->menu_link && (Route::is($child->menu_link) || Route::is($child->menu_link . '*'));

                                                    // Special case: Activate "Ujian" child menu when accessing ujian/rooms
                                                    if (!$childActive && request()->is('ujian/rooms*')) {
                                                        // Check if this child menu is "Ujian" by checking if it contains 'ujian' in route name or menu name
                                                        if (($child->menu_link && str_contains($child->menu_link, 'ujian')) ||
                                                            str_contains(strtolower($child->menu_name), 'ujian')) {
                                                            $childActive = true;
                                                        }
                                                    }
                                                @endphp
                                                <li>
                                                    <a href="{{ $child->menu_link && $child->menu_link !== '#' && Route::has($child->menu_link) ? route($child->menu_link) : '#' }}"
                                                        class="block px-3 py-2 text-[13px] rounded-lg transition-all duration-200 active:scale-[0.98] whitespace-nowrap truncate {{ $childActive ? 'text-indigo-600 bg-indigo-50/50 font-semibold dark:text-indigo-400 dark:bg-indigo-500/10' : 'text-gray-500 font-medium hover:text-gray-900 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800/40' }}">
                                                        {{ $child->menu_name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    {{-- ================= SIMPLE MENU ================= --}}
                                @else
                                    <a href="{{ $menu->menu_link && $menu->menu_link !== '#' && Route::has($menu->menu_link) ? route($menu->menu_link) : '#' }}"
                                        class="group w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 active:scale-[0.98] outline-none overflow-hidden {{ $isActive ? 'bg-indigo-50/80 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200 font-medium' }}">

                                        <span
                                            class="flex shrink-0 items-center justify-center w-6 h-6 transition-transform duration-300 {{ $isActive ? 'scale-110' : 'group-hover:scale-110' }}">
                                            <i class="{{ $menu->menu_icon }} text-lg"></i>
                                        </span>

                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isMobileOpen"
                                            class="text-sm truncate whitespace-nowrap">
                                            {{ $menu->menu_name }}
                                        </span>
                                    </a>
                                @endif

                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</aside>