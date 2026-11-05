<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NasabahController extends Controller
{
    public function index()
    {
        $nasabahs = User::where('role', 'nasabah')->get();
        return view('petugas.nasabah.index', compact('nasabahs'));
    }

    public function create()
    {
        return view('petugas.nasabah.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'nasabah'
        ]);

        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil ditambahkan');
    }

    public function edit($id)
    {
        $nasabah = User::findOrFail($id);
        return view('petugas.nasabah.form', compact('nasabah'));
    }

    public function update(Request $request, $id)
    {
        $nasabah = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,'.$id,
            'no_hp' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $nasabah->update($data);

        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil diupdate');
    }

    public function destroy($id)
    {
        $nasabah = User::findOrFail($id);
        $nasabah->delete();
        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil dihapus');
    }
}
