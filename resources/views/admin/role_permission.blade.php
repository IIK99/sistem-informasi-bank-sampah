@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-3">

        {{-- Notifikasi Berhasil Simpan --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- KOLOM KIRI: PILIH ROLE --}}
            <div class="col-lg-3 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="mb-3 px-2">
                        <h6 class="fw-bold text-dark mb-1">Akses & Peran</h6>
                        <small class="text-muted">Pilih peran untuk mengatur matriks hak akses</small>
                    </div>

                    <div class="list-group list-group-flush gap-2">
                        @foreach ($roles as $keyRole => $roleLabel)
                            <a href="{{ route('admin.role-permissions.index', ['role' => $keyRole]) }}"
                                class="list-group-item list-group-item-action border-0 rounded-3 p-3 d-flex justify-content-between align-items-center {{ $selectedRole === $keyRole ? 'bg-success text-white active fw-semibold shadow-sm' : 'bg-light text-secondary' }}">
                                <div>
                                    <div class="text-capitalize">{{ $keyRole }}</div>
                                    <small class="{{ $selectedRole === $keyRole ? 'text-white-50' : 'text-muted' }}"
                                        style="font-size: 0.75rem;">
                                        {{ $keyRole === 'admin' ? 'Akses modul penuh' : ($keyRole === 'petugas' ? 'Operasional setoran' : 'Portal buku tabungan') }}
                                    </small>
                                </div>
                                <i
                                    class="bi {{ $selectedRole === $keyRole ? 'bi-shield-check' : 'bi-chevron-right' }} fs-5"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: MATRIKS TOGGLE PERMISSION --}}
            <div class="col-lg-9 col-md-8">
                <form action="{{ route('admin.role-permissions.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role" value="{{ $selectedRole }}">

                    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                        {{-- Header Kartu --}}
                        <div
                            class="card-header bg-white border-bottom p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1 text-capitalize">Peran: {{ $selectedRole }}</h5>
                                <p class="text-muted small mb-0">Atur hak akses tiap modul (Lihat, Tambah, Ubah, Hapus)
                                    untuk peran ini.</p>
                            </div>
                            <button type="submit" class="btn btn-success px-4 py-2 rounded-3 shadow-sm fw-semibold">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                        {{-- Tabel Matriks Modul --}}
                        <div class="card-body p-4">
                            @foreach ($menus as $menu)
                                <div class="mb-4 pb-3 border-bottom">
                                    <div class="d-flex align-items-center gap-2 mb-3 text-success fw-bold">
                                        <i class="bi {{ $menu->icon ?? 'bi-folder' }} fs-5"></i>
                                        <span class="fs-6 text-uppercase"
                                            style="letter-spacing: 0.5px;">{{ $menu->title }}</span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-borderless align-middle mb-0">
                                            <thead>
                                                <tr class="text-muted small border-bottom">
                                                    <th style="width: 40%;">Modul / Submenu</th>
                                                    <th class="text-center" style="width: 15%;">Lihat</th>
                                                    <th class="text-center" style="width: 15%;">Tambah</th>
                                                    <th class="text-center" style="width: 15%;">Ubah</th>
                                                    <th class="text-center" style="width: 15%;">Hapus</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($subMenus->get($menu->id, []) as $sub)
                                                    @php
                                                        $perm = $permissions->get($sub->id);
                                                    @endphp
                                                    <tr class="border-bottom-subtle">
                                                        <td>
                                                            <div class="fw-semibold text-dark">{{ $sub->title }}</div>
                                                        </td>
                                                        {{-- Sakelar Lihat --}}
                                                        <td class="text-center">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input class="form-check-input" type="checkbox"
                                                                    role="switch"
                                                                    name="permissions[{{ $sub->id }}][view]"
                                                                    value="1"
                                                                    {{ $perm && $perm->can_view ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        {{-- Sakelar Tambah --}}
                                                        <td class="text-center">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input class="form-check-input" type="checkbox"
                                                                    role="switch"
                                                                    name="permissions[{{ $sub->id }}][create]"
                                                                    value="1"
                                                                    {{ $perm && $perm->can_create ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        {{-- Sakelar Ubah --}}
                                                        <td class="text-center">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input class="form-check-input" type="checkbox"
                                                                    role="switch"
                                                                    name="permissions[{{ $sub->id }}][update]"
                                                                    value="1"
                                                                    {{ $perm && $perm->can_update ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        {{-- Sakelar Hapus --}}
                                                        <td class="text-center">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input class="form-check-input" type="checkbox"
                                                                    role="switch"
                                                                    name="permissions[{{ $sub->id }}][delete]"
                                                                    value="1"
                                                                    {{ $perm && $perm->can_delete ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-muted fst-italic small">Belum ada
                                                            submenu</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
