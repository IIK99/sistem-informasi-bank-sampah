<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class SetoranController extends Controller
{
    public function index()
    {
        NotificationService::markCategoryAsReadForUser(Auth::user(), 'setoran');

        $setorans = SetoranSampah::with([
                'petugas', 
                'details.jenisSampah', 
                'details.satuan'
            ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('nasabah.setoran.index', compact('setorans'));
    }
}