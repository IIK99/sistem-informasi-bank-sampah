<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRoleManagementController extends Controller
{
    public function index(Request $request, $slug)
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        $query = User::where('role', $slug);

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

        $users = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('admin.users.generic.index', compact('users', 'role'));
    }

    public function create($slug)
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        return view('admin.users.generic.create', compact('role'));
    }

    public function store(Request $request, $slug)
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username',
            'email'    => 'nullable|email|max:150|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'name.required'       => "Nama lengkap {$role->name} wajib diisi.",
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username ini sudah terdaftar.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, tanda strip, atau garis bawah.',
            'email.unique'        => 'Email ini sudah digunakan oleh akun lain.',
            'password.min'        => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role->slug,
            'saldo'    => 0,
        ]);

        return redirect()->route('admin.kelola-pengguna.index', $role->slug)
            ->with('success', "Akun {$role->name} '{$request->name}' berhasil ditambahkan!");
    }

    public function edit($slug, $id)
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        $user = User::where('role', $slug)->findOrFail($id);

        return view('admin.users.generic.edit', compact('user', 'role'));
    }

    public function update(Request $request, $slug, $id)
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        $user = User::where('role', $slug)->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'email'    => 'nullable|email|max:150|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ], [
            'name.required'       => "Nama lengkap {$role->name} wajib diisi.",
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username ini sudah terdaftar.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, tanda strip, atau garis bawah.',
            'email.unique'        => 'Email ini sudah digunakan oleh akun lain.',
            'password.min'        => 'Password minimal 6 karakter.',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.kelola-pengguna.index', $role->slug)
            ->with('success', "Data {$role->name} '{$user->name}' berhasil diperbarui!");
    }

    public function destroy($slug, $id)
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        $user = User::where('role', $slug)->findOrFail($id);

        $user->delete();

        return back()->with('success', "Akun {$role->name} '{$user->name}' berhasil dihapus.");
    }
}
