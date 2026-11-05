@extends('layouts.admin')

@section('title', 'Edit Satuan')

@section('content')
    <div class="container-fluid px-0">
        <div class="mb-4">
            <a href="{{ route('admin.satuan.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Satuan
            </a>
            <h4 class="fw-bold mt-2">Ubah Satuan</h4>
        </div>

        <div class="card border-0 shadow-sm rounded-4 col-lg-6">
            <div class="card-body p-4">
                <form action="{{ route('admin.satuan.update', $satuan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Satuan</label>
                        <input type="text" name="nama_satuan"
                            class="form-control @error('nama_satuan') is-invalid @enderror"
                            value="{{ old('nama_satuan', $satuan->nama_satuan) }}" required>
                        @error('nama_satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Simbol</label>
                        <input type="text" name="simbol" class="form-control @error('simbol') is-invalid @enderror"
                            value="{{ old('simbol', $satuan->simbol) }}" required>
                        @error('simbol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
