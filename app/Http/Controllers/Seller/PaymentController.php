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
        $storeId = auth()->user()->store->id;
        
        // Get statistics
        $totalRevenue = Order::where('store_id', $storeId)
            ->where('status', 'completed')
            ->sum('total_amount');
            
        $disbursedAmount = Payment::whereHas('order', function($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->where('status', 'disbursed')
            ->sum('amount');
            
        $pendingAmount = $totalRevenue - $disbursedAmount;
        
        // Get payments with orders
        $query = Payment::with(['order.user'])
            ->whereHas('order', function($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->orderBy('disbursement_date', 'desc');
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $payments = $query->paginate(10);
        
        return view('seller.payments.index', compact(
            'totalRevenue',
            'disbursedAmount',
            'pendingAmount',
            'payments'
        ));
    }
    
    /**
     * Show payment proof
     */
    public function show($id)
    {
        $payment = Payment::with(['order.user', 'order.store'])
            ->whereHas('order', function($q) {
                $q->where('store_id', auth()->user()->store->id);
            })
            ->findOrFail($id);
        
        return view('seller.payments.show', compact('payment'));
    }
}
