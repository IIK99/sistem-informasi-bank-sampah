<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SetoranSampah;
use App\Models\PenarikanSaldo;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    private function getData($type)
    {
        $user = Auth::user();
        $isNasabah = $user->role === 'nasabah';
        
        if ($type === 'setoran') {
            return $isNasabah ? SetoranSampah::where('nasabah_id', $user->id)->get() : SetoranSampah::all();
        } elseif ($type === 'penarikan') {
            return $isNasabah ? PenarikanSaldo::where('nasabah_id', $user->id)->get() : PenarikanSaldo::all();
        }
        
        return collect();
    }

    public function setoran(Request $request)
    {
        $data = $this->getData('setoran');
        $title = 'Laporan Setoran';
        $type = 'setoran';
        
        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('title', 'data', 'type'));
            return $pdf->download('laporan_setoran.pdf');
        }

        return view('laporan.index', compact('title', 'data', 'type'));
    }

    public function penarikan(Request $request)
    {
        $data = $this->getData('penarikan');
        $title = 'Laporan Penarikan';
        $type = 'penarikan';
        
        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('title', 'data', 'type'));
            return $pdf->download('laporan_penarikan.pdf');
        }

        return view('laporan.index', compact('title', 'data', 'type'));
    }

    public function saldo(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'nasabah') {
            $nasabahs = \App\Models\User::where('id', $user->id)->get();
        } else {
            $nasabahs = \App\Models\User::where('role', 'nasabah')->get();
        }

        $data = $nasabahs->map(function($nasabah) {
            $setoran = SetoranSampah::where('nasabah_id', $nasabah->id)->sum('total_harga');
            $penarikan = PenarikanSaldo::where('nasabah_id', $nasabah->id)->where('status', 'disetujui')->sum('jumlah');
            $nasabah->saldo = $setoran - $penarikan;
            return $nasabah;
        });

        $title = 'Laporan Saldo';
        $type = 'saldo';
        
        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('title', 'data', 'type'));
            return $pdf->download('laporan_saldo.pdf');
        }

        return view('laporan.index', compact('title', 'data', 'type'));
    }

    public function sampah(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'nasabah') {
            $data = SetoranSampah::where('nasabah_id', $user->id)
                ->selectRaw('tanggal, sum(total_berat) as berat, sum(total_harga) as harga')
                ->groupBy('tanggal')
                ->get();
        } else {
            $data = SetoranSampah::selectRaw('tanggal, sum(total_berat) as berat, sum(total_harga) as harga')
                ->groupBy('tanggal')
                ->get();
        }

        $title = 'Laporan Sampah';
        $type = 'sampah';
        
        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('title', 'data', 'type'));
            return $pdf->download('laporan_sampah.pdf');
        }

        return view('laporan.index', compact('title', 'data', 'type'));
    }
}
