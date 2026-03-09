<aside id="sidebar"
    class="fixed flex flex-col top-0 left-0 px-5 bg-white dark:bg-gray-900 dark:border-gray-800 h-screen border-r border-gray-200 transition-all duration-300 ease-in-out z-50"
    x-data="{
        open: null,
        toggle(i) {
            this.open = this.open === i ? null : i
        }
    }" :class="{
        'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }" @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">

    
    <div class="pt-8 pb-7 flex justify-center w-full">
        <a href="/" class="flex justify-center">
            
            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="dark:hidden rounded-full aspect-square object-cover shadow-md transition-all duration-300"
                src="/images/logo/logo.jpg" width="80">

            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="hidden dark:block rounded-full aspect-square object-cover shadow-md transition-all duration-300"
                src="/images/logo/logo.jpg" width="80">

            
            <img x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen"
                class="rounded-full aspect-square object-cover shadow-sm transition-all duration-300"
                src="/images/logo/logo.jpg" width="40">
        </a>
    </div>

    
    <div class="flex flex-col overflow-y-auto no-scrollbar">
        <nav class="mb-6">
            <div class="flex flex-col gap-4">

                <div>
                    <h2 class="mb-4 text-xs uppercase text-gray-400">
                        MENU
                    </h2>

                    <ul class="flex flex-col gap-1">

                        <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php
                                $hasChildren = $menu->children->count() > 0;
                                $isActive = false;

                                if ($hasChildren) {
                                    foreach ($menu->children as $child) {
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

                            
                            <li x-init="<?php echo e($isActive && $hasChildren ? "if (open === null) open = $i;" : ""); ?>">

                                
                                <?php if($hasChildren): ?>

                                    <button @click="toggle(<?php echo e($i); ?>)" class="menu-item group w-full"
                                        :class="(open === <?php echo e($i); ?> || <?php echo e($isActive ? 'true' : 'false'); ?>) ? 'menu-item-active' : 'menu-item-inactive'">

                                        
                                        <span class="menu-item-icon">
                                            <i class="<?php echo e($menu->menu_icon); ?> fa-xl"></i>
                                        </span>

                                        
                                        <span
                                            x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                            class="menu-item-text">
                                            <?php echo e($menu->menu_name); ?>

                                        </span>

                                        
                                        <svg x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                            class="ml-auto w-5 h-5 transition-transform duration-200"
                                            :class="{ 'rotate-180 text-brand-500': open === <?php echo e($i); ?> }" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    
                                    <div x-show="open === <?php echo e($i); ?> && ($store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen)"
                                        x-transition style="display: none;">

                                        <ul class="mt-2 space-y-1 ml-9">

                                            <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $childActive = $child->menu_link && (Route::is($child->menu_link) || Route::is($child->menu_link . '*'));
                                                ?>

                                                <li>
                                                    <a href="<?php echo e($child->menu_link && $child->menu_link !== '#' ? route($child->menu_link) : '#'); ?>"
                                                        class="menu-dropdown-item <?php echo e($childActive ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'); ?>">
                                                        <?php echo e($child->menu_name); ?>

                                                    </a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </ul>
                                    </div>

                                    
                                <?php else: ?>

                                    <a href="<?php echo e($menu->menu_link && $menu->menu_link !== '#' ? route($menu->menu_link) : '#'); ?>"
                                        class="menu-item group <?php echo e($isActive ? 'menu-item-active' : 'menu-item-inactive'); ?>">

                                        <span class="menu-item-icon">
                                            <i class="<?php echo e($menu->menu_icon); ?> fa-xl"></i>
                                        </span>

                                        <span
                                            x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                            class="menu-item-text">
                                            <?php echo e($menu->menu_name); ?>

                                        </span>
                                    </a>

                                <?php endif; ?>

                            </li>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </ul>
                </div>

            </div>
        </nav>
    </div>
</aside><?php /**PATH E:\kuliah\semester6\laravel\sainteku\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>