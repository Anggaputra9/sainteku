<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($title ?? 'DASHBOARD'); ?> | UIN Prof. K.H. Saifuddin Zuhri</title>

    
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/images/uin.svg')); ?>">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- Alpine.js -->
    

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

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    // Save state to localStorage (desktop only)
                    if (window.innerWidth >= 1280) {
                        localStorage.setItem('sidebarExpanded', this.isExpanded);
                    }
                    // When toggling desktop sidebar, ensure mobile menu is closed
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                    // Don't modify isExpanded when toggling mobile menu
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
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
    </script>

</head>

<body x-data="{ 'loaded': true }" x-init="// Load saved sidebar state on page load
if (window.innerWidth >= 1280) {
    const savedState = localStorage.getItem('sidebarExpanded');
    $store.sidebar.isExpanded = savedState === 'true';
} else {
    $store.sidebar.isExpanded = false;
}

const checkMobile = () => {
    if (window.innerWidth < 1280) {
        $store.sidebar.setMobileOpen(false);
        $store.sidebar.isExpanded = false;
    } else {
        $store.sidebar.isMobileOpen = false;
        // Load saved state when resizing to desktop
        const savedState = localStorage.getItem('sidebarExpanded');
        $store.sidebar.isExpanded = savedState === 'true';
    }
};
window.addEventListener('resize', checkMobile);">

    
    
    <div id="preloader-wrapper" class="relative z-[99999] transition-opacity duration-500">
        
        <?php if (isset($component)) { $__componentOriginal33757e58bef6aaec67779bf03774fc2d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33757e58bef6aaec67779bf03774fc2d = $attributes; } ?>
<?php $component = App\View\Components\Common\Preloader::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('common.preloader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Common\Preloader::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33757e58bef6aaec67779bf03774fc2d)): ?>
<?php $attributes = $__attributesOriginal33757e58bef6aaec67779bf03774fc2d; ?>
<?php unset($__attributesOriginal33757e58bef6aaec67779bf03774fc2d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33757e58bef6aaec67779bf03774fc2d)): ?>
<?php $component = $__componentOriginal33757e58bef6aaec67779bf03774fc2d; ?>
<?php unset($__componentOriginal33757e58bef6aaec67779bf03774fc2d); ?>
<?php endif; ?>
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
    
    

    <div class="min-h-screen xl:flex">
        <?php echo $__env->make('layouts.backdrop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 transition-all duration-300 ease-in-out w-full min-w-0">
            <!-- app header start -->
            <?php echo $__env->make('layouts.app-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <!-- app header end -->
            <main class="w-full p-4 mx-auto md:p-6" style="max-width: var(--breakpoint-2xl)">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
</body>

<?php echo $__env->yieldPushContent('scripts'); ?>

</html><?php /**PATH C:\laragon\www\sainteku\resources\views/layouts/app.blade.php ENDPATH**/ ?>