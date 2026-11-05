<?php  
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class MenuManagementController extends Controller
{
    // Daftar Icon Bootstrap (Typo sudah diperbaiki)
    private $icons = [
        'bi-speedometer2'           => 'Dashboard / Speedometer',
        'bi-recycle'                => 'Daur Ulang / Sampah',
        'bi-trash'                  => 'Tempat Sampah',
        'bi-people'                 => 'Pengguna / Warga',
        'bi-person-circle'          => 'Akun / Profil',
        'bi-box-seam'               => 'Master Data / Barang', 
        'bi-tags'                   => 'Kategori / Harga',
        'bi-wallet2'                => 'Dompet / Transaksi',
        'bi-cash-stack'             => 'Uang Tunai / Kas',
        'bi-file-earmark-bar-graph' => 'Laporan / Grafik',
        'bi-gear'                   => 'Pengaturan / Sistem',
        'bi-truck'                  => 'Logistik / Pengangkutan',
        'bi-shield-lock'            => 'Keamanan / Izin',
        'bi-bell'                   => 'Notifikasi / Lonceng',
    ];

   public function index()
    {
        $allMenus = Menu::orderBy('order', 'asc')->get();

        $menus = Menu::with('subMenus')->orderBy('order', 'asc')->paginate(5);

        // Ambil nomor urut terakhir + 1 
        $nextMenuOrder = (Menu::max('order') ?? 0) + 1;
        $nextSubMenuOrder = (SubMenu::max('order') ?? 0) + 1;

        $icons = $this->icons;
        $allRoles = \App\Models\Role::orderBy('id', 'asc')->get();

        return view('admin.menus.index', compact(
            'menus', 
            'allMenus', 
            'nextMenuOrder', 
            'nextSubMenuOrder', 
            'icons',
            'allRoles'
        ));
    }

    public function storeMenu(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'icon'  => 'required|string',
            'roles' => 'required|array',
            'order' => 'required|integer',
        ]);

        // Jika no urut sudah terpakai, geser nomor yang ada ke atas
        $existingMenu = Menu::where('order', $request->order)->first();
        if ($existingMenu) {
            $lastOrder = Menu::max('order') ?? 0;
            $existingMenu->update(['order' => $lastOrder + 1]);
        }

        Menu::create([
            'title' => $request->title,
            'icon'  => $request->icon,
            'roles' => $request->roles, 
            'order' => $request->order,
        ]);

        return redirect()->back()->with('success', 'Menu induk berhasil ditambahkan!');
    }

    public function storeSubMenu(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'title'   => 'required|string|max:100',
            'route'   => 'required|string|max:255',
            'roles'   => 'required|array',
            'order'   => 'required|integer',
        ]);

        // Jika no urut submenu di induk yang sama bentrok, geser ke atas
        $existingSub = SubMenu::where('menu_id', $request->menu_id)
            ->where('order', $request->order)
            ->first();

        if ($existingSub) {
            $lastSubOrder = SubMenu::where('menu_id', $request->menu_id)->max('order') ?? 0;
            $existingSub->update(['order' => $lastSubOrder + 1]);
        }

        $subMenu = SubMenu::create([
            'menu_id' => $request->menu_id,
            'title'   => $request->title,
            'route'   => $request->route,
            'roles'   => $request->roles, 
            'order'   => $request->order,
        ]);

        // Sinkronisasi otomatis ke role permissions (Smart Sync)
        foreach (['admin', 'petugas', 'nasabah'] as $role) {
            RolePermission::firstOrCreate(
                ['role' => $role, 'sub_menu_id' => $subMenu->id],
                [
                    'can_view'   => in_array($role, $request->roles), 
                    'can_create' => false, 
                    'can_update' => false, 
                    'can_delete' => false
                ]
            );
        }

        return redirect()->back()->with('success', 'Submenu berhasil ditambahkan!');
    }

    public function updateMenu(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'icon'  => 'required|string|max:50',
            'roles' => 'required|array',
            'order' => 'required|integer',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update($validated);

        return redirect()->back()->with('success', 'Menu utama berhasil diperbarui!');
    }

    public function updateSubMenu(Request $request, $id)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'title'   => 'required|string|max:100',
            'route'   => 'required|string|max:255',
            'roles'   => 'required|array',
            'order'   => 'required|integer',
        ]);

        $subMenu = SubMenu::findOrFail($id);
        $subMenu->update($validated);

        return redirect()->back()->with('success', 'Submenu berhasil diperbarui!');
    }

    public function destroyMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $deletedOrder = $menu->order;
        $menu->delete();

        // Rapatkan nomor urut, semua menu di atasnya turun 1 nomor
        Menu::where('order', '>', $deletedOrder)->decrement('order');

        return redirect()->back()->with('success', 'Menu induk berhasil dihapus!');
    }

    public function destroySubMenu($id)
    {
        $subMenu = SubMenu::findOrFail($id);

        // Simpan menu_id dan urutan lamanya sebelum dihapus
        $menuId = $subMenu->menu_id;
        $deletedOrder = $subMenu->order;

        // Hapus data submenu
        $subMenu->delete();

        // Rapatkan nomor urut khusus untuk saudara di induk yang sama
        SubMenu::where('menu_id', $menuId)
            ->where('order', '>', $deletedOrder)
            ->decrement('order');

        return redirect()->back()->with('success', 'Submenu berhasil dihapus!');
    }
}