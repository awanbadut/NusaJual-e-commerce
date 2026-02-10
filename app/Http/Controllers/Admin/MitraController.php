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

        // ✅ FIX 1: PENDING PAYMENTS - EXCLUDE CANCELLED ORDERS
        $pendingPayments = Payment::whereHas('order', function($q) use ($id) {
        $q->where('store_id', $id)
          ->where('status', '!=', 'cancelled'); // Exclude cancelled orders
    })
    ->where('status', 'paid') // ✅ GANTI DARI 'pending' KE 'paid'
    ->with(['order.user', 'order.items.product'])
    ->latest('paid_at')
    ->paginate(5, ['*'], 'payments_page');

        // 5. Withdrawals - Hanya yang sudah di-request
        $withdrawals = $store->withdrawals()
    ->with('bankAccount')
    ->latest()
    ->paginate(5, ['*'], 'withdrawals_page');

        // ✅ FIX 2: CONFIRMED PAYMENTS - EXCLUDE CANCELLED
        $confirmedPayments = Payment::whereHas('order', function($q) use ($id) {
                $q->where('store_id', $id)
                  ->where('status', '!=', 'cancelled'); // EXCLUDE CANCELLED
            })
            ->where('status', 'confirmed')
            ->with('order')
            ->latest()
            ->paginate(5, ['*'], 'confirmed_page');

        // ✅ FIX 3: COMPLETED ORDERS (untuk Tabel Pencairan Dana)
        $completedOrders = Order::where('store_id', $id)
            ->where('status', 'completed')
            ->with(['items.product', 'payment'])
            ->latest('delivered_at')
            ->paginate(5, ['*'], 'orders_page');

       // 8. Dana Calculations (Real)
$totalWithdrawn = $store->withdrawals()
    ->whereIn('status', ['approved', 'completed'])
    ->sum('amount');

// Calculate total pending withdrawals
$danaTeralokasi = $store->withdrawals()
    ->where('status', 'pending')
    ->sum('amount');

// Dana Tersedia = Total Sales - (Withdrawn + Pending)
$sisaDana = ($store->total_sales ?? 0) - $totalWithdrawn - $danaTeralokasi;

        
        // Total bank accounts
        $totalBankAccounts = $store->bankAccounts->count();
        
        // Total completed withdrawals
        $totalPencairan = $store->withdrawals()
            ->where('status', 'completed')
            ->count();

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
            'totalBankAccounts',
            'totalPencairan'
        ));
    }

    /**
     * Get performance chart data for last 12 months
     */
    private function getPerformanceChartData($storeId)
    {
        $currentYear = now()->year;
        $lastYear = $currentYear - 1;
        
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // Current year data
        $currentYearData = [];
        for ($i = 1; $i <= 12; $i++) {
            $sales = Order::where('store_id', $storeId)
                ->where('status', 'completed')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->sum('total_amount');
            
            $currentYearData[] = round($sales / 1000000, 2); // Convert to millions
        }
        
        // Last year data
        $lastYearData = [];
        for ($i = 1; $i <= 12; $i++) {
            $sales = Order::where('store_id', $storeId)
                ->where('status', 'completed')
                ->whereYear('created_at', $lastYear)
                ->whereMonth('created_at', $i)
                ->sum('total_amount');
            
            $lastYearData[] = round($sales / 1000000, 2); // Convert to millions
        }
        
        return [
            'labels' => $months,
            'currentYear' => $currentYearData,
            'lastYear' => $lastYearData,
            'currentYearLabel' => $currentYear,
            'lastYearLabel' => $lastYear,
        ];
    }
}
