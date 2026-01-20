<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display list of customers
     */
    public function index(Request $request)
    {
        $storeId = auth('web')->user()->store->id;

        // 1. Mulai Query dari Model User
        $query = User::query();

        // 2. Filter: Hanya ambil User yang pernah order di Store ini
        // 'whereHas' menggantikan 'join' + 'groupBy', jadi tidak akan error SQL strict
        $query->whereHas('orders', function ($q) use ($storeId) {
            $q->where('store_id', $storeId);
        });

        // 3. Hitung Statistik (Total Order & Total Belanja)
        $query->withCount(['orders as total_orders' => function ($q) use ($storeId) {
            $q->where('store_id', $storeId);
        }]);

        $query->withSum(['orders as total_spent' => function ($q) use ($storeId) {
            $q->where('store_id', $storeId)->where('status', 'completed');
        }], 'total_amount');

        // 4. Logika Pencarian (Search)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // 5. Urutkan berdasarkan total belanja tertinggi
        // Catatan: total_spent dihasilkan dari withSum di atas
        $customers = $query->orderByDesc('total_spent')
            ->paginate(10);

        return view('seller.customers.index', compact('customers'));
    }

    /**
     * Display customer detail
     */
    public function show($id)
    {
        $storeId = auth('web')->user()->store->id;

        // Get customer with orders
        $customer = User::with(['orders' => function ($query) use ($storeId) {
            $query->where('store_id', $storeId)
                ->with('items.product')
                ->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // Calculate statistics manual (karena hanya 1 user, hitung di collection tidak berat)
        // Kita filter dulu order milik store ini saja
        $storeOrders = $customer->orders->where('store_id', $storeId); // Pastikan filter store_id lagi

        $totalOrders = $storeOrders->count();
        $totalSpent = $storeOrders->where('status', 'completed')->sum('total_amount');
        $averageOrder = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

        return view('seller.customers.show', compact('customer', 'totalOrders', 'totalSpent', 'averageOrder'));
    }
}
