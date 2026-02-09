<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Address;
use App\Models\Order;
use App\Models\Refund;
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
            'dob_day' => 'nullable|numeric',
            'dob_month' => 'nullable|numeric',
            'dob_year' => 'nullable|numeric',
        ]);

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
            'village_code' => 'required',
            'detail_address' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($request->has('is_primary')) {
            Address::where('user_id', Auth::id())->update(['is_primary' => false]);
        }

        $isPrimary = $request->has('is_primary') || Address::where('user_id', Auth::id())->count() == 0;

        Address::create([
            'user_id' => Auth::id(),
            'receiver_name' => $request->receiver_name,
            'phone' => $request->phone,
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
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_primary' => $isPrimary,
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function updateAddress(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province_code' => 'required',
            'city_code' => 'required',
            'district_code' => 'required',
            'village_code' => 'required',
            'province_name' => 'required',
            'city_name' => 'required',
            'district_name' => 'required',
            'village_name' => 'required',
            'postal_code' => 'required',
            'detail_address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        try {
            DB::beginTransaction();

            if ($request->has('is_primary') && $request->is_primary == '1') {
                Address::where('user_id', Auth::id())->update(['is_primary' => false]);
                $address->is_primary = true;
            }

            $address->update([
                'receiver_name' => $validated['receiver_name'],
                'phone' => $validated['phone'],
                'province_code' => $validated['province_code'],
                'city_code' => $validated['city_code'],
                'district_code' => $validated['district_code'],
                'village_code' => $validated['village_code'],
                'province_name' => $validated['province_name'],
                'city_name' => $validated['city_name'],
                'district_name' => $validated['district_name'],
                'village_name' => $validated['village_name'],
                'postal_code' => $validated['postal_code'],
                'detail_address' => $validated['detail_address'],
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_primary' => $address->is_primary ?? false,
            ]);

            DB::commit();

            return redirect()->route('profile.address')
                ->with('success', 'Alamat berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal update alamat: ' . $e->getMessage()]);
        }
    }

    public function destroyAddress($id)
    {
        $address = Address::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $address->delete();

        return redirect()->route('profile.address')
            ->with('success', 'Alamat berhasil dihapus.');
    }

    public function setPrimaryAddress($id)
    {
        $address = Address::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        try {
            DB::beginTransaction();
            Address::where('user_id', Auth::id())->update(['is_primary' => false]);
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

        $query = Order::with(['items.product.primaryImage', 'items.product.store', 'items.product.category', 'payment', 'refund'])
            ->where('user_id', $user->id)
            ->latest();

        if ($status !== 'all') {
            if ($status == 'pending') {
                $query->where('payment_status', 'pending')
                    ->where('status', '!=', 'cancelled');
            } elseif ($status == 'processing') {
                $query->whereIn('status', ['processing', 'shipped'])
                    ->where('payment_status', 'paid');
            } else {
                $query->where('status', $status);
            }
        }

        $orders = $query->paginate(5);

        return view('profileBuyer.orders', compact('orders'));
    }

    /**
     * Cancel Order (Buyer) - WITH REFUND LOGIC
     */
    public function cancelOrder(Request $request, $id)
    {
        $order = Order::with('payment')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan. Waktu pembatalan sudah habis atau pesanan sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // Scenario 1: Belum bayar (payment_status = pending)
            if ($order->payment_status === 'pending') {
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => $request->input('reason', 'Dibatalkan oleh pembeli'),
                    'refund_status' => 'none'
                ]);

                DB::commit();
                return back()->with('success', 'Pesanan berhasil dibatalkan.');
            }

            // Scenario 2: Sudah bayar - REFUND REQUIRED
            if ($order->payment_status === 'paid') {
                $validated = $request->validate([
                    'bank_name' => 'required|string|max:100',
                    'account_number' => 'required|string|max:50',
                    'account_holder' => 'required|string|max:255',
                    'reason' => 'nullable|string|max:500',
                ]);

                $orderAmount = $order->total_amount;
                $adminFee = Refund::calculateAdminFee($orderAmount);
                $refundAmount = Refund::calculateRefundAmount($orderAmount);

                Refund::create([
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'order_amount' => $orderAmount,
                    'admin_fee' => $adminFee,
                    'refund_amount' => $refundAmount,
                    'bank_name' => $validated['bank_name'],
                    'account_number' => $validated['account_number'],
                    'account_holder' => $validated['account_holder'],
                    'status' => 'pending',
                    'cancellation_reason' => $validated['reason'] ?? 'Dibatalkan oleh pembeli',
                    'requested_at' => now(),
                ]);

                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => $validated['reason'] ?? 'Dibatalkan oleh pembeli',
                    'refund_status' => 'pending',
                    'refund_amount' => $refundAmount,
                ]);

                DB::commit();

                return back()->with('success', 
                    "Pesanan berhasil dibatalkan. Dana sebesar Rp " . number_format($refundAmount, 0, ',', '.') . 
                    " akan dikembalikan ke rekening Anda setelah diproses admin (1-3 hari kerja)."
                );
            }

            DB::rollBack();
            return back()->with('error', 'Tidak dapat membatalkan pesanan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function completeOrder($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$order->canBeCompleted()) {
            return back()->with('error', 'Pesanan belum dapat diselesaikan. Pastikan pesanan sudah dikirim.');
        }

        $order->update([
            'status' => 'completed',
            'delivered_at' => now()
        ]);

        return back()->with('success', 'Terima kasih! Pesanan telah diselesaikan.');
    }
}
