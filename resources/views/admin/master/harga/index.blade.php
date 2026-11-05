@extends('layouts.admin')

@section('title', 'Master Harga Sampah')

@section('content')
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Master Harga Sampah Pengepul</h4>
                <p class="text-muted small mb-0">Daftar harga beli per jenis sampah sesuai masing-masing pengepul.</p>
            </div>
            <button type="button" class="btn btn-success rounded-3 px-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#modalTambahHarga">
                <i class="bi bi-plus-lg"></i> Tetapkan Harga Baru
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
                                <th>Pengepul</th>
                                <th>Jenis Sampah</th>
                                <th>Satuan</th>
                                <th>Harga Beli</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hargas as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $hargas->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $item->pengepul->nama_pengepul ?? '-' }}</td>
                                    <td>{{ $item->jenisSampah->nama_jenis ?? '-' }}</td>
                                    <td>{{ $item->satuan->simbol ?? '-' }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-outline-primary border-0 me-1" title="Ubah Data"
                                            data-bs-toggle="modal" data-bs-target="#modalEditHarga{{ $item->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('admin.harga-sampah.destroy', $item->id) }}" method="POST"
                                            class="d-inline form-delete" data-item-title="ketetapan harga {{ $item->jenisSampah->nama_jenis ?? '' }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger border-0" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT HARGA SAMPAH --}}
                                <div class="modal fade" id="modalEditHarga{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="bi bi-pencil-square text-primary me-2"></i>Ubah Ketetapan Harga
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.harga-sampah.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body py-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Pengepul Pembeli <span class="text-danger">*</span></label>
                                                        <select name="pengepul_id" class="form-select rounded-3" required>
                                                            <option value="">-- Pilih Pengepul --</option>
                                                            @foreach ($pengepuls as $pengepul)
                                                                <option value="{{ $pengepul->id }}" {{ $item->pengepul_id == $pengepul->id ? 'selected' : '' }}>
                                                                    {{ $pengepul->nama_pengepul }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Jenis Sampah <span class="text-danger">*</span></label>
                                                        <select name="jenis_sampah_id" class="form-select rounded-3" required>
                                                            <option value="">-- Pilih Jenis Sampah --</option>
                                                            @foreach ($jenisSampahs as $jenis)
                                                                <option value="{{ $jenis->id }}" {{ $item->jenis_sampah_id == $jenis->id ? 'selected' : '' }}>
                                                                    {{ $jenis->nama_jenis }}
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
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Harga Beli Pengepul (Rp) <span class="text-danger">*</span></label>
                                                        <input type="number" name="harga_beli" class="form-control rounded-3" 
                                                            value="{{ old('harga_beli', $item->harga_beli) }}" required min="0" placeholder="Contoh: 3500">
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label small fw-semibold text-secondary">Tanggal Berlaku</label>
                                                        <input type="date" name="tanggal_berlaku" class="form-control rounded-3" 
                                                            value="{{ old('tanggal_berlaku', $item->tanggal_berlaku ? \Carbon\Carbon::parse($item->tanggal_berlaku)->format('Y-m-d') : '') }}">
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
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada pengaturan harga sampah pengepul.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($hargas->hasPages())
                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $hargas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH HARGA SAMPAH --}}
    <div class="modal fade" id="modalTambahHarga" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle text-success me-2"></i>Tetapkan Harga Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.harga-sampah.store') }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Pengepul Pembeli <span class="text-danger">*</span></label>
                            <select name="pengepul_id" class="form-select rounded-3 @error('pengepul_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Pengepul --</option>
                                @foreach ($pengepuls as $pengepul)
                                    <option value="{{ $pengepul->id }}" {{ old('pengepul_id') == $pengepul->id ? 'selected' : '' }}>
                                        {{ $pengepul->nama_pengepul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pengepul_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Jenis Sampah <span class="text-danger">*</span></label>
                            <select name="jenis_sampah_id" class="form-select rounded-3 @error('jenis_sampah_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Jenis Sampah --</option>
                                @foreach ($jenisSampahs as $jenis)
                                    <option value="{{ $jenis->id }}" {{ old('jenis_sampah_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama_jenis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_sampah_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Harga Beli Pengepul (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_beli" class="form-control rounded-3 @error('harga_beli') is-invalid @enderror" 
                                value="{{ old('harga_beli') }}" required min="0" placeholder="Contoh: 3500">
                            @error('harga_beli') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-semibold text-secondary">Tanggal Berlaku</label>
                            <input type="date" name="tanggal_berlaku" class="form-control rounded-3 @error('tanggal_berlaku') is-invalid @enderror" 
                                value="{{ old('tanggal_berlaku', date('Y-m-d')) }}">
                            @error('tanggal_berlaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 text-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">Simpan Harga</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
