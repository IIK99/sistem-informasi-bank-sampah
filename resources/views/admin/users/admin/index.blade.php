@extends('layouts.admin')

@section('title', 'Kelola Admin')
@section('page_title', 'Manajemen Akun Administrator')

@section('content')
    <div class="container-fluid px-0">
        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            {{-- Card Header: Search & Tambah --}}
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <form action="{{ route('admin.kelola-admin.index') }}" method="GET" class="d-flex gap-2 form-live-search"
                        style="max-width: 340px; width: 100%;">
                        <div class="position-relative flex-grow-1">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control rounded-start-pill live-search-input"
                                    placeholder="Cari nama / username / email..." value="{{ request('search') }}" autocomplete="off">
                                <button class="btn btn-outline-secondary rounded-end-pill px-3" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div class="search-suggestions-box position-absolute start-0 w-100 bg-white rounded-4 shadow-lg border mt-1 d-none"
                                style="top: 100%; z-index: 1060; max-height: 320px; overflow-y: auto;">
                            </div>
                        </div>
                        @if (request('search'))
                            <a href="{{ route('admin.kelola-admin.index') }}" class="btn btn-light rounded-3 px-3"
                                title="Reset Filter">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </form>

                    <button type="button" class="btn btn-success rounded-3 px-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
                        <i class="bi bi-person-plus me-1"></i> Tambah Admin Baru
                    </button>
                </div>
            </div>

            {{-- Card Body: Tabel --}}
            <div class="card-body p-4">
                @if ($admins->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-shield-lock fs-1 d-block mb-3 text-secondary"></i>
                        @if (request('search'))
                            Tidak ditemukan admin dengan kata kunci "<strong>{{ request('search') }}</strong>".
                        @else
                            Belum ada data admin yang terdaftar.
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Terdaftar Sejak</th>
                                    <th class="text-center" style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $index => $item)
                                    <tr>
                                        <td>{{ $admins->firstItem() + $index }}</td>
                                        <td class="fw-bold">
                                            {{ $item->name }}
                                            @if ($item->id == Auth::id())
                                                <i class="bi bi-check-circle-fill text-success me-2" title="Akun Anda Saat Ini"></i>
                                            @endif
                                        </td>
                                        <td><code>{{ $item->username }}</code></td>
                                        <td class="text-muted small">{{ $item->email ?: '-' }}</td>
                                        <td class="text-muted small">{{ $item->created_at->format('d M Y') }}</td>
                                        <td class="text-center">
                                            <div class="d-inline-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-primary border-0" title="Ubah Data"
                                                    data-bs-toggle="modal" data-bs-target="#modalEditAdmin{{ $item->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                @if ($item->id != Auth::id())
                                                    <form action="{{ route('admin.kelola-admin.destroy', $item->id) }}" method="POST"
                                                        class="d-inline form-delete" data-item-title="akun admin {{ $item->name }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                                                            title="Hapus Akun">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- MODAL EDIT ADMIN --}}
                                    <div class="modal fade" id="modalEditAdmin{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="bi bi-pencil-square text-primary me-2"></i>Ubah Akun Administrator
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.kelola-admin.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body py-4">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control rounded-3" 
                                                                value="{{ old('name', $item->name) }}" required placeholder="Contoh: Siti Aminah">
                                                        </div>
                                                        <div class="row g-3 mb-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label small fw-semibold text-secondary">Username <span class="text-danger">*</span></label>
                                                                <input type="text" name="username" class="form-control rounded-3" 
                                                                    value="{{ old('username', $item->username) }}" required placeholder="Contoh: siti_admin">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label small fw-semibold text-secondary">Email</label>
                                                                <input type="email" name="email" class="form-control rounded-3" 
                                                                    value="{{ old('email', $item->email) }}" placeholder="Contoh: siti@email.com">
                                                            </div>
                                                        </div>
                                                        <div class="mb-0">
                                                            <label class="form-label small fw-semibold text-secondary">
                                                                Password Baru <span class="text-muted fw-normal">(Kosongkan jika tidak diubah)</span>
                                                            </label>
                                                            <input type="password" name="password" class="form-control rounded-3" 
                                                                placeholder="Minimal 6 karakter">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-0">
                                                        <button type="button" class="btn btn-light rounded-pill px-4 text-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi & Info Counter --}}
                    <div class="d-flex flex-column align-items-center justify-content-center gap-1 pt-3">
                        <div>
                            {{ $admins->links('pagination::bootstrap-5') }}
                        </div>
                        <small class="text-muted">
                            Menampilkan {{ $admins->firstItem() ?? 0 }} - {{ $admins->lastItem() ?? 0 }} dari
                            {{ $admins->total() ?? 0 }} admin
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH ADMIN --}}
    <div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-person-plus text-success me-2"></i>Tambah Admin Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.kelola-admin.store') }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" required placeholder="Contoh: Siti Aminah">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control rounded-3 @error('username') is-invalid @enderror" 
                                    value="{{ old('username') }}" required placeholder="Contoh: siti_admin">
                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Email</label>
                                <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" placeholder="Contoh: siti@email.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-semibold text-secondary">Password Akun <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror" 
                                required placeholder="Minimal 6 karakter">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 text-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">Simpan Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection