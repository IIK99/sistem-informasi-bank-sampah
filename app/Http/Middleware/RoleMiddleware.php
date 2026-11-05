<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Tolak akses jika role user tidak terdaftar pada rute
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
        abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk halaman ini.');
    }

        return $next($request);
    }
}