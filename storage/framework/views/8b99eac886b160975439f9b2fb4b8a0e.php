<div>
    <form method="post" action="<?php echo e(route('profile.destroy')); ?>" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('delete'); ?>

        <div class="bg-red-50 dark:bg-red-900/10 p-4 rounded-lg border border-red-200 dark:border-red-800/30">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-red-800 dark:text-red-400">Warning</h4>
                    <p class="text-xs text-red-700 dark:text-red-300 mt-1">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                        Please enter your password to confirm you would like to permanently delete your account.
                    </p>
                </div>
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Password
            </label>
            <input id="password"
                name="password"
                type="password"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                placeholder="Enter your password" />
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-red-700 transition"
                onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                <i class="fas fa-trash-alt"></i>
                Delete Account
            </button>
        </div>
    </form>
</div><?php /**PATH D:\Unduhan\sainteku\resources\views/profile/partials/delete-user-form.blade.php ENDPATH**/ ?>