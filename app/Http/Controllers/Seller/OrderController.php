<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display list of orders
     */
    public function index(Request $request)
    {
        $storeId = auth()->user()->store->id;
        
        $query = Order::where('store_id', $storeId)
            ->with(['user', 'items.product'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%')
                         ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by order status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('seller.orders.index', compact('orders'));
    }

    /**
     * Display order detail
     */
    public function show($id)
    {
        $storeId = auth()->user()->store->id;
        
        $order = Order::where('store_id', $storeId)
            ->with(['user', 'items.product', 'store'])
            ->findOrFail($id);

        return view('seller.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::where('store_id', auth()->user()->store->id)->findOrFail($id);
        
        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diupdate!');
    }
}
