@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-3 py-2">
        <!-- Header Kasir -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-shop me-2 text-primary"></i>Kasir Jual Sampah ke Pengepul
                </h4>
                <span class="text-muted small">Transaksi multi-item sampah keluar dan pemotongan biaya operasional.</span>
            </div>
            <a href="{{ route('jual-pengepul.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3">
                <i class="bi bi-arrow-left me-1"></i> Riwayat Nota
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger rounded-3 py-2 small">{{ session('error') }}</div>
        @endif

        <form action="{{ route('jual-pengepul.store') }}" method="POST" id="formKasir">
            @csrf
            <div class="row g-3">
                <!-- ================= KOLOM KIRI: KATALOG SAMPAH MITRA ================= -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-3">
                            <!-- Baris Pemilihan Pengepul & Pencarian -->
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary mb-1">1. Pilih Mitra
                                        Pengepul</label>
                                    <select name="pengepul_id" id="pengepul_id" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Pengepul / Lapak --</option>
                                        @foreach ($pengepuls as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('pengepul_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_pengepul }} ({{ $p->kode ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary mb-1">Cari Jenis Sampah</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white"><i
                                                class="bi bi-search text-muted"></i></span>
                                        <input type="text" id="inputSearch" class="form-control"
                                            placeholder="Ketik nama sampah..." disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- Area Katalog Kartu Sampah -->
                            <div class="border rounded-3 p-3 bg-light pos-catalog-container"
                                style="min-height: 480px; max-height: 520px; overflow-y: auto;">
                                <div id="loadingKatalog" class="text-center py-5 d-none">
                                    <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                                    <span class="ms-2 small text-muted">Memuat daftar sampah mitra...</span>
                                </div>

                                <div id="emptyKatalog" class="text-center py-5 text-muted">
                                    <i class="bi bi-arrow-up-circle fs-2 d-block mb-2 text-secondary"></i>
                                    <span id="emptyKatalogText">Pilih mitra pengepul di atas untuk memuat katalog sampah
                                        yang diterima.</span>
                                </div>

                                <div class="row g-2" id="gridKatalog"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= KOLOM KANAN: STRUK KERANJANG & OPERASIONAL ================= -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark"><i class="bi bi-cart3 me-1"></i> Keranjang Nota</span>
                            <span class="badge bg-primary-subtle text-primary border" id="badgeItemCount">0 Jenis
                                Sampah</span>
                        </div>

                        <div class="card-body p-3 pt-0">
                            <!-- Daftar Item Keranjang -->
                            <div class="table-responsive mb-3" style="max-height: 220px; overflow-y: auto;">
                                <table class="table table-sm align-middle mb-0" id="tabelKeranjang">
                                    <thead class="table-light small">
                                        <tr>
                                            <th>Sampah</th>
                                            <th style="width: 100px;">Berat</th>
                                            <th class="text-end">Subtotal</th>
                                            <th style="width: 30px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyKeranjang">
                                        <tr id="rowKeranjangKosong">
                                            <td colspan="4" class="text-center py-4 text-muted small">Keranjang masih
                                                kosong. Klik kartu sampah di sisi kiri.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Bagian Biaya Operasional -->
                            <div class="border rounded-3 p-2 bg-light mb-3">
                                <span class="fw-bold small text-dark d-block mb-1"><i class="bi bi-truck me-1"></i> Biaya
                                    Operasional Lapangan</span>
                                <div class="row g-2">
                                    <div class="col-7">
                                        <input type="text" name="keterangan_operasional"
                                            class="form-control form-control-sm"
                                            placeholder="Ket: Bensin / Kuli / Timbangan">
                                    </div>
                                    <div class="col-5">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="biaya_operasional" id="inputOperasional"
                                                class="form-control text-end" placeholder="0" min="0" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Catatan Ekstra -->
                            <div class="mb-3">
                                <input type="text" name="catatan" class="form-control form-control-sm"
                                    placeholder="Catatan transaksi (opsional)">
                            </div>

                            <!-- Rincian Finansial -->
                            <div class="bg-body-secondary p-3 rounded-3 mb-3 small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Total Penjualan Sampah:</span>
                                    <span class="fw-semibold text-dark" id="labelSubtotal">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1 text-danger">
                                    <span>Potongan Biaya Operasional:</span>
                                    <span class="fw-semibold" id="labelOperasional">- Rp 0</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark fs-6">Kas Masuk Bersih:</span>
                                    <span class="fw-bold text-success fs-5" id="labelKasBersih">Rp 0</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold rounded-3 shadow-sm"
                                id="btnSubmitKasir" disabled>
                                <i class="bi bi-check2-circle me-1"></i> Simpan Transaksi Kasir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <style>
        .hover-card:hover {
            border-color: #0d6efd !important;
            background-color: #f8faff !important;
            transform: translateY(-2px);
        }
    </style>
@endsection
