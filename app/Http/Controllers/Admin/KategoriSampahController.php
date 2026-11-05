<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSampah;
use Illuminate\Http\Request;

class KategoriSampahController extends Controller
{
    public function index()
    {
        $kategoris = KategoriSampah::latest()->paginate(10);
        return view('admin.master.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.master.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_sampahs,nama_kategori',
            'deskripsi'     => 'nullable|string',
        ]);

        KategoriSampah::create($request->only('nama_kategori', 'deskripsi'));

        return redirect()->route('admin.kategori-sampah.index')->with('success', 'Kategori sampah berhasil ditambahkan.');
    }

    public function edit(KategoriSampah $kategoriSampah)
    {
        return view('admin.master.kategori.edit', compact('kategoriSampah'));
    }

    public function update(Request $request, KategoriSampah $kategoriSampah)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_sampahs,nama_kategori,' . $kategoriSampah->id,
            'deskripsi'     => 'nullable|string',
        ]);

        $kategoriSampah->update($request->only('nama_kategori', 'deskripsi'));

        return redirect()->route('admin.kategori-sampah.index')->with('success', 'Kategori sampah berhasil diperbarui.');
    }

    public function destroy(KategoriSampah $kategoriSampah)
    {
        $kategoriSampah->delete();
        return redirect()->route('admin.kategori-sampah.index')->with('success', 'Kategori sampah berhasil dihapus.');
    }
}