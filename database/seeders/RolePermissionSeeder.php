<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubMenu;
use App\Models\RolePermission;
use Illuminate\Support\Str;
use App\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $subMenus = SubMenu::all(); 

        foreach ($subMenus as $sub) {
            $title = strtolower(trim($sub->title));

            // ROLE ADMIN (Akses Penuh Semua Modul)
            RolePermission::updateOrCreate(
                ['role' => 'admin', 'sub_menu_id' => $sub->id],
                [
                    'can_view'   => true,
                    'can_create' => true,
                    'can_update' => true,
                    'can_delete' => true,
                ]
            );

            // ROLE PETUGAS
            $isPetugasForbidden = Str::contains($title, [
                'jenis', 'kategori', 'harga', 'satuan', 'pengepul', 'pengaturan', 'izin', 'menu'
            ]) || in_array($title, ['admin', 'petugas', 'kelola admin', 'kelola petugas', 'kelola izin', 'manajemen menu']);
            
            RolePermission::updateOrCreate(
                ['role' => 'petugas', 'sub_menu_id' => $sub->id],
                [
                    'can_view'   => !$isPetugasForbidden,
                    'can_create' => !$isPetugasForbidden,
                    'can_update' => !$isPetugasForbidden,
                    'can_delete' => !$isPetugasForbidden,
                ]
            );

            // ROLE NASABAH
            $canViewNasabah   = false;
            $canCreateNasabah = false;
            $canUpdateNasabah = false;

            if (Str::contains($title, 'dashboard')) {
                $canViewNasabah = true;
            } elseif (Str::contains($title, 'setoran')) {
                // Setoran Sampah: Hanya "Lihat"
                $canViewNasabah = true;
            } elseif (Str::contains($title, 'penarikan')) {
                // Penarikan Saldo: "Ajukan" (Lihat & Tambah pengajuan)
                $canViewNasabah   = true;
                $canCreateNasabah = true;
            } elseif (Str::contains($title, ['saldo', 'riwayat'])) {
                // Cek Saldo & Mutasi Riwayat
                $canViewNasabah = true;
            } elseif (Str::contains($title, 'profil')) {
                // Profil Akun Warga
                $canViewNasabah   = true;
                $canUpdateNasabah = true;
            }

            RolePermission::updateOrCreate(
                ['role' => 'nasabah', 'sub_menu_id' => $sub->id],
                [
                    'can_view'   => $canViewNasabah,
                    'can_create' => $canCreateNasabah,
                    'can_update' => $canUpdateNasabah,
                    'can_delete' => false,
                ]
            );
        }
        
        $roles = [
        ['name' => 'Administrator', 'slug' => 'admin'],
        ['name' => 'Petugas Lapangan', 'slug' => 'petugas'],
        ['name' => 'Nasabah', 'slug' => 'nasabah'],
            ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}