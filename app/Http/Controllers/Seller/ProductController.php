<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk
     */
    public function index(Request $request)
    {
        $store = auth()->user()->store;
        
        $query = Product::where('store_id', $store->id)
            ->with(['category', 'primaryImage']);

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('seller.products.index', compact('products', 'categories'));
    }

    /**
     * Tampilkan form tambah produk
     */
    public function create()
    {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    /**
     * Simpan produk baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string',
            'status' => 'required|in:active,draft',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Buat produk
            $product = Product::create([
                'store_id' => auth()->user()->store->id,
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'unit' => $validated['unit'],
                'status' => $validated['status'],
            ]);

            // Upload gambar
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0, // Gambar pertama jadi primary
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan produk.',
            ])->withInput();
        }
    }

    /**
     * Tampilkan detail produk
     */
    public function show($id)
    {
        $product = Product::with(['category', 'images'])
            ->where('store_id', auth()->user()->store->id)
            ->findOrFail($id);

        return view('seller.products.show', compact('product'));
    }

    /**
     * Tampilkan form edit produk
     */
    public function edit($id)
    {
        $product = Product::with('images')
            ->where('store_id', auth()->user()->store->id)
            ->findOrFail($id);
        
        $categories = Category::all();

        return view('seller.products.edit', compact('product', 'categories'));
    }

    /**
     * Update produk
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('store_id', auth()->user()->store->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string',
            'status' => 'required|in:active,draft',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'unit' => $validated['unit'],
                'status' => $validated['status'],
            ]);

            // Upload gambar baru jika ada
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat mengupdate produk.',
            ])->withInput();
        }
    }

    /**
     * Hapus produk
     */
    public function destroy($id)
    {
        $product = Product::where('store_id', auth()->user()->store->id)
            ->findOrFail($id);

        try {
            // Hapus gambar dari storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $product->delete();

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menghapus produk.',
            ]);
        }
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        
        // Pastikan gambar ini milik toko yang login
        if ($image->product->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        try {
            // Hapus file dari storage
            Storage::disk('public')->delete($image->image_path);
            
            // Hapus record dari database
            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar'
            ], 500);
        }
    }
}
