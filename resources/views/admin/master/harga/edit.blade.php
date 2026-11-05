@extends('layouts.admin')

@section('title', 'Ubah Harga Sampah')

@section('content')
    <div class="container-fluid px-0">
        <div class="mb-4">
            <a href="{{ route('admin.harga-sampah.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Harga
            </a>
            <h4 class="fw-bold mt-2">Ubah Ketetapan Harga Pengepul</h4>
        </div>

        <div class="card border-0 shadow-sm rounded-4 col-lg-6">
            <div class="card-body p-4">
                <form action="{{ route('admin.harga-sampah.update', $harga->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Pengepul</label>
                        <select name="pengepul_id" class="form-select @error('pengepul_id') is-invalid @enderror" required>
                            @foreach ($pengepuls as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('pengepul_id', $harga->pengepul_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_pengepul }}</option>
                            @endforeach
                        </select>
                        @error('pengepul_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Jenis Sampah</label>
                        <select name="jenis_sampah_id" class="form-select @error('jenis_sampah_id') is-invalid @enderror"
                            required>
                            @foreach ($jenisSampahs as $j)
                                <option value="{{ $j->id }}"
                                    {{ old('jenis_sampah_id', $harga->jenis_sampah_id) == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama_jenis }}</option>
                            @endforeach
                        </select>
                        @error('jenis_sampah_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Satuan</label>
                        <select name="satuan_id" class="form-select @error('satuan_id') is-invalid @enderror" required>
                            @foreach ($satuans as $s)
                                <option value="{{ $s->id }}"
                                    {{ old('satuan_id', $harga->satuan_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_satuan }} ({{ $s->simbol }})</option>
                            @endforeach
                        </select>
                        @error('satuan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Harga Beli Pengepul (Rp)</label>
                        <input type="number" name="harga_beli"
                            class="form-control @error('harga_beli') is-invalid @enderror"
                            value="{{ old('harga_beli', $harga->harga_beli) }}" min="0" required>
                        @error('harga_beli')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
