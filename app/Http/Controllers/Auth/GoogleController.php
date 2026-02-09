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
        // Debug: Cek apakah callback berhasil
        \Log::info('Google Callback Started');
        
        $googleUser = Socialite::driver('google')->user();
        
        \Log::info('Google User Data:', [
            'id' => $googleUser->id,
            'email' => $googleUser->email,
            'name' => $googleUser->name,
        ]);

        // Cek apakah user sudah ada berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if ($user) {
            \Log::info('Existing user found: ' . $user->id);
            
            // Jika user ada, update google_id jika belum ada
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->id,
                ]);
            }

            Auth::login($user);
        } else {
            \Log::info('Creating new user');
            
            // Jika user belum ada, buat user baru
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => bcrypt(Str::random(16)),
                'role' => 'buyer' 
            ]);

            Auth::login($user);
            
            \Log::info('New user created: ' . $user->id);
        }

        return redirect()->intended('/');

    } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
        \Log::error('Invalid State Exception: ' . $e->getMessage());
        return redirect()->route('login')->with('error', 'Session expired. Please try again.');
        
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        \Log::error('Guzzle Client Exception: ' . $e->getMessage());
        return redirect()->route('login')->with('error', 'Invalid Google credentials. Please check your .env file.');
        
    } catch (\Exception $e) {
        \Log::error('Google Login Error: ' . $e->getMessage());
        \Log::error('Stack Trace: ' . $e->getTraceAsString());
        
        return redirect()->route('login')->with('error', 'Login failed: ' . $e->getMessage());
    }
}

}
