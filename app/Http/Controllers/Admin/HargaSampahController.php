<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HargaPengepul;
use App\Models\JenisSampah;
use App\Models\Pengepul;
use App\Models\Satuan;
use Illuminate\Http\Request;

class HargaSampahController extends Controller
{
    public function index()
    {
        $hargas = HargaPengepul::with(['pengepul', 'jenisSampah', 'satuan'])->latest()->paginate(10);
        $pengepuls = Pengepul::where('status', 'aktif')->orderBy('nama_pengepul')->get();
        $jenisSampahs = JenisSampah::where('status', 'aktif')->orderBy('nama_jenis')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();

        return view('admin.master.harga.index', compact('hargas', 'pengepuls', 'jenisSampahs', 'satuans'));
    }

    public function create()
    {
        $pengepuls = Pengepul::where('status', 'aktif')->orderBy('nama_pengepul')->get();
        $jenisSampahs = JenisSampah::where('status', 'aktif')->orderBy('nama_jenis')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();

        return view('admin.master.harga.create', compact('pengepuls', 'jenisSampahs', 'satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengepul_id'     => 'required|exists:pengepuls,id',
            'jenis_sampah_id' => 'required|exists:jenis_sampahs,id',
            'satuan_id'       => 'required|exists:satuans,id',
            'harga_beli'      => 'required|numeric|min:0',
            'tanggal_berlaku' => 'nullable|date',
        ]);

        HargaPengepul::create($request->only('pengepul_id', 'jenis_sampah_id', 'satuan_id', 'harga_beli', 'tanggal_berlaku'));

        return redirect()->route('admin.harga-sampah.index')->with('success', 'Harga sampah berhasil ditetapkan.');
    }

    public function edit(HargaPengepul $hargaSampah)
    {
        $pengepuls = Pengepul::where('status', 'aktif')->orderBy('nama_pengepul')->get();
        $jenisSampahs = JenisSampah::where('status', 'aktif')->orderBy('nama_jenis')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();

        return view('admin.master.harga.edit', [
            'harga'        => $hargaSampah,
            'pengepuls'    => $pengepuls,
            'jenisSampahs' => $jenisSampahs,
            'satuans'      => $satuans,
        ]);
    }

    public function update(Request $request, HargaPengepul $hargaSampah)
    {
        $request->validate([
            'pengepul_id'     => 'required|exists:pengepuls,id',
            'jenis_sampah_id' => 'required|exists:jenis_sampahs,id',
            'satuan_id'       => 'required|exists:satuans,id',
            'harga_beli'      => 'required|numeric|min:0',
            'tanggal_berlaku' => 'nullable|date',
        ]);

        $hargaSampah->update($request->only('pengepul_id', 'jenis_sampah_id', 'satuan_id', 'harga_beli', 'tanggal_berlaku'));

        return redirect()->route('admin.harga-sampah.index')->with('success', 'Harga sampah berhasil diperbarui.');
    }

    public function destroy(HargaPengepul $hargaSampah)
    {
        $hargaSampah->delete();
        return redirect()->route('admin.harga-sampah.index')->with('success', 'Harga sampah berhasil dihapus.');
    }
}