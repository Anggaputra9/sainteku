<header class="topbar sticky top-0 z-50 border-b border-gray-200 bg-white py-4 dark:border-gray-800 dark:bg-black">
    <div class="mx-auto flex max-w-screen-2xl items-center justify-between px-4">
        <div class="flex items-center gap-4">
            <button @click.stop="sidebarToggle = !sidebarToggle"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 hover:text-gray-700 dark:border-gray-800 dark:bg-transparent dark:text-gray-400 dark:hover:text-white">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M2.5 5C2.5 4.58579 2.83579 4.25 3.25 4.25H16.75C17.1642 4.25 17.5 4.58579 17.5 5C17.5 5.41421 17.1642 5.75 16.75 5.75H3.25C2.83579 5.75 2.5 5.41421 2.5 5ZM2.5 10C2.5 9.58579 2.83579 9.25 3.25 9.25H16.75C17.1642 9.25 17.5 9.58579 17.5 10C17.5 10.4142 17.1642 10.75 16.75 10.75H3.25C2.83579 10.75 2.5 10.4142 2.5 10ZM3.25 14.25C2.83579 14.25 2.5 14.5858 2.5 15C2.5 15.4142 2.83579 15.75 3.25 15.75H16.75C17.1642 15.75 17.5 15.4142 17.5 15C17.5 14.5858 17.1642 14.25 16.75 14.25H3.25Z"
                        fill="" />
                </svg>
            </button>

            <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Master Data
            </h2>
        </div>

        <div class="flex items-center gap-4">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2">
                    <img src="{{ asset('tailadmin/images/avatar/avatar-1.jpg') }}" alt="avatar"
                        class="h-8 w-8 rounded-full object-cover" />
                    <span class="hidden sm:inline">{{ auth()->user()->name ?? 'Guest' }}</span>
                </button>

                <div x-show="open" @click.outside="open = false" x-cloak
                    class="absolute right-0 mt-2 w-48 rounded-md border bg-white shadow-lg dark:bg-gray-800">
                    <a href="#" class="block px-4 py-2">Profile</a>
                    <a href="#" class="block px-4 py-2">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
