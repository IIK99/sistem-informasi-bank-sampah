<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bank Sampah</title>
    <!-- Google Fonts (Plus Jakarta Sans) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('Screenshot-2026-10-23-103504-Photoroom.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('Screenshot-2026-10-23-103504-Photoroom.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.pwa_head')

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        min-height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        position: relative;
        overflow: hidden;
        background-color: #f0fdf4;
    }

    /* Container Efek Aurora Soft */
    .aurora-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        filter: blur(80px);
        opacity: 0.65;
    }

    /* Lapisan Aurora Warna Pastels Soft */
    .aurora-item {
        position: absolute;
        border-radius: 50%;
    }

    .aurora-item:nth-child(1) {
        top: -15%;
        left: -10%;
        width: 80%;
        height: 80%;
        background: radial-gradient(circle at center, #86efac 0%, #bbf7d0 40%, transparent 80%);
        animation: auroraMove1 22s ease-in-out infinite;
    }

    .aurora-item:nth-child(2) {
        bottom: -15%;
        right: -10%;
        width: 75%;
        height: 75%;
        background: radial-gradient(circle at center, #a5f3fc 0%, #cffafe 40%, transparent 80%);
        animation: auroraMove2 26s ease-in-out infinite reverse;
    }

    .aurora-item:nth-child(3) {
        top: 25%;
        right: 5%;
        width: 65%;
        height: 65%;
        background: radial-gradient(circle at center, #6ee7b7 0%, #d1fae5 40%, transparent 80%);
        animation: auroraMove3 24s ease-in-out infinite;
    }

    @keyframes auroraMove1 {

        0%,
        100% {
            transform: translate(0, 0) scale(1);
        }

        50% {
            transform: translate(12%, 8%) scale(1.08);
        }
    }

    @keyframes auroraMove2 {

        0%,
        100% {
            transform: translate(0, 0) scale(1.08);
        }

        50% {
            transform: translate(-8%, -12%) scale(1);
        }
    }

    @keyframes auroraMove3 {

        0%,
        100% {
            transform: translate(0, 0) scale(1);
        }

        50% {
            transform: translate(-6%, 8%) scale(1.05);
        }
    }

    /* Overlay Pattern Grid & Dots */
    .pattern-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
        pointer-events: none;
        opacity: 0.12;
        background-image:
            radial-gradient(#166534 1px, transparent 1px),
            linear-gradient(to right, #166534 1px, transparent 1px),
            linear-gradient(to bottom, #166534 1px, transparent 1px);
        background-size: 20px 20px, 80px 80px, 80px 80px;
    }

    /* Card Login Glassmorphism Clean */
    .login-card {
        position: relative;
        z-index: 10;
        border: 1px solid rgba(255, 255, 255, 0.9);
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(22, 101, 52, 0.08);
        background: rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        color: #1e293b;
    }

    .brand-icon {
        width: 65px;
        height: 65px;
        background: linear-gradient(135deg, #16a34a, #15803d);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        margin: 0 auto 1rem;
        box-shadow: 0 8px 20px rgba(22, 163, 74, 0.2);
        animation: floatIcon 4s ease-in-out infinite;
    }

    @keyframes floatIcon {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    /* Badge Wilayah Identitas */
    .identity-badge {
        background-color: rgba(22, 163, 74, 0.1);
        color: #15803d;
        border-radius: 20px;
        padding: 0.35rem 0.85rem;
        font-weight: 600;
        font-size: 0.78rem;
        display: inline-block;
    }

    /* Form Inputs */
    .form-control {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        border: 1.5px solid #e2e8f0;
        background-color: rgba(255, 255, 255, 0.9);
        color: #0f172a;
        font-size: 0.95rem;
        transition: all 0.25s ease;
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .form-control:focus {
        background-color: #ffffff;
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.12);
    }

    /* Button Elegant Soft Green */
    .btn-success-custom {
        background: linear-gradient(135deg, #16a34a, #15803d);
        border: none;
        border-radius: 12px;
        padding: 0.8rem;
        font-weight: 600;
        color: white;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(22, 163, 74, 0.2);
    }

    .btn-success-custom:hover {
        background: linear-gradient(135deg, #15803d, #166534);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(22, 163, 74, 0.3);
        color: white;
    }

    .alert-danger-custom {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        border-radius: 12px;
    }
    </style>
</head>

<body>
    <!-- Lapisan Aurora Soft -->
    <div class="aurora-bg">
        <div class="aurora-item"></div>
        <div class="aurora-item"></div>
        <div class="aurora-item"></div>
    </div>

    <!-- Lapisan Pattern Overlay (Grid & Dots) -->
    <div class="pattern-overlay"></div>

    <!-- Card Login -->
    <div class="card login-card p-4 p-sm-5" style="width: 100%; max-width: 420px;">

        <!-- Logo / Icon Header & Identitas -->
        <div class="text-center mb-4">
            <div class="brand-icon">
                <i class="bi bi-recycle"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Bank Sampah</h4>

            <!-- Badge Identitas Wilayah -->
            <div class="identity-badge my-1">
                <i class="bi bi-geo-alt-fill me-1"></i>RW 012 Desa Curug Kulon
            </div>
            <p class="text-muted small mt-1 mb-0">Kabupaten Tangerang</p>
        </div>

        <!-- Alert Error Laravel -->
        @if ($errors->any())
        <div class="alert alert-danger-custom py-2 px-3 small mb-4 d-flex align-items-center">
            <i class="bi bi-exclamation-circle-fill me-2 fs-6"></i>
            <div>{{ $errors->first() }}</div>
        </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username"
                    value="{{ old('username') }}" required autofocus>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-secondary">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-success-custom w-100">
                Masuk Ke Akun <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>

        <!-- Footer Card -->
        <div class="text-center mt-4">
            <p class="text-muted mb-0" style="font-size: 0.78rem;">
                &copy; {{ date('Y') }} Bank Sampah RW 012<br>
                Desa Curug Kulon, Kab. Tangerang
            </p>
        </div>
    </div>

    @include('partials.pwa_script')
</body>

</html>