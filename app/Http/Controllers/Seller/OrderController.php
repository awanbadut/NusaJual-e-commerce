<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $storeId = auth()->user()->store->id;
        
        $query = Order::where('store_id', $storeId)
            ->with(['user', 'items.product', 'payment'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%')
                         ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by order status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('seller.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $storeId = auth()->user()->store->id;
        
        $order = Order::where('store_id', $storeId)
            ->with(['user', 'items.product', 'store', 'payment'])
            ->findOrFail($id);

        return view('seller.orders.show', compact('order'));
    }

    /**
     * Update order status (HANYA jika payment sudah confirmed)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:processing,packing,shipped,completed,cancelled',
            'tracking_number' => 'required_if:status,shipped|nullable|string|max:100',
            'courier' => 'required_if:status,shipped|nullable|string|max:50'
        ], [
            'tracking_number.required_if' => 'Nomor resi wajib diisi saat status Shipped',
            'courier.required_if' => 'Kurir wajib dipilih saat status Shipped'
        ]);

        $order = Order::where('store_id', auth()->user()->store->id)->findOrFail($id);
        
        // Validasi: Pembayaran harus sudah confirmed
        if ($order->payment_status !== 'confirmed' && $request->status !== 'cancelled') {
            return back()->with('error', 'Pembayaran belum dikonfirmasi admin! Anda belum bisa update status pesanan.');
        }
        
        $updateData = [
            'status' => $request->status,
        ];
        
        // Jika status shipped, simpan resi & kurir
        if ($request->status === 'shipped') {
            $updateData['tracking_number'] = $request->tracking_number;
            $updateData['courier'] = $request->courier;
            $updateData['shipped_at'] = now();
        }
        
        // Jika completed, set delivered_at
        if ($request->status === 'completed') {
            $updateData['delivered_at'] = now();
        }
        
        $order->update($updateData);

        return back()->with('success', 'Status pesanan berhasil diupdate!');
    }
}
