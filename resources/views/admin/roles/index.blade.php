@extends('layouts.admin')

@section('title', 'Manajemen Peran')
@section('page_title', 'Manajemen Peran')
@section('page_subtitle', 'Kelola peran pengguna dan akses dalam sistem.')

@section('content')
    <div class="container-fluid px-0">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <h5 class="fw-bold mb-1 text-dark">Tambah Peran</h5>
                    <p class="text-muted small mb-4">Buat peran baru di dalam sistem.</p>

                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Peran</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Pengepul" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 rounded-3">
                            <i class="bi bi-plus-circle me-1"></i> Simpan Peran
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1 text-dark">Daftar Peran</h5>
                        <p class="text-muted small mb-4">Daftar peran yang terdaftar di dalam sistem.</p>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Peran</th>
                                        <th>Slug</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $index => $role)
                                        <tr>
                                            <td>{{ $roles->firstItem() + $index }}</td>
                                            <td class="fw-bold">{{ $role->name }}</td>
                                            <td><span class="badge bg-secondary">{{ $role->slug }}</span></td>
                                            <td class="text-center">
                                                <div class="d-inline-flex gap-1">
                                                    @if (!in_array($role->slug, ['admin', 'petugas', 'nasabah']))
                                                        <!-- Edit Modal Trigger -->
                                                        <button class="btn btn-sm btn-outline-primary border-0"
                                                            data-bs-toggle="modal" data-bs-target="#editRole{{ $role->id }}"
                                                            title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>

                                                        <!-- Hapus Form -->
                                                        <form action="{{ route('admin.roles.destroy', $role->id) }}"
                                                            method="POST" class="d-inline form-delete" data-item-title="peran {{ $role->name }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                                                                title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted small">Default</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        @if (!in_array($role->slug, ['admin', 'petugas', 'nasabah']))
                                            <div class="modal fade" id="editRole{{ $role->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 rounded-4 shadow">
                                                        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header border-0 pb-0 pt-4 px-4">
                                                                <h5 class="modal-title fw-bold">Edit Peran</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body px-4 py-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-semibold">Nama Peran</label>
                                                                    <input type="text" name="name" class="form-control"
                                                                        value="{{ $role->name }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                                                                <button type="button" class="btn btn-light rounded-3"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary rounded-3">
                                                                    Simpan Perubahan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $roles->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
