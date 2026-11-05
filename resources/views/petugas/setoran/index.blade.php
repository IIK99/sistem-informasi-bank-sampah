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
                    <h5 class="fw-bold mb-1">Riwayat Transaksi Setoran Sampah Warga</h5>
                    <p class="text-muted small mb-0">Catatan penimbangan multi-item sampah masuk dan pertambahan saldo
                        rekening warga.</p>
                </div>
                <a href="{{ route('petugas.setoran.create') }}"
                    class="btn btn-success rounded-3 px-4 fw-semibold shadow-sm">
                    <i class="bi bi-cart-plus me-1"></i> + Timbang Sampah Baru
                </a>
            </div>
            <div class="card-body p-4">
                @if ($setorans->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
                        Belum ada riwayat transaksi setoran yang dicatat.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Nota</th>
                                    <th>Tanggal</th>
                                    <th>Nama Nasabah</th>
                                    <th>Rincian Sampah (Multi-Item)</th>
                                    <th>Total Saldo Masuk</th>
                                    <th>Petugas Timbang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($setorans as $item)
                                    <tr>
                                        <td class="fw-bold text-secondary">{{ $item->kode_setoran }}</td>
                                        <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                                        <td class="fw-bold">{{ $item->nasabah->name ?? '-' }}</td>
                                        <td>
                                            @if ($item->details && $item->details->isNotEmpty())
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
                                            @else
                                                <span class="badge bg-light text-dark border">
                                                    {{ $item->jenisSampah->nama_jenis ?? 'Data Lama' }}
                                                    ({{ $item->jumlah ?? $item->berat }} kg)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-success fs-6">+ Rp
                                            {{ number_format($item->total_harga ?? $item->total_nilai, 0, ',', '.') }}</td>
                                        <td class="text-muted small">{{ $item->petugas->name ?? 'Petugas' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center pt-2">
                        {{ $setorans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
