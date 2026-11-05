@extends('layouts.admin')

@section('title', 'Tambah Kategori Sampah')

@section('content')
    <div class="container-fluid px-0">
        <div class="mb-4">
            <a href="{{ route('admin.kategori-sampah.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kategori
            </a>
            <h4 class="fw-bold mt-2">Tambah Kategori Baru</h4>
        </div>

        <div class="card border-0 shadow-sm rounded-4 col-lg-6">
            <div class="card-body p-4">
                <form action="{{ route('admin.kategori-sampah.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kategori</label>
                        <input type="text" name="nama_kategori"
                            class="form-control @error('nama_kategori') is-invalid @enderror"
                            value="{{ old('nama_kategori') }}" placeholder="Contoh: Plastik" required>
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Deskripsi (Opsional)</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan kategori sampah...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Kategori</button>
                </form>
            </div>
        </div>
    </div>
@endsection
