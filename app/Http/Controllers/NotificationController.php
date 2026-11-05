<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tandai satu notifikasi sebagai terbaca dan arahkan ke link tujuan
     */
    public function readAndRedirect($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            $data = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);
            $targetUrl = $data['url'] ?? '/dashboard';

            // Jika berupa absolute URL (misal http://localhost/penarikan), ambil path-nya saja
            // agar redirect tetap berada di port & host browser user saat ini (127.0.0.1:8000, ngrok, dll)
            if (str_starts_with($targetUrl, 'http://') || str_starts_with($targetUrl, 'https://')) {
                $parsed = parse_url($targetUrl);
                $targetUrl = $parsed['path'] ?? '/dashboard';
                if (!empty($parsed['query'])) {
                    $targetUrl .= '?' . $parsed['query'];
                }
            }

            return redirect($targetUrl);
        }

        return redirect('/dashboard');
    }

    /**
     * Tandai semua notifikasi suatu kategori tertentu sebagai terbaca (realtime via klik sidebar)
     */
    public function markCategoryRead(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $category = $request->input('category');
        NotificationService::markCategoryAsReadForUser($user, $category);

        return response()->json([
            'success'   => true,
            'unread'    => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Tandai semua notifikasi pengguna sebagai terbaca
     */
    public function markAllRead(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
