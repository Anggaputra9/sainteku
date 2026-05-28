
<div class="relative" x-data="{
    dropdownOpen: false,
    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
    },
    closeDropdown() {
        this.dropdownOpen = false;
    },
    handleViewAllClick() {
        console.log('View All Notifications clicked');
        this.closeDropdown();
    }
}" @click.away="closeDropdown()">

    <?php
        // 1. Tarik data notifikasi asli dari Database
        $notifications = Auth::check() ? Auth::user()->unreadNotifications()->take(5)->get() : collect();
        $unreadCount = Auth::check() ? Auth::user()->unreadNotifications()->count() : 0;
    ?>

    <button
        class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-dark-900 h-11 w-11 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
        @click="toggleDropdown()" type="button">
        <span x-show="<?php echo e($unreadCount > 0 ? 'true' : 'false'); ?>"
            class="absolute right-0 top-0.5 z-1 h-2 w-2 rounded-full bg-orange-400" style="display: none;">
            <span
                class="absolute inline-flex w-full h-full bg-orange-400 rounded-full opacity-75 -z-1 animate-ping"></span>
        </span>

        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z"
                fill="" />
        </svg>
    </button>

    <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="fixed left-4 right-4 top-[76px] z-[999990] flex h-auto max-h-[calc(100vh-96px)] w-auto max-w-[calc(100vw-2rem)] flex-col rounded-2xl border border-gray-200 bg-white/70 backdrop-blur-xl supports-[backdrop-filter]:bg-white/60 p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900/70 dark:supports-[backdrop-filter]:bg-gray-900/60 sm:absolute sm:left-auto sm:right-0 sm:top-auto sm:mt-[17px] sm:max-h-[480px] sm:w-[361px] sm:max-w-none"
        style="display: none;">
        <div class="flex items-center justify-between pb-3 mb-2 border-b border-gray-100/50 dark:border-gray-800/50">
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white/90">Notification</h5>

            <div class="flex items-center gap-3">
                <?php if($unreadCount > 0): ?>
                    <span class="px-2 py-0.5 text-[10px] font-bold text-white bg-orange-500 rounded-full"><?php echo e($unreadCount); ?>

                        Baru</span>
                <?php endif; ?>
                <button @click="closeDropdown()" class="text-gray-500 dark:text-gray-400" type="button">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z"
                            fill="" />
                    </svg>
                </button>
            </div>
        </div>

        <ul class="flex flex-col overflow-y-auto custom-scrollbar flex-1">
            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li>
                    
                    <button type="button" @click="
                                closeDropdown(); 
                                if('<?php echo e($notification->data['click_action'] ?? ''); ?>' === 'open_tashih_modal' && '<?php echo e($notification->data['reference_id'] ?? ''); ?>') {
                                    if(window.location.pathname.includes('/tashih')) {
                                        document.querySelector('[x-data=tashihApp]').__x.$data.viewDetail('<?php echo e($notification->data['reference_id']); ?>', 'review');
                                    } else {
                                        window.location.href = '<?php echo e($notification->data['target_url'] ?? '#'); ?>?open_modal=<?php echo e($notification->data['reference_id']); ?>';
                                    }
                                } else {
                                    window.location.href = '<?php echo e($notification->data['target_url'] ?? '#'); ?>';
                                }
                            "
                        class="w-full text-left flex gap-3 rounded-lg border-b border-gray-100/50 p-3 px-4.5 py-3 hover:bg-gray-100/50 dark:border-gray-800/50 dark:hover:bg-white/5 transition">

                        
                        <span
                            class="relative flex items-center justify-center shrink-0 w-10 h-10 rounded-full z-1 bg-blue-50/80 text-blue-600 font-bold border border-blue-200/50 dark:bg-blue-900/30 dark:border-blue-800/50 dark:text-blue-400">
                            <?php echo e($notification->data['userInitial'] ?? 'S'); ?>


                            
                            <span
                                class="absolute bottom-0 right-0 z-10 h-2.5 w-full max-w-2.5 rounded-full border-[1.5px] border-white dark:border-gray-900 <?php echo e(($notification->data['status'] ?? 'online') === 'online' ? 'bg-success-500' : 'bg-error-500'); ?>"></span>
                        </span>

                        <span class="block flex-1">
                            <span class="mb-1.5 block text-theme-sm text-gray-500 dark:text-gray-400">
                                <span class="font-medium text-gray-800 dark:text-white/90">
                                    <?php echo e($notification->data['userName'] ?? 'Sistem'); ?>

                                </span>
                                <?php echo e($notification->data['action'] ?? ''); ?>

                                <span class="font-medium text-gray-800 dark:text-white/90">
                                    <?php echo e($notification->data['item_name'] ?? ''); ?>

                                </span>
                            </span>

                            <span class="flex items-center gap-2 text-gray-500 text-theme-xs dark:text-gray-400">
                                <span><?php echo e($notification->data['type'] ?? 'Info'); ?></span>
                                <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                
                                <span><?php echo e($notification->created_at->diffForHumans()); ?></span>
                            </span>
                        </span>
                    </button>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="py-8 text-center text-gray-500 text-sm flex flex-col items-center">
                    <div
                        class="h-10 w-10 rounded-full bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-center mb-2">
                        <i class="fas fa-bell-slash text-lg text-gray-400"></i>
                    </div>
                    <span>Belum ada notifikasi baru</span>
                </li>
            <?php endif; ?>
        </ul>

        <a href="#"
            class="mt-3 shrink-0 flex justify-center rounded-lg border border-gray-300/50 bg-white/50 p-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50/80 hover:text-gray-800 dark:border-gray-700/50 dark:bg-gray-800/50 dark:text-gray-400 dark:hover:bg-white/[0.05] dark:hover:text-gray-200"
            @click.prevent="handleViewAllClick()">
            Lihat Semua Notifikasi
        </a>
    </div>
</div><?php /**PATH E:\kuliah\semester6\laravel\sainteku\resources\views/components/header/notification-dropdown.blade.php ENDPATH**/ ?>