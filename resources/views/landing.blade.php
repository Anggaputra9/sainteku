<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<!-- Mirrored from themesbrand.com/velzon/html/master/job-landing.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 12 Aug 2024 07:47:11 GMT -->

<head>

    <meta charset="utf-8" />
    <title>Sainteku | UIN Prof. K.H. Saifuddin Zuhri Purwokerto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Fakultas Sains dan Teknologi UIN Saifuddin Zuhri Purwokerto" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets\images\uin.png">

    <!-- Swiper slider css -->
    <link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App Css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom Css -->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
 

    <!-- Style tambahan untuk warna kustom -->
    <style>
        :root {
            --saintek-primary: #FEEB04;
            /* Warna utama yang benar */
            --saintek-primary-rgb: 254, 235, 4;
            /* RGB equivalent untuk efek */
            --saintek-primary-dark: #CBB800;
            /* Warna gelap untuk hover */
        }

        /* Background primary */
        .bg-primary {
            background-color: var(--saintek-primary) !important;
        }

        /* Button primary */
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

        .btn-primary:focus,
        .btn-primary:active {
            background-color: var(--saintek-primary-dark) !important;
            border-color: var(--saintek-primary-dark) !important;
            color: #000000 !important;
        }

        /* Button outline primary */
        .btn-outline-primary {
            border-color: var(--saintek-primary) !important;
            color: #856B2B !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--saintek-primary) !important;
            color: #000000 !important;
        }

        /* Text primary */
        .text-primary {
            color: #856B2B !important;
            /* Warna gelap untuk kontras */
        }

        a.text-primary:hover {
            color: #5F4E20 !important;
        }

        /* Link primary */
        a.link-primary {
            color: #856B2B !important;
        }

        a.link-primary:hover {
            color: #5F4E20 !important;
        }

        /* Badge primary */
        .badge-primary {
            background-color: var(--saintek-primary) !important;
            color: #000000 !important;
        }

        /* Border primary */
        .border-primary {
            border-color: var(--saintek-primary) !important;
        }

        /* Navbar active link */
        .navbar-landing .navbar-nav .nav-link.active {
            color: #856B2B !important;
        }

        /* Link success (diganti primary) */
        .link-success {
            color: #856B2B !important;
        }

        .link-success:hover {
            color: #5F4E20 !important;
        }

        /* Swiper pagination */
        .swiper-pagination-bullet.swiper-pagination-bullet-active {
            background-color: var(--saintek-primary) !important;
        }

        /* Text warning (untuk rating) */
        .text-warning {
            color: #FEEB04 !important;
        }

        /* Background soft primary */
        .bg-primary-subtle {
            background-color: rgba(254, 235, 4, 0.2) !important;
        }

        /* Text primary subtle */
        .text-primary-subtle {
            color: #856B2B !important;
        }
    </style>
</head>

<body data-bs-spy="scroll" data-bs-target="#navbar-example">

    <!-- Begin page -->
    <div class="layout-wrapper landing">
        <nav class="navbar navbar-expand-lg navbar-landing fixed-top job-navbar" id="navbar">
            <div class="container-fluid custom-container">
                <a class="navbar-brand fw-bold text-dark" href="index.html" style="font-size: 32px;">
                    Sainteku
                </a>

                <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="mdi mdi-menu"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
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

                    <div class="">
                        <button class="btn btn-soft-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="ri-user-3-line align-bottom me-1"></i> Login 
                        </button>
                    </div>
                </div>

            </div>
        </nav>
        <!-- end navbar -->

        <!-- start hero section -->
        <section class="section job-hero-section bg-light pb-0" id="hero">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-6">
                        <div>
                            <h1 class="display-6 fw-semibold text-capitalize mb-3 lh-base">Fakultas Sains & Teknologi </h1>
                            <p class="lead text-muted lh-base mb-4">Fakultas Sains dan Teknologi Universitas Islam Negeri Prof. K.H. Saifuddin Zuhri Purwokerto menghadirkan pendidikan berbasis riset dan teknologi terkini. Dengan 4 program studi unggulan: <strong>Informatika, Arsitektur, Ilmu Lingkungan, dan Ilmu Perpustakaan</strong>, kami siap melahirkan talenta yang kreatif, adaptif, dan kompetitif di era digital.</p>
                            <form action="#" class="job-panel-filter">
                                <div class="row g-md-0 g-2">
                                    <div class="col-md-4">
                                        <div>
                                            <input type="search" id="job-title" class="form-control filter-input-box" placeholder="Cari program studi...">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-4">
                                        <div>
                                            <select class="form-control" data-choices>
                                                <option value="">Pilih minat</option>
                                                <option value="Full Time">Informatika</option>
                                                <option value="Part Time">Arsitektur</option>
                                                <option value="Freelance">Ilmu Lingkungan</option>
                                                <option value="Internship">Ilmu Perpustakaan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-4">
                                        <div class="h-100">
                                            <button class="btn btn-primary submit-btn w-100 h-100" type="submit"><i class="ri-search-2-line align-bottom me-1"></i> Temukan</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>

                            <ul class="treding-keywords list-inline mb-0 mt-3 fs-13">
                                <li class="list-inline-item text-danger fw-semibold"><i class="mdi mdi-tag-multiple-outline align-middle"></i> Prodi Unggulan:</li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Informatika,</a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Arsitektur,</a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Ilmu Lingkungan,</a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Ilmu Perpustakaan</a></li>
                            </ul>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-lg-4">
                        <div class="position-relative home-img text-center mt-5 mt-lg-0">
                            <div class="card p-3 rounded shadow-lg inquiry-box">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm flex-shrink-0 me-3">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded fs-18">
                                            <i class="ri-mail-send-line"></i>
                                        </div>
                                    </div>
                                    <h5 class="fs-15 lh-base mb-0">Pertanyaan Umum Seputar PMB</h5>
                                </div>
                            </div>
                            <img src="assets/images/job-profile2.png" alt="" class="user-img">

                            <div class="circle-effect">
                                <div class="circle"></div>
                                <div class="circle2"></div>
                                <div class="circle3"></div>
                                <div class="circle4"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end hero section -->

        <section class="section" id="process">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h1 class="mb-3 ff-secondary fw-semibold lh-base">Alur <span class="text-primary">Penerimaan</span> Mahasiswa Baru</h1>
                            <p class="text-muted">Bergabunglah dengan Sainteku melalui proses yang mudah dan transparan. Ikuti langkah-langkah berikut untuk memulai perjalanan akademik Anda.</p>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!--end row-->
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="card shadow-lg">
                            <div class="card-body p-4">
                                <h1 class="fw-bold display-5 ff-secondary mb-4 text-success position-relative">
                                    <div class="job-icon-effect"></div>
                                    <span>1</span>
                                </h1>
                                <h6 class="fs-17 mb-2">Daftar Akun</h6>
                                <p class="text-muted mb-0 fs-15">Buat akun di portal PMB Sainteku.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card shadow-none">
                            <div class="card-body p-4">
                                <h1 class="fw-bold display-5 ff-secondary mb-4 text-success position-relative">
                                    <div class="job-icon-effect"></div>
                                    <span>2</span>
                                </h1>
                                <h6 class="fs-17 mb-2">Isi Data & Pilih Prodi</h6>
                                <p class="text-muted mb-0 fs-15">Lengkapi formulir dan pilih salah satu dari 4 prodi yang tersedia.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card shadow-none">
                            <div class="card-body p-4">
                                <h1 class="fw-bold display-5 ff-secondary mb-4 text-success position-relative">
                                    <div class="job-icon-effect"></div>
                                    <span>3</span>
                                </h1>

                                <h6 class="fs-17 mb-2">Ikuti Seleksi</h6>
                                <p class="text-muted mb-0 fs-15">Lalui tahapan seleksi sesuai jalur yang dipilih.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card shadow-none">
                            <div class="card-body p-4">
                                <h1 class="fw-bold display-5 ff-secondary mb-4 text-success position-relative">
                                    <div class="job-icon-effect"></div>
                                    <span>4</span>
                                </h1>
                                <h6 class="fs-17 mb-2">Daftar Ulang</h6>
                                <p class="text-muted mb-0 fs-15">Lakukan daftar ulang dan mulai perkuliahan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end container-->
        </section>

        <!-- start features -->
        <section class="section">
            <div class="container">
                <div class="row align-items-center justify-content-lg-between justify-content-center gy-4">
                    <div class="col-lg-5 col-sm-7">
                        <div class="about-img-section mb-5 mb-lg-0 text-center">
                            <div class="card rounded shadow-lg inquiry-box d-none d-lg-block">
                                <div class="card-body d-flex align-items-center">
                                    <div class="avatar-sm flex-shrink-0 me-3">
                                        <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-18">
                                            <i class="ri-briefcase-2-line"></i>
                                        </div>
                                    </div>
                                    <h5 class="fs-15 lh-base mb-0"><span class="text-secondary fw-semibold">1000+</span> Mahasiswa Aktif</h5>
                                </div>
                            </div>

                            <div class="card feedback-box">
                                <div class="card-body d-flex shadow-lg">
                                    <div class="flex-shrink-0 me-3">
                                        <img src="assets/images/image.png" alt="" class="avatar-sm rounded-circle">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fs-14 lh-base mb-0">Prof. Dr. Kholid Mawardi, M. Hum.</h5>
                                        <p class="text-muted fs-11 mb-1">Dekan Fakultas Sains & Teknologi</p>

                                        <div class="text-warning">
                                            <i class="ri-star-s-fill"></i>
                                            <i class="ri-star-s-fill"></i>
                                            <i class="ri-star-s-fill"></i>
                                            <i class="ri-star-s-fill"></i>
                                            <i class="ri-star-s-fill"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <img src="assets/images/about.jpg" alt="" class="img-fluid mx-auto rounded-3" />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-muted">
                            <h1 class="mb-3 lh-base">Temukan <span class="text-primary">Minat dan Bakat</span> Anda di Sainteku</h1>
                            <p class="ff-secondary fs-16 mb-2">Memilih program studi yang tepat adalah langkah awal menuju karir impian. Di Sainteku, Anda tidak hanya belajar teori, tetapi juga terlibat langsung dalam proyek riset dan inovasi.</p>
                            <p class="ff-secondary fs-16">Kami memadukan keilmuan sains dan teknologi dengan nilai-nilai keislaman untuk mencetak lulusan yang berakhlak mulia dan berdaya saing global.</p>

                            <div class="vstack gap-2 mb-4 pb-1">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2">
                                        <div class="avatar-xs icon-effect">
                                            <div class="avatar-title bg-transparent text-success rounded-circle h2">
                                                <i class="ri-check-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0"><strong>4 Program Studi Unggulan</strong> (Informatika, Arsitektur, Ilmu Lingkungan, Ilmu Perpustakaan)</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2">
                                        <div class="avatar-xs icon-effect">
                                            <div class="avatar-title bg-transparent text-success rounded-circle h2">
                                                <i class="ri-check-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0">Laboratorium dan fasilitas penelitian modern.</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2">
                                        <div class="avatar-xs icon-effect">
                                            <div class="avatar-title bg-transparent text-success rounded-circle h2">
                                                <i class="ri-check-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0">Kerjasama dengan berbagai industri dan institusi.</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <a href="#!" class="btn btn-primary">Jelajahi Program Studi <i class="ri-arrow-right-line align-bottom ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end features -->

        <!-- start services -->
        <section class="section bg-light" id="categories">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="text-center mb-5">
                            <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base"><span class="text-primary">Program Studi</span> Kami</h1>
                            <p class="text-muted">Pilih program studi yang sesuai dengan passion dan tujuan karir Anda. Setiap prodi dikelola oleh tenaga pengajar yang ahli di bidangnya.</p>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-lg-3 col-md-6">
                        <div class="card shadow-none text-center py-3">
                            <div class="card-body py-4">
                                <div class="avatar-sm position-relative mb-4 mx-auto">
                                    <div class="job-icon-effect"></div>
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-computer-line fs-1"></i>
                                    </div>
                                </div>
                                <a href="#!" class="stretched-link">
                                    <h5 class="fs-17 pt-1">Informatika</h5>
                                </a>
                                <p class="mb-0 text-muted">Rekayasa Perangkat Lunak, Kecerdasan Buatan, Jaringan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card shadow-none text-center py-3">
                            <div class="card-body py-4">
                                <div class="avatar-sm position-relative mb-4 mx-auto">
                                    <div class="job-icon-effect"></div>
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-building-2-line fs-1"></i>
                                    </div>
                                </div>
                                <a href="#!" class="stretched-link">
                                    <h5 class="fs-17 pt-1">Arsitektur</h5>
                                </a>
                                <p class="mb-0 text-muted">Desain Arsitektur, Perencanaan Kota, Arsitektur Islam</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card shadow-none text-center py-3">
                            <div class="card-body py-4">
                                <div class="avatar-sm mb-4 mx-auto position-relative">
                                    <div class="job-icon-effect"></div>
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-leaf-line fs-1"></i>
                                    </div>
                                </div>
                                <a href="#!" class="stretched-link">
                                    <h5 class="fs-17 pt-1">Ilmu Lingkungan</h5>
                                </a>
                                <p class="mb-0 text-muted">Pengelolaan SDA, Lingkungan Hidup, Amdal</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card shadow-none text-center py-3">
                            <div class="card-body py-4">
                                <div class="avatar-sm position-relative mb-4 mx-auto">
                                    <div class="job-icon-effect"></div>
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-book-open-line fs-1"></i>
                                    </div>
                                </div>
                                <a href="#!" class="stretched-link">
                                    <h5 class="fs-17 pt-1">Ilmu Perpustakaan</h5>
                                </a>
                                <p class="mb-0 text-muted">Manajemen Informasi, Digital Library, Layanan Perpustakaan</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end services -->

        <!-- start cta -->
        <section class="py-5 bg-primary position-relative">
            <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-sm">
                        <div>
                            <h4 class="text-dark mb-2">Siap Menjadi Bagian dari Sainteku?</h4>
                            <p class="text-dark-50 mb-0" style="color: #333 !important;">Daftar sekarang dan mulailah perjalananmu bersama kami.</p>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-sm-auto">
                        <div>
                            <a href="#!" class="btn bg-gradient btn-danger" style="background-color: #dc3545; border-color: #dc3545;">Daftar Jadi Mahasiswa</a>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end cta -->

        <section class="section" id="findJob">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="text-center mb-5">
                            <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base"><span class="text-primary">Prestasi</span> Terkini Mahasiswa</h1>
                            <p class="text-muted">Mahasiswa Sainteku secara konsisten meraih prestasi di tingkat nasional dan internasional. Berikut adalah sebagian kecil dari capaian membanggakan mereka.</p>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-warning-subtle rounded">
                                            <i class="ri-trophy-line fs-3 text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <a href="#!">
                                            <h5>Juara 1 Gemastik XIV</h5>
                                        </a>
                                        <ul class="list-inline text-muted mb-3">
                                            <li class="list-inline-item">
                                                <i class="ri-group-line align-bottom me-1"></i> Tim Rajawali (Informatika)
                                            </li>
                                            <li class="list-inline-item">
                                                <i class="ri-map-pin-2-line align-bottom me-1"></i> Jakarta
                                            </li>
                                            <li class="list-inline-item">
                                                <i class="ri-calendar-line align-bottom me-1"></i> 2024
                                            </li>
                                        </ul>
                                        <div class="hstack gap-2">
                                            <span class="badge bg-success-subtle text-success">Informatika</span>
                                            <span class="badge bg-danger-subtle text-danger">UI/UX</span>
                                            <span class="badge bg-primary-subtle text-primary-subtle">Kategori Pengembangan Aplikasi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle rounded">
                                            <i class="ri-medal-line fs-3 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <a href="#!">
                                            <h5>Medali Emas Krenova</h5>
                                        </a>
                                        <ul class="list-inline text-muted mb-3">
                                            <li class="list-inline-item">
                                                <i class="ri-group-line align-bottom me-1"></i> Tim Eco-Green (Ilmu Lingkungan)
                                            </li>
                                            <li class="list-inline-item">
                                                <i class="ri-map-pin-2-line align-bottom me-1"></i> Purwokerto
                                            </li>
                                            <li class="list-inline-item">
                                                <i class="ri-calendar-line align-bottom me-1"></i> 2024
                                            </li>
                                        </ul>
                                        <div class="hstack gap-2">
                                            <span class="badge bg-primary-subtle text-primary-subtle">Ilmu Lingkungan</span>
                                            <span class="badge bg-secondary-subtle text-secondary">Inovasi Teknologi Tepat Guna</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle rounded">
                                            <i class="ri-award-line fs-3 text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <a href="#!">
                                            <h5>Juara Desain Arsitektur Islam</h5>
                                        </a>
                                        <ul class="list-inline text-muted mb-3">
                                            <li class="list-inline-item">
                                                <i class="ri-group-line align-bottom me-1"></i> Studio Rancang (Arsitektur)
                                            </li>
                                            <li class="list-inline-item">
                                                <i class="ri-map-pin-2-line align-bottom me-1"></i> Yogyakarta
                                            </li>
                                            <li class="list-inline-item">
                                                <i class="ri-calendar-line align-bottom me-1"></i> 2023
                                            </li>
                                        </ul>
                                        <div class="hstack gap-2">
                                            <span class="badge bg-warning-subtle text-warning">Arsitektur</span>
                                            <span class="badge bg-info-subtle text-info">Desain</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-success-subtle rounded">
                                            <i class="ri-cup-line fs-3 text-success"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <a href="#!">
                                            <h5>Pustakawan Berprestasi Tingkat PTKIN</h5>
                                        </a>
                                        <ul class="list-inline text-muted mb-3">
                                            <li class="list-inline-item">
                                                <i class="ri-group-line align-bottom me-1"></i> Anisa Wijaya, S.IP.
                                            </li>
                                            <li class="list-inline-item">
                                                <i class="ri-map-pin-2-line align-bottom me-1"></i> Makassar
                                            </li>
                                            <li class="list-inline-item">
                                                <i class="ri-calendar-line align-bottom me-1"></i> 2023
                                            </li>
                                        </ul>
                                        <div class="hstack gap-2">
                                            <span class="badge bg-success-subtle text-success">Ilmu Perpustakaan</span>
                                            <span class="badge bg-danger-subtle text-danger">Inovasi Layanan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="text-center mt-4">
                            <a href="#!" class="btn btn-ghost-primary">Lihat Prestasi Lainnya <i class="ri-arrow-right-line align-bottom"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- start find jobs -->
        <section class="section">
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="text-muted mt-5 mt-lg-0">
                            <h5 class="fs-12 text-uppercase text-success">Fasilitas Kampus</h5>
                            <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base">Dukung <span class="text-primary">Proses Belajar</span> dengan Fasilitas Modern</h1>
                            <p class="ff-secondary mb-2">Kami percaya bahwa lingkungan belajar yang baik akan mendukung lahirnya generasi terbaik. Oleh karena itu, Sainteku menyediakan berbagai fasilitas penunjang akademik dan non-akademik.</p>
                            <p class="mb-4 ff-secondary">Mulai dari laboratorium komputer dengan spesifikasi tinggi, studio arsitektur, laboratorium lingkungan terpadu, hingga perpustakaan digital yang nyaman, semua tersedia untuk mendukung inovasi dan kreativitas Anda.</p>

                            <div class="mt-4">
                                <a href="index.html" class="btn btn-primary">Jelajahi Fasilitas <i class="ri-arrow-right-line align-middle ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-lg-4 col-sm-7 col-10 ms-lg-auto mx-auto order-1 order-lg-2">
                        <div>
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <button type="button" class="btn btn-icon btn-soft-primary float-end" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                                    <div class="avatar-sm mb-4">
                                        <div class="avatar-title bg-secondary-subtle rounded">
                                            <i class="ri-flask-line fs-3 text-secondary"></i>
                                        </div>
                                    </div>
                                    <a href="#!">
                                        <h5>Lab. Terpadu Informatika</h5>
                                    </a>
                                    <p class="text-muted">Fakultas Sains & Teknologi</p>

                                    <div class="d-flex gap-4 mb-3">
                                        <div>
                                            <i class="ri-cpu-line text-primary me-1 align-bottom"></i> 40 Unit PC Spec Tinggi
                                        </div>
                                    </div>

                                    <p class="text-muted">Digunakan untuk praktikum pemrograman, kecerdasan buatan, dan riset mahasiswa. Terdapat juga perangkat VR dan IoT.</p>

                                    <div class="hstack gap-2">
                                        <span class="badge bg-success-subtle text-success">Informatika</span>
                                        <span class="badge bg-primary-subtle text-primary-subtle">AI Lab</span>
                                        <span class="badge bg-danger-subtle text-danger">24 Jam</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-lg bg-info mb-0 features-company-widgets rounded-3">
                                <div class="card-body">
                                    <h5 class="text-white fs-16 mb-4">Fasilitas Unggulan Lainnya</h5>

                                    <div class="d-flex gap-1 flex-wrap">
                                        <span class="badge bg-light text-dark p-2">Studio Arsitektur</span>
                                        <span class="badge bg-light text-dark p-2">Lab. Lingkungan</span>
                                        <span class="badge bg-light text-dark p-2">Digital Library</span>
                                        <span class="badge bg-light text-dark p-2">Ruang Seminar</span>
                                        <span class="badge bg-light text-dark p-2">Maker Space</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end find jobs -->

        <!-- start candidates -->
        <section class="section bg-light" id="candidates">
            <div class="bg-overlay bg-overlay-pattern"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base">Dosen <span class="text-primary">Ahli</span> dan Berpengalaman</h1>
                            <p class="text-muted mb-4">Proses belajar mengajar ditangani oleh para dosen yang tidak hanya ahli di bidangnya, tetapi juga aktif dalam riset dan pengabdian masyarakat.</p>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="swiper candidate-swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="card text-center">
                                        <div class="card-body p-4">
                                            <img src="assets/images/users/avatar-2.jpg" alt="" class="rounded-circle avatar-md mx-auto d-block">
                                            <h5 class="fs-17 mt-3 mb-2">Dr. Eng. Rina Kurniawati, M.Kom.</h5>
                                            <p class="text-muted fs-13 mb-3">Dosen Informatika</p>

                                            <p class="text-muted mb-4 fs-14">
                                                <i class="ri-graduation-cap-line text-primary me-1 align-bottom"></i> Pakar Kecerdasan Buatan
                                            </p>

                                            <a href="#!" class="btn btn-primary">Lihat Profil</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card text-center">
                                        <div class="card-body p-4">
                                            <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle avatar-md mx-auto d-block">
                                            <h5 class="fs-17 mt-3 mb-2">Dr. Ir. Budi Hartono, M.T.</h5>
                                            <p class="text-muted fs-13 mb-3">Dosen Arsitektur</p>

                                            <p class="text-muted mb-4 fs-14">
                                                <i class="ri-graduation-cap-line text-primary me-1 align-bottom"></i> Arsitektur Islam & Perkotaan
                                            </p>

                                            <a href="#!" class="btn btn-primary">Lihat Profil</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card text-center">
                                        <div class="card-body p-4">
                                            <img src="assets/images/users/avatar-10.jpg" alt="" class="rounded-circle avatar-md mx-auto d-block">
                                            <h5 class="fs-17 mt-3 mb-2">Prof. Dr. Dewi Lestari, M.Si.</h5>
                                            <p class="text-muted fs-13 mb-3">Dosen Ilmu Lingkungan</p>

                                            <p class="text-muted mb-4 fs-14">
                                                <i class="ri-graduation-cap-line text-primary me-1 align-bottom"></i> Ahli Pengelolaan Limbah
                                            </p>

                                            <a href="#!" class="btn btn-primary">Lihat Profil</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card text-center">
                                        <div class="card-body p-4">
                                            <img src="assets/images/users/avatar-8.jpg" alt="" class="rounded-circle avatar-md mx-auto d-block" />
                                            <h5 class="fs-17 mt-3 mb-2">Dr. Faisal Riza, S.IP., M.IP.</h5>
                                            <p class="text-muted fs-13 mb-3">Dosen Ilmu Perpustakaan</p>

                                            <p class="text-muted mb-4 fs-14">
                                                <i class="ri-graduation-cap-line text-primary me-1 align-bottom"></i> Pakar Digital Library
                                            </p>

                                            <a href="#!" class="btn btn-primary">Lihat Profil</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end container -->
        </section>
        <!-- end candidates -->

        <!-- start blog -->
        <section class="section" id="blog">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base">Artikel & <span class="text-primary">Berita</span> Terkini</h1>
                            <p class="text-muted mb-4">Ikuti perkembangan terbaru seputar kegiatan akademik, riset, pengabdian masyarakat, dan prestasi dari Fakultas Sains dan Teknologi.</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <img src="assets/images/small/img-8.jpg" alt="" class="img-fluid rounded" />
                            </div>
                            <div class="card-body">
                                <ul class="list-inline fs-14 text-muted">
                                    <li class="list-inline-item">
                                        <i class="ri-calendar-line align-bottom me-1"></i> 30 Okt, 2024
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="ri-message-2-line align-bottom me-1"></i> 12 Komentar
                                    </li>
                                </ul>
                                <a href="javascript:void(0);">
                                    <h5>Workshop Kecerdasan Buatan untuk Pemula</h5>
                                </a>
                                <p class="text-muted fs-14">Program Studi Informatika mengadakan workshop yang diikuti oleh lebih dari 100 peserta dari berbagai daerah.</p>

                                <div>
                                    <a href="#!" class="link-success">Baca Selengkapnya <i class="ri-arrow-right-line align-bottom ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <img src="assets/images/small/img-6.jpg" alt="" class="img-fluid rounded" />
                            </div>
                            <div class="card-body">
                                <ul class="list-inline fs-14 text-muted">
                                    <li class="list-inline-item">
                                        <i class="ri-calendar-line align-bottom me-1"></i> 15 Okt, 2024
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="ri-message-2-line align-bottom me-1"></i> 8 Komentar
                                    </li>
                                </ul>
                                <a href="javascript:void(0);">
                                    <h5>Pameran Karya Arsitektur "Ruang dan Jiwa"</h5>
                                </a>
                                <p class="text-muted fs-14">Mahasiswa Arsitektur memamerkan maket dan desain inovatif yang mengangkat kearifan lokal dan nilai-nilai Islam.</p>

                                <div>
                                    <a href="#!" class="link-success">Baca Selengkapnya <i class="ri-arrow-right-line align-bottom ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <img src="assets/images/small/img-9.jpg" alt="" class="img-fluid rounded" />
                            </div>
                            <div class="card-body">
                                <ul class="list-inline fs-14 text-muted">
                                    <li class="list-inline-item">
                                        <i class="ri-calendar-line align-bottom me-1"></i> 5 Okt, 2024
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="ri-message-2-line align-bottom me-1"></i> 15 Komentar
                                    </li>
                                </ul>
                                <a href="javascript:void(0);">
                                    <h5>Kuliah Tamu: Pengelolaan Perpustakaan Digital</h5>
                                </a>
                                <p class="text-muted fs-14">Menghadirkan praktisi dari Perpustakaan Nasional untuk berbagi pengalaman tentang transformasi perpustakaan di era digital.</p>

                                <div>
                                    <a href="#!" class="link-success">Baca Selengkapnya <i class="ri-arrow-right-line align-bottom ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end container -->
        </section>
        <!-- end blog -->

        <!-- start cta -->
        <section class="py-5 bg-primary position-relative">
            <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-sm">
                        <div>
                            <h4 class="text-dark fw-semibold">Dapatkan Informasi Terbaru!</h4>
                            <p class="text-dark text-opacity-75 mb-0">Berlangganan newsletter kami untuk info PMB, beasiswa, dan kegiatan kampus.</p>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-sm-auto">
                        <button class="btn btn-danger" type="button" style="background-color: #dc3545; border-color: #dc3545;">Berlangganan Sekarang <i class="ri-arrow-right-line align-bottom"></i></button>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end cta -->

        <!-- Start footer -->
        <footer class="custom-footer bg-dark py-5 position-relative">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mt-4">
                        <div>
                            <div>
                                <span class="fw-bold text-white" style="font-size: 28px;">Sainteku</span>
                            </div>
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
                        <div>
                            <p class="copy-rights mb-0 text-white-50">
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> © Sainteku - UIN Prof. K.H. Saifuddin Zuhri Purwokerto
                            </p>
                        </div>
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
        <!-- end footer -->

        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-primary btn-icon landing-back-top" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

    </div>
    <!-- end layout wrapper -->

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!--Swiper slider js-->
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>

    <!--job landing init -->
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
                  required
                />
                <small class="text-muted d-block mt-2">
                  <strong>Test:</strong> <br>
                  ID: <code>u0001</code> atau Email: <code>test@example.com</code> <br>
                  Password: <code>password</code>
                </small>
              </div>
              <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input 
                  id="password"
                  name="password" 
                  type="password" 
                  class="form-control"
                  placeholder="Password"
                  required
                />
              </div>
              <div class="mb-3 form-check">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  id="remember" 
                  name="remember"
                />
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
        const loginError = document.getElementById('loginError');
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));

        // Show modal and error if there were validation errors
        @if($errors->any())
          loginError.style.display = 'block';
          loginModal.show();
        @endif

        // Show modal if redirected from protected page
        @if(session('show_login_modal'))
          loginModal.show();
        @endif
      });
    </script>
</body>

<!-- Mirrored from themesbrand.com/velzon/html/master/job-landing.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 12 Aug 2024 07:47:12 GMT -->

</html>