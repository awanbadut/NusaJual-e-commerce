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
        // Query Dasar dengan eager load untuk performa
        $query = Product::with([
            'category', 
            'primaryImage',
            'store',
            'orderItems' => function($q) {
                $q->whereHas('order', function($order) {
                    $order->where('status', 'completed');
                });
            }
        ])->where('status', 'active');

        // 1. Filter Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Filter Kategori (Checkbox)
        if ($request->filled('category')) {
            $categories = is_array($request->category) ? $request->category : [$request->category];
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

        // Hitung total terjual untuk setiap produk (sudah eager load)
        $products->getCollection()->transform(function($product) {
            $product->total_sold = $product->orderItems->sum('quantity');
            return $product;
        });

        // Ambil List Kategori untuk Sidebar
        $categoriesList = Category::where('is_active', true)->orderBy('name')->get();

        return view('catalog', compact('products', 'categoriesList'));
    }

    // Method Detail Produk
    public function show($id)
    {
        // 1. Ambil detail produk
        $product = Product::with(['store.user', 'category', 'images'])
            ->where('status', 'active')
            ->findOrFail($id);

        // 2. Produk Terkait
        $relatedProducts = Product::with(['primaryImage', 'store'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // Fallback: Jika tidak ada produk sekategori
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
