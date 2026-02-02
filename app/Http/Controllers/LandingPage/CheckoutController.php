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
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    // 1. Menampilkan Halaman Checkout (Review)
    public function review(Request $request)
    {
        $user = Auth::user();

        // 1. Cek User Pilih Barang atau Tidak
        if ($request->isMethod('get') || !$request->has('selected_items')) {
            return redirect()->route('keranjang')->with('error', 'Silakan pilih produk terlebih dahulu sebelum checkout.');
        }

        // 2. Parsing Item ID
        $selectedItems = $request->selected_items;
        if (is_string($selectedItems)) {
            $selectedItems = explode(',', $selectedItems);
        }

        // 3. Ambil Data Cart + Produk + Toko
        $cartItems = Cart::with(['product.store'])->whereIn('id', $selectedItems)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang')->with('error', 'Pilih minimal satu produk.');
        }

        // 4. Ambil Alamat Tujuan (User)
        $primaryAddress = Address::where('user_id', $user->id)->where('is_primary', true)->first();
        if (!$primaryAddress) {
            $primaryAddress = Address::where('user_id', $user->id)->latest()->first();
        }

        // Inisialisasi variabel
        $couriers = [];
        $origin = null;
        $destination = null;
        $totalWeight = 0;

        // --- LOGIKA HITUNG ONGKIR API.CO.ID ---

        // Cek 1: User punya alamat & punya Kode Kelurahan?
        if ($primaryAddress && $primaryAddress->village_code) {

            // Cek 2: Ambil Toko
            $firstProduct = $cartItems->first()->product;
            $store = $firstProduct->store ?? null;

            // Cek 3: Toko punya Kode Kelurahan?
            if ($store && $store->village_code) {

                $origin = $store->village_code;
                $destination = $primaryAddress->village_code;

                // Cek 4: Hitung Total Berat (Min 1 Kg)
                $totalWeightGram = $cartItems->sum(function ($item) {
                    $weight = $item->product->weight > 0 ? $item->product->weight : 1000;
                    return $weight * $item->quantity;
                });

                $weightInKg = max(1, ceil($totalWeightGram / 1000));

                // Cek 5: Request ke API.co.id
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
                            // Ambil array 'couriers' dari dalam 'data'
                            $results = $apiData['data']['couriers'] ?? [];

                            foreach ($results as $courier) {
                                // Filter: Hanya ambil kurir yang harganya masuk akal (opsional)
                                if (isset($courier['price']) && $courier['price'] > 0) {
                                    $couriers[] = [
                                        // ID unik untuk radio button
                                        'id'    => $courier['courier_code'],
                                        // Nama Tampilan: JNE Express, JNE Cargo, dll
                                        'name'  => $courier['courier_name'],
                                        // Estimasi: Jika null, tampilkan '-'
                                        'etd'   => $courier['estimation'] ? $courier['estimation'] : '-',
                                        // Harga
                                        'price' => $courier['price']
                                    ];
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Log error jika perlu: \Log::error($e->getMessage());
                    // Biarkan kosong, nanti masuk fallback
                }
            }
        }

        // Fallback jika API Gagal / Data tidak lengkap
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
