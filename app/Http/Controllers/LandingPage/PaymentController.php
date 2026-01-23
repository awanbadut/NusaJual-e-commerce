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
     * URL: /payment/{id}
     */
    public function show($id)
    {
        // Cari Order berdasarkan ID dan User yang login
        // Kita gunakan 'with('items')' agar bisa menghitung jumlah produk di sidebar
        $order = Order::with('items')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // (Opsional) Jika status sudah paid/selesai, lempar ke halaman sukses
        // Agar user tidak bayar dua kali
        if ($order->payment_status == 'paid' || $order->status == 'completed') {
            return redirect()->route('payment.success');
        }

        return view('payment', compact('order'));
    }

    /**
     * 2. Memproses Upload Bukti Bayar (POST)
     * URL: /payment/{id}
     */
    public function process(Request $request, $id)
    {
        // Validasi input gambar
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048', // Maks 2MB
        ]);

        // Ambil data order
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->hasFile('payment_proof')) {

            // A. Simpan file ke storage (folder public/payment-proofs)
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            // B. Simpan data ke tabel PAYMENTS
            // Kita cek dulu apa user pernah upload sebelumnya (untuk update)
            $payment = Payment::where('order_id', $order->id)->first();

            if ($payment) {
                // Jika re-upload (misal ditolak admin atau salah upload)
                $payment->update([
                    'payment_proof' => $path,
                    'status' => 'pending', // Reset status konfirmasi
                    'paid_at' => now(),
                ]);
            } else {
                // Buat data pembayaran baru
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $order->total_amount, // Sesuai total tagihan
                    'payment_proof' => $path,
                    'status' => 'pending', // Menunggu konfirmasi admin
                    'paid_at' => now(),
                ]);
            }

            // C. Update status di tabel ORDERS
            // Ubah status pembayaran jadi 'paid' (atau 'waiting_verification')
            $order->update([
                'payment_status' => 'paid',     // Anggap sudah bayar (menunggu verifikasi)
                'status' => 'processing',       // Pesanan masuk tahap diproses
                'payment_method' => 'manual_transfer'
            ]);
        }

        // Redirect ke halaman sukses
        return redirect()->route('payment.success', $id);
    }

    /**
     * 3. Menampilkan Halaman Sukses
     * URL: /payment/success
     */
    public function success($id)
    {
        // Ambil data order untuk ditampilkan di struk
        // Kita load 'items.product' untuk menampilkan gambar & nama produk
        $order = Order::with(['items.product', 'payment'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('paymentSucces', compact('order'));
    }
}
