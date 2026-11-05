<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasManagementController extends Controller
{
    /**
     * Tampilkan daftar semua akun Petugas (dengan fitur pencarian & paginasi).
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'petugas');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->ajax() || $request->wantsJson() || $request->has('ajax')) {
            $suggestions = $query->orderBy('name', 'asc')->limit(8)->get(['id', 'name', 'username', 'email']);
            return response()->json($suggestions);
        }

        $petugasList = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('admin.users.petugas.index', compact('petugasList'));
    }

    /**
     * Form pendaftaran petugas baru.
     */
    public function create()
    {
        return view('admin.users.petugas.create');
    }

    /**
     * Simpan akun petugas baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:150',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username',
            'email'    => 'nullable|email|max:150|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'name.required'       => 'Nama lengkap petugas wajib diisi.',
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username ini sudah terdaftar.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, tanda strip, atau garis bawah.',
            'email.unique'        => 'Email ini sudah digunakan oleh akun lain.',
            'password.min'        => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => strtolower(trim($request->username)),
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'petugas',
            'saldo'    => 0,
        ]);

        return redirect()->route('admin.kelola-petugas.index')
            ->with('success', "Akun petugas '{$request->name}' berhasil didaftarkan!");
    }

    /**
     * Form edit data petugas.
     */
    public function edit($id)
    {
        $petugas = User::where('role', 'petugas')->findOrFail($id);

        return view('admin.users.petugas.edit', compact('petugas'));
    }

    /**
     * Simpan perubahan data petugas.
     */
    public function update(Request $request, $id)
    {
        $petugas = User::where('role', 'petugas')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:150',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $petugas->id,
            'email'    => 'nullable|email|max:150|unique:users,email,' . $petugas->id,
            'password' => 'nullable|string|min:6',
        ], [
            'name.required'       => 'Nama lengkap petugas wajib diisi.',
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username ini sudah terdaftar.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, tanda strip, atau garis bawah.',
            'email.unique'        => 'Email ini sudah digunakan oleh akun lain.',
            'password.min'        => 'Password minimal 6 karakter.',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => strtolower(trim($request->username)),
            'email'    => $request->email,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $petugas->update($data);

        return redirect()->route('admin.kelola-petugas.index')
            ->with('success', "Data petugas '{$petugas->name}' berhasil diperbarui!");
    }

    /**
     * Hapus akun petugas (tolak jika masih ada riwayat transaksi setoran).
     */
    public function destroy($id)
    {
        $petugas = User::where('role', 'petugas')->findOrFail($id);

        // Cek integritas data: apakah petugas pernah mencatat transaksi setoran
        if (SetoranSampah::where('petugas_id', $petugas->id)->exists()) {
            return back()->with('error', "Gagal menghapus! Petugas '{$petugas->name}' masih memiliki riwayat transaksi setoran sampah yang terkait.");
        }

        $petugas->delete();

        return back()->with('success', "Akun petugas '{$petugas->name}' berhasil dihapus.");
    }
}