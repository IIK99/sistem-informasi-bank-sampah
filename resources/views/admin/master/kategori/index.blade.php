@extends('layouts.admin')

@section('title', 'Master Kategori Sampah')

@section('content')
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Kategori Sampah</h4>
                <p class="text-muted small mb-0">Kelola kelompok jenis sampah (Plastik, Kertas, Logam, dll).</p>
            </div>
            <button type="button" class="btn btn-success rounded-3 px-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                <i class="bi bi-plus-lg"></i> Tambah Kategori
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $kategoris->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $item->nama_kategori }}</td>
                                    <td class="text-muted small">{{ $item->deskripsi ?? '-' }}</td>
                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-outline-primary border-0 me-1" title="Ubah Data"
                                            data-bs-toggle="modal" data-bs-target="#modalEditKategori{{ $item->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('admin.kategori-sampah.destroy', $item->id) }}"
                                            method="POST" class="d-inline form-delete"
                                            data-item-title="kategori {{ $item->nama_kategori }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger border-0" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT KATEGORI --}}
                                <div class="modal fade" id="modalEditKategori{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="bi bi-pencil-square text-primary me-2"></i>Ubah Kategori Sampah
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.kategori-sampah.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body py-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Nama Kategori <span class="text-danger">*</span></label>
                                                        <input type="text" name="nama_kategori" class="form-control rounded-3" 
                                                            value="{{ old('nama_kategori', $item->nama_kategori) }}" required placeholder="Contoh: Plastik">
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label small fw-semibold text-secondary">Deskripsi Singkat</label>
                                                        <textarea name="deskripsi" class="form-control rounded-3" rows="3" 
                                                            placeholder="Keterangan kategori sampah...">{{ old('deskripsi', $item->deskripsi) }}</textarea>
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
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($kategoris->hasPages())
                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $kategoris->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH KATEGORI --}}
    <div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle text-success me-2"></i>Tambah Kategori Sampah
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.kategori-sampah.store') }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" class="form-control rounded-3 @error('nama_kategori') is-invalid @enderror" 
                                value="{{ old('nama_kategori') }}" required placeholder="Contoh: Plastik">
                            @error('nama_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-semibold text-secondary">Deskripsi Singkat</label>
                            <textarea name="deskripsi" class="form-control rounded-3 @error('deskripsi') is-invalid @enderror" rows="3" 
                                placeholder="Keterangan kategori sampah...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 text-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
