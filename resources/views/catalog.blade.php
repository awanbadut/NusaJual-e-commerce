<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog - Nusa Belanja</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <section class="pt-28 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto">
            <div
                class="relative w-full h-[320px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-6 shadow-sm">

                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('img/pattern-kopi1.png'); background-size: 100%">
                </div>

                <div class="relative z-10 flex flex-col gap-2">
                    <h1 class="text-4xl md:text-[56px] font-bold text-[#0F4C20] leading-tight">
                        Semua Pilihan Terbaik
                    </h1>
                    <p class="text-lg md:text-2xl font-medium text-[#8B4513]">
                        Produk lokal berkualitas, tinggal pilih & pesan
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto flex flex-col lg:flex-row gap-8">

            <x-ui.sidebarfilter title="Filter Produk">

                <form action="{{ route('katalog') }}" method="GET">
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
                                    onclick="window.location='{{ route('katalog') }}'">
                            </label>

                            @foreach(['Kopi', 'Sawit', 'Teh', 'Tembakau', 'Rempah'] as $cat)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" class="peer hidden" name="category[]" value="{{ $cat }}"
                                    @checked(in_array($cat, request('category', []))) onchange="this.form.submit()">

                                <div
                                    class="w-4 h-4 rounded border-2 border-gray-300 bg-white peer-checked:bg-[#0F4C20] peer-checked:border-[#0F4C20] transition flex items-center justify-center text-white shrink-0">
                                    <x-heroicon-s-check class="w-3 h-3" />
                                </div>

                                <span
                                    class="text-sm font-medium text-gray-600 peer-checked:text-[#0F4C20] peer-checked:font-bold transition">
                                    {{ $cat }}
                                </span>
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
                                    class="text-sm font-medium text-gray-600 peer-checked:text-[#0F4C20] peer-checked:font-bold transition">
                                    {{ $price }}
                                </span>
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
                        <form action="{{ route('katalog') }}" method="GET" class="relative w-full md:w-[320px]">
                            @foreach(request('category', []) as $cat)
                            <input type="hidden" name="category[]" value="{{ $cat }}">
                            @endforeach
                            @if(request('sort_price'))
                            <input type="hidden" name="sort_price" value="{{ request('sort_price') }}">
                            @endif

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari di Nusa Belanja"
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
                            <a href="{{ route('katalog', ['search' => request('search')]) }}"
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
                            <a href="{{ route('katalog') }}" class="text-[#0F4C20] hover:text-red-500 transition">
                                <x-heroicon-s-x-mark class="w-4 h-4" />
                            </a>
                        </div>
                        @endif

                        <a href="{{ route('katalog') }}"
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