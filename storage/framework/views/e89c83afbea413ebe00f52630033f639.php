<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Profile Settings</h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Dashboard /</li>
                        <li class="text-blue-600 dark:text-blue-400">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>

        
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Profile Information
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Update your account's profile information.
                </p>
            </div>
            <div class="p-6">
                <?php echo $__env->make('profile.partials.update-profile-information-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

        
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-lock mr-2 text-blue-500"></i>
                    Update Password
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Ensure your account is using a long, random password to stay secure.
                </p>
            </div>
            <div class="p-6">
                <?php echo $__env->make('profile.partials.update-password-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\semester6\laravel\sainteku\resources\views/profile/edit.blade.php ENDPATH**/ ?>