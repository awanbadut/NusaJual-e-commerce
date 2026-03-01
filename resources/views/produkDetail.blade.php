<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Nusa Belanja</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Agar sticky bottom bar tidak menutupi footer saat di-scroll mentok */
        body {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800 relative">

    <x-navbar />

    <div class="pt-24 md:pt-24 pb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex flex-wrap gap-2 text-xs md:text-sm font-medium text-[#8B4513]">
            <a href="{{ route('home') }}" class="hover:underline">Home</a>
            <x-heroicon-s-chevron-right class="w-3 h-3 md:w-4 md:h-4 text-gray-400" />
            <a href="{{ route('katalog') }}" class="hover:underline">Katalog</a>
            <x-heroicon-s-chevron-right class="w-3 h-3 md:w-4 md:h-4 text-gray-400" />
            <a href="{{ route('katalog', ['category[]' => $product->category->name]) }}"
                class="hover:underline line-clamp-1 max-w-[80px] md:max-w-none">
                {{ $product->category->name }}
            </a>
            <x-heroicon-s-chevron-right class="w-3 h-3 md:w-4 md:h-4 text-gray-400" />
            <span class="text-[#0F4C20] font-bold line-clamp-1 flex-1">{{ $product->name }}</span>
        </div>
    </div>

    <section class="px-4 sm:px-6 lg:px-8 mb-4 md:mb-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative w-full h-[100px] md:h-[180px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-4 md:p-6 shadow-sm">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('{{ asset('img/pattern-kopi1.webp') }}'); background-size: cover; background-position: center;">
                </div>
                <div class="relative z-10 flex flex-col gap-0.5 md:gap-1">
                    <h1 class="text-lg md:text-4xl font-bold text-[#0F4C20]">Info Lengkap Produk</h1>
                    <p class="text-[10px] md:text-lg font-medium text-[#8B4513]">
                        Semua yang perlu kamu tahu sebelum beli.
                    </p>
                </div>
            </div>
        </div>
    </section>

    @php
    $gallery = $product->images->pluck('image_path')->map(fn($path) => asset('storage/'.$path))->toArray();
    if(empty($gallery) && $product->primaryImage) {
    $gallery[] = asset('storage/' . $product->primaryImage->image_path);
    }
    if(empty($gallery)) {
    $gallery[] = 'https://placehold.co/600x600/brown/white?text=' . urlencode($product->name);
    }
    $totalSold = $product->orderItems()->whereHas('order', function($q) { $q->where('status', 'completed');
    })->sum('quantity');
    @endphp

    <section class="pb-16 md:pb-20 px-4 sm:px-6 lg:px-8" x-data="{ 
            images: {{ json_encode($gallery) }},
            active: 0,
            price: {{ $product->price }},
            qty: 1,
            maxStock: {{ $product->stock }},
            get total() { return (this.price * this.qty).toLocaleString('id-ID'); },
            next() { this.active = (this.active === this.images.length - 1) ? 0 : this.active + 1 },
            prev() { this.active = (this.active === 0) ? this.images.length - 1 : this.active - 1 }
        }">

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-12">

            <div class="lg:col-span-7 flex flex-col gap-4 md:gap-6">

                <div class="flex flex-col md:flex-row gap-3 md:gap-4 h-auto">

                    <div class="flex-1 flex flex-col gap-4 order-1 md:order-2">
                        <div
                            class="relative w-full aspect-square md:aspect-auto md:h-[500px] bg-gray-100 rounded-xl overflow-hidden shadow-sm border border-gray-200 group">
                            <img :src="images[active]" class="w-full h-full object-cover transition-all duration-300">

                            <button x-show="images.length > 1" @click="prev()"
                                class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#0F4C20] p-1.5 md:p-2 rounded-full shadow-lg transition transform hover:scale-110 active:scale-95 opacity-100 md:opacity-0 md:group-hover:opacity-100 z-10">
                                <x-heroicon-s-chevron-left class="w-5 h-5 md:w-6 md:h-6" />
                            </button>
                            <button x-show="images.length > 1" @click="next()"
                                class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#0F4C20] p-1.5 md:p-2 rounded-full shadow-lg transition transform hover:scale-110 active:scale-95 opacity-100 md:opacity-0 md:group-hover:opacity-100 z-10">
                                <x-heroicon-s-chevron-right class="w-5 h-5 md:w-6 md:h-6" />
                            </button>

                            <div x-show="images.length > 1"
                                class="absolute bottom-3 left-0 right-0 flex md:hidden justify-center gap-1.5 z-10">
                                <template x-for="(img, index) in images" :key="index">
                                    <button @click="active = index"
                                        class="h-1.5 rounded-full transition-all duration-300 shadow-sm"
                                        :class="active === index ? 'bg-[#0F4C20] w-5' : 'bg-white/70 w-1.5'">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex md:flex-col gap-2 md:gap-3 overflow-x-auto md:overflow-y-auto no-scrollbar shrink-0 justify-start md:max-h-[500px] order-2 md:order-1">
                        <template x-for="(img, index) in images" :key="index">
                            <button @click="active = index"
                                class="w-14 h-14 md:w-20 md:h-20 rounded-lg overflow-hidden border-2 transition shrink-0"
                                :class="active === index ? 'border-[#0F4C20] opacity-100' : 'border-transparent opacity-60 hover:opacity-100'">
                                <img :src="img" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </div>

                <div
                    class="bg-[#F0F2EE] rounded-xl p-3 md:p-4 flex flex-row items-center justify-between border border-[#CCD5C5]">
                    <div class="flex items-center gap-3">
                        <img src="{{ $product->store->logo ? asset('storage/'.$product->store->logo) : 'https://placehold.co/100x100/green/white?text='.substr($product->store->store_name, 0, 1) }}"
                            class="w-10 h-10 md:w-14 md:h-14 rounded-full border-2 border-white shadow-sm object-cover shrink-0">
                        <div class="flex flex-col">
                            <h4 class="text-sm md:text-lg font-bold text-[#2E3B27] line-clamp-1">{{
                                $product->store->store_name }}</h4>
                            <div class="flex items-center gap-1 text-[10px] md:text-sm text-gray-600 mt-0.5">
                                <x-heroicon-s-map-pin class="w-3 h-3 md:w-4 md:h-4 text-gray-500 shrink-0" />
                                <span class="line-clamp-1">{{ $product->store->city ?? 'Indonesia' }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('profil-mitra', $product->store->id) }}"
                        class="bg-white hover:bg-gray-50 text-[#0F4C20] border border-[#0F4C20] font-bold py-1.5 md:py-2 px-3 md:px-4 rounded-lg text-[10px] md:text-sm flex items-center gap-1 md:gap-2 transition shrink-0">
                        <span class="hidden sm:inline">Lihat Mitra</span>
                        <span class="sm:hidden">Kunjungi</span>
                        <x-heroicon-s-arrow-right class="w-3 h-3 md:w-4 md:h-4" />
                    </a>
                </div>

            </div>

            <div class="lg:col-span-5 flex flex-col gap-5 md:gap-6">

                <div class="space-y-2 md:space-y-4 border-b border-gray-200 pb-4 md:pb-6">
                    <span
                        class="inline-block px-2.5 py-1 bg-[#0F4C20] text-white text-[10px] md:text-xs font-bold rounded-full">
                        {{ $product->category->name }}
                    </span>

                    <h1 class="text-xl md:text-4xl font-bold text-[#2E3B27] leading-snug md:leading-tight">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-center gap-4 md:gap-6 text-xs md:text-sm mt-1">
                        <div
                            class="flex items-center gap-1 {{ $product->stock <= 10 ? 'text-yellow-600' : 'text-gray-600' }} font-medium">
                            <x-heroicon-o-archive-box class="w-4 h-4 md:w-5 md:h-5 shrink-0" />
                            <span class="font-semibold">{{ $product->stock }} {{ $product->unit }}</span>
                        </div>
                        <div class="flex items-center gap-1 text-green-600 font-medium">
                            <x-heroicon-s-shopping-bag class="w-4 h-4 md:w-5 md:h-5 shrink-0" />
                            <span class="font-semibold">{{ $totalSold }} Terjual</span>
                        </div>
                    </div>

                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-2xl md:text-3xl font-bold text-[#8B4513]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="text-xs md:text-lg text-gray-500 font-medium">/{{ $product->unit }}</span>
                    </div>
                </div>

                <div class="space-y-1.5 md:space-y-2 pb-24 md:pb-0">
                    <h3 class="text-sm md:text-lg font-bold text-gray-800">Deskripsi:</h3>
                    <p class="text-gray-600 leading-relaxed text-xs md:text-base whitespace-pre-line">
                        {{ $product->description }}
                    </p>
                </div>

                <div
                    class="hidden md:block bg-white rounded-xl shadow-[0px_4px_10px_rgba(0,0,0,0.05)] border border-gray-100 p-5 space-y-5 sticky top-28">
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-bold text-gray-600">Persediaan:</span>
                        <span class="font-bold text-[#0F4C20]">{{ $product->stock }} {{ $product->unit }}</span>
                    </div>
                    <hr class="border-dashed border-gray-200">
                    <div class="flex flex-col gap-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-500">Total Harga:</span>
                            <span class="text-xl font-bold text-[#8B4513]">Rp <span x-text="total"></span></span>
                        </div>

                        <form action="{{ route('keranjang.store') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="qty" :value="qty">
                            <div class="flex gap-4">
                                <div
                                    class="flex items-center border-2 border-[#0F4C20] rounded-lg h-12 w-32 justify-between px-2 shrink-0">
                                    <button type="button" @click="if(qty > 1) qty--"
                                        class="text-[#0F4C20] hover:bg-green-50 p-1 rounded transition active:scale-90">
                                        <x-heroicon-s-minus class="w-5 h-5" />
                                    </button>
                                    <span class="font-bold text-lg text-gray-800" x-text="qty"></span>
                                    <button type="button" @click="if(qty < maxStock) qty++"
                                        class="text-[#0F4C20] hover:bg-green-50 p-1 rounded transition active:scale-90">
                                        <x-heroicon-s-plus class="w-5 h-5" />
                                    </button>
                                </div>
                                <button type="submit" @disabled($product->stock == 0) class="flex-1 bg-[#0F4C20]
                                    hover:bg-[#0b3a18] disabled:bg-gray-400 disabled:cursor-not-allowed text-white
                                    font-bold rounded-lg h-12 flex items-center justify-center gap-2 transition-all
                                    shadow-md active:scale-95">
                                    <span>{{ $product->stock > 0 ? 'Tambah Keranjang' : 'Habis' }}</span>
                                    <x-heroicon-s-shopping-cart class="w-5 h-5" />
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div
            class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-3 flex flex-col gap-2 z-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <form action="{{ route('keranjang.store') }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="qty" :value="qty">

                <div class="flex items-center justify-between gap-3">
                    <div class="flex flex-col flex-1">
                        <span class="text-[10px] text-gray-500 font-medium leading-none">Total</span>
                        <span class="text-sm font-extrabold text-[#8B4513] leading-tight mt-0.5">Rp <span
                                x-text="total"></span></span>
                    </div>

                    <div
                        class="flex items-center border border-[#0F4C20] rounded-lg h-9 w-[90px] justify-between px-1 shrink-0 bg-[#F8FCF8]">
                        <button type="button" @click="if(qty > 1) qty--" class="text-[#0F4C20] p-1 active:scale-90">
                            <x-heroicon-s-minus class="w-3.5 h-3.5" />
                        </button>
                        <span class="font-bold text-xs text-gray-800" x-text="qty"></span>
                        <button type="button" @click="if(qty < maxStock) qty++"
                            class="text-[#0F4C20] p-1 active:scale-90">
                            <x-heroicon-s-plus class="w-3.5 h-3.5" />
                        </button>
                    </div>

                    <button type="submit" @disabled($product->stock == 0) class="bg-[#0F4C20] active:bg-[#0b3a18]
                        disabled:bg-gray-400 text-white font-bold rounded-lg h-9 px-4 flex items-center justify-center
                        gap-1.5 shrink-0 transition-transform active:scale-95">
                        <span class="text-xs">{{ $product->stock > 0 ? '+ Keranjang' : 'Habis' }}</span>
                    </button>
                </div>
            </form>
        </div>

    </section>

    <section class="py-10 md:py-12 bg-[#F8FCF8] border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6 md:mb-10">
                <h2 class="text-xl md:text-3xl font-extrabold text-[#0F4C20]">Rekomendasi Untukmu</h2>
                <p class="text-[#8B4513] font-medium text-xs md:text-base mt-0.5 md:mt-1">Lihat Produk Serupa</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6">
                @forelse($relatedProducts as $related)
                <x-ui.product-card :item="$related" />
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-10 text-center">
                    <div class="bg-gray-100 p-4 rounded-full mb-3">
                        <x-heroicon-o-cube class="w-6 h-6 md:w-8 md:h-8 text-gray-400" />
                    </div>
                    <p class="text-gray-500 font-medium text-xs md:text-sm">Belum ada produk serupa di kategori ini.</p>
                    <a href="{{ route('katalog') }}"
                        class="text-[#0F4C20] text-xs md:text-sm font-bold hover:underline mt-1">
                        Cari produk lain di Katalog
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <x-footer />

</body>

</html>