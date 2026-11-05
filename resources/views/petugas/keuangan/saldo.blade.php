@extends('layouts.admin')

@section('title', 'Total Saldo Keseluruhan')
@section('page_title', 'Saldo Nasabah (Keseluruhan)')
@section('page_subtitle', 'Ringkasan total tabungan / kewajiban kas Bank Sampah saat ini')

@section('content')
<div class="row">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
            <div class="card-body p-5 text-center">
                <i class="bi bi-safe2 display-3 opacity-50 mb-3"></i>
                <h6 class="text-uppercase fw-bold text-white-50">Total Saldo Aktif Seluruh Nasabah</h6>
                <h2 class="display-5 fw-bold mb-0">Rp {{ number_format($saldoTotal ?? 0, 0, ',', '.') }}</h2>
                <hr class="border-light opacity-25 my-4">
                
                <div class="d-flex justify-content-between text-start small">
                    <div>
                        <div class="text-white-50 mb-1">Total Uang Masuk</div>
                        <div class="fw-bold">Rp {{ number_format($totalSetoran ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="text-end">
                        <div class="text-white-50 mb-1">Total Uang Ditarik</div>
                        <div class="fw-bold text-warning">Rp {{ number_format($totalPenarikan ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
