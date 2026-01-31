<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Metric cards - real data
        $totalMitra = Store::count();
        $totalSales = Order::where('status', 'completed')->sum('total_amount');
        $totalOrders = Order::count();

        // Transaksi terbaru dengan relasi store dan items
        $recentTransactions = Order::with(['store', 'items'])
            ->latest()
            ->take(6)
            ->get();

        // Generate chart data dari database
        $chartData = $this->getChartData();

        return view('admin.dashboard', compact(
            'totalMitra',
            'totalSales',
            'totalOrders',
            'recentTransactions',
            'chartData'
        ));
    }

    private function getChartData()
    {
        // Ambil 6 mitra teratas berdasarkan jumlah order
        $topStores = Store::withCount('orders')
            ->orderByDesc('orders_count')
            ->take(6)
            ->get();

        $datasets = [];
        $colors = ['#FB7185', '#A78BFA', '#38BDF8', '#FBBF24', '#A855F7', '#22C55E'];

        foreach ($topStores as $index => $store) {
            $monthlySales = [];

            // Loop untuk setiap bulan (1-12)
            for ($month = 1; $month <= 12; $month++) {
                $sales = Order::where('store_id', $store->id)
                    ->where('status', 'completed')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', date('Y'))
                    ->sum('total_amount');

                // Konversi ke jutaan untuk chart (lebih readable)
                $monthlySales[] = round($sales / 1000000, 2);
            }

            $datasets[] = [
                'label' => $store->store_name,
                'data' => $monthlySales,
                'backgroundColor' => $colors[$index % 6],
                'borderRadius' => 4
            ];
        }

        return $datasets;
    }
}
