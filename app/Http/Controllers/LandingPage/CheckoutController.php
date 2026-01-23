<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // 1. Menampilkan Halaman Checkout (Review)
    public function review(Request $request)
    {
        if ($request->isMethod('get') || !$request->has('selected_items')) {
            return redirect()->route('keranjang')->with('error', 'Silakan pilih produk terlebih dahulu sebelum checkout.');
        }

        // Validasi: Pastikan ada item yang dipilih dari keranjang
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:carts,id'
        ]);

        // Ambil data item keranjang yang dipilih
        $cartItems = Cart::with('product')->whereIn('id', $request->selected_items)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang')->with('error', 'Pilih minimal satu produk.');
        }

        // Hitung Subtotal
        $subTotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // Data dummy ongkir (Nanti bisa dikembangkan pakai API RajaOngkir)
        $couriers = [
            ['id' => 'jne', 'name' => 'JNE Reguler', 'etd' => '5-7 Hari', 'price' => 45000],
            ['id' => 'jnt', 'name' => 'JNT Express', 'etd' => '3-5 Hari', 'price' => 50000],
            ['id' => 'sicepat', 'name' => 'Sicepat', 'etd' => '7-14 Hari', 'price' => 20000],
            ['id' => 'sikilat', 'name' => 'Sikilat', 'etd' => '7-10 Hari', 'price' => 25000],
        ];

        return view('checkout', compact('cartItems', 'subTotal', 'couriers'));
    }

    // 2. Memproses Simpan Order (Action dari tombol "Lakukan Pembayaran")
    // ... (Method review tetap sama) ...

    // 2. Memproses Simpan Order
    public function store(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|string',
            'shipping_cost' => 'required|numeric',
            'shipping_courier' => 'required|string',
        ]);

        $selectedItemIds = explode(',', $request->selected_items);

        // Load data produk
        $cartItems = Cart::with('product')->whereIn('id', $selectedItemIds)->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Terjadi kesalahan, item tidak ditemukan.');
        }

        // Ambil store_id dari produk pertama
        $storeId = $cartItems->first()->product->store_id;

        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $totalPrice = $subtotal + 1000 + $request->shipping_cost;

        try {
            DB::beginTransaction();

            // === [LOGIKA BARU] GENERATE ORDER NUMBER ===
            // Format: NB-YYYYMMDD-XXXX (Urutan per hari)

            $today = now()->format('Ymd'); // Contoh: 20251027

            // Hitung berapa order yang sudah dibuat HARI INI
            $orderCountToday = Order::whereDate('created_at', now()->today())->count();

            // Urutan selanjutnya = Jumlah hari ini + 1
            $nextSequence = $orderCountToday + 1;

            // Format 4 digit (misal: 1 jadi 0001, 15 jadi 0015)
            $sequenceStr = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

            // Gabungkan: NB-20251027-0001
            $newOrderNumber = 'NB-' . $today . '-' . $sequenceStr;
            // ===========================================

            // Buat Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'store_id' => $storeId,

                // Gunakan nomor order yang baru digenerate
                'order_number' => $newOrderNumber,

                'total_amount' => $totalPrice,
                'sub_total' => $subtotal,
                'shipping_cost' => $request->shipping_cost,
                'status' => 'pending',
                'payment_status' => 'pending',
                'recipient_name' => Auth::user()->name,
                'recipient_phone' => Auth::user()->phone ?? '-',
                'shipping_address' => Auth::user()->address ?? '-',
                'notes' => 'Kurir: ' . $request->shipping_courier,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'total' => $item->quantity * $item->product->price,
                ]);
            }

            Cart::whereIn('id', $selectedItemIds)->delete();

            DB::commit();

            return redirect()->route('payment.show', $order->id);
        } catch (\Exception $e) {
            DB::rollback();
            // Kembalikan error agar mudah didebug
            return back()->with('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }
}
