<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use App\Http\Controllers\AuthController;
use App\Models\Menu;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Admin\MenuManagementController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\SatuanController;
use App\Http\Controllers\Admin\KategoriSampahController;
use App\Http\Controllers\Admin\JenisSampahController;
use App\Http\Controllers\Admin\PengepulController;
use App\Http\Controllers\Admin\HargaSampahController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\PetugasManagementController;
use App\Http\Controllers\Admin\HargaPengepulController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\Admin\JualPengepulController;
use App\Http\Controllers\Admin\BroadcastController;

Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

// Rute Tamu (Autentikasi)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute Terproteksi Login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Utama (Semua Role)
    Route::get('/dashboard', function (Request $request) {
        $user = $request->user();

        // Jika Admin atau Petugas
        if (in_array($user->role, ['admin', 'petugas'])) {
            $namaBulan = [1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'Mei', 6=>'Jun', 7=>'Jul', 8=>'Agu', 9=>'Sep', 10=>'Okt', 11=>'Nov', 12=>'Des'];
            $chartLabels = [];
            $chartMasuk = [];
            $chartKeluar = [];

            for ($i = 5; $i >= 0; $i--) {
                $targetDate = now()->subMonths($i);
                $year = $targetDate->year;
                $month = $targetDate->month;
                $chartLabels[] = $namaBulan[$month] . ' ' . $year;

                // Saldo Masuk: Total Jual ke Pengepul
                $masuk = \App\Models\PenjualanPengepul::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->sum('total_harga');

                // Saldo Keluar: Petugas Setor ke Nasabah (Setoran Sampah)
                $keluar = \App\Models\SetoranSampah::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->sum('total_harga');

                $chartMasuk[] = (float) $masuk;
                $chartKeluar[] = (float) $keluar;
            }

            // Total Akumulasi Keseluruhan
            $totalSaldoMasukSemua = (float) \App\Models\PenjualanPengepul::sum('total_harga');
            $totalSaldoKeluarSemua = (float) \App\Models\SetoranSampah::sum('total_harga');
            $saldoKasSekarang = $totalSaldoMasukSemua - $totalSaldoKeluarSemua;

            $viewName = $user->role === 'petugas' ? 'petugas.dashboard' : 'admin.dashboard';

            return view($viewName, compact(
                'user',
                'chartLabels',
                'chartMasuk',
                'chartKeluar',
                'totalSaldoMasukSemua',
                'totalSaldoKeluarSemua',
                'saldoKasSekarang'
            ));
        }

        // Jika Nasabah atau Role Dinamis lainnya (seperti Pengepul dll)
        return view('nasabah.dashboard', compact('user'));
    })->name('dashboard');

    // Proteksi Rute Khusus Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/master-data', fn() => 'Halaman Master Data (Khusus Admin)')->name('master');

        Route::get('/role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
        Route::post('/role-permissions', [RolePermissionController::class, 'update'])->name('role-permissions.update');

        Route::get('/menus', [MenuManagementController::class, 'index'])->name('menus.index');
        Route::post('/menus/menu', [MenuManagementController::class, 'storeMenu'])->name('menus.storeMenu');
        Route::post('/menus/submenu', [MenuManagementController::class, 'storeSubMenu'])->name('menus.storeSubMenu');
        Route::put('/menus/menu/{id}', [MenuManagementController::class, 'updateMenu'])->name('menus.updateMenu');
        Route::put('/menus/submenu/{id}', [MenuManagementController::class, 'updateSubMenu'])->name('menus.updateSubMenu');
        Route::delete('/menus/menu/{id}', [MenuManagementController::class, 'destroyMenu'])->name('menus.destroyMenu');
        Route::delete('/menus/submenu/{id}', [MenuManagementController::class, 'destroySubMenu'])->name('menus.destroySubMenu');

        Route::view('/pengaturan', 'admin.pengaturan.index')->name('pengaturan.index');

            // MASTER DATA (CRUD Lengkap)
        Route::resource('satuan', SatuanController::class);
        Route::resource('kategori-sampah', KategoriSampahController::class);
        Route::resource('jenis-sampah', JenisSampahController::class);
        Route::resource('pengepul', PengepulController::class);
        Route::resource('harga-sampah', HargaSampahController::class);
        

        // MANAJEMEN PENGGUNA (CRUD Admin & Petugas)
        Route::resource('kelola-admin', AdminManagementController::class);
        Route::resource('kelola-petugas', PetugasManagementController::class);

        // MANAJEMEN PERAN & PENGGUNA GENERIK
        Route::resource('roles', \App\Http\Controllers\Admin\RoleManagementController::class)->except('show');
        Route::get('pengguna/{slug}', [\App\Http\Controllers\Admin\UserRoleManagementController::class, 'index'])->name('kelola-pengguna.index');
        Route::get('pengguna/{slug}/create', [\App\Http\Controllers\Admin\UserRoleManagementController::class, 'create'])->name('kelola-pengguna.create');
        Route::post('pengguna/{slug}', [\App\Http\Controllers\Admin\UserRoleManagementController::class, 'store'])->name('kelola-pengguna.store');
        Route::get('pengguna/{slug}/{id}/edit', [\App\Http\Controllers\Admin\UserRoleManagementController::class, 'edit'])->name('kelola-pengguna.edit');
        Route::put('pengguna/{slug}/{id}', [\App\Http\Controllers\Admin\UserRoleManagementController::class, 'update'])->name('kelola-pengguna.update');
        Route::delete('pengguna/{slug}/{id}', [\App\Http\Controllers\Admin\UserRoleManagementController::class, 'destroy'])->name('kelola-pengguna.destroy');

        Route::redirect('/users', '/admin/kelola-admin');
        Route::redirect('/petugas', '/admin/kelola-petugas');

        // Rute kelola harga sampah pengepul
    Route::get('pengepul/{pengepul}/harga', [HargaPengepulController::class, 'index'])->name('pengepul.harga.index');
    Route::post('pengepul/{pengepul}/harga', [HargaPengepulController::class, 'store'])->name('pengepul.harga.store');
    Route::delete('pengepul/{pengepul}/harga/{hargaPengepul}', [HargaPengepulController::class, 'destroy'])->name('pengepul.harga.destroy');
    }); 

    // MODUL SALDO (URL Bersama)
    Route::middleware(['perm:Saldo'])->group(function () {
        Route::get('/saldo', function () {
            if (Auth::user()->role === 'nasabah') {
                return app(\App\Http\Controllers\Nasabah\SaldoController::class)->index();
            }
            return app(\App\Http\Controllers\Petugas\SaldoController::class)->index();
        })->name('saldo.index');
    });

    // MODUL SETORAN SAMPAH
    
    // 1. Admin/Petugas (Sesuai Seeder: "Setoran Sampah")
    Route::middleware(['perm:Setoran Sampah'])->group(function () {
        Route::get('/setoran', [\App\Http\Controllers\Petugas\SetoranController::class, 'index'])->name('petugas.setoran.index');
        Route::get('/setoran/create', [\App\Http\Controllers\Petugas\SetoranController::class, 'create'])->name('petugas.setoran.create');
        Route::post('/setoran', [\App\Http\Controllers\Petugas\SetoranController::class, 'store'])->name('petugas.setoran.store');
    });

    Route::get('/jual-pengepul', [JualPengepulController::class, 'index'])->name('jual-pengepul.index');
    Route::get('/jual-pengepul/create', [JualPengepulController::class, 'create'])->name('jual-pengepul.create');
    Route::post('/jual-pengepul', [JualPengepulController::class, 'store'])->name('jual-pengepul.store');

    // Endpoint AJAX / Fetch data harga sampah milik pengepul
    Route::get('/jual-pengepul/get-sampah/{pengepul}', [JualPengepulController::class, 'getSampahByPengepul'])->name('jual-pengepul.get-sampah');

    // 2. Nasabah (Sesuai Seeder: "Riwayat Setoran")
    Route::middleware(['perm:Riwayat Setoran'])->group(function () {
        Route::get('/riwayat-setoran', [\App\Http\Controllers\Nasabah\SetoranController::class, 'index'])->name('nasabah.setoran.index');
    });

    // MODUL PENARIKAN SALDO
    
    // URL Bersama & Aksi Admin, Petugas, serta Nasabah (Sesuai Seeder: "Penarikan Saldo")
    Route::middleware(['perm:Penarikan Saldo'])->group(function () {
        Route::get('/penarikan', function () {
            if (Auth::user()->role === 'nasabah') {
                return app(\App\Http\Controllers\Nasabah\PenarikanController::class)->index();
            }
            return app(\App\Http\Controllers\Petugas\PenarikanController::class)->index();
        })->name('nasabah.penarikan.index');

        Route::patch('/penarikan/{id}/setujui', [\App\Http\Controllers\Petugas\PenarikanController::class, 'setujui'])->name('petugas.penarikan.setujui');
        Route::patch('/penarikan/{id}/tolak', [\App\Http\Controllers\Petugas\PenarikanController::class, 'tolak'])->name('petugas.penarikan.tolak');
        Route::get('/penarikan/{id}/cetak-pdf', [\App\Http\Controllers\Petugas\PenarikanController::class, 'cetakKwitansiPdf'])->name('petugas.penarikan.cetak-pdf');
        Route::post('/penarikan', [\App\Http\Controllers\Nasabah\PenarikanController::class, 'store'])->name('nasabah.penarikan.store');
    });

    // Fallback URL jika ada yang mengakses /penarikan/create
    Route::get('/penarikan/create', function () {
        return redirect()->route('nasabah.penarikan.index');
    })->name('nasabah.penarikan.create');

    // MODUL RIWAYAT TRANSAKSI (URL Bersama)
    Route::middleware(['perm:Riwayat Transaksi'])->group(function () {
        Route::get('/riwayat-transaksi', function (\Illuminate\Http\Request $request) {
            if (Auth::user()->role === 'nasabah') {
                return app(\App\Http\Controllers\Nasabah\TransaksiController::class)->index();
            }
            return app(\App\Http\Controllers\Petugas\RiwayatController::class)->index($request);
        })->name('riwayat.transaksi');
    });

    // MODUL MANAJEMEN PENGGUNA: KELOLA NASABAH
    // Sesuai Seeder: "Nasabah"
    Route::middleware(['perm:Nasabah'])->group(function () {
        Route::resource('nasabah', \App\Http\Controllers\NasabahManagementController::class);
    });

    // MODUL LAPORAN (Admin & Petugas)
    Route::middleware(['perm:Laporan Setoran'])->group(function () {
        Route::get('/laporan-setoran', [\App\Http\Controllers\LaporanController::class, 'setoran'])->name('laporan.setoran');
        Route::get('/laporan-setoran/pdf', [\App\Http\Controllers\LaporanController::class, 'cetakSetoranPdf'])->name('laporan.setoran.pdf');
    });

    Route::middleware(['perm:Laporan Penarikan'])->group(function () {
        Route::get('/laporan-penarikan', [\App\Http\Controllers\LaporanController::class, 'penarikan'])->name('laporan.penarikan');
        Route::get('/laporan-penarikan/pdf', [\App\Http\Controllers\LaporanController::class, 'cetakPenarikanPdf'])->name('laporan.penarikan.pdf');
    });

    Route::middleware(['perm:Laporan Saldo'])->group(function () {
        Route::get('/laporan-saldo', [\App\Http\Controllers\LaporanController::class, 'saldo'])->name('laporan.saldo');
        Route::get('/laporan-saldo/pdf', [\App\Http\Controllers\LaporanController::class, 'cetakSaldoPdf'])->name('laporan.saldo.pdf');
    });

    Route::middleware(['perm:Laporan Sampah'])->group(function () {
        Route::get('/laporan-sampah', [\App\Http\Controllers\LaporanController::class, 'sampah'])->name('laporan.sampah');
        Route::get('/laporan-sampah/pdf', [\App\Http\Controllers\LaporanController::class, 'cetakSampahPdf'])->name('laporan.sampah.pdf');
    });

    Route::middleware(['perm:Laporan Jual ke Pengepul'])->group(function () {
        Route::get('/laporan-jual-pengepul', [\App\Http\Controllers\LaporanController::class, 'jualPengepul'])->name('laporan.jual-pengepul');
        Route::get('/laporan-jual-pengepul/pdf', [\App\Http\Controllers\LaporanController::class, 'cetakJualPengepulPdf'])->name('laporan.jual-pengepul.pdf');
    });

    // Rute Profil Akun
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Rute Notifikasi
    Route::get('/notifikasi/{id}/baca', [\App\Http\Controllers\NotificationController::class, 'readAndRedirect'])->name('notifikasi.read');
    Route::post('/notifikasi/tandai-semua', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifikasi.markAllRead');
    Route::post('/notifikasi/baca-kategori', [\App\Http\Controllers\NotificationController::class, 'markCategoryRead'])->name('notifikasi.markCategoryRead');

    Route::get('/pengaturan/broadcast', [BroadcastController::class, 'index'])->name('admin.broadcast.index');
    Route::post('/pengaturan/broadcast', [BroadcastController::class, 'send'])->name('admin.broadcast.send');

});