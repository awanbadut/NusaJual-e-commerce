<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Confirm payment (Admin approve pembayaran)
     */
    public function confirm(Request $request, $id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        // Update payment status
        $payment->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => auth()->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Update order payment status & order status
        $payment->order->update([
            'payment_status' => 'confirmed', // or 'paid'
            'status' => 'processing', // Siap diproses seller
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi! Seller sudah bisa memproses pesanan.');
    }

    /**
     * Reject payment
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi'
        ]);

        $payment = Payment::with('order')->findOrFail($id);

        $payment->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'rejection_reason' => $request->admin_notes,
        ]);

        // Update order
        $payment->order->update([
            'payment_status' => 'failed',
            'status' => 'pending', // Kembali ke pending
        ]);

        return back()->with('success', 'Pembayaran ditolak! Buyer perlu upload ulang bukti pembayaran.');
    }
}
