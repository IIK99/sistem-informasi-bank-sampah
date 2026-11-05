@extends('layouts.admin')

@section('title', 'Ubah Data petugas')
@section('page_title', 'Perbarui Data petugas')
@section('page_subtitle', 'Edit data identitas akun petugas warga.')

@section('content')
    <div class="container-fluid px-0">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    <form action="{{ route('admin.kelola-petugas.update', $petugas->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold small text-secondary">Nama Lengkap
                                Warga</label>
                            <input type="text" name="name" id="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror"
                                value="{{ old('name', $petugas->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Username & Email --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold small text-secondary">Username</label>
                                <input type="text" name="username" id="username"
                                    class="form-control rounded-3 @error('username') is-invalid @enderror"
                                    value="{{ old('username', $petugas->username) }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold small text-secondary">Email</label>
                                <input type="email" name="email" id="email"
                                    class="form-control rounded-3 @error('email') is-invalid @enderror"
                                    value="{{ old('email', $petugas->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                            <a href="{{ route('admin.kelola-petugas.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
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

