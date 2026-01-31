<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display profile page
     */
    public function index()
    {
        $user = Auth::user();
        $store = $user->store;


        return view('seller.profile.index', compact('user', 'store'));
    }

    /**
     * Update store information (Nama Toko, Pemilik, Logo)
     */
    public function updateStore(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone'      => 'required|string|max:20',
            'store_logo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $store = $user->store;

        // Update data User (Nama Pemilik)
        $user->update(['name' => $request->owner_name]);

        $data = [
            'store_name' => $request->store_name,
            'owner_name' => $request->owner_name, // Optional jika di store ada kolom owner_name juga
            'phone'      => $request->phone,
        ];

        // Handle logo upload
        if ($request->hasFile('store_logo')) {
            // Delete old logo if exists
            if ($store->store_logo) {
                Storage::disk('public')->delete($store->store_logo);
            }

            $data['store_logo'] = $request->file('store_logo')->store('stores', 'public');
        }

        $store->update($data);

        return redirect()->back()->with('success', 'Informasi toko berhasil diperbarui!');
    }

    /**
     * Update store address (INI YANG DIPERBAIKI)
     */
    public function updateAddress(Request $request)
    {
        $request->validate([
            // Validasi Kode Wilayah (Penting untuk API Ongkir)
            'province_code' => 'required',
            'city_code'     => 'required',
            'district_code' => 'required',
            'village_code'  => 'required',

            // Validasi Nama Wilayah (Penting untuk Tampilan)
            'province'      => 'required|string',
            'city'          => 'required|string',
            'district'      => 'required|string',
            'village'       => 'required|string',

            'postal_code'   => 'required|string|max:10',
            'address'       => 'required|string', // Alamat Lengkap (Jalan, No Rumah)
        ]);

        $store = Auth::user()->store;

        $store->update([
            // Simpan Kode (ID dari API RajaOngkir/BinderByte)
            'province_code' => $request->province_code,
            'city_code'     => $request->city_code,
            'district_code' => $request->district_code,
            'village_code'  => $request->village_code,

            // Simpan Nama Wilayah (Text)
            'province'      => $request->province,
            'city'          => $request->city,
            'district'      => $request->district,
            'village'       => $request->village,

            'postal_code'   => $request->postal_code,
            'address'       => $request->address,
        ]);

        return redirect()->back()->with('success', 'Alamat toko berhasil diperbarui!');
    }

    /**
     * Update bank account
     */
    public function updateBankAccount(Request $request)
    {
        $request->validate([
            'bank_name'      => 'required|string',
            'account_number' => 'required|string|max:20',
            'account_holder' => 'required|string|max:255',
        ]);

        $store = Auth::user()->store;

        $store->update([
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
        ]);

        return redirect()->back()->with('success', 'Rekening mitra berhasil diperbarui!');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }
}
