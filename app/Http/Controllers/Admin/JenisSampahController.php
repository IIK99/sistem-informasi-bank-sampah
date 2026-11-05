<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use App\Models\Satuan;
use Illuminate\Http\Request;

class JenisSampahController extends Controller
{
    public function index()
    {
        $jenisSampahs = JenisSampah::with(['kategori', 'satuan'])->latest()->paginate(10);
        $kategoris = KategoriSampah::orderBy('nama_kategori')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();
        return view('admin.master.jenis.index', compact('jenisSampahs', 'kategoris', 'satuans'));
    }

    public function create()
    {
        $kategoris = KategoriSampah::orderBy('nama_kategori')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();
        return view('admin.master.jenis.create', compact('kategoris', 'satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis'  => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori_sampahs,id',
            'satuan_id'   => 'required|exists:satuans,id',
            'status'      => 'required|in:aktif,nonaktif',
        ]);

        JenisSampah::create($request->only('nama_jenis', 'kategori_id', 'satuan_id', 'status'));

        return redirect()->route('admin.jenis-sampah.index')->with('success', 'Jenis sampah berhasil ditambahkan.');
    }

    public function edit(JenisSampah $jenisSampah)
    {
        $kategoris = KategoriSampah::orderBy('nama_kategori')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();
        return view('admin.master.jenis.edit', compact('jenisSampah', 'kategoris', 'satuans'));
    }

    public function update(Request $request, JenisSampah $jenisSampah)
    {
        $request->validate([
            'nama_jenis'  => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori_sampahs,id',
            'satuan_id'   => 'required|exists:satuans,id',
            'status'      => 'required|in:aktif,nonaktif',
        ]);

        $jenisSampah->update($request->only('nama_jenis', 'kategori_id', 'satuan_id', 'status'));

        return redirect()->route('admin.jenis-sampah.index')->with('success', 'Jenis sampah berhasil diperbarui.');
    }

    public function destroy(JenisSampah $jenisSampah)
    {
        $jenisSampah->delete();
        return redirect()->route('admin.jenis-sampah.index')->with('success', 'Jenis sampah berhasil dihapus.');
    }
}