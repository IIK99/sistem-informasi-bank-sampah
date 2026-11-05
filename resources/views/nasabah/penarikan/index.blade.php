@extends('layouts.nasabah')

@section('title', 'Riwayat Penarikan')

@section('content')
    <div class="container py-4">
        {{-- NOTIFIKASI SUKSES --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1">Riwayat Penarikan Saldo</h4>
                <p class="text-muted small mb-0">Pantau status pencairan saldo tabungan Anda dan unduh bukti transfer resmi.</p>
            </div>
            @canAction('Penarikan Saldo', 'create')
            <button type="button"
                class="btn btn-success px-4 py-2 rounded-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalAjukanPenarikan">
                <i class="bi bi-plus-circle-fill"></i> Ajukan Penarikan
            </button>
            @endcanAction
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                @if ($penarikans->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-receipt fs-1 d-block mb-3 text-secondary opacity-50"></i>
                        <p class="mb-3">Belum ada catatan pengajuan penarikan dana.</p>
                        @canAction('Penarikan Saldo', 'create')
                        <button type="button" class="btn btn-outline-success rounded-3 px-4 py-2 fw-semibold"
                            data-bs-toggle="modal" data-bs-target="#modalAjukanPenarikan">
                            <i class="bi bi-plus-circle me-1"></i> Ajukan Penarikan Pertama
                        </button>
                        @endcanAction
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Penarikan</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nominal</th>
                                    <th>Catatan Anda</th>
                                    <th>Status</th>
                                    <th class="text-center">Bukti Transfer</th>
                                    <th>Catatan Petugas</th>
                                    <th class="text-center">Kwitansi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penarikans as $item)
                                    <tr>
                                        <td class="fw-semibold text-secondary">{{ $item->kode_penarikan }}</td>
                                        <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                                        <td class="fw-bold text-dark">Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $item->catatan_nasabah ?: '-' }}</td>
                                        <td>
                                            @if ($item->status === 'menunggu')
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-3">Menunggu</span>
                                            @elseif($item->status === 'disetujui')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-3">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->bukti_transfer)
                                                @php
                                                    $isImg = in_array(strtolower(pathinfo($item->bukti_transfer, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                                @endphp
                                                @if ($isImg)
                                                    <button type="button" 
                                                        class="btn btn-sm btn-outline-success rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center"
                                                        style="width: 30px; height: 30px; padding: 0;"
                                                        onclick="previewBuktiNasabah('{{ asset('storage/' . $item->bukti_transfer) }}', '{{ $item->kode_penarikan }}')"
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
                                        <td class="text-muted small">{{ $item->catatan_petugas ?: '-' }}</td>
                                        <td class="text-center">
                                            @if ($item->status === 'disetujui')
                                                <a href="{{ route('petugas.penarikan.cetak-pdf', $item->id) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-danger rounded-3 px-3 shadow-sm d-inline-flex align-items-center gap-1">
                                                    <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center pt-2">
                        {{ $penarikans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL PREVIEW BUKTI TRANSFER UNTUK NASABAH --}}
    <div class="modal fade" id="modalPreviewBuktiNasabah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-dark text-white py-3 px-4">
                    <h6 class="modal-title fw-bold" id="modalPreviewNasabahTitle">
                        <i class="bi bi-image me-2"></i>Bukti Transfer
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 text-center bg-light">
                    <img id="previewNasabahImageSrc" src="#" alt="Bukti Transfer" class="img-fluid rounded-3 shadow-sm" style="max-height: 500px; object-fit: contain;">
                </div>
                <div class="modal-footer bg-light px-4 py-2 border-0 justify-content-between">
                    <a id="downloadNasabahBuktiLink" href="#" target="_blank" class="btn btn-sm btn-outline-primary rounded-3 px-3">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Ukuran Penuh
                    </a>
                    <button type="button" class="btn btn-sm btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL AJUKAN PENARIKAN --}}
    @include('nasabah.penarikan._modal_ajukan')
@endsection

@push('scripts')
<script>
    function previewBuktiNasabah(url, kode) {
        document.getElementById('modalPreviewNasabahTitle').innerHTML = '<i class="bi bi-image me-2"></i>Bukti Transfer #' + kode;
        document.getElementById('previewNasabahImageSrc').src = url;
        document.getElementById('downloadNasabahBuktiLink').href = url;
        new bootstrap.Modal(document.getElementById('modalPreviewBuktiNasabah')).show();
    }
</script>
@endpush
