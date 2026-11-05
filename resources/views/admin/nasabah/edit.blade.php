@extends('layouts.admin')

@section('title', 'Ubah Data Nasabah')
@section('page_title', 'Perbarui Data Nasabah')
@section('page_subtitle', 'Edit data identitas akun nasabah warga.')

@section('content')
    <div class="container-fluid px-0">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    <form action="{{ route('nasabah.update', $nasabah->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold small text-secondary">Nama Lengkap
                                Warga</label>
                            <input type="text" name="name" id="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror"
                                value="{{ old('name', $nasabah->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Username & No HP & Email --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold small text-secondary">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username"
                                    class="form-control rounded-3 @error('username') is-invalid @enderror"
                                    value="{{ old('username', $nasabah->username) }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="no_hp" class="form-label fw-semibold small text-secondary">No. Handphone / WA</label>
                                <input type="text" name="no_hp" id="no_hp"
                                    class="form-control rounded-3 @error('no_hp') is-invalid @enderror"
                                    value="{{ old('no_hp', $nasabah->no_hp) }}" placeholder="Contoh: 08123456789">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small text-secondary">Email</label>
                            <input type="email" name="email" id="email"
                                class="form-control rounded-3 @error('email') is-invalid @enderror"
                                value="{{ old('email', $nasabah->email) }}" placeholder="Contoh: budi@gmail.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password Baru (Opsional) --}}
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold small text-secondary">
                                Password Baru <span class="text-muted fw-normal">(Kosongkan jika tidak ingin mengubah
                                    password)</span>
                            </label>
                            <input type="password" name="password" id="password"
                                class="form-control rounded-3 @error('password') is-invalid @enderror"
                                placeholder="Ketik password baru">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('nasabah.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
                            <button type="submit" class="btn btn-success rounded-3 px-4 fw-semibold shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
