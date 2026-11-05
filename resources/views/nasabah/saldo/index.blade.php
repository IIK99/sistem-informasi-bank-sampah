@extends('layouts.nasabah')

@section('title', 'Saldo Tabungan')

@section('content')
    <div class="mx-auto" style="max-width: 820px;">
        {{-- KARTU SALDO UTAMA --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4"
            style="background: linear-gradient(135deg, #198754, #146c43); color: white;">
            <div class="card-body p-4 p-md-5">
                <span class="badge bg-white text-success px-3 py-2 rounded-3 fw-semibold mb-3 shadow-sm">
                    <i class="bi bi-wallet2 me-1"></i> Buku Kas Digital
                </span>
                <h6 class="text-white-50 text-uppercase fw-semibold mb-1" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                    Total Saldo Tersedia
                </h6>
                <h1 class="display-5 fw-bold mb-4">
                    Rp {{ number_format($user->saldo ?? 0, 0, ',', '.') }}
                </h1>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button"
                        class="btn btn-light text-success fw-bold px-4 py-2 rounded-3 shadow-sm d-inline-flex align-items-center gap-2"
                        data-bs-toggle="modal" data-bs-target="#modalAjukanPenarikan">
                        <i class="bi bi-cash-stack"></i> Tarik Saldo Tunai
                    </button>
                    <a href="{{ route('nasabah.penarikan.index') }}" class="btn btn-outline-light px-4 py-2 rounded-3">
                        <i class="bi bi-clock-history me-1"></i> Riwayat Penarikan
                    </a>
                </div>
            </div>
        </div>

        {{-- REKAP PENARIKAN TERAKHIR --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Pengajuan Penarikan Terakhir</h6>
                <a href="{{ route('nasabah.penarikan.index') }}"
                    class="text-decoration-none small text-success fw-semibold">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body px-4 pb-4">
                @if ($riwayatPenarikan->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        Belum ada riwayat penarikan saldo.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="text-secondary small">
                                    <th>Kode</th>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayatPenarikan as $item)
                                    <tr>
                                        <td class="fw-semibold text-secondary">{{ $item->kode_penarikan }}</td>
                                        <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                                        <td class="fw-bold">Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if ($item->status === 'menunggu')
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-3">Menunggu</span>
                                            @elseif($item->status === 'disetujui')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-3">Ditolak</span>
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
    </div>

    {{-- MODAL AJUKAN PENARIKAN --}}
    @include('nasabah.penarikan._modal_ajukan', ['saldo' => $user->saldo ?? 0])
@endsection
