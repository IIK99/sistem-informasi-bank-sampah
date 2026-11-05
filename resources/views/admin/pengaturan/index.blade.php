@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page_title', 'Pengaturan Sistem')
@section('page_subtitle', 'Mengatur konfigurasi sistem')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 text-center">
                <i class="bi bi-gear display-1 text-secondary mb-3"></i>
                <h4 class="fw-bold">Pengaturan Sistem</h4>
                <p class="text-muted">Halaman ini digunakan untuk mengatur konfigurasi sistem Bank Sampah. Fitur lengkap sedang dalam pengembangan.</p>
                <div class="alert alert-info d-inline-block mt-3">
                    <i class="bi bi-info-circle me-2"></i> Konfigurasi aplikasi seperti Nama Aplikasi, Logo, dll akan tersedia di sini.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
