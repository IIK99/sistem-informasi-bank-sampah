<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengepul;
use App\Models\JenisSampah;
use App\Models\HargaPengepul;
use Illuminate\Http\Request;

class HargaPengepulController extends Controller
{
    /**
     * Menampilkan daftar harga sampah milik pengepul tertentu
     */
    public function index(Pengepul $pengepul)
    {
        // Ambil semua master jenis sampah beserta relasi satuannya untuk dropdown pilihan
        $jenisSampahs = JenisSampah::with('satuan')->get();

        // Ambil daftar harga yang sudah diatur khusus pengepul ini
        $hargaList = HargaPengepul::with('jenisSampah.satuan')
            ->where('pengepul_id', $pengepul->id)
            ->get();

        return view('admin.master.pengepul.harga', compact('pengepul', 'jenisSampahs', 'hargaList'));
    }

    /**
     * Menyimpan harga baru atau memperbarui harga jika jenis sampah sudah ada
     */
    public function store(Request $request, Pengepul $pengepul)
    {
        $request->validate([
            'jenis_sampah_id' => 'required|exists:jenis_sampahs,id',
            'harga' => 'required|numeric|min:0',
        ], [
            'jenis_sampah_id.required' => 'Pilih jenis sampah terlebih dahulu.',
            'jenis_sampah_id.exists' => 'Jenis sampah tidak valid.',
            'harga.required' => 'Nominal harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh minus.',
        ]);

        // Ambil data jenis sampah terpilih untuk mendapatkan satuan_id miliknya
        $jenisSampah = JenisSampah::findOrFail($request->jenis_sampah_id);

        // Simpan atau update data harga pengepul
        HargaPengepul::updateOrCreate(
            [
                'pengepul_id' => $pengepul->id,
                'jenis_sampah_id' => $request->jenis_sampah_id,
            ],
            [
                'satuan_id' => $jenisSampah->satuan_id,
                'harga_beli' => $request->harga, // Mengisi kolom harga_beli
                'tanggal_berlaku' => now()->toDateString(), // Mengisi tanggal berlaku hari ini
            ]
        );

        return redirect()
            ->route('admin.pengepul.harga.index', $pengepul->id)
            ->with('success', 'Harga sampah pengepul berhasil disimpan!');
}
    /**
     * Menghapus baris harga sampah dari pengepul terkait
     */
    public function destroy(Pengepul $pengepul, HargaPengepul $hargaPengepul)
    {
        $hargaPengepul->delete();

        return redirect()
            ->route('admin.pengepul.harga.index', $pengepul->id)
            ->with('success', 'Harga sampah berhasil dihapus!');
    }
}