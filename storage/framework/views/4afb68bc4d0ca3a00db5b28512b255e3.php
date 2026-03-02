<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sainteku - Master Data Dashboard</title>

    
    <?php if(file_exists(public_path('tailadmin/style.css'))): ?>
        <link rel="stylesheet" href="<?php echo e(asset('tailadmin/style.css')); ?>">
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset('tailadmin-free-tailwind-dashboard-template-main/src/css/style.css')); ?>">
    <?php endif; ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        ::selection {
            background-color: #feeb04;
            color: #000;
        }

        /* Pastikan tidak ada overflow liar */
        body {
            overflow: hidden;
        }
    </style>
</head>

<body x-data="{ 
        sidebarToggle: true, 
        darkMode: localStorage.getItem('darkMode') === 'true',
        selected: 'Master Data'
    }" x-init="$watch('darkMode', value => localStorage.setItem('darkMode', value))"
    :class="{ 'dark bg-[#050505]': darkMode, 'bg-gray-50': !darkMode }" class="font-sans text-body">

    <div class="flex h-screen overflow-hidden">
        
        <?php echo $__env->make('masterdata::components.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden transition-all duration-300 ease-in-out"
            
            :class="sidebarToggle ? 'lg:ml-[290px]' : 'lg:ml-[90px]'">

            
            <?php echo $__env->make('masterdata::components.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <main class="min-h-screen">
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    <?php echo e($slot); ?>

                </div>
            </main>
        </div>
    </div>

    <?php if(file_exists(public_path('tailadmin/bundle.js'))): ?>
        <script src="<?php echo e(asset('tailadmin/bundle.js')); ?>"></script>
    <?php endif; ?>
</body>

</html><?php /**PATH C:\sainteku\Modules/MasterData\resources/views/components/layouts/master.blade.php ENDPATH**/ ?>