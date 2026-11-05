@extends('layouts.admin')

@section('title', 'Tambah petugas')
@section('page_title', 'Pendaftaran petugas Warga')
@section('page_subtitle', 'Buat akun login baru agar warga dapat mengakses portal bank sampah.')

@section('content')
    <div class="container-fluid px-0">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    <form action="{{ route('admin.kelola-petugas.store') }}" method="POST">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold small text-secondary">Nama Lengkap
                                Warga</label>
                            <input type="text" name="name" id="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror"
                                placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Username & Email --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold small text-secondary">Username (Untuk
                                    Login)</label>
                                <input type="text" name="username" id="username"
                                    class="form-control rounded-3 @error('username') is-invalid @enderror"
                                    placeholder="Contoh: budi01" value="{{ old('username') }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold small text-secondary">Email
                                    (Opsional)</label>
                                <input type="email" name="email" id="email"
                                    class="form-control rounded-3 @error('email') is-invalid @enderror"
                                    placeholder="Contoh: budi@gmail.com" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold small text-secondary">Password Akun</label>
                            <input type="password" name="password" id="password"
                                class="form-control rounded-3 @error('password') is-invalid @enderror"
                                placeholder="Minimal 6 karakter" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.kelola-petugas.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
                            <button type="submit" class="btn btn-success rounded-3 px-4 fw-semibold shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Akun petugas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

