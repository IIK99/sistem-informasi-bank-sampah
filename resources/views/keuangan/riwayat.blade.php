@extends(Auth::user()->role === 'nasabah' ? 'layouts.nasabah' : 'layouts.admin')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Semua Riwayat Transaksi')
@section('page_subtitle', 'Catatan gabungan setoran dan penarikan saldo Anda')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        @if($riwayat->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-clock-history fs-1 mb-3 text-secondary"></i>
                <h5>Belum ada transaksi</h5>
                <p>Anda belum memiliki riwayat setoran maupun penarikan.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Transaksi</th>
                            <th>Nominal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td>
                                    @if($item->jenis == 'Setoran')
                                        <span class="badge bg-success">Setoran</span>
                                    @else
                                        <span class="badge bg-danger">Penarikan</span>
                                    @endif
                                </td>
                                <td class="fw-bold">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->jenis == 'Setoran')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3">Selesai</span>
                                    @else
                                        @if($item->status == 'pending' || $item->status == 'menunggu')
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-3">Menunggu</span>
                                        @elseif($item->status == 'disetujui')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-3">Ditolak</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
