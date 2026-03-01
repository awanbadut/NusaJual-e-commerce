@extends('layouts.seller')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')
@section('page-subtitle', 'Lengkapi informasi produk agar siap ditampilkan dan dijual')

@section('content')
<div class="max-w-5xl mx-auto px-2 sm:px-0">

    <div class="mb-4 md:mb-6">
        <nav class="flex text-[10px] md:text-sm text-gray-600 font-medium">
            <a href="{{ route('seller.products.index') }}" class="hover:text-green-800">Produk</a>
            <span class="mx-1.5 md:mx-2">›</span>
            <span class="text-gray-900 font-bold">Tambah Produk</span>
        </nav>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-3 md:p-4 mb-4 md:mb-6">
        <ul class="list-disc list-inside text-xs md:text-sm text-red-800 space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-5 md:gap-6">

                <div class="md:col-span-2">
                    <label class="block text-xs md:text-sm font-bold text-gray-900 mb-2 md:mb-3">Gambar Produk</label>

                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 md:p-6 text-center hover:border-green-600 transition cursor-pointer bg-[#FAFAFA]"
                        id="mainUploadArea">
                        <input type="file" name="images[]" id="mainImageInput" class="hidden" accept="image/*" multiple>

                        <div id="mainUploadContent">
                            <svg class="mx-auto h-8 w-8 md:h-12 md:w-12 text-green-600 mb-2 md:mb-3" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            <p class="text-xs md:text-sm text-gray-700 font-bold mb-0.5 md:mb-1">
                                Klik upload <span class="hidden sm:inline font-medium text-gray-500">atau seret
                                    file</span>
                            </p>
                            <p class="text-[9px] md:text-xs text-gray-500">SVG, PNG, JPG (MAX. 800x800px)</p>
                        </div>
                        <div id="mainPreview" class="hidden">
                            <img src="" alt="Preview" class="max-h-32 md:max-h-48 mx-auto rounded-lg shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 md:gap-3 mt-2 md:mt-3">
                        @for($i = 1; $i <= 3; $i++) <div
                            class="border-2 border-dashed border-gray-300 rounded-lg aspect-square flex items-center justify-center hover:border-green-600 transition cursor-pointer relative bg-[#FAFAFA]"
                            data-upload-slot="{{ $i }}">
                            <input type="file" name="images[]" class="hidden additional-image-input" accept="image/*">
                            <span class="text-2xl md:text-3xl text-gray-400 font-light">+</span>
                            <img src="" alt="Preview"
                                class="hidden absolute inset-0 w-full h-full object-cover rounded-lg image-preview shadow-sm">
                    </div>
                    @endfor
                </div>
            </div>

            <div class="md:col-span-3 space-y-3 md:space-y-4">

                <div>
                    <label class="block text-xs md:text-sm font-bold text-gray-900 mb-1.5 md:mb-2">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-xs md:text-sm @error('name') border-red-500 @enderror"
                        placeholder="Contoh : Nienow-Heidenreich" required>
                </div>

                <div>
                    <label class="block text-xs md:text-sm font-bold text-gray-900 mb-1.5 md:mb-2">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent resize-none text-xs md:text-sm"
                        placeholder="Deskripsi dan detail produk">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-3 md:gap-4">
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-gray-900 mb-1.5 md:mb-2">Kategori</label>
                        <div class="relative">
                            <select name="category_id"
                                class="w-full px-3 py-2 md:px-4 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none bg-white text-xs md:text-sm @error('category_id') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' :
                                    '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <x-heroicon-m-chevron-down
                                class="w-4 h-4 text-gray-400 absolute right-3 top-2.5 md:top-3 pointer-events-none" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-bold text-gray-900 mb-1.5 md:mb-2">Satuan</label>
                        <div class="relative">
                            <select name="unit"
                                class="w-full px-3 py-2 md:px-4 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none bg-white text-xs md:text-sm @error('unit') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Satuan</option>
                                <option value="Kg" {{ old('unit')=='Kg' ? 'selected' : '' }}>Kg</option>
                                <option value="Gr" {{ old('unit')=='Gr' ? 'selected' : '' }}>Gr</option>
                                <option value="Pcs" {{ old('unit')=='Pcs' ? 'selected' : '' }}>Pcs</option>
                                <option value="Pack" {{ old('unit')=='Pack' ? 'selected' : '' }}>Pack</option>
                            </select>
                            <x-heroicon-m-chevron-down
                                class="w-4 h-4 text-gray-400 absolute right-3 top-2.5 md:top-3 pointer-events-none" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-gray-900 mb-1.5 md:mb-2">Harga</label>
                        <div class="relative flex items-center">
                            <span
                                class="absolute left-3 text-gray-500 text-xs md:text-sm font-medium pointer-events-none">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}"
                                class="w-full pl-9 pr-3 py-2 md:pl-10 md:pr-4 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-xs md:text-sm font-bold @error('price') border-red-500 @enderror"
                                placeholder="0" min="0" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-bold text-gray-900 mb-1.5 md:mb-2">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock') }}"
                            class="w-full px-3 py-2 md:px-4 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-xs md:text-sm @error('stock') border-red-500 @enderror"
                            placeholder="0" min="0" required>
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-bold text-gray-900 mb-1.5 md:mb-2">Berat
                            (Gram)</label>
                        <div class="relative flex items-center">
                            <input type="number" name="weight" value="{{ old('weight') }}"
                                class="w-full pl-3 pr-14 py-2 md:pl-4 md:pr-16 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-xs md:text-sm @error('weight') border-red-500 @enderror"
                                placeholder="1000" min="1" required>
                            <span
                                class="absolute right-3 text-gray-500 text-[10px] md:text-xs font-bold pointer-events-none">
                                gram
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs md:text-sm font-bold text-gray-900 mb-2 md:mb-3">Status Produk</label>
                    <div class="flex items-center gap-4 md:gap-6 bg-[#FAFAFA] p-3 rounded-lg border border-gray-100">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="active" {{ old('status', 'active' )=='active'
                                ? 'checked' : '' }}
                                class="w-4 h-4 md:w-5 md:h-5 text-[#0F4C20] focus:ring-[#0F4C20] focus:ring-2">
                            <span class="ml-2 text-xs md:text-sm font-bold text-gray-900">Aktif</span>
                        </label>

                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="draft" {{ old('status')=='draft' ? 'checked' : ''
                                }} class="w-4 h-4 md:w-5 md:h-5 text-[#0F4C20] focus:ring-[#0F4C20] focus:ring-2">
                            <span class="ml-2 text-xs md:text-sm font-medium text-gray-900">Draft</span>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        <div
            class="flex flex-col-reverse sm:flex-row justify-end gap-2 md:gap-3 mt-5 md:mt-6 pt-4 md:pt-6 border-t border-gray-100">
            <a href="{{ route('seller.products.index') }}"
                class="w-full sm:w-auto text-center px-4 py-2.5 md:px-5 md:py-2.5 border border-gray-300 rounded-lg text-xs md:text-sm text-gray-700 font-bold hover:bg-gray-50 transition shadow-sm">
                Batal
            </a>
            <button type="submit"
                class="w-full sm:w-auto text-center px-4 py-2.5 md:px-5 md:py-2.5 bg-[#0F4C20] text-white rounded-lg hover:bg-[#0b3a18] transition text-xs md:text-sm font-bold shadow-md">
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