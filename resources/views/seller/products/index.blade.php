@extends('layouts.seller')

@section('title', 'Etalase Produk')
@section('page-title', 'Etalase Produk')
@section('page-subtitle', 'Semua produk yang sedang Anda tawarkan di Nusa Belanja')

@section('content')
<div class="space-y-6 px-2 sm:px-0">
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="w-full lg:flex-1 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="text-gray-400 w-5 h-5" />
                </div>
                <input type="text" id="searchInput" placeholder="Cari nama produk..." value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
            </div>

            <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
                <div class="w-full sm:w-40">
                    <div class="relative">
                        <select id="statusFilter"
                            class="w-full px-3 py-2.5 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 bg-white cursor-pointer appearance-none">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <x-heroicon-m-chevron-down class="w-5 h-5 text-gray-400" />
                        </div>
                    </div>
                </div>

                <div class="w-full sm:w-48">
                    <div class="relative">
                        <select id="categoryFilter"
                            class="w-full px-3 py-2.5 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 bg-white cursor-pointer appearance-none">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category')==$category->id ? 'selected' : ''
                                }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <x-heroicon-m-chevron-down class="w-5 h-5 text-gray-400" />
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('seller.products.create') }}"
                class="w-full lg:w-auto bg-green-800 text-white px-6 py-2.5 rounded-lg hover:bg-green-900 transition flex items-center justify-center whitespace-nowrap font-semibold shadow-sm">
                <span>Tambah Produk</span> <span class="ml-2 text-lg">+</span>
            </a>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-[13px] min-w-[800px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Nama Produk</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Satuan</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Berat</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Kategori</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Stok</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Harga</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Status</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-gradient-to-br from-orange-400 to-red-500">
                                    @if($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                        alt="{{ $product->name }}" class="w-full h-full object-cover">
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
                        <td class="px-6 py-4 text-gray-700">
                            @if($product->weight >= 1000)
                            {{ number_format($product->weight / 1000, 1) }} kg
                            @else
                            {{ $product->weight }} gr
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600 bg-gray-100 px-2 py-1 rounded font-semibold">{{
                                $product->category->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                            $stockColor = 'bg-yellow-400 text-gray-900';
                            if($product->stock <= 5) { $stockColor='bg-red-500 text-white' ; } elseif($product->stock <=
                                    20) { $stockColor='bg-yellow-400 text-gray-900' ; } else {
                                    $stockColor='bg-yellow-400 text-gray-900' ; } @endphp <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $stockColor }}">
                                    {{ $product->stock }} Tersedia
                                    </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">Rp {{ number_format($product->price, 0, ',',
                            '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($product->status === 'active')
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white">
                                Active
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white">
                                Draft
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('seller.products.edit', $product->id) }}"
                                    class="text-orange-500 hover:text-orange-600 transition" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus produk {{ $product->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600 transition"
                                        title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            Belum ada produk. <a href="{{ route('seller.products.create') }}"
                                class="text-green-700 underline">Tambah Produk Pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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