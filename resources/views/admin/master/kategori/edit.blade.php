@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <div class="container-fluid px-0">
        <div class="mb-4">
            <a href="{{ route('admin.kategori-sampah.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kategori
            </a>
            <h4 class="fw-bold mt-2">Ubah Kategori</h4>
        </div>

        <div class="card border-0 shadow-sm rounded-4 col-lg-6">
            <div class="card-body p-4">
                <form action="{{ route('admin.kategori-sampah.update', $kategoriSampah->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kategori</label>
                        <input type="text" name="nama_kategori"
                            class="form-control @error('nama_kategori') is-invalid @enderror"
                            value="{{ old('nama_kategori', $kategoriSampah->nama_kategori) }}" required>
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $kategoriSampah->deskripsi) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
