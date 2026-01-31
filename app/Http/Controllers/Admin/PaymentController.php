<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function confirm(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => auth()->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        $payment->order->update([
            'payment_status' => 'confirmed',
            'status' => 'processing',
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['admin_notes' => 'required|string']);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Pembayaran ditolak.');
    }
}
