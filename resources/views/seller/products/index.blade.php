@extends('layouts.seller')

@section('title', 'Etalase Produk')
@section('page-title', 'Etalase Produk')
@section('page-subtitle', 'Semua produk yang sedang Anda tawarkan di Nusa Belanja')

@section('content')
<div class="space-y-6">
    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <!-- Search -->
            <div class="flex-1 relative">
                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">🔍</span>
                <input type="text" 
                    id="searchInput"
                    placeholder="Cari produk yang terdaftar" 
                    value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
            </div>
            
            <!-- Status Filter -->
            <div class="w-full md:w-48">
                <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div class="w-full md:w-48">
                <select id="categoryFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Tambah Produk Button -->
            <a href="{{ route('seller.products.create') }}" 
               class="w-full md:w-auto bg-green-800 text-white px-6 py-2 rounded-lg hover:bg-green-900 transition flex items-center justify-center whitespace-nowrap font-medium">
                Tambah Produk Baru <span class="ml-2">+</span>
            </a>
        </div>
    </div>

    <!-- Tabel Produk -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-white border-b-2 border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Produk</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Satuan</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kategori</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Stok</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Harga</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-gradient-to-br from-orange-400 to-red-500">
                                @if($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white text-xs">
                                        No Img
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $product->unit }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $product->category->name }}</td>
                    <td class="px-6 py-4">
                        @php
                            $stockColor = 'bg-yellow-400 text-gray-900';
                            if($product->stock <= 5) {
                                $stockColor = 'bg-red-500 text-white';
                            } elseif($product->stock <= 20) {
                                $stockColor = 'bg-yellow-400 text-gray-900';
                            } else {
                                $stockColor = 'bg-yellow-400 text-gray-900';
                            }
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $stockColor }}">
                            {{ $product->stock }} Tersedia
                        </span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($product->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white">
                            Active
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white">
                            Draft
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <!-- Edit Icon -->
                            <a href="{{ route('seller.products.edit', $product->id) }}" 
                               class="text-orange-500 hover:text-orange-600 transition"
                               title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            
                            <!-- Delete Icon -->
                            <form action="{{ route('seller.products.destroy', $product->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus produk {{ $product->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-500 hover:text-red-600 transition"
                                        title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500 mb-4">Belum ada produk</p>
                            <a href="{{ route('seller.products.create') }}" 
                               class="bg-green-800 text-white px-6 py-2 rounded-lg hover:bg-green-900 transition">
                                Tambah Produk Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="bg-white rounded-lg shadow-sm px-6 py-4">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Menampilkan <span class="font-medium">{{ $products->firstItem() }}</span> 
                sampai <span class="font-medium">{{ $products->lastItem() }}</span> 
                dari <span class="font-medium">{{ $products->total() }}</span> data
            </p>
            <div class="flex items-center gap-2">
                {{ $products->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Filter dengan query string
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');

    function applyFilter() {
        const search = searchInput.value;
        const category = categoryFilter.value;
        const status = statusFilter.value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (category) params.append('category', category);
        if (status) params.append('status', status);
        
        const url = '{{ route("seller.products.index") }}' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    }

    // Debounce search
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilter, 500);
    });

    categoryFilter.addEventListener('change', applyFilter);
    statusFilter.addEventListener('change', applyFilter);
</script>
@endpush
@endsection
