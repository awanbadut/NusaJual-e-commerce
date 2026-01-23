<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerAuthController extends Controller
{
    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        // 1. Logout user dari session Auth default (web)
        Auth::logout();

        // 2. Invalidate session saat ini (Hapus data session)
        $request->session()->invalidate();

        // 3. Regenerate CSRF token (Keamanan)
        $request->session()->regenerateToken();

        // 4. Redirect ke halaman utama atau login
        return redirect()->route('home'); // Pastikan route 'home' ada
    }
}
