<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * 1. Menampilkan Halaman Pembayaran (GET)
     */
    public function show($id)
    {
        $order = Order::with('items')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // ✅ FIX: Cek apakah order sudah cancelled
        if ($order->status === 'cancelled') {
            return redirect()->route('profile.orders')->with('error', 'Pesanan sudah dibatalkan.');
        }

        // Jika sudah bayar dan confirmed
        if ($order->payment && $order->payment->status == 'confirmed') {
            return redirect()->route('payment.success', $id);
        }

        return view('payment', compact('order'));
    }

    /**
     * 2. Memproses Upload Bukti Bayar (POST)
     */
    public function process(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // ✅ FIX: Cek apakah order sudah cancelled
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Pesanan sudah dibatalkan. Tidak bisa upload bukti bayar.');
        }

        if ($request->hasFile('payment_proof')) {

            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            $payment = Payment::where('order_id', $order->id)->first();

            if ($payment) {
                // Update existing payment
                $payment->update([
                    'payment_proof' => $path,
                    'status' => 'paid', // ✅ Status jadi 'paid' (menunggu konfirmasi admin)
                    'paid_at' => now(),
                ]);
            } else {
                // Create new payment
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $order->total_amount,
                    'payment_proof' => $path,
                    'status' => 'paid', // ✅ Status 'paid' bukan 'pending'
                    'paid_at' => now(),
                ]);
            }

            // ✅ Update order status
            $order->update([
                'status' => 'pending', // Order masih pending sampai payment confirmed
                'payment_method' => 'manual_transfer'
            ]);
        }

        return redirect()->route('payment.success', $id);
    }

    /**
     * 3. Menampilkan Halaman Sukses
     */
    public function success($id)
    {
        $order = Order::with(['items.product.category', 'payment'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('paymentSucces', compact('order'));
    }
}
