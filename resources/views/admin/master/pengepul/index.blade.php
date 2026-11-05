@extends('layouts.admin')

@section('title', 'Master Pengepul')

@section('content')
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Master Pengepul</h4>
                <p class="text-muted small mb-0">Kelola data pembeli/pengepul sampah pihak ketiga.</p>
            </div>
            <button type="button" class="btn btn-success rounded-3 px-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#modalTambahPengepul">
                <i class="bi bi-plus-lg"></i> Tambah Pengepul
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
                                <th>Kode</th>
                                <th>Nama Pengepul</th>
                                <th>Kontak</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Harga Sampah</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengepuls as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $pengepuls->firstItem() + $index }}</td>
                                    <td><code>{{ $item->kode ?? '-' }}</code></td>
                                    <td class="fw-semibold">{{ $item->nama_pengepul }}</td>
                                    <td>
                                        <div><i class="bi bi-telephone text-muted me-1"></i>{{ $item->no_telepon ?? '-' }}</div>
                                    </td>
                                    <td class="text-muted small">{{ $item->alamat ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $item->status === 'aktif' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-2 py-1 rounded-3">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.pengepul.harga.index', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-tag-fill"></i> Atur Harga
                                        </a>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-outline-primary border-0 me-1" title="Ubah Data"
                                            data-bs-toggle="modal" data-bs-target="#modalEditPengepul{{ $item->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('admin.pengepul.destroy', $item->id) }}" method="POST"
                                            class="d-inline form-delete" data-item-title="data pengepul {{ $item->nama_pengepul }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger border-0" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT PENGEPUL --}}
                                <div class="modal fade" id="modalEditPengepul{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="bi bi-pencil-square text-primary me-2"></i>Ubah Data Pengepul
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.pengepul.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body py-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Kode Pengepul <span class="text-danger">*</span></label>
                                                        <input type="text" name="kode" class="form-control rounded-3" 
                                                            value="{{ old('kode', $item->kode) }}" required placeholder="Contoh: P-001">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Nama Pengepul / Lapak <span class="text-danger">*</span></label>
                                                        <input type="text" name="nama_pengepul" class="form-control rounded-3" 
                                                            value="{{ old('nama_pengepul', $item->nama_pengepul) }}" required placeholder="Contoh: UD Berkah Rongsok">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">No. Telepon / WhatsApp</label>
                                                        <input type="text" name="no_telepon" class="form-control rounded-3" 
                                                            value="{{ old('no_telepon', $item->no_telepon) }}" placeholder="Contoh: 08123456789">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Alamat / Lokasi Lapak</label>
                                                        <textarea name="alamat" class="form-control rounded-3" rows="3" 
                                                            placeholder="Alamat gudang/lapak...">{{ old('alamat', $item->alamat) }}</textarea>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label small fw-semibold text-secondary">Status Operasional <span class="text-danger">*</span></label>
                                                        <select name="status" class="form-select rounded-3" required>
                                                            <option value="aktif" {{ $item->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                            <option value="nonaktif" {{ $item->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                        </select>
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
                                    <td colspan="8" class="text-center py-4 text-muted">Belum ada data pengepul.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($pengepuls->hasPages())
                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $pengepuls->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH PENGEPUL --}}
    <div class="modal fade" id="modalTambahPengepul" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle text-success me-2"></i>Tambah Pengepul Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.pengepul.store') }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Kode Pengepul <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control rounded-3 @error('kode') is-invalid @enderror" 
                                value="{{ old('kode') }}" required placeholder="Contoh: P-001">
                            @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nama Pengepul / Lapak <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pengepul" class="form-control rounded-3 @error('nama_pengepul') is-invalid @enderror" 
                                value="{{ old('nama_pengepul') }}" required placeholder="Contoh: UD Berkah Rongsok">
                            @error('nama_pengepul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">No. Telepon / WhatsApp</label>
                            <input type="text" name="no_telepon" class="form-control rounded-3 @error('no_telepon') is-invalid @enderror" 
                                value="{{ old('no_telepon') }}" placeholder="Contoh: 08123456789">
                            @error('no_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Alamat / Lokasi Lapak</label>
                            <textarea name="alamat" class="form-control rounded-3 @error('alamat') is-invalid @enderror" rows="3" 
                                placeholder="Alamat gudang/lapak...">{{ old('alamat') }}</textarea>
                            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-semibold text-secondary">Status Operasional <span class="text-danger">*</span></label>
                            <select name="status" class="form-select rounded-3" required>
                                <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 text-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">Simpan Pengepul</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
