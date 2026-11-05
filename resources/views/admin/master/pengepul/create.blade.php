@extends('layouts.admin')

@section('title', 'Tambah Pengepul')

@section('content')
    <div class="container-fluid px-0">
        <div class="mb-4">
            <a href="{{ route('admin.pengepul.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pengepul
            </a>
            <h4 class="fw-bold mt-2">Tambah Pengepul Baru</h4>
        </div>

        <div class="card border-0 shadow-sm rounded-4 col-lg-6">
            <div class="card-body p-4">
                <form action="{{ route('admin.pengepul.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kode Pengepul</label>
                        <input type="text" name="kode" class="form-control" placeholder="Contoh: P-001"
                            value="{{ old('kode', $pengepul->kode ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Pengepul / Lapak</label>
                        <input type="text" name="nama_pengepul"
                            class="form-control @error('nama_pengepul') is-invalid @enderror"
                            value="{{ old('nama_pengepul') }}" placeholder="Contoh: UD Berkah Rongsok" required>
                        @error('nama_pengepul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">No. Telepon / WhatsApp</label>
                        <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon') }}"
                            placeholder="Contoh: 08123456789">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alamat / Lokasi Lapak</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat gudang/lapak...">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Pengepul</button>
                </form>
            </div>
        </div>
    </div>
@endsection
