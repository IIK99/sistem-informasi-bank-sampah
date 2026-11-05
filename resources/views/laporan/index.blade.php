@extends(Auth::user()->role === 'nasabah' ? 'layouts.nasabah' : 'layouts.admin')

@section('title', 'Laporan')
@section('page_title', $title ?? 'Laporan Sistem')
@section('page_subtitle', 'Ringkasan data laporan')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">{{ $title ?? 'Laporan' }}</h5>
            <a href="?export=pdf" class="btn btn-outline-danger rounded-20px px-3">
                <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Laporan PDF
            </a>
        </div>

        @if(isset($data) && $data->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        @if(isset($type) && $type === 'setoran')
                            <tr>
                                <th>Tanggal</th>
                                <th>Nasabah</th>
                                <th>Total Berat</th>
                                <th>Total Harga</th>
                            </tr>
                        @elseif(isset($type) && $type === 'penarikan')
                            <tr>
                                <th>Tanggal</th>
                                <th>Nasabah</th>
                                <th>Jumlah Penarikan</th>
                                <th>Status</th>
                            </tr>
                        @elseif(isset($type) && $type === 'saldo')
                            <tr>
                                <th>ID Nasabah</th>
                                <th>Nama Nasabah</th>
                                <th>Total Saldo Aktif</th>
                            </tr>
                        @elseif(isset($type) && $type === 'sampah')
                            <tr>
                                <th>Tanggal</th>
                                <th>Total Berat Sampah (Kg)</th>
                                <th>Estimasi Nilai (Rp)</th>
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                            @if(isset($type) && $type === 'setoran')
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $item->nasabah->name ?? '-' }}</td>
                                    <td>{{ $item->total_berat }} Kg</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @elseif(isset($type) && $type === 'penarikan')
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $item->nasabah->name ?? '-' }}</td>
                                    <td class="fw-bold text-danger">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->status == 'pending' || $item->status == 'menunggu')
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-3">Menunggu</span>
                                        @elseif($item->status == 'disetujui')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-3">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                            @elseif(isset($type) && $type === 'saldo')
                                <tr>
                                    <td>#{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                                </tr>
                            @elseif(isset($type) && $type === 'sampah')
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $item->berat }} Kg</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5 text-muted bg-light rounded-3">
                <i class="bi bi-file-earmark-bar-graph fs-1 mb-3 text-secondary"></i>
                <h5>Data belum tersedia</h5>
                <p>Silakan isi data transaksi terlebih dahulu untuk melihat tabel laporan.</p>
            </div>
        @endif
    </div>
</div>
@endsection
