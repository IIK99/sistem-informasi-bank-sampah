<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\PenarikanSaldo;
use Illuminate\Support\Facades\Auth;

class SaldoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Mengambil ringkasan riwayat penarikan terakhir nasabah
        $riwayatPenarikan = PenarikanSaldo::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('nasabah.saldo.index', compact('user', 'riwayatPenarikan'));
    }
}