<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>SAINTEKU | UIN SAIZU PURWOKERTO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Fakultas Sains dan Teknologi UIN Saifuddin Zuhri Purwokerto" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/uin.png">

    <!-- Swiper slider css -->
    <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <!-- App Css -->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />

    <!-- Custom Css -->
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
            background-color: #f9fafb;
            border-color: #9ca3af;
            color: #000000;
        }

        .language-toggle:focus {
            outline: none;
            border-color: var(--saintek-primary);
        }

        .language-toggle:active {
            background-color: #f3f4f6;
        }

        .language-toggle i {
            font-size: 0.85rem;
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
            transition: background-color 0.15s ease;
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
            border-radius: 2px;
        }

        .dropdown-item.language-item span:first-child {
            font-size: 1rem;
        }

        /* UTILITY CLASSES */
        .text-dark-50 {
            color: rgba(0, 0, 0, 0.7);
        }

        .fs-14 {
            font-size: 14px;
        }

        .fs-15 {
            font-size: 15px;
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

        /* MODAL STYLING */
        .modal-dialog {
            margin: 1.75rem auto;
        }

        .form-control:focus {
            border-color: var(--saintek-primary) !important;
            box-shadow: 0 0 0 3px rgba(254, 235, 4, 0.15) !important;
            outline: none;
        }

        .form-check-input:checked {
            background-color: var(--saintek-primary);
            border-color: var(--saintek-primary);
        }

        /* ALL CORNERS SQUARE EXCEPT BUTTONS */
        .modal-content,
        .col-md-5,
        .col-md-7,
        .form-control,
        .border-top {
            border-radius: 0 !important;
        }

        /* BUTTONS WITH SLIGHT RADIUS */
        .btn {
            border-radius: 4px !important;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem) !important;
            }

            .modal-content {
                margin: 0;
            }

            .col-md-5 {
                display: none !important;
            }

            .col-md-7 {
                width: 100% !important;
            }

            .p-4 {
                padding: 1.25rem !important;
            }

            h1 {
                font-size: 1.6rem !important;
            }

            .btn {
                font-size: 0.85rem !important;
            }
        }

        /* Desktop Styles */
        @media (min-width: 769px) {
            .d-md-none {
                display: none !important;
            }

            .col-md-7 {
                width: 58.33333333% !important;
            }
        }

        /* ALERT POPUP STYLES */
        .alert-popup {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 320px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 12px !important;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateX(-50%) translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }

            to {
                transform: translateX(-50%) translateY(-100%);
                opacity: 0;
            }
        }
    </style>
</head>

<body data-bs-spy="scroll" data-bs-target="#navbar-example">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Sainteku</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#event">{{ __('messages.event') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#prestasi">{{ __('messages.achievements') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#blog">{{ __('messages.blog') }}</a>
                    </li>
                </ul>

                <!-- Language Toggle Button -->
                <div class="dropdown me-2">
                    <a href="#" class="language-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-global-line"></i>
                        <span class="d-none d-md-inline">{{ session('locale') == 'en' ? 'English' : 'Indonesia' }}</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item language-item {{ session('locale') == 'id' ? 'active-lang' : '' }}" href="{{ route('language.switch', 'id') }}">
                                <span>🇮🇩</span>
                                <span>Indonesia</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item language-item {{ session('locale') == 'en' ? 'active-lang' : '' }}" href="{{ route('language.switch', 'en') }}">
                                <span>🇬🇧</span>
                                <span>English</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Login Button -->
                <div>
                    <button class="btn btn-soft-primary rounded-lg" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="ri-user-3-line align-bottom me-1"></i> {{ __('messages.login') }}
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- ALERT POPUP -->
    <div id="loginAlert" class="alert alert-dismissible fade show alert-popup" role="alert" style="display: none;">
        <i id="alertIcon" class="ri-checkbox-circle-line me-2 fs-5 align-middle"></i>
        <span id="alertMessage"></span>
        <button type="button" class="btn-close" onclick="hideAlert()"></button>
    </div>

    <!-- HERO SECTION -->
    <section class="hero-section pb-0" id="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-4 fw-semibold text-capitalize mb-3 lh-base">{{ __('messages.faculty_name') }}</h1>
                    <p class="lead fs-4 text-muted lh-base mb-4">
                        {!! __('messages.faculty_desc', ['study_programs' => '<strong>' . __('messages.study_programs') . '</strong>']) !!}
                    </p>

                    <form action="#" class="job-panel-filter bg-white p-4 rounded-4 shadow-sm">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <input type="search" class="form-control form-control-lg" placeholder="{{ __('messages.search_placeholder') }}">
                            </div>
                            <div class="col-md-4">
                                <select class="form-control form-control-lg">
                                    <option value="">{{ __('messages.interest_placeholder') }}</option>
                                    <option value="Informatika">{{ __('messages.informatics') }}</option>
                                    <option value="Arsitektur">{{ __('messages.architecture') }}</option>
                                    <option value="Ilmu Lingkungan">{{ __('messages.environmental') }}</option>
                                    <option value="Ilmu Perpustakaan">{{ __('messages.library') }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary btn-lg w-100 h-100" type="button">
                                    <i class="ri-search-2-line align-bottom me-1"></i> {{ __('messages.find_button') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <ul class="list-inline mb-0 mt-4 fs-14">
                        <li class="list-inline-item text-danger fw-semibold"><i class="mdi mdi-tag-multiple-outline align-middle"></i> {{ __('messages.featured_programs') }}</li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline">{{ __('messages.informatics') }},</a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline">{{ __('messages.architecture') }},</a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline">{{ __('messages.environmental') }},</a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline">{{ __('messages.library') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-5">
                    <div class="position-relative text-center mt-5 mt-lg-0">
                        <!-- Image -->
                        <img src="assets/images/image11.png" alt="Hero Illustration" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TENTANG FAKULTAS -->
    <section class="section bg-light pt-0" id="tentang">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <div class="position-relative">
                        <img src="assets/images/about.jpg" alt="" class="img-fluid rounded-4 shadow-lg">

                        <div class="card position-absolute bottom-0 start-0 mb-4 ms-4 shadow-lg" style="max-width: 250px;">
                            <div class="card-body d-flex align-items-center p-3">
                                <div class="flex-shrink-0 me-3">
                                    <img src="assets/images/image.png" alt="" class="avatar-md rounded-circle">
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Prof. Dr. Kholid Mawardi, M. Hum.</h6>
                                    <p class="text-muted small mb-1">{{ __('messages.dean') }}</p>
                                    <div class="text-warning small">
                                        <i class="ri-star-s-fill"></i>
                                        <i class="ri-star-s-fill"></i>
                                        <i class="ri-star-s-fill"></i>
                                        <i class="ri-star-s-fill"></i>
                                        <i class="ri-star-s-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card position-absolute top-0 end-0 mt-4 me-4 shadow-lg">
                            <div class="card-body d-flex align-items-center p-3">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-18">
                                        <i class="ri-briefcase-2-line"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fs-15 lh-base mb-0"><span class="text-secondary fw-semibold">1000+</span> {{ __('Mahasiswa') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <h1 class="display-6 mb-4 lh-base">{{ __('messages.about_title') }}</h1>
                    <p class="fs-5 text-muted mb-3">{{ __('messages.about_desc1') }}</p>
                    <p class="fs-5 text-muted mb-4">{{ __('messages.about_desc2') }}</p>

                    <div class="vstack gap-3 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-checkbox-circle-fill text-primary fs-4 me-3"></i>
                            <span class="fs-5"><strong>{{ __('messages.about_list1') }}</strong> (Informatika, Arsitektur, Ilmu Lingkungan, Ilmu Perpustakaan)</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ri-checkbox-circle-fill text-primary fs-4 me-3"></i>
                            <span class="fs-5">{{ __('messages.about_list2') }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ri-checkbox-circle-fill text-primary fs-4 me-3"></i>
                            <span class="fs-5">{{ __('messages.about_list3') }}</span>
                        </div>
                    </div>

                    <a href="#!" class="btn btn-primary btn-lg px-5 rounded-pill">{{ __('messages.explore_button') }} <i class="ri-arrow-right-line align-bottom ms-1"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- PROGRAM STUDI -->
    <section class="section" id="program-studi">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-6 mb-3 ff-secondary fw-semibold"><span class="text-primary">{{ __('messages.our_programs') }}</span></h1>
                <p class="fs-5 text-muted col-lg-8 mx-auto">{{ __('messages.programs_desc') }}</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3">
                                <i class="ri-computer-line fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">{{ __('messages.informatics') }}</h4>
                        <p class="text-muted mb-4">{{ __('messages.informatics_desc') }}</p>
                        <a href="#" class="link-primary stretched-link">{{ __('messages.detail') }} <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3">
                                <i class="ri-building-2-line fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">{{ __('messages.architecture') }}</h4>
                        <p class="text-muted mb-4">{{ __('messages.architecture_desc') }}</p>
                        <a href="#" class="link-primary stretched-link">{{ __('messages.detail') }} <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3">
                                <i class="ri-leaf-line fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">{{ __('messages.environmental') }}</h4>
                        <p class="text-muted mb-4">{{ __('messages.environmental_desc') }}</p>
                        <a href="#" class="link-primary stretched-link">{{ __('messages.detail') }} <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3">
                                <i class="ri-book-open-line fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">{{ __('messages.library') }}</h4>
                        <p class="text-muted mb-4">{{ __('messages.library_desc') }}</p>
                        <a href="#" class="link-primary stretched-link">{{ __('messages.detail') }} <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA BERGABUNG -->
    <section class="py-5 bg-primary mt-0">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h4 class="text-dark fs-2 fw-semibold mb-2">{{ __('messages.cta_title') }}</h4>
                    <p class="text-dark-50 fs-5 mb-0">{{ __('messages.cta_desc') }}</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="#!" class="btn btn-danger btn-lg px-5 py-3 rounded-pill">{{ __('messages.register_button') }}</a>
                </div>
            </div>
        </div>
    </section>

    <!-- PRESTASI -->
    <section class="section" id="prestasi">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-5">
                    <h2 class="fw-bold mb-3">{{ __('messages.achievements_title') }}</h2>
                    <p class="text-muted fs-5">{{ __('messages.achievements_desc') }}</p>
                </div>
            </div>

            <div class="row g-4">
                <!-- Prestasi 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative">
                            <img src="assets/images/small/img-10.jpg" class="card-img-top" alt="Gemastik" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">2024</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="ri-trophy-line text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('messages.informatics') }}</h6>
                                    <small class="text-muted">Tim Rajawali</small>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Juara 1 Gemastik XIV</h5>
                            <p class="text-muted small mb-3">Aplikasi Smart Campus untuk efisiensi energi kampus.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="assets/images/users/avatar-2.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-3.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-4.jpg" class="rounded-circle" width="28" height="28" alt="Member">
                                </div>
                                <a href="#" class="text-primary small">{{ __('messages.more') }} <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Prestasi 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative">
                            <img src="assets/images/small/img-9.jpg" class="card-img-top" alt="Arsitektur" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">2024</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="ri-trophy-line text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('messages.architecture') }}</h6>
                                    <small class="text-muted">Tim Garuda</small>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Juara 2 Lomba Desain Arsitektur</h5>
                            <p class="text-muted small mb-3">Desain hunian vertikal ramah lingkungan.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="assets/images/users/avatar-5.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-6.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-7.jpg" class="rounded-circle" width="28" height="28" alt="Member">
                                </div>
                                <a href="#" class="text-primary small">{{ __('messages.more') }} <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Prestasi 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative">
                            <img src="assets/images/small/img-8.jpg" class="card-img-top" alt="Ilmu Lingkungan" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">2024</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="ri-trophy-line text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('messages.environmental') }}</h6>
                                    <small class="text-muted">Tim Hijau</small>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Juara Harapan 1 KTI</h5>
                            <p class="text-muted small mb-3">Pengolahan sampah organik menjadi energi.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="assets/images/users/avatar-8.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-9.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-10.jpg" class="rounded-circle" width="28" height="28" alt="Member">
                                </div>
                                <a href="#" class="text-primary small">{{ __('messages.more') }} <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Lihat Semua -->
            <div class="text-center mt-5">
                <a href="#" class="btn btn-outline-primary px-5 py-3 rounded-pill">{{ __('messages.view_all') }}</a>
            </div>
        </div>
    </section>

    <!-- FASILITAS -->
    <section class="section" id="fasilitas">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-5">
                    <h2 class="fw-bold mb-3">{{ __('messages.facilities_title') }}</h2>
                    <p class="text-muted fs-5">{{ __('messages.facilities_desc') }}</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <img src="assets/images/small/img-3.jpg" class="card-img-top rounded-top-4" alt="Lab Informatika" style="height: 180px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Lab. Terpadu Informatika</h5>
                            <p class="text-muted small mb-3">40 Unit PC Spesifikasi Tinggi, VR, IoT</p>
                            <span class="badge bg-primary-subtle text-primary">{{ __('messages.informatics') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <img src="assets/images/small/img-4.jpg" class="card-img-top rounded-top-4" alt="Studio Arsitektur" style="height: 180px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Studio Arsitektur</h5>
                            <p class="text-muted small mb-3">Ruang desain, workshop, peralatan lengkap</p>
                            <span class="badge bg-primary-subtle text-primary">{{ __('messages.architecture') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <img src="assets/images/small/img-5.jpg" class="card-img-top rounded-top-4" alt="Lab Lingkungan" style="height: 180px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Lab. Ilmu Lingkungan</h5>
                            <p class="text-muted small mb-3">Alat ukur kualitas air, udara, dan tanah</p>
                            <span class="badge bg-primary-subtle text-primary">{{ __('messages.environmental') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="#" class="btn btn-primary px-5 py-2 rounded-pill">{{ __('messages.explore_facilities') }}</a>
            </div>
        </div>
    </section>

    <!-- BLOG -->
    <section class="section bg-light" id="blog">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-6 mb-3 ff-secondary fw-semibold">{{ __('messages.blog_title') }}</h1>
                <p class="fs-5 text-muted col-lg-8 mx-auto">{{ __('messages.blog_desc') }}</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card">
                        <img src="assets/images/small/img-8.jpg" class="card-img-top" alt="Blog Post 1" style="height: 220px; object-fit: cover;">
                        <div class="card-body p-4">
                            <div class="d-flex text-muted mb-3">
                                <div class="me-3"><i class="ri-calendar-line me-1"></i> 30 Okt, 2024</div>
                                <div><i class="ri-message-2-line me-1"></i> 12 Komentar</div>
                            </div>
                            <h4 class="mb-3">Workshop Kecerdasan Buatan untuk Pemula</h4>
                            <p class="text-muted mb-4">Program Studi Informatika mengadakan workshop yang diikuti oleh lebih dari 100 peserta dari berbagai daerah.</p>
                            <a href="#" class="link-primary fw-semibold">{{ __('messages.read_more') }} <i class="ri-arrow-right-line"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <img src="assets/images/small/img-7.jpg" class="card-img-top" alt="Blog Post 2" style="height: 220px; object-fit: cover;">
                        <div class="card-body p-4">
                            <div class="d-flex text-muted mb-3">
                                <div class="me-3"><i class="ri-calendar-line me-1"></i> 25 Okt, 2024</div>
                                <div><i class="ri-message-2-line me-1"></i> 8 Komentar</div>
                            </div>
                            <h4 class="mb-3">Pameran Arsitektur Mahasiswa 2024</h4>
                            <p class="text-muted mb-4">Mahasiswa Arsitektur memamerkan karya desain terbaik mereka dengan tema hunian masa depan.</p>
                            <a href="#" class="link-primary fw-semibold">{{ __('messages.read_more') }} <i class="ri-arrow-right-line"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <img src="assets/images/small/img-6.jpg" class="card-img-top" alt="Blog Post 3" style="height: 220px; object-fit: cover;">
                        <div class="card-body p-4">
                            <div class="d-flex text-muted mb-3">
                                <div class="me-3"><i class="ri-calendar-line me-1"></i> 20 Okt, 2024</div>
                                <div><i class="ri-message-2-line me-1"></i> 15 Komentar</div>
                            </div>
                            <h4 class="mb-3">Seminar Nasional Lingkungan Hidup</h4>
                            <p class="text-muted mb-4">Membahas isu perubahan iklim dan peran generasi muda dalam pelestarian lingkungan.</p>
                            <a href="#" class="link-primary fw-semibold">{{ __('messages.read_more') }} <i class="ri-arrow-right-line"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA NEWSLETTER -->
    <section class="py-5 bg-primary">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h4 class="text-dark fs-2 fw-semibold mb-2">{{ __('messages.newsletter_title') }}</h4>
                    <p class="text-dark-50 fs-5 mb-0">{{ __('messages.newsletter_desc') }}</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <button class="btn btn-danger btn-lg px-5 py-3 rounded-pill">{{ __('messages.subscribe_button') }} <i class="ri-arrow-right-line align-bottom"></i></button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="custom-footer bg-dark py-5 position-relative">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mt-4">
                    <div>
                        <span class="fw-bold text-white" style="font-size: 28px;">Sainteku</span>
                        <div class="mt-4 fs-13">
                            <p class="text-white-50">{{ __('messages.footer_desc') }}</p>
                            <ul class="list-inline mb-0 footer-social-link">
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-facebook-fill"></i>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-twitter-fill"></i>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-instagram-fill"></i>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-youtube-fill"></i>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 ms-lg-auto">
                    <div class="row">
                        <div class="col-sm-4 mt-4">
                            <h5 class="text-white mb-0">{{ __('messages.quick_links') }}</h5>
                            <div class="text-muted mt-3">
                                <ul class="list-unstyled ff-secondary footer-list">
                                    <li><a href="#">{{ __('messages.about_us') }}</a></li>
                                    <li><a href="#">{{ __('messages.accreditation') }}</a></li>
                                    <li><a href="#">{{ __('messages.lecturers') }}</a></li>
                                    <li><a href="#">{{ __('messages.student_affairs') }}</a></li>
                                    <li><a href="#">{{ __('messages.alumni') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 mt-4">
                            <h5 class="text-white mb-0">{{ __('messages.study_programs_footer') }}</h5>
                            <div class="text-muted mt-3">
                                <ul class="list-unstyled ff-secondary footer-list">
                                    <li><a href="#">{{ __('messages.informatics') }}</a></li>
                                    <li><a href="#">{{ __('messages.architecture') }}</a></li>
                                    <li><a href="#">{{ __('messages.environmental') }}</a></li>
                                    <li><a href="#">{{ __('messages.library') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 mt-4">
                            <h5 class="text-white mb-0">{{ __('messages.help') }}</h5>
                            <div class="text-muted mt-3">
                                <ul class="list-unstyled ff-secondary footer-list">
                                    <li><a href="#">{{ __('messages.faq') }}</a></li>
                                    <li><a href="#">{{ __('messages.contact_us') }}</a></li>
                                    <li><a href="#">{{ __('messages.sitemap') }}</a></li>
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
                        </script> © Sainteku - {{ __('UIN Prof. K.H. Saifuddin Zuhri') }}
                    </p>
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end mt-3 mt-sm-0">
                        <ul class="list-inline mb-0 footer-list gap-4 fs-13">
                            <li class="list-inline-item">
                                <a href="#">{{ __('messages.privacy_policy') }}</a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#">{{ __('messages.terms') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- BACK TO TOP -->
    <button onclick="topFunction()" class="btn btn-primary btn-icon rounded-circle" id="back-to-top" style="position: fixed; bottom: 30px; right: 30px; display: none;">
        <i class="ri-arrow-up-line"></i>
    </button>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/pages/job-lading.init.js"></script>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 1000px; margin: 1.75rem auto;">
            <div class="modal-content overflow-hidden" style="background: transparent; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                <div class="row g-0">
                    <!-- Left Column - Branding (Desktop Only) -->
                    <div class="col-md-5 d-none d-md-flex flex-column align-items-center justify-content-center"
                        style="background: linear-gradient(145deg, #FEEB04 0%, #CBB800 100%); min-height: 550px; position: relative;">

                        <!-- Grid Pattern -->
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-10"
                            style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22 viewBox=%220 0 100 100%22><path d=%22M0 0 L100 100 M100 0 L0 100%22 stroke=%22%23000000%22 stroke-width=%221%22 opacity=%220.2%22/></svg>'); background-size: 30px 30px;">
                        </div>

                        <div class="text-center position-relative z-1 px-4 py-3">
                            <img src="{{ asset('assets/images/uin.png') }}" alt="Logo UIN Saizu" class="img-fluid mb-3" style="max-width: 95px; height: auto;">
                            <h2 class="fw-bold mb-2" style="color: #000000; font-size: 2rem;">Sainteku</h2>
                            <p class="mb-0 text-dark" style="font-size: 0.9rem;">{{ __('messages.faculty_name') }}</p>
                            <p class="mb-3 text-dark" style="font-size: 0.9rem;">{{ __('UIN Prof. K.H. Saifuddin Zuhri Purwokerto') }}</p>

                            <!-- Quote -->
                            <div class="mt-4 pt-2">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-2 opacity-75">
                                    <path d="M10 11H6V7H10V11ZM18 11H14V7H18V11Z" fill="#000000" />
                                </svg>
                                <p class="fst-italic text-dark" style="font-size: 0.85rem; max-width: 260px; margin: 0 auto; line-height: 1.5; opacity: 0.9;">
                                    "{{ __('messages.quote') }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Login Form -->
                    <div class="col-md-7 bg-white">
                        <!-- Mobile Header -->
                        <div class="d-md-none p-4 text-center border-bottom" style="background: #F9FAFB;">
                            <h2 class="fw-bold mb-1" style="color: #000000; font-size: 1.8rem;">Sainteku</h2>
                            <p class="mb-0 text-muted small">{{ __('messages.faculty_name') }}</p>
                            <p class="mb-0 text-muted small">{{ __('UIN Prof. K.H. Saifuddin Zuhri Purwokerto') }}</p>
                        </div>

                        <div class="p-4" style="max-width: 450px; margin: 0 auto;">
                            <!-- Back Link -->
                            <div class="mb-3">
                                <a href="#" class="d-inline-flex align-items-center text-sm text-gray-500 text-decoration-none" data-bs-dismiss="modal">
                                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20 20" fill="none">
                                        <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ __('messages.back_to_dashboard') }}
                                </a>
                            </div>

                            <!-- Header -->
                            <div class="mb-4">
                                <h1 class="fw-semibold text-gray-800" style="font-size: 1.8rem; margin-bottom: 0.25rem;">{{ __('messages.sign_in') }}</h1>
                                <p class="text-sm text-gray-500">{{ __('messages.enter_credentials') }}</p>
                            </div>

                            <!-- Social Buttons -->
                            <!-- <button class="btn btn-light w-100 py-2 d-flex align-items-center justify-content-center gap-2 border-0" style="background: #F3F4F6; font-size: 0.85rem;">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                                    <path d="M18.7511 10.1944C18.7511 9.47495 18.6915 8.94995 18.5626 8.40552H10.1797V11.6527H15.1003C15.0011 12.4597 14.4654 13.675 13.2749 14.4916L13.2582 14.6003L15.9087 16.6126L16.0924 16.6305C17.7788 15.1041 18.7511 12.8583 18.7511 10.1944Z" fill="#4285F4" />
                                    <path d="M10.1788 18.75C12.5895 18.75 14.6133 17.9722 16.0915 16.6305L13.274 14.4916C12.5201 15.0068 11.5081 15.3666 10.1788 15.3666C7.81773 15.3666 5.81379 13.8402 5.09944 11.7305L4.99473 11.7392L2.23868 13.8295L2.20264 13.9277C3.67087 16.786 6.68674 18.75 10.1788 18.75Z" fill="#34A853" />
                                    <path d="M5.10014 11.7305C4.91165 11.186 4.80257 10.6027 4.80257 9.99992C4.80257 9.3971 4.91165 8.81379 5.09022 8.26935L5.08523 8.1534L2.29464 6.02954L2.20333 6.0721C1.5982 7.25823 1.25098 8.5902 1.25098 9.99992C1.25098 11.4096 1.5982 12.7415 2.20333 13.9277L5.10014 11.7305Z" fill="#FBBC05" />
                                    <path d="M10.1789 4.63331C11.8554 4.63331 12.9864 5.34303 13.6312 5.93612L16.1511 3.525C14.6035 2.11528 12.5895 1.25 10.1789 1.25C6.68676 1.25 3.67088 3.21387 2.20264 6.07218L5.08953 8.26943C5.81381 6.15972 7.81776 4.63331 10.1789 4.63331Z" fill="#EB4335" />
                                </svg>
                                <span>{{ __('Sign in with Google') }}</span>
                            </button> -->
                            <!--
                            <div class="divider">
                                <div class="border-top border-gray-200" style="position: absolute; top: 50%; left: 0; right: 0;"></div>
                                <span class="px-2 bg-white text-gray-400 small position-relative">{{ __('or') }}</span>
                            </div> -->

                            <!-- Login Form -->
                            <form method="POST" action="/login" id="loginForm">
                                @csrf

                                @if($errors->any())
                                <div class="alert alert-danger py-2 mb-3 small" style="background: #FEF3F2; border-color: #F04438; color: #B42318;">
                                    @foreach($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                    @endforeach
                                </div>
                                @endif

                                <!-- Email -->
                                <div class="mb-3">
                                    <label class="form-label d-block small fw-medium text-gray-700 mb-1">
                                        {{ __('messages.email_label') }}<span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control w-100 px-3 py-2 text-sm text-gray-800 bg-transparent border border-gray-300"
                                        id="credential"
                                        name="credential"
                                        placeholder="info@gmail.com"
                                        value="{{ old('credential') }}"
                                        style="height: 42px;"
                                        required>
                                </div>

                                <!-- Password -->
                                <div class="mb-3" x-data="{ showPassword: false }">
                                    <label class="form-label d-block small fw-medium text-gray-700 mb-1">
                                        {{ __('messages.password_label') }}<span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input :type="showPassword ? 'text' : 'password'"
                                            class="form-control w-100 px-3 py-2 text-sm text-gray-800 bg-transparent border border-gray-300"
                                            id="password"
                                            name="password"
                                            placeholder="{{ __('messages.password_placeholder') }}"
                                            style="height: 42px; padding-right: 42px;"
                                            required
                                            @keydown.enter="showPassword = false">
                                        <span @click="showPassword = !showPassword"
                                            class="position-absolute text-gray-500 cursor-pointer user-select-none"
                                            style="right: 12px; top: 50%; transform: translateY(-50%); z-index: 10;"
                                            :title="showPassword ? 'Sembunyikan Password' : 'Tampilkan Password'">
                                            <svg x-show="!showPassword" width="18" height="18" viewBox="0 0 20 20" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z" fill="#98A2B3" />
                                            </svg>
                                            <svg x-show="showPassword" width="18" height="18" viewBox="0 0 20 20" fill="none" style="display: none;">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z" fill="#98A2B3" />
                                            </svg>
                                        </span>
                                    </div>
                                    <small class="text-muted d-block mt-1 small">{{ __('messages.password_hint') }}</small>
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input type="checkbox"
                                            class="form-check-input"
                                            id="remember"
                                            name="remember"
                                            style="width: 16px; height: 16px;">
                                        <label class="form-check-label small text-gray-700 ms-1" for="remember">
                                            {{ __('messages.remember_me') }}
                                        </label>
                                    </div>
                                    <a href="#" class="small text-decoration-none" style="color: #000000;"
                                        data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                                        {{ __('messages.forgot_password') }}
                                    </a>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit"
                                    class="btn w-100 py-2 small fw-medium border-0 btn-masuk">
                                    {{ __('messages.login') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lupa Password -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
            <div class="modal-content" style="border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-semibold" id="forgotPasswordModalLabel">{{ __('messages.forgot_password_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Alert untuk pesan sukses/error -->
                    <div id="forgotPasswordAlert" class="alert" style="display: none;"></div>

                    <p class="text-sm text-gray-600 mb-4">{{ __('messages.forgot_password_desc') }}</p>

                    <!-- Form Lupa Password -->
                    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label d-block small fw-medium text-gray-700 mb-1">
                                {{ __('messages.email_label') }}<span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                class="form-control w-100 px-3 py-2 text-sm text-gray-800 bg-transparent border border-gray-300"
                                name="email"
                                id="forgot_email"
                                placeholder="nama@email.com"
                                style="height: 42px;"
                                required>
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">
                            <button type="button"
                                class="btn w-100 py-2 small border"
                                style="background: #F3F4F6; color: #000;"
                                data-bs-dismiss="modal">
                                {{ __('messages.cancel') }}
                            </button>
                            <button type="submit"
                                class="btn w-100 py-2 small fw-medium border-0"
                                style="background-color: var(--saintek-primary); color: #000000;">
                                {{ __('messages.send_reset_link') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Script untuk Login -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginModal = document.getElementById('loginModal');

            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = '{{ __("messages.login") }}';

                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '{{ __("messages.processing") }}...';

                    const formData = new FormData(this);

                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
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
                                showAlert('error', data.message || '{{ __("messages.login_failed") }}');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('error', '{{ __("messages.error_occurred") }}');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                });
            }

            function showAlert(type, message) {
                const alert = document.getElementById('loginAlert');
                const alertIcon = document.getElementById('alertIcon');
                const alertMessage = document.getElementById('alertMessage');

                if (!alert || !alertIcon || !alertMessage) return;

                // Set message
                alertMessage.textContent = message;

                // Set icon and class based on type
                if (type === 'success') {
                    alertIcon.className = 'ri-checkbox-circle-line me-2 fs-5 align-middle';
                    alert.className = 'alert alert-success alert-dismissible fade show alert-popup';
                } else {
                    alertIcon.className = 'ri-error-warning-line me-2 fs-5 align-middle';
                    alert.className = 'alert alert-danger alert-dismissible fade show alert-popup';
                }

                // Show alert
                alert.style.display = 'block';

                // Auto hide after 5 seconds
                setTimeout(() => {
                    hideAlert();
                }, 5000);
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

            // Make hideAlert global
            window.hideAlert = hideAlert;

            @if($errors->any())
            if (typeof bootstrap !== 'undefined' && loginModal) {
                const modal = new bootstrap.Modal(loginModal);
                modal.show();
                showAlert('error', '{{ $errors->first() }}');
            }
            @endif
        });
    </script>

    <!-- Script untuk Forgot Password -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forgotForm = document.getElementById('forgotPasswordForm');
            const forgotAlert = document.getElementById('forgotPasswordAlert');
            const forgotModal = document.getElementById('forgotPasswordModal');

            if (forgotForm) {
                forgotForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = '{{ __("messages.send_reset_link") }}';
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '{{ __("messages.sending") }}...';

                    const formData = new FormData(this);

                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
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
                                forgotAlert.innerHTML = data.message || '{{ __("messages.email_not_found") }}';
                            }

                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (!forgotAlert) return;

                            forgotAlert.style.display = 'block';
                            forgotAlert.className = 'alert alert-danger py-2 mb-3 small';
                            forgotAlert.innerHTML = '{{ __("messages.error_occurred") }}';

                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                });
            }

            @if(session('status'))
            if (typeof bootstrap !== 'undefined' && forgotModal && forgotAlert) {
                const modal = new bootstrap.Modal(forgotModal);
                modal.show();
                forgotAlert.style.display = 'block';
                forgotAlert.className = 'alert alert-success py-2 mb-3 small';
                forgotAlert.innerHTML = '{{ session("status") }}';
            }
            @endif

            @if($errors->has('email'))
            if (typeof bootstrap !== 'undefined' && forgotModal && forgotAlert) {
                const modal = new bootstrap.Modal(forgotModal);
                modal.show();
                forgotAlert.style.display = 'block';
                forgotAlert.className = 'alert alert-danger py-2 mb-3 small';
                forgotAlert.innerHTML = '{{ $errors->first("email") }}';
            }
            @endif
        });
    </script>

    <script>
        // Back to top button functionality
        window.onscroll = function() {
            scrollFunction();
        };

        function scrollFunction() {
            const backToTopButton = document.getElementById("back-to-top");
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                backToTopButton.style.display = "block";
            } else {
                backToTopButton.style.display = "none";
            }
        }

        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>

</body>

</html>