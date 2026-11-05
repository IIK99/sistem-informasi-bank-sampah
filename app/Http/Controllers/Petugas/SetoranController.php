<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use App\Models\DetailSetoranSampah;
use App\Models\User;
use App\Models\JenisSampah;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SetoranController extends Controller
{
    public function index()
    {
        NotificationService::markCategoryAsReadForUser(Auth::user(), 'setoran');

        $setorans = SetoranSampah::with(['nasabah', 'petugas', 'details.jenisSampah', 'details.satuan'])
            ->latest()
            ->paginate(10);

        return view('petugas.setoran.index', compact('setorans'));
    }

    public function create()
    {
        $nasabahs = User::where('role', 'nasabah')->orderBy('name', 'asc')->get();

        $jenisSampahs = JenisSampah::with(['satuan', 'kategori'])
            ->where('status', 'aktif')
            ->orderBy('nama_jenis', 'asc')
            ->get();

        return view('petugas.setoran.create', compact('nasabahs', 'jenisSampahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'                 => 'required|exists:users,id',
            'items'                   => 'required|array|min:1',
            'items.*.jenis_sampah_id' => 'required|exists:jenis_sampahs,id',
            'items.*.jumlah'          => 'required|numeric|min:0.01',
            'items.*.harga_satuan'    => 'required|numeric|min:1',
            'catatan'                 => 'nullable|string|max:255',
        ], [
            'user_id.required'        => 'Pilih nasabah yang menyetor.',
            'items.required'          => 'Keranjang setoran masih kosong! Tambahkan minimal 1 jenis sampah.',
            'items.*.jumlah.min'      => 'Berat timbangan minimal 0.01.',
        ]);

        DB::beginTransaction();
        try {
            $totalAkumulasi = 0;
            $totalBerat = 0;

            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_satuan'];
                $totalAkumulasi += $subtotal;
                $totalBerat += $item['jumlah'];
            }

            // 1. Simpan Header Nota Setoran
            $setoran = SetoranSampah::create([
                'kode_setoran'    => 'STR-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'user_id'         => $request->user_id,
                'petugas_id'      => Auth::id(),
                'jenis_sampah_id' => $request->items[0]['jenis_sampah_id'],
                'satuan_id'       => 1,
                'jumlah'          => $totalBerat,
                'harga_satuan'    => 0,
                'total_harga'     => $totalAkumulasi,
                'catatan'         => $request->catatan,
            ]);

            // 2. Simpan Rincian Multi-Item
            foreach ($request->items as $item) {
                $sampah = JenisSampah::findOrFail($item['jenis_sampah_id']);

                DetailSetoranSampah::create([
                    'setoran_sampah_id' => $setoran->id,
                    'jenis_sampah_id'   => $sampah->id,
                    'satuan_id'         => $sampah->satuan_id ?? 1,
                    'jumlah'            => $item['jumlah'],
                    'harga_satuan'      => $item['harga_satuan'],
                    'subtotal'          => $item['jumlah'] * $item['harga_satuan'],
                ]);
            }

            // 3. Tambahkan Saldo Nasabah Secara Otomatis
            $nasabah = User::findOrFail($request->user_id);
            $nasabah->increment('saldo', $totalAkumulasi);

            DB::commit();

            try {
                \App\Services\NotificationService::notifySetoranSampah($setoran);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Notifikasi setoran sampah error: ' . $e->getMessage());
            }

            return redirect()->route('petugas.setoran.index')
                ->with('success', "Setoran nota {$setoran->kode_setoran} berhasil dicatat! Saldo Rp " . number_format($totalAkumulasi, 0, ',', '.') . " telah ditambahkan ke {$nasabah->name}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses setoran: ' . $e->getMessage());
        }
    }
}