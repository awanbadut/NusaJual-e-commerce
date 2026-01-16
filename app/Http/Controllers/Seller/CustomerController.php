<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display list of customers
     */
    public function index(Request $request)
    {
        $storeId = auth()->user()->store->id;
        
        // Get customers with their order statistics
        $customers = User::select('users.*')
            ->join('orders', 'orders.user_id', '=', 'users.id')
            ->where('orders.store_id', $storeId)
            ->with(['orders' => function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            }])
            ->withCount(['orders as total_orders' => function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            }])
            ->withSum(['orders as total_spent' => function($query) use ($storeId) {
                $query->where('store_id', $storeId)->where('status', 'completed');
            }], 'total_amount')
            ->groupBy('users.id')
            ->orderBy('total_spent', 'desc')
            ->paginate(10);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $customers = User::select('users.*')
                ->join('orders', 'orders.user_id', '=', 'users.id')
                ->where('orders.store_id', $storeId)
                ->where(function($query) use ($request) {
                    $query->where('users.name', 'like', '%' . $request->search . '%')
                          ->orWhere('users.email', 'like', '%' . $request->search . '%')
                          ->orWhere('users.phone', 'like', '%' . $request->search . '%');
                })
                ->with(['orders' => function($query) use ($storeId) {
                    $query->where('store_id', $storeId);
                }])
                ->withCount(['orders as total_orders' => function($query) use ($storeId) {
                    $query->where('store_id', $storeId);
                }])
                ->withSum(['orders as total_spent' => function($query) use ($storeId) {
                    $query->where('store_id', $storeId)->where('status', 'completed');
                }], 'total_amount')
                ->groupBy('users.id')
                ->paginate(10);
        }

        return view('seller.customers.index', compact('customers'));
    }

    /**
     * Display customer detail
     */
    public function show($id)
    {
        $storeId = auth()->user()->store->id;
        
        // Get customer with orders
        $customer = User::with(['orders' => function($query) use ($storeId) {
            $query->where('store_id', $storeId)
                  ->with('items.product')
                  ->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // Calculate statistics
        $totalOrders = $customer->orders->count();
        $totalSpent = $customer->orders->where('status', 'completed')->sum('total_amount');
        $averageOrder = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

        return view('seller.customers.show', compact('customer', 'totalOrders', 'totalSpent', 'averageOrder'));
    }
}
