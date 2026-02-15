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

        // 1. Total Items Sold
        $totalItemsSold = \DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.store_id', $id)
            ->where('orders.status', 'completed')
            ->sum('order_items.quantity');

        // 2. Growth Calculations
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

        // 3. Chart Data
        $chartData = $this->getPerformanceChartData($id);

        // 4. Pending Payments
        $pendingPayments = Payment::whereHas('order', function($q) use ($id) {
                $q->where('store_id', $id)
                  ->where('status', '!=', 'cancelled');
            })
            ->where('status', 'paid')
            ->with(['order.user', 'order.items.product'])
            ->latest('paid_at')
            ->paginate(5, ['*'], 'payments_page');

        // 5. Withdrawals
        $withdrawals = $store->withdrawals()
            ->with('bankAccount')
            ->latest()
            ->paginate(5, ['*'], 'withdrawals_page');

        // 6. Confirmed Payments
        $confirmedPayments = Payment::whereHas('order', function($q) use ($id) {
                $q->where('store_id', $id)
                  ->where('status', '!=', 'cancelled');
            })
            ->where('status', 'confirmed')
            ->with('order')
            ->latest()
            ->paginate(5, ['*'], 'confirmed_page');

        // 7. Completed Orders
        $completedOrders = Order::where('store_id', $id)
            ->where('status', 'completed')
            ->with(['items.product', 'payment'])
            ->latest('delivered_at')
            ->paginate(5, ['*'], 'orders_page');

        // 8. Dana Calculations
        $totalWithdrawn = $store->withdrawals()
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        $danaTeralokasi = $store->withdrawals()
            ->where('status', 'pending')
            ->sum('amount');

        $sisaDana = ($store->total_sales ?? 0) - $totalWithdrawn - $danaTeralokasi;
        
        $totalBankAccounts = $store->bankAccounts->count();
        $totalPencairan = $store->withdrawals()->where('status', 'completed')->count();

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

    private function getPerformanceChartData($storeId)
    {
        $currentYear = now()->year;
        $lastYear = $currentYear - 1;
        
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        $currentYearData = [];
        for ($i = 1; $i <= 12; $i++) {
            $sales = Order::where('store_id', $storeId)
                ->where('status', 'completed')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->sum('total_amount');
            
            $currentYearData[] = round($sales / 1000000, 2);
        }
        
        $lastYearData = [];
        for ($i = 1; $i <= 12; $i++) {
            $sales = Order::where('store_id', $storeId)
                ->where('status', 'completed')
                ->whereYear('created_at', $lastYear)
                ->whereMonth('created_at', $i)
                ->sum('total_amount');
            
            $lastYearData[] = round($sales / 1000000, 2);
        }
        
        return [
            'labels' => $months,
            'currentYear' => $currentYearData,
            'lastYear' => $lastYearData,
            'currentYearLabel' => $currentYear,
            'lastYearLabel' => $lastYear,
        ];
    }

    /**
     * Export detailed store report to CSV
     */
    public function export($id)
    {
        $store = Store::with(['user', 'bankAccounts', 'products'])
            ->withCount('orders')
            ->withSum(['orders as total_sales' => function($q) {
                $q->where('status', 'completed');
            }], 'total_amount')
            ->findOrFail($id);

        $totalItemsSold = \DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.store_id', $id)
            ->where('orders.status', 'completed')
            ->sum('order_items.quantity');

        $totalWithdrawn = $store->withdrawals()
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        $pendingWithdrawals = $store->withdrawals()
            ->where('status', 'pending')
            ->sum('amount');

        $sisaDana = ($store->total_sales ?? 0) - $totalWithdrawn - $pendingWithdrawals;

        $orders = Order::where('store_id', $id)
            ->with(['user', 'items.product', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get();

        $products = $store->products()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        $withdrawals = $store->withdrawals()
            ->with('bankAccount')
            ->orderBy('requested_at', 'desc')
            ->get();

        $filename = 'laporan_mitra_' . \Str::slug($store->store_name) . '_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'no-cache, must-revalidate',
        ];

        $callback = function() use ($store, $totalItemsSold, $totalWithdrawn, $pendingWithdrawals, $sisaDana, $orders, $products, $withdrawals) {
            $file = fopen('php://output', 'w');
            
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['=== INFORMASI TOKO ===']);
            fputcsv($file, ['Nama Toko', $store->store_name]);
            fputcsv($file, ['Pemilik', $store->user->name]);
            fputcsv($file, ['Email', $store->user->email]);
            fputcsv($file, ['Telepon', $store->user->phone ?? '-']);
            fputcsv($file, ['Alamat', $store->address]);
            fputcsv($file, ['Deskripsi', $store->description ?? '-']);
            fputcsv($file, ['Tanggal Bergabung', $store->created_at->format('d-m-Y H:i')]);
            fputcsv($file, []);
            
            fputcsv($file, ['=== STATISTIK KEUANGAN ===']);
            fputcsv($file, ['Total Penjualan (Completed)', 'Rp ' . number_format($store->total_sales ?? 0, 0, ',', '.')]);
            fputcsv($file, ['Total Pesanan', $store->orders_count]);
            fputcsv($file, ['Total Item Terjual', number_format($totalItemsSold)]);
            fputcsv($file, ['Total Dana Ditarik', 'Rp ' . number_format($totalWithdrawn, 0, ',', '.')]);
            fputcsv($file, ['Dana Pending', 'Rp ' . number_format($pendingWithdrawals, 0, ',', '.')]);
            fputcsv($file, ['Sisa Dana Tersedia', 'Rp ' . number_format($sisaDana, 0, ',', '.')]);
            fputcsv($file, []);
            
            fputcsv($file, ['=== REKENING BANK ===']);
            if ($store->bankAccounts->count() > 0) {
                fputcsv($file, ['Nama Bank', 'Nomor Rekening', 'Nama Pemilik']);
                foreach ($store->bankAccounts as $bank) {
                    fputcsv($file, [$bank->bank_name, $bank->account_number, $bank->account_name]);
                }
            } else {
                fputcsv($file, ['Belum ada rekening bank terdaftar']);
            }
            fputcsv($file, []);
            
            fputcsv($file, ['=== DAFTAR PRODUK ===']);
            fputcsv($file, ['Nama Produk', 'Kategori', 'Harga', 'Stok', 'Berat (gr)', 'Status']);
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->name,
                    $product->category->name,
                    'Rp ' . number_format($product->price, 0, ',', '.'),
                    $product->stock,
                    $product->weight,
                    ucfirst($product->status)
                ]);
            }
            fputcsv($file, []);
            
            fputcsv($file, ['=== RIWAYAT PESANAN ===']);
            fputcsv($file, ['Tanggal', 'Nomor Order', 'Customer', 'Status', 'Payment Status', 'Total Amount']);
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->created_at->format('d-m-Y H:i'),
                    $order->order_number,
                    $order->user->name,
                    ucfirst($order->status),
                    $order->payment ? ucfirst($order->payment->status) : 'N/A',
                    'Rp ' . number_format($order->total_amount, 0, ',', '.')
                ]);
            }
            fputcsv($file, []);
            
            fputcsv($file, ['=== DETAIL ITEM PESANAN ===']);
            fputcsv($file, ['Tanggal', 'Nomor Order', 'Produk', 'Qty', 'Harga Satuan', 'Total Item']);
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    fputcsv($file, [
                        $order->created_at->format('d-m-Y'),
                        $order->order_number,
                        $item->product->name,
                        $item->quantity,
                        'Rp ' . number_format($item->price, 0, ',', '.'),
                        'Rp ' . number_format($item->quantity * $item->price, 0, ',', '.')
                    ]);
                }
            }
            fputcsv($file, []);
            
            fputcsv($file, ['=== RIWAYAT PENCAIRAN DANA ===']);
            if ($withdrawals->count() > 0) {
                fputcsv($file, ['Tanggal Request', 'ID', 'Bank', 'No Rekening', 'Jumlah', 'Biaya Admin', 'Diterima', 'Status', 'Tanggal Approved', 'Tanggal Completed']);
                foreach ($withdrawals as $wd) {
                    fputcsv($file, [
                        $wd->requested_at->format('d-m-Y H:i'),
                        '#WD-' . str_pad($wd->id, 4, '0', STR_PAD_LEFT),
                        $wd->bankAccount->bank_name,
                        $wd->bankAccount->account_number,
                        'Rp ' . number_format($wd->amount, 0, ',', '.'),
                        'Rp ' . number_format($wd->admin_fee, 0, ',', '.'),
                        'Rp ' . number_format($wd->total_received, 0, ',', '.'),
                        ucfirst($wd->status),
                        $wd->approved_at ? $wd->approved_at->format('d-m-Y H:i') : '-',
                        $wd->completed_at ? $wd->completed_at->format('d-m-Y H:i') : '-'
                    ]);
                }
            } else {
                fputcsv($file, ['Belum ada riwayat pencairan dana']);
            }
            fputcsv($file, []);
            
            fputcsv($file, ['=== LAPORAN DIBUAT ===']);
            fputcsv($file, ['Tanggal Generate', now()->format('d-m-Y H:i:s')]);
            fputcsv($file, ['Generate Oleh', 'Admin Nusa Belanja']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export all stores summary
     */
    public function exportAll(Request $request)
    {
        $query = Store::with(['user'])
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

        $stores = $query->get();

        $filename = 'laporan_semua_mitra_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'no-cache, must-revalidate',
        ];

        $callback = function() use ($stores) {
            $file = fopen('php://output', 'w');
            
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['=== LAPORAN SEMUA MITRA NUSA BELANJA ===']);
            fputcsv($file, ['Tanggal Generate', now()->format('d-m-Y H:i:s')]);
            fputcsv($file, ['Total Mitra', $stores->count()]);
            fputcsv($file, []);
            
            fputcsv($file, [
                'Nama Toko',
                'Pemilik',
                'Email',
                'Telepon',
                'Alamat',
                'Total Penjualan',
                'Total Pesanan',
                'Tanggal Bergabung'
            ]);
            
            foreach ($stores as $store) {
                fputcsv($file, [
                    $store->store_name,
                    $store->user->name,
                    $store->user->email,
                    $store->user->phone ?? '-',
                    $store->address,
                    'Rp ' . number_format($store->total_sales ?? 0, 0, ',', '.'),
                    $store->orders_count,
                    $store->created_at->format('d-m-Y')
                ]);
            }
            
            fputcsv($file, []);
            fputcsv($file, ['=== RINGKASAN ===']);
            fputcsv($file, ['Total Mitra', $stores->count()]);
            fputcsv($file, ['Total Penjualan Seluruh Mitra', 'Rp ' . number_format($stores->sum('total_sales'), 0, ',', '.')]);
            fputcsv($file, ['Total Pesanan Seluruh Mitra', $stores->sum('orders_count')]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
 * Export confirmed payments history to CSV
 */
public function exportConfirmedPayments($id)
{
    $store = Store::findOrFail($id);
    
    $confirmedPayments = Payment::whereHas('order', function($q) use ($id) {
            $q->where('store_id', $id)
              ->where('status', '!=', 'cancelled');
        })
        ->where('status', 'confirmed')
        ->with(['order.user'])
        ->latest()
        ->get();

    $filename = 'histori_pembayaran_' . \Str::slug($store->store_name) . '_' . now()->format('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
        'Cache-Control' => 'no-cache, must-revalidate',
    ];

    $callback = function() use ($store, $confirmedPayments) {
        $file = fopen('php://output', 'w');
        
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($file, ['=== HISTORI VERIFIKASI PEMBAYARAN ===']);
        fputcsv($file, ['Mitra', $store->store_name]);
        fputcsv($file, ['Tanggal Export', now()->format('d-m-Y H:i:s')]);
        fputcsv($file, ['Total Transaksi', $confirmedPayments->count()]);
        fputcsv($file, []);
        
        fputcsv($file, ['Tanggal Transaksi', 'ID Pesanan', 'Nama Pembeli', 'Total Transaksi', 'Status', 'Tanggal Konfirmasi']);
        
        foreach ($confirmedPayments as $payment) {
            fputcsv($file, [
                $payment->confirmed_at ? $payment->confirmed_at->format('d-m-Y H:i') : $payment->created_at->format('d-m-Y H:i'),
                '#NB-' . str_pad($payment->order->id, 4, '0', STR_PAD_LEFT),
                $payment->order->user->name,
                'Rp ' . number_format($payment->amount, 0, ',', '.'),
                'Terkonfirmasi',
                $payment->confirmed_at ? $payment->confirmed_at->format('d-m-Y H:i') : '-'
            ]);
        }
        
        fputcsv($file, []);
        fputcsv($file, ['Total Transaksi', $confirmedPayments->count()]);
        fputcsv($file, ['Total Nilai Transaksi', 'Rp ' . number_format($confirmedPayments->sum('amount'), 0, ',', '.')]);
        
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

/**
 * Export completed orders to CSV
 */
public function exportCompletedOrders($id)
{
    $store = Store::findOrFail($id);
    
    $completedOrders = Order::where('store_id', $id)
        ->where('status', 'completed')
        ->with(['user', 'items.product', 'payment'])
        ->latest('delivered_at')
        ->get();

    $filename = 'pesanan_selesai_' . \Str::slug($store->store_name) . '_' . now()->format('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
        'Cache-Control' => 'no-cache, must-revalidate',
    ];

    $callback = function() use ($store, $completedOrders) {
        $file = fopen('php://output', 'w');
        
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($file, ['=== REKAPITULASI PESANAN SELESAI ===']);
        fputcsv($file, ['Mitra', $store->store_name]);
        fputcsv($file, ['Tanggal Export', now()->format('d-m-Y H:i:s')]);
        fputcsv($file, ['Total Pesanan', $completedOrders->count()]);
        fputcsv($file, []);
        
        fputcsv($file, ['Tanggal Pembelian', 'ID Order', 'Nama Pembeli', 'Jumlah Dana', 'Ongkir', 'Total', 'Status Payment', 'Tanggal Selesai']);
        
        foreach ($completedOrders as $order) {
            fputcsv($file, [
                $order->created_at->format('d-m-Y H:i'),
                '#ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                $order->user->name,
                'Rp ' . number_format($order->total_amount - $order->shipping_cost, 0, ',', '.'),
                'Rp ' . number_format($order->shipping_cost, 0, ',', '.'),
                'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                $order->payment ? ucfirst($order->payment->status) : '-',
                $order->delivered_at ? $order->delivered_at->format('d-m-Y H:i') : '-'
            ]);
        }
        
        fputcsv($file, []);
        fputcsv($file, ['=== DETAIL PRODUK TERJUAL ===']);
        fputcsv($file, ['ID Order', 'Produk', 'Qty', 'Harga Satuan', 'Total Item']);
        
        foreach ($completedOrders as $order) {
            foreach ($order->items as $item) {
                fputcsv($file, [
                    '#ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    $item->product->name,
                    $item->quantity,
                    'Rp ' . number_format($item->price, 0, ',', '.'),
                    'Rp ' . number_format($item->quantity * $item->price, 0, ',', '.')
                ]);
            }
        }
        
        fputcsv($file, []);
        fputcsv($file, ['Total Pesanan Selesai', $completedOrders->count()]);
        fputcsv($file, ['Total Pendapatan', 'Rp ' . number_format($completedOrders->sum('total_amount'), 0, ',', '.')]);
        
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    public function showOrder($storeId, $orderId)
{
    $store = Store::findOrFail($storeId);
    
    $order = Order::where('id', $orderId)
        ->where('store_id', $storeId)
        ->with([
            'user',
            'items.product.images',
            'payment',
            'store'
        ])
        ->firstOrFail();

    return view('admin.mitra.order-detail', compact('store', 'order'));
}

public function showPayment($storeId, $paymentId)
{
    $store = Store::findOrFail($storeId);
    
    $payment = Payment::where('id', $paymentId)
        ->whereHas('order', function($q) use ($storeId) {
            $q->where('store_id', $storeId);
        })
        ->with([
            'order.user',
            'order.items.product.images',
            'order.shippingAddress',
            'order.store'
        ])
        ->firstOrFail();

    // Order items with details
    $orderItems = $payment->order->items->map(function($item) {
        return [
            'product_name' => $item->product->name,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'subtotal' => $item->quantity * $item->price,
            'image' => $item->product->images->first()->image_path ?? null,
        ];
    });

    // Timeline
    $timeline = [
        [
            'title' => 'Pesanan Dibuat',
            'date' => $payment->order->created_at,
            'status' => 'completed',
            'icon' => 'shopping-cart',
        ],
        [
            'title' => 'Pembayaran Diunggah',
            'date' => $payment->created_at,
            'status' => 'completed',
            'icon' => 'credit-card',
        ],
    ];

    if ($payment->confirmed_at) {
        $timeline[] = [
            'title' => 'Pembayaran Dikonfirmasi',
            'date' => $payment->confirmed_at,
            'status' => 'completed',
            'icon' => 'check-circle',
            'admin' => 'Admin Nusa Belanja',
        ];
    }

    if ($payment->order->status === 'completed') {
        $timeline[] = [
            'title' => 'Pesanan Selesai',
            'date' => $payment->order->completed_at ?? $payment->order->updated_at,
            'status' => 'completed',
            'icon' => 'check-badge',
        ];
    }

    return view('admin.mitra.payment-detail', compact('store', 'payment', 'orderItems', 'timeline'));
}


}
