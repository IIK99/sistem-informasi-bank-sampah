@extends('layouts.nasabah')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <h4 class="fw-bold mb-1">Buku Tabungan / Mutasi Kas</h4>
            <p class="text-muted small mb-0">Semua catatan transaksi pemasukan hasil sampah dan pencairan dana tunai Anda.
            </p>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                @if ($mutasi->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-receipt fs-1 d-block mb-3 text-secondary"></i>
                        Belum ada rekaman transaksi buku tabungan.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Ref</th>
                                    <th>Keterangan Transaksi</th>
                                    <th>Status</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mutasi as $trx)
                                    <tr>
                                        <td class="text-muted small">{{ $trx->tanggal->format('d M Y, H:i') }}</td>
                                        <td class="fw-semibold small text-secondary">{{ $trx->kode }}</td>
                                        <td>{{ $trx->keterangan }}</td>
                                        <td>
                                            @if ($trx->status === 'selesai' || $trx->status === 'disetujui')
                                                <span
                                                    class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3">Selesai</span>
                                            @elseif($trx->status === 'menunggu')
                                                <span
                                                    class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-3">Diproses</span>
                                            @else
                                                <span
                                                    class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-3">Ditolak</span>
                                            @endif
                                        </td>
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
