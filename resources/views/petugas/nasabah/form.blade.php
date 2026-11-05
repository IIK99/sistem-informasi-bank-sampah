@extends('layouts.admin')

@section('title', isset($nasabah) ? 'Edit Nasabah' : 'Tambah Nasabah')
@section('page_title', isset($nasabah) ? 'Edit Data Nasabah' : 'Tambah Data Nasabah')
@section('page_subtitle', 'Formulir data akun nasabah')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ isset($nasabah) ? route('nasabah.update', $nasabah->id) : route('nasabah.store') }}" method="POST">
                    @csrf
                    @if(isset($nasabah))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $nasabah->name ?? '') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Username</label>
                        <input type="text" name="username" class="form-control rounded-3 @error('username') is-invalid @enderror" value="{{ old('username', $nasabah->username ?? '') }}" required>
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">No. Handphone / WA</label>
                        <input type="text" name="no_hp" class="form-control rounded-3 @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $nasabah->no_hp ?? '') }}" placeholder="Contoh: 08123456789">
                        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Email <span class="text-muted">(Opsional)</span></label>
                        <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror" value="{{ old('email', $nasabah->email ?? '') }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Password {!! isset($nasabah) ? '<span class="text-muted">(Kosongkan jika tidak ingin diubah)</span>' : '' !!}</label>
                        <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror" {{ isset($nasabah) ? '' : 'required' }}>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-20px px-4">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                        <a href="{{ route('nasabah.index') }}" class="btn btn-light border rounded-20px px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
