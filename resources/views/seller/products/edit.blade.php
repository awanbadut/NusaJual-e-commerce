@extends('layouts.seller')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')
@section('page-subtitle', 'Update informasi produk')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm text-gray-600">
            <a href="{{ route('seller.products.index') }}" class="hover:text-green-800">Produk</a>
            <span class="mx-2">›</span>
            <span class="text-gray-900 font-medium">Edit Produk</span>
        </nav>
    </div>

    <!-- Alert Error -->
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <ul class="list-disc list-inside text-sm text-red-800 space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

                <!-- Left Column: Upload Gambar (2 kolom) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Gambar Produk</label>

                    <!-- Gambar Existing dengan tombol hapus -->
                    @if($product->images->count() > 0)
                    <div class="mb-4">
                        <p class="text-xs text-gray-600 mb-2">Gambar saat ini:</p>
                        <div class="grid grid-cols-4 gap-2" id="existingImages">
                            @foreach($product->images as $image)
                            <div class="relative group image-item" data-image-id="{{ $image->id }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                    class="w-full h-20 object-cover rounded-lg border-2 border-gray-200">

                                @if($image->is_primary)
                                <span
                                    class="absolute top-1 left-1 bg-green-600 text-white text-xs px-1.5 py-0.5 rounded text-[10px]">Primary</span>
                                @endif

                                <!-- Tombol Hapus -->
                                <button type="button" onclick="deleteImage({{ $image->id }})"
                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition hover:bg-red-600"
                                    title="Hapus gambar">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Main Upload Area -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-600 transition cursor-pointer"
                        id="mainUploadArea">
                        <input type="file" name="images[]" id="mainImageInput" class="hidden" accept="image/*" multiple>
                        <div id="mainUploadContent">
                            <svg class="mx-auto h-12 w-12 text-green-600 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            <p class="text-sm text-gray-700 font-medium mb-1">Upload gambar baru</p>
                            <p class="text-xs text-gray-500">SVG, PNG, JPG (MAX. 800x800px)</p>
                        </div>
                        <div id="previewContainer" class="hidden grid grid-cols-4 gap-2"></div>
                    </div>
                </div>

                <!-- Right Column: Form Fields (3 kolom) -->
                <div class="md:col-span-3 space-y-4">
                    <!-- Nama Produk -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-sm @error('name') border-red-500 @enderror"
                            required>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent resize-none text-sm"
                            placeholder="Deskripsi dan detail produk">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Kategori & Satuan -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Kategori</label>
                            <select name="category_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none bg-white text-sm"
                                required>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) ==
                                    $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Satuan</label>
                            <select name="unit"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none bg-white text-sm"
                                required>
                                <option value="Kg" {{ old('unit', $product->unit) == 'Kg' ? 'selected' : '' }}>Kg
                                </option>
                                <option value="Gr" {{ old('unit', $product->unit) == 'Gr' ? 'selected' : '' }}>Gr
                                </option>
                                <option value="Pcs" {{ old('unit', $product->unit) == 'Pcs' ? 'selected' : '' }}>Pcs
                                </option>
                                <option value="Pack" {{ old('unit', $product->unit) == 'Pack' ? 'selected' : '' }}>Pack
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Harga & Stok & Weight -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Harga</label>
                            <div class="relative flex items-center"> <span
                                    class="absolute left-4 text-gray-500 text-sm pointer-events-none">
                                    Rp
                                </span>
                                <input type="number" name="price" value="{{ old('price', $product->price) }}"
                                    class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-sm @error('price') border-red-500 @enderror"
                                    min="0" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Stok</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-sm @error('stock') border-red-500 @enderror"
                                min="0" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Berat (Gram)</label>
                            <div class="relative flex items-center"> <input type="number" name="weight"
                                    value="{{ old('weight', $product->weight) }}"
                                    class="w-full pl-4 pr-16 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-sm @error('weight') border-red-500 @enderror"
                                    placeholder="1000" min="1" required>
                                <span class="absolute right-4 text-gray-500 text-xs pointer-events-none">
                                    gram
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Status Produk -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Status Produk</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="active" {{ old('status', $product->status) ==
                                'active' ? 'checked' : '' }}
                                class="w-4 h-4 text-green-600 focus:ring-green-600 focus:ring-2">
                                <span class="ml-2 text-sm text-gray-900">Aktif</span>
                            </label>

                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="draft" {{ old('status', $product->status) ==
                                'draft' ? 'checked' : '' }}
                                class="w-4 h-4 text-green-600 focus:ring-green-600 focus:ring-2">
                                <span class="ml-2 text-sm text-gray-900">Draft</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                <a href="{{ route('seller.products.index') }}"
                    class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium">
                    Update Produk
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Upload preview untuk gambar baru
    const mainUploadArea = document.getElementById('mainUploadArea');
    const mainImageInput = document.getElementById('mainImageInput');
    const mainUploadContent = document.getElementById('mainUploadContent');
    const previewContainer = document.getElementById('previewContainer');

    mainUploadArea.addEventListener('click', () => {
        mainImageInput.click();
    });

    mainImageInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            previewContainer.innerHTML = '';
            mainUploadContent.classList.add('hidden');
            previewContainer.classList.remove('hidden');

            Array.from(e.target.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg border-2 border-green-500">
                        <span class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded text-[10px]">New</span>
                    `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    });

    // Fungsi hapus gambar
    function deleteImage(imageId) {
        if (!confirm('Yakin ingin menghapus gambar ini?')) {
            return;
        }

        fetch(`/seller/products/${imageId}/image`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`[data-image-id="${imageId}"]`).remove();
                alert('Gambar berhasil dihapus!');
            } else {
                alert('Gagal menghapus gambar!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan!');
        });
    }
</script>
@endpush
@endsection