<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SetoranSampah;
use App\Models\PenarikanSaldo;

class SaldoController extends Controller
{
    public function index()
    {
        // 1. Akumulasi total saldo semua nasabah warga
        $totalSaldoWarga = User::where('role', 'nasabah')->sum('saldo');
        $totalNasabah    = User::where('role', 'nasabah')->count();

        // 2. Daftar rincian saldo per nasabah
        $daftarNasabah = User::where('role', 'nasabah')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('petugas.saldo.index', compact(
            'totalSaldoWarga',
            'totalNasabah',
            'daftarNasabah'
        ));
    }
}