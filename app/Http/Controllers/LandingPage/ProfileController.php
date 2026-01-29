<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Address;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profileBuyer.index');
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            // Validasi tanggal lahir manual dari 3 dropdown
            'dob_day' => 'nullable|numeric',
            'dob_month' => 'nullable|numeric',
            'dob_year' => 'nullable|numeric',
        ]);

        // Gabungkan tanggal lahir: YYYY-MM-DD
        $dob = null;
        if ($request->dob_year && $request->dob_month && $request->dob_day) {
            $dob = $request->dob_year . '-' . $request->dob_month . '-' . $request->dob_day;
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'date_of_birth' => $dob,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function address()
    {
        // Ambil data asli dari database
        $addresses = Address::where('user_id', Auth::id())
            ->orderBy('is_primary', 'desc')
            ->get();

        return view('profileBuyer.address', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required',
            'phone' => 'required',
            'province_code' => 'required',
            'city_code' => 'required',
            'district_code' => 'required',
            'village_code' => 'required', // Ini kunci utama ongkir
            'detail_address' => 'required',
        ]);

        if ($request->has('is_primary')) {
            Address::where('user_id', Auth::id())->update(['is_primary' => false]);
        }

        $isPrimary = $request->has('is_primary') || Address::where('user_id', Auth::id())->count() == 0;

        Address::create([
            'user_id' => Auth::id(),
            'receiver_name' => $request->receiver_name,
            'phone' => $request->phone,

            // Simpan Kode dan Nama (dikirim dari hidden input)
            'province_code' => $request->province_code,
            'province_name' => $request->province_name,
            'city_code' => $request->city_code,
            'city_name' => $request->city_name,
            'district_code' => $request->district_code,
            'district_name' => $request->district_name,
            'village_code' => $request->village_code,
            'village_name' => $request->village_name,
            'postal_code' => $request->postal_code,

            'detail_address' => $request->detail_address,
            'is_primary' => $isPrimary,
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }
}
