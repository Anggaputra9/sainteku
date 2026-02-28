<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>Sainteku | UIN Prof. K.H. Saifuddin Zuhri Purwokerto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Fakultas Sains dan Teknologi UIN Saifuddin Zuhri Purwokerto" name="description" />
    <meta content="Themesbrand" name="author" />
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
            background-color: rgba(254, 235, 4, 0.1);
            color: var(--saintek-text);
        }

        /* MODAL LOGIN */
        .btn-soft-primary {
            background-color: rgba(254, 235, 4, 0.15) !important;
            color: var(--saintek-text) !important;
            border: none;
        }

        .btn-soft-primary:hover {
            background-color: var(--saintek-primary) !important;
            color: #000000 !important;
        }

        /* UTILITY CLASSES */
        .text-dark-50 {
            color: rgba(0, 0, 0, 0.7);
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
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
                        <a class="nav-link active" href="#event">Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#prestasi">Prestasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#blog">Blog</a>
                    </li>
                </ul>
                <div>
                    <button class="btn btn-soft-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="ri-user-3-line align-bottom me-1"></i> Login
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section" id="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-4 fw-semibold text-capitalize mb-3 lh-base">Fakultas Sains & Teknologi</h1>
                    <p class="lead fs-4 text-muted lh-base mb-4">Fakultas Sains dan Teknologi Universitas Islam Negeri Prof. K.H. Saifuddin Zuhri Purwokerto menghadirkan pendidikan berbasis riset dan teknologi terkini. Dengan 4 program studi unggulan: <strong>Informatika, Arsitektur, Ilmu Lingkungan, dan Ilmu Perpustakaan</strong>, kami siap melahirkan talenta yang kreatif, adaptif, dan kompetitif di era digital.</p>

                    <form action="#" class="job-panel-filter bg-white p-4 rounded-4 shadow-sm">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <input type="search" class="form-control form-control-lg" placeholder="Cari program studi...">
                            </div>
                            <div class="col-md-4">
                                <select class="form-control form-control-lg">
                                    <option value="">Pilih minat</option>
                                    <option value="Informatika">Informatika</option>
                                    <option value="Arsitektur">Arsitektur</option>
                                    <option value="Ilmu Lingkungan">Ilmu Lingkungan</option>
                                    <option value="Ilmu Perpustakaan">Ilmu Perpustakaan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary btn-lg w-100 h-100" type="button">
                                    <i class="ri-search-2-line align-bottom me-1"></i> Temukan
                                </button>
                            </div>
                        </div>
                    </form>

                    <ul class="list-inline mb-0 mt-4 fs-14">
                        <li class="list-inline-item text-danger fw-semibold"><i class="mdi mdi-tag-multiple-outline align-middle"></i> Prodi Unggulan:</li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline">Informatika,</a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline">Arsitektur,</a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline">Ilmu Lingkungan,</a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="text-decoration-underline">Ilmu Perpustakaan</a></li>
                    </ul>
                </div>
                <div class="col-lg-5">
                    <div class="position-relative text-center mt-5 mt-lg-0">
                        <div class="card p-3 shadow-lg inquiry-box mx-auto mb-4" style="max-width: 300px;">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <div class="avatar-title bg-warning-subtle text-warning rounded fs-18">
                                        <i class="ri-mail-send-line"></i>
                                    </div>
                                </div>
                                <h5 class="fs-15 lh-base mb-0">Pertanyaan Umum Seputar PMB</h5>
                            </div>
                        </div>
                        <img src="assets/images/job-profile2.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TENTANG FAKULTAS -->
    <section class="section bg-light" id="tentang">
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
                                    <p class="text-muted small mb-1">Dekan Fakultas Sains & Teknologi</p>
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
                                    <h5 class="fs-15 lh-base mb-0"><span class="text-secondary fw-semibold">1000+</span> Mahasiswa Aktif</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <h1 class="display-6 mb-4 lh-base">Temukan <span class="text-primary">Minat dan Bakat</span> Anda di Sainteku</h1>
                    <p class="fs-5 text-muted mb-3">Memilih program studi yang tepat adalah langkah awal menuju karir impian. Di Sainteku, Anda tidak hanya belajar teori, tetapi juga terlibat langsung dalam proyek riset dan inovasi.</p>
                    <p class="fs-5 text-muted mb-4">Kami memadukan keilmuan sains dan teknologi dengan nilai-nilai keislaman untuk mencetak lulusan yang berakhlak mulia dan berdaya saing global.</p>

                    <div class="vstack gap-3 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-checkbox-circle-fill text-primary fs-4 me-3"></i>
                            <span class="fs-5"><strong>4 Program Studi Unggulan</strong> (Informatika, Arsitektur, Ilmu Lingkungan, Ilmu Perpustakaan)</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ri-checkbox-circle-fill text-primary fs-4 me-3"></i>
                            <span class="fs-5">Laboratorium dan fasilitas penelitian modern.</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ri-checkbox-circle-fill text-primary fs-4 me-3"></i>
                            <span class="fs-5">Kerjasama dengan berbagai industri dan institusi.</span>
                        </div>
                    </div>

                    <a href="#!" class="btn btn-primary btn-lg px-5 rounded-pill">Jelajahi Program Studi <i class="ri-arrow-right-line align-bottom ms-1"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- PROGRAM STUDI -->
    <section class="section" id="program-studi">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-6 mb-3 ff-secondary fw-semibold"><span class="text-primary">Program Studi</span> Kami</h1>
                <p class="fs-5 text-muted col-lg-8 mx-auto">Pilih program studi yang sesuai dengan passion dan tujuan karir Anda. Setiap prodi dikelola oleh tenaga pengajar yang ahli di bidangnya.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3">
                                <i class="ri-computer-line fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">Informatika</h4>
                        <p class="text-muted mb-4">Rekayasa Perangkat Lunak, Kecerdasan Buatan, Jaringan</p>
                        <a href="#" class="link-primary stretched-link">Detail <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3">
                                <i class="ri-building-2-line fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">Arsitektur</h4>
                        <p class="text-muted mb-4">Desain Arsitektur, Perencanaan Kota, Arsitektur Islam</p>
                        <a href="#" class="link-primary stretched-link">Detail <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3">
                                <i class="ri-leaf-line fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">Ilmu Lingkungan</h4>
                        <p class="text-muted mb-4">Pengelolaan SDA, Lingkungan Hidup, Amdal</p>
                        <a href="#" class="link-primary stretched-link">Detail <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle rounded-3">
                                <i class="ri-book-open-line fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">Ilmu Perpustakaan</h4>
                        <p class="text-muted mb-4">Manajemen Informasi, Digital Library, Layanan Perpustakaan</p>
                        <a href="#" class="link-primary stretched-link">Detail <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA BERGABUNG -->
    <section class="py-5 bg-primary">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h4 class="text-dark fs-2 fw-semibold mb-2">Siap Menjadi Bagian dari Sainteku?</h4>
                    <p class="text-dark-50 fs-5 mb-0">Daftar sekarang dan mulailah perjalananmu bersama kami.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="#!" class="btn btn-danger btn-lg px-5 py-3 rounded-pill">Daftar Jadi Mahasiswa</a>
                </div>
            </div>
        </div>
    </section>

    <!-- PRESTASI - MODERN GRID LAYOUT -->
    <section class="section" id="prestasi">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-5">
                    <h2 class="fw-bold mb-3">Prestasi <span class="text-primary">Mahasiswa</span></h2>
                    <p class="text-muted fs-5">Kebanggaan Sainteku di kancah nasional dan internasional.</p>
                </div>
            </div>

            <!-- Grid Layout 3 Kolom -->
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
                                    <h6 class="fw-bold mb-0">Informatika</h6>
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
                                <a href="#" class="text-primary small">Selengkapnya <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prestasi 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative">
                            <img src="assets/images/small/img-11.jpg" class="card-img-top" alt="Krenova" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">2024</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="ri-leaf-line text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Ilmu Lingkungan</h6>
                                    <small class="text-muted">Tim Eco-Green</small>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Medali Emas Krenova</h5>
                            <p class="text-muted small mb-3">Inovasi teknologi pengolahan limbah ramah lingkungan.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="assets/images/users/avatar-5.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-6.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-7.jpg" class="rounded-circle" width="28" height="28" alt="Member">
                                </div>
                                <a href="#" class="text-primary small">Selengkapnya <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prestasi 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative">
                            <img src="assets/images/small/img-12.jpg" class="card-img-top" alt="Arsitektur" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">2023</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="ri-building-2-line text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Arsitektur</h6>
                                    <small class="text-muted">Studio Rancang</small>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Juara Desain Arsitektur Islam</h5>
                            <p class="text-muted small mb-3">Desain masjid dengan konsep ramah lingkungan.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="assets/images/users/avatar-8.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-9.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-10.jpg" class="rounded-circle" width="28" height="28" alt="Member">
                                </div>
                                <a href="#" class="text-primary small">Selengkapnya <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prestasi 4 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative">
                            <img src="assets/images/small/img-13.jpg" class="card-img-top" alt="Perpustakaan" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">2023</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="ri-book-open-line text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Ilmu Perpustakaan</h6>
                                    <small class="text-muted">Anisa Wijaya</small>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Pustakawan Berprestasi PTKIN</h5>
                            <p class="text-muted small mb-3">Inovasi sistem digital library untuk akses global.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="assets/images/users/avatar-11.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-12.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                </div>
                                <a href="#" class="text-primary small">Selengkapnya <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prestasi 5 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative">
                            <img src="assets/images/small/img-14.jpg" class="card-img-top" alt="Robotik" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">2024</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="ri-robot-line text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Informatika</h6>
                                    <small class="text-muted">Tim Robotech</small>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Juara 2 Kontes Robot Indonesia</h5>
                            <p class="text-muted small mb-3">Robot pemadam api dengan navigasi cerdas.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="assets/images/users/avatar-13.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-14.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-15.jpg" class="rounded-circle" width="28" height="28" alt="Member">
                                </div>
                                <a href="#" class="text-primary small">Selengkapnya <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prestasi 6 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative">
                            <img src="assets/images/small/img-15.jpg" class="card-img-top" alt="PKM" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">2024</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="ri-flask-line text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Ilmu Lingkungan</h6>
                                    <small class="text-muted">Tim Inovasi</small>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Pendanaan PKM 5 Bidang</h5>
                            <p class="text-muted small mb-3">5 proposal PKM berhasil mendapat pendanaan Dikti.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="assets/images/users/avatar-16.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-17.jpg" class="rounded-circle me-2" width="28" height="28" alt="Member">
                                    <img src="assets/images/users/avatar-18.jpg" class="rounded-circle" width="28" height="28" alt="Member">
                                </div>
                                <a href="#" class="text-primary small">Selengkapnya <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Lihat Semua -->
            <div class="text-center mt-5">
                <a href="#" class="btn btn-outline-primary px-5 py-3 rounded-pill">Lihat Semua Prestasi</a>
            </div>
        </div>
    </section>

    <!-- FASILITAS -->
    <section class="section" id="fasilitas">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-5">
                    <h2 class="fw-bold mb-3">Fasilitas Kampus</h2>
                    <p class="text-muted fs-5">Kami percaya bahwa lingkungan belajar yang baik akan mendukung lahirnya generasi terbaik. Oleh karena itu, Sainteku menyediakan berbagai fasilitas penunjang akademik dan non-akademik.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <img src="assets/images/small/img-3.jpg" class="card-img-top rounded-top-4" alt="Lab Informatika" style="height: 180px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Lab. Terpadu Informatika</h5>
                            <p class="text-muted small mb-3">40 Unit PC Spesifikasi Tinggi, VR, IoT</p>
                            <span class="badge bg-primary-subtle text-primary">Informatika</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <img src="assets/images/small/img-4.jpg" class="card-img-top rounded-top-4" alt="Studio Arsitektur" style="height: 180px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Studio Arsitektur</h5>
                            <p class="text-muted small mb-3">Ruang desain dan prototyping</p>
                            <span class="badge bg-warning-subtle text-warning">Arsitektur</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <img src="assets/images/small/img-5.jpg" class="card-img-top rounded-top-4" alt="Lab Lingkungan" style="height: 180px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Lab. Lingkungan</h5>
                            <p class="text-muted small mb-3">Penelitian dan analisis lingkungan</p>
                            <span class="badge bg-success-subtle text-success">Ilmu Lingkungan</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <img src="assets/images/small/img-6.jpg" class="card-img-top rounded-top-4" alt="Digital Library" style="height: 180px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Digital Library</h5>
                            <p class="text-muted small mb-3">Perpustakaan digital modern</p>
                            <span class="badge bg-info-subtle text-info">Ilmu Perpustakaan</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <img src="assets/images/small/img-7.jpg" class="card-img-top rounded-top-4" alt="Ruang Seminar" style="height: 180px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Ruang Seminar</h5>
                            <p class="text-muted small mb-3">Kapasitas 200 orang</p>
                            <span class="badge bg-secondary-subtle text-secondary">Multifungsi</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <img src="assets/images/small/img-8.jpg" class="card-img-top rounded-top-4" alt="Maker Space" style="height: 180px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">Maker Space</h5>
                            <p class="text-muted small mb-3">Ruang inovasi dan workshop</p>
                            <span class="badge bg-danger-subtle text-danger">Inovasi</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="#" class="btn btn-primary px-5 py-2 rounded-pill">Jelajahi Semua Fasilitas</a>
            </div>
        </div>
    </section>

    <!-- BLOG -->
    <section class="section bg-light" id="blog">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-6 mb-3 ff-secondary fw-semibold">Artikel & <span class="text-primary">Berita</span> Terkini</h1>
                <p class="fs-5 text-muted col-lg-8 mx-auto">Ikuti perkembangan terbaru seputar kegiatan akademik, riset, pengabdian masyarakat, dan prestasi dari Fakultas Sains dan Teknologi.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card">
                        <img src="assets/images/small/img-8.jpg" class="card-img-top" alt="" style="height: 220px; object-fit: cover;">
                        <div class="card-body p-4">
                            <div class="d-flex text-muted mb-3">
                                <div class="me-3"><i class="ri-calendar-line me-1"></i> 30 Okt, 2024</div>
                                <div><i class="ri-message-2-line me-1"></i> 12 Komentar</div>
                            </div>
                            <h4 class="mb-3">Workshop Kecerdasan Buatan untuk Pemula</h4>
                            <p class="text-muted mb-4">Program Studi Informatika mengadakan workshop yang diikuti oleh lebih dari 100 peserta dari berbagai daerah.</p>
                            <a href="#" class="link-primary fw-semibold">Baca Selengkapnya <i class="ri-arrow-right-line"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <img src="assets/images/small/img-6.jpg" class="card-img-top" alt="" style="height: 220px; object-fit: cover;">
                        <div class="card-body p-4">
                            <div class="d-flex text-muted mb-3">
                                <div class="me-3"><i class="ri-calendar-line me-1"></i> 15 Okt, 2024</div>
                                <div><i class="ri-message-2-line me-1"></i> 8 Komentar</div>
                            </div>
                            <h4 class="mb-3">Pameran Karya Arsitektur "Ruang dan Jiwa"</h4>
                            <p class="text-muted mb-4">Mahasiswa Arsitektur memamerkan maket dan desain inovatif yang mengangkat kearifan lokal dan nilai-nilai Islam.</p>
                            <a href="#" class="link-primary fw-semibold">Baca Selengkapnya <i class="ri-arrow-right-line"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <img src="assets/images/small/img-9.jpg" class="card-img-top" alt="" style="height: 220px; object-fit: cover;">
                        <div class="card-body p-4">
                            <div class="d-flex text-muted mb-3">
                                <div class="me-3"><i class="ri-calendar-line me-1"></i> 5 Okt, 2024</div>
                                <div><i class="ri-message-2-line me-1"></i> 15 Komentar</div>
                            </div>
                            <h4 class="mb-3">Kuliah Tamu: Pengelolaan Perpustakaan Digital</h4>
                            <p class="text-muted mb-4">Menghadirkan praktisi dari Perpustakaan Nasional untuk berbagi pengalaman tentang transformasi perpustakaan di era digital.</p>
                            <a href="#" class="link-primary fw-semibold">Baca Selengkapnya <i class="ri-arrow-right-line"></i></a>
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
                    <h4 class="text-dark fs-2 fw-semibold mb-2">Dapatkan Informasi Terbaru!</h4>
                    <p class="text-dark-50 fs-5 mb-0">Berlangganan newsletter kami untuk info PMB, beasiswa, dan kegiatan kampus.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <button class="btn btn-danger btn-lg px-5 py-3 rounded-pill">Berlangganan Sekarang <i class="ri-arrow-right-line align-bottom"></i></button>
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
                            <p class="text-white-50">Fakultas Sains dan Teknologi UIN Prof. K.H. Saifuddin Zuhri Purwokerto. Mencerdaskan dan memajukan bangsa melalui pendidikan sains dan teknologi yang berlandaskan nilai-nilai Islam.</p>
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
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-twitter-x-fill"></i>
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
                            <h5 class="text-white mb-0">Tautan</h5>
                            <div class="text-muted mt-3">
                                <ul class="list-unstyled ff-secondary footer-list">
                                    <li><a href="#">Tentang Kami</a></li>
                                    <li><a href="#">Akreditasi</a></li>
                                    <li><a href="#">Dosen & Staf</a></li>
                                    <li><a href="#">Kemahasiswaan</a></li>
                                    <li><a href="#">Alumni</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 mt-4">
                            <h5 class="text-white mb-0">Program Studi</h5>
                            <div class="text-muted mt-3">
                                <ul class="list-unstyled ff-secondary footer-list">
                                    <li><a href="#">Informatika</a></li>
                                    <li><a href="#">Arsitektur</a></li>
                                    <li><a href="#">Ilmu Lingkungan</a></li>
                                    <li><a href="#">Ilmu Perpustakaan</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 mt-4">
                            <h5 class="text-white mb-0">Bantuan</h5>
                            <div class="text-muted mt-3">
                                <ul class="list-unstyled ff-secondary footer-list">
                                    <li><a href="#">FAQ Penerimaan</a></li>
                                    <li><a href="#">Hubungi Kami</a></li>
                                    <li><a href="#">Sitemap</a></li>
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
                        </script> © Sainteku - UIN Prof. K.H. Saifuddin Zuhri Purwokerto
                    </p>
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end mt-3 mt-sm-0">
                        <ul class="list-inline mb-0 footer-list gap-4 fs-13">
                            <li class="list-inline-item">
                                <a href="#">Kebijakan Privasi</a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#">Syarat & Ketentuan</a>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="loginModalLabel">Masuk ke Sainteku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="loginError" class="alert alert-danger" role="alert" style="display: none;">
                        @if($errors->any())
                        @foreach($errors->all() as $error)
                        <p class="mb-1">{{ $error }}</p>
                        @endforeach
                        @endif
                    </div>

                    <form id="loginForm" action="/login" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="credential">Email / ID Pengguna</label>
                            <input
                                id="credential"
                                name="credential"
                                type="text"
                                class="form-control"
                                placeholder="Contoh: u0001 atau test@example.com"
                                value="{{ old('credential') }}"
                                required />
                            <small class="text-muted d-block mt-2">
                                <strong>Test:</strong> <br>
                                ID: <code>u0001</code> atau Email: <code>test@example.com</code> <br>
                                Password: <code>password</code>
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <div class="position-relative">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="form-control pe-5"
                                    placeholder="Password"
                                    required />
                                <button
                                    type="button"
                                    class="btn position-absolute end-0 top-0 h-100 border-0 bg-transparent"
                                    id="togglePassword"
                                    style="z-index: 10; outline: none; box-shadow: none;">
                                    <i class="ri-eye-off-line" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="remember"
                                name="remember" />
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            togglePassword.addEventListener('click', function(e) {
                e.preventDefault();

                // Toggle tipe input
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle icon
                if (type === 'password') {
                    toggleIcon.classList.remove('ri-eye-line');
                    toggleIcon.classList.add('ri-eye-off-line');
                } else {
                    toggleIcon.classList.remove('ri-eye-off-line');
                    toggleIcon.classList.add('ri-eye-line');
                }

                // Fokus kembali ke input
                passwordInput.focus();
            });

            // Optional: tekan Enter di input password
            passwordInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('loginForm').submit();
                }
            });
        });
    </script>
</body>

</html>