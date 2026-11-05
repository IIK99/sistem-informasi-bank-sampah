<?php

namespace App\Http\Controllers;

use App\Models\SetoranSampah;
use App\Models\PenarikanSaldo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    // 12. Laporan Setoran Sampah
    public function setoran(Request $request)
    {
        $query = SetoranSampah::with(['nasabah', 'petugas', 'jenisSampah', 'satuan']);

        // Filter Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('setoran_sampahs.created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter Jenis Sampah (via relasi jenis_sampah_id)
        if ($request->filled('jenis_sampah_id')) {
            $query->where('jenis_sampah_id', $request->jenis_sampah_id);
        }

        // Filter Nasabah
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $setorans = (clone $query)->latest()->paginate(15)->withQueryString();
        $totalBerat = (clone $query)->sum('jumlah');
        $totalNominal = (clone $query)->sum('total_harga');

        $nasabahs = User::where('role', 'nasabah')->orderBy('name')->get();

        return view('laporan.setoran', compact('setorans', 'totalBerat', 'totalNominal', 'nasabahs'));
    }

    // 13. Laporan Penarikan Saldo
    public function penarikan(Request $request)
    {
        $query = PenarikanSaldo::with(['nasabah', 'petugas']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $penarikans = (clone $query)->latest()->paginate(15)->withQueryString();
        $totalDitarik = (clone $query)->where('status', 'disetujui')->sum('nominal');

        $nasabahs = User::where('role', 'nasabah')->orderBy('name')->get();

        return view('laporan.penarikan', compact('penarikans', 'totalDitarik', 'nasabahs'));
    }

    // 14. Laporan Sampah (Rekap Berat per Jenis Sampah)
    public function sampah(Request $request)
    {
        $query = SetoranSampah::join('jenis_sampahs', 'setoran_sampahs.jenis_sampah_id', '=', 'jenis_sampahs.id')
            ->leftJoin('satuans', 'setoran_sampahs.satuan_id', '=', 'satuans.id')
            ->select(
                'jenis_sampahs.nama_jenis as jenis_sampah',
                'satuans.simbol as satuan',
                DB::raw('SUM(setoran_sampahs.jumlah) as total_berat'),
                DB::raw('SUM(setoran_sampahs.total_harga) as total_uang'),
                DB::raw('COUNT(setoran_sampahs.id) as total_transaksi')
            )
            ->groupBy('jenis_sampahs.id', 'jenis_sampahs.nama_jenis', 'satuans.simbol');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('setoran_sampahs.created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $rekapSampah = (clone $query)->paginate(15)->withQueryString();
        $unpaginated = (clone $query)->get();
        $grandTotalBerat = $unpaginated->sum('total_berat');
        $grandTotalUang = $unpaginated->sum('total_uang');

        return view('laporan.sampah', compact('rekapSampah', 'grandTotalBerat', 'grandTotalUang'));
    }

    // 15. Laporan Saldo Nasabah
    public function saldo(Request $request)
    {
        $query = User::where('role', 'nasabah')
            ->with(['setoranSaldos', 'penarikanSaldos'])
            ->orderBy('name');

        $totalKasWarga = User::where('role', 'nasabah')->sum('saldo');

        $nasabahs = $query->paginate(15)->withQueryString()->through(function ($user) {
            return (object) [
                'name'            => $user->name,
                'username'        => $user->username,
                'total_setoran'   => $user->setoranSaldos->sum('total_harga'),
                'total_penarikan' => $user->penarikanSaldos->where('status', 'disetujui')->sum('nominal'),
                'saldo_aktif'     => $user->saldo,
            ];
        });

        return view('laporan.saldo', compact('nasabahs', 'totalKasWarga'));
    }


    // 1. Cetak PDF Laporan Setoran
    public function cetakSetoranPdf(Request $request)
    {
        $query = SetoranSampah::with(['nasabah', 'petugas', 'jenisSampah', 'satuan']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('setoran_sampahs.created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
        if ($request->filled('jenis_sampah_id')) {
            $query->where('jenis_sampah_id', $request->jenis_sampah_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $setorans     = $query->latest()->get();
        $totalBerat   = $setorans->sum('jumlah');
        $totalNominal = $setorans->sum('total_harga');

        $pdf = Pdf::loadView('laporan.pdf.setoran', compact('setorans', 'totalBerat', 'totalNominal'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Setoran-Sampah-' . date('Ymd-His') . '.pdf');
    }

    // 2. Cetak PDF Laporan Penarikan Saldo
    public function cetakPenarikanPdf(Request $request)
    {
        $query = PenarikanSaldo::with(['nasabah', 'petugas']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $penarikans   = $query->latest()->get();
        $totalDitarik = $penarikans->where('status', 'disetujui')->sum('nominal');

        foreach ($penarikans as $item) {
            $item->bukti_base64 = null;
            if ($item->bukti_transfer) {
                $filePath = storage_path('app/public/' . $item->bukti_transfer);
                if (file_exists($filePath)) {
                    $mime = mime_content_type($filePath);
                    if (str_starts_with($mime, 'image/')) {
                        $item->bukti_base64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($filePath));
                    }
                }
            }
        }

        $pdf = Pdf::loadView('laporan.pdf.penarikan', compact('penarikans', 'totalDitarik'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Penarikan-Saldo-' . date('Ymd-His') . '.pdf');
    }

    // 3. Cetak PDF Laporan Rekap Sampah
    public function cetakSampahPdf(Request $request)
    {
        $query = SetoranSampah::join('jenis_sampahs', 'setoran_sampahs.jenis_sampah_id', '=', 'jenis_sampahs.id')
            ->leftJoin('satuans', 'setoran_sampahs.satuan_id', '=', 'satuans.id')
            ->select(
                'jenis_sampahs.nama_jenis as jenis_sampah',
                'satuans.simbol as satuan',
                DB::raw('SUM(setoran_sampahs.jumlah) as total_berat'),
                DB::raw('SUM(setoran_sampahs.total_harga) as total_uang'),
                DB::raw('COUNT(setoran_sampahs.id) as total_transaksi')
            )
            ->groupBy('jenis_sampahs.id', 'jenis_sampahs.nama_jenis', 'satuans.simbol');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('setoran_sampahs.created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $rekapSampah = $query->get();
        $grandTotalBerat = $rekapSampah->sum('total_berat');
        $grandTotalUang = $rekapSampah->sum('total_uang');

        $pdf = Pdf::loadView('laporan.pdf.sampah', compact('rekapSampah', 'grandTotalBerat', 'grandTotalUang'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Akumulasi-Sampah-' . date('Ymd-His') . '.pdf');
    }

    // 4. Cetak PDF Laporan Saldo Nasabah
    public function cetakSaldoPdf()
    {
        $nasabahs = User::where('role', 'nasabah')
            ->with(['setoranSaldos', 'penarikanSaldos'])
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return (object) [
                    'name'            => $user->name,
                    'username'        => $user->username,
                    'total_setoran'   => $user->setoranSaldos->sum('total_harga'),
                    'total_penarikan' => $user->penarikanSaldos->where('status', 'disetujui')->sum('nominal'),
                    'saldo_aktif'     => $user->saldo,
                ];
            });

        $totalKasWarga = $nasabahs->sum('saldo_aktif');

        $pdf = Pdf::loadView('laporan.pdf.saldo', compact('nasabahs', 'totalKasWarga'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Rekap-Saldo-Warga-' . date('Ymd-His') . '.pdf');
    }

    public function jualPengepul(Request $request)
    {
        $pengepuls = \App\Models\Pengepul::orderBy('nama_pengepul', 'asc')->get();
        $query = \App\Models\PenjualanPengepul::with(['pengepul', 'details.jenisSampah.satuan', 'petugas']);

        // Filter Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter Mitra Pengepul
        if ($request->filled('pengepul_id')) {
            $query->where('pengepul_id', $request->pengepul_id);
        }

        $penjualans       = (clone $query)->latest()->paginate(15)->withQueryString();
        $totalKotor       = (clone $query)->sum('total_harga');
        $totalOperasional = (clone $query)->sum('biaya_operasional');
        $totalKasBersih   = (clone $query)->sum('total_kas_bersih');

        return view('laporan.jual_pengepul', compact('penjualans', 'pengepuls', 'totalKotor', 'totalOperasional', 'totalKasBersih'));
    }

    // Cetak DomPDF Laporan Jual Pengepul
    public function cetakJualPengepulPdf(Request $request)
    {
        $query = \App\Models\PenjualanPengepul::with(['pengepul', 'details.jenisSampah.satuan', 'petugas']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('pengepul_id')) {
            $query->where('pengepul_id', $request->pengepul_id);
        }

        $penjualans       = $query->latest()->get();
        $totalKotor       = $penjualans->sum('total_harga');
        $totalOperasional = $penjualans->sum('biaya_operasional');
        $totalKasBersih   = $penjualans->sum('total_kas_bersih');

        $pdf = Pdf::loadView('laporan.pdf.jual_pengepul', compact('penjualans', 'totalKotor', 'totalOperasional', 'totalKasBersih'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Jual-Pengepul-' . date('Ymd-His') . '.pdf');
    }
}