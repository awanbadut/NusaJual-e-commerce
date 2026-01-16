<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Nusa Belanja</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <div class="pt-24 pb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex items-center gap-2 text-sm font-medium text-[#8B4513]">
            <a href="{{ route('katalog') }}" class="hover:underline">Katalog</a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="text-[#0F4C20] font-bold">Informasi Produk</span>
        </div>
    </div>

    <section class="px-4 sm:px-6 lg:px-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative w-full h-[180px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-6 shadow-sm">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('img/pattern-kopi1.png'); background-size: 100%;">
                </div>
                <div class="relative z-10 flex flex-col gap-1">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-[#0F4C20]">Info Lengkap Produk</h1>
                    <p class="text-base md:text-lg font-medium text-[#8B4513]">
                        Semua yang perlu kamu tahu sebelum beli, kami jelasin di sini.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-20 px-4 sm:px-6 lg:px-8" x-data="{ 
                images: [
                    'https://placehold.co/600x600/brown/white?text=Img+1',
                    'https://placehold.co/600x600/brown/white?text=Img+2',
                    'https://placehold.co/600x600/brown/white?text=Img+3',
                    'https://placehold.co/600x600/brown/white?text=Img+4'
                ],
                active: 0,
                price: 200000,
                qty: 1,
                get total() { return (this.price * this.qty).toLocaleString('id-ID'); },
                next() { this.active = (this.active === this.images.length - 1) ? 0 : this.active + 1 },
                prev() { this.active = (this.active === 0) ? this.images.length - 1 : this.active - 1 }
             }">

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

            <div class="lg:col-span-7 flex flex-col gap-6">

                <div class="flex flex-col-reverse md:flex-row gap-4 h-auto">

                    <div
                        class="flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto no-scrollbar shrink-0 justify-center md:justify-start">
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

                            <button @click="prev()"
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#0F4C20] p-2 rounded-full shadow-lg transition transform hover:scale-110 active:scale-95 opacity-100 md:opacity-0 md:group-hover:opacity-100 z-10">
                                <x-heroicon-s-chevron-left class="w-6 h-6" />
                            </button>

                            <button @click="next()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#0F4C20] p-2 rounded-full shadow-lg transition transform hover:scale-110 active:scale-95 opacity-100 md:opacity-0 md:group-hover:opacity-100 z-10">
                                <x-heroicon-s-chevron-right class="w-6 h-6" />
                            </button>

                            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10">
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
                        <img src="https://placehold.co/100x100/green/white?text=Mitra"
                            class="w-14 h-14 rounded-full border-2 border-white shadow-sm">
                        <div>
                            <h4 class="text-lg font-bold text-[#2E3B27]">Mitra Jaya Makmur</h4>
                            <div class="flex items-center gap-1 text-sm text-gray-600">
                                <x-heroicon-s-map-pin class="w-4 h-4 text-gray-500" />
                                <span>Padang, Sumatera Barat</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('profil-mitra') }}"
                        class="bg-white hover:bg-gray-50 text-[#0F4C20] border border-[#0F4C20] font-bold py-2 px-4 rounded-lg text-sm flex items-center gap-2 transition">
                        Lihat Mitra
                        <x-heroicon-s-arrow-right class="w-4 h-4" />
                    </a>
                </div>

            </div>

            <div class="lg:col-span-5 flex flex-col gap-6">

                <div class="space-y-4 border-b border-gray-200 pb-6">
                    <span
                        class="inline-block px-3 py-1 bg-[#0F4C20] text-white text-xs font-bold rounded-full">Kopi</span>

                    <h1 class="text-3xl md:text-4xl font-bold text-[#2E3B27] leading-tight">
                        Pharetra nec adipiscing
                    </h1>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5 text-sm text-gray-500 font-medium">
                            <x-heroicon-s-shopping-bag class="w-5 h-5 text-[#F0C400]" />
                            <span>500 Terjual</span>
                        </div>

                    </div>

                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-[#8B4513]">Rp 200.000</span>
                        <span class="text-lg text-gray-500 font-medium">/Kg</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <h3 class="text-lg font-bold text-gray-800">Deskripsi :</h3>
                    <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                        Vulputate ac amet mauris fusce eleifend et eget lacinia blandit. Duis tincidunt non neque
                        interdum pellentesque nibh turpis enim amet. Morbi purus enim maecenas cras tristique senectus
                        morbi massa adipiscing. Vivamus est lorem eget morbi aliquet tellus. Duis viverra sit mauris
                        metus diam viverra pulvinar vitae diam.
                    </p>
                </div>

                <div
                    class="bg-white rounded-xl shadow-[0px_4px_10px_rgba(0,0,0,0.05)] border border-gray-100 p-5 space-y-5">

                    <div class="flex justify-between items-center text-sm">
                        <span class="font-bold text-gray-600">Persediaan:</span>
                        <span class="font-bold text-[#0F4C20]">445 Kg</span>
                    </div>

                    <hr class="border-dashed border-gray-200">

                    <div class="flex flex-col gap-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-500">Total Harga:</span>
                            <span class="text-xl font-bold text-[#8B4513]">Rp <span x-text="total"></span></span>
                        </div>

                        <div class="flex gap-4">
                            <div
                                class="flex items-center border-2 border-[#0F4C20] rounded-lg h-12 w-32 justify-between px-2">
                                <button @click="if(qty > 1) qty--"
                                    class="text-[#0F4C20] hover:bg-green-50 p-1 rounded transition">
                                    <x-heroicon-s-minus class="w-5 h-5" />
                                </button>
                                <span class="font-bold text-lg text-gray-800" x-text="qty"></span>
                                <button @click="qty++" class="text-[#0F4C20] hover:bg-green-50 p-1 rounded transition">
                                    <x-heroicon-s-plus class="w-5 h-5" />
                                </button>
                            </div>

                            <button
                                class="flex-1 bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold rounded-lg h-12 flex items-center justify-center gap-2 transition shadow-md">
                                <span>Tambah Keranjang</span>
                                <x-heroicon-s-shopping-cart class="w-5 h-5" />
                            </button>
                        </div>
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
                @foreach([
                ['name' => 'Egestas vehicula', 'cat' => 'Kopi', 'price' => '500.000', 'old' => '600.000', 'sold' =>
                '500+'],
                ['name' => 'Sagittis elit', 'cat' => 'Teh', 'price' => '200.000', 'old' => null, 'sold' => '200'],
                ['name' => 'Imperdiet ultrices', 'cat' => 'Teh', 'price' => '250.000', 'old' => null, 'sold' => '120'],
                ['name' => 'Ipsum donec', 'cat' => 'Teh', 'price' => '159.000', 'old' => '600.000', 'sold' => '200'],
                ] as $item)

                <x-ui.product-card :item="$item" />

                @endforeach
            </div>
        </div>
    </section>

    <x-footer />

</body>

</html>