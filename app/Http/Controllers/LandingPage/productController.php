<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Query Dasar
        $query = Product::with(['category', 'primaryImage'])
            ->where('status', 'active');

        // 1. Filter Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Filter Kategori (Checkbox)
        // Menerima array category[] dari checkbox, atau single slug dari link menu
        if ($request->filled('category')) {
            $categories = is_array($request->category) ? $request->category : [$request->category];

            // Jika input berupa slug, cari ID-nya dulu, atau filter by category name
            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('name', $categories)->orWhereIn('slug', $categories);
            });
        }

        // 3. Filter Harga (Sort)
        if ($request->filled('sort_price')) {
            if ($request->sort_price == 'Harga Tertinggi') {
                $query->orderBy('price', 'desc');
            } elseif ($request->sort_price == 'Harga Terjangkau') {
                $query->orderBy('price', 'asc');
            }
        } else {
            // Default sort: Terbaru
            $query->latest();
        }

        // Ambil Data Produk (Pagination)
        $products = $query->paginate(9)->withQueryString();
        // Ambil List Kategori untuk Sidebar (yang punya produk saja biar rapi)
        $categoriesList = Category::has('products')->pluck('name');

        return view('catalog', compact('products', 'categoriesList'));
    }

    // Method Detail Produk (Persiapan nanti)
    public function show($id)
    {
        // 1. Ambil detail produk
        $product = Product::with(['store.user', 'category', 'images'])
            ->where('status', 'active')
            ->findOrFail($id);

        // 2. Coba Ambil Produk Terkait (Satu Kategori)
        $relatedProducts = Product::with(['primaryImage', 'store'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // Hindari produk yang sedang dibuka
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // [BARU] Fallback: Jika tidak ada produk sekategori, ambil produk acak dari kategori lain
        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::with(['primaryImage', 'store'])
                ->where('id', '!=', $product->id)
                ->where('status', 'active')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        return view('produkDetail', compact('product', 'relatedProducts'));
    }
}
