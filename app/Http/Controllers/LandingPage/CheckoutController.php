<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    // 1. Menampilkan Halaman Checkout (Review)
    public function review(Request $request)
    {
        $user = Auth::user();

        if ($request->isMethod('get') || !$request->has('selected_items')) {
            return redirect()->route('keranjang')->with('error', 'Silakan pilih produk terlebih dahulu sebelum checkout.');
        }

        $selectedItems = $request->selected_items;
        if (is_string($selectedItems)) {
            $selectedItems = explode(',', $selectedItems);
        }

        $cartItems = Cart::with(['product.store'])->whereIn('id', $selectedItems)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang')->with('error', 'Pilih minimal satu produk.');
        }

        // ✅ FIX: Validasi stok sebelum checkout
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('keranjang')->with(
                    'error',
                    "Stok {$item->product->name} tidak mencukupi. Stok tersedia: {$item->product->stock}"
                );
            }
        }

        $primaryAddress = Address::where('user_id', $user->id)->where('is_primary', true)->first();
        if (!$primaryAddress) {
            $primaryAddress = Address::where('user_id', $user->id)->latest()->first();
        }

        $couriers = [];
        $origin = null;
        $destination = null;
        $totalWeight = 0;

        if ($primaryAddress && $primaryAddress->village_code) {
            $firstProduct = $cartItems->first()->product;
            $store = $firstProduct->store ?? null;

            if ($store && $store->village_code) {
                $origin = $store->village_code;
                $destination = $primaryAddress->village_code;

                $totalWeightGram = $cartItems->sum(function ($item) {
                    $weight = $item->product->weight > 0 ? $item->product->weight : 1000;
                    return $weight * $item->quantity;
                });

                $weightInKg = max(1, ceil($totalWeightGram / 1000));

                try {
                    $apiKey = env('API_CO_ID_KEY');

                    if ($apiKey) {
                        $response = Http::withHeaders([
                            'x-api-co-id' => $apiKey,
                        ])->get('https://use.api.co.id/expedition/shipping-cost', [
                            'origin_village_code'      => $origin,
                            'destination_village_code' => $destination,
                            'weight'                   => $weightInKg,
                        ]);

                        if ($response->successful()) {
                            $apiData = $response->json();
                            $results = $apiData['data']['couriers'] ?? [];

                            foreach ($results as $courier) {
                                if (isset($courier['price']) && $courier['price'] > 0) {
                                    $couriers[] = [
                                        'id'    => $courier['courier_code'],
                                        'name'  => $courier['courier_name'],
                                        'etd'   => $courier['estimation'] ? $courier['estimation'] : '-',
                                        'price' => $courier['price']
                                    ];
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Log error
                }
            }
        }

        if (empty($couriers)) {
            $couriers = [
                ['id' => 'manual', 'name' => 'Kurir Toko (Manual)', 'etd' => '3-7 Hari', 'price' => 25000],
            ];
        }

        $subTotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('checkout', compact('cartItems', 'subTotal', 'couriers', 'primaryAddress'));
    }

    // 2. Memproses Simpan Order
    public function store(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|string',
            'shipping_cost' => 'required|numeric',
            'shipping_courier' => 'required|string',
            'address_id' => 'required|exists:addresses,id',
            'notes' => 'nullable|string|max:500', // ✅ TAMBAHKAN VALIDASI NOTES
        ]);

        $selectedItemIds = explode(',', $request->selected_items);
        $cartItems = Cart::with('product')->whereIn('id', $selectedItemIds)->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Terjadi kesalahan, item tidak ditemukan.');
        }

        // ✅ Validasi stok lagi sebelum create order
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with(
                    'error',
                    "Stok {$item->product->name} tidak mencukupi. Stok tersedia: {$item->product->stock}"
                );
            }
        }

        $shippingAddress = Address::find($request->address_id);

        $fullAddressString = $shippingAddress->detail_address . ', ' .
            $shippingAddress->village_name . ', ' .
            $shippingAddress->district_name . ', ' .
            $shippingAddress->city_name . ', ' .
            $shippingAddress->province_name . ' ' .
            $shippingAddress->postal_code;

        $storeId = $cartItems->first()->product->store_id;
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $totalPrice = $subtotal + $request->shipping_cost;

        try {
            DB::beginTransaction();

            // Generate Order Number
            $today = now()->format('Ymd');
            $orderCountToday = Order::whereDate('created_at', now()->today())->count();
            $nextSequence = $orderCountToday + 1;
            $newOrderNumber = 'NB-' . $today . '-' . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

            // ✅ FIX: Buat Order - SIMPAN COURIER DI KOLOM courier
            $order = Order::create([
                'user_id' => Auth::id(),
                'store_id' => $storeId,
                'order_number' => $newOrderNumber,
                'total_amount' => $totalPrice,
                'sub_total' => $subtotal,
                'shipping_cost' => $request->shipping_cost,
                'courier' => $request->shipping_courier, // ✅ SAVE COURIER
                'status' => 'pending',
                'payment_status' => 'pending',
                'recipient_name' => $shippingAddress->receiver_name,
                'recipient_phone' => $shippingAddress->phone,
                'shipping_address' => $fullAddressString,
                'notes' => $request->notes, // ✅ NOTES SEKARANG OPTIONAL
            ]);

            // Simpan order items & KURANGI STOK
            foreach ($cartItems as $item) {
                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'total' => $item->quantity * $item->product->price,
                ]);

                // KURANGI STOK PRODUK
                $product = Product::find($item->product_id);
                $product->decrement('stock', $item->quantity);
            }

            // Hapus dari cart
            Cart::whereIn('id', $selectedItemIds)->delete();

            DB::commit();

            return redirect()->route('payment.show', $order->id);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }
}
