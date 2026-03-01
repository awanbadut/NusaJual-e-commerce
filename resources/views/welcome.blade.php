<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nusa Belanja - Marketplace Hasil Bumi</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <!-- Hero Section (SAMA - tidak perlu diubah) -->
    <section class="pt-24 pb-8 px-4 bg-white">
        <div class="max-w-[1440px] mx-auto relative group">
            <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4 no-scrollbar scroll-smooth"
                id="hero-carousel">

                <div
                    class="snap-center shrink-0 w-full relative rounded-2xl overflow-hidden shadow-md bg-[#F0EFE6] border border-[#496030] flex flex-col md:flex-row items-center min-h-[350px] md:h-[472px] px-6 md:px-10 py-6 md:py-0">
                    <div class="absolute inset-0 opacity-10"
                        style="background-image: url('/img/pattern-kopi1.webp'); background-size: cover;"></div>

                    <div
                        class="w-full md:w-[45%] h-32 md:h-full relative flex items-end justify-center md:justify-end mt-4 md:mt-0 order-1 md:order-2">
                        <img src="img/kurir.webp" class="h-full object-contain object-bottom">
                    </div>

                    <div
                        class="flex-1 flex flex-col justify-center items-center md:items-start gap-3 md:gap-6 z-10 w-full md:pl-10 text-center md:text-left order-2 md:order-1">
                        <h1 class="text-xl md:text-[48px] font-bold text-[#045405] leading-tight">
                            Jembatan UMKM ke Pasar Nasional
                        </h1>
                        <p
                            class="text-xs md:text-[20px] font-medium text-[#045405] leading-relaxed max-w-xl line-clamp-3 md:line-clamp-none">
                            nusaBelanja hadir sebagai penghubung utama antara pelaku UMKM lokal dengan pasar yang lebih
                            luas di seluruh Indonesia.
                        </p>
                        <a href="{{ route('katalog') }}"
                            class="bg-[#D7AF00] text-[#384A23] font-bold px-6 py-2 md:py-3 rounded-lg flex items-center justify-center gap-2 hover:bg-[#b89600] transition shadow-sm w-full md:w-auto text-sm md:text-base">
                            <span>Belanja Sekarang</span>
                            <x-heroicon-s-arrow-right class="w-4 h-4 md:w-5 md:h-5" />
                        </a>
                    </div>
                </div>

                <div
                    class="snap-center shrink-0 w-full relative rounded-2xl overflow-hidden shadow-md bg-[#7E4715] flex flex-col justify-center items-center min-h-[350px] md:h-[472px] text-center px-6 py-8 md:py-0">
                    <div class="absolute inset-0 opacity-20"
                        style="background-image: url('/img/pattern-kopi1.webp'); background-size: cover;"></div>
                    <div class="relative z-10 max-w-3xl flex flex-col items-center gap-4 md:gap-6">
                        <h1 class="text-xl md:text-[48px] font-bold text-white leading-tight">
                            Dari Lokal untuk Nasional
                        </h1>
                        <p
                            class="text-white text-xs md:text-[20px] font-medium leading-relaxed max-w-2xl line-clamp-3 md:line-clamp-none">
                            Mengusung semangat kebanggaan produk lokal, kami membantu memperluas jangkauan bisnis Anda
                            ke seluruh pelosok negeri.
                        </p>
                        <a href="{{ route('katalog') }}"
                            class="bg-[#FCFCF9] text-[#030712] font-bold px-6 py-2.5 md:py-3 rounded-lg flex items-center gap-2 hover:bg-gray-100 transition shadow-sm text-sm md:text-base">
                            <span>Lihat Produk</span>
                            <x-heroicon-s-arrow-right class="w-4 h-4 md:w-5 md:h-5" />
                        </a>
                    </div>
                </div>

                <div
                    class="snap-center shrink-0 w-full relative rounded-2xl overflow-hidden shadow-md bg-[#F5F5F1] border border-[#496030] flex flex-col md:flex-row items-center min-h-[350px] md:h-[472px] px-6 md:px-10 py-6 md:py-0">

                    <div
                        class="w-full md:w-[50%] h-32 md:h-full relative flex items-center justify-center md:justify-end mt-4 md:mt-0 order-1 md:order-2">
                        <img src="/img/petaniTeh.webp"
                            class="w-full h-full md:h-[85%] object-cover rounded-xl shadow-sm">
                    </div>

                    <div
                        class="flex-1 flex flex-col justify-center items-center md:items-start gap-4 md:gap-6 z-10 w-full md:pl-16 text-center md:text-left order-2 md:order-1">
                        <h1 class="text-xl md:text-[48px] font-bold text-[#045405] leading-tight">
                            Komitmen Kami
                        </h1>
                        <p
                            class="text-xs md:text-h6 font-medium text-[#045405] leading-relaxed max-w-xl line-clamp-3 md:line-clamp-none">
                            nusaBelanja berkomitmen mengangkat produk-produk lokal agar mampu bersaing dan dikenal di
                            seluruh Indonesia.
                        </p>
                        <a href="{{ route('katalog') }}"
                            class="bg-[#1D4300] text-white font-bold px-6 py-2 md:py-3 rounded-lg flex items-center justify-center gap-2 hover:bg-[#143000] transition shadow-sm w-full md:w-auto text-sm md:text-base">
                            <span>Belanja Sekarang</span>
                            <x-heroicon-s-arrow-right class="w-4 h-4 md:w-5 md:h-5" />
                        </a>
                    </div>
                </div>
            </div>

            <button
                onclick="document.getElementById('hero-carousel').scrollBy({left: -window.innerWidth, behavior: 'smooth'})"
                class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 md:p-3 rounded-full shadow-lg z-20 backdrop-blur-sm hidden md:flex items-center justify-center transition group">
                <x-heroicon-o-chevron-left class="w-5 h-5 md:w-6 md:h-6 text-[#045406]" />
            </button>
            <button
                onclick="document.getElementById('hero-carousel').scrollBy({left: window.innerWidth, behavior: 'smooth'})"
                class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 md:p-3 rounded-full shadow-lg z-20 backdrop-blur-sm hidden md:flex items-center justify-center transition group">
                <x-heroicon-o-chevron-right class="w-5 h-5 md:w-6 md:h-6 text-[#045406]" />
            </button>

            <div class="flex justify-center gap-2 mt-4 md:hidden" id="carousel-dots">
                <div onclick="scrollToSlide(0)"
                    class="w-2 h-2 rounded-full cursor-pointer transition-all duration-300 bg-[#045405]"></div>
                <div onclick="scrollToSlide(1)"
                    class="w-2 h-2 rounded-full cursor-pointer transition-all duration-300 bg-gray-300"></div>
                <div onclick="scrollToSlide(2)"
                    class="w-2 h-2 rounded-full cursor-pointer transition-all duration-300 bg-gray-300"></div>
            </div>
        </div>
    </section>

    <!-- Mitra Section (SAMA - tidak perlu diubah) -->
    <section class="py-12 md:py-16 px-4 bg-[#F8FCF8]">
        <div class="max-w-7xl mx-auto text-center mb-10 flex flex-col items-center gap-2">
            <h2 class="text-2xl md:text-4xl font-extrabold text-[#0F4C20]">Bareng Mitra Terbaik</h2>
            <p class="text-sm md:text-lg text-[#8B4513] font-medium">Dari kebun, gudang, sampai ke tangan pelanggan</p>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
            @foreach($stores as $store)
            <div
                class="bg-white rounded-xl p-2 md:p-3 shadow-sm border border-gray-100 flex flex-col items-center hover:shadow-md transition duration-300 h-full w-full">
                <div
                    class="w-[80px] h-[80px] md:w-[176px] md:h-[176px] rounded-full overflow-hidden mb-3 bg-gray-100 shrink-0 border-2 border-[#F8FCF8]">
                    <img src="{{ $store->logo ? asset('storage/'.$store->logo) : 'https://placehold.co/400x400/green/white?text='.substr($store->store_name, 0, 2) }}"
                        class="w-full h-full object-cover">
                </div>

                <div class="w-full flex flex-col items-center gap-1 md:gap-2 px-1 mb-3 text-center flex-1">
                    <h3 class="text-sm md:text-xl font-bold text-[#321804] line-clamp-1">{{ $store->store_name }}</h3>
                    <p class="text-[10px] md:text-sm font-medium text-[#283618] line-clamp-1 md:line-clamp-2">
                        {{ $store->description ?? 'Mitra terpercaya Nusa Belanja.' }}
                    </p>
                </div>

                <div class="w-full grid grid-cols-3 gap-0.5 mb-3 border-t border-gray-50 pt-2">
                    <div class="flex flex-col items-center gap-1 border-r border-gray-100">
                        <span
                            class="text-[#9ca3af] text-[8px] md:text-[10px] font-semibold uppercase tracking-tight">Owner</span>
                        <span class="text-[10px] md:text-xs font-bold text-[#0a0a0a] line-clamp-1">{{ explode(' ',
                            $store->user->name)[0] }}</span>
                    </div>
                    <div class="flex flex-col items-center gap-1 border-r border-gray-100">
                        <span
                            class="text-[#9ca3af] text-[8px] md:text-[10px] font-semibold uppercase tracking-tight">Produk</span>
                        <span class="text-[10px] md:text-xs font-bold text-[#0a0a0a]">{{ $store->products_count
                            }}</span>
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <span
                            class="text-[#9ca3af] text-[8px] md:text-[10px] font-semibold uppercase tracking-tight">Lokasi</span>
                        <span
                            class="text-[10px] md:text-xs font-bold text-[#0a0a0a] truncate w-full text-center px-1">{{
                            $store->city ?? 'Indo' }}</span>
                    </div>
                </div>

                <a href="{{ route('profil-mitra', $store->id) }}"
                    class="w-full bg-[#045405] hover:bg-[#033a03] text-white font-bold py-2 rounded-lg flex items-center justify-center gap-1 transition text-[10px] md:text-sm mt-auto">
                    <span>Profil</span>
                    <x-heroicon-s-arrow-right class="w-3 h-3 md:w-4 md:h-4" />
                </a>
            </div>
            @endforeach

            @if($sisaMitra > 0)
            <a href="#"
                class="bg-[#F0EFE6] border-2 border-dashed border-[#496030] rounded-xl p-3 shadow-sm flex flex-col items-center justify-center hover:bg-[#e6e4d6] transition duration-300 h-full w-full group cursor-pointer min-h-[180px] md:min-h-[300px]">
                <div
                    class="w-10 h-10 md:w-[80px] md:h-[80px] rounded-full bg-[#496030] flex items-center justify-center mb-2 md:mb-4 group-hover:scale-110 transition-transform">
                    <span class="text-white font-bold text-sm md:text-2xl">+{{ $sisaMitra }}</span>
                </div>
                <h3 class="text-xs md:text-xl font-bold text-[#0F4C20] text-center uppercase md:normal-case">Lainnya
                </h3>
                <div class="mt-3 hidden md:flex items-center gap-2 text-[#0F4C20] font-bold text-sm">
                    <span>Lihat Semua</span>
                    <x-heroicon-s-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                </div>
            </a>
            @endif
        </div>
    </section>

    <!-- Kategori Section (SAMA - tidak perlu diubah) -->
    <section class="py-12 md:py-24 px-4 bg-white">
        <div class="max-w-7xl mx-auto text-center mb-14 md:mb-20 flex flex-col items-center gap-1 md:gap-2">
            <h2 class="text-xl md:text-4xl font-extrabold text-[#0F4C20]">Hasil Bumi Pilihan Kita</h2>
            <p class="text-sm md:text-lg text-[#8B4513] font-medium">Siap memenuhi kebutuhan harian Anda</p>
        </div>

        <div
            class="max-w-7xl mx-auto grid grid-cols-2 sm:grid-cols-3 md:flex md:flex-wrap md:justify-center gap-3 md:gap-x-8 md:gap-y-16">
            @foreach($categories as $category)
            <a href="{{ route('katalog', ['category' => $category->slug]) }}"
                class="relative flex flex-col items-center bg-[#fefefb] border border-[#e3fb9a] rounded-xl p-3 md:p-4 shadow-sm hover:shadow-md transition duration-300 cursor-pointer group mt-8 md:mt-10 w-full md:w-48">

                <div
                    class="w-16 h-16 md:w-[129px] md:h-[129px] rounded-full overflow-hidden border-2 md:border-[4px] border-white shadow-sm absolute -top-8 md:-top-[64px] bg-gray-100 group-hover:scale-105 transition-transform">
                    <img src="{{ asset('img/' . $category->slug . '.webp') }}" alt="{{ $category->name }}"
                        class="w-full h-full object-cover">
                </div>

                <div class="h-8 md:h-[60px]"></div>

                <div class="flex flex-col items-center w-full text-center mt-1">
                    <h4 class="text-xs md:text-lg font-bold text-[#0a0a0a] line-clamp-1 w-full leading-tight">
                        {{ $category->name }}
                    </h4>
                    <span class="text-[10px] md:text-sm font-semibold text-[#87470c] opacity-80 mt-0.5">
                        {{ $category->products_count }} Item
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    <!-- ✅ PRODUK SECTION - UPDATE DI SINI -->
    <section class="py-12 sm:py-16 px-2 sm:px-4 bg-[#F8FCF8]">
        <div class="max-w-7xl mx-auto text-center mb-8 sm:mb-12 flex flex-col items-center gap-1 sm:gap-2">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#0F4C20]">Produk Lokal Pilihan</h2>
            <p class="text-sm sm:text-lg md:text-h5 text-[#8B4513] font-medium">Hasil terbaik dari mitra terpercaya</p>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-2 lg:grid-cols-2 gap-3 sm:gap-6 justify-items-center">
            @foreach($products as $product)
            @php
            // Hitung total terjual dari database
            $totalSold = $product->orderItems()
            ->whereHas('order', function($q) {
            $q->where('status', 'completed');
            })
            ->sum('quantity');
            @endphp

            <div
                class="flex flex-col sm:flex-row bg-[#fefefb] border border-[#e3fb9a] rounded-lg p-2 sm:p-3 shadow-sm hover:shadow-md transition duration-300 w-full max-w-xl h-full">

                <div
                    class="w-full sm:w-[204px] aspect-square sm:aspect-auto sm:h-[178px] rounded-lg overflow-hidden shrink-0 bg-gray-100">
                    @php
                    $imgUrl = $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) :
                    'https://placehold.co/400x300/brown/white?text=' . urlencode($product->name);
                    @endphp
                    <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
                </div>

                <div class="flex-1 flex flex-col justify-between px-1 sm:px-4 py-2 sm:py-2">
                    <div class="flex justify-between items-center w-full mb-1 sm:mb-0">
                        <span class="text-[#4b5563] text-[9px] sm:text-sm font-medium line-clamp-1">{{
                            $product->category->name ?? 'Umum' }}</span>
                        <div
                            class="flex items-center gap-1 sm:gap-2 text-green-600 text-[9px] sm:text-xs font-semibold">
                            <x-heroicon-s-shopping-bag class="w-3 h-3 sm:w-4 sm:h-4" />
                            <span>{{ number_format($totalSold, 0, ',', '.') }} <span
                                    class="hidden sm:inline">terjual</span></span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-0.5 sm:gap-1 mb-1 sm:mb-0">
                        <h3
                            class="text-xs sm:text-lg font-bold text-[#111827] line-clamp-2 sm:line-clamp-1 h-8 sm:h-auto">
                            {{ $product->name }}</h3>
                        <div class="flex items-baseline gap-1 mt-1 sm:mt-0">
                            <span class="text-xs sm:text-[16px] font-bold text-[#87470c]">Rp {{
                                number_format($product->price, 0,
                                ',', '.') }}</span>
                            <span class="text-[8px] sm:text-xs text-[#6b7280]">/{{ $product->unit }}</span>
                        </div>
                    </div>

                    <p class="hidden sm:block text-xs text-[#6b7280] line-clamp-2 leading-relaxed mb-3 sm:mb-0">{{
                        $product->description }}</p>

                    <div class="flex justify-end mt-2 sm:mt-2">
                        <a href="{{ route('produk.show', $product->id) }}"
                            class="bg-[#f4b400] hover:bg-yellow-500 text-[#045405] font-bold text-[10px] sm:text-xs py-1.5 sm:py-2 px-3 sm:px-4 rounded-lg flex items-center gap-1 sm:gap-2 transition shadow-sm w-full sm:w-auto justify-center">
                            <span>Beli</span>
                            <x-heroicon-s-arrow-right class="w-3 h-3 sm:w-4 sm:h-4" />
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-8 sm:mt-12">
            <a href="{{ route('katalog') }}"
                class="inline-flex bg-[#045405] hover:bg-[#033a03] text-white font-bold py-2.5 sm:py-3 px-6 sm:px-8 rounded-lg items-center justify-center gap-2 transition text-xs sm:text-sm">
                Lihat Semua Produk
                <x-heroicon-s-arrow-right class="w-4 h-4" />
            </a>
        </div>
    </section>

    <x-footer />

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const carousel = document.getElementById('hero-carousel');
            let scrollInterval;
            function autoScroll() {
                if (carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth - 10) {
                    carousel.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    carousel.scrollBy({ left: carousel.clientWidth, behavior: 'smooth' });
                }
            }
            function startAutoSlide() { scrollInterval = setInterval(autoScroll, 5000); }
            function stopAutoSlide() { clearInterval(scrollInterval); }
            startAutoSlide();
            carousel.addEventListener('mouseenter', stopAutoSlide);
            carousel.addEventListener('mouseleave', startAutoSlide);
            carousel.addEventListener('touchstart', stopAutoSlide);
            carousel.addEventListener('touchend', startAutoSlide);

            const dots = document.querySelectorAll('#carousel-dots div');

            // Fungsi untuk mengupdate warna titik saat scroll
            function updateDots() {
                const scrollLeft = carousel.scrollLeft;
                const width = carousel.clientWidth;
                // Menghitung indeks slide yang sedang aktif
                const activeIndex = Math.round(scrollLeft / width);

                dots.forEach((dot, index) => {
                    if (index === activeIndex) {
                        dot.classList.remove('bg-gray-300');
                        dot.classList.add('bg-[#045405]', 'w-4'); // w-4 untuk efek memanjang (optional)
                    } else {
                        dot.classList.remove('bg-[#045405]', 'w-4');
                        dot.classList.add('bg-gray-300');
                    }
                });
            }

            // Fungsi agar titik bisa diklik untuk pindah slide
            window.scrollToSlide = function(index) {
                const width = carousel.clientWidth;
                carousel.scrollTo({
                    left: width * index,
                    behavior: 'smooth'
                });
            };

            // Pasang event listener saat user menggeser (scroll)
            carousel.addEventListener('scroll', updateDots);
            
            // Panggil sekali saat load awal
            updateDots();
            });
    </script>
</body>

</html>