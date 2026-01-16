<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display profile page
     */
    public function index()
    {
        $user = auth()->user();
        $store = $user->store;
        
        return view('seller.profile.index', compact('user', 'store'));
    }
    
    /**
     * Update store information
     */
    public function updateStore(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'store_logo' => 'nullable|image|max:2048',
        ]);
        
        $store = auth()->user()->store;
        
        $data = [
            'store_name' => $request->store_name,
            'owner_name' => $request->owner_name,
            'phone' => $request->phone,
        ];
        
        // Handle logo upload
        if ($request->hasFile('store_logo')) {
            // Delete old logo
            if ($store->store_logo) {
                Storage::disk('public')->delete($store->store_logo);
            }
            
            $data['store_logo'] = $request->file('store_logo')->store('stores', 'public');
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
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'required|string|max:5',
            'full_address' => 'required|string',
        ]);
        
        $store = auth()->user()->store;
        
        $store->update([
            'province' => $request->province,
            'city' => $request->city,
            'district' => $request->district,
            'postal_code' => $request->postal_code,
            'address' => $request->full_address,
        ]);
        
        return redirect()->back()->with('success', 'Alamat toko berhasil diperbarui!');
    }
    
    /**
     * Update bank account
     */
    public function updateBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'account_number' => 'required|string|max:20',
            'account_holder' => 'required|string|max:255',
        ]);
        
        $store = auth()->user()->store;
        
        $store->update([
            'bank_name' => $request->bank_name,
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
        
        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }
}
