<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Bank Sampah</title>

    <!-- Bootstrap 5 & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('Screenshot-2026-10-23-103504-Photoroom.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('Screenshot-2026-10-23-103504-Photoroom.ico') }}">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    <!-- SIDEBAR  -->
    <aside class="sidebar-wrapper">
        <div class="sidebar-brand justify-content-between">
            <a href="{{ route('dashboard') }}" class="text-white text-decoration-none d-flex align-items-center gap-2">
                <img src="{{ asset('Screenshot 2026-10-23 103504-Photoroom.png') }}" alt="Logo" style="height: 32px; width: auto; object-fit: contain;">
                <span>Bank Sampah</span>
            </a>
            <button type="button" class="btn btn-sm text-white-50 p-0 border-0 d-lg-none" id="sidebarCloseBtn" aria-label="Tutup Menu">
                <i class="bi bi-x-lg fs-5"></i>
            </button>
        </div>

        <div class="sidebar-menu">
            <div class="sidebar-heading">Menu Navigasi</div>
            <ul class="nav flex-column gap-1" id="sidebarMenuAccordion">
                @php $menus = $menus ?? collect(); @endphp
                @foreach ($menus as $menu)
                    @if ($menu->subMenus->isNotEmpty())
                        @php
                            $isMenuActive = false;
                            foreach ($menu->subMenus as $checkSub) {
                                $cUrl = $checkSub->route;
                                if ($cUrl === '#') {
                                    $t = strtolower(trim($checkSub->title));
                                    if (str_contains($t, 'nasabah')) $cUrl = route('nasabah.index');
                                    elseif (str_contains($t, 'setoran')) $cUrl = str_contains($t, 'laporan') ? route('laporan.setoran') : route('petugas.setoran.index');
                                    elseif (str_contains($t, 'penarikan')) $cUrl = str_contains($t, 'laporan') ? route('laporan.penarikan') : route('nasabah.penarikan.index');
                                    elseif (str_contains($t, 'saldo')) $cUrl = str_contains($t, 'laporan') ? route('laporan.saldo') : route('saldo.index');
                                    elseif (str_contains($t, 'riwayat')) $cUrl = route('riwayat.transaksi');
                                    elseif (str_contains($t, 'sampah') && str_contains($t, 'laporan')) $cUrl = route('laporan.sampah');
                                    elseif (str_contains($t, 'profil')) $cUrl = route('profil.index');
                                }
                                $checkPath = trim(parse_url($cUrl, PHP_URL_PATH), '/');
                                if (!empty($checkPath) && (request()->is($checkPath) || request()->is($checkPath . '/*') || request()->url() == url($cUrl))) {
                                    $isMenuActive = true;
                                    break;
                                }
                            }
                        @endphp
                        <li class="nav-item">
                            <a href="#collapse-{{ $menu->id }}" 
                                class="sidebar-link {{ $isMenuActive ? 'active' : '' }}" 
                                data-bs-toggle="collapse"
                                role="button" 
                                aria-expanded="{{ $isMenuActive ? 'true' : 'false' }}" 
                                aria-controls="collapse-{{ $menu->id }}">
                                <div>
                                    <i class="bi {{ $menu->icon }} menu-icon"></i>
                                    <span>{{ $menu->title }}</span>
                                </div>
                                <i class="bi bi-chevron-down submenu-arrow"></i>
                            </a>
                            <div class="collapse {{ $isMenuActive ? 'show' : '' }}" id="collapse-{{ $menu->id }}" data-bs-parent="#sidebarMenuAccordion">
                                <ul class="sidebar-submenu">
                                    @foreach ($menu->subMenus as $sub)
                                        @if (in_array(strtolower($sub->title), ['logout', 'keluar']))
                                            {{-- Khusus tombol Logout --}}
                                            <li>
                                                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                                                    @csrf
                                                    <button type="submit"
                                                        class="sidebar-submenu-link text-danger border-0 bg-transparent w-100 text-start d-flex align-items-center gap-1"
                                                        style="cursor: pointer;">
                                                        <i class="bi bi-box-arrow-right"></i> {{ $sub->title }}
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            @php
                                                $url = $sub->route;
                                                if ($url === '#') {
                                                    $t = strtolower(trim($sub->title));
                                                    if (str_contains($t, 'nasabah')) $url = route('nasabah.index');
                                                    elseif (str_contains($t, 'setoran')) $url = str_contains($t, 'laporan') ? route('laporan.setoran') : route('petugas.setoran.index');
                                                    elseif (str_contains($t, 'penarikan')) $url = str_contains($t, 'laporan') ? route('laporan.penarikan') : route('nasabah.penarikan.index');
                                                    elseif (str_contains($t, 'saldo')) $url = str_contains($t, 'laporan') ? route('laporan.saldo') : route('saldo.index');
                                                    elseif (str_contains($t, 'riwayat')) $url = route('riwayat.transaksi');
                                                    elseif (str_contains($t, 'sampah') && str_contains($t, 'laporan')) $url = route('laporan.sampah');
                                                    elseif (str_contains($t, 'profil')) $url = route('profil.index');
                                                }
                                                $subClean = trim(parse_url($url, PHP_URL_PATH), '/');
                                                $isSubActive = !empty($subClean) && (request()->is($subClean) || request()->is($subClean . '/*') || request()->url() == url($url));
                                            @endphp
                                            {{-- Submenu biasa --}}
                                            <li>
                                                <a href="{{ $url }}" class="sidebar-submenu-link {{ $isSubActive ? 'active' : '' }}">
                                                    • {{ $sub->title }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @else
                        @php
                            $mClean = trim(parse_url($menu->route ?? '', PHP_URL_PATH), '/');
                            $isSingleActive = !empty($mClean) && (request()->is($mClean) || request()->is($mClean . '/*'));
                        @endphp
                        <li class="nav-item">
                            <a href="{{ $menu->route ?? '#' }}" class="sidebar-link {{ $isSingleActive ? 'active' : '' }}">
                                <div>
                                    <i class="bi {{ $menu->icon }} menu-icon"></i>
                                    <span>{{ $menu->title }}</span>
                                </div>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        <!-- Profil Sidebar -->
        <div class="sidebar-profile">
            <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                style="width: 38px; height: 38px;">
                <i class="bi bi-person-fill fs-5"></i>
            </div>
            <div class="overflow-hidden">
                <div class="text-white fw-bold small text-truncate">{{ auth()->user()->name }}</div>
                <div class="text-secondary small text-capitalize" style="font-size: 0.75rem;">
                    {{ auth()->user()->role }}</div>
            </div>
        </div>
    </aside>

    <!-- KONTEN AREA UTAMA  -->
    <div class="main-wrapper">
        <!-- 1. Top Navbar -->
        <header class="main-navbar">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <!-- Tombol Hamburger (Hanya Muncul di Layar HP/Tablet) -->
                <button class="btn btn-sm btn-light border d-lg-none flex-shrink-0" type="button" id="sidebarToggle" aria-label="Buka Menu">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <span class="text-muted small fw-bold d-none d-sm-inline text-truncate">Sistem Informasi Bank Sampah</span>
                <span class="text-muted small fw-bold d-inline d-sm-none text-truncate">Bank Sampah</span>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button"
                    data-bs-toggle="dropdown">
                    <span class="me-1">{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <h6 class="dropdown-header">Role: {{ strtoupper(auth()->user()->role) }}</h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <!-- Konten Halaman  -->
        <main class="main-body">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="main-footer">
            &copy; {{ date('Y') }} Bank Sampah — Lingkungan Bersih, Rezeki Bertambah.
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <!-- SweetAlert2 Handler -->
    @include('partials.sweetalert')

    @stack('scripts')
</body>

</html>
