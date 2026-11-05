@extends('layouts.admin')

@section('title', 'Edit Pengepul')

@section('content')
    <div class="container-fluid px-0">
        <div class="mb-4">
            <a href="{{ route('admin.pengepul.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pengepul
            </a>
            <h4 class="fw-bold mt-2">Ubah Data Pengepul</h4>
        </div>

        <div class="card border-0 shadow-sm rounded-4 col-lg-6">
            <div class="card-body p-4">
                <form action="{{ route('admin.pengepul.update', $pengepul->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Pengepul / Lapak</label>
                        <input type="text" name="nama_pengepul"
                            class="form-control @error('nama_pengepul') is-invalid @enderror"
                            value="{{ old('nama_pengepul', $pengepul->nama_pengepul) }}" required>
                        @error('nama_pengepul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">No. Telepon / WhatsApp</label>
                        <input type="text" name="no_telepon" class="form-control"
                            value="{{ old('no_telepon', $pengepul->no_telepon) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alamat / Lokasi Lapak</label>
                        <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $pengepul->alamat) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="aktif" {{ $pengepul->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $pengepul->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
