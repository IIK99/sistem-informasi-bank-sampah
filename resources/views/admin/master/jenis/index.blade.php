@extends('layouts.admin')

@section('title', 'Master Jenis Sampah')

@section('content')
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Master Jenis Sampah</h4>
                <p class="text-muted small mb-0">Kelola rincian item sampah, kategori, dan takaran satuan.</p>
            </div>
            <button type="button" class="btn btn-success rounded-3 px-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#modalTambahJenis">
                <i class="bi bi-plus-lg"></i> Tambah Jenis Sampah
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
                                <th>Nama Jenis</th>
                                <th>Kategori</th>
                                <th>Satuan Default</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jenisSampahs as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $jenisSampahs->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $item->nama_jenis }}</td>
                                    <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                    <td>
                                        {{ $item->satuan->nama_satuan ?? '-' }}
                                        @if($item->satuan && $item->satuan->simbol)
                                            <span class="text-muted small">({{ $item->satuan->simbol }})</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->status === 'aktif' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-2 py-1 rounded-3">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-outline-primary border-0 me-1" title="Ubah Data"
                                            data-bs-toggle="modal" data-bs-target="#modalEditJenis{{ $item->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('admin.jenis-sampah.destroy', $item->id) }}" method="POST"
                                            class="d-inline form-delete" data-item-title="jenis sampah {{ $item->nama_jenis }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger border-0" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT JENIS SAMPAH --}}
                                <div class="modal fade" id="modalEditJenis{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="bi bi-pencil-square text-primary me-2"></i>Ubah Jenis Sampah
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.jenis-sampah.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body py-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Nama Jenis Sampah <span class="text-danger">*</span></label>
                                                        <input type="text" name="nama_jenis" class="form-control rounded-3" 
                                                            value="{{ old('nama_jenis', $item->nama_jenis) }}" required placeholder="Contoh: Kardus Tebal">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Kategori Sampah <span class="text-danger">*</span></label>
                                                        <select name="kategori_id" class="form-select rounded-3" required>
                                                            <option value="">-- Pilih Kategori --</option>
                                                            @foreach ($kategoris as $kategori)
                                                                <option value="{{ $kategori->id }}" {{ $item->kategori_id == $kategori->id ? 'selected' : '' }}>
                                                                    {{ $kategori->nama_kategori }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Satuan Ukuran <span class="text-danger">*</span></label>
                                                        <select name="satuan_id" class="form-select rounded-3" required>
                                                            <option value="">-- Pilih Satuan --</option>
                                                            @foreach ($satuans as $satuan)
                                                                <option value="{{ $satuan->id }}" {{ $item->satuan_id == $satuan->id ? 'selected' : '' }}>
                                                                    {{ $satuan->nama_satuan }} ({{ $satuan->simbol }})
                                                                </option>
                                                            @endforeach
                                                        </select>
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
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data jenis sampah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($jenisSampahs->hasPages())
                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $jenisSampahs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH JENIS SAMPAH --}}
    <div class="modal fade" id="modalTambahJenis" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle text-success me-2"></i>Tambah Jenis Sampah Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.jenis-sampah.store') }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nama Jenis Sampah <span class="text-danger">*</span></label>
                            <input type="text" name="nama_jenis" class="form-control rounded-3 @error('nama_jenis') is-invalid @enderror" 
                                value="{{ old('nama_jenis') }}" required placeholder="Contoh: Kardus Tebal">
                            @error('nama_jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Kategori Sampah <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-select rounded-3 @error('kategori_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Satuan Ukuran <span class="text-danger">*</span></label>
                            <select name="satuan_id" class="form-select rounded-3 @error('satuan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach ($satuans as $satuan)
                                    <option value="{{ $satuan->id }}" {{ old('satuan_id') == $satuan->id ? 'selected' : '' }}>
                                        {{ $satuan->nama_satuan }} ({{ $satuan->simbol }})
                                    </option>
                                @endforeach
                            </select>
                            @error('satuan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">Simpan Jenis Sampah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
