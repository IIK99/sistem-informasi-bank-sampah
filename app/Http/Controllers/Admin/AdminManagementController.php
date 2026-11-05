<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    /**
     * Tampilkan daftar semua akun Admin (dengan fitur pencarian & paginasi).
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'admin');

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

        $admins = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('admin.users.admin.index', compact('admins'));
    }

    /**
     * Form tambah admin baru.
     */
    public function create()
    {
        return view('admin.users.admin.create');
    }

    /**
     * Simpan akun admin baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:150',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username',
            'email'    => 'nullable|email|max:150|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
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
            'role'     => 'admin',
            'saldo'    => 0,
        ]);

        return redirect()->route('admin.kelola-admin.index')
            ->with('success', "Akun admin '{$request->name}' berhasil ditambahkan!");
    }

    /**
     * Form edit data admin.
     */
    public function edit($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        return view('admin.users.admin.edit', compact('admin'));
    }

    /**
     * Simpan perubahan data admin.
     */
    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:150',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $admin->id,
            'email'    => 'nullable|email|max:150|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:6',
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
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

        $admin->update($data);

        return redirect()->route('admin.kelola-admin.index')
            ->with('success', "Data admin '{$admin->name}' berhasil diperbarui!");
    }

    /**
     * Hapus akun admin (tidak boleh menghapus akun sendiri).
     */
    public function destroy($id)
    {
        if ($id == Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->delete();

        return back()->with('success', "Akun admin '{$admin->name}' berhasil dihapus.");
    }
}