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
use App\Models\Address;

class CheckoutController extends Controller
{
    // 1. Menampilkan Halaman Checkout (Review)
    public function review(Request $request)
    {
        $user = Auth::user();
        if ($request->isMethod('get') || !$request->has('selected_items')) {
            return redirect()->route('keranjang')->with('error', 'Silakan pilih produk terlebih dahulu sebelum checkout.');
        }

        // Validasi
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:carts,id'
        ]);

        // Ambil data item keranjang
        $cartItems = Cart::with('product')->whereIn('id', $request->selected_items)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang')->with('error', 'Pilih minimal satu produk.');
        }

        // Hitung Subtotal
        $subTotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // Ambil Alamat Utama
        $primaryAddress = Address::where('user_id', $user->id)
            ->where('is_primary', true)
            ->first();

        // Fallback jika tidak ada utama
        if (!$primaryAddress) {
            $primaryAddress = Address::where('user_id', $user->id)
                ->latest()
                ->first();
        }

        // Data dummy ongkir
        $couriers = [
            ['id' => 'jne', 'name' => 'JNE Reguler', 'etd' => '5-7 Hari', 'price' => 45000],
            ['id' => 'jnt', 'name' => 'JNT Express', 'etd' => '3-5 Hari', 'price' => 50000],
            ['id' => 'sicepat', 'name' => 'Sicepat', 'etd' => '7-14 Hari', 'price' => 20000],
            ['id' => 'sikilat', 'name' => 'Sikilat', 'etd' => '7-10 Hari', 'price' => 25000],
        ];

        // PERBAIKAN UTAMA DISINI: Tambahkan 'primaryAddress'
        return view('checkout', compact('cartItems', 'subTotal', 'couriers', 'primaryAddress'));
    }

    // 2. Memproses Simpan Order
    public function store(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|string',
            'shipping_cost' => 'required|numeric',
            'shipping_courier' => 'required|string',
            'address_id' => 'required|exists:addresses,id', // Validasi ID Alamat
        ]);

        $selectedItemIds = explode(',', $request->selected_items);
        $cartItems = Cart::with('product')->whereIn('id', $selectedItemIds)->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Terjadi kesalahan, item tidak ditemukan.');
        }

        // Ambil data alamat yang digunakan untuk transaksi ini
        $shippingAddress = Address::find($request->address_id);

        // Format string alamat lengkap untuk disimpan di tabel order (snapshot)
        $fullAddressString = $shippingAddress->detail_address . ', ' .
            $shippingAddress->village_name . ', ' .
            $shippingAddress->district_name . ', ' .
            $shippingAddress->city_name . ', ' .
            $shippingAddress->province_name . ' ' .
            $shippingAddress->postal_code;

        $storeId = $cartItems->first()->product->store_id;
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $totalPrice = $subtotal + 1000 + $request->shipping_cost;

        try {
            DB::beginTransaction();

            // Generate Order Number
            $today = now()->format('Ymd');
            $orderCountToday = Order::whereDate('created_at', now()->today())->count();
            $nextSequence = $orderCountToday + 1;
            $newOrderNumber = 'NB-' . $today . '-' . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

            // Buat Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'store_id' => $storeId,
                'order_number' => $newOrderNumber,
                'total_amount' => $totalPrice,
                'sub_total' => $subtotal,
                'shipping_cost' => $request->shipping_cost,
                'status' => 'pending',
                'payment_status' => 'pending',

                // [FIX] Gunakan data dari Tabel Address, bukan Auth::user()
                'recipient_name' => $shippingAddress->receiver_name,
                'recipient_phone' => $shippingAddress->phone,
                'shipping_address' => $fullAddressString, // Simpan alamat lengkap

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
            return back()->with('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }
}
