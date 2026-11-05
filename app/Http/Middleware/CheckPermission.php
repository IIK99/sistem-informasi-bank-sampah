<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubMenu;
use App\Models\RolePermission;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $moduleTitle): Response
    {
        $user = Auth::user();

        // 1. Pengaman jika user belum login
        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        // 2. Kunci Mati: Modul portal personal Nasabah DILARANG diakses Admin & Petugas
        $modulPersonalNasabah = [
            'Saldo Saya',
            'Ajukan Penarikan',
            'Riwayat Setoran'
        ];

        if (in_array($moduleTitle, $modulPersonalNasabah) && $user->role !== 'nasabah') {
            abort(403, 'Akses ditolak: Anda tidak memiliki akses ke portal personal nasabah.');
        }

        // 3. Admin lolos akses penuh untuk modul manajemen, master data, dan pengaturan sistem
        if ($user->role === 'admin') {
            return $next($request);
        }

        // 3. Deteksi aksi CRUD berdasarkan method controller
        $action = match ($request->route()->getActionMethod()) {
            'create', 'store'   => 'can_create',
            'edit', 'update'    => 'can_update',
            'destroy'           => 'can_delete',
            default             => 'can_view',
        };

        // 4. Cari sub menu berdasarkan nama modul
        $subMenu = SubMenu::where('title', $moduleTitle)->first();
        if (!$subMenu) {
            abort(403, 'Modul tidak ditemukan.');
        }

        // 5. Cek izin di database untuk Petugas dan Nasabah
        $permission = RolePermission::where('role', $user->role)
            ->where('sub_menu_id', $subMenu->id)
            ->first();

        // 6. Tolak kalau izin belum diset atau statusnya false
        if (!$permission || !$permission->$action) {
            abort(403, 'Akses ditolak: Izin aksi ini dinonaktifkan.');
        }

        return $next($request);
    }
}