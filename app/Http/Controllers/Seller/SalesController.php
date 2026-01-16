<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Display sales history
     */
    public function index(Request $request)
    {
        $storeId = auth()->user()->store->id;
        
        // Date range filter
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());
        
        // Get sales statistics
        $totalRevenue = Order::where('store_id', $storeId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
            
        $totalOrders = Order::where('store_id', $storeId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Calculate percentage changes (vs last period)
        $lastPeriodStart = Carbon::parse($startDate)->subDays(Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)));
        $lastPeriodEnd = Carbon::parse($startDate)->subDay();
        
        $lastPeriodRevenue = Order::where('store_id', $storeId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$lastPeriodStart, $lastPeriodEnd])
            ->sum('total_amount');
            
        $revenueChange = $lastPeriodRevenue > 0 ? (($totalRevenue - $lastPeriodRevenue) / $lastPeriodRevenue) * 100 : 0;
        
        // Get sales by day (for chart)
        $salesByDay = Order::where('store_id', $storeId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DAYNAME(created_at) as day'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('day')
            ->pluck('total', 'day');
        
        // Get order items with products
        $query = OrderItem::select('order_items.*', 'orders.created_at as order_date', 'orders.order_number', 'users.name as customer_name', 'users.email as customer_email')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.store_id', $storeId)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->with(['product', 'order.user'])
            ->orderBy('orders.created_at', 'desc');
        
        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('orders.order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('users.name', 'like', '%' . $request->search . '%');
            });
        }
        
        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('products.category_id', $request->category);
        }
        
        $salesItems = $query->paginate(10);
        
        return view('seller.sales.index', compact(
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'revenueChange',
            'salesByDay',
            'salesItems'
        ));
    }
    
    /**
     * Export sales data
     */
    public function export(Request $request)
    {
        // TODO: Implement CSV export
        return redirect()->back()->with('success', 'Export sedang diproses...');
    }
}
