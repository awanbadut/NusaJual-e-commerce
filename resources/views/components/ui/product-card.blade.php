@props(['item'])

<div
    class="group block h-full bg-white rounded-lg shadow-[0px_4px_4px_rgba(0,0,0,0.25)] border border-gray-100 overflow-hidden hover:-translate-y-1 transition duration-300 flex flex-col">

    @php
    $id = is_object($item) ? $item->id : 1;
    $name = is_object($item) ? $item->name : $item['name'];
    $cat = is_object($item) ? ($item->category->name ?? 'Umum') : ($item['cat'] ?? 'Umum');
    $price = is_object($item) ? $item->price : str_replace('.','', $item['price']);

    if (is_object($item) && $item->primaryImage) {
    $imgUrl = asset('storage/' . $item->primaryImage->image_path);
    } else {
    $imgUrl = 'https://placehold.co/400x300/brown/white?text=' . urlencode($cat);
    }
    @endphp

    <a href="{{ route('produk.show', $id) }}"
        class="h-[178px] w-full bg-gray-100 overflow-hidden shrink-0 relative block">
        <img src="{{ $imgUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
    </a>

    <div class="px-3 py-4 flex flex-col gap-2 flex-1">

        <a href="{{ route('produk.show', $id) }}" class="flex flex-col gap-2 flex-1">

            <div class="flex items-center">
                <span class="text-sm font-medium text-gray-500">{{ $cat }}</span>
            </div>

            <h3 class="text-xl font-bold text-[#2E3B27] leading-6 line-clamp-1 group-hover:text-[#0F4C20] transition">
                {{ $name }}
            </h3>

            <div class="flex items-center justify-between w-full mb-1">
                <span class="text-sm font-medium text-gray-500">
                    {{ is_object($item) ? ($item->store->store_name ?? 'Mitra Jaya') : 'Mitra Jaya Makmur' }}
                </span>
                <div class="flex items-center gap-1 text-xs text-gray-400">
                    <x-heroicon-s-map-pin class="w-4 h-4" />
                    <span class="font-medium text-gray-500">
                        {{ is_object($item) ? ($item->store->city ?? 'Indonesia') : 'Padang' }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2.5 mt-auto">
                <div class="flex items-baseline gap-[1px]">
                    <span class="text-[#8B4513] font-bold text-base tracking-tight">
                        Rp {{ number_format($price, 0, ',', '.') }}
                    </span>
                    <span class="text-xs font-medium text-[#8B4513]">/{{ is_object($item) ? $item->unit : 'Kg' }}</span>
                </div>
            </div>
        </a>
        <div class="flex items-center justify-between mt-2 pt-2 border-t border-dashed border-gray-100">
            <div class="flex items-center gap-1 text-xs text-gray-500">
                <x-heroicon-s-shopping-bag class="w-4 h-4 text-[#F0C400]" />
                <span class="font-medium">{{ is_object($item) ? '0' : ($item['sold'] ?? '0') }} terjual</span>
            </div>

            @if(is_object($item))
            <form action="{{ route('keranjang.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $item->id }}">
                <input type="hidden" name="qty" value="1">

                <button type="submit"
                    class="h-8 bg-[#104911] hover:bg-[#0b3a18] text-white text-sm font-bold px-3 rounded-lg flex items-center justify-center gap-2 transition shadow-sm text-label-2 z-10 relative">
                    <span>Tambah</span>
                    <x-heroicon-s-shopping-cart class="w-4 h-4" />
                </button>
            </form>
            @else
            <div
                class="h-8 bg-[#104911] opacity-50 cursor-not-allowed text-white text-sm font-bold px-3 rounded-lg flex items-center justify-center gap-2 text-label-2">
                <span>Tambah</span>
                <x-heroicon-s-shopping-cart class="w-4 h-4" />
            </div>
            @endif
        </div>

    </div>
</div>