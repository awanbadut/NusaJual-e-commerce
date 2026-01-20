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
        // Ambil keranjang user login
        $cartItems = Cart::with(['product.store', 'product.primaryImage', 'product.category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Grouping berdasarkan ID Toko
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

        // Cek apakah produk sudah ada di keranjang user ini?
        $cart = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            // Jika ada, tambahkan quantity-nya
            $cart->quantity += $request->qty;
            $cart->save();
        } else {
            // Jika belum ada, buat baru
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->qty
            ]);
        }

        return redirect()->route('keranjang')->with('success', 'Produk berhasil masuk keranjang!');
    }

    // Update Quantity (via AJAX atau Form nanti)
    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->update(['quantity' => $request->quantity]);

        return back();
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
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
