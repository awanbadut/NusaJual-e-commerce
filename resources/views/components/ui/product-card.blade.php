@props(['item'])

<a href="{{ route('detail-produk') }}" class="group block h-full">

    <div
        class="bg-white rounded-lg shadow-[0px_4px_4px_rgba(0,0,0,0.25)] border border-gray-100 overflow-hidden hover:-translate-y-1 transition duration-300 flex flex-col h-full cursor-pointer">

        <div class="h-[178px] w-full bg-gray-100 overflow-hidden shrink-0 relative">
            <img src="https://placehold.co/400x300/brown/white?text={{ $item['cat'] }}"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

            @if(isset($item['old']) && $item['old'])
            <div class="absolute top-2 right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">
                Promo
            </div>
            @endif
        </div>

        <div class="px-3 py-4 flex flex-col gap-2 flex-1">

            <div class="flex items-center">
                <span class="text-sm font-medium text-gray-500">{{ $item['cat'] ?? 'Umum' }}</span>
            </div>

            <h3 class="text-xl font-bold text-[#2E3B27] leading-6 line-clamp-1 group-hover:text-[#0F4C20] transition">
                {{ $item['name'] }}
            </h3>

            <div class="flex items-center justify-between w-full mb-1">
                <span class="text-sm font-medium text-gray-500">Mitra Jaya Makmur</span>
                <div class="flex items-center gap-1 text-xs text-gray-400">
                    <x-heroicon-s-map-pin class="w-4 h-4" />
                    <span class="font-medium text-gray-500">Padang</span>
                </div>
            </div>

            <div class="flex items-center gap-2.5 mt-auto">
                <div class="flex items-baseline gap-[1px]">
                    <span class="text-[#8B4513] font-bold text-base tracking-tight">Rp {{ $item['price'] }}</span>
                    <span class="text-xs font-medium text-[#8B4513]">/Kg</span>
                </div>

                @if(isset($item['old']) && $item['old'])
                <span class="text-sm text-gray-400 line-through decoration-gray-400">Rp {{ $item['old'] }}</span>
                @endif
            </div>

            <div class="flex items-center justify-between mt-2 pt-2 border-t border-dashed border-gray-100">
                <div class="flex items-center gap-1 text-xs text-gray-500">
                    <x-heroicon-s-shopping-bag class="w-4 h-4 text-[#F0C400]" />
                    <span class="font-medium">{{ $item['sold'] ?? '0' }} terjual</span>
                </div>

                <div
                    class="h-8 bg-[#104911] group-hover:bg-[#0b3a18] text-white text-sm font-bold px-3 rounded-lg flex items-center justify-center gap-2 transition shadow-sm text-label-2">
                    <span>Tambah</span>
                    <x-heroicon-s-shopping-cart class="w-4 h-4" />
                </div>
            </div>

        </div>
    </div>
</a>