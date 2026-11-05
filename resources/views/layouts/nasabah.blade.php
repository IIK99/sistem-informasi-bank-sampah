<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Warga') - Bank Sampah</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('Screenshot-2026-10-23-103504-Photoroom.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('Screenshot-2026-10-23-103504-Photoroom.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- CSS Khusus Nasabah -->
    <link rel="stylesheet" href="{{ asset('css/nasabah.css') }}">

    <script>
        (function() {
            const savedTheme = localStorage.getItem('nasabah_theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    @include('partials.pwa_head')
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <!-- NAVBAR  -->
    <nav class="navbar navbar-expand-lg nasabah-navbar sticky-top py-2">
        <div class="container-xxl">
            <!-- Brand Logo & Subjudul -->
            <a class="nasabah-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <img src="{{ asset('Screenshot 2026-10-23 103504-Photoroom.png') }}" alt="Logo Bank Sampah"
                    style="height: 38px; width: auto; object-fit: contain;" class="flex-shrink-0">
                <div class="brand-text">
                    <h6 class="mb-0">Bank Sampah</h6>
                    <small>Solusi Bersih & Tabungan</small>
                </div>
            </a>

            <!-- Tombol Hamburger Mobile -->
            <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#nasabahNav">
                <i class="bi bi-list fs-2 text-white"></i>
            </button>

            <!-- Navigasi Tengah & Tombol Profil -->
            <div class="collapse navbar-collapse" id="nasabahNav">
                <ul class="navbar-nav mx-auto my-2 my-lg-0 gap-lg-1 align-items-lg-center">
                    @php $menus = $menus ?? collect(); @endphp
                    @foreach ($menus as $menu)
                        {{-- Saring menu Akun dan Profil agar tidak bocor ke navigasi tengah --}}
                        @if (in_array(strtolower($menu->title), ['profil', 'akun']))
                            @continue
                        @endif

                        @php
                            $hasMultipleSub = $menu->subMenus->count() > 1;
                            $singleSub = $menu->subMenus->count() === 1 ? $menu->subMenus->first() : null;
                            $targetRoute = $singleSub ? $singleSub->route : $menu->route ?? '#';
                            if ($targetRoute === '#') {
                                $t = strtolower(trim($singleSub ? $singleSub->title : $menu->title));
                                if (str_contains($t, 'saldo')) {
                                    $targetRoute = route('saldo.index');
                                } elseif (str_contains($t, 'riwayat') || str_contains($t, 'transaksi')) {
                                    $targetRoute = route('riwayat.transaksi');
                                } elseif (str_contains($t, 'setoran')) {
                                    $targetRoute = route('nasabah.setoran.index');
                                } elseif (str_contains($t, 'penarikan')) {
                                    $targetRoute = route('nasabah.penarikan.index');
                                }
                            }
                        @endphp

                        @if ($hasMultipleSub)
                            @php
                                $isDropdownActive = false;
                                $nasabahParentBadge = 0;
                                foreach ($menu->subMenus as $chk) {
                                    $p = trim(parse_url($chk->route, PHP_URL_PATH), '/');
                                    if (!empty($p) && (request()->is($p) || request()->is($p . '/*'))) {
                                        $isDropdownActive = true;
                                    }
                                    $cTitle = strtolower(trim($chk->title));
                                    if (str_contains($cTitle, 'penarikan')) $nasabahParentBadge += ($sidebarBadges['penarikan'] ?? 0);
                                    elseif (str_contains($cTitle, 'setoran')) $nasabahParentBadge += ($sidebarBadges['setoran'] ?? 0);
                                }
                            @endphp
                            {{-- Dropdown jika memiliki lebih dari 1 anak menu --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white-50 px-2 d-flex align-items-center gap-1 {{ $isDropdownActive ? 'active text-white fw-bold' : '' }}"
                                    href="#" role="button" data-bs-toggle="dropdown">
                                    <span>{{ $menu->title }}</span>
                                    @if ($nasabahParentBadge > 0)
                                        <span class="sidebar-notif-dot bg-danger rounded-circle d-inline-block" style="width: 7px; height: 7px; box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.4);"></span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu shadow border-0 rounded-3">
                                    @foreach ($menu->subMenus as $sub)
                                        @php
                                            $url = $sub->route;
                                            if ($url === '#') {
                                                $t = strtolower(trim($sub->title));
                                                if (str_contains($t, 'setoran')) {
                                                    $url = str_contains($t, 'laporan')
                                                        ? route('laporan.setoran')
                                                        : route('nasabah.setoran.index');
                                                } elseif (str_contains($t, 'penarikan')) {
                                                    $url = str_contains($t, 'laporan')
                                                        ? route('laporan.penarikan')
                                                        : route('nasabah.penarikan.index');
                                                } elseif (str_contains($t, 'saldo')) {
                                                    $url = str_contains($t, 'laporan')
                                                        ? route('laporan.saldo')
                                                        : route('saldo.index');
                                                } elseif (str_contains($t, 'riwayat')) {
                                                    $url = route('riwayat.transaksi');
                                                } elseif (str_contains($t, 'sampah') && str_contains($t, 'laporan')) {
                                                    $url = route('laporan.sampah');
                                                } elseif (str_contains($t, 'profil')) {
                                                    $url = url('/profil');
                                                }
                                            }
                                            $subP = trim(parse_url($url, PHP_URL_PATH), '/');
                                            $isSubAct =
                                                !empty($subP) && (request()->is($subP) || request()->is($subP . '/*'));
                                            $subTitleLower = strtolower(trim($sub->title));
                                            $menuBadge = 0;
                                            $subCategory = '';
                                            if (str_contains($subTitleLower, 'penarikan')) {
                                                $menuBadge = $sidebarBadges['penarikan'] ?? 0;
                                                $subCategory = 'penarikan';
                                            } elseif (str_contains($subTitleLower, 'setoran')) {
                                                $menuBadge = $sidebarBadges['setoran'] ?? 0;
                                                $subCategory = 'setoran';
                                            }
                                        @endphp
                                        <li>
                                            <a class="dropdown-item small py-2 d-flex align-items-center justify-content-between {{ $isSubAct ? 'active fw-bold' : '' }}"
                                                href="{{ $url }}"
                                                @if(!empty($subCategory)) data-notif-category="{{ $subCategory }}" @endif>
                                                <span>{{ $sub->title }}</span>
                                                @if ($menuBadge > 0)
                                                    <span class="sidebar-notif-dot bg-danger rounded-circle d-inline-block" style="width: 7px; height: 7px; box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.4);"></span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            {{-- Menu biasa tanpa dropdown --}}
                            @php
                                $singleTitleLower = strtolower(trim($menu->title));
                                $singleBadge = 0;
                                $singleCategory = '';
                                if (str_contains($singleTitleLower, 'penarikan')) {
                                    $singleBadge = $sidebarBadges['penarikan'] ?? 0;
                                    $singleCategory = 'penarikan';
                                } elseif (str_contains($singleTitleLower, 'setoran')) {
                                    $singleBadge = $sidebarBadges['setoran'] ?? 0;
                                    $singleCategory = 'setoran';
                                }
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link text-white-50 px-2 d-flex align-items-center gap-1 {{ request()->is(ltrim(parse_url($targetRoute, PHP_URL_PATH), '/')) ? 'active text-white fw-bold' : '' }}"
                                    href="{{ $targetRoute }}"
                                    @if(!empty($singleCategory)) data-notif-category="{{ $singleCategory }}" @endif>
                                    <span>{{ $menu->title }}</span>
                                    @if ($singleBadge > 0)
                                        <span class="sidebar-notif-dot bg-danger rounded-circle d-inline-block" style="width: 7px; height: 7px; box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.4);"></span>
                                    @endif
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>

                <!-- Tombol Pill Profil, Notifikasi, & Toggle Mode Gelap -->
                <div
                    class="d-flex align-items-center gap-2 pt-3 pt-lg-0 border-top border-white border-opacity-10 mt-3 mt-lg-0">
                    <!-- Tombol Dark Mode / Light Mode di sebelah kiri ikon lonceng -->
                    <button type="button" class="btn-theme-toggle" id="themeToggleBtn"
                        title="Ganti Mode Terang / Gelap" aria-label="Toggle Dark/Light Mode">
                        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                    </button>

                    <!-- Icon Lonceng Notifikasi di samping Profil -->
                    @include('partials.notification_bell')

                    <!-- Dropdown Profil -->
                    <div class="dropdown">
                        <button class="btn-profile-pill dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            data-bs-display="static" aria-expanded="false">
                            <i class="bi bi-person-fill"></i>
                            <span>{{ auth()->user()->name }}</span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-3 mt-2 profile-dropdown-box"
                            style="min-width: 240px;">
                            {{-- Info User, Profil Saya, dan Keluar DISATUKAN dalam DIV yang sama --}}
                            <div class="profile-unified-wrapper">
                                <div class="d-flex align-items-center gap-3 pb-3 border-bottom mb-2">
                                    <div class="bg-success-subtle text-success fw-bold d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                        style="width: 42px; height: 42px; font-size: 1.15rem;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="fw-bold text-truncate text-dark profile-name-label"
                                           style="font-size: 0.92rem;">
                                            {{ auth()->user()->name }}
                                        </div>
                                        <div class="text-muted text-truncate small profile-user-handle">
                                            @<span>{{ auth()->user()->username }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <a class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2 fw-medium text-secondary"
                                        href="{{ url('/profil') }}">
                                        <i class="bi bi-person-circle fs-5 text-success"></i>
                                        <span>Profil Saya</span>
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit"
                                            class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2 fw-medium text-danger w-100">
                                            <i class="bi bi-box-arrow-right fs-5"></i>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- AREA KONTEN -->
    <main class="container-fluid px-3 px-lg-5 py-4 flex-grow-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="py-3 bg-white border-top text-center text-muted small mt-auto">
        <div class="container-fluid px-3 px-lg-5">
            &copy; {{ date('Y') }} Bank Sampah Warga — Lingkungan Bersih, Dompet Terisi.
        </div>
    </footer>

    <!-- Handler Darkmode / Lightmode -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            const themeIcon = document.getElementById('themeIcon');
            const htmlEl = document.documentElement;

            function applyTheme(theme) {
                htmlEl.setAttribute('data-bs-theme', theme);
                localStorage.setItem('nasabah_theme', theme);
                if (themeIcon) {
                    if (theme === 'dark') {
                        themeIcon.className = 'bi bi-sun-fill text-warning';
                    } else {
                        themeIcon.className = 'bi bi-moon-stars-fill text-white';
                    }
                }
            }

            // Inisialisasi ikon saat pertama load
            const activeTheme = htmlEl.getAttribute('data-bs-theme') || 'light';
            applyTheme(activeTheme);

            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    const nextTheme = htmlEl.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                    applyTheme(nextTheme);
                });
            }
        });
    </script>

    <!-- Realtime Notification Clear Handler Nasabah -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            document.querySelectorAll('[data-notif-category]').forEach(function (el) {
                el.addEventListener('click', function () {
                    const category = this.getAttribute('data-notif-category');

                    // 1. Hilangkan dot merah seketika
                    const dot = this.querySelector('.sidebar-notif-dot');
                    if (dot) {
                        dot.remove();
                    }

                    // 2. Jika di dalam dropdown menu, periksa apakah sisa dot lain masih ada
                    const dropdownMenu = this.closest('.dropdown-menu');
                    if (dropdownMenu) {
                        const remaining = dropdownMenu.querySelectorAll('.sidebar-notif-dot');
                        if (remaining.length === 0) {
                            const dropdownParent = dropdownMenu.closest('.dropdown');
                            if (dropdownParent) {
                                const parentDot = dropdownParent.querySelector('.sidebar-notif-dot');
                                if (parentDot) parentDot.remove();
                            }
                        }
                    }

                    // 3. Kirim update ke server di background
                    if (category && csrfToken) {
                        fetch('{{ route("notifikasi.markCategoryRead") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ category: category }),
                            keepalive: true
                        }).then(r => r.json()).then(data => {
                            if (data && typeof data.unread !== 'undefined') {
                                const bellBadge = document.querySelector('.btn-notification-bell .notif-badge');
                                if (bellBadge) {
                                    if (data.unread > 0) {
                                        bellBadge.textContent = data.unread > 99 ? '99+' : data.unread;
                                    } else {
                                        bellBadge.remove();
                                    }
                                }
                            }
                        }).catch(() => {});
                    }
                });
            });
        });
    </script>

    @include('partials.pwa_script')

    <!-- SweetAlert2 Handler -->
    @include('partials.sweetalert')

    @stack('scripts')
</body>

</html>
