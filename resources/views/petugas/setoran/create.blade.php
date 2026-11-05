@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-3 py-2">
        <!-- Header Kasir -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-wallet2 me-2 text-success"></i>Kasir Setoran Sampah Warga
                </h4>
                <span class="text-muted small">Timbang sampah setoran nasabah dan akumulasi saldo tabungan
                    otomatis.</span>
            </div>
            <a href="{{ route('petugas.setoran.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3">
                <i class="bi bi-arrow-left me-1"></i> Riwayat Setoran
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger rounded-3 py-2 small">{{ session('error') }}</div>
        @endif

        <form action="{{ route('petugas.setoran.store') }}" method="POST" id="formSetoran">
            @csrf
            <div class="row g-3">
                <!--  KATALOG MASTER SAMPAH  -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-3">
                            <!-- Baris Nasabah & Pencarian -->
                            <div class="row g-3 align-items-end mb-3">
                                <!-- Pilih Akun Nasabah -->
                                <div class="col-md-7">
                                    <label class="form-label small fw-bold text-secondary mb-1">Pilih Akun Nasabah
                                        (Warga)</label>
                                    <div class="dropdown" id="dropdownNasabah">
                                        <input type="hidden" name="user_id" id="selectedNasabahId"
                                            value="{{ old('user_id') }}" required>

                                        <button
                                            class="btn btn-white w-100 text-start d-flex justify-content-between align-items-center bg-white border rounded-3 px-3 shadow-sm dropdown-toggle"
                                            type="button" id="btnPilihNasabah" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside" aria-expanded="false" style="height: 44px;">
                                            <span id="labelNasabah" class="text-truncate text-secondary small">Pilih
                                                Nasabah</span>
                                        </button>

                                        <div class="dropdown-menu w-100 p-2 shadow-lg border-0 rounded-3 mt-1"
                                            aria-labelledby="btnPilihNasabah" style="min-width: 100%; z-index: 1050;">
                                            <div class="input-group input-group-sm mb-2">
                                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                                        class="bi bi-search"></i></span>
                                                <input type="text" id="inputCariNasabah"
                                                    class="form-control border-start-0"
                                                    placeholder="Ketik nama atau saldo warga..." autocomplete="off">
                                            </div>

                                            <div id="listNasabahContainer" style="max-height: 250px; overflow-y: auto;">
                                                @foreach ($nasabahs as $n)
                                                    <a href="javascript:void(0)"
                                                        class="dropdown-item item-nasabah py-2 px-2 rounded-2 text-wrap d-flex justify-content-between align-items-center"
                                                        data-id="{{ $n->id }}" data-nama="{{ $n->name }}"
                                                        data-saldo="{{ number_format($n->saldo ?? 0, 0, ',', '.') }}">
                                                        <div>
                                                            <div class="fw-semibold text-dark small">{{ $n->name }}
                                                            </div>
                                                            <small class="text-muted" style="font-size: 11px;">Saldo: Rp
                                                                {{ number_format($n->saldo ?? 0, 0, ',', '.') }}</small>
                                                        </div>
                                                        <i class="bi bi-check2 text-success fs-5 d-none cek-icon"></i>
                                                    </a>
                                                @endforeach

                                                <div id="nasabahKosong" class="text-center py-3 text-muted small"
                                                    style="display: none;">
                                                    <i class="bi bi-emoji-frown me-1"></i> Nasabah tidak ditemukan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label small fw-bold text-secondary mb-1">Cari Jenis Sampah</label>
                                    <div class="input-group shadow-sm rounded-3">
                                        <span class="input-group-text bg-white border-end-0 text-muted ps-3 rounded-start-3"
                                            style="height: 44px;">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="text" id="inputSearch"
                                            class="form-control border-start-0 ps-1 rounded-end-3 small"
                                            placeholder="Ketik nama sampah..." style="height: 44px;" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <!-- Grid Kartu Sampah -->
                            <div class="border rounded-3 p-3 bg-light pos-catalog-container"
                                style="min-height: 480px; max-height: 520px; overflow-y: auto;">
                                <div class="row g-2" id="gridKatalog">
                                    @if (isset($jenisSampahs) && $jenisSampahs->count() > 0)
                                        @foreach ($jenisSampahs as $sampah)
                                            @php
                                                $hargaBeliNasabah =
                                                    $sampah->harga_beli ?? ($sampah->harga_per_satuan ?? 3000);
                                                $simbolSatuan = $sampah->satuan->simbol ?? 'kg';
                                                $namaKategori = $sampah->kategori->nama_kategori ?? 'Umum';
                                            @endphp
                                            <div class="col-sm-6 col-md-4 card-sampah-item"
                                                data-nama="{{ strtolower($sampah->nama_jenis) }}">
                                                <div class="card h-100 border rounded-3 p-2 shadow-none hover-card"
                                                    onclick="tambahKeKeranjang({{ $sampah->id }}, '{{ addslashes($sampah->nama_jenis) }}', {{ $hargaBeliNasabah }}, '{{ $simbolSatuan }}')"
                                                    style="cursor: pointer; transition: all 0.2s;">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <span class="badge bg-secondary-subtle text-secondary small"
                                                            style="font-size: 10px;">{{ $namaKategori }}</span>
                                                        <span class="badge bg-light text-muted border"
                                                            style="font-size: 10px;">/{{ $simbolSatuan }}</span>
                                                    </div>
                                                    <span
                                                        class="fw-bold small text-dark d-block text-truncate mb-1">{{ $sampah->nama_jenis }}</span>
                                                    <span class="fw-bold text-success small">Rp
                                                        {{ number_format($hargaBeliNasabah, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-5 text-muted col-12">
                                            Data jenis sampah belum tersedia.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--  KOLOM KANAN: KERANJANG TIMBANGAN  -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark"><i class="bi bi-basket3 me-1"></i> Keranjang Timbangan</span>
                            <span class="badge bg-success-subtle text-success border" id="badgeItemCount">0 Jenis
                                Sampah</span>
                        </div>

                        <div class="card-body p-3 pt-0">
                            <!-- Daftar Keranjang -->
                            <div class="table-responsive mb-3" style="max-height: 250px; overflow-y: auto;">
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
                                            <td colspan="4" class="text-center py-4 text-muted small">Belum ada sampah
                                                ditimbang. Klik kartu sampah di sisi kiri.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Catatan -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary mb-1">Catatan Tambahan</label>
                                <input type="text" name="catatan" class="form-control form-control-sm"
                                    placeholder="Contoh: Kondisi kering, kardus sudah dipress">
                            </div>

                            <!-- Ringkasan Finansial Saldo Masuk -->
                            <div class="bg-body-secondary p-3 rounded-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted small">Total Penambahan Saldo:</span>
                                    <span class="fw-bold fs-4 text-success" id="labelTotalNilai">Rp 0</span>
                                </div>
                                <span class="text-muted d-block" style="font-size: 11px;">*Saldo akun nasabah langsung
                                    bertambah setelah nota disimpan.</span>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold rounded-3 shadow-sm"
                                id="btnSubmitSetoran" disabled>
                                <i class="bi bi-save me-1"></i> Simpan Setoran & Tambah Saldo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>



    <style>
        .hover-card:hover {
            border-color: #198754 !important;
            background-color: #f6fff9 !important;
            transform: translateY(-2px);
        }
    </style>
@endsection
