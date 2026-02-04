<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

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
            'latitude'       => 'nullable|numeric', // Tambahan
            'longitude'      => 'nullable|numeric', // Tambahan
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
            'latitude'       => $request->latitude,  // Simpan
            'longitude'      => $request->longitude, // Simpan
            'is_primary' => $isPrimary,
        ]);
        //dd($request->all());

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function updateAddress(Request $request, $id)
    {
        // 1. Cari Alamat milik user yang sedang login (Security Check)
        $address = Address::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        // 2. Validasi sama persis dengan Store
        $validated = $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'province_code' => 'required',
            'city_code'     => 'required',
            'district_code' => 'required',
            'village_code'  => 'required',
            'province_name' => 'required',
            'city_name'     => 'required',
            'district_name' => 'required',
            'village_name'  => 'required',
            'postal_code'   => 'required',
            'detail_address' => 'required|string',
            'latitude'       => 'nullable|numeric', // Tambahan
            'longitude'      => 'nullable|numeric', // Tambahan
        ]);

        try {
            DB::beginTransaction();

            // 3. Logika "Jadikan Utama"
            // Jika user mencentang "Jadikan Utama", maka alamat lain harus di-set false
            if ($request->has('is_primary') && $request->is_primary == '1') {
                Address::where('user_id', Auth::id())->update(['is_primary' => false]);
                $address->is_primary = true;
            }
            // Catatan: Jika user uncheck, kita biarkan saja (tidak otomatis mengubah yang lain)
            // Atau logic-nya bisa disesuaikan kebutuhan.

            // 4. Update Data
            $address->update([
                'receiver_name' => $validated['receiver_name'],
                'phone'         => $validated['phone'],
                'province_code' => $validated['province_code'],
                'city_code'     => $validated['city_code'],
                'district_code' => $validated['district_code'],
                'village_code'  => $validated['village_code'],
                'province_name' => $validated['province_name'],
                'city_name'     => $validated['city_name'],
                'district_name' => $validated['district_name'],
                'village_name'  => $validated['village_name'],
                'postal_code'   => $validated['postal_code'],
                'detail_address' => $validated['detail_address'],
                'latitude'       => $request->latitude,  // Update
                'longitude'      => $request->longitude, // Update
                'is_primary'    => $address->is_primary ?? false, // Pertahankan status jika tidak diubah
            ]);

            DB::commit();

            return redirect()->route('profile.address')
                ->with('success', 'Alamat berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal update alamat: ' . $e->getMessage()]);
        }
    }

    /**
     * HAPUS ALAMAT
     */
    public function destroyAddress($id)
    {
        $address = Address::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $address->delete();

        return redirect()->route('profile.address')
            ->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * SET ALAMAT UTAMA (Via Tombol Kecil "Jadikan Utama")
     */
    public function setPrimaryAddress($id)
    {
        // Cari alamat yang mau dijadikan utama
        $address = Address::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        try {
            DB::beginTransaction();

            // 1. Reset semua alamat user ini jadi false
            Address::where('user_id', Auth::id())->update(['is_primary' => false]);

            // 2. Set alamat yang dipilih jadi true
            $address->update(['is_primary' => true]);

            DB::commit();

            return redirect()->back()->with('success', 'Alamat utama berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengubah alamat utama.']);
        }
    }

    public function orders(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status', 'all');

        // Query Dasar: Ambil Order user login + relasi produk
        $query = Order::with(['items.product.primaryImage', 'items.product.store'])
            ->where('user_id', $user->id)
            ->latest(); // Urutkan dari yang terbaru

        // Logic Filter Status
        if ($status !== 'all') {
            if ($status == 'pending') {
                // Filter "Belum Dibayar"
                $query->where('payment_status', 'pending')
                    ->where('status', '!=', 'cancelled');
            } elseif ($status == 'processing') {
                // Filter "Diproses" (bisa mencakup paid, processing, shipped)
                $query->whereIn('status', ['processing', 'shipped'])
                    ->where('payment_status', 'paid'); // Pastikan sudah bayar
            } else {
                // Filter status biasa (completed, cancelled)
                $query->where('status', $status);
            }
        }

        $orders = $query->paginate(5); // Pagination 5 order per halaman

        return view('profileBuyer.orders', compact('orders'));
    }
}
