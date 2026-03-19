<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store->store_name }} - Nusa Belanja</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <div class="pt-24 md:pt-24 pb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex flex-wrap gap-2 text-xs md:text-sm font-medium text-[#8B4513]">
            <a href="{{ route('katalog') }}" class="hover:underline">Katalog</a>
            <x-heroicon-s-chevron-right class="w-3 h-3 md:w-4 md:h-4 text-gray-400 mt-0.5" />
            <span class="text-gray-500">Mitra</span>
            <x-heroicon-s-chevron-right class="w-3 h-3 md:w-4 md:h-4 text-gray-400 mt-0.5" />
            <span class="text-[#0F4C20] font-bold line-clamp-1">{{ $store->store_name }}</span>
        </div>
    </div>

    <section class="px-4 sm:px-6 lg:px-8 mb-6 md:mb-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">

                <div class="flex flex-row items-center w-full md:w-auto flex-1 gap-3 md:gap-6">

                    <div
                        class="w-16 h-16 md:w-[136px] md:h-[136px] rounded-full md:rounded-lg overflow-hidden shrink-0 bg-gray-100 border border-gray-100 md:border-none">
                        <img src="{{ $store->logo ? asset('storage/'.$store->logo) : 'https://placehold.co/200x200/green/white?text='.substr($store->store_name, 0, 1) }}"
                            class="w-full h-full object-cover">
                    </div>

                    <div class="flex flex-col items-start text-left gap-1 md:gap-2 flex-1">
                        <h1 class="text-base md:text-3xl font-bold text-[#2E3B27] line-clamp-1">{{ $store->store_name }}
                        </h1>

                        <div class="flex items-center gap-1 md:gap-1.5 text-[10px] md:text-sm text-gray-600">
                            <x-heroicon-s-map-pin class="w-3 h-3 md:w-5 md:h-5 text-gray-400 shrink-0" />
                            <span class="line-clamp-1">{{ $store->address }}, {{ $store->city }}</span>
                        </div>

                        <div
                            class="flex items-center gap-2 md:gap-3 mt-0.5 md:mt-1 text-[10px] md:text-sm text-gray-600">
                            <div class="flex items-center gap-1 md:gap-1.5 font-medium">
                                <x-heroicon-s-cube class="w-3 h-3 md:w-5 md:h-5 text-[#8B4513]" />
                                <span>{{ $store->products_count }} Produk</span>
                            </div>
                            <div class="w-1 h-1 md:w-1.5 md:h-1.5 bg-gray-300 rounded-full"></div>
                            <div class="flex items-center gap-1 md:gap-1.5 font-medium">
                                <x-heroicon-s-shopping-bag class="w-3 h-3 md:w-5 md:h-5 text-[#8B4513]" />
                                <span>0 terjual</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($store->whatsapp_url !== '#')
<a href="{{ $store->whatsapp_url }}" target="_blank"
    class="w-full flex justify-center items-center gap-2 px-4 py-2.5 rounded-lg bg-[#0F4C20] text-white font-bold text-xs md:text-sm hover:bg-[#0b3a18] transition shadow-sm">
    <img src="https://img.icons8.com/ios-filled/50/ffffff/whatsapp.png" class="w-4 h-4 md:w-5 md:h-5">
    Hubungi Penjual
</a>
@else
<span class="w-full flex justify-center items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-100 text-gray-400 font-bold text-xs md:text-sm cursor-not-allowed">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/>
    </svg>
    Kontak Belum Tersedia
</span>
@endif

            </div>
        </div>
    </section>

    <section class="pb-20 px-4 sm:px-6 lg:px-8" x-data="{ isFilterOpen: false }">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6 md:gap-8">

            <div class="hidden lg:block lg:w-1/4 shrink-0">
                <x-ui.sidebarfilter title="Atur Pilihanmu">
                    <form action="{{ url()->current() }}" method="GET" id="storeFilterForm">
                        @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div class="space-y-2">
                            <h4 class="text-sm font-bold text-gray-800">Pilih Kategori</h4>
                            <div class="space-y-2">
                                @foreach($categoriesList as $catName)
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" class="peer hidden" name="category[]" value="{{ $catName }}"
                                        @checked(in_array($catName, request('category', [])))
                                        onchange="this.form.submit()">
                                    <div
                                        class="w-4 h-4 rounded border-2 border-gray-300 bg-white peer-checked:bg-[#0F4C20] peer-checked:border-[#0F4C20] transition flex items-center justify-center text-white shrink-0">
                                        <x-heroicon-s-check class="w-3 h-3" />
                                    </div>
                                    <span
                                        class="text-sm font-medium text-gray-600 peer-checked:text-[#0F4C20] peer-checked:font-bold transition">
                                        {{ $catName }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <hr class="border-gray-100 my-4">

                        <div class="space-y-2">
                            <h4 class="text-sm font-bold text-gray-800">Sesuaikan Harga</h4>
                            <div class="space-y-2">
                                @foreach(['Harga Tertinggi', 'Harga Terjangkau'] as $priceSort)
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="radio" name="sort_price" class="peer hidden" value="{{ $priceSort }}"
                                        @checked(request('sort_price')==$priceSort) onchange="this.form.submit()">
                                    <div
                                        class="w-4 h-4 rounded-full border-2 border-gray-300 bg-white peer-checked:border-[#0F4C20] peer-checked:bg-white flex items-center justify-center shrink-0">
                                        <div
                                            class="w-2 h-2 rounded-full bg-[#0F4C20] opacity-0 group-has-[:checked]:opacity-100 transition">
                                        </div>
                                    </div>
                                    <span
                                        class="text-sm font-medium text-gray-600 peer-checked:text-[#0F4C20] peer-checked:font-bold transition">
                                        {{ $priceSort }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </x-ui.sidebarfilter>
            </div>

            <div class="flex-1 w-full">

                <div class="flex flex-col gap-4 mb-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 md:gap-4">

                        <div class="flex items-center gap-1.5 md:gap-2 text-sm md:text-lg">
                            <span class="text-gray-500 font-medium">Menampilkan</span>
                            <span class="font-bold text-[#0F4C20]">
                                @if(request('search') || request('category') || request('sort_price'))
                                {{ $products->total() }} Produk
                                @else
                                Semua Produk Toko
                                @endif
                            </span>
                        </div>

                        <div class="flex w-full md:w-auto gap-2">
                            <form action="{{ url()->current() }}" method="GET" class="relative flex-1 md:w-[320px]">
                                @foreach(request('category', []) as $cat)
                                <input type="hidden" name="category[]" value="{{ $cat }}">
                                @endforeach

                                @if(request('sort_price'))
                                <input type="hidden" name="sort_price" value="{{ request('sort_price') }}">
                                @endif

                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari di Toko Ini"
                                    class="w-full pl-3 md:pl-4 pr-10 py-2.5 rounded-lg border-2 border-[#0F4C20] focus:outline-none focus:ring-2 focus:ring-green-200 text-xs md:text-sm font-medium placeholder-gray-400">

                                <button type="submit"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#0F4C20]">
                                    <x-heroicon-o-magnifying-glass class="w-4 h-4 md:w-5 md:h-5" />
                                </button>
                            </form>

                            <button @click="isFilterOpen = true" type="button"
                                class="lg:hidden flex items-center justify-center gap-2 bg-white border-2 border-[#0F4C20] text-[#0F4C20] px-3 py-2.5 rounded-lg font-bold text-xs shrink-0 active:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                                </svg>
                                Filter
                            </button>
                        </div>
                    </div>

                    @if(request('category') || request('sort_price') || request('search'))
                    <div class="flex flex-wrap items-center gap-2 md:gap-3 animate-fade-in">
                        <span class="text-[10px] md:text-sm font-medium text-gray-600">Pilihanmu:</span>

                        @if(request('search'))
                        <div
                            class="inline-flex items-center gap-1.5 px-2 md:px-3 py-1 bg-white border border-[#0F4C20] rounded-full shadow-sm">
                            <span class="text-[9px] md:text-[11px] font-bold text-[#0F4C20] uppercase tracking-wide">"{{
                                request('search') }}"</span>
                            <a href="{{ url()->current() }}?{{ http_build_query(request()->except('search')) }}"
                                class="text-[#0F4C20] hover:text-red-500 transition">
                                <x-heroicon-s-x-mark class="w-3 h-3 md:w-4 md:h-4" />
                            </a>
                        </div>
                        @endif

                        @if(request('category'))
                        @foreach(request('category') as $cat)
                        <div
                            class="inline-flex items-center gap-1.5 px-2 md:px-3 py-1 bg-white border border-[#0F4C20] rounded-full shadow-sm">
                            <span class="text-[9px] md:text-[11px] font-bold text-[#0F4C20] uppercase tracking-wide">{{
                                $cat }}</span>
                            @php
                            $catParams = request()->except('category');
                            $remainingCats = array_diff(request('category', []), [$cat]);
                            if(!empty($remainingCats)) $catParams['category'] = $remainingCats;
                            @endphp
                            <a href="{{ url()->current() }}?{{ http_build_query($catParams) }}"
                                class="text-[#0F4C20] hover:text-red-500 transition">
                                <x-heroicon-s-x-mark class="w-3 h-3 md:w-4 md:h-4" />
                            </a>
                        </div>
                        @endforeach
                        @endif

                        @if(request('sort_price'))
                        <div
                            class="inline-flex items-center gap-1.5 px-2 md:px-3 py-1 bg-white border border-[#0F4C20] rounded-full shadow-sm">
                            <span class="text-[9px] md:text-[11px] font-bold text-[#0F4C20] uppercase tracking-wide">{{
                                request('sort_price') }}</span>
                            <a href="{{ url()->current() }}?{{ http_build_query(request()->except('sort_price')) }}"
                                class="text-[#0F4C20] hover:text-red-500 transition">
                                <x-heroicon-s-x-mark class="w-3 h-3 md:w-4 md:h-4" />
                            </a>
                        </div>
                        @endif

                        <a href="{{ url()->current() }}"
                            class="text-[10px] md:text-xs text-red-600 font-semibold hover:underline ml-1 decoration-red-600">
                            Hapus Semua
                        </a>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-3 gap-3 md:gap-6">
                    @forelse($products as $product)
                    <x-ui.product-card :item="$product" />
                    @empty
                    <div class="col-span-full py-12 text-center">
                        <div class="inline-flex bg-gray-100 p-4 rounded-full mb-3">
                            <x-heroicon-o-face-frown class="w-6 h-6 md:w-8 h-8 text-gray-400" />
                        </div>
                        <h3 class="text-sm md:text-lg font-bold text-gray-700">Produk Tidak Ditemukan</h3>
                        <p class="text-gray-500 text-xs md:text-sm mt-1">Toko ini belum memiliki produk dengan kriteria
                            tersebut.</p>
                        <a href="{{ url()->current() }}"
                            class="text-[#0F4C20] font-bold text-xs md:text-sm mt-3 inline-block hover:underline">
                            Lihat Semua Produk Toko
                        </a>
                    </div>
                    @endforelse
                </div>

                <div class="mt-8 flex justify-center w-full overflow-x-auto pb-4">
                    {{ $products->links() }}
                </div>

            </div>
        </div>

        <div x-show="isFilterOpen" style="display: none;"
            class="fixed inset-0 z-[100] lg:hidden flex justify-end flex-col">

            <div x-show="isFilterOpen" x-transition.opacity.duration.300ms @click="isFilterOpen = false"
                class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

            <div x-show="isFilterOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full"
                class="relative bg-white w-full max-h-[90vh] rounded-t-3xl shadow-2xl flex flex-col overflow-hidden">

                <div class="flex items-center justify-between p-4 border-b border-gray-100 shrink-0">
                    <h3 class="font-bold text-lg text-gray-800">Atur Pilihanmu</h3>
                    <button @click="isFilterOpen = false"
                        class="p-2 bg-gray-100 rounded-full text-gray-500 hover:text-red-500">
                        <x-heroicon-s-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-5 overflow-y-auto flex-1">
                    <form action="{{ url()->current() }}" method="GET" id="filter-form-mobile" class="space-y-6">
                        @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div class="space-y-3">
                            <h4 class="font-bold text-gray-800">Kategori</h4>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($categoriesList as $catName)
                                <label
                                    class="flex items-center justify-between p-3 border rounded-xl cursor-pointer group has-[:checked]:border-[#0F4C20] has-[:checked]:bg-[#f4fbf4] transition">
                                    <span
                                        class="text-sm font-medium text-gray-700 group-has-[:checked]:text-[#0F4C20] group-has-[:checked]:font-bold">{{
                                        $catName }}</span>
                                    <input type="checkbox" class="hidden" name="category[]" value="{{ $catName }}"
                                        @checked(in_array($catName, request('category', [])))>
                                    <div
                                        class="w-5 h-5 rounded border-2 border-gray-300 bg-white group-has-[:checked]:bg-[#0F4C20] group-has-[:checked]:border-[#0F4C20] transition flex items-center justify-center text-white shrink-0">
                                        <x-heroicon-s-check
                                            class="w-3.5 h-3.5 opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200" />
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h4 class="font-bold text-gray-800">Harga</h4>
                            <div class="grid grid-cols-1 gap-3">
                                @foreach(['Harga Tertinggi', 'Harga Terjangkau'] as $priceSort)
                                <label
                                    class="flex items-center justify-between p-3 border rounded-xl cursor-pointer group has-[:checked]:border-[#0F4C20] has-[:checked]:bg-[#f4fbf4] transition">
                                    <span
                                        class="text-sm font-medium text-gray-700 group-has-[:checked]:text-[#0F4C20] group-has-[:checked]:font-bold">{{
                                        $priceSort }}</span>
                                    <input type="radio" class="hidden" name="sort_price" value="{{ $priceSort }}"
                                        @checked(request('sort_price')==$priceSort)>
                                    <div
                                        class="w-5 h-5 rounded-full border-2 border-gray-300 bg-white group-has-[:checked]:border-[#0F4C20] flex items-center justify-center shrink-0 transition">
                                        <div
                                            class="w-2.5 h-2.5 rounded-full bg-[#0F4C20] opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200">
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>

                <div class="p-4 pb-8 border-t border-gray-100 shrink-0 grid grid-cols-2 gap-3 bg-white">
                    <a href="{{ url()->current() }}"
                        class="w-full flex justify-center items-center py-3.5 rounded-xl border border-gray-300 text-gray-600 font-bold text-sm hover:bg-gray-50 transition">
                        Reset
                    </a>
                    <button type="button" onclick="document.getElementById('filter-form-mobile').submit()"
                        class="w-full py-3.5 rounded-xl bg-[#0F4C20] hover:bg-[#0a3616] transition text-white font-bold text-sm shadow-md">
                        Terapkan
                    </button>
                </div>
            </div>
        </div>

    </section>

    <x-footer />

</body>

</html>