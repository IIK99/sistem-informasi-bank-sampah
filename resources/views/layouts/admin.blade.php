<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Bank Sampah</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('Screenshot-2026-10-23-103504-Photoroom.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('Screenshot-2026-10-23-103504-Photoroom.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.pwa_head')
    @stack('styles')
</head>

<body>

    <!-- SIDEBAR  -->
    <aside class="sidebar-wrapper">
        <div class="sidebar-brand justify-content-between">
            <a href="{{ route('dashboard') }}" class="text-white text-decoration-none d-flex align-items-center gap-2">
                <img src="{{ asset('Screenshot 2026-10-23 103504-Photoroom.png') }}" alt="Logo"
                    style="height: 32px; width: auto; object-fit: contain;">
                <span>Bank Sampah</span>
            </a>
            <button type="button" class="btn btn-sm text-white-50 p-0 border-0 d-lg-none" id="sidebarCloseBtn"
                aria-label="Tutup Menu">
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
                                    if (str_contains($t, 'admin')) {
                                        $cUrl = url('/admin/kelola-admin');
                                    } elseif (str_contains($t, 'petugas')) {
                                        $cUrl = url('/admin/kelola-petugas');
                                    } elseif (str_contains($t, 'nasabah')) {
                                        $cUrl = route('nasabah.index');
                                    } elseif (str_contains($t, 'setoran')) {
                                        $cUrl = str_contains($t, 'laporan')
                                            ? route('laporan.setoran')
                                            : route('petugas.setoran.index');
                                    } elseif (str_contains($t, 'penarikan')) {
                                        $cUrl = str_contains($t, 'laporan')
                                            ? route('laporan.penarikan')
                                            : route('nasabah.penarikan.index');
                                    } elseif (str_contains($t, 'saldo')) {
                                        $cUrl = str_contains($t, 'laporan')
                                            ? route('laporan.saldo')
                                            : route('saldo.index');
                                    } elseif (str_contains($t, 'riwayat')) {
                                        $cUrl = route('riwayat.transaksi');
                                    } elseif (str_contains($t, 'sampah') && str_contains($t, 'laporan')) {
                                        $cUrl = route('laporan.sampah');
                                    } elseif (str_contains($t, 'profil')) {
                                        $cUrl = route('profil.index');
                                    }
                                }
                                $checkPath = trim(parse_url($cUrl, PHP_URL_PATH), '/');
                                if (
                                    !empty($checkPath) &&
                                    (request()->is($checkPath) ||
                                        request()->is($checkPath . '/*') ||
                                        request()->url() == url($cUrl))
                                ) {
                                    $isMenuActive = true;
                                    break;
                                }
                            }
                        @endphp
                        @php
                            $parentBadgeCount = 0;
                            foreach ($menu->subMenus as $chkBadge) {
                                $st = strtolower(trim($chkBadge->title));
                                if (str_contains($st, 'penarikan')) {
                                    $parentBadgeCount += $sidebarBadges['penarikan'] ?? 0;
                                } elseif (str_contains($st, 'setoran')) {
                                    $parentBadgeCount += $sidebarBadges['setoran'] ?? 0;
                                } elseif (str_contains($st, 'pengepul')) {
                                    $parentBadgeCount += $sidebarBadges['jual_pengepul'] ?? 0;
                                }
                            }
                        @endphp
                        <li class="nav-item">
                            <a href="#collapse-{{ $menu->id }}"
                                class="sidebar-link {{ $isMenuActive ? 'active' : '' }}" data-bs-toggle="collapse"
                                role="button" aria-expanded="{{ $isMenuActive ? 'true' : 'false' }}"
                                aria-controls="collapse-{{ $menu->id }}">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi {{ $menu->icon }} menu-icon"></i>
                                    <span>{{ $menu->title }}</span>
                                    @if ($parentBadgeCount > 0)
                                        <span class="sidebar-notif-dot bg-danger rounded-circle d-inline-block ms-1"
                                            style="width: 8px; height: 8px; box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.4);"></span>
                                    @endif
                                </div>
                                <i class="bi bi-chevron-down submenu-arrow"></i>
                            </a>
                            <div class="collapse {{ $isMenuActive ? 'show' : '' }}" id="collapse-{{ $menu->id }}"
                                data-bs-parent="#sidebarMenuAccordion">
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
                                                    if (str_contains($t, 'admin')) {
                                                        $url = url('/admin/kelola-admin');
                                                    } elseif (str_contains($t, 'petugas')) {
                                                        $url = url('/admin/kelola-petugas');
                                                    } elseif (str_contains($t, 'nasabah')) {
                                                        $url = route('nasabah.index');
                                                    } elseif (str_contains($t, 'setoran')) {
                                                        $url = str_contains($t, 'laporan')
                                                            ? route('laporan.setoran')
                                                            : route('petugas.setoran.index');
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
                                                    } elseif (
                                                        str_contains($t, 'sampah') &&
                                                        str_contains($t, 'laporan')
                                                    ) {
                                                        $url = route('laporan.sampah');
                                                    } elseif (str_contains($t, 'profil')) {
                                                        $url = route('profil.index');
                                                    }
                                                }
                                                $subClean = trim(parse_url($url, PHP_URL_PATH), '/');
                                                $isSubActive =
                                                    !empty($subClean) &&
                                                    (request()->is($subClean) ||
                                                        request()->is($subClean . '/*') ||
                                                        request()->url() == url($url));

                                                $subTitleLower = strtolower(trim($sub->title));
                                                $menuBadge = 0;
                                                $subCategory = '';
                                                if (str_contains($subTitleLower, 'penarikan')) {
                                                    $menuBadge = $sidebarBadges['penarikan'] ?? 0;
                                                    $subCategory = 'penarikan';
                                                } elseif (str_contains($subTitleLower, 'setoran')) {
                                                    $menuBadge = $sidebarBadges['setoran'] ?? 0;
                                                    $subCategory = 'setoran';
                                                } elseif (str_contains($subTitleLower, 'pengepul')) {
                                                    $menuBadge = $sidebarBadges['jual_pengepul'] ?? 0;
                                                    $subCategory = 'jual-pengepul';
                                                }
                                            @endphp
                                            {{-- Submenu biasa --}}
                                            <li>
                                                <a href="{{ $url }}"
                                                    class="sidebar-submenu-link d-flex align-items-center justify-content-between {{ $isSubActive ? 'active' : '' }}"
                                                    @if (!empty($subCategory)) data-notif-category="{{ $subCategory }}" @endif>
                                                    <span>• {{ $sub->title }}</span>
                                                    @if ($menuBadge > 0)
                                                        <span
                                                            class="sidebar-notif-dot bg-danger rounded-circle d-inline-block"
                                                            style="width: 8px; height: 8px; box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.4);"></span>
                                                    @endif
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
                            $isSingleActive =
                                !empty($mClean) && (request()->is($mClean) || request()->is($mClean . '/*'));

                            $singleTitleLower = strtolower(trim($menu->title));
                            $singleBadge = 0;
                            $singleCategory = '';
                            if (str_contains($singleTitleLower, 'penarikan')) {
                                $singleBadge = $sidebarBadges['penarikan'] ?? 0;
                                $singleCategory = 'penarikan';
                            } elseif (str_contains($singleTitleLower, 'setoran')) {
                                $singleBadge = $sidebarBadges['setoran'] ?? 0;
                                $singleCategory = 'setoran';
                            } elseif (str_contains($singleTitleLower, 'pengepul')) {
                                $singleBadge = $sidebarBadges['jual_pengepul'] ?? 0;
                                $singleCategory = 'jual-pengepul';
                            }
                        @endphp
                        <li class="nav-item">
                            <a href="{{ $menu->route ?? '#' }}"
                                class="sidebar-link d-flex align-items-center justify-content-between {{ $isSingleActive ? 'active' : '' }}"
                                @if (!empty($singleCategory)) data-notif-category="{{ $singleCategory }}" @endif>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi {{ $menu->icon }} menu-icon"></i>
                                    <span>{{ $menu->title }}</span>
                                </div>
                                @if ($singleBadge > 0)
                                    <span class="sidebar-notif-dot bg-danger rounded-circle d-inline-block"
                                        style="width: 8px; height: 8px; box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.4);"></span>
                                @endif
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
                <button class="btn btn-sm btn-light border d-lg-none flex-shrink-0" type="button" id="sidebarToggle"
                    aria-label="Buka Menu">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <span class="text-muted small fw-bold d-none d-sm-inline text-truncate">Sistem Informasi Bank
                    Sampah</span>
                <span class="text-muted small fw-bold d-inline d-sm-none text-truncate">Bank Sampah</span>
            </div>

            <div class="d-flex align-items-center gap-2">
                @include('partials.notification_bell')

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

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/jual_pengepul.js') }}"></script>
    <script src="{{ asset('js/setoran.js') }}"></script>

    <!-- Realtime Sidebar Notification Clear Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            document.querySelectorAll('[data-notif-category]').forEach(function(el) {
                el.addEventListener('click', function() {
                    const category = this.getAttribute('data-notif-category');

                    // 1. REALTIME: Hilangkan dot merah di item yang diklik seketika tanpa menunggu refresh
                    const dot = this.querySelector('.sidebar-notif-dot');
                    if (dot) {
                        dot.remove();
                    }

                    // 2. Jika di dalam collapse/submenu, periksa apakah sisa dot lain masih ada
                    const collapseParent = this.closest('.collapse');
                    if (collapseParent) {
                        const remainingDots = collapseParent.querySelectorAll('.sidebar-notif-dot');
                        if (remainingDots.length === 0) {
                            const parentToggle = document.querySelector(
                                `[data-bs-target="#${collapseParent.id}"], [href="#${collapseParent.id}"]`
                            );
                            if (parentToggle) {
                                const parentDot = parentToggle.querySelector('.sidebar-notif-dot');
                                if (parentDot) parentDot.remove();
                            }
                        }
                    }

                    // 3. Kirim update ke server di background dengan keepalive
                    if (category && csrfToken) {
                        fetch('{{ route('notifikasi.markCategoryRead') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                category: category
                            }),
                            keepalive: true
                        }).then(r => r.json()).then(data => {
                            if (data && typeof data.unread !== 'undefined') {
                                const bellBadge = document.querySelector(
                                    '.btn-notification-bell .notif-badge');
                                if (bellBadge) {
                                    if (data.unread > 0) {
                                        bellBadge.textContent = data.unread > 99 ? '99+' :
                                            data.unread;
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

    <!-- SweetAlert2 Handler -->
    @include('partials.sweetalert')

    @include('partials.pwa_script')
    @stack('scripts')
</body>

</html>
