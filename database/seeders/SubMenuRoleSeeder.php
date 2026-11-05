<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\SubMenu;

class SubMenuRoleSeeder extends Seeder
{
    public function run(): void
    {
        $menuPengaturan = Menu::where('title', 'Pengaturan')->first();
        if ($menuPengaturan && !SubMenu::where('title', 'Manajemen Peran')->exists()) {
            $sub = SubMenu::create([
                'menu_id' => $menuPengaturan->id,
                'title' => 'Manajemen Peran',
                'route' => '/admin/roles',
                'roles' => ['admin'],
                'order' => SubMenu::where('menu_id', $menuPengaturan->id)->count() + 1
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
    }
}
