<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Store;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Kategori (Tetap)
        $categories = Category::withCount('products')->limit(5)->get();

        // 2. Mitra/Toko (DIUBAH)
        // Ambil 8 mitra dengan produk terbanyak
        $stores = Store::with('user')
            ->withCount('products')
            ->orderByDesc('products_count') // Urutkan dari produk terbanyak
            ->take(8) // Ambil 8 saja untuk ditampilkan
            ->get();

        // Hitung total seluruh mitra untuk mengetahui sisanya
        $totalStores = Store::count();
        // Hitung sisa (Total - 8). Jika hasilnya minus (karena toko < 8), jadikan 0.
        $sisaMitra = max(0, $totalStores - 8);

        // 3. Produk (Tetap)
        $products = Product::with(['category', 'store', 'primaryImage'])
            ->where('status', 'active')
            ->latest()
            ->limit(4)
            ->get();

        // Jangan lupa kirim $sisaMitra ke view
        return view('welcome', compact('categories', 'stores', 'products', 'sisaMitra'));
    }
}
