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
        // 1. Validasi Input
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone'      => 'required|string|max:20',

            // Validasi Kode (Dikirim dari value option dropdown)
            'province_code' => 'required',
            'city_code'     => 'required',
            'district_code' => 'required',
            'village_code'  => 'required', // PENTING BANGET

            // Validasi Nama (Dikirim dari input hidden)
            'province' => 'required',
            'city'     => 'required',
            'district' => 'required',
            'village'  => 'required',

            'postal_code' => 'required|string|max:10',
            'address'     => 'required|string',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:8|confirmed',
        ], [
            // Custom Messages
            'store_name.required' => 'Nama toko wajib diisi',
            'province_code.required' => 'Provinsi wajib dipilih',
            'city_code.required' => 'Kota/Kabupaten wajib dipilih',
            'district_code.required' => 'Kecamatan wajib dipilih',
            'village_code.required' => 'Kelurahan wajib dipilih',
            'email.unique' => 'Email sudah terdaftar',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            DB::beginTransaction();

            // 2. Buat User Penjual
            $user = User::create([
                'name'     => $validated['owner_name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'seller',
                'phone'    => $validated['phone'],
            ]);

            // 3. Buat Slug Toko (biar URL-nya cantik, misal: nusa-belanja-123)
            $slug = \Illuminate\Support\Str::slug($validated['store_name']) . '-' . rand(100, 999);

            // 4. Buat Toko
            Store::create([
                'user_id'       => $user->id,
                'store_name'    => $validated['store_name'],
                'slug'          => $slug,
                'description'   => 'Toko resmi ' . $validated['store_name'], // <--- INI YG DITAMBAHKAN

                // Simpan Kode Wilayah (Penting untuk Ongkir)
                'province_code' => $validated['province_code'],
                'city_code'     => $validated['city_code'],
                'district_code' => $validated['district_code'],
                'village_code'  => $validated['village_code'],

                // Simpan Nama Wilayah (Untuk Tampilan)
                'province'      => $validated['province'],
                'city'          => $validated['city'],
                'district'      => $validated['district'],
                'village'       => $validated['village'],

                'postal_code'   => $validated['postal_code'],
                'address'       => $validated['address'],
            ]);

            DB::commit();

            return redirect()->route('seller.login')
                ->with('success', 'Registrasi berhasil! Silakan login untuk mengelola toko.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Uncomment baris ini jika ingin melihat detail error di layar saat testing
            // dd($e->getMessage()); 

            return back()->withErrors([
                'error' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
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
