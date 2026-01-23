<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    // 1. Redirect user ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Handle callback dari Google setelah user login
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah ada berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Jika user ada, update google_id jika belum ada (misal login email sebelumnya)
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                    ]);
                }

                Auth::login($user);
            } else {
                // Jika user belum ada, buat user baru
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)), // Password random
                    'role' => 'buyer' 
                ]);

                Auth::login($user);
            }

            return redirect()->intended('/'); // Redirect ke home atau halaman yang dituju sebelumnya

        } catch (\Exception $e) {
            // return redirect()->route('login')->with('error', 'Login Google Gagal: ' . $e->getMessage());

            dd($e->getMessage());
        }
    }
}
