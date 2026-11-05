@extends('layouts.admin')

@section('title', 'Laporan Setoran Sampah')
@section('page_title', 'Laporan Rekap Setoran Sampah')
@section('page_subtitle', 'Rekapitulasi transaksi penimbangan sampah warga berdasarkan periode dan jenis.')

@section('content')
    <div class="container-fluid px-0">
        {{-- FILTER & TOMBOL PRINT --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
            <div class="card-body p-4">
                <form action="{{ url('/laporan-setoran') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Nasabah</label>
                        <select name="user_id" class="form-select">
                            <option value="">-- Semua Nasabah --</option>
                            @foreach ($nasabahs as $n)
                                <option value="{{ $n->id }}" {{ request('user_id') == $n->id ? 'selected' : '' }}>
                                    {{ $n->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-fill rounded-3">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('laporan.setoran.pdf', request()->query()) }}" target="_blank"
                            class="btn btn-danger rounded-3 px-3">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- KARTU REKAP --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <span class="text-muted small">Total Berat Sampah Terkumpul:</span>
                    <h3 class="fw-bold text-dark my-1">{{ number_format($totalBerat, 2, ',', '.') }} Kg</h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <span class="text-muted small">Total Akumulasi Nilai Rupiah:</span>
                    <h3 class="fw-bold text-success my-1">Rp {{ number_format($totalNominal, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        {{-- TABEL LAPORAN --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Tanggal</th>
                                <th>Nama Nasabah</th>
                                <th>Jenis Sampah</th>
                                <th>Berat</th>
                                <th>Total Nilai</th>
                                <th>Petugas Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($setorans as $index => $item)
                                <tr>
                                    <td>{{ $setorans->firstItem() ? $setorans->firstItem() + $index : $index + 1 }}</td>
                                    <td class="fw-semibold text-secondary">{{ $item->kode_setoran }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="fw-bold">{{ $item->nasabah->name ?? 'Nasabah' }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $item->jenisSampah->nama_jenis ?? $item->jenis_sampah ?? '-' }}</span></td>
                                    <td>{{ $item->jumlah ?? $item->berat ?? 0 }} kg</td>
                                    <td class="fw-bold text-success">+ Rp
                                        {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    <td class="text-muted small">{{ $item->petugas->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Tidak ada data setoran pada
                                        periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top mt-2">
                    <small class="text-muted">
                        Menampilkan {{ $setorans->firstItem() ?? 0 }} - {{ $setorans->lastItem() ?? 0 }} dari {{ $setorans->total() ?? 0 }} data
                    </small>
                    <div>
                        {{ $setorans->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
