<?php if(session('success')): ?>
    <div class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
        <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
        <p class="text-sm font-bold text-green-700 dark:text-green-400"><?php echo e(session('success')); ?></p>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
        <i class="fa-solid fa-circle-xmark text-red-500 text-xl mr-3"></i>
        <p class="text-sm font-bold text-red-700 dark:text-red-400"><?php echo e(session('error')); ?></p>
    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="flex items-start w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
        <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl mr-3 mt-0.5"></i>
        <div class="text-sm font-bold text-red-700 dark:text-red-400">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><?php echo e($error); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\sainteku\resources\views/settings/ai/partials/_alerts.blade.php ENDPATH**/ ?>