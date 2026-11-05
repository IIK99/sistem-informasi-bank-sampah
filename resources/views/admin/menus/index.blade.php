@extends('layouts.admin')

@section('title', 'Manajemen Menu')
@section('page_title', 'Manajemen Menu & Submenu')
@section('page_subtitle', 'Kelola struktur navigasi sistem secara dinamis tanpa menyentuh kode template.')

@section('content')
    <div class="container-fluid px-0">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <!-- Form Tambah Menu Induk  -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <h5 class="fw-bold mb-1 text-dark">Menu</h5>
                    <p class="text-muted small mb-4">Buat header atau kelompok menu baru di sidebar.</p>

                    <form action="{{ route('admin.menus.storeMenu') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Menu</label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Logistik"
                                required>
                        </div>
                        <div class="row">
                            <!-- Dropdown Icon  -->
                            <div class="col-7 mb-3">
                                <label class="form-label small fw-semibold">Icon</label>
                                <div class="icon-picker-group">
                                    <input type="hidden" name="icon" class="icon-input" value="bi-speedometer2"
                                        required>
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-between dropdown-toggle bg-white text-dark py-2 border"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="d-flex align-items-center gap-2 icon-preview">
                                                <span class="small">-- Pilih Icon --</span>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu w-100 shadow border-0 rounded-3 p-2"
                                            style="max-height: 250px; overflow-y: auto;">
                                            @foreach ($icons as $class => $label)
                                                <li>
                                                    <button type="button"
                                                        class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 btn-select-icon"
                                                        data-icon="{{ $class }}" data-label="{{ $label }}">
                                                        <i class="bi {{ $class }} fs-5 text-success"></i>
                                                        <span class="small">{{ $label }}</span>
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Nomor Urut -->
                            <div class="col-5 mb-3">
                                <label class="form-label small fw-semibold">Urutan</label>
                                <input type="number" name="order" class="form-control" value="{{ $nextMenuOrder }}"
                                    required>
                            </div>
                        </div>

                        <!-- Checkbox Role Induk -->
                        <div class="mb-3 border-top pt-3">
                            <label class="form-label small fw-semibold">Pilih Hak Akses Role</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="admin"
                                        id="role_admin_menu" checked>
                                    <label class="form-check-label small" for="role_admin_menu">Admin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="petugas"
                                        id="role_petugas_menu">
                                    <label class="form-check-label small" for="role_petugas_menu">Petugas</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="nasabah"
                                        id="role_nasabah_menu">
                                    <label class="form-check-label small" for="role_nasabah_menu">Nasabah</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-3 mt-2">
                            + Simpan Menu Induk
                        </button>
                    </form>
                </div>
            </div>

            <!--  Form Tambah Submenu  -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <h5 class="fw-bold mb-1 text-dark">Submenu</h5>
                    <p class="text-muted small mb-4">Kaitkan tautan halaman spesifik ke dalam menu.</p>

                    <form action="{{ route('admin.menus.storeSubMenu') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-semibold">Pilih Menu </label>
                                <select name="menu_id" class="form-select" required>
                                    <option value="">-- Pilih Menu --</option>
                                    {{-- Gunakan $allMenus agar pilihan tidak terpotong pagination --}}
                                    @foreach ($allMenus as $m)
                                        <option value="{{ $m->id }}">{{ $m->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-semibold">Nama Submenu</label>
                                <input type="text" name="title" class="form-control" placeholder="Contoh: Pengiriman"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8 mb-3">
                                <label class="form-label small fw-semibold">Alamat Route / URL</label>
                                <input type="text" name="route" class="form-control"
                                    placeholder="Contoh: /admin/pengiriman atau #" required>
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label small fw-semibold">Urutan</label>
                                <input type="number" name="order" class="form-control"
                                    value="{{ $nextSubMenuOrder }}" required>
                            </div>
                        </div>

                        <!-- Checkbox Role Submenu -->
                        <div class="mb-3 border-top pt-3">
                            <label class="form-label small fw-semibold">Pilih Hak Akses Role</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="admin"
                                        id="role_admin_sub" checked>
                                    <label class="form-check-label small" for="role_admin_sub">Admin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="petugas"
                                        id="role_petugas_sub">
                                    <label class="form-check-label small" for="role_petugas_sub">Petugas</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="nasabah"
                                        id="role_nasabah_sub">
                                    <label class="form-check-label small" for="role_nasabah_sub">Nasabah</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-3 mt-2">
                            + Simpan Submenu
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabel Daftar Menu & Submenu --}}
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3 text-dark">Daftar Struktur Menu Saat Ini</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">Urutan</th>
                            <th>Menu & Submenu</th>
                            <th>Icon / URL</th>
                            <th>Hak Akses</th> <!-- Kolom Badge Role -->
                            <th style="width: 100px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                            {{-- BARIS MENU UTAMA --}}
                            <tr class="table-secondary fw-bold">
                                <td>{{ $menu->order }}</td>
                                <td>
                                    <i class="bi {{ $menu->icon }} me-2"></i> {{ $menu->title }}
                                </td>
                                <td><span class="badge bg-dark font-monospace">{{ $menu->icon }}</span></td>

                                {{-- Menampilkan Badge Role --}}
                                <td>
                                    @if (!empty($menu->roles) && is_array($menu->roles))
                                        @foreach ($menu->roles as $role)
                                            <span class="badge bg-success text-capitalize me-1">{{ $role }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-secondary">Tidak ada</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary border-0"
                                            data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $menu->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form action="{{ route('admin.menus.destroyMenu', $menu->id) }}" method="POST"
                                            class="form-delete d-inline" data-item-title="menu utama {{ $menu->name }} beserta submenunya">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT MENU UTAMA --}}
                            <div class="modal fade" id="editMenuModal{{ $menu->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Ubah Menu</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.menus.updateMenu', $menu->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Nama Menu</label>
                                                    <input type="text" name="title" class="form-control"
                                                        value="{{ $menu->title }}" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-7 mb-3">
                                                        <label class="form-label small fw-semibold">Icon</label>

                                                        <div class="icon-picker-group">
                                                            <input type="hidden" name="icon" class="icon-input"
                                                                value="{{ $menu->icon }}" required>

                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-between dropdown-toggle bg-white text-dark py-2 border"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    <span
                                                                        class="d-flex align-items-center gap-2 icon-preview">
                                                                        <i
                                                                            class="bi {{ $menu->icon }} text-success fs-5"></i>
                                                                        <span
                                                                            class="small">{{ $icons[$menu->icon] ?? $menu->icon }}</span>
                                                                    </span>
                                                                </button>
                                                                <ul class="dropdown-menu w-100 shadow border-0 rounded-3 p-2"
                                                                    style="max-height: 250px; overflow-y: auto;">
                                                                    @foreach ($icons as $class => $label)
                                                                        <li>
                                                                            <button type="button"
                                                                                class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 btn-select-icon"
                                                                                data-icon="{{ $class }}"
                                                                                data-label="{{ $label }}">
                                                                                <i
                                                                                    class="bi {{ $class }} fs-5 text-success"></i>
                                                                                <span
                                                                                    class="small">{{ $label }}</span>
                                                                            </button>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-5 mb-3">
                                                        <label class="form-label small fw-semibold">Urutan</label>
                                                        <input type="number" name="order" class="form-control"
                                                            value="{{ $menu->order }}" required>
                                                    </div>
                                                </div>

                                                <!-- Checkbox Role untuk Update -->
                                                <div class="mb-3 border-top pt-3">
                                                    <label class="form-label small fw-semibold">Pilih Hak Akses
                                                        Role</label>
                                                    <div class="d-flex gap-3">
                                                        @php $currentRoles = is_array($menu->roles) ? $menu->roles : []; @endphp
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="roles[]" value="admin"
                                                                id="edit_role_admin_m{{ $menu->id }}"
                                                                {{ in_array('admin', $currentRoles) ? 'checked' : '' }}>
                                                            <label class="form-check-label small"
                                                                for="edit_role_admin_m{{ $menu->id }}">Admin</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="roles[]" value="petugas"
                                                                id="edit_role_petugas_m{{ $menu->id }}"
                                                                {{ in_array('petugas', $currentRoles) ? 'checked' : '' }}>
                                                            <label class="form-check-label small"
                                                                for="edit_role_petugas_m{{ $menu->id }}">Petugas</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="roles[]" value="nasabah"
                                                                id="edit_role_nasabah_m{{ $menu->id }}"
                                                                {{ in_array('nasabah', $currentRoles) ? 'checked' : '' }}>
                                                            <label class="form-check-label small"
                                                                for="edit_role_nasabah_m{{ $menu->id }}">Nasabah</label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-3"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success rounded-3 px-4">Simpan
                                                    Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- BARIS SUBMENU --}}
                            @foreach ($menu->subMenus as $sub)
                                <tr>
                                    <td class="text-end text-muted small pe-4">{{ $sub->order }}</td>
                                    <td class="ps-4">
                                        <span class="text-muted me-1">↳</span> {{ $sub->title }}
                                    </td>
                                    <td><code class="text-secondary">{{ $sub->route }}</code></td>

                                    {{-- Menampilkan Badge Role --}}
                                    <td>
                                        @if (!empty($sub->roles) && is_array($sub->roles))
                                            @foreach ($sub->roles as $role)
                                                <span
                                                    class="badge bg-info text-dark text-capitalize me-1">{{ $role }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-secondary">Tidak ada</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary border-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editSubMenuModal{{ $sub->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <form action="{{ route('admin.menus.destroySubMenu', $sub->id) }}"
                                                method="POST" class="form-delete d-inline" data-item-title="submenu {{ $sub->name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT SUBMENU --}}
                                <div class="modal fade" id="editSubMenuModal{{ $sub->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">Ubah Submenu</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.menus.updateSubMenu', $sub->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Pilih Menu</label>
                                                        <select name="menu_id" class="form-select" required>
                                                            {{-- Gunakan $allMenus agar pilihan tidak terpotong pagination --}}
                                                            @foreach ($allMenus as $m)
                                                                <option value="{{ $m->id }}"
                                                                    {{ $m->id == $sub->menu_id ? 'selected' : '' }}>
                                                                    {{ $m->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Nama Submenu</label>
                                                        <input type="text" name="title" class="form-control"
                                                            value="{{ $sub->title }}" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-8 mb-3">
                                                            <label class="form-label small fw-semibold">Alamat Route /
                                                                URL</label>
                                                            <input type="text" name="route" class="form-control"
                                                                value="{{ $sub->route }}" required>
                                                        </div>
                                                        <div class="col-4 mb-3">
                                                            <label class="form-label small fw-semibold">Urutan</label>
                                                            <input type="number" name="order" class="form-control"
                                                                value="{{ $sub->order }}" required>
                                                        </div>
                                                    </div>

                                                    <!-- Checkbox Role untuk Update -->
                                                    <div class="mb-3 border-top pt-3">
                                                        <label class="form-label small fw-semibold">Pilih Hak Akses
                                                            Role</label>
                                                        <div class="d-flex gap-3">
                                                            @php $currentSubRoles = is_array($sub->roles) ? $sub->roles : []; @endphp
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="roles[]" value="admin"
                                                                    id="edit_role_admin_s{{ $sub->id }}"
                                                                    {{ in_array('admin', $currentSubRoles) ? 'checked' : '' }}>
                                                                <label class="form-check-label small"
                                                                    for="edit_role_admin_s{{ $sub->id }}">Admin</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="roles[]" value="petugas"
                                                                    id="edit_role_petugas_s{{ $sub->id }}"
                                                                    {{ in_array('petugas', $currentSubRoles) ? 'checked' : '' }}>
                                                                <label class="form-check-label small"
                                                                    for="edit_role_petugas_s{{ $sub->id }}">Petugas</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="roles[]" value="nasabah"
                                                                    id="edit_role_nasabah_s{{ $sub->id }}"
                                                                    {{ in_array('nasabah', $currentSubRoles) ? 'checked' : '' }}>
                                                                <label class="form-check-label small"
                                                                    for="edit_role_nasabah_s{{ $sub->id }}">Nasabah</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-3"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit"
                                                        class="btn btn-success rounded-3 px-4">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Navigasi Pagination  --}}
            <div class="d-flex flex-column align-items-center justify-content-center gap-1 mt-4 pt-3 border-top">
                <div>
                    {{ $menus->links('pagination::bootstrap-5') }}
                </div>
                <span class="text-muted small">
                    Menampilkan {{ $menus->firstItem() ?? 0 }}–{{ $menus->lastItem() ?? 0 }} dari
                    {{ $menus->total() }} menu induk
                </span>
            </div>

        </div>
    </div>
@endsection

