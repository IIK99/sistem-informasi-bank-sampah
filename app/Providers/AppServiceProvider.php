<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\RolePermission;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Custom directive untuk cek aksi (create, read, update, delete)
        Blade::if('canAction', function ($moduleTitle, $action) {
            $user = Auth::user();
            if (!$user) return false;

            $column = 'can_' . $action;
            $subMenu = SubMenu::where('title', $moduleTitle)->first();

            if (!$subMenu) return false;

            // Cek murni berdasarkan data toggle di database untuk role yang sedang login
            $permission = RolePermission::where('role', $user->role)
                ->where('sub_menu_id', $subMenu->id)
                ->first();

            return (bool) ($permission->$column ?? false);
        });

        // Otomatis kirim $menus ke semua layout (admin dan nasabah)
        View::composer(['layouts.nasabah', 'layouts.admin'], function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // 1. Ambil semua menu dan submenu beserta urutannya, hiraukan JSON roles
                $allMenus = Menu::with(['subMenus' => function ($query) {
                    $query->orderBy('order', 'asc');
                }])->orderBy('order', 'asc')->get();

                // 2. Filter menu menggunakan Collection berdasarkan Role & RolePermission
                $menus = $allMenus->filter(function ($menu) use ($user) {
                    // Cek jika menu induk memiliki batasan role tertentu
                    if (!empty($menu->roles) && is_array($menu->roles) && !in_array($user->role, $menu->roles)) {
                        return false;
                    }
                    
                    // Saring submenu: Hanya sisakan yang can_view = true di tabel role_permissions
                    $filteredSubMenus = $menu->subMenus->filter(function ($subMenu) use ($user) {
                        $permission = RolePermission::where('role', $user->role)
                            ->where('sub_menu_id', $subMenu->id)
                            ->first();
                            
                        return $permission && $permission->can_view;
                    });

                    // Timpa relasi subMenus bawaan dengan yang sudah difilter
                    $menu->setRelation('subMenus', $filteredSubMenus);

                    // 3. Tampilkan Menu Induk JIKA masih ada minimal 1 submenu di dalamnya
                    // ATAU jika nama menunya adalah "Dashboard" (karena dashboard biasanya tidak punya submenu)
                    return $menu->subMenus->isNotEmpty() || strtolower($menu->title) === 'dashboard';
                });

                // 4. Ambil data notifikasi untuk navbar lonceng & badge sidebar
                $userNotifications = $user->notifications()->take(10)->get();
                $unreadNotificationsCount = $user->unreadNotifications()->count();

                $unreadItems = $user->unreadNotifications()->get();
                $sidebarBadges = [
                    'penarikan' => 0,
                    'setoran' => 0,
                    'jual_pengepul' => 0,
                ];

                foreach ($unreadItems as $notif) {
                    $d = is_array($notif->data) ? $notif->data : json_decode($notif->data, true);
                    $target = $d['target_menu'] ?? '';
                    if ($target === 'penarikan') {
                        $sidebarBadges['penarikan']++;
                    } elseif ($target === 'setoran') {
                        $sidebarBadges['setoran']++;
                    } elseif ($target === 'jual-pengepul') {
                        $sidebarBadges['jual_pengepul']++;
                    }
                }

                $view->with([
                    'menus'                    => $menus,
                    'userNotifications'        => $userNotifications,
                    'unreadNotificationsCount' => $unreadNotificationsCount,
                    'sidebarBadges'            => $sidebarBadges,
                ]);
            } else {
                $view->with([
                    'menus'                    => collect(),
                    'userNotifications'        => collect(),
                    'unreadNotificationsCount' => 0,
                    'sidebarBadges'            => ['penarikan' => 0, 'setoran' => 0, 'jual_pengepul' => 0],
                ]);
            }
        });

        if (!$this->app->runningInConsole()) {
            if (request()->server('HTTP_X_FORWARDED_PROTO') === 'https' 
                || request()->header('X-Forwarded-Proto') === 'https'
                || str_contains(request()->header('host', ''), 'ngrok')) {
                URL::forceScheme('https');
            }
        }
    }
}