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
        :root {
            --saintek-primary: #FEEB04;
            --saintek-primary-dark: #CBB800;
            --saintek-text: #856B2B;
            --saintek-soft: rgba(254, 235, 4, 0.15)
        }

        .bg-primary {
            background-color: var(--saintek-primary) !important
        }

        .btn-primary {
            background-color: var(--saintek-primary) !important;
            border-color: var(--saintek-primary) !important;
            color: #000 !important
        }

        .btn-primary:hover {
            background-color: var(--saintek-primary-dark) !important;
            border-color: var(--saintek-primary-dark) !important;
            color: #000 !important
        }

        .btn-outline-primary {
            border-color: var(--saintek-primary) !important;
            color: var(--saintek-text) !important
        }

        .btn-outline-primary:hover {
            background-color: var(--saintek-primary) !important;
            color: #000 !important
        }

        .text-primary {
            color: var(--saintek-text) !important
        }

        .bg-primary-subtle {
            background-color: rgba(254, 235, 4, 0.2) !important
        }

        body {
            font-family: 'Inter', 'DM Sans', 'Plus Jakarta Sans', sans-serif;
            color: #1e293b
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 600;
            letter-spacing: -0.02em
        }

        .navbar-landing {
            background-color: #fff !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.03);
            padding: 16px 0
        }

        .navbar-brand {
            font-size: 32px !important;
            font-weight: 700 !important;
            color: #1e293b !important
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            color: #334155;
            margin: 0 8px
        }

        .navbar-nav .nav-link.active {
            color: var(--saintek-text) !important
        }

        .card {
            border: none;
            border-radius: 28px;
            box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease
        }

        .card:hover {
            box-shadow: 0 25px 40px -12px rgba(254, 235, 4, 0.15);
            transform: translateY(-6px)
        }

        .section {
            padding: 100px 0
        }

        .hero-section {
            background: linear-gradient(145deg, #fff 0%, #fffaf0 100%);
            padding: 160px 0 100px
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.2) !important
        }

        .modal-backdrop.show {
            opacity: 0.5 !important
        }

        .btn-soft-primary {
            background-color: var(--saintek-primary) !important;
            color: #000 !important;
            border: none;
            transition: all 0.3s ease;
            border-radius: 4px !important
        }

        .btn-soft-primary:hover {
            background-color: var(--saintek-soft) !important;
            color: var(--saintek-text) !important;
            transform: translateY(-1px)
        }

        .btn-masuk {
            background-color: var(--saintek-primary) !important;
            color: #000 !important;
            border: none;
            transition: all 0.3s ease;
            border-radius: 4px !important
        }

        .btn-masuk:hover {
            background-color: var(--saintek-soft) !important;
            color: var(--saintek-text) !important
        }

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
            background-color: #fff;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease
        }

        .language-toggle:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
            color: #000
        }

        .language-toggle:focus {
            outline: none;
            border-color: var(--saintek-primary)
        }

        .language-toggle:active {
            background-color: #f3f4f6
        }

        .language-toggle i {
            font-size: 0.85rem
        }

        .dropdown-menu.dropdown-menu-end {
            border: 1px solid #e5e7eb !important;
            border-radius: 4px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
            min-width: 150px !important;
            padding: 0.4rem 0 !important;
            overflow: hidden !important
        }

        .dropdown-item.language-item {
            padding: 0.5rem 0.85rem;
            font-size: 0.8rem;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.15s ease;
            border-radius: 4px;
            margin: 0.2rem 0.4rem
        }

        .dropdown-item.language-item:hover {
            background-color: #f3f4f6;
            color: #1f2937
        }

        .dropdown-item.language-item.active-lang {
            background-color: #e5e7eb;
            color: #000;
            font-weight: 500;
            border-radius: 2px
        }

        .text-dark-50 {
            color: rgba(0, 0, 0, 0.7)
        }

        .fs-14 {
            font-size: 14px
        }

        .fs-15 {
            font-size: 15px
        }

        @media(max-width:768px) {
            .hero-section {
                padding: 140px 0 60px
            }

            .section {
                padding: 60px 0
            }
        }

        .modal-dialog {
            margin: 1.75rem auto
        }

        .form-control:focus {
            border-color: var(--saintek-primary) !important;
            box-shadow: 0 0 0 3px rgba(254, 235, 4, 0.15) !important;
            outline: none
        }

        .form-check-input:checked {
            background-color: var(--saintek-primary);
            border-color: var(--saintek-primary)
        }

        .modal-content,
        .col-md-5,
        .col-md-7,
        .form-control,
        .border-top {
            border-radius: 0 !important
        }

        .btn {
            border-radius: 4px !important
        }

        @media(max-width:768px) {
            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem) !important
            }

            .modal-content {
                margin: 0
            }

            .col-md-5 {
                display: none !important
            }

            .col-md-7 {
                width: 100% !important
            }
        }

        @media(min-width:769px) {
            .d-md-none {
                display: none !important
            }

            .col-md-7 {
                width: 58.33333333% !important
            }
        }

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
            transition: all 0.3s ease
        }

        .hamburger-btn:hover,
        .hamburger-btn:focus {
            background: #f3f4f6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            outline: none
        }

        .hamburger-btn:active {
            background: #e5e7eb
        }

        .hamburger-btn span {
            width: 24px;
            height: 2.5px;
            background: #1e293b;
            border-radius: 4px;
            transition: all 0.3s ease
        }

        .hamburger-btn.active span:nth-child(1) {
            transform: translateY(8.5px) rotate(45deg)
        }

        .hamburger-btn.active span:nth-child(2) {
            opacity: 0;
            transform: scale(0)
        }

        .hamburger-btn.active span:nth-child(3) {
            transform: translateY(-8.5px) rotate(-45deg)
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            height: 100vh;
            background: #fff;
            box-shadow: -5px 0 25px rgba(0, 0, 0, 0.1);
            z-index: 1002;
            transition: right 0.3s ease;
            display: flex;
            flex-direction: column
        }

        .mobile-menu.open {
            right: 0
        }

        .mobile-menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb
        }

        .mobile-menu-header h5 {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            color: #1e293b
        }

        .mobile-menu-close {
            width: 44px;
            height: 44px;
            background: #f1f5f9;
            border: none;
            border-radius: 8px;
            font-size: 28px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            color: #1e293b;
            font-weight: 300
        }

        .mobile-menu-close:hover {
            background: #e2e8f0
        }

        .mobile-menu-close:focus {
            outline: 2px solid var(--saintek-primary-dark);
            outline-offset: 2px;
            background: #e2e8f0
        }

        .mobile-menu-body {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column
        }

        .mobile-nav {
            list-style: none;
            padding: 0;
            margin: 0
        }

        .mobile-nav li {
            margin-bottom: 12px
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
            transition: all 0.3s ease
        }

        .mobile-nav-link:hover {
            background: #f3f4f6;
            color: #1e293b;
            transform: translateX(5px)
        }

        .mobile-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 20px 0
        }

        .mobile-lang-section {
            background: #f8fafc;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 20px
        }

        .mobile-lang-title {
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 12px
        }

        .mobile-lang-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            transition: all 0.3s ease
        }

        .mobile-lang-link:hover,
        .mobile-lang-link:focus {
            background: #f3f4f6;
            color: var(--saintek-text);
            outline: none
        }

        .mobile-login-btn {
            background: var(--saintek-primary);
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            color: #000;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .mobile-login-btn:hover {
            background: var(--saintek-primary-dark);
            transform: translateY(-2px)
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
            transition: all 0.3s ease
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible
        }

        @media(max-width:991px) {
            .navbar-collapse {
                display: none !important
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
                    <li class="nav-item"><a class="nav-link" href="#repository">Repository</a></li>
                    <li class="nav-item"><a class="nav-link" href="#prestasi"><?php echo e(__('messages.achievements')); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="#inventaris">Inventaris</a></li>
                </ul>
            </div>
            <div class="d-none d-lg-flex align-items-center gap-2">
                <div class="dropdown me-2">
                    <a href="#" class="language-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-global-line"></i>
                        <span><?php echo e(session('locale') == 'en' ? 'English' : 'Indonesia'); ?></span>
                        <i class="ri-arrow-down-s-line"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item language-item <?php echo e(session('locale') == 'id' ? 'active-lang' : ''); ?>" href="<?php echo e(route('language.switch', 'id')); ?>"><span>🇮🇩</span><span>Indonesia</span></a></li>
                        <li><a class="dropdown-item language-item <?php echo e(session('locale') == 'en' ? 'active-lang' : ''); ?>" href="<?php echo e(route('language.switch', 'en')); ?>"><span>🇬🇧</span><span>English</span></a></li>
                    </ul>
                </div>
                <button class="btn btn-soft-primary rounded-lg" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="ri-user-3-line align-bottom me-1"></i> <?php echo e(__('messages.login')); ?>

                </button>
            </div>
            <button class="hamburger-btn d-lg-none" id="hamburgerBtn" type="button" aria-label="Menu"><span></span><span></span><span></span></button>
        </div>
    </nav>

    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <h5>Menu</h5><button class="mobile-menu-close" id="closeMenuBtn" aria-label="Close menu">&times;</button>
        </div>
        <div class="mobile-menu-body">
            <ul class="mobile-nav">
                <li><a href="#repository" class="mobile-nav-link">Repository</a></li>
                <li><a href="#prestasi" class="mobile-nav-link"><?php echo e(__('messages.achievements')); ?></a></li>
                <li><a href="#inventaris" class="mobile-nav-link">Inventaris</a></li>
            </ul>
            <div class="mobile-divider"></div>
            <div class="mobile-lang-section">
                <div class="mobile-lang-title">Language</div>
                <a href="<?php echo e(route('language.switch', 'id')); ?>" class="mobile-lang-link">Indonesia</a>
                <a href="<?php echo e(route('language.switch', 'en')); ?>" class="mobile-lang-link">English</a>
            </div>
            <button class="mobile-login-btn" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="ri-user-3-line me-2"></i> <?php echo e(__('messages.login')); ?></button>
        </div>
    </div>

    <section class="hero-section pb-0" id="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-4 fw-semibold text-capitalize mb-3 lh-base"><?php echo e(__('messages.faculty_name')); ?></h1>
                    <p class="lead fs-4 text-muted lh-base mb-4"><?php echo __('messages.faculty_desc', ['study_programs' => '<strong>' . __('messages.study_programs') . '</strong>']); ?></p>
                    <form action="#" class="job-panel-filter bg-white p-4 rounded-4 shadow-sm">
                        <div class="row g-2">
                            <div class="col-md-5"><input type="search" class="form-control form-control-lg" placeholder="<?php echo e(__('messages.search_placeholder')); ?>"></div>
                            <div class="col-md-4"><select class="form-control form-control-lg">
                                    <option value=""><?php echo e(__('messages.interest_placeholder')); ?></option>
                                    <option value="Informatika"><?php echo e(__('messages.informatics')); ?></option>
                                    <option value="Arsitektur"><?php echo e(__('messages.architecture')); ?></option>
                                    <option value="Ilmu Lingkungan"><?php echo e(__('messages.environmental')); ?></option>
                                    <option value="Ilmu Perpustakaan"><?php echo e(__('messages.library')); ?></option>
                                </select></div>
                            <div class="col-md-3"><button class="btn btn-primary btn-lg w-100 h-100" type="button"><i class="ri-search-2-line align-bottom me-1"></i> <?php echo e(__('messages.find_button')); ?></button></div>
                        </div>
                    </form>
                    <ul class="list-inline mb-0 mt-4 fs-14">
                        <li class="list-inline-item text-danger fw-semibold"><i class="mdi mdi-tag-multiple-outline align-middle"></i> <?php echo e(__('messages.featured_programs')); ?></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline"><?php echo e(__('messages.informatics')); ?>,</a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline"><?php echo e(__('messages.architecture')); ?>,</a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline"><?php echo e(__('messages.environmental')); ?>,</a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline"><?php echo e(__('messages.library')); ?></a></li>
                    </ul>
                </div>
                <div class="col-lg-5">
                    <div class="position-relative text-center mt-5 mt-lg-0"><img src="assets/images/image11.png" alt="Hero Illustration" class="img-fluid"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light pt-0" id="tentang">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <div class="position-relative"><img src="assets/images/about.jpg" alt="" class="img-fluid rounded-4 shadow-lg">
                        <div class="card position-absolute bottom-0 start-0 mb-4 ms-4 shadow-lg" style="max-width:250px">
                            <div class="card-body d-flex align-items-center p-3">
                                <div class="flex-shrink-0 me-3"><img src="assets/images/image.png" alt="" class="avatar-md rounded-circle"></div>
                                <div>
                                    <h6 class="fw-bold mb-1">Prof. Dr. Kholid Mawardi, M. Hum.</h6>
                                    <p class="text-muted small mb-1"><?php echo e(__('messages.dean')); ?></p>
                                    <div class="text-warning small"><i class="ri-star-s-fill"></i><i class="ri-star-s-fill"></i><i class="ri-star-s-fill"></i><i class="ri-star-s-fill"></i><i class="ri-star-s-fill"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="card position-absolute top-0 end-0 mt-4 me-4 shadow-lg">
                            <div class="card-body d-flex align-items-center p-3">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-18"><i class="ri-briefcase-2-line"></i></div>
                                </div>
                                <div>
                                    <h5 class="fs-15 lh-base mb-0"><span class="text-secondary fw-semibold">1000+</span> <?php echo e(__('Mahasiswa')); ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <h1 class="display-6 mb-4 lh-base"><?php echo e(__('messages.about_title')); ?></h1>
                    <p class="fs-5 text-muted mb-3"><?php echo e(__('messages.about_desc1')); ?></p>
                    <p class="fs-5 text-muted mb-4"><?php echo e(__('messages.about_desc2')); ?></p>
                    <div class="vstack gap-3 mb-4">
                        <div class="d-flex align-items-center"><i class="ri-checkbox-circle-fill text-primary fs-4 me-3"></i><span class="fs-5"><strong><?php echo e(__('messages.about_list1')); ?></strong></span></div>
                        <div class="d-flex align-items-center"><i class="ri-checkbox-circle-fill text-primary fs-4 me-3"></i><span class="fs-5"><?php echo e(__('messages.about_list2')); ?></span></div>
                        <div class="d-flex align-items-center"><i class="ri-checkbox-circle-fill text-primary fs-4 me-3"></i><span class="fs-5"><?php echo e(__('messages.about_list3')); ?></span></div>
                    </div><a href="#!" class="btn btn-primary btn-lg px-5 rounded-pill"><?php echo e(__('messages.explore_button')); ?> <i class="ri-arrow-right-line align-bottom ms-1"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="program-studi">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-6 mb-3 ff-secondary fw-semibold"><span class="text-primary"><?php echo e(__('messages.our_programs')); ?></span></h1>
                <p class="fs-5 text-muted col-lg-8 mx-auto"><?php echo e(__('messages.programs_desc')); ?></p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3"><i class="ri-computer-line fs-1 text-primary"></i></div>
                        </div>
                        <h4 class="mb-3"><?php echo e(__('messages.informatics')); ?></h4>
                        <p class="text-muted mb-4"><?php echo e(__('messages.informatics_desc')); ?></p><a href="#" class="link-primary stretched-link"><?php echo e(__('messages.detail')); ?> <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3"><i class="ri-building-2-line fs-1 text-primary"></i></div>
                        </div>
                        <h4 class="mb-3"><?php echo e(__('messages.architecture')); ?></h4>
                        <p class="text-muted mb-4"><?php echo e(__('messages.architecture_desc')); ?></p><a href="#" class="link-primary stretched-link"><?php echo e(__('messages.detail')); ?> <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3"><i class="ri-leaf-line fs-1 text-primary"></i></div>
                        </div>
                        <h4 class="mb-3"><?php echo e(__('messages.environmental')); ?></h4>
                        <p class="text-muted mb-4"><?php echo e(__('messages.environmental_desc')); ?></p><a href="#" class="link-primary stretched-link"><?php echo e(__('messages.detail')); ?> <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3"><i class="ri-book-open-line fs-1 text-primary"></i></div>
                        </div>
                        <h4 class="mb-3"><?php echo e(__('messages.library')); ?></h4>
                        <p class="text-muted mb-4"><?php echo e(__('messages.library_desc')); ?></p><a href="#" class="link-primary stretched-link"><?php echo e(__('messages.detail')); ?> <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-primary mt-0">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h4 class="text-dark fs-2 fw-semibold mb-2"><?php echo e(__('messages.cta_title')); ?></h4>
                    <p class="text-dark-50 fs-5 mb-0"><?php echo e(__('messages.cta_desc')); ?></p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0"><a href="#!" class="btn btn-danger btn-lg px-5 py-3 rounded-pill"><?php echo e(__('messages.register_button')); ?></a></div>
            </div>
        </div>
    </section>

    
    <?php use Illuminate\Support\Facades\DB; $inventories = DB::table('mst_inventory')->where('status',1)->orderBy('item_name','asc')->paginate(9); ?>
    <section class="section" id="inventaris">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-6 mb-3 ff-secondary fw-semibold">Inventaris Fasilitas</h1>
                <p class="fs-5 text-muted col-lg-8 mx-auto">Daftar peralatan dan fasilitas yang tersedia untuk dipinjam</p>
            </div>
            <div class="row g-4">
                <?php $__empty_1 = true; $__currentLoopData = $inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="avatar-sm me-3 flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle rounded-3"><i class="ri-tools-line fs-3 text-primary"></i></div>
                            </div>
                            <div class="flex-grow-1"><?php if($item->stock>0): ?><span class="badge bg-success-subtle text-success mb-1" style="font-size:0.7rem"><i class="ri-check-line me-1"></i>Tersedia</span><?php else: ?><span class="badge bg-danger-subtle text-danger mb-1" style="font-size:0.7rem"><i class="ri-close-line me-1"></i>Habis</span><?php endif; ?><h5 class="fw-bold mb-0 fs-16"><?php echo e($item->item_name); ?></h5>
                            </div>
                        </div>
                        <div class="mb-3"><?php if(!empty($item->description)): ?><p class="text-muted small mb-2"><?php echo e(Str::limit($item->description,80)); ?></p><?php endif; ?><small class="text-muted d-block mb-1"><i class="ri-stack-line me-1"></i> Stok: <strong><?php echo e($item->stock ?? 0); ?></strong> unit</small></div>
                        <hr class="my-3">
                        <div class="mt-auto"><?php if($item->stock>0): ?><button class="btn btn-outline-primary btn-sm w-100" disabled><i class="ri-hand-coin-line me-1"></i> Bisa Dipinjam</button><?php else: ?><button class="btn btn-secondary btn-sm w-100" disabled style="opacity:0.5"><i class="ri-forbid-line me-1"></i> Tidak Tersedia</button><?php endif; ?></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-light rounded-circle"><i class="ri-box-3-line fs-1 text-muted"></i></div>
                        </div>
                        <h5 class="text-muted mb-2">Belum Ada Inventaris</h5>
                        <p class="text-muted">Data inventaris akan ditampilkan setelah ditambahkan oleh admin.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php if($inventories->hasPages()): ?><div class="d-flex justify-content-center mt-5"><?php echo e($inventories->links()); ?></div><?php endif; ?>
        </div>
    </section>

    
    <?php use Modules\DocumentRepository\app\Models\Document; $search=request('search'); $query=Document::with(['type','unit','creator'])->where('status',3); if($search){$query->where(function($q)use($search){$q->where('document_title','like','%'.$search.'%')->orWhere('document_id','like','%'.$search.'%');});} $documents=$query->orderBy('created_at','desc')->paginate(9); ?>
    <section class="section" id="repository">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-6 mb-3 ff-secondary fw-semibold">Repository Dokumen</h1>
                <p class="fs-5 text-muted col-lg-8 mx-auto">Koleksi dokumen, pedoman, dan karya ilmiah Fakultas Sains dan Teknologi</p>
            </div>
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto">
                    <form action="#repository" method="GET" class="bg-white p-3 rounded-4 shadow-sm">
                        <div class="row g-2">
                            <div class="col-md-8"><input type="search" name="search" class="form-control form-control-lg" placeholder="Cari dokumen..." value="<?php echo e($search ?? ''); ?>"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary btn-lg w-100 h-100"><i class="ri-search-2-line align-bottom me-1"></i> Cari</button></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row g-4">
                <?php $__empty_1 = true; $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="avatar-sm me-3 flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle rounded-3"><i class="ri-file-text-line fs-3 text-primary"></i></div>
                            </div>
                            <div class="flex-grow-1"><span class="badge bg-success-subtle text-success mb-1" style="font-size:0.7rem"><i class="ri-check-line me-1"></i>Tersedia</span>
                                <h5 class="fw-bold mb-0 fs-16"><?php echo e(Str::limit($doc->document_title,50)); ?></h5>
                            </div>
                        </div>
                        <div class="mb-3"><small class="text-muted d-block mb-1"><i class="ri-hashtag me-1"></i> <?php echo e($doc->document_id); ?></small><small class="text-muted d-block mb-1"><i class="ri-price-tag-3-line me-1"></i> <?php echo e($doc->type->description ?? 'Umum'); ?></small><small class="text-muted d-block"><i class="ri-building-2-line me-1"></i> <?php echo e($doc->unit->unit_name ?? 'Fakultas'); ?></small></div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center mt-auto"><small class="text-muted"><i class="ri-user-line me-1"></i> <?php echo e($doc->creator->name ?? 'Admin'); ?></small><small class="text-muted"><i class="ri-calendar-line me-1"></i> <?php echo e($doc->created_at->format('d M Y')); ?></small></div><a href="<?php echo e(route('DocumentRepository.download',$doc->id)); ?>" class="btn btn-primary btn-sm w-100 mt-3" target="_blank"><i class="ri-download-2-line me-1"></i> Download Dokumen</a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-light rounded-circle"><i class="ri-folder-open-line fs-1 text-muted"></i></div>
                        </div>
                        <h5 class="text-muted mb-2">Belum Ada Dokumen</h5>
                        <p class="text-muted">Dokumen akan muncul setelah disetujui oleh reviewer.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php if($documents->hasPages()): ?><div class="d-flex justify-content-center mt-5"><?php echo e($documents->links()); ?></div><?php endif; ?>
        </div>
    </section>

    <section class="section" id="prestasi">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-5">
                    <h2 class="fw-bold mb-3"><?php echo e(__('messages.achievements_title')); ?></h2>
                    <p class="text-muted fs-5"><?php echo e(__('messages.achievements_desc')); ?></p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative"><img src="assets/images/small/img-10.jpg" class="card-img-top" alt="" style="height:200px;object-fit:cover">
                            <div class="position-absolute top-0 end-0 m-3"><span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">2024</span></div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle"><i class="ri-trophy-line text-primary"></i></div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo e(__('messages.informatics')); ?></h6><small class="text-muted">Tim Rajawali</small>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Juara 1 Gemastik XIV</h5>
                            <p class="text-muted small mb-3">Aplikasi Smart Campus untuk efisiensi energi kampus.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center"><img src="assets/images/users/avatar-2.jpg" class="rounded-circle me-2" width="28" height="28" alt=""><img src="assets/images/users/avatar-3.jpg" class="rounded-circle me-2" width="28" height="28" alt=""><img src="assets/images/users/avatar-4.jpg" class="rounded-circle" width="28" height="28" alt=""></div><a href="#" class="text-primary small"><?php echo e(__('messages.more')); ?> <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative"><img src="assets/images/small/img-9.jpg" class="card-img-top" alt="" style="height:200px;object-fit:cover">
                            <div class="position-absolute top-0 end-0 m-3"><span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">2024</span></div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle"><i class="ri-trophy-line text-primary"></i></div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo e(__('messages.architecture')); ?></h6><small class="text-muted">Tim Garuda</small>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Juara 2 Lomba Desain Arsitektur</h5>
                            <p class="text-muted small mb-3">Desain hunian vertikal ramah lingkungan.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center"><img src="assets/images/users/avatar-5.jpg" class="rounded-circle me-2" width="28" height="28" alt=""><img src="assets/images/users/avatar-6.jpg" class="rounded-circle me-2" width="28" height="28" alt=""><img src="assets/images/users/avatar-7.jpg" class="rounded-circle" width="28" height="28" alt=""></div><a href="#" class="text-primary small"><?php echo e(__('messages.more')); ?> <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative"><img src="assets/images/small/img-8.jpg" class="card-img-top" alt="" style="height:200px;object-fit:cover">
                            <div class="position-absolute top-0 end-0 m-3"><span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">2024</span></div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle"><i class="ri-trophy-line text-primary"></i></div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo e(__('messages.environmental')); ?></h6><small class="text-muted">Tim Hijau</small>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Juara Harapan 1 KTI</h5>
                            <p class="text-muted small mb-3">Pengolahan sampah organik menjadi energi.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center"><img src="assets/images/users/avatar-8.jpg" class="rounded-circle me-2" width="28" height="28" alt=""><img src="assets/images/users/avatar-9.jpg" class="rounded-circle me-2" width="28" height="28" alt=""><img src="assets/images/users/avatar-10.jpg" class="rounded-circle" width="28" height="28" alt=""></div><a href="#" class="text-primary small"><?php echo e(__('messages.more')); ?> <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5"><a href="#" class="btn btn-outline-primary px-5 py-3 rounded-pill"><?php echo e(__('messages.view_all')); ?></a></div>
        </div>
    </section>

    <section class="section" id="fasilitas">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-5">
                    <h2 class="fw-bold mb-3"><?php echo e(__('messages.facilities_title')); ?></h2>
                    <p class="text-muted fs-5"><?php echo e(__('messages.facilities_desc')); ?></p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4"><img src="assets/images/small/img-3.jpg" class="card-img-top rounded-top-4" alt="" style="height:180px;object-fit:cover">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Lab. Terpadu Informatika</h5>
                            <p class="text-muted small mb-3">40 Unit PC Spesifikasi Tinggi, VR, IoT</p><span class="badge bg-primary-subtle text-primary"><?php echo e(__('messages.informatics')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4"><img src="assets/images/small/img-4.jpg" class="card-img-top rounded-top-4" alt="" style="height:180px;object-fit:cover">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Studio Arsitektur</h5>
                            <p class="text-muted small mb-3">Ruang desain, workshop, peralatan lengkap</p><span class="badge bg-primary-subtle text-primary"><?php echo e(__('messages.architecture')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4"><img src="assets/images/small/img-5.jpg" class="card-img-top rounded-top-4" alt="" style="height:180px;object-fit:cover">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Lab. Ilmu Lingkungan</h5>
                            <p class="text-muted small mb-3">Alat ukur kualitas air, udara, dan tanah</p><span class="badge bg-primary-subtle text-primary"><?php echo e(__('messages.environmental')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5"><a href="#" class="btn btn-primary px-5 py-2 rounded-pill"><?php echo e(__('messages.explore_facilities')); ?></a></div>
        </div>
    </section>

    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-6 mb-3 ff-secondary fw-semibold"><?php echo e(__('messages.blog_title')); ?></h1>
            <p class="fs-5 text-muted col-lg-8 mx-auto"><?php echo e(__('messages.blog_desc')); ?></p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card"><img src="assets/images/small/img-8.jpg" class="card-img-top" alt="" style="height:220px;object-fit:cover">
                    <div class="card-body p-4">
                        <div class="d-flex text-muted mb-3">
                            <div class="me-3"><i class="ri-calendar-line me-1"></i> 30 Okt, 2024</div>
                            <div><i class="ri-message-2-line me-1"></i> 12 Komentar</div>
                        </div>
                        <h4 class="mb-3">Workshop Kecerdasan Buatan</h4>
                        <p class="text-muted mb-4">Program Studi Informatika mengadakan workshop yang diikuti oleh lebih dari 100 peserta.</p><a href="#" class="link-primary fw-semibold"><?php echo e(__('messages.read_more')); ?> <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card"><img src="assets/images/small/img-7.jpg" class="card-img-top" alt="" style="height:220px;object-fit:cover">
                    <div class="card-body p-4">
                        <div class="d-flex text-muted mb-3">
                            <div class="me-3"><i class="ri-calendar-line me-1"></i> 25 Okt, 2024</div>
                            <div><i class="ri-message-2-line me-1"></i> 8 Komentar</div>
                        </div>
                        <h4 class="mb-3">Pameran Arsitektur 2024</h4>
                        <p class="text-muted mb-4">Mahasiswa Arsitektur memamerkan karya desain terbaik mereka.</p><a href="#" class="link-primary fw-semibold"><?php echo e(__('messages.read_more')); ?> <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card"><img src="assets/images/small/img-6.jpg" class="card-img-top" alt="" style="height:220px;object-fit:cover">
                    <div class="card-body p-4">
                        <div class="d-flex text-muted mb-3">
                            <div class="me-3"><i class="ri-calendar-line me-1"></i> 20 Okt, 2024</div>
                            <div><i class="ri-message-2-line me-1"></i> 15 Komentar</div>
                        </div>
                        <h4 class="mb-3">Seminar Nasional Lingkungan</h4>
                        <p class="text-muted mb-4">Membahas isu perubahan iklim dan peran generasi muda.</p><a href="#" class="link-primary fw-semibold"><?php echo e(__('messages.read_more')); ?> <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5 bg-primary">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h4 class="text-dark fs-2 fw-semibold mb-2"><?php echo e(__('messages.newsletter_title')); ?></h4>
                    <p class="text-dark-50 fs-5 mb-0"><?php echo e(__('messages.newsletter_desc')); ?></p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0"><button class="btn btn-danger btn-lg px-5 py-3 rounded-pill"><?php echo e(__('messages.subscribe_button')); ?> <i class="ri-arrow-right-line align-bottom"></i></button></div>
            </div>
        </div>
    </section>

    <footer class="custom-footer bg-dark py-5 position-relative">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mt-4">
                    <div><span class="fw-bold text-white" style="font-size:28px">Sainteku</span>
                        <div class="mt-4 fs-13">
                            <p class="text-white-50"><?php echo e(__('messages.footer_desc')); ?></p>
                            <ul class="list-inline mb-0 footer-social-link">
                                <li class="list-inline-item"><a href="javascript:void(0)" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle"><i class="ri-facebook-fill"></i></div>
                                    </a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle"><i class="ri-twitter-fill"></i></div>
                                    </a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle"><i class="ri-instagram-fill"></i></div>
                                    </a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle"><i class="ri-youtube-fill"></i></div>
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 ms-lg-auto">
                    <div class="row">
                        <div class="col-sm-4 mt-4">
                            <h5 class="text-white mb-0"><?php echo e(__('messages.quick_links')); ?></h5>
                            <div class="text-muted mt-3">
                                <ul class="list-unstyled ff-secondary footer-list">
                                    <li><a href="#"><?php echo e(__('messages.about_us')); ?></a></li>
                                    <li><a href="#"><?php echo e(__('messages.accreditation')); ?></a></li>
                                    <li><a href="#"><?php echo e(__('messages.lecturers')); ?></a></li>
                                    <li><a href="#"><?php echo e(__('messages.student_affairs')); ?></a></li>
                                    <li><a href="#"><?php echo e(__('messages.alumni')); ?></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 mt-4">
                            <h5 class="text-white mb-0"><?php echo e(__('messages.study_programs_footer')); ?></h5>
                            <div class="text-muted mt-3">
                                <ul class="list-unstyled ff-secondary footer-list">
                                    <li><a href="#"><?php echo e(__('messages.informatics')); ?></a></li>
                                    <li><a href="#"><?php echo e(__('messages.architecture')); ?></a></li>
                                    <li><a href="#"><?php echo e(__('messages.environmental')); ?></a></li>
                                    <li><a href="#"><?php echo e(__('messages.library')); ?></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 mt-4">
                            <h5 class="text-white mb-0"><?php echo e(__('messages.help')); ?></h5>
                            <div class="text-muted mt-3">
                                <ul class="list-unstyled ff-secondary footer-list">
                                    <li><a href="#"><?php echo e(__('messages.faq')); ?></a></li>
                                    <li><a href="#"><?php echo e(__('messages.contact_us')); ?></a></li>
                                    <li><a href="#"><?php echo e(__('messages.sitemap')); ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row text-center text-sm-start align-items-center mt-5">
                <div class="col-sm-6">
                    <p class="copy-rights mb-0 text-white-50">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> &copy; Sainteku - <?php echo e(__('UIN Prof. K.H. Saifuddin Zuhri')); ?>

                    </p>
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end mt-3 mt-sm-0">
                        <ul class="list-inline mb-0 footer-list gap-4 fs-13">
                            <li class="list-inline-item"><a href="#"><?php echo e(__('messages.privacy_policy')); ?></a></li>
                            <li class="list-inline-item"><a href="#"><?php echo e(__('messages.terms')); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <button onclick="topFunction()" class="btn btn-primary btn-icon rounded-circle" id="back-to-top" style="position:fixed;bottom:30px;right:30px;display:none"><i class="ri-arrow-up-line"></i></button>

    
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:1000px;margin:1.75rem auto">
            <div class="modal-content overflow-hidden" style="background:transparent;border:none;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25)">
                <div class="row g-0">
                    <div class="col-md-5 d-none d-md-flex flex-column align-items-center justify-content-center" style="background:linear-gradient(145deg,#FEEB04 0%,#CBB800 100%);min-height:550px;position:relative">
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-10" style="background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22 viewBox=%220 0 100 100%22><path d=%22M0 0 L100 100 M100 0 L0 100%22 stroke=%22%23000000%22 stroke-width=%221%22 opacity=%220.2%22/></svg>');background-size:30px 30px"></div>
                        <div class="text-center position-relative z-1 px-4 py-3">
                            <img src="<?php echo e(asset('assets/images/uin.png')); ?>" alt="Logo UIN Saizu" class="img-fluid mb-3" style="max-width:95px;height:auto">
                            <h2 class="fw-bold mb-2" style="color:#000;font-size:2rem">Sainteku</h2>
                            <p class="mb-0 text-dark" style="font-size:0.9rem"><?php echo e(__('messages.faculty_name')); ?></p>
                            <p class="mb-3 text-dark" style="font-size:0.9rem"><?php echo e(__('UIN Prof. K.H. Saifuddin Zuhri Purwokerto')); ?></p>
                            <div class="mt-4 pt-2">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-2 opacity-75">
                                    <path d="M10 11H6V7H10V11ZM18 11H14V7H18V11Z" fill="#000" />
                                </svg>
                                <p class="fst-italic text-dark" style="font-size:0.85rem;max-width:260px;margin:0 auto;line-height:1.5;opacity:0.9">"<?php echo e(__('messages.quote')); ?>"</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 bg-white">
                        <div class="d-md-none p-4 text-center border-bottom" style="background:#F9FAFB">
                            <h2 class="fw-bold mb-1" style="color:#000;font-size:1.8rem">Sainteku</h2>
                            <p class="mb-0 text-muted small"><?php echo e(__('messages.faculty_name')); ?></p>
                            <p class="mb-0 text-muted small"><?php echo e(__('UIN Prof. K.H. Saifuddin Zuhri Purwokerto')); ?></p>
                        </div>
                        <div class="p-4" style="max-width:450px;margin:0 auto">
                            <div class="mb-3"><a href="#" class="d-inline-flex align-items-center text-sm text-gray-500 text-decoration-none" data-bs-dismiss="modal"><svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20 20" fill="none">
                                        <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg><?php echo e(__('messages.back_to_dashboard')); ?></a></div>
                            <div class="mb-4">
                                <h1 class="fw-semibold text-gray-800" style="font-size:1.8rem;margin-bottom:0.25rem"><?php echo e(__('messages.sign_in')); ?></h1>
                                <p class="text-sm text-gray-500"><?php echo e(__('messages.enter_credentials')); ?></p>
                            </div>
                            <form method="POST" action="/login" id="loginForm">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3"><label class="form-label d-block small fw-medium text-gray-700 mb-1"><?php echo e(__('messages.email_label')); ?><span class="text-danger">*</span></label><input type="text" class="form-control w-100 px-3 py-2 text-sm text-gray-800 bg-transparent border border-gray-300" name="credential" placeholder="info@gmail.com" style="height:42px" required></div>
                                <div class="mb-3"><label class="form-label d-block small fw-medium text-gray-700 mb-1"><?php echo e(__('messages.password_label')); ?><span class="text-danger">*</span></label><input type="password" class="form-control w-100 px-3 py-2 text-sm text-gray-800 bg-transparent border border-gray-300" name="password" placeholder="Password" style="height:42px" required></div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check"><input type="checkbox" class="form-check-input" name="remember" style="width:16px;height:16px"><label class="form-check-label small text-gray-700 ms-1"><?php echo e(__('messages.remember_me')); ?></label></div><a href="#" class="small text-decoration-none" style="color:#000" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal"><?php echo e(__('messages.forgot_password')); ?></a>
                                </div>
                                <button type="submit" class="btn w-100 py-2 small fw-medium border-0 btn-masuk"><?php echo e(__('messages.login')); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="forgotPasswordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:500px">
            <div class="modal-content" style="border:none;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25)">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-semibold"><?php echo e(__('messages.forgot_password_title')); ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST" action="<?php echo e(route('password.email')); ?>"><?php echo csrf_field(); ?><div class="mb-4"><input type="email" class="form-control" name="email" placeholder="nama@email.com" required></div>
                        <div class="d-flex gap-2"><button type="button" class="btn w-100" style="background:#F3F4F6" data-bs-dismiss="modal"><?php echo e(__('messages.cancel')); ?></button><button type="submit" class="btn w-100" style="background:var(--saintek-primary);color:#000"><?php echo e(__('messages.send_reset_link')); ?></button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/pages/job-lading.init.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const b = document.getElementById('hamburgerBtn'),
                m = document.getElementById('mobileMenu'),
                o = document.getElementById('mobileMenuOverlay'),
                c = document.getElementById('closeMenuBtn'),
                bd = document.body;

            function open() {
                m.classList.add('open');
                o.classList.add('active');
                b.classList.add('active');
                bd.style.overflow = 'hidden'
            }

            function close() {
                m.classList.remove('open');
                o.classList.remove('active');
                b.classList.remove('active');
                bd.style.overflow = ''
            }
            if (b) b.addEventListener('click', function(e) {
                e.stopPropagation();
                m.classList.contains('open') ? close() : open()
            });
            if (c) c.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                close()
            });
            if (o) o.addEventListener('click', close);
            document.querySelectorAll('.mobile-nav-link').forEach(l => l.addEventListener('click', function(e) {
                close();
                const id = this.getAttribute('href');
                if (id && id !== '#') {
                    e.preventDefault();
                    const el = document.querySelector(id);
                    if (el) setTimeout(() => el.scrollIntoView({
                        behavior: 'smooth'
                    }), 300)
                }
            }));
            const mlb = document.querySelector('.mobile-login-btn');
            if (mlb) mlb.addEventListener('click', close);
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) close()
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && m.classList.contains('open')) close()
            });

            const lf = document.getElementById('loginForm');
            if (lf) lf.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = this.querySelector('button[type="submit"]');
                const orig = '<?php echo e(__("messages.login")); ?>';
                btn.disabled = true;
                btn.innerHTML = 'Processing...';
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>'
                    }
                }).then(r => r.json()).then(d => {
                    if (d.success) {
                        alert(d.message);
                        window.location.href = d.redirect || '/dashboard';
                    } else {
                        alert(d.message || 'Login gagal!');
                        btn.disabled = false;
                        btn.innerHTML = orig;
                    }
                }).catch(() => {
                    alert('Terjadi kesalahan. Coba lagi.');
                    btn.disabled = false;
                    btn.innerHTML = orig;
                })
            });
        });
    </script>
</body>

</html><?php /**PATH D:\Unduhan\sainteku\resources\views/landing.blade.php ENDPATH**/ ?>