<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use App\Models\PenarikanSaldo;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil seluruh data setoran sampah (Pemasukan)
        $setorans = SetoranSampah::with(['nasabah', 'petugas'])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tanggal'    => $item->created_at,
                    'kode'       => $item->kode_setoran,
                    'nasabah'    => $item->nasabah->name ?? 'Nasabah',
                    'keterangan' => 'Setor Sampah: ' . $item->jenis_sampah . ' (' . $item->berat . ' kg)',
                    'tipe'       => 'masuk',
                    'nominal'    => $item->total_harga,
                    'status'     => 'selesai',
                    'petugas'    => $item->petugas->name ?? 'Petugas Pos',
                ];
            });

        // 2. Ambil seluruh data penarikan saldo (Pengeluaran Kas)
        $penarikans = PenarikanSaldo::with(['nasabah', 'petugas'])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tanggal'    => $item->created_at,
                    'kode'       => $item->kode_penarikan,
                    'nasabah'    => $item->nasabah->name ?? 'Nasabah',
                    'keterangan' => 'Tarik Saldo Tunai' . ($item->catatan_nasabah ? ' (' . $item->catatan_nasabah . ')' : ''),
                    'tipe'       => 'keluar',
                    'nominal'    => $item->nominal,
                    'status'     => $item->status,
                    'petugas'    => $item->petugas->name ?? '-',
                ];
            });

        // 3. Gabungkan dan urutkan dari transaksi yang paling baru
        $semuaTransaksi = $setorans->concat($penarikans)->sortByDesc('tanggal');

        return view('petugas.riwayat.index', compact('semuaTransaksi'));
    }
}