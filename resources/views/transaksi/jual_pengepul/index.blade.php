@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Riwayat Transaksi Jual ke Pengepul</h5>
                    <p class="text-muted small mb-0">Catatan kasir penjualan multi-item, biaya operasional, dan penerimaan
                        kas bersih.</p>
                </div>
                <a href="{{ route('jual-pengepul.create') }}"
                    class="btn btn-primary rounded-3 px-4 fw-semibold shadow-sm">
                    <i class="bi bi-cart-plus me-1"></i> + Buka Kasir Penjualan
                </a>
            </div>
            <div class="card-body p-4">
                @if ($penjualans->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-truck fs-1 d-block mb-3 text-secondary"></i>
                        Belum ada transaksi penjualan yang dicatat.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Nota</th>
                                    <th>Tanggal</th>
                                    <th>Mitra Pengepul</th>
                                    <th>Rincian Muatan (Multi-Item)</th>
                                    <th>Penjualan Kotor</th>
                                    <th>Operasional</th>
                                    <th>Kas Masuk Bersih</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penjualans as $item)
                                    <tr>
                                        <td class="fw-bold text-secondary">{{ $item->kode_penjualan }}</td>
                                        <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                                        <td class="fw-bold">{{ $item->pengepul->nama_pengepul ?? '-' }}</td>
                                        <td>
                                            <ul class="list-unstyled mb-0 small">
                                                @foreach ($item->details as $d)
                                                    <li>
                                                        • {{ $d->jenisSampah->nama_jenis ?? '-' }}:
                                                        <strong>{{ $d->jumlah }}
                                                            {{ $d->satuan->simbol ?? 'kg' }}</strong>
                                                        <span class="text-muted">(Rp
                                                            {{ number_format($d->subtotal, 0, ',', '.') }})</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-dark">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                        <td class="text-danger">
                                            @if ($item->biaya_operasional > 0)
                                                - Rp {{ number_format($item->biaya_operasional, 0, ',', '.') }}
                                                @if ($item->keterangan_operasional)
                                                    <span class="d-block text-muted"
                                                        style="font-size: 11px;">({{ $item->keterangan_operasional }})</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-success fs-6">+ Rp
                                            {{ number_format($item->total_kas_bersih, 0, ',', '.') }}</td>
                                        <td class="text-muted small">{{ $item->petugas->name ?? 'Admin' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center pt-2">
                        {{ $penjualans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
