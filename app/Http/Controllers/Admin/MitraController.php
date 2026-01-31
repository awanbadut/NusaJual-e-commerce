<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::with('user')
            ->withCount('orders')
            ->withSum(['orders as total_sales' => function($q) {
                $q->where('status', 'completed');
            }], 'total_amount');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('store_name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('sort')) {
            switch($request->sort) {
                case 'sales_desc':
                    $query->orderByDesc('total_sales');
                    break;
                case 'orders_desc':
                    $query->orderByDesc('orders_count');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
            }
        } else {
            $query->latest();
        }

        $stores = $query->paginate(10)->withQueryString();

        return view('admin.mitra.index', compact('stores'));
    }

    public function show($id)
    {
        $store = Store::with(['user', 'bankAccounts', 'products'])
            ->withCount('orders')
            ->withSum(['orders as total_sales' => function($q) {
                $q->where('status', 'completed');
            }], 'total_amount')
            ->findOrFail($id);

        // ========== REAL DATA CALCULATIONS ==========
        
        // 1. Total Items Sold (Real)
        $totalItemsSold = \DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.store_id', $id)
            ->where('orders.status', 'completed')
            ->sum('order_items.quantity');

        // 2. Growth Calculations (Real)
        $currentMonth = now();
        $lastMonth = now()->subMonth();

        // Sales growth
        $currentMonthSales = Order::where('store_id', $id)
            ->where('status', 'completed')
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->sum('total_amount');

        $lastMonthSales = Order::where('store_id', $id)
            ->where('status', 'completed')
            ->whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->sum('total_amount');

        $salesGrowth = $lastMonthSales > 0 
            ? (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100 
            : ($currentMonthSales > 0 ? 100 : 0);

        // Orders growth
        $currentMonthOrders = Order::where('store_id', $id)
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->count();

        $lastMonthOrders = Order::where('store_id', $id)
            ->whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();

        $ordersGrowth = $lastMonthOrders > 0 
            ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 
            : ($currentMonthOrders > 0 ? 100 : 0);

        // 3. Chart Data (Real - 12 bulan)
        $chartData = $this->getPerformanceChartData($id);

        // 4. Pending Payments
        $pendingPayments = Payment::whereHas('order', function($q) use ($id) {
                $q->where('store_id', $id);
            })
            ->where('status', 'pending')
            ->with(['order.user'])
            ->latest()
            ->paginate(5, ['*'], 'payments_page');

        // 5. Withdrawals
        $withdrawals = $store->withdrawals()
            ->with('bankAccount')
            ->latest()
            ->paginate(5, ['*'], 'withdrawals_page');

        // 6. Confirmed Payments
        $confirmedPayments = Payment::whereHas('order', function($q) use ($id) {
                $q->where('store_id', $id);
            })
            ->where('status', 'confirmed')
            ->with('order')
            ->latest()
            ->paginate(5, ['*'], 'confirmed_page');

        // 7. Completed Orders
        $completedOrders = Order::where('store_id', $id)
            ->where('status', 'completed')
            ->with(['items.product', 'payment'])
            ->latest()
            ->paginate(5, ['*'], 'orders_page');

        // 8. Dana Calculations (Real)
        $totalWithdrawn = $store->withdrawals()
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        $sisaDana = ($store->total_sales ?? 0) - $totalWithdrawn;
        
        $danaTeralokasi = $store->withdrawals()
            ->where('status', 'pending')
            ->sum('amount');
        
        $totalPencairan = $store->withdrawals()
            ->where('status', 'completed')
            ->count();

        // 9. Bank Accounts Count
        $totalBankAccounts = $store->bankAccounts()->count();

        return view('admin.mitra.show', compact(
            'store',
            'totalItemsSold',
            'salesGrowth',
            'ordersGrowth',
            'chartData',
            'pendingPayments',
            'withdrawals',
            'confirmedPayments',
            'completedOrders',
            'sisaDana',
            'danaTeralokasi',
            'totalPencairan',
            'totalBankAccounts'
        ));
    }

    /**
     * Generate real chart data untuk 12 bulan terakhir
     */
    private function getPerformanceChartData($storeId)
    {
        $months = [];
        $currentYearData = [];
        $lastYearData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');

            // Data tahun ini
            $currentYearSales = Order::where('store_id', $storeId)
                ->where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');

            $currentYearData[] = round($currentYearSales / 1000000, 2);

            // Data tahun lalu
            $lastYearDate = $date->copy()->subYear();
            $lastYearSales = Order::where('store_id', $storeId)
                ->where('status', 'completed')
                ->whereYear('created_at', $lastYearDate->year)
                ->whereMonth('created_at', $lastYearDate->month)
                ->sum('total_amount');

            $lastYearData[] = round($lastYearSales / 1000000, 2);
        }

        return [
            'labels' => $months,
            'currentYear' => $currentYearData,
            'lastYear' => $lastYearData,
            'currentYearLabel' => now()->year,
            'lastYearLabel' => now()->subYear()->year
        ];
    }
}
