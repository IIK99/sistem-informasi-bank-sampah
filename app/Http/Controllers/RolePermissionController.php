<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\RolePermission;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar role dari tabel roles
        $rolesList = \App\Models\Role::orderBy('id', 'asc')->get();
        $roles = [];
        foreach ($rolesList as $r) {
            $roles[$r->slug] = $r->name;
        }

        $selectedRole = $request->query('role', 'admin');
        if (!array_key_exists($selectedRole, $roles)) {
            $selectedRole = 'admin';
        }

        // Ambil data menu dan submenu yang sudah ada
        // kecuali Dashboard
        $menus = Menu::where('title', '!=', 'Dashboard')->orderBy('order')->get();
        $subMenus = SubMenu::orderBy('order')->get()->groupBy('menu_id');

        //  Ambil data izin yang tersimpan untuk role yang dipilih
        $permissions = RolePermission::where('role', $selectedRole)
            ->get()
            ->keyBy('sub_menu_id');

        return view('admin.role_permission', compact('roles', 'selectedRole', 'menus', 'subMenus', 'permissions'));
    }

    public function update(Request $request)
    {
        $role = $request->input('role');
        $matrix = $request->input('permissions', []);

        // Ambil semua submenu untuk validasi update
        $allSubMenus = SubMenu::all();

        foreach ($allSubMenus as $sub) {
            $subId = $sub->id;
            $canView   = isset($matrix[$subId]['view']);
            $canCreate = isset($matrix[$subId]['create']);
            $canUpdate = isset($matrix[$subId]['update']);
            $canDelete = isset($matrix[$subId]['delete']);

            RolePermission::updateOrCreate(
                [
                    'role' => $role,
                    'sub_menu_id' => $subId,
                ],
                [
                    'can_view'   => $canView,
                    'can_create' => $canCreate,
                    'can_update' => $canUpdate,
                    'can_delete' => $canDelete,
                ]
            );
        }

        return redirect()
            ->route('admin.role-permissions.index', ['role' => $role])
            ->with('success', "Hak akses untuk role {$role} berhasil diperbarui!");
    }
}