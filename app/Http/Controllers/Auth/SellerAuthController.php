<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SellerAuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.seller.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Cek apakah role adalah seller atau admin
            if (!in_array($user->role, ['seller', 'admin'])) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Akun ini tidak memiliki akses ke dashboard penjual.',
                ]);
            }

            $request->session()->regenerate();

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }
            
            return redirect()->intended(route('seller.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Tampilkan halaman register
     */
    public function showRegisterForm()
    {
        return view('auth.seller.register');
    }

    /**
     * Proses register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'store_name.required' => 'Nama toko wajib diisi',
            'owner_name.required' => 'Nama pemilik wajib diisi',
            'phone.required' => 'Nomor telepon wajib diisi',
            'province.required' => 'Provinsi wajib dipilih',
            'city.required' => 'Kota/Kabupaten wajib dipilih',
            'district.required' => 'Kecamatan wajib dipilih',
            'postal_code.required' => 'Kode pos wajib diisi',
            'address.required' => 'Alamat lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            DB::beginTransaction();

            // Buat user penjual
            $user = User::create([
                'name' => $validated['owner_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'seller',
                'phone' => $validated['phone'],
            ]);

            // Buat toko
            Store::create([
                'user_id' => $user->id,
                'store_name' => $validated['store_name'],
                'province' => $validated['province'],
                'city' => $validated['city'],
                'district' => $validated['district'],
                'postal_code' => $validated['postal_code'],
                'address' => $validated['address'],
            ]);

            DB::commit();

            // Login otomatis
            Auth::login($user);

            return redirect()->route('seller.dashboard')
                ->with('success', 'Registrasi berhasil! Selamat datang di NusaJual.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.',
            ])->withInput();
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('seller.login')
            ->with('success', 'Berhasil logout.');
    }
}
