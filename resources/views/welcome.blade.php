<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nusa Belanja - Marketplace Hasil Bumi</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <section class="pt-28 pb-12 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-[1440px] mx-auto relative group">

            <div class="flex overflow-x-auto snap-x snap-mandatory gap-6 pb-4 no-scrollbar scroll-smooth"
                id="hero-carousel">

                <div
                    class="snap-center shrink-0 w-full relative rounded-xl overflow-hidden shadow-md bg-[#F0EFE6] border border-[#496030] flex flex-col-reverse md:flex-row items-center min-h-[500px] md:h-[472px] h-auto px-4 md:px-10 py-8 md:py-0">
                    <div class="absolute inset-0 opacity-10"
                        style="background-image: url('/img/pattern-kopi1.png'); background-size: 100%;"></div>

                    <div
                        class="flex-1 flex flex-col justify-center items-start gap-4 md:gap-6 z-10 w-full md:pl-10 text-center md:text-left">
                        <h1 class="text-4xl md:text-[64px] font-bold text-[#045405] leading-tight">Nusa Belanja</h1>
                        <p class="text-lg md:text-[20px] font-medium text-[#045405] leading-relaxed max-w-xl">
                            Deskripsi Nusa Belanja Lorem ipsum dolor sit amet consectetur. Auctor mattis senectus sit ut
                            ipsum mattis pellentesque sem.
                        </p>
                        <a href="{{ route('katalog') }}"
                            class="bg-[#D7AF00] text-[#384A23] font-bold px-6 py-3 rounded-lg flex items-center justify-center md:justify-start gap-2 hover:bg-[#b89600] transition shadow-sm w-full md:w-auto">
                            <span>Belanja Sekarang</span>
                            <x-heroicon-s-arrow-right class="w-5 h-5" />
                        </a>
                    </div>

                    <div class="w-full md:w-[45%] h-48 md:h-full relative flex items-end justify-center md:justify-end">
                        <img src="img/kurir.png" class="h-full object-contain object-bottom">
                    </div>
                </div>

                <div
                    class="snap-center shrink-0 w-full relative rounded-xl overflow-hidden shadow-md bg-[#7E4715] flex flex-col justify-center items-center min-h-[500px] md:h-[472px] h-auto text-center px-4 py-8 md:py-0">
                    <div class="absolute inset-0"
                        style="background-image: url('/img/pattern-kopi1.png'); background-size: 100%;"></div>
                    <div class="relative z-10 max-w-3xl flex flex-col items-center gap-6">
                        <h1 class="text-4xl md:text-[64px] font-bold text-white leading-tight">Nusa Belanja</h1>
                        <p class="text-white text-lg md:text-[20px] font-medium leading-relaxed max-w-2xl">
                            Deskripsi Nusa Belanja Lorem ipsum dolor sit amet consectetur. Auctor mattis senectus sit ut
                            ipsum mattis pellentesque sem.
                        </p>
                        <a href="{{ route('katalog') }}"
                            class="bg-[#FCFCF9] text-[#030712] font-bold px-6 py-3 rounded-lg flex items-center gap-2 hover:bg-gray-100 transition shadow-sm">
                            <span>Belanja Sekarang</span>
                            <x-heroicon-s-arrow-right class="w-5 h-5" />
                        </a>
                    </div>
                </div>

                <div
                    class="snap-center shrink-0 w-full relative rounded-xl overflow-hidden shadow-md bg-[#F5F5F1] border border-[#496030] flex flex-col-reverse md:flex-row items-center min-h-[500px] md:h-[472px] h-auto px-4 md:px-10 py-8 md:py-0">
                    <div
                        class="flex-1 flex flex-col justify-center items-start gap-6 z-10 w-full md:pl-16 md:pr-4 text-center md:text-left">
                        <h1 class="text-4xl md:text-[64px] font-bold text-[#045405] leading-tight">Nusa Belanja</h1>
                        <p class="text-lg md:text-h6 font-medium text-[#045405] leading-relaxed max-w-xl">
                            Deskripsi Nusa Belanja Lorem ipsum dolor sit amet consectetur. Auctor mattis senectus sit ut
                            ipsum mattis pellentesque sem.
                        </p>
                        <a href="{{ route('katalog') }}"
                            class="bg-[#1D4300] text-white font-bold px-6 py-3 rounded-lg flex items-center justify-center md:justify-start gap-2 hover:bg-[#143000] transition shadow-sm w-full md:w-auto">
                            <span>Belanja Sekarang</span>
                            <x-heroicon-s-arrow-right class="w-5 h-5" />
                        </a>
                    </div>
                    <div class="w-full md:w-[50%] h-48 md:h-full relative flex items-center justify-end p-4 md:p-6">
                        <img src="/img/petaniTeh.png"
                            class="w-full h-full md:h-[90%] object-cover rounded-[24px] shadow-sm">
                    </div>
                </div>

            </div>

            <button
                onclick="document.getElementById('hero-carousel').scrollBy({left: -window.innerWidth, behavior: 'smooth'})"
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-3 rounded-full shadow-lg z-20 backdrop-blur-sm hidden md:flex items-center justify-center transition group">
                <x-heroicon-o-chevron-left class="w-6 h-6 text-[#045406] group-hover:scale-110 transition" />
            </button>
            <button
                onclick="document.getElementById('hero-carousel').scrollBy({left: window.innerWidth, behavior: 'smooth'})"
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-3 rounded-full shadow-lg z-20 backdrop-blur-sm hidden md:flex items-center justify-center transition group">
                <x-heroicon-o-chevron-right class="w-6 h-6 text-[#045406] group-hover:scale-110 transition" />
            </button>

        </div>
    </section>

    <section class="py-16 px-4">
        <div class="max-w-7xl mx-auto text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#0F4C20] mb-2">Kenalan Sama Nusa Belanja</h2>
            <p class="text-lg md:text-h5 text-[#8B4513] font-medium">Marketplace yang ngerangkul mitra lokal biar
                jualannya makin lancar</p>
        </div>
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            @foreach(['Keunggulan 1', 'Keunggulan 2', 'Keunggulan 3'] as $item)
            <div
                class="relative flex flex-col items-center justify-center p-8 pt-12 gap-4 bg-white rounded-xl shadow-md border border-gray-100 transition duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 left-6 w-10 h-16 bg-[#045406] shadow-sm"
                    style="clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 75%, 0 100%);"></div>
                <div
                    class="w-[65px] h-[65px] bg-[#f4b400] rounded-lg p-[10px] flex items-center justify-center gap-[10px] shadow-sm z-10">
                    <x-heroicon-s-truck class="w-8 h-8 text-[#492403]" />
                </div>
                <div class="flex flex-col items-center gap-2 w-full self-stretch z-10">
                    <h3 class="text-xl font-bold text-[#492403] text-center">{{ $item }}</h3>
                    <p class="text-sm font-medium text-gray-500 text-center leading-relaxed">
                        Nibh massa vitae porta tincidunt. Quam faucibus elementum proin mi nec id. Pulvinar et quis quam
                        euismod in sapien.
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <section class="py-16 px-4 bg-[#F8FCF8]">
        <div class="max-w-7xl mx-auto text-center mb-12 flex flex-col items-center gap-2">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#0F4C20]">Bareng Mitra Terbaik</h2>
            <p class="text-lg md:text-h5 text-[#8B4513] font-medium">Dari kebun, gudang, sampai ke tangan pelanggan</p>
        </div>
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @for ($i = 1; $i <= 4; $i++) <div
                class="bg-white rounded-lg p-3 shadow-sm border border-gray-100 flex flex-col items-center hover:shadow-md transition duration-300">
                <div
                    class="w-[150px] h-[150px] md:w-[176px] md:h-[176px] rounded-full overflow-hidden mb-4 bg-gray-100 shrink-0">
                    <img src="https://placehold.co/400x400/green/white?text=Toko+{{$i}}"
                        class="w-full h-full object-cover">
                </div>
                <div class="w-full flex flex-col items-center gap-2 px-2 mb-4 text-center">
                    <h3 class="text-xl font-bold text-[#321804]">Kopi Jaya {{ $i }}</h3>
                    <p class="text-sm font-medium text-[#283618] line-clamp-2">Nibh massa vitae porta tincidunt. Quam
                        faucibus elementum proin mi nec id.</p>
                </div>
                <div class="w-full grid grid-cols-3 gap-1 mb-4 border-t border-gray-50 pt-3">
                    <div class="flex flex-col items-center gap-1 border-r border-gray-100">
                        <span class="text-[#9ca3af] text-[10px] font-semibold uppercase tracking-wider">Kategori</span>
                        <div class="flex flex-col items-center gap-1">
                            <div
                                class="w-[24px] h-[24px] bg-[#fefae0] rounded-full flex items-center justify-center shrink-0">
                                <x-heroicon-s-tag class="w-3 h-3 text-[#283618]" />
                            </div>
                            <span class="text-xs font-bold text-[#0a0a0a]">Kopi</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-center gap-1 border-r border-gray-100">
                        <span class="text-[#9ca3af] text-[10px] font-semibold uppercase tracking-wider">Produk</span>
                        <div class="flex flex-col items-center gap-1">
                            <div
                                class="w-[24px] h-[24px] bg-[#ffefd0] rounded-full flex items-center justify-center shrink-0">
                                <x-heroicon-s-cube class="w-3 h-3 text-[#283618]" />
                            </div>
                            <span class="text-xs font-bold text-[#0a0a0a]">110</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-[#9ca3af] text-[10px] font-semibold uppercase tracking-wider">Lokasi</span>
                        <div class="flex flex-col items-center gap-1 w-full px-1">
                            <div
                                class="w-[24px] h-[24px] bg-[#e2f7d7] rounded-full flex items-center justify-center shrink-0">
                                <x-heroicon-s-map-pin class="w-3 h-3 text-[#283618]" />
                            </div>
                            <span class="text-xs font-bold text-[#0a0a0a] truncate w-full text-center">Solok</span>
                        </div>
                    </div>
                </div>
                <button
                    class="w-full bg-[#045405] hover:bg-[#033a03] text-white font-bold py-2.5 rounded-lg flex items-center justify-center gap-2 transition text-sm mt-auto">
                    Lihat Profile
                    <x-heroicon-s-arrow-right class="w-4 h-4" />
                </button>
        </div>
        @endfor
        </div>
    </section>

    <section class="py-24 px-4 bg-white">
        <div class="max-w-7xl mx-auto text-center mb-20 flex flex-col items-center gap-2">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#0F4C20]">Hasil Bumi Pilihan Kita</h2>
            <p class="text-lg md:text-h5 text-[#8B4513] font-medium">Dari berbagai daerah, siap memenuhi kebutuhan</p>
        </div>
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-y-20 gap-x-6 pt-10">
            @foreach(['Kopi', 'Sawit', 'Teh', 'Rempah', 'Buah'] as $cat)
            <div
                class="relative flex flex-col items-center bg-[#fefefb] border border-[#e3fb9a] rounded-lg p-4 shadow-sm hover:shadow-md transition duration-300 cursor-pointer group mt-10">
                <div
                    class="w-[100px] h-[100px] md:w-[129px] md:h-[129px] rounded-full overflow-hidden border-[4px] border-white shadow-sm absolute -top-[50px] md:-top-[64px] bg-gray-200 group-hover:scale-105 transition-transform">
                    <img src="https://placehold.co/300x300/brown/white?text={{$cat}}"
                        class="w-full h-full object-cover">
                </div>
                <div class="h-[50px] md:h-[60px]"></div>
                <div class="flex flex-col items-center gap-1 w-full text-center mt-2">
                    <h4 class="text-lg font-bold text-[#0a0a0a]">{{ $cat }}</h4>
                    <span class="text-sm font-semibold text-[#87470c]">500 Produk</span>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <section class="py-16 px-4 bg-[#F8FCF8]">
        <div class="max-w-7xl mx-auto text-center mb-12 flex flex-col items-center gap-2">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#0F4C20]">Produk Lokal Pilihan</h2>
            <p class="text-lg md:text-h5 text-[#8B4513] font-medium">Hasil terbaik dari mitra terpercaya</p>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-6">
            @for ($j = 1; $j <= 4; $j++) <div
                class="flex flex-col sm:flex-row bg-[#fefefb] border border-[#e3fb9a] rounded-lg p-3 shadow-sm hover:shadow-md transition duration-300">

                <div class="w-full sm:w-[204px] h-[200px] sm:h-[178px] rounded-lg overflow-hidden shrink-0">
                    <img src="https://placehold.co/400x300/brown/white?text=Kopi+Gayo"
                        class="w-full h-full object-cover">
                </div>

                <div class="flex-1 flex flex-col justify-between px-2 sm:px-4 py-4 sm:py-2">
                    <div class="flex justify-between items-center w-full mb-2 sm:mb-0">
                        <span class="text-[#4b5563] text-sm font-medium">Kopi</span>
                        <div class="flex items-center gap-2 text-[#030712] text-xs font-medium">
                            <x-heroicon-s-shopping-bag class="w-4 h-4 text-[#f4b400]" />
                            <span>1000 terjual</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1 mb-2 sm:mb-0">
                        <h3 class="text-lg sm:text-h6 font-bold text-[#111827]">Egestas vehicula</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-[16px] font-bold text-[#87470c]">Rp 500.000</span>
                            <span class="text-xs text-[#6b7280]">/Kg</span>
                        </div>
                    </div>
                    <p class="text-xs text-[#6b7280] line-clamp-2 leading-relaxed mb-3 sm:mb-0">
                        Non suspendisse turpis velit enim facilisi et at. Dictum senectus ac elit ac risus mattis. Urna.
                    </p>
                    <div class="flex justify-end mt-2">
                        <button
                            class="bg-[#f4b400] hover:bg-yellow-500 text-[#045405] font-bold text-xs py-2 px-4 rounded-lg flex items-center gap-2 transition shadow-sm w-full sm:w-auto justify-center">
                            Beli Sekarang
                            <x-heroicon-s-arrow-right class="w-4 h-4" />
                        </button>
                    </div>
                </div>
        </div>
        @endfor
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('katalog') }}"
                class="inline-flex bg-[#045405] hover:bg-[#033a03] text-white font-bold py-3 px-8 rounded-lg items-center justify-center gap-2 transition text-label-1">
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
        });
    </script>
</body>

</html>