<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;

class StoreController extends Controller
{
    public function show(Request $request, $id)
    {
        // 1. Ambil Data Toko
        $store = Store::with('user')->withCount('products')->findOrFail($id);

        // 2. Query Produk (Hanya milik toko ini)
        $query = Product::with(['category', 'primaryImage'])
            ->where('store_id', $id)
            ->where('status', 'active');

        // --- Logika Filter (Sama seperti Katalog, tapi scoped ke Toko ini) ---

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter Kategori
        if ($request->filled('category')) {
            $categories = is_array($request->category) ? $request->category : [$request->category];
            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('name', $categories);
            });
        }

        // Sort Harga
        if ($request->filled('sort_price')) {
            if ($request->sort_price == 'Harga Tertinggi') {
                $query->orderBy('price', 'desc');
            } elseif ($request->sort_price == 'Harga Terjangkau') {
                $query->orderBy('price', 'asc');
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(9)->withQueryString();

        // 3. List Kategori (Hanya kategori yang dimiliki oleh produk di toko ini)
        $categoriesList = Category::whereHas('products', function ($q) use ($id) {
            $q->where('store_id', $id)->where('status', 'active');
        })->pluck('name');

        return view('profilMitra', compact('store', 'products', 'categoriesList'));
    }
}
