<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content shadow-lg p-2">
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <h5 class="modal-title fw-bold" id="forgotPasswordModalLabel"><?php echo e(__('messages.forgot_password_title')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="forgotPasswordAlert" class="alert" style="display: none;"></div>
                <p class="text-sm text-gray-600 mb-4"><?php echo e(__('messages.forgot_password_desc')); ?></p>

                <form method="POST" action="<?php echo e(route('password.email')); ?>" id="forgotPasswordForm">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label class="form-label d-block small fw-medium text-gray-700 mb-1">
                            <?php echo e(__('messages.email_label')); ?><span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control w-100 px-3 py-2 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-3"
                            name="email" id="forgot_email" placeholder="nama@email.com" style="height: 42px;" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn w-100 py-2 small border rounded-3" style="background: #F3F4F6; color: #000;" data-bs-dismiss="modal">
                            <?php echo e(__('messages.cancel')); ?>

                        </button>
                        <button type="submit" class="btn w-100 py-2 small fw-medium border-0 btn-primary rounded-3">
                            <?php echo e(__('messages.send_reset_link')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\sainteku\resources\views/partials/landing/forgot-password-modal.blade.php ENDPATH**/ ?>