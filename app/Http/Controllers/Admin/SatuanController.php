<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::latest()->paginate(10);
        return view('admin.master.satuan.index', compact('satuans'));
    }

    public function create()
    {
        return view('admin.master.satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuans,nama_satuan',
            'simbol'      => 'required|string|max:10',
        ]);

        Satuan::create($request->only('nama_satuan', 'simbol'));

        return redirect()->route('admin.satuan.index')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function edit(Satuan $satuan)
    {
        return view('admin.master.satuan.edit', compact('satuan'));
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuans,nama_satuan,' . $satuan->id,
            'simbol'      => 'required|string|max:10',
        ]);

        $satuan->update($request->only('nama_satuan', 'simbol'));

        return redirect()->route('admin.satuan.index')->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Satuan $satuan)
    {
        $satuan->delete();
        return redirect()->route('admin.satuan.index')->with('success', 'Satuan berhasil dihapus.');
    }
}