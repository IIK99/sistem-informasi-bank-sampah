@extends('layouts.admin')

@section('title', 'Kelola Nasabah')
@section('page_title', 'Manajemen Data Nasabah')
@section('page_subtitle', 'Daftar seluruh warga nasabah bank sampah yang terdaftar di sistem.')

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
                    <form action="{{ route('nasabah.index') }}" method="GET" class="d-flex gap-2 form-live-search"
                        style="max-width: 340px; width: 100%;">
                        <div class="position-relative flex-grow-1">
                            <div class="input-group">
                                <input type="text" name="search"
                                    class="form-control rounded-start-pill live-search-input"
                                    placeholder="Cari nama / username..." value="{{ request('search') }}"
                                    autocomplete="off">
                                <button class="btn btn-outline-secondary rounded-end-pill px-3" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div class="search-suggestions-box position-absolute start-0 w-100 bg-white rounded-4 shadow-lg border mt-1 d-none"
                                style="top: 100%; z-index: 1060; max-height: 320px; overflow-y: auto;">
                            </div>
                        </div>
                        @if (request('search'))
                            <a href="{{ route('nasabah.index') }}" class="btn btn-light rounded-3 px-3"
                                title="Reset Filter">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </form>

                    @canAction('Nasabah', 'create')
                    <button type="button"
                        class="btn btn-success rounded-3 px-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal" data-bs-target="#modalTambahNasabah">
                        <i class="bi bi-person-plus me-1"></i> Tambah Nasabah Baru
                    </button>
                    @endcanAction
                </div>
            </div>

            {{-- Card Body: Tabel --}}
            <div class="card-body p-4">
                @if ($nasabahs->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-3 text-secondary"></i>
                        @if (request('search'))
                            Tidak ditemukan nasabah dengan kata kunci "<strong>{{ request('search') }}</strong>".
                        @else
                            Belum ada data nasabah yang terdaftar.
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
                                    <th>Kontak</th>
                                    <th>Saldo Tabungan</th>
                                    <th>Terdaftar Sejak</th>
                                    <th class="text-center" style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nasabahs as $index => $item)
                                    <tr>
                                        <td>{{ $nasabahs->firstItem() + $index }}</td>
                                        <td class="fw-bold">{{ $item->name }}</td>
                                        <td><code>{{ $item->username }}</code></td>
                                        <td class="text-muted small">{{ $item->email ?: '-' }}</td>
                                        <td>
                                            @if ($item->no_hp)
                                                @php
                                                    $cleanWa = preg_replace('/[^0-9]/', '', $item->no_hp);
                                                    if (str_starts_with($cleanWa, '0')) {
                                                        $cleanWa = '62' . substr($cleanWa, 1);
                                                    }
                                                @endphp
                                                <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="text-success text-decoration-none fw-semibold d-inline-flex align-items-center gap-1" title="Kirim Pesan WhatsApp">
                                                    <i class="bi bi-whatsapp"></i>
                                                    <span>{{ $item->no_hp }}</span>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-success">Rp {{ number_format($item->saldo, 0, ',', '.') }}
                                        </td>
                                        <td class="text-muted small">{{ $item->created_at->format('d M Y') }}</td>
                                        <td class="text-center">
                                            <div class="d-inline-flex gap-1">
                                                @canAction('Nasabah', 'update')
                                                <button type="button" class="btn btn-sm btn-outline-primary border-0"
                                                    title="Ubah Data" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditNasabah{{ $item->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                @endcanAction

                                                @canAction('Nasabah', 'delete')
                                                <form action="{{ route('nasabah.destroy', $item->id) }}" method="POST"
                                                    class="d-inline form-delete"
                                                    data-item-title="akun nasabah {{ $item->name }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                                                        title="Hapus Akun">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                @endcanAction
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- MODAL EDIT NASABAH --}}
                                    <div class="modal fade" id="modalEditNasabah{{ $item->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="bi bi-pencil-square text-primary me-2"></i>Ubah Akun
                                                        Nasabah
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('nasabah.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body py-4">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold text-secondary">Nama
                                                                Lengkap Warga <span class="text-danger">*</span></label>
                                                            <input type="text" name="name"
                                                                class="form-control rounded-3"
                                                                value="{{ old('name', $item->name) }}" required
                                                                placeholder="Contoh: Budi Santoso">
                                                        </div>
                                                        <div class="row g-3 mb-3">
                                                            <div class="col-md-6">
                                                                <label
                                                                    class="form-label small fw-semibold text-secondary">Username
                                                                    <span class="text-danger">*</span></label>
                                                                <input type="text" name="username"
                                                                    class="form-control rounded-3"
                                                                    value="{{ old('username', $item->username) }}"
                                                                    required placeholder="Contoh: budi01">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label
                                                                    class="form-label small fw-semibold text-secondary">No. Handphone / WA</label>
                                                                <input type="text" name="no_hp"
                                                                    class="form-control rounded-3"
                                                                    value="{{ old('no_hp', $item->no_hp) }}"
                                                                    placeholder="Contoh: 08123456789">
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label
                                                                class="form-label small fw-semibold text-secondary">Email <span class="text-muted fw-normal">(Opsional)</span></label>
                                                            <input type="email" name="email"
                                                                class="form-control rounded-3"
                                                                value="{{ old('email', $item->email) }}"
                                                                placeholder="Contoh: budi@gmail.com">
                                                        </div>
                                                        <div class="mb-0">
                                                            <label class="form-label small fw-semibold text-secondary">
                                                                Password Baru <span class="text-muted fw-normal">(Kosongkan
                                                                    jika tidak diubah)</span>
                                                            </label>
                                                            <input type="password" name="password"
                                                                class="form-control rounded-3"
                                                                placeholder="Minimal 6 karakter">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-0">
                                                        <button type="button"
                                                            class="btn btn-light rounded-pill px-4 text-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit"
                                                            class="btn btn-primary rounded-pill px-4 fw-semibold">Simpan
                                                            Perubahan</button>
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
                            {{ $nasabahs->links('pagination::bootstrap-5') }}
                        </div>
                        <small class="text-muted">
                            Menampilkan {{ $nasabahs->firstItem() ?? 0 }} - {{ $nasabahs->lastItem() ?? 0 }} dari
                            {{ $nasabahs->total() ?? 0 }} nasabah
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH NASABAH --}}
    <div class="modal fade" id="modalTambahNasabah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-person-plus text-success me-2"></i>Tambah Nasabah Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('nasabah.store') }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nama Lengkap Warga <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Username <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="username"
                                    class="form-control rounded-3 @error('username') is-invalid @enderror"
                                    value="{{ old('username') }}" required placeholder="Contoh: budi01">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">No. Handphone / WA</label>
                                <input type="text" name="no_hp"
                                    class="form-control rounded-3 @error('no_hp') is-invalid @enderror"
                                    value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Email <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="email" name="email"
                                class="form-control rounded-3 @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="Contoh: budi@gmail.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-semibold text-secondary">Password Akun <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="password"
                                class="form-control rounded-3 @error('password') is-invalid @enderror" required
                                placeholder="Minimal 6 karakter">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 text-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">Simpan
                            Nasabah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
