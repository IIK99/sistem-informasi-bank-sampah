@extends('layouts.admin')

@section('title', 'Laporan Rekap Sampah')
@section('page_title', 'Laporan Akumulasi Sampah')
@section('page_subtitle', 'Rekap jumlah berat dan estimasi nilai per jenis sampah yang disetorkan warga.')

@section('content')
    <div class="container-fluid px-0">
        <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
            <div class="card-body p-4">
                <form action="{{ url('/laporan-sampah') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-fill rounded-3">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('laporan.sampah.pdf', request()->query()) }}" target="_blank"
                            class="btn btn-danger rounded-3 px-3">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <span class="text-muted small">Total Keseluruhan Berat Sampah:</span>
                    <h3 class="fw-bold text-dark my-1">{{ number_format($grandTotalBerat, 2, ',', '.') }} Kg</h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <span class="text-muted small">Total Perputaran Nilai Uang:</span>
                    <h3 class="fw-bold text-success my-1">Rp {{ number_format($grandTotalUang, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Jenis Sampah</th>
                                <th>Frekuensi Setoran</th>
                                <th>Total Berat (Kg)</th>
                                <th class="text-end">Total Nilai Saldo Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapSampah as $index => $item)
                                <tr>
                                    <td>{{ $rekapSampah->firstItem() ? $rekapSampah->firstItem() + $index : $index + 1 }}</td>
                                    <td class="fw-bold"><i
                                            class="bi bi-trash text-success me-2"></i>{{ $item->jenis_sampah ?? ($item->jenisSampah->nama_jenis ?? '-') }}</td>
                                    <td>{{ $item->total_transaksi }} kali transaksi</td>
                                    <td class="fw-semibold fs-6">{{ number_format($item->total_berat, 2, ',', '.') }} {{ $item->satuan ?? 'Kg' }}
                                    </td>
                                    <td class="text-end fw-bold text-success fs-6">
                                        Rp {{ number_format($item->total_uang, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada sampah yang tercatat
                                        pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top mt-2">
                    <small class="text-muted">
                        Menampilkan {{ $rekapSampah->firstItem() ?? 0 }} - {{ $rekapSampah->lastItem() ?? 0 }} dari {{ $rekapSampah->total() ?? 0 }} data
                    </small>
                    <div>
                        {{ $rekapSampah->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
