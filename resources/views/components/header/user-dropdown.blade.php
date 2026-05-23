<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" @click.outside="open = false"
        class="flex items-center gap-3 rounded-full p-1 pr-4 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none">

        {{-- Avatar & Status Indikator --}}
        <div class="relative">
            {{-- Avatar dengan foto dari database --}}
            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold overflow-hidden border-2 border-indigo-500/20">
                @if(Auth::user()->avatar && file_exists(public_path('storage/avatars/' . Auth::user()->avatar)))
                <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" class="h-full w-full object-cover">
                @else
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
            </div>
            <span
                class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-green-500 dark:border-black"></span>
        </div>

        {{-- Nama (Cuma 1 Kata) & Role --}}
        <div class="hidden text-left sm:block">
            <p class="text-sm font-semibold leading-none text-gray-800 dark:text-white/90">
                {{-- Ambil kata pertama saja menggunakan explode --}}
                {{ explode(' ', trim(auth()->user()->name ?? 'Guest'))[0] }}
            </p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 capitalize">
                {{ auth()->user()->roles->first()?->role_name ?? 'Belum Ada Akses' }}
            </p>
        </div>

        {{-- Ikon Chevron --}}
        <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Dropdown Menu --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" x-cloak
        class="fixed left-4 right-4 top-[76px] z-[999990] origin-top-right rounded-2xl border border-gray-100 bg-white p-2 shadow-xl dark:border-gray-700 dark:bg-gray-800 sm:absolute sm:left-auto sm:right-0 sm:top-auto sm:mt-3 sm:w-64">

        {{-- Header Dropdown (Tampil Nama Lengkap & Email) --}}
        <div class="px-4 py-3 mb-1 border-b border-gray-100 dark:border-gray-700">
            <p class="text-sm font-bold text-gray-900 dark:text-white">
                {{ auth()->user()->name ?? 'Guest User' }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                {{ auth()->user()->email ?? 'No Email' }}
            </p>
        </div>

        {{-- Menu Profil --}}
        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-gray-600 transition-colors hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile
        </a>

        <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>

        {{-- Form Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-red-500 transition-colors hover:bg-red-50 dark:hover:bg-red-900/20">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>