<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\BankAccount;
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
        
        // Ambil bank account pertama (atau semua jika multiple)
        $bankAccount = $store->bankAccounts()->first();

        return view('seller.profile.index', compact('user', 'store', 'bankAccount'));
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
            'owner_name' => $request->owner_name,
            'phone'      => $request->phone,
        ];

        // Handle logo upload
        if ($request->hasFile('store_logo')) {
            // Delete old logo if exists
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }

            $data['logo'] = $request->file('store_logo')->store('stores/logos', 'public');
        }

        $store->update($data);

        return redirect()->back()->with('success', 'Informasi toko berhasil diperbarui!');
    }

    /**
     * Update store address
     */
    public function updateAddress(Request $request)
    {
        $request->validate([
            'province_code' => 'required',
            'city_code'     => 'required',
            'district_code' => 'required',
            'village_code'  => 'required',
            'province'      => 'required|string',
            'city'          => 'required|string',
            'district'      => 'required|string',
            'village'       => 'required|string',
            'postal_code'   => 'required|string|max:10',
            'address'       => 'required|string',
        ]);

        $store = Auth::user()->store;

        $store->update([
            'province_code' => $request->province_code,
            'city_code'     => $request->city_code,
            'district_code' => $request->district_code,
            'village_code'  => $request->village_code,
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
     * Update bank account (FIX: Simpan ke bank_accounts table)
     */
    public function updateBankAccount(Request $request)
    {
        $request->validate([
            'bank_name'      => 'required|string',
            'account_number' => 'required|string|max:20',
            'account_holder' => 'required|string|max:255',
        ]);

        $store = Auth::user()->store;

        // Cek apakah sudah ada bank account
        $bankAccount = $store->bankAccounts()->first();

        if ($bankAccount) {
            // Update existing
            $bankAccount->update([
                'bank_name'      => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name'   => $request->account_holder, // Field di DB adalah account_name
            ]);
        } else {
            // Create new
            $store->bankAccounts()->create([
                'bank_name'      => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name'   => $request->account_holder,
            ]);
        }

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
