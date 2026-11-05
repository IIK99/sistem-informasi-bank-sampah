@php
    $unreadCount = $unreadNotificationsCount ?? 0;
    $notificationsList = $userNotifications ?? collect();
@endphp

<div class="dropdown notification-dropdown-wrapper">
    <button class="btn btn-notification-bell position-relative" type="button" id="notifDropdownBtn"
        data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" title="Notifikasi Masuk">
        <i class="bi bi-bell-fill fs-5"></i>
        @if ($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notif-badge">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                <span class="visually-hidden">notifikasi baru</span>
            </span>
        @endif
    </button>

    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-0 mt-2 notification-box"
        aria-labelledby="notifDropdownBtn">
        <!-- Header Notifikasi -->
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom notification-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-bell-fill text-success fs-5"></i>
                <h6 class="mb-0 fw-bold">Notifikasi</h6>
                @if ($unreadCount > 0)
                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                        {{ $unreadCount }} baru
                    </span>
                @endif
            </div>
            @if ($unreadCount > 0)
                <form action="{{ route('notifikasi.markAllRead') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0 text-success fw-semibold"
                        style="font-size: 0.78rem;">
                        <i class="bi bi-check2-all me-1"></i>Tandai Dibaca
                    </button>
                </form>
            @endif
        </div>

        <!-- List Notifikasi -->
        <div class="notification-list-scroll">
            @forelse ($notificationsList as $notif)
                @php
                    $d = is_array($notif->data) ? $notif->data : json_decode($notif->data, true);
                    $isUnread = is_null($notif->read_at);
                    $icon = $d['icon'] ?? 'bi-bell';
                    $color = $d['color'] ?? 'success';
                    $title = $d['title'] ?? 'Pemberitahuan';
                    $msg = $d['message'] ?? '';
                @endphp
                <a href="{{ route('notifikasi.read', $notif->id) }}"
                    class="notification-item d-flex align-items-start gap-3 p-3 border-bottom text-decoration-none {{ $isUnread ? 'is-unread' : '' }}">
                    <div class="notif-icon-circle bg-{{ $color }}-subtle text-{{ $color }} flex-shrink-0">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                            <h6 class="mb-0 notif-title text-truncate {{ $isUnread ? 'fw-bold text-dark' : 'fw-semibold text-secondary' }}" style="font-size: 0.88rem;">
                                {{ $title }}
                            </h6>
                            @if ($isUnread)
                                <span class="notif-dot flex-shrink-0" title="Belum dibaca"></span>
                            @endif
                        </div>
                        <p class="mb-1 text-muted small notif-message text-break" style="font-size: 0.8rem; line-height: 1.4;">
                            {{ $msg }}
                        </p>
                        <small class="text-secondary notif-time" style="font-size: 0.72rem;">
                            <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                        </small>
                    </div>
                </a>
            @empty
                <!-- Kosong -->
                <div class="text-center py-5 px-3">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 54px; height: 54px;">
                        <i class="bi bi-bell-slash text-muted fs-3"></i>
                    </div>
                    <h6 class="fw-semibold text-secondary mb-1" style="font-size: 0.9rem;">Belum Ada Notifikasi</h6>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">
                        Aktivitas transaksi, setoran, dan penarikan saldo Anda akan muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Footer Notifikasi -->
        @if ($notificationsList->isNotEmpty())
            <div class="p-2 text-center border-top notification-footer bg-light rounded-bottom-4">
                <form action="{{ route('notifikasi.markAllRead') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-decoration-none text-secondary w-100 fw-medium"
                        style="font-size: 0.78rem;">
                        <i class="bi bi-check-circle me-1"></i>Tandai semua notifikasi telah dibaca
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<style>
/* Styling Tombol Lonceng */
.btn-notification-bell {
    background: transparent;
    border: none;
    color: inherit;
    padding: 0.45rem 0.65rem;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    cursor: pointer;
}

.btn-notification-bell:hover,
.btn-notification-bell:focus {
    background-color: rgba(22, 163, 74, 0.12);
    color: #16a34a !important;
    transform: translateY(-1px);
}

.nasabah-navbar .btn-notification-bell {
    color: rgba(255, 255, 255, 0.85);
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
    width: 38px;
    height: 38px;
    padding: 0;
}

.nasabah-navbar .btn-notification-bell:hover {
    background: rgba(255, 255, 255, 0.18);
    color: #ffffff !important;
}

.notif-badge {
    font-size: 0.65rem;
    padding: 0.25em 0.5em;
    animation: pulseBadge 2.2s infinite;
}

@keyframes pulseBadge {
    0%, 100% { transform: translate(-50%, -50%) scale(1); }
    50% { transform: translate(-50%, -50%) scale(1.15); }
}

/* Kotak Dropdown Notifikasi Smooth */
.notification-box {
    width: 340px;
    max-width: 92vw;
    animation: dropdownFadeSlide 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    transform-origin: top right;
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08) !important;
}

@keyframes dropdownFadeSlide {
    0% {
        opacity: 0;
        transform: translateY(-10px) scale(0.97);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.notification-list-scroll {
    max-height: 380px;
    overflow-y: auto;
}

.notification-list-scroll::-webkit-scrollbar {
    width: 5px;
}
.notification-list-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.notification-item {
    transition: background-color 0.2s ease;
    cursor: pointer;
}

.notification-item:hover {
    background-color: #f8fafc;
}

.notification-item.is-unread {
    background-color: #f0fdf4;
}

.notification-item.is-unread:hover {
    background-color: #dcfce7;
}

.notif-icon-circle {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
}

.notif-dot {
    width: 8px;
    height: 8px;
    background-color: #dc2626;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.25);
}

/* Dukungan Dark Mode di Nasabah */
[data-bs-theme="dark"] .notification-box {
    background: #1e293b !important;
    border-color: #334155 !important;
    color: #f8fafc;
}

[data-bs-theme="dark"] .notification-header,
[data-bs-theme="dark"] .notification-item {
    border-color: #334155 !important;
}

[data-bs-theme="dark"] .notification-item:hover {
    background-color: #334155;
}

[data-bs-theme="dark"] .notification-item.is-unread {
    background-color: rgba(22, 163, 74, 0.15);
}

[data-bs-theme="dark"] .notification-item.is-unread:hover {
    background-color: rgba(22, 163, 74, 0.25);
}

[data-bs-theme="dark"] .notification-item.is-unread .notif-title {
    color: #f8fafc !important;
}

[data-bs-theme="dark"] .notification-footer {
    background-color: #0f172a !important;
    border-color: #334155 !important;
}
</style>
