<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use App\Models\PenarikanSaldo;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil data setoran (Pemasukan Saldo)
        $setorans = SetoranSampah::where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tanggal'    => $item->created_at,
                    'kode'       => $item->kode_setoran,
                    'keterangan' => 'Setor Sampah: ' . $item->jenis_sampah . ' (' . $item->berat . ' kg)',
                    'tipe'       => 'masuk',
                    'nominal'    => $item->total_harga,
                    'status'     => 'selesai',
                ];
            });

        // 2. Ambil data penarikan (Pengeluaran Saldo)
        $penarikans = PenarikanSaldo::where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tanggal'    => $item->created_at,
                    'kode'       => $item->kode_penarikan,
                    'keterangan' => 'Tarik Saldo Tunai' . ($item->catatan_nasabah ? ' (' . $item->catatan_nasabah . ')' : ''),
                    'tipe'       => 'keluar',
                    'nominal'    => $item->nominal,
                    'status'     => $item->status,
                ];
            });

        // 3. Gabungkan dan urutkan dari transaksi terbaru
        $mutasi = $setorans->concat($penarikans)->sortByDesc('tanggal');

        return view('nasabah.transaksi.index', compact('mutasi'));
    }
}