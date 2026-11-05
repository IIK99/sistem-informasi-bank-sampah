@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1 font-weight-bold">Atur Harga Sampah: {{ $pengepul->nama_pengepul }}</h4>
                <p class="text-muted mb-0">Kode: <code>{{ $pengepul->kode }}</code> | Alamat:
                    {{ $pengepul->alamat }}</p>
            </div>
            <a href="{{ route('admin.pengepul.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Tambah / Update Harga -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="card-title fw-bold mb-3">Tambah / Perbarui Tarif Sampah</h6>
                <form action="{{ route('admin.pengepul.harga.store', $pengepul->id) }}" method="POST"
                    class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-5">
                        <label class="form-label">Jenis Sampah</label>
                        <select name="jenis_sampah_id" class="form-select @error('jenis_sampah_id') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Jenis Sampah --</option>
                            @foreach ($jenisSampahs as $sampah)
                                <option value="{{ $sampah->id }}">
                                    {{ $sampah->nama_jenis }} (Satuan: {{ $sampah->satuan->nama_satuan ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_sampah_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Harga Beli Pengepul (Rp)</label>
                        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                            placeholder="Contoh: 3500" min="0" required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle"></i> Simpan Tarif
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Harga -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold">Daftar Harga yang Diterima Pengepul</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">No</th>
                                <th>Jenis Sampah</th>
                                <th>Satuan</th>
                                <th>Harga Pengepul</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hargaList as $idx => $item)
                                <tr>
                                    <td class="ps-3">{{ $idx + 1 }}</td>
                                    <td>{{ $item->jenisSampah->nama_jenis ?? '-' }}</td>
                                    <td>{{ $item->jenisSampah->satuan->nama_satuan ?? '-' }}</td>
                                    <td><strong>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</strong></td>
                                    <td class="text-center">
                                        <form
                                            action="{{ route('admin.pengepul.harga.destroy', [$pengepul->id, $item->id]) }}"
                                            method="POST" class="d-inline form-delete" data-item-title="tarif jenis sampah {{ $item->jenisSampah->nama_jenis ?? '' }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada harga sampah yang diatur untuk pengepul ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
