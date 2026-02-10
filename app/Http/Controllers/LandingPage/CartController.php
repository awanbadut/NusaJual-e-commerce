<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Tampilkan Halaman Keranjang
    public function index()
    {
        $cartItems = Cart::with(['product.store', 'product.primaryImage', 'product.category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $groupedCarts = $cartItems->groupBy(function ($item) {
            return $item->product->store->id;
        });

        return view('keranjang', compact('groupedCarts', 'cartItems'));
    }

    // Tambah Barang ke Keranjang
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $product = Product::findOrFail($request->product_id);

        // Validasi stok
        if ($product->stock < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $product->stock);
        }

        $cart = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            $newQty = $cart->quantity + $request->qty;
            
            if ($product->stock < $newQty) {
                return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $product->stock);
            }
            
            $cart->quantity = $newQty;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->qty
            ]);
        }

        return redirect()->route('keranjang')->with('success', 'Produk berhasil masuk keranjang!');
    }

    // ✅ UPDATE: Fix method untuk AJAX request
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $cart = Cart::with('product')
                ->where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();
            
            // Validasi stok
            if ($cart->product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi! Stok tersedia: ' . $cart->product->stock
                ], 400);
            }

            // Update quantity
            $cart->update(['quantity' => $request->quantity]);

            // Calculate new subtotal
            $subtotal = $cart->product->price * $cart->quantity;

            // Calculate cart totals
            $allCartItems = Cart::with('product')->where('user_id', Auth::id())->get();
            $grandTotal = $allCartItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

            return response()->json([
                'success' => true,
                'message' => 'Quantity berhasil diupdate',
                'data' => [
                    'cart_id' => $cart->id,
                    'quantity' => $cart->quantity,
                    'item_subtotal' => $subtotal,
                    'item_subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                    'cart_grand_total' => $grandTotal,
                    'cart_grand_total_formatted' => 'Rp ' . number_format($grandTotal, 0, ',', '.')
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quantity minimal 1'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Hapus Semua Item
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Keranjang dikosongkan.');
    }

    // Hapus 1 Item
    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        
        if (!$cart) {
            return back()->with('error', 'Item tidak ditemukan.');
        }

        $cart->delete();
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
