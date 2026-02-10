<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
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

        // Search by order number or customer name/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->whereHas('payment', function($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        // Filter by order status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        // Get statistics for quick view
        $stats = [
            'pending' => Order::where('store_id', $store->id)->where('status', 'pending')->count(),
            'processing' => Order::where('store_id', $store->id)->whereIn('status', ['processing', 'packing'])->count(),
            'shipped' => Order::where('store_id', $store->id)->where('status', 'shipped')->count(),
            'completed' => Order::where('store_id', $store->id)->where('status', 'completed')->count(),
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
    /**
 * Update order status
 */
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:processing,packing,shipped,completed,cancelled',
        'tracking_number' => 'required_if:status,shipped|nullable|string|max:100',
        'cancellation_reason' => 'required_if:status,cancelled|nullable|string|max:500'
    ], [
        'tracking_number.required_if' => 'Nomor resi wajib diisi saat status Shipped',
        'cancellation_reason.required_if' => 'Alasan pembatalan wajib diisi'
    ]);

    $store = auth()->user()->store;
    $order = Order::with('items.product')->where('store_id', $store->id)->findOrFail($id);
    
    // Validasi pembayaran harus confirmed (kecuali cancel)
    if ($request->status !== 'cancelled') {
        if (!$order->payment || $order->payment->status !== 'confirmed') {
            return back()->with('error', 'Pembayaran belum dikonfirmasi admin! Anda belum bisa mengubah status pesanan.');
        }
    }
    
    // Prevent update if already completed or cancelled
    if (in_array($order->status, ['completed', 'cancelled'])) {
        return back()->with('error', 'Status pesanan tidak bisa diubah karena sudah ' . ($order->status == 'completed' ? 'selesai' : 'dibatalkan'));
    }
    
    $updateData = [
        'status' => $request->status,
    ];
    
    // Handle different status
    switch ($request->status) {
        case 'shipped':
            // ✅ COURIER SUDAH ADA DI ORDER, TIDAK PERLU INPUT LAGI
            $updateData['tracking_number'] = $request->tracking_number;
            $updateData['shipped_at'] = now();
            break;
            
        case 'completed':
            $updateData['delivered_at'] = now();
            break;
            
        case 'cancelled':
            $updateData['cancellation_reason'] = $request->cancellation_reason;
            $updateData['cancelled_at'] = now();
            $updateData['cancelled_by'] = auth()->id();
            
            // Update payment status to 'rejected'
            if ($order->payment) {
                $order->payment->update([
                    'status' => 'rejected',
                    'rejection_reason' => 'Order dibatalkan seller: ' . $request->cancellation_reason,
                    'rejected_at' => now(),
                    'rejected_by' => auth()->id()
                ]);
            }
            
            // RESTORE STOCK when cancelled
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
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
