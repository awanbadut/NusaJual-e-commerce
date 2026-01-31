<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Nusa Belanja</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <div class="pt-24 pb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex items-center gap-2 text-sm font-medium text-[#8B4513]">
            <a href="{{ route('home') }}" class="hover:underline">Home</a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <a href="{{ route('katalog') }}" class="hover:underline">Katalog</a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <a href="{{ route('katalog', ['category[]' => $product->category->name]) }}" class="hover:underline">
                {{ $product->category->name }}
            </a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="text-[#0F4C20] font-bold truncate max-w-[150px]">{{ $product->name }}</span>
        </div>
    </div>

    <section class="px-4 sm:px-6 lg:px-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative w-full h-[180px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-6 shadow-sm">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('{{ asset('img/pattern-kopi1.png') }}'); background-size: 100%;">
                </div>
                <div class="relative z-10 flex flex-col gap-1">
                    <h1 class="text-3xl md:text-4xl font-bold text-[#0F4C20]">Info Lengkap Produk</h1>
                    <p class="text-base md:text-lg font-medium text-[#8B4513]">
                        Semua yang perlu kamu tahu sebelum beli, kami jelasin di sini.
                    </p>
                </div>
            </div>
        </div>
    </section>

    @php
    // Ambil semua gambar produk
    $gallery = $product->images->pluck('image_path')->map(fn($path) => asset('storage/'.$path))->toArray();

    // Jika tidak ada gambar galeri, pakai gambar utama
    if(empty($gallery) && $product->primaryImage) {
    $gallery[] = asset('storage/' . $product->primaryImage->image_path);
    }

    // Jika masih kosong, pakai placeholder
    if(empty($gallery)) {
    $gallery[] = 'https://placehold.co/600x600/brown/white?text=' . urlencode($product->name);
    }
    @endphp

    <section class="pb-20 px-4 sm:px-6 lg:px-8" x-data="{ 
            images: {{ json_encode($gallery) }},
            active: 0,
            price: {{ $product->price }},
            qty: 1,
            maxStock: {{ $product->stock }},
            get total() { 
                return (this.price * this.qty).toLocaleString('id-ID'); 
            },
            next() { 
                this.active = (this.active === this.images.length - 1) ? 0 : this.active + 1 
            },
            prev() { 
                this.active = (this.active === 0) ? this.images.length - 1 : this.active - 1 
            }
        }">

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

            <div class="lg:col-span-7 flex flex-col gap-6">

                <div class="flex flex-col-reverse md:flex-row gap-4 h-auto">

                    <div
                        class="flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto no-scrollbar shrink-0 justify-center md:justify-start max-h-[500px]">
                        <template x-for="(img, index) in images" :key="index">
                            <button @click="active = index"
                                class="w-16 h-16 md:w-20 md:h-20 rounded-lg overflow-hidden border-2 transition shrink-0"
                                :class="active === index ? 'border-[#0F4C20] opacity-100' : 'border-transparent opacity-60 hover:opacity-100'">
                                <img :src="img" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>

                    <div class="flex-1 flex flex-col gap-4">
                        <div
                            class="relative w-full aspect-square md:aspect-auto md:h-[500px] bg-gray-100 rounded-xl overflow-hidden shadow-sm border border-gray-200 group">

                            <img :src="images[active]" class="w-full h-full object-cover transition-all duration-300">

                            <button x-show="images.length > 1" @click="prev()"
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#0F4C20] p-2 rounded-full shadow-lg transition transform hover:scale-110 active:scale-95 opacity-100 md:opacity-0 md:group-hover:opacity-100 z-10">
                                <x-heroicon-s-chevron-left class="w-6 h-6" />
                            </button>

                            <button x-show="images.length > 1" @click="next()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#0F4C20] p-2 rounded-full shadow-lg transition transform hover:scale-110 active:scale-95 opacity-100 md:opacity-0 md:group-hover:opacity-100 z-10">
                                <x-heroicon-s-chevron-right class="w-6 h-6" />
                            </button>

                            <div x-show="images.length > 1"
                                class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10">
                                <template x-for="(img, index) in images" :key="index">
                                    <button @click="active = index"
                                        class="h-2.5 rounded-full transition-all duration-300 shadow-sm"
                                        :class="active === index ? 'bg-[#0F4C20] w-8' : 'bg-white/70 hover:bg-white w-2.5'">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#F0F2EE] rounded-xl p-4 flex items-center justify-between border border-[#CCD5C5]">
                    <div class="flex items-center gap-4">
                        <img src="{{ $product->store->logo ? asset('storage/'.$product->store->logo) : 'https://placehold.co/100x100/green/white?text='.substr($product->store->store_name, 0, 1) }}"
                            class="w-14 h-14 rounded-full border-2 border-white shadow-sm object-cover">
                        <div>
                            <h4 class="text-lg font-bold text-[#2E3B27]">{{ $product->store->store_name }}</h4>
                            <div class="flex items-center gap-1 text-sm text-gray-600">
                                <x-heroicon-s-map-pin class="w-4 h-4 text-gray-500" />
                                <span>{{ $product->store->city ?? 'Indonesia' }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('profil-mitra', $product->store->id) }}"
                        class="bg-white hover:bg-gray-50 text-[#0F4C20] border border-[#0F4C20] font-bold py-2 px-4 rounded-lg text-sm flex items-center gap-2 transition">
                        Lihat Mitra
                        <x-heroicon-s-arrow-right class="w-4 h-4" />
                    </a>
                </div>

            </div>

            <div class="lg:col-span-5 flex flex-col gap-6">

                <div class="space-y-4 border-b border-gray-200 pb-6">
                    <span class="inline-block px-3 py-1 bg-[#0F4C20] text-white text-xs font-bold rounded-full">
                        {{ $product->category->name }}
                    </span>

                    <h1 class="text-3xl md:text-4xl font-bold text-[#2E3B27] leading-tight">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5 text-sm text-gray-500 font-medium">
                            <x-heroicon-s-shopping-bag class="w-5 h-5 text-[#F0C400]" />
                            <span>{{ rand(10, 100) }} Terjual</span>
                        </div>
                    </div>

                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-[#8B4513]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="text-lg text-gray-500 font-medium">/{{ $product->unit }}</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <h3 class="text-lg font-bold text-gray-800">Deskripsi :</h3>
                    <p class="text-gray-600 leading-relaxed text-sm md:text-base whitespace-pre-line">
                        {{ $product->description }}
                    </p>
                </div>

                <div
                    class="bg-white rounded-xl shadow-[0px_4px_10px_rgba(0,0,0,0.05)] border border-gray-100 p-5 space-y-5 sticky top-28">

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

                        <form action="{{ route('keranjang.store') }}" method="POST" class="w-full" x-data="{ 
        loading: false, 
        added: false,
        submitForm(e) {
            this.loading = true;
            // Simulasi delay sedikit biar animasi kelihatan (opsional, karena submit asli reload page)
            // Tapi karena ini submit form biasa (bukan AJAX), loading akan muncul sampai page reload.
            // Jika mau animasi 'berhasil' tanpa reload, harus pakai AJAX.
            // Di sini kita pakai animasi klik simple saat submit.
        }
    }" @submit="submitForm">
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

                                <button type="submit"
                                    class="group relative flex-1 bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold rounded-lg h-12 flex items-center justify-center gap-2 transition-all duration-300 shadow-md active:scale-95 overflow-hidden"
                                    :class="{ 'cursor-not-allowed opacity-90': loading }" :disabled="loading">

                                    <div class="flex items-center gap-2 transition-transform duration-300"
                                        :class="{ '-translate-y-10': loading }">
                                        <span>Tambah Keranjang</span>
                                        <x-heroicon-s-shopping-cart class="w-5 h-5 group-hover:animate-bounce" />
                                    </div>

                                    <div class="absolute inset-0 flex items-center justify-center transition-transform duration-300 translate-y-10"
                                        :class="{ 'translate-y-0': loading }">
                                        <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <span class="ml-2">Memproses...</span>
                                    </div>

                                    <span
                                        class="absolute inset-0 rounded-lg bg-white/20 scale-0 transition-transform duration-300 active:scale-100"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <section class="py-12 bg-[#F8FCF8] border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-extrabold text-[#0F4C20]">Rekomendasi Untukmu</h2>
                <p class="text-[#8B4513] font-medium mt-1">Lihat Produk yang Serupa di Sini</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($relatedProducts as $related)
                <x-ui.product-card :item="$related" />
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-10 text-center">
                    <div class="bg-gray-100 p-4 rounded-full mb-3">
                        <x-heroicon-o-cube class="w-8 h-8 text-gray-400" />
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada produk serupa di kategori ini.</p>
                    <a href="{{ route('katalog') }}" class="text-[#0F4C20] text-sm font-bold hover:underline mt-1">
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