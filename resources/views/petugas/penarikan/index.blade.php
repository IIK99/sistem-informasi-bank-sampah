@extends('layouts.admin')

@section('title', 'Kelola Penarikan Saldo')
@section('page_title', 'Verifikasi Penarikan Saldo Warga')
@section('page_subtitle', 'Persetujuan pencairan uang kas tabungan nasabah di pos dengan unggah bukti transfer.')

@section('content')
    <div class="container-fluid px-0">
        {{-- Flash Alert Fallback --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Antrean Pengajuan Pencairan Dana</h5>
                    <p class="text-muted small mb-0">Klik tombol ACC untuk mengunggah bukti transfer/pembayaran dan memotong saldo nasabah secara otomatis.</p>
                </div>
            </div>
            <div class="card-body p-4">
                @if ($penarikans->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-cash-stack fs-1 d-block mb-3 text-secondary opacity-50"></i>
                        Belum ada pengajuan pencairan saldo dari nasabah.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Tanggal</th>
                                    <th>Nasabah</th>
                                    <th>Sisa Saldo</th>
                                    <th>Nominal Tarik</th>
                                    <th>Catatan Warga</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 100px;">Bukti Transfer</th>
                                    <th class="text-center" style="width: 140px;">Aksi Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penarikans as $item)
                                    <tr>
                                        <td class="fw-semibold text-secondary">{{ $item->kode_penarikan }}</td>
                                        <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                                        <td class="fw-bold">{{ $item->nasabah->name ?? 'Nasabah' }}</td>
                                        <td class="text-muted">
                                            Rp {{ number_format($item->nasabah->saldo ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="fw-bold text-danger">
                                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="small">{{ $item->catatan_nasabah ?: '-' }}</td>
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
                                                        style="width: 32px; height: 32px; padding: 0;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalPreviewBukti"
                                                        onclick="previewBukti('{{ asset('storage/' . $item->bukti_transfer) }}', '{{ $item->kode_penarikan }}')"
                                                        title="Lihat Bukti Transfer">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                @else
                                                    <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px; padding: 0;"
                                                        title="Lihat Dokumen PDF">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status === 'menunggu')
                                                <div class="d-flex justify-content-center align-items-center gap-1">
                                                    {{-- Tombol Buka Modal ACC --}}
                                                    <button type="button"
                                                        class="btn btn-sm btn-success rounded-3 shadow-sm btn-open-acc px-2 py-0 d-inline-flex align-items-center gap-1"
                                                        style="font-size: 0.75rem; height: 26px; line-height: 1;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalAccPenarikan"
                                                        onclick="bukaModalAcc(this)"
                                                        data-id="{{ $item->id }}"
                                                        data-kode="{{ $item->kode_penarikan }}"
                                                        data-nasabah="{{ $item->nasabah->name ?? 'Nasabah' }}"
                                                        data-nominal="{{ number_format($item->nominal, 0, ',', '.') }}"
                                                        data-saldo="{{ number_format($item->nasabah->saldo ?? 0, 0, ',', '.') }}"
                                                        data-catatan="{{ $item->catatan_nasabah ?: '-' }}"
                                                        data-action="{{ route('petugas.penarikan.setujui', $item->id) }}">
                                                        <i class="bi bi-check-lg"></i> ACC
                                                    </button>

                                                    {{-- Tombol Buka Modal Tolak --}}
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger rounded-3 shadow-sm btn-open-tolak px-2 py-0 d-inline-flex align-items-center"
                                                        style="font-size: 0.75rem; height: 26px; line-height: 1;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalTolakPenarikan"
                                                        onclick="bukaModalTolak(this)"
                                                        data-id="{{ $item->id }}"
                                                        data-kode="{{ $item->kode_penarikan }}"
                                                        data-nasabah="{{ $item->nasabah->name ?? 'Nasabah' }}"
                                                        data-action="{{ route('petugas.penarikan.tolak', $item->id) }}">
                                                        Tolak
                                                    </button>
                                                </div>
                                            @elseif ($item->status === 'disetujui')
                                                <div class="d-flex flex-column align-items-center gap-1">
                                                    <a href="{{ route('petugas.penarikan.cetak-pdf', $item->id) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-danger rounded-3 px-2 py-0 shadow-sm d-inline-flex align-items-center gap-1"
                                                        style="font-size: 0.75rem; height: 26px;">
                                                        <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
                                                    </a>
                                                    <small class="text-muted" style="font-size: 0.72rem;">
                                                        Oleh: {{ $item->petugas->name ?? 'Petugas' }}
                                                    </small>
                                                </div>
                                            @else
                                                <small class="text-muted" style="font-size: 0.75rem;">Ditolak</small>
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

    {{-- ============================================================== --}}
    {{-- MODAL ACC PENARIKAN (UPLOAD LAMPIRAN BUKTI TRANSFER)             --}}
    {{-- ============================================================== --}}
    <div class="modal fade" id="modalAccPenarikan" tabindex="-1" aria-labelledby="modalAccPenarikanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-success text-white py-3 px-4">
                    <h5 class="modal-title fw-bold" id="modalAccPenarikanLabel">
                        <i class="bi bi-patch-check-fill me-2"></i>Persetujuan Penarikan & Upload Bukti Transfer
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formAccPenarikan" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="modal-body p-4">
                        {{-- Ringkasan Pengajuan --}}
                        <div class="bg-light p-3 rounded-4 mb-4 border">
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <div class="small text-muted">Kode Pengajuan:</div>
                                    <div class="fw-bold fs-6 text-success" id="accKodePenarikan">-</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="small text-muted">Nama Nasabah:</div>
                                    <div class="fw-bold fs-6" id="accNamaNasabah">-</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="small text-muted">Sisa Saldo Nasabah:</div>
                                    <div class="fw-semibold text-secondary" id="accSaldoNasabah">Rp 0</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="small text-muted">Nominal Diminta:</div>
                                    <div class="fw-bold fs-5 text-danger" id="accNominal">Rp 0</div>
                                </div>
                                <div class="col-12 mt-2 pt-2 border-top">
                                    <div class="small text-muted">Catatan Nasabah:</div>
                                    <div class="fst-italic small text-dark" id="accCatatanNasabah">-</div>
                                </div>
                            </div>
                        </div>

                        {{-- Input Upload Bukti Transfer --}}
                        <div class="mb-3">
                            <label for="inputBuktiTransfer" class="form-label fw-bold">
                                Lampiran Bukti Transfer / Pembayaran <span class="text-muted fw-normal small">(Opsional)</span>
                            </label>
                            <input type="file" name="bukti_transfer" id="inputBuktiTransfer" 
                                class="form-control rounded-3" 
                                accept="image/jpeg,image/png,image/jpg,application/pdf">
                            <div class="form-text small">
                                Opsional. Format didukung: <strong>JPG, JPEG, PNG, PDF</strong> (Ukuran maksimal: <strong>2MB</strong>). Jika dilampirkan, bukti akan tercetak di kwitansi & laporan PDF.
                            </div>
                        </div>

                        {{-- Preview Gambar --}}
                        <div id="previewBuktiContainer" class="d-none mb-3 text-center p-3 border rounded-3 bg-white">
                            <div class="small text-muted mb-2 fw-semibold">Pratinjau Bukti Transfer:</div>
                            <img id="imgPreview" src="#" alt="Pratinjau Bukti" class="img-fluid rounded-3 border shadow-sm" style="max-height: 220px; object-fit: contain;">
                        </div>

                        {{-- Preview Dokumen PDF --}}
                        <div id="previewPdfContainer" class="d-none mb-3 p-3 border rounded-3 bg-white text-center text-primary">
                            <i class="bi bi-file-earmark-pdf-fill fs-2 d-block text-danger"></i>
                            <span class="small fw-semibold" id="pdfFileName">dokumen.pdf</span>
                        </div>

                        {{-- Input Catatan Petugas --}}
                        <div class="mb-2">
                            <label for="inputCatatanPetugas" class="form-label fw-semibold">Catatan Petugas (Opsional)</label>
                            <textarea name="catatan_petugas" id="inputCatatanPetugas" class="form-control rounded-3" rows="2" 
                                placeholder="Contoh: Disetujui. Transfer via Bank BCA / Uang tunai diserahkan di pos.">Disetujui oleh petugas.</textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 py-3 border-0">
                        <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-3 px-4 fw-semibold shadow-sm" id="btnSubmitAcc">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan & ACC Penarikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- MODAL TOLAK PENARIKAN                                          --}}
    {{-- ============================================================== --}}
    <div class="modal fade" id="modalTolakPenarikan" tabindex="-1" aria-labelledby="modalTolakPenarikanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-danger text-white py-3 px-4">
                    <h5 class="modal-title fw-bold" id="modalTolakPenarikanLabel">
                        <i class="bi bi-x-circle-fill me-2"></i>Tolak Pengajuan Penarikan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formTolakPenarikan" action="" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="modal-body p-4">
                        <p class="text-secondary mb-3">
                            Apakah Anda yakin ingin menolak penarikan saldo <strong id="tolakKode">-</strong> milik <strong id="tolakNasabah">-</strong>?
                        </p>

                        <div class="mb-3">
                            <label for="tolakCatatanPetugas" class="form-label fw-semibold">Alasan Penolakan</label>
                            <textarea name="catatan_petugas" id="tolakCatatanPetugas" class="form-control rounded-3" rows="3"
                                placeholder="Tuliskan alasan penolakan agar diketahui oleh nasabah..." required>Saldo tidak mencukupi atau informasi penarikan tidak valid.</textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 py-3 border-0">
                        <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger rounded-3 px-4 fw-semibold shadow-sm">
                            <i class="bi bi-x-octagon-fill me-1"></i> Ya, Tolak Penarikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- MODAL PREVIEW BUKTI TRANSFER                                   --}}
    {{-- ============================================================== --}}
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

    {{-- JAVASCRIPT HANDLER LANGSUNG DALAM CONTENT AGAR PASTI TER-RENDER --}}
    <script>
        function bukaModalAcc(btn) {
            try {
                const action = btn.getAttribute('data-action');
                const kode = btn.getAttribute('data-kode');
                const nasabah = btn.getAttribute('data-nasabah');
                const saldo = btn.getAttribute('data-saldo');
                const nominal = btn.getAttribute('data-nominal');
                const catatan = btn.getAttribute('data-catatan') || '-';

                const formAcc = document.getElementById('formAccPenarikan');
                if (formAcc) formAcc.action = action;

                const accKode = document.getElementById('accKodePenarikan');
                if (accKode) accKode.textContent = kode;

                const accNama = document.getElementById('accNamaNasabah');
                if (accNama) accNama.textContent = nasabah;

                const accSaldo = document.getElementById('accSaldoNasabah');
                if (accSaldo) accSaldo.textContent = 'Rp ' + saldo;

                const accNominal = document.getElementById('accNominal');
                if (accNominal) accNominal.textContent = 'Rp ' + nominal;

                const accCatatan = document.getElementById('accCatatanNasabah');
                if (accCatatan) accCatatan.textContent = catatan;

                // Reset preview
                const inputBukti = document.getElementById('inputBuktiTransfer');
                if (inputBukti) inputBukti.value = '';

                const previewContainer = document.getElementById('previewBuktiContainer');
                if (previewContainer) previewContainer.classList.add('d-none');

                const previewPdfContainer = document.getElementById('previewPdfContainer');
                if (previewPdfContainer) previewPdfContainer.classList.add('d-none');

                const imgPreview = document.getElementById('imgPreview');
                if (imgPreview) imgPreview.src = '#';

                const modalEl = document.getElementById('modalAccPenarikan');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    inst.show();
                }
            } catch (err) {
                console.error('bukaModalAcc error:', err);
            }
        }

        function bukaModalTolak(btn) {
            try {
                const action = btn.getAttribute('data-action');
                const kode = btn.getAttribute('data-kode');
                const nasabah = btn.getAttribute('data-nasabah');

                const formTolak = document.getElementById('formTolakPenarikan');
                if (formTolak) formTolak.action = action;

                const tolakKode = document.getElementById('tolakKode');
                if (tolakKode) tolakKode.textContent = kode;

                const tolakNasabah = document.getElementById('tolakNasabah');
                if (tolakNasabah) tolakNasabah.textContent = nasabah;

                const modalEl = document.getElementById('modalTolakPenarikan');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    inst.show();
                }
            } catch (err) {
                console.error('bukaModalTolak error:', err);
            }
        }

        function previewBukti(url, kode) {
            try {
                const modalTitle = document.getElementById('modalPreviewTitle');
                if (modalTitle) modalTitle.innerHTML = '<i class="bi bi-image me-2"></i>Bukti Transfer #' + kode;

                const previewImageSrc = document.getElementById('previewImageSrc');
                if (previewImageSrc) previewImageSrc.src = url;

                const downloadBuktiLink = document.getElementById('downloadBuktiLink');
                if (downloadBuktiLink) downloadBuktiLink.href = url;

                const modalEl = document.getElementById('modalPreviewBukti');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    inst.show();
                }
            } catch (err) {
                console.error('previewBukti error:', err);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Event delegation sebagai backup bila ada klik
            document.addEventListener('click', function (e) {
                const accBtn = e.target.closest('.btn-open-acc');
                if (accBtn) {
                    bukaModalAcc(accBtn);
                    return;
                }

                const tolakBtn = e.target.closest('.btn-open-tolak');
                if (tolakBtn) {
                    bukaModalTolak(tolakBtn);
                    return;
                }
            });

            // Input File Preview Handler
            const inputBukti = document.getElementById('inputBuktiTransfer');
            const previewContainer = document.getElementById('previewBuktiContainer');
            const previewPdfContainer = document.getElementById('previewPdfContainer');
            const imgPreview = document.getElementById('imgPreview');
            const pdfFileName = document.getElementById('pdfFileName');
            const formAcc = document.getElementById('formAccPenarikan');
            const btnSubmitAcc = document.getElementById('btnSubmitAcc');

            if (inputBukti) {
                inputBukti.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) {
                        if (previewContainer) previewContainer.classList.add('d-none');
                        if (previewPdfContainer) previewPdfContainer.classList.add('d-none');
                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Ukuran Terlalu Besar',
                                text: 'Ukuran file maksimal adalah 2MB. Silakan pilih berkas yang lebih kecil.',
                                confirmButtonColor: '#198754'
                            });
                        } else {
                            alert('Ukuran file maksimal adalah 2MB.');
                        }
                        this.value = '';
                        if (previewContainer) previewContainer.classList.add('d-none');
                        if (previewPdfContainer) previewPdfContainer.classList.add('d-none');
                        return;
                    }

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            if (imgPreview) imgPreview.src = e.target.result;
                            if (previewContainer) previewContainer.classList.remove('d-none');
                            if (previewPdfContainer) previewPdfContainer.classList.add('d-none');
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        if (pdfFileName) pdfFileName.textContent = file.name;
                        if (previewPdfContainer) previewPdfContainer.classList.remove('d-none');
                        if (previewContainer) previewContainer.classList.add('d-none');
                    } else {
                        if (previewContainer) previewContainer.classList.add('d-none');
                        if (previewPdfContainer) previewPdfContainer.classList.add('d-none');
                    }
                });
            }

            if (formAcc) {
                formAcc.addEventListener('submit', function () {
                    if (btnSubmitAcc) {
                        btnSubmitAcc.disabled = true;
                        btnSubmitAcc.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...';
                    }
                });
            }
        });
    </script>
@endsection
