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
    // =========================================================
    // HELPER: Kompresi & simpan gambar pakai GD
    // =========================================================
    private function compressAndSave($uploadedFile, string $savePath, int $maxWidth = 1200, int $quality = 82): void
    {
        if (!file_exists(dirname($savePath))) {
            mkdir(dirname($savePath), 0755, true);
        }

        $tmp  = $uploadedFile->getRealPath();
        $mime = mime_content_type($tmp);

        $src = match (true) {
            str_contains($mime, 'png')  => @imagecreatefrompng($tmp),
            str_contains($mime, 'webp') => @imagecreatefromwebp($tmp),
            str_contains($mime, 'gif')  => @imagecreatefromgif($tmp),
            default                     => @imagecreatefromjpeg($tmp),
        };

        // Fallback: copy langsung jika GD gagal baca
        if (!$src) {
            copy($tmp, $savePath);
            return;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);

        // Flatten transparansi ke background putih
        $flat = imagecreatetruecolor($origW, $origH);
        $white = imagecolorallocate($flat, 255, 255, 255);
        imagefill($flat, 0, 0, $white);
        imagecopy($flat, $src, 0, 0, 0, 0, $origW, $origH);
        imagedestroy($src);

        // Scale down jika lebih besar dari maxWidth
        if ($origW > $maxWidth) {
            $newW = $maxWidth;
            $newH = (int) round($origH * $maxWidth / $origW);
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        $dst = imagecreatetruecolor($newW, $newH);
        imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
        imagecopyresampled($dst, $flat, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($flat);

        imagejpeg($dst, $savePath, $quality);
        imagedestroy($dst);
    }

    // =========================================================
    public function index(Request $request)
    {
        $store = auth()->user()->store;

        $query = Product::where('store_id', $store->id)
            ->with(['category', 'primaryImage']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products   = $query->latest()->paginate(10);
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('seller.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'weight'      => 'required|integer|min:1',
            'unit'        => 'required|string',
            'status'      => 'required|in:active,draft',
            'images'      => 'nullable|array|max:4',
            'images.*'    => 'nullable|file|max:10240',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::create([
                'store_id'    => auth()->user()->store->id,
                'category_id' => $request->category_id,
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
                'stock'       => $request->stock,
                'weight'      => $request->weight,
                'unit'        => $request->unit,
                'status'      => $request->status,
            ]);

            if ($request->hasFile('images')) {
                $isPrimary = true;
                foreach ($request->file('images') as $image) {
                    if (!$image || !$image->isValid()) continue;

                    // Validasi: pastikan file adalah gambar valid
                    $imageInfo = @getimagesize($image->getRealPath());
                    if (!$imageInfo) continue;

                    $filename = 'products/' . uniqid('img_', true) . '.jpg';
                    $fullPath = storage_path('app/public/' . $filename);

                    $this->compressAndSave($image, $fullPath, 1200, 82);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $filename,
                        'is_primary'  => $isPrimary,
                    ]);

                    $isPrimary = false;
                }
            }

            DB::commit();

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $product = Product::with(['category', 'images'])
            ->where('store_id', auth()->user()->store->id)
            ->findOrFail($id);

        return view('seller.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with('images')
            ->where('store_id', auth()->user()->store->id)
            ->findOrFail($id);

        $categories = Category::where(function ($q) use ($product) {
            $q->where('is_active', true)
              ->orWhere('id', $product->category_id);
        })->orderBy('name')->get();

        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('store_id', auth()->user()->store->id)
            ->findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'weight'      => 'required|integer|min:1',
            'unit'        => 'required|string',
            'status'      => 'required|in:active,draft',
            'images'      => 'nullable|array|max:4',
            'images.*'    => 'nullable|file|max:10240',
        ]);

        try {
            DB::beginTransaction();

            $product->update([
                'category_id' => $request->category_id,
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
                'stock'       => $request->stock,
                'weight'      => $request->weight,
                'unit'        => $request->unit,
                'status'      => $request->status,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if (!$image || !$image->isValid()) continue;

                    $imageInfo = @getimagesize($image->getRealPath());
                    if (!$imageInfo) continue;

                    $filename = 'products/' . uniqid('img_', true) . '.jpg';
                    $fullPath = storage_path('app/public/' . $filename);

                    $this->compressAndSave($image, $fullPath, 1200, 82);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $filename,
                        'is_primary'  => false,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk "' . $product->name . '" berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $product = Product::where('store_id', auth()->user()->store->id)
            ->findOrFail($id);

        try {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $name = $product->name;
            $product->delete();

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk "' . $name . '" berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus produk.']);
        }
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);

        if ($image->product->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        try {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();

            return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus gambar'], 500);
        }
    }
}