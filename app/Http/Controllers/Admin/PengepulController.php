<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengepul;
use Illuminate\Http\Request;

class PengepulController extends Controller
{
    public function index()
    {
        $pengepuls = Pengepul::latest()->paginate(10);
        return view('admin.master.pengepul.index', compact('pengepuls'));
    }

    public function create()
    {
        return view('admin.master.pengepul.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'          => 'required|string|max:20|unique:pengepuls,kode',
            'nama_pengepul' => 'required|string|max:150',
            'no_telepon'    => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        Pengepul::create($request->only('kode', 'nama_pengepul', 'no_telepon', 'alamat', 'status'));

        return redirect()->route('admin.pengepul.index')->with('success', 'Data pengepul berhasil ditambahkan.');
    }

    public function edit(Pengepul $pengepul)
    {
        return view('admin.master.pengepul.edit', compact('pengepul'));
    }

    public function update(Request $request, Pengepul $pengepul)
    {
        $request->validate([
            'kode'          => 'required|string|max:20|unique:pengepuls,kode,' . $pengepul->id,
            'nama_pengepul' => 'required|string|max:150',
            'no_telepon'    => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        $pengepul->update($request->only('kode', 'nama_pengepul', 'no_telepon', 'alamat', 'status'));

        return redirect()->route('admin.pengepul.index')->with('success', 'Data pengepul berhasil diperbarui.');
    }

    public function destroy(Pengepul $pengepul)
    {
        $pengepul->delete();
        return redirect()->route('admin.pengepul.index')->with('success', 'Data pengepul berhasil dihapus.');
    }
}