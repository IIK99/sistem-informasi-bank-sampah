@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Rekapitulasi Riwayat Transaksi')
@section('page_subtitle', 'Semua rekaman aktivitas setoran sampah masuk dan penarikan kas nasabah.')

@section('content')
    <div class="container-fluid px-0">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Arus Transaksi Bank Sampah</h5>
            </div>
            <div class="card-body p-4">
                @if ($semuaTransaksi->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-receipt fs-1 d-block mb-3 text-secondary"></i>
                        Belum ada riwayat transaksi yang tercatat di sistem.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Kode Ref</th>
                                    <th>Nama Nasabah</th>
                                    <th>Aktivitas Transaksi</th>
                                    <th>Status</th>
                                    <th>Petugas</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($semuaTransaksi as $trx)
                                    <tr>
                                        <td class="text-muted small">{{ $trx->tanggal->format('d M Y, H:i') }}</td>
                                        <td class="fw-semibold small text-secondary">{{ $trx->kode }}</td>
                                        <td class="fw-bold">{{ $trx->nasabah }}</td>
                                        <td>{{ $trx->keterangan }}</td>
                                        <td>
                                            @if ($trx->status === 'selesai' || $trx->status === 'disetujui')
                                                <span
                                                    class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3">Selesai</span>
                                            @elseif($trx->status === 'menunggu')
                                                <span
                                                    class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-3">Menunggu</span>
                                            @else
                                                <span
                                                    class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-3">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">{{ $trx->petugas }}</td>
                                        <td
                                            class="text-end fw-bold {{ $trx->tipe === 'masuk' ? 'text-success' : 'text-danger' }}">
                                            {{ $trx->tipe === 'masuk' ? '+' : '-' }} Rp
                                            {{ number_format($trx->nominal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
