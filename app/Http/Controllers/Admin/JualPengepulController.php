<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenjualanPengepul;
use App\Models\DetailPenjualanPengepul;
use App\Models\Pengepul;
use App\Models\HargaPengepul;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JualPengepulController extends Controller
{
    public function index()
    {
        NotificationService::markCategoryAsReadForUser(Auth::user(), 'jual-pengepul');

        $penjualans = PenjualanPengepul::with(['pengepul', 'petugas', 'details.jenisSampah', 'details.satuan'])
            ->latest()
            ->paginate(10);

        return view('transaksi.jual_pengepul.index', compact('penjualans'));
    }

    public function create()
    {
        $pengepuls = Pengepul::where('status', 'aktif')->orderBy('nama_pengepul', 'asc')->get();

        return view('transaksi.jual_pengepul.create', compact('pengepuls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengepul_id'            => 'required|exists:pengepuls,id',
            'items'                  => 'required|array|min:1',
            'items.*.jenis_sampah_id'=> 'required|exists:jenis_sampahs,id',
            'items.*.jumlah'         => 'required|numeric|min:0.01',
            'items.*.harga_satuan'   => 'required|numeric|min:1',
            'biaya_operasional'      => 'nullable|numeric|min:0',
            'keterangan_operasional' => 'nullable|string|max:255',
            'catatan'                => 'nullable|string|max:255',
        ], [
            'pengepul_id.required'   => 'Pilih mitra pengepul terlebih dahulu.',
            'items.required'         => 'Keranjang transaksi masih kosong. Tambahkan minimal 1 item.',
            'items.*.jumlah.min'     => 'Berat timbangan tidak boleh nol.',
        ]);

        DB::beginTransaction();
        try {
            $totalPenjualanKotor = 0;
            $totalBerat = 0;

            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_satuan'];
                $totalPenjualanKotor += $subtotal;
                $totalBerat += $item['jumlah'];
            }

            $biayaOperasional = $request->biaya_operasional ?? 0;
            $totalKasBersih = $totalPenjualanKotor - $biayaOperasional;

            // 1. Simpan Header Nota Penjualan
            $penjualan = PenjualanPengepul::create([
                'kode_penjualan'         => 'JPL-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'pengepul_id'            => $request->pengepul_id,
                'jenis_sampah_id'        => $request->items[0]['jenis_sampah_id'],
                'satuan_id'              => 1,
                'user_id'                => Auth::id(),
                'jumlah'                 => $totalBerat,
                'harga_satuan'           => 0,
                'total_harga'            => $totalPenjualanKotor,
                'biaya_operasional'      => $biayaOperasional,
                'keterangan_operasional' => $request->keterangan_operasional,
                'total_kas_bersih'       => $totalKasBersih,
                'catatan'                => $request->catatan,
            ]);

            // 2. Simpan Item Keranjang ke Detail
            foreach ($request->items as $item) {
                $hargaPengepul = HargaPengepul::where('pengepul_id', $request->pengepul_id)
                    ->where('jenis_sampah_id', $item['jenis_sampah_id'])
                    ->firstOrFail();

                DetailPenjualanPengepul::create([
                    'penjualan_pengepul_id' => $penjualan->id,
                    'jenis_sampah_id'       => $item['jenis_sampah_id'],
                    'satuan_id'             => $hargaPengepul->satuan_id,
                    'jumlah'                => $item['jumlah'],
                    'harga_satuan'          => $item['harga_satuan'],
                    'subtotal'              => $item['jumlah'] * $item['harga_satuan'],
                ]);
            }

            DB::commit();

            try {
                \App\Services\NotificationService::notifyPenjualanPengepul($penjualan);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Notifikasi penjualan pengepul error: ' . $e->getMessage());
            }

            return redirect()->route('jual-pengepul.index')
                ->with('success', "Nota kasir {$penjualan->kode_penjualan} berhasil disimpan! Kas bersih bank sampah: Rp " . number_format($totalKasBersih, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function destroy(PenjualanPengepul $penjualan)
    {
        $penjualan->delete();

        return redirect()->route('jual-pengepul.index')
            ->with('success', "Transaksi nota {$penjualan->kode_penjualan} berhasil dihapus.");
    }

    public function getSampahByPengepul(Pengepul $pengepul)
    {
        $hargas = HargaPengepul::with(['jenisSampah.kategori', 'jenisSampah.satuan'])
            ->where('pengepul_id', $pengepul->id)
            ->get()
            ->map(function ($item) {
                return [
                    'jenis_sampah_id' => $item->jenis_sampah_id,
                    'nama_jenis'      => $item->jenisSampah->nama_jenis,
                    'kategori'        => $item->jenisSampah->kategori->nama_kategori ?? 'Umum',
                    'satuan'          => $item->satuan->nama_satuan ?? 'Kg',
                    'simbol'          => $item->satuan->simbol ?? 'kg',
                    'harga'           => (float) $item->harga_beli,
                ];
            });

        return response()->json($hargas);
    }
}