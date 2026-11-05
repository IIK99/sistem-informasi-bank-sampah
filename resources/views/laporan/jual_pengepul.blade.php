@extends('layouts.admin')

@section('title', 'Laporan Jual ke Pengepul')

@section('content')
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Laporan Penjualan ke Pengepul</h4>
                <p class="text-muted small mb-0">Rekapitulasi penjualan muatan gudang, beban operasional, dan arus kas masuk
                    bersih.</p>
            </div>
            <a href="{{ route('laporan.jual-pengepul.pdf', request()->query()) }}" target="_blank"
                class="btn btn-outline-dark rounded-pill px-4 shadow-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Laporan PDF
            </a>
        </div>

        <!-- Filter Form -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('laporan.jual-pengepul') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Mitra Pengepul</label>
                        <select name="pengepul_id" class="form-select">
                            <option value="">-- Semua Mitra Pengepul --</option>
                            @foreach ($pengepuls as $p)
                                <option value="{{ $p->id }}"
                                    {{ request('pengepul_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_pengepul }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-filter"></i> Filter</button>
                        <a href="{{ route('laporan.jual-pengepul') }}" class="btn btn-light border"><i
                                class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ringkasan Finansial Card -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <span class="text-secondary small fw-semibold text-uppercase">Total Penjualan Kotor</span>
                    <h4 class="fw-bold my-2 text-dark">Rp {{ number_format($totalKotor, 0, ',', '.') }}</h4>
                    <small class="text-muted"><i class="bi bi-cash-stack me-1"></i> Akumulasi bruto muatan</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <span class="text-secondary small fw-semibold text-uppercase">Total Biaya Operasional</span>
                    <h4 class="fw-bold my-2 text-danger">- Rp {{ number_format($totalOperasional, 0, ',', '.') }}</h4>
                    <small class="text-muted"><i class="bi bi-truck me-1"></i> Logistik, bensin, dan kuli</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-white"
                    style="background: linear-gradient(135deg, #198754, #0f5132);">
                    <span class="text-white-50 small fw-semibold text-uppercase">Total Kas Bersih Masuk</span>
                    <h4 class="fw-bold my-2 text-white">+ Rp {{ number_format($totalKasBersih, 0, ',', '.') }}</h4>
                    <small class="text-white-50"><i class="bi bi-wallet2 me-1"></i> Riil masuk kas bank sampah</small>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Nota</th>
                                <th>Tanggal</th>
                                <th>Mitra Pengepul</th>
                                <th>Rincian Muatan (Multi-Item)</th>
                                <th>Penjualan Kotor</th>
                                <th>Operasional</th>
                                <th>Kas Bersih</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penjualans as $item)
                                <tr>
                                    <td class="fw-semibold"><code>{{ $item->kode_penjualan }}</code></td>
                                    <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                                    <td class="fw-bold">{{ $item->pengepul->nama_pengepul ?? '-' }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0 small">
                                            @foreach ($item->details as $d)
                                                <li>• {{ $d->jenisSampah->nama_jenis ?? 'Sampah' }} ({{ $d->jumlah }}
                                                    {{ $d->jenisSampah->satuan->simbol ?? 'kg' }}): Rp
                                                    {{ number_format($d->subtotal, 0, ',', '.') }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="fw-semibold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    <td class="text-danger fw-semibold">
                                        {{ $item->biaya_operasional > 0 ? '- Rp ' . number_format($item->biaya_operasional, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="fw-bold text-success">+ Rp
                                        {{ number_format($item->total_kas_bersih, 0, ',', '.') }}</td>
                                    <td class="small text-muted">{{ $item->petugas->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Belum ada transaksi penjualan ke
                                        pengepul untuk periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top mt-2">
                    <small class="text-muted">
                        Menampilkan {{ $penjualans->firstItem() ?? 0 }} - {{ $penjualans->lastItem() ?? 0 }} dari {{ $penjualans->total() ?? 0 }} data
                    </small>
                    <div>
                        {{ $penjualans->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
