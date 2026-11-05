@extends('layouts.admin')

@section('title', 'Tambah Jenis Sampah')

@section('content')
    <div class="container-fluid px-0">
        <div class="mb-4">
            <a href="{{ route('admin.jenis-sampah.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Jenis Sampah
            </a>
            <h4 class="fw-bold mt-2">Tambah Jenis Sampah Baru</h4>
        </div>

        <div class="card border-0 shadow-sm rounded-4 col-lg-6">
            <div class="card-body p-4">
                <form action="{{ route('admin.jenis-sampah.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Jenis Sampah</label>
                        <input type="text" name="nama_jenis"
                            class="form-control @error('nama_jenis') is-invalid @enderror" value="{{ old('nama_jenis') }}"
                            placeholder="Contoh: Kardus Lembaran" required>
                        @error('nama_jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kategori Sampah</label>
                        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Satuan Takaran Default</label>
                        <select name="satuan_id" class="form-select @error('satuan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Satuan --</option>
                            @foreach ($satuans as $sat)
                                <option value="{{ $sat->id }}" {{ old('satuan_id') == $sat->id ? 'selected' : '' }}>
                                    {{ $sat->nama_satuan }} ({{ $sat->simbol }})</option>
                            @endforeach
                        </select>
                        @error('satuan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Jenis Sampah</button>
                </form>
            </div>
        </div>
    </div>
@endsection
