@extends('layouts.admin')

@section('title', 'Laporan Saldo Nasabah')
@section('page_title', 'Laporan Rekapitulasi Saldo Nasabah')
@section('page_subtitle', 'Rekap total setoran, penarikan, dan saldo aktif seluruh warga nasabah bank sampah.')

@section('content')
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-end mb-4 d-print-none">
            <a href="{{ route('laporan.saldo.pdf') }}" target="_blank" class="btn btn-danger rounded-3 px-4 shadow-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 text-white mb-4"
            style="background: linear-gradient(135deg, #198754, #0f5132);">
            <span class="text-white-50 small fw-semibold text-uppercase">Total Beban Saldo Kas Warga</span>
            <h2 class="fw-bold my-2">Rp {{ number_format($totalKasWarga, 0, ',', '.') }}</h2>
            <small class="text-white-50">Kewajiban kas fisik bank sampah yang harus siap dicairkan warga.</small>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Nasabah</th>
                                <th>Username</th>
                                <th>Total Setoran Masuk</th>
                                <th>Total Penarikan Keluar</th>
                                <th class="text-end">Sisa Saldo Tabungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nasabahs as $index => $item)
                                <tr>
                                    <td>{{ $nasabahs->firstItem() ? $nasabahs->firstItem() + $index : $index + 1 }}</td>
                                    <td class="fw-bold">{{ $item->name }}</td>
                                    <td><code>{{ $item->username }}</code></td>
                                    <td class="text-success fw-semibold">+ Rp
                                        {{ number_format($item->total_setoran, 0, ',', '.') }}</td>
                                    <td class="text-danger fw-semibold">- Rp
                                        {{ number_format($item->total_penarikan, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold text-success fs-6">
                                        Rp {{ number_format($item->saldo_aktif, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada nasabah warga yang
                                        tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top mt-2">
                    <small class="text-muted">
                        Menampilkan {{ $nasabahs->firstItem() ?? 0 }} - {{ $nasabahs->lastItem() ?? 0 }} dari {{ $nasabahs->total() ?? 0 }} data
                    </small>
                    <div>
                        {{ $nasabahs->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
