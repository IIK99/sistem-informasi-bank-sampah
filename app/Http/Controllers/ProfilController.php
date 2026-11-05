<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Menu;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $layout = $user->role === 'nasabah' ? 'layouts.nasabah' : 'layouts.admin';
        $menus = Menu::with('subMenus')->orderBy('order')->get();

        return view('akun.profil', compact('user', 'layout', 'menus'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
