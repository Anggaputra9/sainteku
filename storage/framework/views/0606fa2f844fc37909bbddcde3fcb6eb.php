<header
    class="topbar sticky top-0 z-50 border-b border-gray-200 bg-white/80 backdrop-blur-md py-3 dark:border-gray-800 dark:bg-black/80">
    <div class="mx-auto flex max-w-screen-2xl items-center justify-between px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-5">
            <button @click.stop="sidebarToggle = !sidebarToggle"
                class="group flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white shadow-sm transition-all hover:bg-gray-50 active:scale-95 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800">
                <svg class="h-5 w-5 text-gray-600 transition-colors group-hover:text-indigo-600 dark:text-gray-400 dark:group-hover:text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="hidden sm:block">
                <p class="text-lg font-bold text-gray-800 dark:text-white">Master Data</p>
            </div>
        </div>

        <div class="flex items-center gap-3">

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center gap-3 rounded-full p-1 pr-4 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800">
                    <div class="relative">
                        <img src="<?php echo e(asset('tailadmin/images/avatar/avatar-1.jpg')); ?>" alt="avatar"
                            class="h-9 w-9 rounded-full border-2 border-indigo-500/20 object-cover shadow-sm" />
                        <span
                            class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-green-500 dark:border-black"></span>
                    </div>

                    <div class="hidden text-left sm:block">
                        <p class="text-sm font-semibold leading-none text-gray-800 dark:text-white/90">
                            <?php echo e(auth()->user()->name ?? 'Guest User'); ?>

                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <?php echo e(auth()->user()->role_name ?? 'Administrator'); ?>

                        </p>
                    </div>

                    <svg class="h-4 w-4 text-gray-400 transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95" @click.outside="open = false" x-cloak
                    class="absolute right-0 mt-3 w-56 origin-top-right rounded-2xl border border-gray-100 bg-white p-2 shadow-xl dark:border-gray-700 dark:bg-gray-800">

                    <a href="#"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-gray-600 transition-colors hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-gray-600 transition-colors hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </a>

                    <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>

                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-red-500 transition-colors hover:bg-red-50 dark:hover:bg-red-900/20">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header><?php /**PATH C:\sainteku\Modules/MasterData\resources/views/components/partials/header.blade.php ENDPATH**/ ?>