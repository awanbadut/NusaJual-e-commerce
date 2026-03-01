<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog - Nusa Belanja</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <section class="pt-24 pb-6 px-4 md:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto">
            <div
                class="relative w-full h-[180px] md:h-[320px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-4 md:p-6 shadow-sm">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('img/pattern-kopi1.webp'); background-size: cover; background-position: center;">
                </div>
                <div class="relative z-10 flex flex-col gap-1 md:gap-2">
                    <h1 class="text-2xl md:text-[56px] font-bold text-[#0F4C20] leading-tight">
                        Semua Pilihan Terbaik
                    </h1>
                    <p class="text-xs md:text-2xl font-medium text-[#8B4513]">
                        Produk lokal berkualitas, tinggal pilih & pesan
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-16 md:pb-20 px-4 md:px-6 lg:px-8" x-data="{ isFilterOpen: false }">
        <div class="max-w-[1440px] mx-auto flex flex-col lg:flex-row gap-6 md:gap-8">

            <div class="hidden lg:block lg:w-1/4 shrink-0">
                <x-ui.sidebarfilter title="Filter Produk">
                    <form action="{{ route('katalog') }}" method="GET" id="filter-form-desktop">
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
                                        onchange="document.getElementById('filter-form-desktop').submit()">
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
                                        @checked(request('sort_price')==$priceSort)
                                        onchange="document.getElementById('filter-form-desktop').submit()">
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
                                Semua Produk
                                @endif
                            </span>
                        </div>

                        <div class="flex w-full md:w-auto gap-2">
                            <form action="{{ route('katalog') }}" method="GET" class="relative flex-1 md:w-[320px]">
                                @if(request('category'))
                                @foreach(request('category') as $cat)
                                <input type="hidden" name="category[]" value="{{ $cat }}">
                                @endforeach
                                @endif

                                @if(request('sort_price'))
                                <input type="hidden" name="sort_price" value="{{ request('sort_price') }}">
                                @endif

                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari produk..."
                                    class="w-full pl-3 md:pl-4 pr-10 py-2.5 rounded-lg border-2 border-[#0F4C20] focus:outline-none focus:ring-2 focus:ring-green-200 text-xs md:text-sm font-medium placeholder-gray-400">

                                <button type="submit"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#0F4C20] transition">
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
                            <a href="{{ route('katalog', request()->except('search')) }}"
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
                            <a href="{{ route('katalog', array_merge(request()->except('category'), request()->has('search') ? ['search' => request('search')] : [], request()->has('sort_price') ? ['sort_price' => request('sort_price')] : [])) }}"
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
                            <a href="{{ route('katalog', request()->except('sort_price')) }}"
                                class="text-[#0F4C20] hover:text-red-500 transition">
                                <x-heroicon-s-x-mark class="w-3 h-3 md:w-4 md:h-4" />
                            </a>
                        </div>
                        @endif

                        <a href="{{ route('katalog') }}"
                            class="text-[10px] md:text-xs text-red-600 font-semibold hover:underline ml-1 decoration-red-600">
                            Hapus Semua
                        </a>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-3 md:gap-6">
                    @forelse($products as $product)
                    <x-ui.product-card :item="$product" />
                    @empty
                    <div class="col-span-full py-12 text-center">
                        <div class="inline-flex bg-gray-100 p-4 rounded-full mb-3">
                            <x-heroicon-o-face-frown class="w-6 h-6 md:w-8 md:h-8 text-gray-400" />
                        </div>
                        <h3 class="text-sm md:text-lg font-bold text-gray-700">Produk Tidak Ditemukan</h3>
                        <p class="text-gray-500 text-xs md:text-sm mt-1">
                            @if(request('search'))
                            Tidak ada produk dengan kata kunci "<strong>{{ request('search') }}</strong>"
                            @else
                            Coba kata kunci lain atau kurangi filter.
                            @endif
                        </p>
                        <a href="{{ route('katalog') }}"
                            class="text-[#0F4C20] font-bold text-xs md:text-sm mt-3 inline-block hover:underline">Reset
                            Filter</a>
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
                    <h3 class="font-bold text-lg text-gray-800">Filter Produk</h3>
                    <button @click="isFilterOpen = false"
                        class="p-2 bg-gray-100 rounded-full text-gray-500 hover:text-red-500">
                        <x-heroicon-s-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-5 overflow-y-auto flex-1">
                    <form action="{{ route('katalog') }}" method="GET" id="filter-form-mobile" class="space-y-6">

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
                    <a href="{{ route('katalog') }}"
                        class="w-full flex items-center justify-center py-3.5 rounded-xl border border-gray-300 text-gray-600 font-bold text-sm hover:bg-gray-50 transition">
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