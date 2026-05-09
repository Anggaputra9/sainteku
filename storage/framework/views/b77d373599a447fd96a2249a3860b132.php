<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 1000px;">
        <div class="modal-content shadow-lg">
            <div class="row g-0">

                
                <div class="col-md-5 d-none d-md-flex flex-column align-items-center justify-content-center"
                    style="background: linear-gradient(145deg, #FEEB04 0%, #CBB800 100%); min-height: 550px; position: relative;">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-10"
                        style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22 viewBox=%220 0 100 100%22><path d=%22M0 0 L100 100 M100 0 L0 100%22 stroke=%22%23000000%22 stroke-width=%221%22 opacity=%220.2%22/></svg>'); background-size: 30px 30px;">
                    </div>
                    <div class="text-center position-relative z-1 px-4 py-3">
                        <img src="<?php echo e(asset('assets/images/uin.png')); ?>" alt="Logo UIN Saizu" class="img-fluid mb-3"
                            style="max-width: 95px; height: auto;">
                        <h2 class="fw-bold mb-2" style="color: #000000; font-size: 2rem;">Sainteku</h2>
                        <p class="mb-0 text-dark" style="font-size: 0.9rem;"><?php echo e(__('messages.faculty_name')); ?></p>
                        <p class="mb-3 text-dark" style="font-size: 0.9rem;">
                            <?php echo e(__('UIN Prof. K.H. Saifuddin Zuhri Purwokerto')); ?>

                        </p>
                        <div class="mt-4 pt-2">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-2 opacity-75">
                                <path d="M10 11H6V7H10V11ZM18 11H14V7H18V11Z" fill="#000000" />
                            </svg>
                            <p class="fst-italic text-dark"
                                style="font-size: 0.85rem; max-width: 260px; margin: 0 auto; line-height: 1.5; opacity: 0.9;">
                                "<?php echo e(__('messages.quote')); ?>"
                            </p>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-7 bg-white">
                    <div class="d-md-none p-4 text-center border-bottom" style="background: #F9FAFB;">
                        <h2 class="fw-bold mb-1" style="color: #000000; font-size: 1.8rem;">Sainteku</h2>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.faculty_name')); ?></p>
                        <p class="mb-0 text-muted small"><?php echo e(__('UIN Prof. K.H. Saifuddin Zuhri Purwokerto')); ?></p>
                    </div>

                    <div class="p-4" style="max-width: 450px; margin: 0 auto;">
                        <div class="mb-3">
                            <a href="#"
                                class="d-inline-flex align-items-center text-sm text-gray-500 text-decoration-none"
                                data-bs-dismiss="modal">
                                <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <?php echo e(__('messages.back_to_dashboard')); ?>

                            </a>
                        </div>

                        <div class="mb-4">
                            <h1 class="fw-semibold text-gray-800" style="font-size: 1.8rem; margin-bottom: 0.25rem;">
                                <?php echo e(__('messages.sign_in')); ?>

                            </h1>
                            <p class="text-sm text-gray-500"><?php echo e(__('messages.enter_credentials')); ?></p>
                        </div>

                        <form method="POST" action="/login" id="loginForm">
                            <?php echo csrf_field(); ?>
                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger py-2 mb-3 small"
                                    style="background: #FEF3F2; border-color: #F04438; color: #B42318;">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p class="mb-0"><?php echo e($error); ?></p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label d-block small fw-medium text-gray-700 mb-1">
                                    <?php echo e(__('messages.email_label')); ?><span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control w-100 px-3 py-2 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-3"
                                    id="credential" name="credential" placeholder="info@gmail.com"
                                    value="<?php echo e(old('credential')); ?>" style="height: 42px;" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block small fw-medium text-gray-700 mb-1">
                                    <?php echo e(__('messages.password_label')); ?><span class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <input type="password"
                                        class="form-control w-100 px-3 py-2 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-3"
                                        id="password" name="password"
                                        placeholder="<?php echo e(__('messages.password_placeholder')); ?>"
                                        style="height: 42px; padding-right: 42px;" required>

                                    <span id="togglePasswordBtn"
                                        class="position-absolute text-gray-500 cursor-pointer user-select-none"
                                        style="right: 12px; top: 50%; transform: translateY(-50%); z-index: 10;"
                                        title="Tampilkan Password">

                                        <svg id="icon-eye-closed" width="18" height="18" viewBox="0 0 20 20"
                                            fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z"
                                                fill="#98A2B3" />
                                        </svg>

                                        <svg id="icon-eye-open" width="18" height="18" viewBox="0 0 20 20" fill="none"
                                            style="display: none;">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z"
                                                fill="#98A2B3" />
                                        </svg>
                                    </span>
                                </div>
                                <small class="text-muted d-block mt-1"><?php echo e(__('messages.password_hint')); ?></small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                        style="width: 16px; height: 16px;">
                                    <label class="form-check-label small text-gray-700 ms-1"
                                        for="remember"><?php echo e(__('messages.remember_me')); ?></label>
                                </div>
                                <a href="#" class="small text-decoration-none" style="color: #000000;"
                                    data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                                    <?php echo e(__('messages.forgot_password')); ?>

                                </a>
                            </div>

                            <button type="submit" class="btn w-100 py-2 small fw-medium btn-masuk rounded-3">
                                <?php echo e(__('messages.login')); ?>

                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\sainteku\resources\views/partials/landing/login-modal.blade.php ENDPATH**/ ?>