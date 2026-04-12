<aside id="sidebar"
    class="fixed flex flex-col top-0 left-0 px-4 bg-white dark:bg-gray-900 dark:border-gray-800 h-screen border-r border-gray-100 transition-all duration-300 ease-in-out z-50 shadow-[4px_0_24px_rgba(0,0,0,0.01)]"
    x-data="{
        open: null,
        toggle(i) {
            this.open = this.open === i ? null : i
        }
    }" :class="{
        'w-[280px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[84px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }" @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">

    
    <div class="pt-20 md:pt-8 pb-6 flex justify-center w-full border-b border-gray-100/50 dark:border-gray-800/50 mb-4">
        <a href="/" class="flex justify-center group">
            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="dark:hidden rounded-full aspect-square object-cover transition-transform duration-300 group-hover:scale-105"
                src="/images/logo/logo.svg" width="65">
            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="hidden dark:block rounded-full aspect-square object-cover transition-transform duration-300 group-hover:scale-105"
                src="/images/logo/logo.svg" width="65">
            <img x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen"
                class="rounded-full aspect-square object-cover transition-transform duration-300 group-hover:scale-110"
                src="/images/logo/logo.svg" width="40">
        </a>
    </div>

    
    <div class="flex flex-col overflow-y-auto custom-scrollbar flex-1 pb-8">
        <nav>
            <div class="flex flex-col gap-2">
                <div>
                    <h2 x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                        class="mb-3 px-3 text-[10px] font-extrabold tracking-[0.2em] text-gray-400 dark:text-gray-500 uppercase">
                        Menu Utama
                    </h2>

                    <ul class="flex flex-col gap-1.5">
                        <?php
                        // Ambil data user dan role
                        $user = Auth::user();
                        $userType = $user->user_type;
                        $userRoles = \DB::table('trx_user_role')
                        ->join('mst_role', 'trx_user_role.role_id', '=', 'mst_role.id')
                        ->where('trx_user_role.user_id', $user->id)
                        ->pluck('mst_role.role_code')
                        ->toArray();

                        $isAdminSuper = in_array('ADM', $userRoles);
                        $isAdminUnit = in_array('OPS', $userRoles);
                        $isDosen = ($userType == 'DSN' || in_array('DSN', $userRoles));
                        $isMahasiswa = ($userType == 'MHS' || in_array('MHS', $userRoles));
                        ?>

                        <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $hasChildren = $menu->children->count() > 0;
                        $isActive = false;
                        $visibleChildren = collect();

                        // Filter children berdasarkan role untuk menu Manajemen Prestasi (id = 50)
                        if ($hasChildren && $menu->id == 50) {
                        foreach ($menu->children as $child) {
                        $show = false;
                        if ($child->id == 41 && ($isMahasiswa || $isAdminSuper)) $show = true; // Prestasi Mahasiswa
                        if ($child->id == 42 && ($isDosen || $isAdminSuper)) $show = true; // Repository Dosen
                        if ($child->id == 43) $show = true; // Portofolio User (semua bisa)
                        if ($child->id == 44 && ($isAdminUnit || $isAdminSuper)) $show = true; // Approval Mahasiswa
                        if ($child->id == 45 && ($isAdminUnit || $isAdminSuper)) $show = true; // Approval Dosen
                        if ($show) $visibleChildren->push($child);
                        }
                        } elseif ($hasChildren) {
                        $visibleChildren = $menu->children;
                        }

                        // Cek active state untuk parent menu
                        if ($hasChildren) {
                        foreach ($visibleChildren as $child) {
                        if ($child->menu_link && (Route::is($child->menu_link) || Route::is($child->menu_link . '*'))) {
                        $isActive = true;
                        break;
                        }
                        }
                        } else {
                        if ($menu->menu_link && (Route::is($menu->menu_link) || Route::is($menu->menu_link . '*'))) {
                        $isActive = true;
                        }
                        }
                        ?>

                        
                        <?php if(($hasChildren && $visibleChildren->count() > 0) || (!$hasChildren)): ?>
                        <li x-init="<?php echo e($isActive && $hasChildren ? "if (open === null) open = $i;" : ""); ?>">

                            
                            <?php if($hasChildren && $visibleChildren->count() > 0): ?>
                            <button @click="toggle(<?php echo e($i); ?>)"
                                class="group w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 active:scale-[0.98] outline-none"
                                :class="(open === <?php echo e($i); ?> || <?php echo e($isActive ? 'true' : 'false'); ?>) 
                                                            ? 'bg-indigo-50/80 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400 font-semibold' 
                                                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200 font-medium'">

                                <span class="flex items-center justify-center w-6 h-6 transition-transform duration-300"
                                    :class="(open === <?php echo e($i); ?> || <?php echo e($isActive ? 'true' : 'false'); ?>) ? 'scale-110' : 'group-hover:scale-110'">
                                    <i class="<?php echo e($menu->menu_icon); ?> text-lg"></i>
                                </span>

                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                    class="text-sm truncate">
                                    <?php echo e($menu->menu_name); ?>

                                </span>

                                <svg x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                    class="ml-auto w-4 h-4 transition-transform duration-300 ease-in-out"
                                    :class="{ 'rotate-180 text-indigo-500': open === <?php echo e($i); ?>, 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300': open !== <?php echo e($i); ?> }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            
                            <div x-show="open === <?php echo e($i); ?> && ($store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen)"
                                x-transition:enter="transition-all ease-out duration-300"
                                x-transition:enter-start="opacity-0 max-h-0 translate-y-[-10px]"
                                x-transition:enter-end="opacity-100 max-h-[500px] translate-y-0"
                                x-transition:leave="transition-all ease-in duration-200"
                                x-transition:leave-start="opacity-100 max-h-[500px] translate-y-0"
                                x-transition:leave-end="opacity-0 max-h-0 translate-y-[-10px]" class="overflow-hidden"
                                style="display: none;">

                                <ul class="mt-1 mb-2 ml-[1.35rem] pl-3 border-l-[1.5px] border-gray-100 dark:border-gray-800 space-y-1">
                                    <?php $__currentLoopData = $visibleChildren; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $childActive = $child->menu_link && (Route::is($child->menu_link) || Route::is($child->menu_link . '*'));
                                    $childLink = $child->menu_link && $child->menu_link !== '#' ? route($child->menu_link) : '#';
                                    ?>
                                    <li>
                                        <a href="<?php echo e($childLink); ?>"
                                            class="block px-3 py-2 text-[13px] rounded-lg transition-all duration-200 active:scale-[0.98] <?php echo e($childActive ? 'text-indigo-600 bg-indigo-50/50 font-semibold dark:text-indigo-400 dark:bg-indigo-500/10' : 'text-gray-500 font-medium hover:text-gray-900 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800/40'); ?>">
                                            <?php echo e($child->menu_name); ?>

                                        </a>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>

                            
                            <?php elseif(!$hasChildren): ?>
                            <?php
                            $menuLink = $menu->menu_link && $menu->menu_link !== '#' ? route($menu->menu_link) : '#';
                            ?>
                            <a href="<?php echo e($menuLink); ?>"
                                class="group w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 active:scale-[0.98] outline-none <?php echo e($isActive ? 'bg-indigo-50/80 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200 font-medium'); ?>">

                                <span class="flex items-center justify-center w-6 h-6 transition-transform duration-300 <?php echo e($isActive ? 'scale-110' : 'group-hover:scale-110'); ?>">
                                    <i class="<?php echo e($menu->menu_icon); ?> text-lg"></i>
                                </span>

                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                    class="text-sm truncate">
                                    <?php echo e($menu->menu_name); ?>

                                </span>
                            </a>
                            <?php endif; ?>

                        </li>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</aside><?php /**PATH D:\Unduhan\sainteku\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>