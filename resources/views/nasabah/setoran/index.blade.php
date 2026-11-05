@extends('layouts.nasabah')

@section('title', 'Riwayat Setoran Sampah')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <h4 class="fw-bold mb-1">Riwayat Setoran Sampah</h4>
            <p class="text-muted small mb-0">Catatan penimbangan sampah yang diserahkan ke pos operasional.</p>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                @if ($setorans->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-box-seam fs-1 d-block mb-3 text-secondary"></i>
                        Belum ada riwayat setoran sampah yang tercatat.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Setor</th>
                                    <th>Tanggal</th>
                                    <th>Rincian Sampah (Multi-Item)</th>
                                    <th>Nilai Uang (Saldo Masuk)</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($setorans as $item)
                                    <tr>
                                        <td class="fw-bold text-secondary">{{ $item->kode_setoran }}</td>
                                        <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            @if ($item->details && $item->details->isNotEmpty())
                                                <ul class="list-unstyled mb-0 small">
                                                    @foreach ($item->details as $d)
                                                        <li>
                                                            • <strong>{{ $d->jenisSampah->nama_jenis ?? '-' }}</strong>:
                                                            {{ $d->jumlah }} {{ $d->satuan->simbol ?? 'kg' }}
                                                            <span class="text-muted">(Rp
                                                                {{ number_format($d->subtotal, 0, ',', '.') }})</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                {{-- Fallback data transaksi lama --}}
                                                <span class="badge bg-light text-dark border">
                                                    {{ $item->jenisSampah->nama_jenis ?? 'Sampah' }}
                                                    ({{ $item->jumlah ?? $item->berat }} kg)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-success fs-6">
                                            + Rp {{ number_format($item->total_harga ?? $item->total_nilai, 0, ',', '.') }}
                                        </td>
                                        <td class="text-muted small">{{ $item->petugas->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            Belum ada riwayat setoran sampah yang tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($setorans->hasPages())
                        <div class="d-flex justify-content-center pt-3 pb-2">
                            {{ $setorans->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
