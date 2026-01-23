<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureBuyer
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek 1: Apakah user sudah login?
        // Cek 2: Apakah role-nya 'buyer'? (Sesuaikan dengan data di database kamu)
        if (Auth::check() && Auth::user()->role === 'buyer') {
            return $next($request);
        }

        // Jika bukan buyer (misal seller/admin coba akses), tolak akses
        abort(403, 'Unauthorized access. Customer only.');
    }
}
