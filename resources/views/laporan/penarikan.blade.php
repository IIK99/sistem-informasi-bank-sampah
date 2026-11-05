@extends('layouts.admin')

@section('title', 'Laporan Penarikan Saldo')
@section('page_title', 'Laporan Rekap Penarikan Saldo')
@section('page_subtitle', 'Rekapitulasi pencairan dana tabungan warga berdasarkan periode dan status verifikasi.')

@section('content')
    <div class="container-fluid px-0">
        <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
            <div class="card-body p-4">
                <form action="{{ url('/laporan-penarikan') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Status Pengajuan</label>
                        <select name="status" class="form-select">
                            <option value="">-- Semua Status --</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu
                            </option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-fill rounded-3">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('laporan.penarikan.pdf', request()->query()) }}" target="_blank"
                            class="btn btn-danger rounded-3 px-3 shadow-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
            <span class="text-muted small">Total Kas Keluar (Status Disetujui):</span>
            <h3 class="fw-bold text-danger my-1">Rp {{ number_format($totalDitarik, 0, ',', '.') }}</h3>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Penarikan</th>
                                <th>Tanggal</th>
                                <th>Nama Nasabah</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Petugas Verifikasi</th>
                                <th class="text-center">Bukti Transfer</th>
                                <th class="text-center">Kwitansi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penarikans as $index => $item)
                                <tr>
                                    <td>{{ $penarikans->firstItem() ? $penarikans->firstItem() + $index : $index + 1 }}</td>
                                    <td class="fw-semibold text-secondary">{{ $item->kode_penarikan }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="fw-bold">{{ $item->nasabah->name ?? 'Nasabah' }}</td>
                                    <td class="fw-bold text-danger">Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($item->status === 'disetujui')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3">Disetujui</span>
                                        @elseif($item->status === 'menunggu')
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-3">Menunggu</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-3">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $item->petugas->name ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($item->bukti_transfer)
                                            @php
                                                $isImg = in_array(strtolower(pathinfo($item->bukti_transfer, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                            @endphp
                                            @if ($isImg)
                                                <button type="button" 
                                                    class="btn btn-sm btn-outline-success rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center"
                                                    style="width: 30px; height: 30px; padding: 0;"
                                                    onclick="previewBukti('{{ asset('storage/' . $item->bukti_transfer) }}', '{{ $item->kode_penarikan }}')"
                                                    title="Lihat Bukti Transfer">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            @else
                                                <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center"
                                                    style="width: 30px; height: 30px; padding: 0;"
                                                    title="Lihat Dokumen PDF">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->status === 'disetujui')
                                            <a href="{{ route('petugas.penarikan.cetak-pdf', $item->id) }}" target="_blank"
                                                class="btn btn-sm btn-outline-danger rounded-3 px-3 shadow-sm d-inline-flex align-items-center gap-1"
                                                title="Cetak Kwitansi Penarikan PDF">
                                                <i class="bi bi-file-earmark-pdf-fill"></i> Cetak
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">Tidak ada riwayat penarikan pada
                                        periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top mt-2">
                    <small class="text-muted">
                        Menampilkan {{ $penarikans->firstItem() ?? 0 }} - {{ $penarikans->lastItem() ?? 0 }} dari {{ $penarikans->total() ?? 0 }} data
                    </small>
                    <div>
                        {{ $penarikans->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PREVIEW BUKTI TRANSFER --}}
    <div class="modal fade" id="modalPreviewBukti" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-dark text-white py-3 px-4">
                    <h6 class="modal-title fw-bold" id="modalPreviewTitle">
                        <i class="bi bi-image me-2"></i>Bukti Transfer
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 text-center bg-light">
                    <img id="previewImageSrc" src="#" alt="Bukti Transfer" class="img-fluid rounded-3 shadow-sm" style="max-height: 500px; object-fit: contain;">
                </div>
                <div class="modal-footer bg-light px-4 py-2 border-0 justify-content-between">
                    <a id="downloadBuktiLink" href="#" target="_blank" class="btn btn-sm btn-outline-primary rounded-3 px-3">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Ukuran Penuh
                    </a>
                    <button type="button" class="btn btn-sm btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function previewBukti(url, kode) {
        document.getElementById('modalPreviewTitle').innerHTML = '<i class="bi bi-image me-2"></i>Bukti Transfer #' + kode;
        document.getElementById('previewImageSrc').src = url;
        document.getElementById('downloadBuktiLink').href = url;
        new bootstrap.Modal(document.getElementById('modalPreviewBukti')).show();
    }
</script>
@endpush
