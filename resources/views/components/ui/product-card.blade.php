@props(['item'])

<div
    class="group block h-full bg-white rounded-lg shadow-[0px_4px_4px_rgba(0,0,0,0.25)] border border-gray-100 overflow-hidden hover:-translate-y-1 transition duration-300 flex flex-col">

    @php
    $id = is_object($item) ? $item->id : 1;
    $name = is_object($item) ? $item->name : $item['name'];
    $cat = is_object($item) ? ($item->category->name ?? 'Umum') : ($item['cat'] ?? 'Umum');
    $price = is_object($item) ? $item->price : str_replace('.','', $item['price']);
    $stock = is_object($item) ? $item->stock : 0;
    $unit = is_object($item) ? $item->unit : 'Kg';

    // Hitung total terjual
    if (is_object($item)) {
    $totalSold = $item->orderItems()
    ->whereHas('order', function($q) {
    $q->where('status', 'completed');
    })
    ->sum('quantity');
    } else {
    $totalSold = $item['sold'] ?? 0;
    }

    if (is_object($item) && $item->primaryImage) {
    $imgUrl = asset('storage/' . $item->primaryImage->image_path);
    } else {
    $imgUrl = 'https://placehold.co/400x300/brown/white?text=' . urlencode($cat);
    }
    @endphp

    <a href="{{ route('produk.show', $id) }}"
        class="w-full h-[120px] sm:h-[150px] md:h-[178px] bg-gray-100 overflow-hidden shrink-0 relative block">
        <img src="{{ $imgUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

        @if($stock <= 10 && $stock> 0)
            <div
                class="absolute top-1.5 left-1.5 md:top-2 md:left-2 bg-yellow-500 text-white text-[8px] md:text-xs font-bold px-1.5 md:px-2 py-0.5 md:py-1 rounded-full shadow-md">
                Stok Menipis
            </div>
            @elseif($stock == 0)
            <div
                class="absolute top-1.5 left-1.5 md:top-2 md:left-2 bg-red-600 text-white text-[8px] md:text-xs font-bold px-1.5 md:px-2 py-0.5 md:py-1 rounded-full shadow-md">
                Habis
            </div>
            @endif
    </a>

    <div class="p-2 md:px-3 md:py-4 flex flex-col gap-1 md:gap-2 flex-1">

        <a href="{{ route('produk.show', $id) }}" class="flex flex-col gap-1 md:gap-2 flex-1">

            <div class="flex items-center">
                <span class="text-[9px] md:text-sm font-medium text-gray-500 line-clamp-1">{{ $cat }}</span>
            </div>

            <h3
                class="text-[12px] md:text-xl font-bold text-[#2E3B27] leading-tight md:leading-6 line-clamp-2 md:line-clamp-1 group-hover:text-[#0F4C20] transition mb-1 md:mb-1.5">
                {{ $name }}
            </h3>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between w-full mb-0.5 md:mb-1">
                <span class="text-[9px] md:text-sm font-medium text-gray-500 line-clamp-1">
                    {{ is_object($item) ? ($item->store->store_name ?? 'Mitra Jaya') : 'Mitra Jaya Makmur' }}
                </span>
                <div class="flex items-center gap-0.5 md:gap-1 text-[8px] md:text-xs text-gray-400 mt-0.5 md:mt-0">
                    <x-heroicon-s-map-pin class="w-2.5 h-2.5 md:w-4 md:h-4 shrink-0" />
                    <span class="font-medium text-gray-500 line-clamp-1">
                        {{ is_object($item) ? ($item->store->city ?? 'Indonesia') : 'Padang' }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2.5 mt-auto pt-1 md:pt-0">
                <div class="flex items-baseline gap-[1px]">
                    <span class="text-[#8B4513] font-bold text-[13px] md:text-base tracking-tight">
                        Rp {{ number_format($price, 0, ',', '.') }}
                    </span>
                    <span class="text-[8px] md:text-xs font-medium text-[#8B4513]">/{{ $unit }}</span>
                </div>
            </div>
        </a>

        <div class="flex flex-col gap-1.5 md:gap-2 mt-1 md:mt-2 pt-1.5 md:pt-2 border-t border-dashed border-gray-100">

            <div class="flex items-center justify-between text-[9px] md:text-xs">
                <div
                    class="flex items-center gap-0.5 md:gap-1 {{ $stock <= 10 ? 'text-yellow-600' : 'text-gray-600' }}">
                    <svg class="w-3 h-3 md:w-4 md:h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
                    <span class="font-semibold">{{ $stock > 0 ? number_format($stock, 0, ',', '.') : '0' }} <span
                            class="hidden sm:inline">{{ $unit }}</span></span>
                </div>

                <div class="flex items-center gap-0.5 md:gap-1 text-green-600">
                    <x-heroicon-s-shopping-bag class="w-3 h-3 md:w-4 md:h-4 shrink-0" />
                    <span class="font-semibold">{{ number_format($totalSold, 0, ',', '.') }} <span
                            class="hidden sm:inline">terjual</span></span>
                </div>
            </div>

            @if(is_object($item))
            <form action="{{ route('keranjang.store') }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="product_id" value="{{ $item->id }}">
                <input type="hidden" name="qty" value="1">

                <button type="submit" @disabled($stock==0)
                    class="w-full h-7 md:h-9 bg-[#104911] hover:bg-[#0b3a18] text-white text-[10px] md:text-sm font-bold rounded-lg flex items-center justify-center gap-1.5 md:gap-2 transition shadow-sm disabled:bg-gray-400 disabled:cursor-not-allowed disabled:hover:bg-gray-400">
                    <span>{{ $stock > 0 ? 'Tambah' : 'Habis' }}</span>
                    <x-heroicon-s-shopping-cart class="w-3 h-3 md:w-4 md:h-4 shrink-0" />
                </button>
            </form>
            @else
            <div
                class="w-full h-7 md:h-9 bg-[#104911] opacity-50 cursor-not-allowed text-white text-[10px] md:text-sm font-bold rounded-lg flex items-center justify-center gap-1.5 md:gap-2">
                <span>Tambah</span>
                <x-heroicon-s-shopping-cart class="w-3 h-3 md:w-4 md:h-4 shrink-0" />
            </div>
            @endif
        </div>

    </div>
</div>