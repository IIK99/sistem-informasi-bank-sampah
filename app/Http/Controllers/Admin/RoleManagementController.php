<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleManagementController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id', 'asc')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $slug = Str::slug($request->name);

        // Pastikan slug juga unique
        if (Role::where('slug', $slug)->exists()) {
            return back()->with('error', 'Role dengan nama tersebut menghasilkan slug yang sudah ada.');
        }

        $role = Role::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        // Buat submenu di Manajemen Pengguna
        $menuPengguna = Menu::where('title', 'Manajemen Pengguna')->first();
        if ($menuPengguna) {
            $sub = SubMenu::create([
                'menu_id' => $menuPengguna->id,
                'title' => $role->name,
                'route' => '/admin/pengguna/' . $role->slug,
                'roles' => ['admin'], // Default hanya admin yang bisa manage
                'order' => SubMenu::where('menu_id', $menuPengguna->id)->count() + 1
            ]);
            
            \App\Models\RolePermission::create([
                'role' => 'admin',
                'sub_menu_id' => $sub->id,
                'can_view' => true,
                'can_create' => true,
                'can_update' => true,
                'can_delete' => true,
            ]);
        }

        return back()->with('success', "Role '{$role->name}' berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        // Jangan biarkan mengubah role default (admin, petugas, nasabah)
        if (in_array($role->slug, ['admin', 'petugas', 'nasabah'])) {
            return back()->with('error', 'Role default sistem tidak dapat diubah.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $oldName = $role->name;
        $role->update([
            'name' => $request->name,
        ]);

        // Update title di SubMenu
        $menuPengguna = Menu::where('title', 'Manajemen Pengguna')->first();
        if ($menuPengguna) {
            $subMenu = SubMenu::where('menu_id', $menuPengguna->id)
                ->where('route', '/admin/pengguna/' . $role->slug)
                ->first();
            if ($subMenu) {
                $subMenu->update(['title' => $role->name]);
            }
        }

        return back()->with('success', "Role berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if (in_array($role->slug, ['admin', 'petugas', 'nasabah'])) {
            return back()->with('error', 'Role default sistem tidak dapat dihapus.');
        }

        // Hapus submenu
        $menuPengguna = Menu::where('title', 'Manajemen Pengguna')->first();
        if ($menuPengguna) {
            SubMenu::where('menu_id', $menuPengguna->id)
                ->where('route', '/admin/pengguna/' . $role->slug)
                ->delete();
        }

        $role->delete();

        return back()->with('success', "Role '{$role->name}' berhasil dihapus.");
    }
}
