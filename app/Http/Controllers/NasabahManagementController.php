<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NasabahManagementController extends Controller
{
    // 1. Tampilkan daftar semua akun nasabah (dengan fitur pencarian)
    public function index(Request $request)
    {
        $query = User::where('role', 'nasabah');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        if ($request->ajax() || $request->wantsJson() || $request->has('ajax')) {
            $suggestions = $query->orderBy('name', 'asc')->limit(8)->get(['id', 'name', 'username', 'email', 'no_hp', 'saldo']);
            return response()->json($suggestions);
        }

        $nasabahs = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('admin.nasabah.index', compact('nasabahs'));
    }

    // 2. Form pendaftaran nasabah warga baru
    public function create()
    {
        return view('admin.nasabah.create');
    }

    // 3. Simpan akun nasabah baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:150',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username',
            'email'    => 'nullable|email|max:150|unique:users,email',
            'no_hp'    => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ], [
            'name.required'     => 'Nama lengkap nasabah wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username ini sudah terdaftar.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, tanda strip, atau garis bawah.',
            'email.unique'      => 'Email ini sudah digunakan oleh akun lain.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => strtolower(trim($request->username)),
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'password' => Hash::make($request->password),
            'role'     => 'nasabah',
            'saldo'    => 0,
        ]);

        return redirect()->route('nasabah.index')
            ->with('success', "Akun nasabah '{$request->name}' berhasil didaftarkan!");
    }

    // 4. Form edit nasabah
    public function edit($id)
    {
        $nasabah = User::where('role', 'nasabah')->findOrFail($id);
        return view('admin.nasabah.edit', compact('nasabah'));
    }

    // 5. Simpan perubahan data nasabah
    public function update(Request $request, $id)
    {
        $nasabah = User::where('role', 'nasabah')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:150',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $nasabah->id,
            'email'    => 'nullable|email|max:150|unique:users,email,' . $nasabah->id,
            'no_hp'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => strtolower(trim($request->username)),
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
        ];

        // Jika kolom password diisi, update password-nya
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $nasabah->update($data);

        return redirect()->route('nasabah.index')
            ->with('success', "Data nasabah '{$nasabah->name}' berhasil diperbarui!");
    }

    // 6. Hapus akun nasabah
    public function destroy($id)
    {
        $nasabah = User::where('role', 'nasabah')->findOrFail($id);

        // Pengamanan: Jangan izinkan hapus nasabah jika masih ada saldo tersisa
        if ($nasabah->saldo > 0) {
            return back()->with('error', "Gagal menghapus! Nasabah ini masih memiliki saldo aktif sebesar Rp " . number_format($nasabah->saldo, 0, ',', '.') . ".");
        }

        $nasabah->delete();

        return back()->with('success', "Akun nasabah '{$nasabah->name}' berhasil dihapus.");
    }
}