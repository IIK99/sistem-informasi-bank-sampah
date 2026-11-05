<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\SubMenu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. MANAJEMEN PENGGUNA (Admin & Petugas)
        $userMgmt = Menu::create([
            'title' => 'Manajemen Pengguna',
            'icon'  => 'bi-people',
            'roles' => ['admin', 'petugas'],
            'order' => 2
        ]);
       SubMenu::create(['menu_id' => $userMgmt->id, 'title' => 'Admin', 'route' => '/admin/kelola-admin', 'roles' => ['admin'], 'order' => 1]);
        SubMenu::create(['menu_id' => $userMgmt->id, 'title' => 'Petugas', 'route' => '/admin/kelola-petugas', 'roles' => ['admin'], 'order' => 2]);
        SubMenu::create(['menu_id' => $userMgmt->id, 'title' => 'Nasabah', 'route' => '/nasabah', 'roles' => ['admin', 'petugas'], 'order' => 3]);
        
        // 2. MASTER DATA (Khusus Admin)
        $master = Menu::create([
            'title' => 'Master Data',
            'icon'  => 'bi-box-seam',
            'roles' => ['admin'],
            'order' => 3
        ]);
        SubMenu::create(['menu_id' => $master->id, 'title' => 'Kategori Sampah', 'route' => '/admin/kategori-sampah', 'roles' => ['admin'], 'order' => 1]);
        SubMenu::create(['menu_id' => $master->id, 'title' => 'Satuan', 'route' => '/admin/satuan', 'roles' => ['admin'], 'order' => 2]);
        SubMenu::create(['menu_id' => $master->id, 'title' => 'Jenis Sampah', 'route' => '/admin/jenis-sampah', 'roles' => ['admin'], 'order' => 3]);
        SubMenu::create(['menu_id' => $master->id, 'title' => 'Pengepul', 'route' => '/admin/pengepul', 'roles' => ['admin'], 'order' => 4]);
        SubMenu::create(['menu_id' => $master->id, 'title' => 'Harga Sampah', 'route' => '/admin/harga-sampah', 'roles' => ['admin'], 'order' => 5]);

        // 3. TRANSAKSI (Admin & Petugas)
        $trx = Menu::create([
            'title' => 'Transaksi',
            'icon'  => 'bi-wallet2',
            'roles' => ['admin', 'petugas'],
            'order' => 4
        ]);
        SubMenu::create(['menu_id' => $trx->id, 'title' => 'Setoran Sampah', 'route' => '/setoran', 'roles' => ['admin', 'petugas'], 'order' => 1]);
        SubMenu::create(['menu_id' => $trx->id, 'title' => 'Penarikan Saldo', 'route' => '/penarikan', 'roles' => ['admin', 'petugas'], 'order' => 2]);

        // 4. KEUANGAN (Admin & Petugas)
        $keuangan = Menu::create([
            'title' => 'Keuangan',
            'icon'  => 'bi-cash-coin',
            'roles' => ['admin', 'petugas'],
            'order' => 5
        ]);
        SubMenu::create(['menu_id' => $keuangan->id, 'title' => 'Saldo', 'route' => '/saldo', 'roles' => ['admin', 'petugas'], 'order' => 1]);
        SubMenu::create(['menu_id' => $keuangan->id, 'title' => 'Riwayat Transaksi', 'route' => '/riwayat-transaksi', 'roles' => ['admin', 'petugas'], 'order' => 2]);

        // 5. LAPORAN (Admin & Petugas)
        $laporan = Menu::create([
            'title' => 'Laporan',
            'icon'  => 'bi-file-earmark-bar-graph',
            'roles' => ['admin', 'petugas'],
            'order' => 6
        ]);
        SubMenu::create(['menu_id' => $laporan->id, 'title' => 'Laporan Setoran', 'route' => '/laporan-setoran', 'roles' => ['admin', 'petugas'], 'order' => 1]);
        SubMenu::create(['menu_id' => $laporan->id, 'title' => 'Laporan Penarikan', 'route' => '/laporan-penarikan', 'roles' => ['admin', 'petugas'], 'order' => 2]);
        SubMenu::create(['menu_id' => $laporan->id, 'title' => 'Laporan Saldo', 'route' => '/laporan-saldo', 'roles' => ['admin', 'petugas'], 'order' => 3]);
        SubMenu::create(['menu_id' => $laporan->id, 'title' => 'Laporan Sampah', 'route' => '/laporan-sampah', 'roles' => ['admin', 'petugas'], 'order' => 4]);

        // 6. PENGATURAN (Khusus Admin)
        $pengaturan = Menu::create([
            'title' => 'Pengaturan',
            'icon'  => 'bi-gear',
            'roles' => ['admin'],
            'order' => 7
        ]);
        SubMenu::create(['menu_id' => $pengaturan->id, 'title' => 'Pengaturan Sistem', 'route' => '/admin/pengaturan', 'roles' => ['admin'], 'order' => 1]);
        SubMenu::create(['menu_id' => $pengaturan->id, 'title' => 'Kelola Izin', 'route' => '/admin/role-permissions', 'roles' => ['admin'], 'order' => 2]);
        SubMenu::create(['menu_id' => $pengaturan->id, 'title' => 'Manajemen Menu', 'route' => '/admin/menus', 'roles' => ['admin'], 'order' => 3]);
        SubMenu::create(['menu_id' => $pengaturan->id, 'title' => 'Kelola Peran',   'route' => '/admin/roles',      'roles' => ['admin'], 'order' => 2]);

        // 7. AKUN (Semua Role)
        $akun = Menu::create([
            'title' => 'Akun',
            'icon'  => 'bi-person-circle',
            'roles' => ['admin', 'petugas', 'nasabah'],
            'order' => 8
        ]);
        SubMenu::create(['menu_id' => $akun->id, 'title' => 'Profil', 'route' => '/profil', 'roles' => ['admin', 'petugas', 'nasabah'], 'order' => 1]);
        SubMenu::create(['menu_id' => $akun->id, 'title' => 'Logout', 'route' => '/logout', 'roles' => ['admin', 'petugas', 'nasabah'], 'order' => 2]);

        // 8. MENU KHUSUS PORTAL NASABAH
        // Saldo Nasabah
        $mSaldo = Menu::create([
            'title' => 'Saldo',
            'icon'  => 'bi-cash-stack',
            'roles' => ['nasabah'],
            'order' => 10,
        ]);
        SubMenu::create(['menu_id' => $mSaldo->id, 'title' => 'Saldo Saya', 'route' => '/saldo', 'roles' => ['nasabah'], 'order' => 1]);

        // Setoran Nasabah
        $mSetoran = Menu::create([
            'title' => 'Setoran',
            'icon'  => 'bi-box-arrow-in-down',
            'roles' => ['nasabah'],
            'order' => 11,
        ]);
        SubMenu::create(['menu_id' => $mSetoran->id, 'title' => 'Riwayat Setoran', 'route' => '/riwayat-setoran', 'roles' => ['nasabah'], 'order' => 1]);

        // Penarikan Nasabah
        $mPenarikan = Menu::create([
            'title' => 'Penarikan',
            'icon'  => 'bi-arrow-down-circle',
            'roles' => ['nasabah'],
            'order' => 12,
        ]);
        SubMenu::create(['menu_id' => $mPenarikan->id, 'title' => 'Riwayat Penarikan', 'route' => '/penarikan', 'roles' => ['nasabah'], 'order' => 1]);

        // Transaksi Nasabah
        $mTrxNasabah = Menu::create([
            'title' => 'Riwayat Transaksi',
            'icon'  => 'bi-receipt',
            'roles' => ['nasabah'],
            'order' => 13,
        ]);
        SubMenu::create(['menu_id' => $mTrxNasabah->id, 'title' => 'Riwayat Transaksi', 'route' => '/riwayat-transaksi', 'roles' => ['nasabah'], 'order' => 1]);

        SubMenu::create([
        'menu_id' => $laporan->id,
        'title'   => 'Laporan Jual ke Pengepul',
        'route'   => '/laporan-jual-pengepul',
        'roles'   => ['admin', 'petugas'],
        'order'   => 5,
        ]);
    }
}