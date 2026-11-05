<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendBroadcastEmailJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BroadcastController extends Controller
{
    public function index()
    {
        $tanggalHariIni = now()->locale('id')->translatedFormat('l, d F Y');

        $defaultTemplate = '<p>Yth. Bapak/Ibu Nasabah Bank Sampah,</p>'
            . '<p>Diberitahukan bahwa <strong>HARI INI</strong> ada jadwal penimbangan sampah bersama mitra pengepul.<br>'
            . 'Bagi nasabah yang ingin menyetorkan sampah pilahannya, silakan datang ke lokasi Bank Sampah pada:</p>'
            . '<ul>'
            . '<li><strong>Hari / Tanggal :</strong> ' . $tanggalHariIni . '</li>'
            . '<li><strong>Waktu :</strong> Pukul 09.00 - 12.00 WIB</li>'
            . '<li><strong>Lokasi :</strong> Pos Utama Bank Sampah</li>'
            . '</ul>'
            . '<p>Pastikan sampah sudah dipilah sesuai kategorinya dalam kondisi bersih dan kering.<br>'
            . 'Terima kasih atas partisipasi Anda menjaga kebersihan lingkungan!</p>';

        $totalNasabah = User::where('role', 'nasabah')->count();

        return view('admin.broadcast.index', compact('defaultTemplate', 'totalNasabah'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'pesan' => 'required|string',
        ]);

        $judul = $request->judul;
        $pesan = $request->pesan;

        // 1. Kirim notifikasi lonceng in-app instan ke semua nasabah
        $nasabahs = User::where('role', 'nasabah')->get();
        foreach ($nasabahs as $nasabah) {
            DB::table('notifications')->insert([
                'id'              => (string) Str::uuid(),
                'type'            => 'BroadcastPengumuman',
                'notifiable_type' => User::class,
                'notifiable_id'   => $nasabah->id,
                'data'            => json_encode([
                    'title'       => $judul,
                    'message'     => Str::limit($pesan, 90),
                    'url'         => '/dashboard',
                    'icon'        => 'bi-megaphone-fill',
                    'color'       => 'info',
                    'target_menu' => 'dashboard',
                ]),
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        // 2. Lempar pengiriman email massal ke antrean background (Queue)
        SendBroadcastEmailJob::dispatch($judul, $pesan);

        return redirect()->back()->with('success', 'Pesan siaran berhasil dikirim ke antrean! Notifikasi lonceng aktif dan email sedang dikirim di latar belakang.');
    }
}