<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Platform Sistem Informasi Bank Sampah Desa Curug Kulon, Kabupaten Tangerang. Solusi cerdas pengelolaan sampah anorganik berbasis sirkular ekonomi dan prinsip 3R.">
    <title>Bank Sampah Desa Curug Kulon - Lingkungan Bersih, Bernilai Ekonomis</title>

    <!-- Google Fonts (Plus Jakarta Sans) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('Screenshot-2026-10-23-103504-Photoroom.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('Screenshot-2026-10-23-103504-Photoroom.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.pwa_head')

    <style>
        :root {
            --primary-emerald: #10b981;
            --primary-dark: #065f46;
            --accent-green: #059669;
            --bg-hero-light: #f0fdf4;
            --bg-card-light: #ffffff;
            --border-light: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        [data-bs-theme="dark"] {
            --bg-hero-light: #0b1712;
            --bg-card-light: #16241d;
            --border-light: #233e31;
            --text-dark: #f1f5f9;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-dark);
            background-color: var(--bs-body-bg);
            scroll-behavior: smooth;
            line-height: 1.7;
        }

        /* Navbar Styling */
        .landing-navbar {
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            background-color: rgba(255, 255, 255, 0.88);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        [data-bs-theme="dark"] .landing-navbar {
            background-color: rgba(15, 23, 42, 0.88);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-dark);
            padding: 0.5rem 1rem !important;
            border-radius: 9999px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent-green) !important;
            background-color: rgba(16, 185, 129, 0.08);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(180deg, var(--bg-hero-light) 0%, var(--bs-body-bg) 100%);
            padding-top: 5rem;
            padding-bottom: 5rem;
            position: relative;
            overflow: hidden;
        }

        .hero-img-wrapper {
            position: relative;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.15);
            border: 4px solid #ffffff;
            transition: transform 0.4s ease;
        }

        [data-bs-theme="dark"] .hero-img-wrapper {
            border: 4px solid #1e293b;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.6);
        }

        .hero-img-wrapper:hover {
            transform: translateY(-4px);
        }

        .hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            max-height: 440px;
            display: block;
        }

        /* Accordion Style matching Jakarta.go.id reference */
        .custom-accordion .accordion-item {
            background-color: var(--bg-card-light);
            border: 1.5px solid #cbd5e1;
            border-radius: 1rem !important;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.25s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        [data-bs-theme="dark"] .custom-accordion .accordion-item {
            border-color: #334155;
            background-color: #1e293b;
        }

        .custom-accordion .accordion-item:hover {
            border-color: #10b981;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.1);
        }

        .custom-accordion .accordion-button {
            background-color: transparent;
            font-size: 1.08rem;
            font-weight: 600;
            color: #0284c7;
            padding: 1.25rem 1.5rem;
            box-shadow: none !important;
            border-radius: 1rem !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        [data-bs-theme="dark"] .custom-accordion .accordion-button {
            color: #38bdf8;
        }

        .custom-accordion .accordion-button:not(.collapsed) {
            color: var(--accent-green);
            background-color: rgba(16, 185, 129, 0.06);
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        [data-bs-theme="dark"] .custom-accordion .accordion-button:not(.collapsed) {
            color: #34d399;
            background-color: rgba(16, 185, 129, 0.12);
        }

        .custom-accordion .accordion-body {
            padding: 1.25rem 1.5rem 1.5rem 1.5rem;
            color: var(--text-dark);
            font-size: 0.98rem;
            line-height: 1.75;
        }

        /* Pill Features */
        .feature-card {
            background: var(--bg-card-light);
            border: 1px solid var(--border-light);
            border-radius: 1.25rem;
            padding: 1.75rem;
            height: 100%;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            border-color: #10b981;
            box-shadow: 0 12px 24px -8px rgba(16, 185, 129, 0.15);
        }

        /* Contact Box */
        .contact-box {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.03) 100%);
            border: 1.5px solid rgba(16, 185, 129, 0.25);
            border-radius: 1.5rem;
            padding: 2.5rem;
        }

        [data-bs-theme="dark"] .contact-box {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(15, 23, 42, 0.5) 100%);
            border-color: rgba(16, 185, 129, 0.3);
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-light);
            background: transparent;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .theme-toggle-btn:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--accent-green);
        }

        [data-bs-theme="dark"] .theme-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body>

    <!-- NAVBAR UTAMA -->
    <nav class="navbar navbar-expand-lg landing-navbar sticky-top py-3">
        <div class="container">
            <!-- Brand Logo & Name -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img src="{{ asset('Screenshot 2026-10-23 103504-Photoroom.png') }}" alt="Logo Bank Sampah"
                    style="height: 42px; width: auto; object-fit: contain;">
                <div>
                    <span class="fw-bold fs-5 d-block text-success lh-1">Bank Sampah</span>
                    <small class="text-secondary fw-semibold" style="font-size: 0.75rem;">Desa Curug Kulon</small>
                </div>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#landingNavbarNav" aria-controls="landingNavbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="bi bi-list fs-2 text-success"></i>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="landingNavbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#cara-kerja">Cara Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                </ul>

                <!-- Right Actions: Theme Toggle & Login -->
                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    <!-- Dark Mode Toggle Button -->
                    <button type="button" class="theme-toggle-btn" id="themeToggleBtn" title="Ganti Mode Gelap/Terang" aria-label="Ganti Tema">
                        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                    </button>

                    <!-- Tombol Login / Dashboard -->
                    @if (Auth::check())
                        <a href="{{ route('dashboard') }}" class="btn btn-success rounded-pill px-4 py-2 fw-semibold shadow-sm d-flex align-items-center gap-2">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success rounded-pill px-4 py-2 fw-semibold shadow-sm d-flex align-items-center gap-2">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Masuk / Login</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-success-subtle text-success border border-success-subtle fw-semibold mb-3">
                        <i class="bi bi-recycle"></i>
                        <span>Platform Bank Sampah Desa Curug Kulon</span>
                    </div>

                    <h1 class="display-5 fw-extrabold mb-3 text-body-emphasis lh-tight">
                        Ubah Sampah Jadi Berkah, Wujudkan Lingkungan Lestari
                    </h1>

                    <p class="lead text-secondary mb-4">
                        Solusi terpadu pencatatan setoran sampah anorganik, monitoring tabungan saldo secara transparan, serta sirkular ekonomi terpadu bagi warga Desa Curug Kulon, Kabupaten Tangerang.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="{{ route('login') }}" class="btn btn-success btn-lg rounded-pill px-4 py-3 fw-semibold shadow-sm d-flex align-items-center gap-2">
                            <span>Mulai Sekarang</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="#tentang" class="btn btn-outline-secondary btn-lg rounded-pill px-4 py-3 fw-semibold">
                            <span>Pelajari Program</span>
                        </a>
                    </div>

                    <!-- Highlight Badges -->
                    <div class="row g-3 pt-2">
                        <div class="col-auto">
                            <div class="d-flex align-items-center gap-2 text-secondary">
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                <span class="fw-semibold">Prinsip 3R (Reduce, Reuse, Recycle)</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center gap-2 text-secondary">
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                <span class="fw-semibold">Notifikasi WhatsApp & Email</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center gap-2 text-secondary">
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                <span class="fw-semibold">Bebas Retribusi Kebersihan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="hero-img-wrapper">
                        <img src="{{ asset('images/apa-itu-bank-sampah-dan-bagaimana-cara-kerjanya-20260820104039.jpg') }}"
                            alt="Apa itu Bank Sampah dan Cara Kerjanya" class="hero-img">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION TENTANG -->
    <section class="py-5" id="tentang">
        <div class="container py-4">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold mb-2">
                        Tentang Bank Sampah
                    </span>
                    <h2 class="fw-bold mb-3">Membangun Kesadaran Lingkungan & Sirkular Ekonomi</h2>
                    <p class="text-secondary">Kenali lebih dekat komitmen dan peran aktif Bank Sampah dalam pengelolaan kebersihan desa.</p>
                </div>
            </div>

            <!-- Teks Paragraf Resmi Tentang Bank Sampah Curug Kulon -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="p-4 p-md-5 rounded-4 bg-body-tertiary border shadow-sm">
                        <p class="fs-6 mb-4 text-justify" style="text-align: justify;">
                            Populasi penduduk Kabupaten Tanggerang yang terus meningkat menjadi tantangan tersendiri dalam menjaga lingkungan. Semakin banyak jumlah penduduk, semakin banyak sampah yang dihasilkan. Salah satu cara kreatif mengelola sampah adalah melalui Bank Sampah. Paradigma yang digunakan dalam Bank Sampah adalah 3R (reduce, reuse dan recycle), yaitu kurangi sampahnya, guna ulang sampahnya, dan daur ulang sampahnya.
                        </p>
                        <p class="fs-6 mb-4 text-justify" style="text-align: justify;">
                            Bank Sampah merupakan konsep pengumpulan sampah anorganik yang masih bernilai ekonomis, seperti plastik, kertas, kaleng, dan lain-lain yang merupakan salah satu penerapan prinsip sirkular ekonomi. Nilai ekonomis sampah ini diharapkan dapat meningkatkan partisipasi warga dalam memilah serta mendaur ulang sampah. Di Desa Curug Kulon, Bank Sampah yang merupakan bertujuan untuk mengurangi sampah dari sumbernya dan menghidupkan siklus sirkular ekonomi di tengah masyarakat.
                        </p>
                        <p class="fs-6 mb-0 text-justify" style="text-align: justify;">
                            Dalam rangka mendukung program tersebut, platform Bank Sampah Desa Curug Kulon pada 1 November 2026. Platform Bank Sampah ini memudahkan warga dalam bertransaksi di Bank Sampah. Bagi petugas Bank Sampah memudahkan dalam mencatat transaksi dan pelaporan. Sementara bagi nasabah memudahkan dalam memonitor tabungan sampahnya dan mendapatkan informasi Bank Sampah mana saja yang aktif. Selain itu, platform ini dapat memfasilitasi warga yang ingin mendapatkan pembebasan retribusi kebersihan dengan syarat menjadi nasabah aktif Bank Sampah.
                        </p>
                    </div>
                </div>
            </div>

            <!-- 3 Pilar Cards -->
            <div class="row g-4 mt-4 justify-content-center">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                            <i class="bi bi-trash3-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Reduce (Kurangi)</h5>
                        <p class="text-secondary small mb-0">Mengurangi volume timbulan sampah sejak dari rumah tangga dengan membatasi penggunaan kemasan sekali pakai.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                            <i class="bi bi-arrow-repeat fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Reuse (Guna Ulang)</h5>
                        <p class="text-secondary small mb-0">Memanfaatkan kembali barang-barang yang masih layak pakai sebelum akhirnya dijadikan sampah buangan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                            <i class="bi bi-recycle fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Recycle (Daur Ulang)</h5>
                        <p class="text-secondary small mb-0">Mengumpulkan sampah anorganik bernilai ekonomis untuk disalurkan ke industri daur ulang melalui mitra pengepul.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION FITUR BANK SAMPAH (ACCORDION DROPDOWN) -->
    <section class="py-5 bg-body-tertiary" id="fitur">
        <div class="container py-4">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold mb-2">
                        Fitur Unggulan
                    </span>
                    <h2 class="fw-bold mb-3">Fitur Platform Bank Sampah</h2>
                    <p class="text-secondary">Dirancang untuk memudahkan nasabah, petugas, dan admin dalam ekosistem pengelolaan sampah terpadu.</p>
                </div>
            </div>

            <!-- Accordion Style Sesuai Screenshot Contoh -->
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion custom-accordion" id="accordionFitur">

                        <!-- Item 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFitur1">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFitur1" aria-expanded="false" aria-controls="collapseFitur1">
                                    <span>Pendaftaran Nasabah Bank Sampah</span>
                                </button>
                            </h2>
                            <div id="collapseFitur1" class="accordion-collapse collapse" aria-labelledby="headingFitur1"
                                data-bs-parent="#accordionFitur">
                                <div class="accordion-body">
                                    Warga Desa Curug Kulon dapat didaftarkan sebagai nasabah resmi dengan data nama lengkap, nomor WhatsApp aktif, dan alamat domisili. Setiap nasabah mendapatkan akun digital untuk memonitor seluruh riwayat transaksi dan tabungan secara mandiri dan aman.
                                </div>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFitur2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFitur2" aria-expanded="false" aria-controls="collapseFitur2">
                                    <span>Tabungan di Bank Sampah & Saldo Real-Time</span>
                                </button>
                            </h2>
                            <div id="collapseFitur2" class="accordion-collapse collapse" aria-labelledby="headingFitur2"
                                data-bs-parent="#accordionFitur">
                                <div class="accordion-body">
                                    Setiap sampah anorganik yang disetorkan langsung dikonversi menjadi saldo rupiah sesuai harga terkini. Nasabah dapat mengecek saldo terkini, histori setoran, serta total berat sampah yang telah dikontribusikan kapan saja secara transparan melalui dashboard nasabah.
                                </div>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFitur3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFitur3" aria-expanded="false" aria-controls="collapseFitur3">
                                    <span>Penimbangan & Kalkulasi Otomatis</span>
                                </button>
                            </h2>
                            <div id="collapseFitur3" class="accordion-collapse collapse" aria-labelledby="headingFitur3"
                                data-bs-parent="#accordionFitur">
                                <div class="accordion-body">
                                    Sistem memfasilitasi petugas dalam menimbang multi-kategori sampah (plastik, kertas/karton, logam, botol kaca, minyak jelantah, dan kategori lainnya). Perhitungan total rupiah dilakukan secara otomatis oleh sistem sehingga meminimalkan potensi kesalahan hitung manual.
                                </div>
                            </div>
                        </div>

                        <!-- Item 4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFitur4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFitur4" aria-expanded="false" aria-controls="collapseFitur4">
                                    <span>Penarikan Saldo & Riwayat Transaksi Digital</span>
                                </button>
                            </h2>
                            <div id="collapseFitur4" class="accordion-collapse collapse" aria-labelledby="headingFitur4"
                                data-bs-parent="#accordionFitur">
                                <div class="accordion-body">
                                    Nasabah dapat mengajukan penarikan tunai saldo tabungannya secara fleksibel. Pengajuan diproses dan diverifikasi oleh petugas secara transparan, lengkap dengan pencetakan bukti kuitansi digital/PDF serta pembukuan keuangan yang akurat.
                                </div>
                            </div>
                        </div>

                        <!-- Item 5 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFitur5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFitur5" aria-expanded="false" aria-controls="collapseFitur5">
                                    <span>Notifikasi Otomatis via WhatsApp & Email</span>
                                </button>
                            </h2>
                            <div id="collapseFitur5" class="accordion-collapse collapse" aria-labelledby="headingFitur5"
                                data-bs-parent="#accordionFitur">
                                <div class="accordion-body">
                                    Sistem terintegrasi dengan gateway WhatsApp dan notifikasi Email. Begitu setoran sampah dicatat oleh petugas, nasabah langsung mendapatkan pesan konfirmasi ke nomor WhatsApp dan email pribadinya. Begitu juga saat pengajuan dan persetujuan penarikan saldo disetujui.
                                </div>
                            </div>
                        </div>

                        <!-- Item 6 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFitur6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFitur6" aria-expanded="false" aria-controls="collapseFitur6">
                                    <span>Manfaat Bebas Retribusi Kebersihan bagi Nasabah Aktif</span>
                                </button>
                            </h2>
                            <div id="collapseFitur6" class="accordion-collapse collapse" aria-labelledby="headingFitur6"
                                data-bs-parent="#accordionFitur">
                                <div class="accordion-body">
                                    Sebagai wujud apresiasi bagi warga yang konsisten memilah sampah, nasabah yang aktif menyetorkan sampah berhak mendapatkan fasilitas pembebasan retribusi kebersihan desa sesuai regulasi yang berlaku di lingkungan Desa Curug Kulon.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION CARA KERJA (ACCORDION DROPDOWN) -->
    <section class="py-5" id="cara-kerja">
        <div class="container py-4">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold mb-2">
                        Mekanisme Kerja
                    </span>
                    <h2 class="fw-bold mb-3">Bagaimana Cara Kerja Bank Sampah?</h2>
                    <p class="text-secondary">Alur mudah dan terstruktur dari pemilahan sampah di rumah tangga hingga menjadi rupiah.</p>
                </div>
            </div>

            <!-- Accordion Style Cara Kerja -->
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion custom-accordion" id="accordionCaraKerja">

                        <!-- Langkah 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingKerja1">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseKerja1" aria-expanded="false" aria-controls="collapseKerja1">
                                    <span>1. Pemilahan Sampah dari Rumah (Sumber)</span>
                                </button>
                            </h2>
                            <div id="collapseKerja1" class="accordion-collapse collapse" aria-labelledby="headingKerja1"
                                data-bs-parent="#accordionCaraKerja">
                                <div class="accordion-body">
                                    Warga memilah sampah anorganik (seperti kardus, kertas, botol plastik, gelas air mineral, kaleng, dan logam) dari sampah organik dapur. Pastikan sampah anorganik dalam kondisi relatif bersih dan kering agar bernilai ekonomis optimal.
                                </div>
                            </div>
                        </div>

                        <!-- Langkah 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingKerja2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseKerja2" aria-expanded="false" aria-controls="collapseKerja2">
                                    <span>2. Penyetoran ke Titik Bank Sampah Desa Curug Kulon</span>
                                </button>
                            </h2>
                            <div id="collapseKerja2" class="accordion-collapse collapse" aria-labelledby="headingKerja2"
                                data-bs-parent="#accordionCaraKerja">
                                <div class="accordion-body">
                                    Nasabah membawa sampah terpilah ke lokasi atau pos penimbangan Bank Sampah Desa Curug Kulon pada jadwal operasional yang telah ditentukan oleh pengurus.
                                </div>
                            </div>
                        </div>

                        <!-- Langkah 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingKerja3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseKerja3" aria-expanded="false" aria-controls="collapseKerja3">
                                    <span>3. Penimbangan & Validasi Jenis Sampah oleh Petugas</span>
                                </button>
                            </h2>
                            <div id="collapseKerja3" class="accordion-collapse collapse" aria-labelledby="headingKerja3"
                                data-bs-parent="#accordionCaraKerja">
                                <div class="accordion-body">
                                    Petugas memeriksa jenis sampah nasabah dan melakukan penimbangan akurat. Petugas menginput data berat dan kategori sampah langsung ke aplikasi Sistem Informasi Bank Sampah.
                                </div>
                            </div>
                        </div>

                        <!-- Langkah 4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingKerja4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseKerja4" aria-expanded="false" aria-controls="collapseKerja4">
                                    <span>4. Konversi Saldo & Notifikasi Bukti Setoran (WA & Email)</span>
                                </button>
                            </h2>
                            <div id="collapseKerja4" class="accordion-collapse collapse" aria-labelledby="headingKerja4"
                                data-bs-parent="#accordionCaraKerja">
                                <div class="accordion-body">
                                    Sistem secara otomatis mengkalkulasi nominal rupiah dan menambahkan saldo ke rekening tabungan nasabah. Bukti setoran digital beserta rincian berat dan nominal dikirim seketika melalui pesan WhatsApp dan Email.
                                </div>
                            </div>
                        </div>

                        <!-- Langkah 5 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingKerja5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseKerja5" aria-expanded="false" aria-controls="collapseKerja5">
                                    <span>5. Penyaluran ke Pengepul & Pengolahan Sirkular</span>
                                </button>
                            </h2>
                            <div id="collapseKerja5" class="accordion-collapse collapse" aria-labelledby="headingKerja5"
                                data-bs-parent="#accordionCaraKerja">
                                <div class="accordion-body">
                                    Sampah yang terkumpul di Bank Sampah dikelompokkan dan dijual kepada mitra pengepul daur ulang terpercaya. Hasil penjualan menjadi sumber dana kas dan pemutaran siklus ekonomi sirkular desa.
                                </div>
                            </div>
                        </div>

                        <!-- Langkah 6 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingKerja6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseKerja6" aria-expanded="false" aria-controls="collapseKerja6">
                                    <span>6. Pencairan Tabungan Saldo Kapan Saja</span>
                                </button>
                            </h2>
                            <div id="collapseKerja6" class="accordion-collapse collapse" aria-labelledby="headingKerja6"
                                data-bs-parent="#accordionCaraKerja">
                                <div class="accordion-body">
                                    Nasabah dapat mencairkan saldo tabungannya sewaktu-waktu sesuai ketentuan penarikan, baik untuk belanja kebutuhan harian, membayar keperluan listrik, maupun ditabung untuk keperluan hari raya.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION KONTAK -->
    <section class="py-5 bg-body-tertiary" id="kontak">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="contact-box shadow-sm">
                        <div class="text-center mb-4">
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold mb-2">
                                Hubungi Kami & Bergabung
                            </span>
                            <h3 class="fw-bold mb-3">Mari Bersama Menjaga Desa Curug Kulon</h3>
                            <p class="fs-6 lead text-body-emphasis mb-0">
                                Dengan Bank Sampah Desa Curug Kulon, setiap warga kini bisa berkontribusi menjaga lingkungan dengan lebih mudah. Mari menjadi nasabah Bank Sampah, mari ambil bagian dalam upaya menciptakan lingkungan yang lebih bersih dan lestari.
                            </p>
                        </div>

                        <hr class="my-4 opacity-25">

                        <div class="row g-4 justify-content-center text-center text-md-start">
                            <!-- WhatsApp -->
                            <div class="col-md-4">
                                <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                                        <i class="bi bi-whatsapp fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary fw-semibold d-block">WhatsApp / Telepon</small>
                                        <a href="https://wa.me/6281234567890" target="_blank" class="fw-bold text-decoration-none text-success fs-6">
                                            +62 812-3456-7890
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-4">
                                <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                                        <i class="bi bi-envelope-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary fw-semibold d-block">Email Resmi</small>
                                        <a href="mailto:info@banksampah-curugkulon.desa.id" class="fw-bold text-decoration-none text-body fs-6">
                                            info@banksampah-curugkulon.desa.id
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Lokasi / Jam -->
                            <div class="col-md-4">
                                <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                                        <i class="bi bi-geo-alt-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary fw-semibold d-block">Lokasi Operasional</small>
                                        <span class="fw-bold text-body fs-6">
                                            Kantor Desa Curug Kulon, Banten
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Bottom Button -->
                        <div class="text-center mt-5">
                            <a href="{{ route('login') }}" class="btn btn-success btn-lg rounded-pill px-5 py-3 fw-bold shadow-sm">
                                <i class="bi bi-person-plus-fill me-2"></i> Masuk / Daftar Nasabah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="py-4 border-top">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('Screenshot 2026-10-23 103504-Photoroom.png') }}" alt="Logo" style="height: 30px; width: auto;">
                    <span class="fw-bold text-success">Bank Sampah Desa Curug Kulon</span>
                </div>
                <div class="text-secondary small">
                    &copy; 2026 Bank Sampah Desa Curug Kulon — Lingkungan Bersih, Dompet Terisi.
                </div>
            </div>
        </div>
    </footer>

    <!-- DARK MODE & UI INTERACTION SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            const themeIcon = document.getElementById('themeIcon');
            const htmlElement = document.documentElement;

            // 1. Ambil tema tersimpan atau default ke 'light'
            const savedTheme = localStorage.getItem('bank_sampah_landing_theme') || 'light';
            applyTheme(savedTheme);

            function applyTheme(theme) {
                htmlElement.setAttribute('data-bs-theme', theme);
                localStorage.setItem('bank_sampah_landing_theme', theme);

                if (theme === 'dark') {
                    themeIcon.classList.remove('bi-moon-stars-fill');
                    themeIcon.classList.add('bi-sun-fill');
                    themeToggleBtn.setAttribute('title', 'Ganti ke Mode Terang');
                } else {
                    themeIcon.classList.remove('bi-sun-fill');
                    themeIcon.classList.add('bi-moon-stars-fill');
                    themeToggleBtn.setAttribute('title', 'Ganti ke Mode Gelap');
                }
            }

            themeToggleBtn.addEventListener('click', function() {
                const currentTheme = htmlElement.getAttribute('data-bs-theme');
                const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
                applyTheme(nextTheme);
            });

            // Smooth scrolling active link highlight
            const navLinks = document.querySelectorAll('.nav-link[href^="#"]');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId && targetId !== '#') {
                        const targetEl = document.querySelector(targetId);
                        if (targetEl) {
                            e.preventDefault();
                            targetEl.scrollIntoView({ behavior: 'smooth' });

                            // Collapse mobile navbar if open
                            const navbarCollapse = document.getElementById('landingNavbarNav');
                            if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                                if (bsCollapse) bsCollapse.hide();
                            }
                        }
                    }
                });
            });
        });
    </script>

    @include('partials.pwa_script')
</body>

</html>
