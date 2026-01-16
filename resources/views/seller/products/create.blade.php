@extends('layouts.seller')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')
@section('page-subtitle', 'Lengkapi informasi produk agar siap ditampilkan dan dijual')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm text-gray-600">
            <a href="{{ route('seller.products.index') }}" class="hover:text-green-800">Produk</a>
            <span class="mx-2">›</span>
            <span class="text-gray-900 font-medium">Tambah Produk</span>
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
    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                
                <!-- Left Column: Upload Gambar (2 kolom) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Gambar Produk</label>
                    
                    <!-- Main Upload Area -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-600 transition cursor-pointer" id="mainUploadArea">
                        <input type="file" name="images[]" id="mainImageInput" class="hidden" accept="image/*" multiple>
                        <div id="mainUploadContent">
                            <svg class="mx-auto h-12 w-12 text-green-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-sm text-gray-700 font-medium mb-1">Click to upload <span class="text-gray-500">or drag and drop</span></p>
                            <p class="text-xs text-gray-500">SVG, PNG, JPG (MAX. 800x800px)</p>
                        </div>
                        <div id="mainPreview" class="hidden">
                            <img src="" alt="Preview" class="max-h-48 mx-auto rounded-lg">
                        </div>
                    </div>

                    <!-- Additional Images (3 slots) -->
                    <div class="grid grid-cols-3 gap-3 mt-3">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="border-2 border-dashed border-gray-300 rounded-lg aspect-square flex items-center justify-center hover:border-green-600 transition cursor-pointer relative" data-upload-slot="{{ $i }}">
                            <input type="file" name="images[]" class="hidden additional-image-input" accept="image/*">
                            <span class="text-3xl text-gray-400 font-light">+</span>
                            <img src="" alt="Preview" class="hidden absolute inset-0 w-full h-full object-cover rounded-lg image-preview">
                        </div>
                        @endfor
                    </div>
                </div>

                <!-- Right Column: Form Fields (3 kolom) -->
                <div class="md:col-span-3 space-y-4">
                    <!-- Nama Produk -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Nama Produk</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-sm @error('name') border-red-500 @enderror" 
                            placeholder="Contoh : Nienow-Heidenreich"
                            required>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Deskripsi</label>
                        <textarea 
                            name="description" 
                            rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent resize-none text-sm"
                            placeholder="Deskripsi dan detail produk">{{ old('description') }}</textarea>
                    </div>

                    <!-- Kategori & Satuan -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Kategori</label>
                            <select 
                                name="category_id" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none bg-white text-sm @error('category_id') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Satuan</label>
                            <select 
                                name="unit" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none bg-white text-sm @error('unit') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Satuan</option>
                                <option value="Kg" {{ old('unit') == 'Kg' ? 'selected' : '' }}>Kg</option>
                                <option value="Gr" {{ old('unit') == 'Gr' ? 'selected' : '' }}>Gr</option>
                                <option value="Pcs" {{ old('unit') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                <option value="Pack" {{ old('unit') == 'Pack' ? 'selected' : '' }}>Pack</option>
                            </select>
                        </div>
                    </div>

                    <!-- Harga & Stok -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Harga</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                <input 
                                    type="number" 
                                    name="price" 
                                    value="{{ old('price') }}"
                                    class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-sm @error('price') border-red-500 @enderror" 
                                    placeholder="0"
                                    min="0"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Stok</label>
                            <input 
                                type="number" 
                                name="stock" 
                                value="{{ old('stock') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-sm @error('stock') border-red-500 @enderror" 
                                placeholder="0"
                                min="0"
                                required>
                        </div>
                    </div>

                    <!-- Status Produk -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Status Produk</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="status" 
                                    value="active" 
                                    {{ old('status', 'active') == 'active' ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-600 focus:ring-green-600 focus:ring-2">
                                <span class="ml-2 text-sm text-gray-900">Aktif</span>
                            </label>

                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="status" 
                                    value="draft" 
                                    {{ old('status') == 'draft' ? 'checked' : '' }}
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
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium">
                    Simpan Produk
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Main upload area
    const mainUploadArea = document.getElementById('mainUploadArea');
    const mainImageInput = document.getElementById('mainImageInput');
    const mainUploadContent = document.getElementById('mainUploadContent');
    const mainPreview = document.getElementById('mainPreview');

    mainUploadArea.addEventListener('click', () => {
        mainImageInput.click();
    });

    mainImageInput.addEventListener('change', (e) => {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                mainPreview.querySelector('img').src = e.target.result;
                mainUploadContent.classList.add('hidden');
                mainPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Additional upload slots
    document.querySelectorAll('[data-upload-slot]').forEach(slot => {
        const input = slot.querySelector('.additional-image-input');
        const preview = slot.querySelector('.image-preview');

        slot.addEventListener('click', () => {
            input.click();
        });

        input.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    slot.querySelector('span').classList.add('hidden');
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    });

    // Drag and drop
    mainUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        mainUploadArea.classList.add('border-green-600', 'bg-green-50');
    });

    mainUploadArea.addEventListener('dragleave', () => {
        mainUploadArea.classList.remove('border-green-600', 'bg-green-50');
    });

    mainUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        mainUploadArea.classList.remove('border-green-600', 'bg-green-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            mainImageInput.files = files;
            const event = new Event('change');
            mainImageInput.dispatchEvent(event);
        }
    });
</script>
@endpush
@endsection
