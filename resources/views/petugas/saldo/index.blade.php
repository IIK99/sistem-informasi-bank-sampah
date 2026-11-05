@extends('layouts.admin')

@section('title', 'Rekap Saldo Nasabah')
@section('page_title', 'Rekapitulasi Saldo Nasabah')
@section('page_subtitle', 'Pantau akumulasi saldo tabungan seluruh warga desa.')

@section('content')
    <div class="container-fluid px-0">
        {{-- KARTU RINGKASAN TOTAL SALDO SEMUA NASABAH --}}
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-white"
                    style="background: linear-gradient(135deg, #198754, #0f5132);">
                    <span class="text-white-50 small fw-semibold text-uppercase">Total Saldo Semua Nasabah</span>
                    <h2 class="fw-bold my-2">Rp {{ number_format($totalSaldoWarga, 0, ',', '.') }}</h2>
                    <small class="text-white-50"><i class="bi bi-shield-check me-1"></i> Total kewajiban kas bank
                        sampah</small>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <span class="text-secondary small fw-semibold text-uppercase">Jumlah Nasabah Terdaftar</span>
                    <h2 class="fw-bold my-2 text-dark">{{ $totalNasabah }} Orang</h2>
                    <small class="text-muted"><i class="bi bi-people me-1"></i> Akun warga aktif menabung</small>
                </div>
            </div>
        </div>

        {{-- TABEL RINCIAN SALDO PER NASABAH --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Rincian Saldo Akun Warga</h5>
            </div>
            <div class="card-body p-4">
                @if ($daftarNasabah->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-3 text-secondary"></i>
                        Belum ada nasabah terdaftar di sistem.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Warga</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th class="text-end">Sisa Saldo Tabungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($daftarNasabah as $index => $item)
                                    <tr>
                                        <td>{{ $daftarNasabah->firstItem() + $index }}</td>
                                        <td class="fw-bold">{{ $item->name }}</td>
                                        <td><code>{{ $item->username }}</code></td>
                                        <td class="text-muted small">{{ $item->email ?? '-' }}</td>
                                        <td class="text-end fw-bold text-success fs-6">
                                            Rp {{ number_format($item->saldo, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex flex-column align-items-center justify-content-center gap-1 pt-3">
                        <div>
                            {{ $daftarNasabah->links('pagination::bootstrap-5') }}
                        </div>
                        <small class="text-muted">
                            Menampilkan {{ $daftarNasabah->firstItem() ?? 0 }} - {{ $daftarNasabah->lastItem() ?? 0 }} dari
                            {{ $daftarNasabah->total() ?? 0 }} nasabah
                        </small>
                    </div> 
                @endif
            </div>
        </div>
    </div>
@endsection
