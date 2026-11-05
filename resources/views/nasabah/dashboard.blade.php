@extends('layouts.nasabah')

@section('title', 'Beranda Nasabah')

@section('content')
    <!-- HERO BANNER -->
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-white hero-nasabah-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
            <div style="max-width: 620px;">
                <h1 class="hero-title mb-3">
                    Kumpulkan Sampah, Nikmati Tabungannya.
                </h1>
                <p class="text-muted small mb-0" style="line-height: 1.6;">
                    Setorkan sampah anorganik Anda ke petugas penimbangan, lalu pantau saldo tabungan yang siap dicairkan
                    secara
                    transparan.
                </p>
            </div>

            <div class="hero-saldo-box flex-shrink-0" style="width: 100%; max-width: 390px;">
                <div class="p-4 rounded-4 shadow-lg position-relative overflow-hidden"
                    style="background: linear-gradient(135deg, #071912 0%, #0d3826 60%, #144933 100%); border: 1.5px solid rgba(180, 241, 5, 0.45); box-shadow: 0 12px 30px -5px rgba(7, 25, 18, 0.45) !important;">

                    <div class="position-absolute end-0 top-0 w-50 h-50"
                        style="background: radial-gradient(circle at 80% 20%, rgba(180, 241, 5, 0.18) 0%, transparent 70%); pointer-events: none;">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 position-relative z-1">
                        <span class="text-white-50 fw-semibold d-flex align-items-center gap-2" style="font-size: 0.92rem;">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                style="width: 32px; height: 32px; background: rgba(180, 241, 5, 0.15);">
                                <i class="bi bi-wallet2 text-success fs-5"></i>
                            </span>
                            Saldo Tabungan Anda
                        </span>
                        <button type="button" id="btnToggleSaldo"
                            class="btn btn-sm btn-link text-white-50 p-1 text-decoration-none shadow-none rounded-circle"
                            style="transition: all 0.2s;" title="Sembunyikan / Tampilkan Saldo" aria-label="Toggle Saldo">
                            <i class="bi bi-eye-slash fs-4 text-white" id="iconToggleSaldo"></i>
                        </button>
                    </div>

                    <div class="my-3 py-1 position-relative z-1">
                        <div class="fw-bold text-white mb-0" id="nominalSaldoReal"
                            style="font-size: clamp(1.85rem, 3.5vw, 2.35rem); letter-spacing: -0.02em; line-height: 1.2;">
                            Rp {{ number_format(auth()->user()->saldo ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="fw-bold text-white mb-0 d-none" id="nominalSaldoMasked"
                            style="font-size: clamp(1.85rem, 3.5vw, 2.35rem); letter-spacing: 5px; line-height: 1.2;">
                            Rp ••••••••
                        </div>
                    </div>

                    <div
                        class="mt-3 pt-3 border-top border-white border-opacity-15 d-flex justify-content-between align-items-center position-relative z-1">
                        <span class="text-white-50 small d-flex align-items-center gap-1" style="font-size: 0.82rem;">
                            <i class="bi bi-patch-check-fill text-success fs-6"></i> Status: <strong
                                class="text-white">Aktif</strong>
                        </span>
                        <a href="{{ route('saldo.index') }}"
                            class="btn btn-sm btn-success rounded-pill px-3 py-1.5 fw-semibold shadow-sm d-inline-flex align-items-center gap-1"
                            style="font-size: 0.82rem;">
                            Kelola Saldo <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MENU CEPAT-->
    <div class="mb-4">
        <div class="row g-3">
            <!-- Tombol Unduh / Pasang Web App -->
            <div class="col-md-4">
                <a href="javascript:void(0)" id="btnInstallApp" class="quick-action-card text-decoration-none h-100">
                    <div class="action-icon">
                        <i class="bi bi-phone text-success fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold small text-dark">Unduh Web App</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Akses langsung dari layar utama anda</div>
                    </div>
                </a>
            </div>

            <!-- 2. Pencairan Dana -->
            <div class="col-12 col-md-4">
                <a href="{{ route('nasabah.penarikan.index') }}" class="quick-action-card h-100">
                    <div class="action-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="fw-bold small text-dark">Pencairan Dana</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Tarik saldo tabungan tunai</div>
                    </div>
                </a>
            </div>

            <!-- 3. Profil Akun -->
            <div class="col-12 col-md-4">
                <a href="{{ url('/profil') }}" class="quick-action-card h-100">
                    <div class="action-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <div>
                        <div class="fw-bold small text-dark">Profil Saya</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Informasi data akun pribadi</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnToggle = document.getElementById('btnToggleSaldo');
            const iconToggle = document.getElementById('iconToggleSaldo');
            const saldoReal = document.getElementById('nominalSaldoReal');
            const saldoMasked = document.getElementById('nominalSaldoMasked');

            if (btnToggle && iconToggle && saldoReal && saldoMasked) {
                let isHidden = localStorage.getItem('nasabah_saldo_hidden') === 'true';

                function renderSaldoState() {
                    if (isHidden) {
                        saldoReal.classList.add('d-none');
                        saldoMasked.classList.remove('d-none');
                        iconToggle.className = 'bi bi-eye fs-5';
                        btnToggle.title = 'Tampilkan Saldo';
                    } else {
                        saldoReal.classList.remove('d-none');
                        saldoMasked.classList.add('d-none');
                        iconToggle.className = 'bi bi-eye-slash fs-5';
                        btnToggle.title = 'Sembunyikan Saldo';
                    }
                }

                renderSaldoState();

                btnToggle.addEventListener('click', function() {
                    isHidden = !isHidden;
                    localStorage.setItem('nasabah_saldo_hidden', isHidden);
                    renderSaldoState();
                });
            }
        });


        //PWA
        let deferredPrompt;
        const btnInstall = document.getElementById('btnInstallApp');

        // Tangkap perintah instalasi bawaan browser
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
        });

        if (btnInstall) {
            btnInstall.addEventListener('click', async () => {
                if (deferredPrompt) {
                    // Munculkan pop-up install native browser
                    deferredPrompt.prompt();
                    const {
                        outcome
                    } = await deferredPrompt.userChoice;
                    if (outcome === 'accepted') {
                        deferredPrompt = null;
                    }
                } else {
                    // Notifikasi ramah jika browser warga tidak mendukung otomatis / sudah terpasang
                    alert(
                        "Aplikasi siap dipasang! Jika dialog tidak otomatis muncul, ketuk menu titik tiga (⋮) di pojok atas browser kamu lalu pilih 'Instal Aplikasi' atau 'Tambahkan ke Layar Utama'."
                    );
                }
            });
        }

        // Reset prompt setelah berhasil dipasang
        window.addEventListener('appinstalled', () => {
            deferredPrompt = null;
            alert("Aplikasi Bank Sampah berhasil dipasang di perangkat kamu!");
        });
    </script>
@endpush
