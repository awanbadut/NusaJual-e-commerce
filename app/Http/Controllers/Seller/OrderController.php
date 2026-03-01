<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display orders list
     */
    public function index(Request $request)
    {
        $store = auth()->user()->store;

        if (!$store) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Toko belum terdaftar');
        }

        $query = Order::where('store_id', $store->id)
            ->with(['user', 'items.product.images', 'payment'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        $stats = [
            'pending'    => Order::where('store_id', $store->id)->where('status', 'pending')->count(),
            'processing' => Order::where('store_id', $store->id)->whereIn('status', ['processing', 'packing'])->count(),
            'shipped'    => Order::where('store_id', $store->id)->where('status', 'shipped')->count(),
            'completed'  => Order::where('store_id', $store->id)->where('status', 'completed')->count(),
        ];

        return view('seller.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show order details
     */
    public function show($id)
    {
        $store = auth()->user()->store;

        $order = Order::where('store_id', $store->id)
            ->with(['user', 'items.product.images', 'store', 'payment.confirmedBy'])
            ->findOrFail($id);

        return view('seller.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'              => 'required|in:processing,packing,shipped,completed,cancelled',
            'tracking_number'     => 'required_if:status,shipped|nullable|string|max:100',
            'cancellation_reason' => 'required_if:status,cancelled|nullable|string|max:500',
        ], [
            'tracking_number.required_if'     => 'Nomor resi wajib diisi saat status Shipped',
            'cancellation_reason.required_if' => 'Alasan pembatalan wajib diisi',
        ]);

        $store = auth()->user()->store;
        $order = Order::with(['items.product', 'payment', 'user'])
            ->where('store_id', $store->id)
            ->findOrFail($id);

        // Validasi pembayaran harus confirmed (kecuali cancel)
        if ($request->status !== 'cancelled') {
            if (!$order->payment || $order->payment->status !== 'confirmed') {
                return back()->with('error', 'Pembayaran belum dikonfirmasi admin! Anda belum bisa mengubah status pesanan.');
            }
        }

        // Prevent update if already completed or cancelled
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Status pesanan tidak bisa diubah karena sudah ' .
                ($order->status == 'completed' ? 'selesai' : 'dibatalkan'));
        }

        // Validasi khusus status 'completed'
        if ($request->status === 'completed') {
            if ($order->status !== 'shipped') {
                return back()->with('error', 'Pesanan harus dalam status dikirim terlebih dahulu.');
            }

            if (!$order->shipped_at || now()->diffInDays($order->shipped_at) < 14) {
                $selisihDetik = now()->getTimestamp() - $order->shipped_at->getTimestamp();
                $hariBerjalan = $selisihDetik / 86400;
                $sisaHari     = ceil(14 - $hariBerjalan);

                return back()->with('error', "Anda baru bisa menyelesaikan pesanan ini secara manual dalam $sisaHari hari lagi.");
            }
        }

        $updateData = ['status' => $request->status];

        switch ($request->status) {
            case 'shipped':
                $updateData['tracking_number'] = $request->tracking_number;
                $updateData['shipped_at']       = now();
                break;

            case 'completed':
                $updateData['delivered_at'] = now();
                break;

            case 'cancelled':
                $updateData['cancellation_reason'] = $request->cancellation_reason;
                $updateData['cancelled_at']         = now();
                $updateData['cancelled_by']         = auth()->id();

                // Update payment ke rejected
                if ($order->payment) {
                    $order->payment->update([
                        'status'           => 'rejected',
                        'rejection_reason' => 'Order dibatalkan seller: ' . $request->cancellation_reason,
                        'rejected_at'      => now(),
                        'rejected_by'      => auth()->id(),
                    ]);
                }

                // ✅ Buat Refund otomatis dengan status needs_bank_info
                if ($order->payment && in_array($order->payment->status, ['paid', 'confirmed', 'rejected'])) {
                    $alreadyHasRefund = Refund::where('order_id', $order->id)->exists();

                    if (!$alreadyHasRefund) {
                        $orderAmount  = $order->total_amount;
                        $adminFee     = Refund::calculateAdminFee($orderAmount);
                        $refundAmount = Refund::calculateRefundAmount($orderAmount);

                        Refund::create([
                            'order_id'            => $order->id,
                            'user_id'             => $order->user_id,
                            'order_amount'        => $orderAmount,
                            'admin_fee'           => $adminFee,
                            'refund_amount'       => $refundAmount,
                            'bank_name'           => null,
                            'account_number'      => null,
                            'account_holder'      => null,
                            'status'              => 'needs_bank_info', // ✅ buyer harus isi rekening dulu
                            'cancellation_reason' => 'Dibatalkan seller: ' . $request->cancellation_reason,
                            'requested_at'        => now(),
                        ]);

                        $updateData['refund_status'] = 'pending';
                        $updateData['refund_amount'] = $refundAmount;
                    }
                }

                // Restore stock
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
                break;
        }

        $order->update($updateData);

        $successMessage = 'Status pesanan berhasil diupdate!';
        if ($request->status === 'cancelled') {
            $successMessage .= ' Stok produk telah dikembalikan.';
        }

        return back()->with('success', $successMessage);
    }
}
