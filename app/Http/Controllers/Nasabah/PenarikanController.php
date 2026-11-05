<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\PenarikanSaldo;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PenarikanController extends Controller
{
    // Menampilkan riwayat penarikan milik nasabah (Tabel 6 No. 5)
    public function index()
    {
        NotificationService::markCategoryAsReadForUser(Auth::user(), 'penarikan');

        $penarikans = PenarikanSaldo::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        $saldo = Auth::user()->saldo ?? 0;

        return view('nasabah.penarikan.index', compact('penarikans', 'saldo'));
    }

    // Formulir ajukan sekarang dialihkan ke modal pada halaman riwayat penarikan
    public function create()
    {
        return redirect()->route('nasabah.penarikan.index');
    }

    // Menyimpan pengajuan penarikan
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nominal' => [
                'required',
                'numeric',
                'min:10000',
                'max:' . $user->saldo,
            ],
            'catatan_nasabah' => 'nullable|string|max:255',
        ], [
            'nominal.min' => 'Minimal penarikan saldo adalah Rp 10.000.',
            'nominal.max' => 'Nominal penarikan melebihi sisa saldo Anda.',
            'nominal.required' => 'Nominal penarikan wajib diisi.',
        ]);

        $penarikan = PenarikanSaldo::create([
            'kode_penarikan'  => 'WD-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'user_id'          => $user->id,
            'nominal'          => $request->nominal,
            'status'           => 'menunggu',
            'catatan_nasabah'  => $request->catatan_nasabah,
        ]);

        try {
            \App\Services\NotificationService::notifyPengajuanPenarikan($penarikan);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Notifikasi pengajuan penarikan error: ' . $e->getMessage());
        }

        return redirect()->route('nasabah.penarikan.index')
            ->with('success', 'Pengajuan penarikan saldo berhasil dikirim. Menunggu verifikasi petugas.');
    }
}