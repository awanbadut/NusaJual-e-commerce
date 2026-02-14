<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display list of customers with search & filter
     */
    public function index(Request $request)
    {
        $storeId = auth('web')->user()->store->id;

        // 1. Mulai Query dari Model User
        $query = User::query();

        // 2. Filter: Hanya ambil User yang pernah order di Store ini
        $query->whereHas('orders', function ($q) use ($storeId) {
            $q->where('store_id', $storeId);
        });

        // 3. Hitung Statistik (Total Order & Total Belanja)
        $query->withCount(['orders as total_orders' => function ($q) use ($storeId) {
            $q->where('store_id', $storeId);
        }]);

        $query->withSum(['orders as total_spent' => function ($q) use ($storeId) {
            $q->where('store_id', $storeId)
                ->where('status', 'completed');
        }], 'total_amount');

        // ✅ 4. SEARCH (Nama, Email, Phone)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // ✅ 5. SORT / FILTER
        $sort = $request->get('sort', 'total_spent_desc'); // Default: Total Belanja Tertinggi

        switch ($sort) {
            case 'total_spent_desc':
                $query->orderByDesc('total_spent');
                break;
            case 'total_spent_asc':
                $query->orderBy('total_spent');
                break;
            case 'total_orders_desc':
                $query->orderByDesc('total_orders');
                break;
            case 'total_orders_asc':
                $query->orderBy('total_orders');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderByDesc('total_spent');
        }

        // 6. Pagination with Query String (preserve search & filter)
        $customers = $query->paginate(10)->withQueryString();

        return view('seller.customers.index', compact('customers'));
    }

    /**
     * Display customer detail
     */
    /**
 * Display customer detail with search & filter
 */
public function show(Request $request, $id)
{
    $storeId = auth('web')->user()->store->id;

    // Get customer info
    $customer = User::findOrFail($id);

    // Query orders dengan filter & search
    $ordersQuery = $customer->orders()
        ->where('store_id', $storeId)
        ->with('items.product');

    // ✅ SEARCH (Order Number)
    if ($request->filled('search')) {
        $ordersQuery->where('order_number', 'like', '%' . $request->search . '%');
    }

    // ✅ FILTER (Status)
    if ($request->filled('status')) {
        $ordersQuery->where('status', $request->status);
    }

    // Order by latest
    $ordersQuery->orderBy('created_at', 'desc');

    // ✅ PAGINATION with Query String
    $orders = $ordersQuery->paginate(10)->withQueryString();

    // Calculate statistics (ALL orders, not filtered)
    $allOrders = $customer->orders()->where('store_id', $storeId)->get();
    $totalOrders = $allOrders->count();
    $totalSpent = $allOrders->where('status', 'completed')->sum('total_amount');
    $averageOrder = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

    return view('seller.customers.show', compact('customer', 'orders', 'totalOrders', 'totalSpent', 'averageOrder'));
}

}
