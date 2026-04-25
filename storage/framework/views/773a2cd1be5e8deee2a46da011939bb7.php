<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>SAINTEKU | UIN SAIZU PURWOKERTO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Fakultas Sains dan Teknologi UIN Saifuddin Zuhri Purwokerto" name="description" />
    <meta content="Themesbrand" name="author" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="shortcut icon" href="assets/images/uin.png">

    <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/layout.js"></script>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    <style>
        /* WARNA SAINTEKU */
        :root {
            --saintek-primary: #FEEB04;
            --saintek-primary-dark: #CBB800;
            --saintek-text: #856B2B;
            --saintek-soft: rgba(254, 235, 4, 0.15);
        }

        .bg-primary {
            background-color: var(--saintek-primary) !important;
        }

        .btn-primary {
            background-color: var(--saintek-primary) !important;
            border-color: var(--saintek-primary) !important;
            color: #000000 !important;
        }

        .btn-primary:hover {
            background-color: var(--saintek-primary-dark) !important;
            border-color: var(--saintek-primary-dark) !important;
            color: #000000 !important;
        }

        .btn-outline-primary {
            border-color: var(--saintek-primary) !important;
            color: var(--saintek-text) !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--saintek-primary) !important;
            color: #000000 !important;
        }

        .text-primary {
            color: var(--saintek-text) !important;
        }

        .bg-primary-subtle {
            background-color: rgba(254, 235, 4, 0.2) !important;
        }

        /* TIPOGRAFI */
        body {
            font-family: 'Inter', 'DM Sans', 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        /* NAVBAR */
        .navbar-landing {
            background-color: white !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.03);
            padding: 16px 0;
        }

        .navbar-brand {
            font-size: 32px !important;
            font-weight: 700 !important;
            color: #1e293b !important;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            color: #334155;
            margin: 0 8px;
        }

        .navbar-nav .nav-link.active {
            color: var(--saintek-text) !important;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 28px;
            box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 25px 40px -12px rgba(254, 235, 4, 0.15);
            transform: translateY(-6px);
        }

        /* SECTION */
        .section {
            padding: 100px 0;
        }

        /* HERO SECTION */
        .hero-section {
            background: linear-gradient(145deg, #ffffff 0%, #fffaf0 100%);
            padding: 160px 0 100px;
        }

        /* DROPDOWN */
        .dropdown-menu {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 12px;
        }

        .dropdown-item {
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: var(--saintek-soft);
            color: var(--saintek-text);
        }

        /* MODAL LOGIN - FIX BACKGROUND */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.2) !important;
        }

        .modal-backdrop.show {
            opacity: 0.5 !important;
        }

        /* BUTTON STYLES */
        .btn-soft-primary {
            background-color: var(--saintek-primary) !important;
            color: #000000 !important;
            border: none;
            transition: all 0.3s ease;
            border-radius: 4px !important;
        }

        .btn-soft-primary:hover {
            background-color: var(--saintek-soft) !important;
            color: var(--saintek-text) !important;
            transform: translateY(-1px);
        }

        .btn-masuk {
            background-color: var(--saintek-primary) !important;
            color: #000000 !important;
            border: none;
            transition: all 0.3s ease;
            border-radius: 4px !important;
        }

        .btn-masuk:hover {
            background-color: var(--saintek-soft) !important;
            color: var(--saintek-text) !important;
        }

        /* LANGUAGE TOGGLE BUTTON */
        .language-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            color: #1e293b;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.375rem 0.65rem;
            text-decoration: none;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .language-toggle:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
            color: #000000;
        }

        /* DROPDOWN MENU */
        .dropdown-menu.dropdown-menu-end {
            border: 1px solid #e5e7eb !important;
            border-radius: 4px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
            min-width: 150px !important;
            padding: 0.4rem 0 !important;
            overflow: hidden !important;
        }

        .dropdown-item.language-item {
            padding: 0.5rem 0.85rem;
            font-size: 0.8rem;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 4px;
            margin: 0.2rem 0.4rem;
        }

        .dropdown-item.language-item:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .dropdown-item.language-item.active-lang {
            background-color: #e5e7eb;
            color: #000000;
            font-weight: 500;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hero-section {
                padding: 140px 0 60px;
            }

            .section {
                padding: 60px 0;
            }
        }

        /* ALL CORNERS SQUARE EXCEPT BUTTONS */
        .modal-content,
        .col-md-5,
        .col-md-7,
        .form-control,
        .border-top {
            border-radius: 0 !important;
        }

        .btn {
            border-radius: 4px !important;
        }

        /* ALERT POPUP STYLES */
        .alert-popup {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 320px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 12px !important;
        }

        /* ===== HAMBURGER MENU STYLES (THE FIX) ===== */
        .hamburger-btn {
            width: 44px;
            height: 44px;
            background: transparent;
            border: none;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 6px;
            padding: 0;
            z-index: 1001;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        /* FIXED HOVER COLOR TO GREY */
        .hamburger-btn:hover,
        .hamburger-btn:focus {
            background: #f3f4f6 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            outline: none;
        }

        .hamburger-btn span {
            width: 24px;
            height: 2.5px;
            background: #1e293b;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 25px rgba(0, 0, 0, 0.1);
            z-index: 1002;
            transition: right 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .mobile-menu.open {
            right: 0;
        }

        .mobile-menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .mobile-menu-close {
            width: 44px;
            height: 44px;
            background: #f1f5f9;
            border: none;
            border-radius: 50%;
            font-size: 32px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            z-index: 1100;
        }

        .mobile-menu-close:hover {
            background: #e2e8f0;
            color: #ef4444;
        }

        .mobile-menu-body {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .mobile-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-nav li {
            margin-bottom: 12px;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: #f8fafc;
            border-radius: 14px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            color: #334155;
            transition: all 0.3s ease;
        }

        .mobile-nav-link:hover {
            background: #f3f4f6;
            transform: translateX(5px);
        }

        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 991px) {
            .navbar-collapse {
                display: none !important;
            }
        }
    </style>
</head>

<body data-bs-spy="scroll" data-bs-target="#navbar-example">

    <nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Sainteku</a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="#repository">Repository</a></li>
                    <li class="nav-item"><a class="nav-link" href="#prestasi"><?php echo e(__('messages.achievements')); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="#inventaris">Inventaris</a></li>
                </ul>
            </div>

            <div class="d-none d-lg-flex align-items-center gap-2">
                <div class="dropdown me-2">
                    <a href="#" class="language-toggle" data-bs-toggle="dropdown">
                        <i class="ri-global-line"></i>
                        <span><?php echo e(session('locale') == 'en' ? 'English' : 'Indonesia'); ?></span>
                        <i class="ri-arrow-down-s-line"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item language-item <?php echo e(session('locale') == 'id' ? 'active-lang' : ''); ?>" href="<?php echo e(route('language.switch', 'id')); ?>"><span>🇮🇩</span> Indonesia</a></li>
                        <li><a class="dropdown-item language-item <?php echo e(session('locale') == 'en' ? 'active-lang' : ''); ?>" href="<?php echo e(route('language.switch', 'en')); ?>"><span>🇬🇧</span> English</a></li>
                    </ul>
                </div>
                <button class="btn btn-soft-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="ri-user-3-line align-bottom me-1"></i> <?php echo e(__('messages.login')); ?>

                </button>
            </div>

            <button class="hamburger-btn d-lg-none" id="hamburgerBtn" type="button">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <h5>Menu</h5>
            <button class="mobile-menu-close" id="closeMenuBtn">&times;</button>
        </div>
        <div class="mobile-menu-body">
            <ul class="mobile-nav">
                <li><a href="#repository" class="mobile-nav-link">Repository</a></li>
                <li><a href="#prestasi" class="mobile-nav-link"><?php echo e(__('messages.achievements')); ?></a></li>
                <li><a href="#inventaris" class="mobile-nav-link">Inventaris</a></li>
            </ul>
        </div>
    </div>

    <section class="hero-section pb-0" id="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-4 fw-semibold text-capitalize mb-3 lh-base"><?php echo e(__('messages.faculty_name')); ?></h1>
                    <p class="lead fs-4 text-muted lh-base mb-4">
                        <?php echo __('messages.faculty_desc', ['study_programs' => '<strong>' . __('messages.study_programs') . '</strong>']); ?>

                    </p>
                    <form class="bg-white p-4 rounded-4 shadow-sm row g-2">
                        <div class="col-md-5"><input type="search" class="form-control form-control-lg" placeholder="<?php echo e(__('messages.search_placeholder')); ?>"></div>
                        <div class="col-md-4">
                            <select class="form-control form-control-lg">
                                <option value=""><?php echo e(__('messages.interest_placeholder')); ?></option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-lg w-100 h-100"><?php echo e(__('messages.find_button')); ?></button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-5 text-center mt-5 mt-lg-0"><img src="assets/images/image11.png" class="img-fluid"></div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
            <div class="modal-content overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 d-none d-md-flex bg-primary align-items-center justify-content-center flex-column p-4">
                        <img src="assets/images/uin.png" width="80" class="mb-3">
                        <h2 class="fw-bold">Sainteku</h2>
                    </div>
                    <div class="col-md-7 p-5 bg-white">
                        <h1 class="fw-semibold mb-4"><?php echo e(__('messages.sign_in')); ?></h1>
                        <form id="loginForm">
                            <div class="mb-3"><input type="text" class="form-control" placeholder="Email" required></div>
                            <div class="mb-3"><input type="password" class="form-control" placeholder="Password" required></div>
                            <button type="submit" class="btn btn-masuk w-100 py-2"><?php echo e(__('messages.login')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hb = document.getElementById('hamburgerBtn');
            const mm = document.getElementById('mobileMenu');
            const ov = document.getElementById('mobileMenuOverlay');
            const cl = document.getElementById('closeMenuBtn');

            function toggle(show) {
                mm.classList.toggle('open', show);
                ov.classList.toggle('active', show);
                document.body.style.overflow = show ? 'hidden' : '';
            }

            hb.onclick = () => toggle(true);
            cl.onclick = () => toggle(false);
            ov.onclick = () => toggle(false);
        });
    </script>
</body>

</html><?php /**PATH D:\Unduhan\sainteku\resources\views/landing.blade.php ENDPATH**/ ?>