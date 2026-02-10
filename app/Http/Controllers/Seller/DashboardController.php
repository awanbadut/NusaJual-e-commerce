<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Withdrawal;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        // ===== REVENUE METRICS =====
        
        // Total Pendapatan (sub_total + shipping_cost untuk completed orders)
        $completedOrders = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->get();
        
        $totalRevenue = 0;
        foreach ($completedOrders as $order) {
            $totalRevenue += $order->sub_total + $order->shipping_cost;
        }

        // Pendapatan bulan ini
        $revenueThisMonth = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get()
            ->sum(function($order) {
                return $order->sub_total + $order->shipping_cost;
            });

        // Pendapatan bulan lalu (untuk persentase growth)
        $revenueLastMonth = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->get()
            ->sum(function($order) {
                return $order->sub_total + $order->shipping_cost;
            });

        // Hitung persentase pertumbuhan
        $revenueGrowth = $revenueLastMonth > 0 
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : 0;

        // ===== ORDER METRICS =====

        // Pesanan Baru (pending)
        $newOrders = Order::where('store_id', $store->id)
            ->where('status', 'pending')
            ->count();

        // Pesanan Diproses
        $processingOrders = Order::where('store_id', $store->id)
            ->whereIn('status', ['confirmed', 'processing', 'shipped'])
            ->count();

        // Total Orders (all time)
        $totalOrders = Order::where('store_id', $store->id)->count();

        // Orders Completed Today
        $ordersToday = Order::where('store_id', $store->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        // ===== WITHDRAWAL METRICS =====

        // Dana Tersedia untuk dicairkan
        $totalSales = 0;
        foreach ($completedOrders as $order) {
            $totalSales += $order->sub_total + $order->shipping_cost;
        }

        $totalWithdrawn = Withdrawal::where('store_id', $store->id)
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        $pendingWithdrawals = Withdrawal::where('store_id', $store->id)
            ->where('status', 'pending')
            ->sum('amount');

        $availableBalance = max(0, $totalSales - $totalWithdrawn - $pendingWithdrawals);

        // ===== PRODUCT METRICS =====

        // Total Products
        $totalProducts = Product::where('store_id', $store->id)->count();

        // Active Products
        $activeProducts = Product::where('store_id', $store->id)
            ->where('status', 'active')
            ->count();

        // Produk yang stoknya menipis
        $lowStockProducts = Product::where('store_id', $store->id)
            ->where('status', 'active')
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // ===== SALES TREND (7 hari terakhir) =====
        $salesTrend = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(6))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(sub_total + shipping_cost) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ===== TOP SELLING PRODUCTS =====
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.store_id', $store->id)
            ->where('orders.status', 'completed')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // ===== RECENT ORDERS =====
        $recentOrders = Order::where('store_id', $store->id)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // ===== RATING & REVIEWS =====
try {
    $averageRating = DB::table('reviews')
        ->join('order_items', 'reviews.order_item_id', '=', 'order_items.id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->where('products.store_id', $store->id)
        ->avg('reviews.rating');

    $totalReviews = DB::table('reviews')
        ->join('order_items', 'reviews.order_item_id', '=', 'order_items.id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->where('products.store_id', $store->id)
        ->count();
} catch (\Exception $e) {
    // Tabel reviews belum ada
    $averageRating = 0;
    $totalReviews = 0;
}


        
        // ===== ORDER STATUS BREAKDOWN =====
        $orderStatusBreakdown = Order::where('store_id', $store->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return view('seller.dashboard.index', compact(
            'store',
            'totalRevenue',
            'revenueThisMonth',
            'revenueGrowth',
            'newOrders',
            'processingOrders',
            'totalOrders',
            'ordersToday',
            'availableBalance',
            'totalProducts',
            'activeProducts',
            'lowStockProducts',
            'salesTrend',
            'topProducts',
            'recentOrders',
            'averageRating',
            'totalReviews',
            'orderStatusBreakdown'
        ));
    }
}
