<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display payments list
     */
    public function index(Request $request)
{
    $store = auth()->user()->store;
    
    if (!$store) {
        return redirect()->route('seller.dashboard')
            ->with('error', 'Toko belum terdaftar');
    }

    // Get statistics (EXCLUDE CANCELLED/FAILED ORDERS)
    $totalRevenue = Order::where('store_id', $store->id)
        ->where('status', 'completed')
        ->sum('total_amount');
        
    $confirmedPayments = Payment::whereHas('order', function($q) use ($store) {
            $q->where('store_id', $store->id)
              ->whereNotIn('status', ['cancelled', 'failed']); // ✅ FIX
        })
        ->where('status', 'confirmed')
        ->sum('amount');
        
    $pendingPayments = Payment::whereHas('order', function($q) use ($store) {
            $q->where('store_id', $store->id)
              ->whereNotIn('status', ['cancelled', 'failed']); // ✅ FIX
        })
        ->whereIn('status', ['pending', 'paid'])
        ->sum('amount');
    
    $rejectedPayments = Payment::whereHas('order', function($q) use ($store) {
            $q->where('store_id', $store->id)
              ->whereNotIn('status', ['cancelled', 'failed']); // ✅ FIX
        })
        ->where('status', 'rejected')
        ->count();
    
    // Get payments with orders (EXCLUDE CANCELLED)
    $query = Payment::with(['order.user', 'order.items.product'])
        ->whereHas('order', function($q) use ($store) {
            $q->where('store_id', $store->id)
              ->whereNotIn('status', ['cancelled', 'failed']); // ✅ FIX
        })
        ->orderBy('created_at', 'desc');
    
    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    // Filter by date range
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }
    
    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }
    
    $payments = $query->paginate(15);
    
    return view('seller.payments.index', compact(
        'totalRevenue',
        'confirmedPayments',
        'pendingPayments',
        'rejectedPayments',
        'payments'
    ));
}

    
    /**
     * Show payment details
     */
    public function show($id)
    {
        $payment = Payment::with(['order.user', 'order.items.product', 'order.store', 'confirmedBy', 'rejectedBy'])
            ->whereHas('order', function($q) {
                $q->where('store_id', auth()->user()->store->id);
            })
            ->findOrFail($id);
        
        return view('seller.payments.show', compact('payment'));
    }
    
    /**
     * Export payments to CSV
     */
    public function export(Request $request)
    {
        $store = auth()->user()->store;
        
        $payments = Payment::with(['order.user'])
            ->whereHas('order', function($q) use ($store) {
                $q->where('store_id', $store->id);
            })
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('date_from'), function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'laporan_pembayaran_' . $store->store_name . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Tanggal',
                'ID Order',
                'Nama Customer',
                'Jumlah Pembayaran',
                'Status',
                'Tanggal Konfirmasi',
            ]);
            
            // Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->created_at->format('d-m-Y H:i'),
                    '#ORD-' . str_pad($payment->order_id, 4, '0', STR_PAD_LEFT),
                    $payment->order->user->name ?? '-',
                    number_format($payment->amount, 0, ',', '.'),
                    ucfirst($payment->status),
                    $payment->confirmed_at ? $payment->confirmed_at->format('d-m-Y H:i') : '-',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
