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
        $store = auth()->user()->store;
        
        if (!$store) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Toko belum terdaftar');
        }

        // Date range filter
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->startOfDay() 
            : now()->startOfMonth();
            
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date)->endOfDay() 
            : now()->endOfMonth();
        
        // ✅ Get ONLY COMPLETED orders (exclude cancelled/failed)
        $completedOrders = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate]);
        
        // Get sales statistics
        $totalRevenue = (clone $completedOrders)->sum('total_amount');
        $totalOrders = (clone $completedOrders)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Calculate percentage changes (vs last period)
        $periodDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $lastPeriodStart = Carbon::parse($startDate)->subDays($periodDays);
        $lastPeriodEnd = Carbon::parse($endDate)->subDays($periodDays);
        
        $lastPeriodRevenue = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$lastPeriodStart, $lastPeriodEnd])
            ->sum('total_amount');
            
        $revenueChange = $lastPeriodRevenue > 0 
            ? (($totalRevenue - $lastPeriodRevenue) / $lastPeriodRevenue) * 100 
            : ($totalRevenue > 0 ? 100 : 0);
        
        $lastPeriodOrders = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$lastPeriodStart, $lastPeriodEnd])
            ->count();
            
        $ordersChange = $lastPeriodOrders > 0 
            ? (($totalOrders - $lastPeriodOrders) / $lastPeriodOrders) * 100 
            : ($totalOrders > 0 ? 100 : 0);
        
        $lastPeriodAvg = $lastPeriodOrders > 0 ? $lastPeriodRevenue / $lastPeriodOrders : 0;
        $avgChange = $lastPeriodAvg > 0 
            ? (($avgOrderValue - $lastPeriodAvg) / $lastPeriodAvg) * 100 
            : ($avgOrderValue > 0 ? 100 : 0);
        
        // Get sales by day (for chart) - last 7 days
        $salesByDay = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        // Format chart data
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->format('D'); // Mon, Tue, etc
            
            $chartLabels[] = $dayName;
            
            $dayData = $salesByDay->firstWhere('date', $date);
            $chartData[] = $dayData ? $dayData->total : 0;
        }
        
        // Get completed orders with items
        $query = Order::with(['user', 'items.product'])
            ->where('store_id', $store->id)
            ->where('status', 'completed') // ✅ ONLY COMPLETED
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc');
        
        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sort
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'total_desc':
                    $query->orderBy('total_amount', 'desc');
                    break;
                case 'total_asc':
                    $query->orderBy('total_amount', 'asc');
                    break;
            }
        }
        
        $sales = $query->paginate(15);
        
        return view('seller.sales.index', compact(
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'revenueChange',
            'ordersChange',
            'avgChange',
            'chartLabels',
            'chartData',
            'sales',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Export sales data to CSV
     */
    public function export(Request $request)
    {
        $store = auth()->user()->store;
        
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->startOfDay() 
            : now()->startOfMonth();
            
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date)->endOfDay() 
            : now()->endOfMonth();
        
        $sales = Order::with(['user', 'items.product'])
            ->where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'penjualan_' . $store->store_name . '_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'no-cache, must-revalidate',
        ];
        
        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');
            
            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header CSV
            fputcsv($file, [
                'Tanggal',
                'Nomor Order',
                'Nama Customer',
                'Email Customer',
                'Produk',
                'Quantity',
                'Harga Satuan',
                'Total Order',
                'Status'
            ]);
            
            // Data
            foreach ($sales as $order) {
                foreach ($order->items as $item) {
                    fputcsv($file, [
                        $order->created_at->format('d-m-Y H:i'),
                        $order->order_number,
                        $order->user->name,
                        $order->user->email,
                        $item->product->name,
                        $item->quantity,
                        number_format($item->price, 0, ',', '.'),
                        number_format($order->total_amount, 0, ',', '.'),
                        'Selesai'
                    ]);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
