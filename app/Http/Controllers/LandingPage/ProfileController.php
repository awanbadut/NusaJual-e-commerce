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
            'name'      => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'gender'    => 'nullable|in:Laki-laki,Perempuan',
            'dob_day'   => 'nullable|numeric',
            'dob_month' => 'nullable|numeric',
            'dob_year'  => 'nullable|numeric',
        ]);

        $dob = null;
        if ($request->dob_year && $request->dob_month && $request->dob_day) {
            $dob = $request->dob_year . '-' . $request->dob_month . '-' . $request->dob_day;
        }

        $user->update([
            'name'          => $request->name,
            'phone'         => $request->phone,
            'gender'        => $request->gender,
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
            'receiver_name'  => 'required',
            'phone'          => 'required',
            'province_code'  => 'required',
            'city_code'      => 'required',
            'district_code'  => 'required',
            'village_code'   => 'required',
            'detail_address' => 'required',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
        ]);

        if ($request->has('is_primary')) {
            Address::where('user_id', Auth::id())->update(['is_primary' => false]);
        }

        $isPrimary = $request->has('is_primary') || Address::where('user_id', Auth::id())->count() == 0;

        Address::create([
            'user_id'        => Auth::id(),
            'receiver_name'  => $request->receiver_name,
            'phone'          => $request->phone,
            'province_code'  => $request->province_code,
            'province_name'  => $request->province_name,
            'city_code'      => $request->city_code,
            'city_name'      => $request->city_name,
            'district_code'  => $request->district_code,
            'district_name'  => $request->district_name,
            'village_code'   => $request->village_code,
            'village_name'   => $request->village_name,
            'postal_code'    => $request->postal_code,
            'detail_address' => $request->detail_address,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
            'is_primary'     => $isPrimary,
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function updateAddress(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'receiver_name'  => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'province_code'  => 'required',
            'city_code'      => 'required',
            'district_code'  => 'required',
            'village_code'   => 'required',
            'province_name'  => 'required',
            'city_name'      => 'required',
            'district_name'  => 'required',
            'village_name'   => 'required',
            'postal_code'    => 'required',
            'detail_address' => 'required|string',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
        ]);

        try {
            DB::beginTransaction();

            if ($request->has('is_primary') && $request->is_primary == '1') {
                Address::where('user_id', Auth::id())->update(['is_primary' => false]);
                $address->is_primary = true;
            }

            $address->update([
                'receiver_name'  => $validated['receiver_name'],
                'phone'          => $validated['phone'],
                'province_code'  => $validated['province_code'],
                'city_code'      => $validated['city_code'],
                'district_code'  => $validated['district_code'],
                'village_code'   => $validated['village_code'],
                'province_name'  => $validated['province_name'],
                'city_name'      => $validated['city_name'],
                'district_name'  => $validated['district_name'],
                'village_name'   => $validated['village_name'],
                'postal_code'    => $validated['postal_code'],
                'detail_address' => $validated['detail_address'],
                'latitude'       => $request->latitude,
                'longitude'      => $request->longitude,
                'is_primary'     => $address->is_primary ?? false,
            ]);

            DB::commit();
            return redirect()->route('profile.address')->with('success', 'Alamat berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal update alamat: ' . $e->getMessage()]);
        }
    }

    public function destroyAddress($id)
    {
        $address = Address::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $address->delete();

        return redirect()->route('profile.address')->with('success', 'Alamat berhasil dihapus.');
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
        $user   = Auth::user();
        $status = $request->query('status', 'all');

        $query = Order::with([
                'items.product.primaryImage',
                'items.product.store',
                'items.product.category',
                'payment',
                'refund',
            ])
            ->where('user_id', $user->id)
            ->latest();

        if ($status !== 'all') {
            if ($status === 'pending') {
                $query->where('status', 'pending');
            } elseif ($status === 'processing') {
                $query->whereIn('status', ['processing', 'packing', 'shipped']);
            } else {
                $query->where('status', $status);
            }
        }

        $orders = $query->paginate(5);

        return view('profileBuyer.orders', compact('orders'));
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::with(['payment', 'items.product'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (in_array($order->status, ['shipped', 'completed', 'cancelled'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah ' . $order->status);
        }

        DB::beginTransaction();
        try {
            // SCENARIO 1: Belum bayar
            if (!$order->payment || $order->payment->status === 'pending') {

                if ($order->payment) {
                    $order->payment->update([
                        'status'           => 'rejected',
                        'rejection_reason' => 'Order dibatalkan buyer: ' . ($request->input('reason', 'Dibatalkan oleh pembeli')),
                        'rejected_at'      => now(),
                    ]);
                }

                $order->update([
                    'status'              => 'cancelled',
                    'cancelled_at'        => now(),
                    'cancelled_by'        => Auth::id(),
                    'cancellation_reason' => $request->input('reason', 'Dibatalkan oleh pembeli'),
                    'refund_status'       => 'none',
                ]);

                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }

                DB::commit();
                return back()->with('success', 'Pesanan berhasil dibatalkan. Stok produk telah dikembalikan.');
            }

            // SCENARIO 2: Sudah bayar — perlu refund
            if ($order->payment->status === 'paid') {

                $validated = $request->validate([
                    'bank_name'      => 'required|string|max:100',
                    'account_number' => 'required|string|max:50',
                    'account_holder' => 'required|string|max:255',
                    'reason'         => 'nullable|string|max:500',
                ]);

                $orderAmount  = $order->total_amount;
                $adminFee     = Refund::calculateAdminFee($orderAmount);
                $refundAmount = Refund::calculateRefundAmount($orderAmount);

                Refund::create([
                    'order_id'            => $order->id,
                    'user_id'             => Auth::id(),
                    'order_amount'        => $orderAmount,
                    'admin_fee'           => $adminFee,
                    'refund_amount'       => $refundAmount,
                    'bank_name'           => $validated['bank_name'],
                    'account_number'      => $validated['account_number'],
                    'account_holder'      => $validated['account_holder'],
                    'status'              => 'pending', // buyer cancel langsung isi rekening
                    'cancellation_reason' => $validated['reason'] ?? 'Dibatalkan oleh pembeli',
                    'requested_at'        => now(),
                ]);

                $order->payment->update([
                    'status'           => 'rejected',
                    'rejection_reason' => 'Order dibatalkan buyer (refund pending): ' . ($validated['reason'] ?? 'Dibatalkan oleh pembeli'),
                    'rejected_at'      => now(),
                ]);

                $order->update([
                    'status'              => 'cancelled',
                    'cancelled_at'        => now(),
                    'cancelled_by'        => Auth::id(),
                    'cancellation_reason' => $validated['reason'] ?? 'Dibatalkan oleh pembeli',
                    'refund_status'       => 'pending',
                    'refund_amount'       => $refundAmount,
                ]);

                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }

                DB::commit();
                return back()->with('success',
                    'Pesanan berhasil dibatalkan. Dana sebesar Rp ' . number_format($refundAmount, 0, ',', '.') .
                    ' akan dikembalikan setelah diproses admin (1-3 hari kerja).'
                );
            }

            // SCENARIO 3: Payment confirmed — tidak bisa cancel
            if ($order->payment->status === 'confirmed') {
                DB::rollBack();
                return back()->with('error', 'Pesanan sudah dikonfirmasi admin. Silakan hubungi admin untuk pembatalan.');
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

        if ($order->status !== 'shipped') {
            return back()->with('error', 'Pesanan belum dapat diselesaikan. Pastikan pesanan sudah dikirim.');
        }

        $order->update([
            'status'       => 'completed',
            'delivered_at' => now(),
        ]);

        return back()->with('success', 'Terima kasih! Pesanan telah diselesaikan.');
    }

    // ✅ Method baru: buyer isi rekening setelah seller cancel
    public function submitRefundBank(Request $request, $orderId)
    {
        $order = Order::with('refund')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$order->refund || $order->refund->status !== 'needs_bank_info') {
            return back()->with('error', 'Tidak ada permintaan rekening untuk pesanan ini.');
        }

        $validated = $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:255',
        ]);

        $order->refund->update([
            'bank_name'      => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_holder' => $validated['account_holder'],
            'status'         => 'pending', // ✅ siap diproses admin
            'requested_at'   => now(),
        ]);

        return back()->with('success', 'Informasi rekening berhasil disimpan. Admin akan memproses pengembalian dana dalam 1-3 hari kerja.');
    }
}
