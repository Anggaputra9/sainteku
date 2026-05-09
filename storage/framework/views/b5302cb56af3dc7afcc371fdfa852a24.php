<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('loginForm');
        const loginModal = document.getElementById('loginModal');

        if (loginForm) {
            loginForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = '<?php echo e(__("messages.login")); ?>';

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<?php echo e(__("messages.processing")); ?>...';
                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            setTimeout(() => {
                                window.location.href = data.redirect || '/dashboard';
                            }, 1500);
                        } else {
                            showAlert('error', data.message || '<?php echo e(__("messages.login_failed")); ?>');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('error', '<?php echo e(__("messages.error_occurred")); ?>');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });
        }

        // LOGIC TOGGLE PASSWORD (VANILLA JS)
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const iconEyeClosed = document.getElementById('icon-eye-closed');
        const iconEyeOpen = document.getElementById('icon-eye-open');

        if (togglePasswordBtn && passwordInput) {
            togglePasswordBtn.addEventListener('click', function () {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    iconEyeClosed.style.display = 'none';
                    iconEyeOpen.style.display = 'block';
                    this.title = 'Sembunyikan Password';
                } else {
                    passwordInput.type = 'password';
                    iconEyeClosed.style.display = 'block';
                    iconEyeOpen.style.display = 'none';
                    this.title = 'Tampilkan Password';
                }
            });
        }
        function showAlert(type, message) {
            const alert = document.getElementById('loginAlert');
            const alertIcon = document.getElementById('alertIcon');
            const alertMessage = document.getElementById('alertMessage');

            if (!alert || !alertIcon || !alertMessage) return;

            alertMessage.textContent = message;

            if (type === 'success') {
                alertIcon.className = 'ri-checkbox-circle-line me-2 fs-5 align-middle';
                alert.className = 'alert alert-success alert-dismissible fade show alert-popup';
            } else {
                alertIcon.className = 'ri-error-warning-line me-2 fs-5 align-middle';
                alert.className = 'alert alert-danger alert-dismissible fade show alert-popup';
            }

            alert.style.display = 'block';

            setTimeout(() => { hideAlert(); }, 5000);
        }

        function hideAlert() {
            const alert = document.getElementById('loginAlert');
            if (alert) {
                alert.style.animation = 'slideUp 0.3s ease-in';
                setTimeout(() => {
                    alert.style.display = 'none';
                    alert.style.animation = '';
                }, 300);
            }
        }
        window.hideAlert = hideAlert;

        <?php if($errors->any()): ?>
            if (typeof bootstrap !== 'undefined' && loginModal) {
                const modal = new bootstrap.Modal(loginModal);
                modal.show();
                showAlert('error', '<?php echo e($errors->first()); ?>');
            }
        <?php endif; ?>
    });

    // SCRIPTS FORGOT PASSWORD & MOBILE MENU
    document.addEventListener('DOMContentLoaded', function () {
        // ... (Kodingan forgot password dan Hamburger Menu persis kayak sebelumnya, ditaruh di sini) ...
        const forgotForm = document.getElementById('forgotPasswordForm');
        const forgotAlert = document.getElementById('forgotPasswordAlert');
        const forgotModal = document.getElementById('forgotPasswordModal');

        if (forgotForm) {
            forgotForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = '<?php echo e(__("messages.send_reset_link")); ?>';
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<?php echo e(__("messages.sending")); ?>...';
                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (!forgotAlert) return;
                        forgotAlert.style.display = 'block';
                        if (data.success) {
                            forgotAlert.className = 'alert alert-success py-2 mb-3 small';
                            forgotAlert.innerHTML = data.message;
                            forgotForm.reset();
                            setTimeout(() => {
                                const modal = bootstrap.Modal.getInstance(forgotModal);
                                if (modal) modal.hide();
                                forgotAlert.style.display = 'none';
                            }, 3000);
                        } else {
                            forgotAlert.className = 'alert alert-danger py-2 mb-3 small';
                            forgotAlert.innerHTML = data.message || '<?php echo e(__("messages.email_not_found")); ?>';
                        }
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (!forgotAlert) return;
                        forgotAlert.style.display = 'block';
                        forgotAlert.className = 'alert alert-danger py-2 mb-3 small';
                        forgotAlert.innerHTML = '<?php echo e(__("messages.error_occurred")); ?>';
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });
        }

        // Hamburger Menu Logic
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('mobileMenuOverlay');
        const closeMenuBtn = document.getElementById('closeMenuBtn');
        const body = document.body;

        function openMenu() {
            mobileMenu.classList.add('open');
            menuOverlay.classList.add('active');
            hamburgerBtn.classList.add('active');
            body.style.overflow = 'hidden';
        }

        function closeMenu() {
            mobileMenu.classList.remove('open');
            menuOverlay.classList.remove('active');
            hamburgerBtn.classList.remove('active');
            body.style.overflow = '';
        }

        if (hamburgerBtn) hamburgerBtn.addEventListener('click', openMenu);
        if (closeMenuBtn) closeMenuBtn.addEventListener('click', closeMenu);
        if (menuOverlay) menuOverlay.addEventListener('click', closeMenu);

        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
                closeMenu();
                const targetId = this.getAttribute('href');
                if (targetId && targetId !== '#') {
                    e.preventDefault();
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        const mobileLoginBtn = document.querySelector('.mobile-login-btn');
        if (mobileLoginBtn) {
            mobileLoginBtn.addEventListener('click', function () {
                closeMenu();
            });
        }
    });
</script><?php /**PATH C:\laragon\www\sainteku\resources\views/partials/landing/auth-scripts.blade.php ENDPATH**/ ?>