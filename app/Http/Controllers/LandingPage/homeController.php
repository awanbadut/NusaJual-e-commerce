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
        // 1. Ambil 5 Kategori (bisa diurutkan berdasarkan nama atau yang paling banyak produknya)
        $categories = Category::withCount('products')->limit(5)->get();

        // 2. Ambil 4 Mitra/Toko (Eager load user & hitung produk)
        $stores = Store::with('user')
            ->withCount('products')
            ->latest()
            ->limit(4)
            ->get();

        // 3. Ambil 4 Produk Terbaru yang statusnya active (Eager load category, store, dan primaryImage)
        $products = Product::with(['category', 'store', 'primaryImage'])
            ->where('status', 'active')
            ->latest()
            ->limit(4)
            ->get();

        return view('welcome', compact('categories', 'stores', 'products'));
    }
}
