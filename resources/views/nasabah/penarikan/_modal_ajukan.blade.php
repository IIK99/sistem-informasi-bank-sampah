@php
    $userSaldo = $saldo ?? (Auth::user()->saldo ?? 0);
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

<!-- MODAL AJUKAN PENARIKAN SALDO -->
<div class="modal fade" id="modalAjukanPenarikan" tabindex="-1" aria-labelledby="modalAjukanPenarikanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0 pt-3 pt-sm-4 px-3 px-sm-4 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle flex-shrink-0" style="width: 44px; height: 44px;">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0 fs-6 fs-sm-5" id="modalAjukanPenarikanLabel">Ajukan Penarikan Saldo</h5>
                        <p class="text-muted small mb-0" style="font-size: 0.75rem;">Ambil di pos bank sampah setelah disetujui</p>
                    </div>
                </div>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 m-sm-4" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('nasabah.penarikan.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3 p-sm-4">
                    <!-- Info Saldo Card -->
                    <div class="card border-0 rounded-4 mb-4 p-3" style="background: linear-gradient(135deg, #198754, #157347); color: white;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-white-50 small fw-medium">Saldo Aktif Tersedia</span>
                            @if ($userSaldo >= 10000)
                                <span class="badge bg-white text-success rounded-3 px-2 py-1 small fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Siap Ditarik
                                </span>
                            @else
                                <span class="badge bg-warning text-dark rounded-3 px-2 py-1 small fw-semibold">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Saldo Kurang
                                </span>
                            @endif
                        </div>
                        <h3 class="fw-bold mb-0">
                            Rp {{ number_format($userSaldo, 0, ',', '.') }}
                        </h3>
                    </div>

                    <!-- Input Nominal -->
                    <div class="mb-3">
                        <label for="nominalPenarikan" class="form-label fw-semibold small text-secondary">
                            Nominal Penarikan <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0 fw-bold text-muted fs-6">Rp</span>
                            <input type="number" 
                                name="nominal" 
                                id="nominalPenarikan"
                                class="form-control border-start-0 fs-5 fw-bold text-dark @error('nominal') is-invalid @enderror"
                                placeholder="Contoh: 50000" 
                                value="{{ old('nominal') }}" 
                                min="10000"
                                max="{{ (int) $userSaldo }}" 
                                required
                                {{ $userSaldo < 10000 ? 'disabled' : '' }}>
                            @error('nominal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="text-muted" style="font-size: 0.75rem;">Min. Rp 10.000</span>
                            <span class="text-muted" style="font-size: 0.75rem;">Maks. Rp {{ number_format($userSaldo, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Nominal Pilihan Cepat (Quick Chips) -->
                    @if ($userSaldo >= 10000)
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary" style="font-size: 0.75rem;">Pilihan Cepat:</label>
                            <div class="d-flex flex-wrap gap-2">
                                @if ($userSaldo >= 20000)
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1 chip-nominal" onclick="setNominal(20000)">
                                        20 Ribu
                                    </button>
                                @endif
                                @if ($userSaldo >= 50000)
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1 chip-nominal" onclick="setNominal(50000)">
                                        50 Ribu
                                    </button>
                                @endif
                                @if ($userSaldo >= 100000)
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1 chip-nominal" onclick="setNominal(100000)">
                                        100 Ribu
                                    </button>
                                @endif
                                <button type="button" class="btn btn-outline-success btn-sm rounded-3 px-3 py-1 chip-nominal fw-semibold" onclick="setNominal({{ (int) $userSaldo }})">
                                    Tarik Semua
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Input Catatan -->
                    <div class="mb-3">
                        <label for="catatanNasabah" class="form-label fw-semibold small text-secondary">
                            Catatan / Keterangan <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <textarea name="catatan_nasabah" 
                            id="catatanNasabah" 
                            rows="2"
                            class="form-control rounded-3 @error('catatan_nasabah') is-invalid @enderror" 
                            placeholder="Contoh: Ambil hari Sabtu sore / untuk uang saku">{{ old('catatan_nasabah') }}</textarea>
                        @error('catatan_nasabah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Info Alert -->
                    <div class="alert alert-light border rounded-3 p-2 mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-success fs-5 flex-shrink-0"></i>
                        <span class="text-muted" style="font-size: 0.75rem; line-height: 1.35;">
                            Setelah dikirim, petugas bank sampah akan memvalidasi pengajuan sebelum dana tunai diserahkan.
                        </span>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 pt-0 px-3 px-sm-4 pb-3 pb-sm-4 gap-2 flex-nowrap">
                    <button type="button" class="btn btn-light rounded-3 px-3 py-2 text-secondary fw-semibold w-50" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" 
                        class="btn btn-success rounded-3 px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center justify-content-center gap-2 w-50"
                        {{ $userSaldo < 10000 ? 'disabled' : '' }}>
                        <i class="bi bi-send-fill"></i> Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setNominal(amount) {
        const input = document.getElementById('nominalPenarikan');
        if (input) {
            input.value = amount;
            input.focus();
        }
    }

    @if ($errors->has('nominal') || $errors->has('catatan_nasabah'))
        document.addEventListener("DOMContentLoaded", function() {
            var myModalEl = document.getElementById('modalAjukanPenarikan');
            if (myModalEl) {
                var myModal = new bootstrap.Modal(myModalEl);
                myModal.show();
            }
        });
    @endif
</script>
