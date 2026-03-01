@extends('layouts.seller')

@section('title', 'Etalase Produk')
@section('page-title', 'Etalase Produk')
@section('page-subtitle', 'Semua produk yang sedang Anda tawarkan di Nusa Belanja')

@section('content')
<div class="space-y-4 md:space-y-6 px-2 sm:px-0">

    <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border border-gray-100">
        <div class="flex flex-col lg:flex-row gap-3 md:gap-4 items-center w-full">

            <div class="w-full lg:flex-1 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="text-gray-400 w-4 h-4 md:w-5 md:h-5" />
                </div>
                <input type="text" id="searchInput" placeholder="Cari nama produk..." value="{{ request('search') }}"
                    class="w-full pl-9 md:pl-10 pr-4 py-2 md:py-2.5 text-xs md:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
            </div>

            <div class="w-full lg:w-auto grid grid-cols-2 sm:flex sm:flex-row gap-2 md:gap-3">
                <div class="w-full sm:w-40 relative">
                    <select id="statusFilter"
                        class="w-full px-3 py-2 md:py-2.5 text-xs md:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 bg-white cursor-pointer appearance-none">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                        <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 md:pr-3 pointer-events-none">
                        <x-heroicon-m-chevron-down class="w-4 h-4 md:w-5 md:h-5 text-gray-400" />
                    </div>
                </div>

                <div class="w-full sm:w-48 relative">
                    <select id="categoryFilter"
                        class="w-full px-3 py-2 md:py-2.5 text-xs md:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 bg-white cursor-pointer appearance-none">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category')==$category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 md:pr-3 pointer-events-none">
                        <x-heroicon-m-chevron-down class="w-4 h-4 md:w-5 md:h-5 text-gray-400" />
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-auto flex flex-row gap-2 md:gap-3">
                {{-- TOMBOL RESET --}}
                @if(request('search') || request('category') || request('status'))
                <button id="resetFilter" type="button"
                    class="h-9 md:h-11 px-3 md:px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold text-xs md:text-sm transition flex items-center justify-center shrink-0">
                    Reset
                </button>
                @endif

                {{-- TOMBOL TAMBAH PRODUK --}}
                <a href="{{ route('seller.products.create') }}"
                    class="flex-1 lg:flex-none h-9 md:h-11 bg-green-800 text-white px-4 md:px-6 rounded-lg hover:bg-green-900 transition flex items-center justify-center whitespace-nowrap font-semibold shadow-sm text-xs md:text-sm gap-1 md:gap-2">
                    <span>Tambah Produk</span> <span class="text-sm md:text-lg leading-none">+</span>
                </a>
            </div>

        </div>
    </div>


    {{-- ✅ TABEL PRODUK (Dioptimalkan padding dan ukuran font-nya) --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left whitespace-nowrap min-w-[700px] md:min-w-[800px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr>
                        <th class="px-4 py-3 md:px-5 md:py-4 text-[11px] md:text-[13px] font-semibold text-[#15803D]">
                            Nama Produk</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 text-[11px] md:text-[13px] font-semibold text-[#15803D]">
                            Satuan</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 text-[11px] md:text-[13px] font-semibold text-[#15803D]">
                            Berat</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 text-[11px] md:text-[13px] font-semibold text-[#15803D]">
                            Kategori</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 text-[11px] md:text-[13px] font-semibold text-[#15803D]">
                            Stok</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 text-[11px] md:text-[13px] font-semibold text-[#15803D]">
                            Harga</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 text-[11px] md:text-[13px] font-semibold text-[#15803D]">
                            Status</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 text-[11px] md:text-[13px] font-semibold text-[#15803D] text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs md:text-[13px]">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2.5 md:px-6 md:py-4">
                            <div class="flex items-center gap-2.5 md:gap-3">
                                <div
                                    class="w-9 h-9 md:w-12 md:h-12 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 border border-gray-200">
                                    @if($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                        alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                    <div
                                        class="w-full h-full flex items-center justify-center text-gray-400 text-[10px] md:text-xs font-medium">
                                        No Img
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 line-clamp-1 max-w-[150px] md:max-w-[200px]">{{
                                        $product->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2.5 md:px-6 md:py-4 text-gray-700">{{ $product->unit }}</td>
                        <td class="px-4 py-2.5 md:px-6 md:py-4 text-gray-700">
                            @if($product->weight >= 1000)
                            {{ number_format($product->weight / 1000, 1) }} kg
                            @else
                            {{ $product->weight }} gr
                            @endif
                        </td>
                        <td class="px-4 py-2.5 md:px-6 md:py-4">
                            <span
                                class="text-gray-600 bg-gray-100 px-2 py-0.5 md:py-1 rounded text-[10px] md:text-xs font-semibold">{{
                                $product->category->name }}</span>
                        </td>
                        <td class="px-4 py-2.5 md:px-6 md:py-4">
                            @php
                            $stockColor = 'bg-yellow-400 text-gray-900';
                            if($product->stock <= 5) { $stockColor='bg-red-500 text-white' ; } elseif($product->stock <=
                                    20) { $stockColor='bg-yellow-400 text-gray-900' ; } else {
                                    $stockColor='bg-yellow-400 text-gray-900' ; } @endphp <span
                                    class="inline-flex items-center px-2 py-0.5 md:px-3 md:py-1 rounded-full text-[10px] md:text-xs font-bold {{ $stockColor }}">
                                    {{ $product->stock }} Tersedia
                                    </span>
                        </td>
                        <td class="px-4 py-2.5 md:px-6 md:py-4 font-bold text-gray-900">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2.5 md:px-6 md:py-4">
                            @if($product->status === 'active')
                            <span
                                class="inline-flex items-center px-2 py-0.5 md:px-3 md:py-1 rounded-full text-[10px] md:text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                Active
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2 py-0.5 md:px-3 md:py-1 rounded-full text-[10px] md:text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                Draft
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 md:px-6 md:py-4">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('seller.products.edit', $product->id) }}"
                                    class="text-orange-500 hover:text-orange-600 transition p-1 bg-orange-50 rounded"
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none"
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
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-600 transition p-1 bg-red-50 rounded"
                                        title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                        <td colspan="8" class="px-4 py-10 text-center text-xs md:text-sm text-gray-500">
                            Belum ada produk. <a href="{{ route('seller.products.create') }}"
                                class="text-green-700 font-bold hover:underline">Tambah Produk Pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($products->hasPages())
    <div class="mt-4 overflow-x-auto pb-2">
        {{ $products->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Filter dengan query string
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const resetButton = document.getElementById('resetFilter');

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

    // FUNGSI RESET FILTER
    function resetFilter() {
        window.location.href = '{{ route("seller.products.index") }}';
    }

    // Debounce search
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilter, 500);
    });

    categoryFilter.addEventListener('change', applyFilter);
    statusFilter.addEventListener('change', applyFilter);
    
    // EVENT LISTENER RESET
    if (resetButton) {
        resetButton.addEventListener('click', resetFilter);
    }
</script>
@endpush
@endsection