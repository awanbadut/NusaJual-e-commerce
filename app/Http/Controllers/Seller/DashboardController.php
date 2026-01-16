<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        // Total Pendapatan
        $totalRevenue = Order::where('store_id', $store->id)
            ->whereIn('status', ['completed'])
            ->sum('total_amount');

        // Pesanan Baru (pending)
        $newOrders = Order::where('store_id', $store->id)
            ->where('status', 'pending')
            ->count();

        // Pesanan Diproses
        $processingOrders = Order::where('store_id', $store->id)
            ->whereIn('status', ['confirmed', 'processing', 'shipped'])
            ->count();

        // Sales Trend (7 hari terakhir)
        $salesTrend = Order::where('store_id', $store->id)
            ->whereIn('status', ['completed'])
            ->where('created_at', '>=', now()->subDays(6))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Produk yang stoknya menipis
        $lowStockProducts = Product::where('store_id', $store->id)
            ->where('status', 'active')
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take(3)
            ->get();

        return view('seller.dashboard.index', compact(
            'store',
            'totalRevenue',
            'newOrders',
            'processingOrders',
            'salesTrend',
            'lowStockProducts'
        ));
    }
}
