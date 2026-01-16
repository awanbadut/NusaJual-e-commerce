<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Mitra - Nusa Belanja</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <div class="pt-24 pb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex gap-2 text-sm font-medium text-[#8B4513]">
            <a href="{{ route('katalog') }}" class="hover:underline">Katalog</a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <a href="{{route('detail-produk')}}" class="hover:underline">Informasi Produk</a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="text-[#0F4C20] font-bold">Informasi Mitra</span>
        </div>
    </div>

    <section class="px-4 sm:px-6 lg:px-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row items-center gap-6">

                <div class="w-32 h-32 md:w-[136px] md:h-[136px] rounded-lg overflow-hidden shrink-0 bg-gray-100">
                    <img src="https://placehold.co/200x200/green/white?text=Mitra" class="w-full h-full object-cover">
                </div>

                <div class="flex-1 flex flex-col items-center md:items-start text-center md:text-left gap-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-[#2E3B27]">Mitra Jaya Makmur</h1>

                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <x-heroicon-s-map-pin class="w-5 h-5 text-gray-400" />
                        <span>Jalan Limau Manis City No. 12, Padang</span>
                    </div>

                    <div class="flex items-center gap-3 mt-1 text-sm text-gray-600">
                        <div class="flex items-center gap-1.5 font-medium">
                            <x-heroicon-s-cube class="w-5 h-5 text-[#8B4513]" />
                            <span>50 Produk</span>
                        </div>
                        <div class="w-1.5 h-1.5 bg-gray-300 rounded-full"></div>
                        <div class="flex items-center gap-1.5 font-medium">
                            <x-heroicon-s-shopping-bag class="w-5 h-5 text-[#8B4513]" />
                            <span>1000+ terjual</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-4 md:mt-0">
                    <button
                        class="flex items-center gap-2 px-4 py-2 rounded-lg border-2 border-[#0F4C20] text-[#0F4C20] font-bold text-sm hover:bg-green-50 transition">
                        <img src="https://img.icons8.com/ios-filled/50/0F4C20/instagram-new.png" class="w-5 h-5">
                        Instagram
                    </button>
                    <button
                        class="flex items-center gap-2 px-4 py-2 rounded-lg bg-[#0F4C20] text-white font-bold text-sm hover:bg-[#0b3a18] transition">
                        <img src="https://img.icons8.com/ios-filled/50/ffffff/whatsapp.png" class="w-5 h-5">
                        Whatsapp
                    </button>
                </div>

            </div>
        </div>
    </section>

    <section class="pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

            <x-ui.sidebarfilter title="Atur Pilihanmu">

                <form action="{{ url()->current() }}" method="GET">

                    @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="space-y-2">
                        <h4 class="text-sm font-bold text-gray-800">Pilih Kategori</h4>
                        <div class="space-y-2">

                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <div
                                    class="w-4 h-4 rounded border-2 {{ !request('category') ? 'border-[#0F4C20] bg-[#0F4C20]' : 'border-gray-300 bg-white' }} flex items-center justify-center text-white shrink-0">
                                    @if(!request('category'))
                                    <x-heroicon-s-check class="w-3 h-3" /> @endif
                                </div>
                                <span
                                    class="text-sm font-bold {{ !request('category') ? 'text-[#0F4C20]' : 'text-gray-600' }}">Semua
                                    Produk</span>
                                <input type="radio" name="reset_cat" class="hidden"
                                    onclick="window.location='{{ url()->current() }}?search={{ request('search') }}'">
                            </label>

                            @foreach(['Kopi', 'Sawit', 'Teh'] as $cat)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" class="peer hidden" name="category[]" value="{{ $cat }}"
                                    @checked(in_array($cat, request('category', []))) onchange="this.form.submit()">

                                <div
                                    class="w-4 h-4 rounded border-2 border-gray-300 bg-white peer-checked:bg-[#0F4C20] peer-checked:border-[#0F4C20] transition flex items-center justify-center text-white shrink-0">
                                    <x-heroicon-s-check class="w-3 h-3" />
                                </div>
                                <span
                                    class="text-sm font-medium text-gray-600 peer-checked:text-[#0F4C20] peer-checked:font-bold transition">{{
                                    $cat }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <hr class="border-gray-100 my-4">

                    <div class="space-y-2">
                        <h4 class="text-sm font-bold text-gray-800">Sesuaikan Harga</h4>
                        <div class="space-y-2">
                            @foreach(['Harga Tertinggi', 'Harga Terjangkau'] as $price)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="radio" name="sort_price" class="peer hidden" value="{{ $price }}"
                                    @checked(request('sort_price')==$price) onchange="this.form.submit()">

                                <div
                                    class="w-4 h-4 rounded-full border-2 border-gray-300 bg-white peer-checked:border-[#0F4C20] peer-checked:bg-white flex items-center justify-center shrink-0">
                                    <div
                                        class="w-2 h-2 rounded-full bg-[#0F4C20] opacity-0 group-has-[:checked]:opacity-100 transition">
                                    </div>
                                </div>
                                <span
                                    class="text-sm font-medium text-gray-600 peer-checked:text-[#0F4C20] peer-checked:font-bold transition">{{
                                    $price }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                </form>
            </x-ui.sidebarfilter>


            <div class="flex-1">

                <div class="flex flex-col gap-4 mb-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-lg">
                            <span class="text-gray-500 font-medium">Menampilkan</span>
                            <span class="font-bold text-[#0F4C20]">Semua Produk</span>
                        </div>

                        <form action="{{ url()->current() }}" method="GET" class="relative w-full md:w-[320px]">
                            @foreach(request('category', []) as $cat)
                            <input type="hidden" name="category[]" value="{{ $cat }}">
                            @endforeach
                            @if(request('sort_price'))
                            <input type="hidden" name="sort_price" value="{{ request('sort_price') }}">
                            @endif

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari di Toko Ini"
                                class="w-full pl-4 pr-10 py-2.5 rounded-lg border-2 border-[#0F4C20] focus:outline-none focus:ring-2 focus:ring-green-200 text-sm font-medium placeholder-gray-400">
                            <button type="submit"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#0F4C20]">
                                <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                            </button>
                        </form>
                    </div>

                    @if(request('category') || request('sort_price'))
                    <div class="flex flex-wrap items-center gap-3 animate-fade-in">
                        <span class="text-sm font-medium text-gray-600">Pilihanmu:</span>

                        @if(request('category'))
                        @foreach(request('category') as $cat)
                        <div
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-[#0F4C20] rounded-full shadow-sm">
                            <span class="text-[11px] font-bold text-[#0F4C20] uppercase tracking-wide">{{ $cat }}</span>
                            <a href="{{ url()->current() }}?search={{ request('search') }}"
                                class="text-[#0F4C20] hover:text-red-500 transition">
                                <x-heroicon-s-x-mark class="w-4 h-4" />
                            </a>
                        </div>
                        @endforeach
                        @endif

                        @if(request('sort_price'))
                        <div
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-[#0F4C20] rounded-full shadow-sm">
                            <span class="text-[11px] font-bold text-[#0F4C20] uppercase tracking-wide">{{
                                request('sort_price') }}</span>
                            <a href="{{ url()->current() }}?search={{ request('search') }}"
                                class="text-[#0F4C20] hover:text-red-500 transition">
                                <x-heroicon-s-x-mark class="w-4 h-4" />
                            </a>
                        </div>
                        @endif

                        <a href="{{ url()->current() }}"
                            class="text-xs text-red-600 font-semibold hover:underline ml-1 decoration-red-600">
                            Hapus Semua
                        </a>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach([
                    ['name' => 'Egestas vehicula', 'cat' => 'Kopi', 'price' => '500.000', 'old' => '600.000', 'sold' =>
                    '500+'],
                    ['name' => 'Egestas velit', 'cat' => 'Sawit', 'price' => '750.000', 'old' => null, 'sold' => '500'],
                    ['name' => 'Sagittis elit', 'cat' => 'Teh', 'price' => '200.000', 'old' => null, 'sold' => '200'],
                    ['name' => 'Imperdiet ultrices', 'cat' => 'Teh', 'price' => '250.000', 'old' => null, 'sold' =>
                    '120'],
                    ['name' => 'Ipsum donec', 'cat' => 'Teh', 'price' => '159.000', 'old' => '600.000', 'sold' =>
                    '200'],
                    ['name' => 'Tempus consequat', 'cat' => 'Teh', 'price' => '220.000', 'old' => null, 'sold' => '50'],
                    ['name' => 'Egestas vitae', 'cat' => 'Kopi', 'price' => '450.000', 'old' => '600.000', 'sold' =>
                    '120'],
                    ['name' => 'Dictumst in', 'cat' => 'Kopi', 'price' => '450.000', 'old' => null, 'sold' => '500+'],
                    ['name' => 'Tempus consequat', 'cat' => 'Sawit', 'price' => '220.000', 'old' => null, 'sold' =>
                    '50'],
                    ] as $item)

                    <x-ui.product-card :item="$item" />

                    @endforeach
                </div>

            </div>

        </div>
    </section>

    <x-footer />

</body>

</html>