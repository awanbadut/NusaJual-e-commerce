<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store->store_name }} - Nusa Belanja</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <div class="pt-24 pb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex gap-2 text-sm font-medium text-[#8B4513]">
            <a href="{{ route('katalog') }}" class="hover:underline">Katalog</a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <a href="#" class="hover:underline">Mitra</a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="text-[#0F4C20] font-bold">{{ $store->store_name }}</span>
        </div>
    </div>

    <section class="px-4 sm:px-6 lg:px-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row items-center gap-6">

                <div class="w-32 h-32 md:w-[136px] md:h-[136px] rounded-lg overflow-hidden shrink-0 bg-gray-100">
                    <img src="{{ $store->logo ? asset('storage/'.$store->logo) : 'https://placehold.co/200x200/green/white?text='.substr($store->store_name, 0, 1) }}"
                        class="w-full h-full object-cover">
                </div>

                <div class="flex-1 flex flex-col items-center md:items-start text-center md:text-left gap-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-[#2E3B27]">{{ $store->store_name }}</h1>

                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <x-heroicon-s-map-pin class="w-5 h-5 text-gray-400" />
                        <span>{{ $store->address }}, {{ $store->city }}</span>
                    </div>

                    <div class="flex items-center gap-3 mt-1 text-sm text-gray-600">
                        <div class="flex items-center gap-1.5 font-medium">
                            <x-heroicon-s-cube class="w-5 h-5 text-[#8B4513]" />
                            <span>{{ $store->products_count }} Produk</span>
                        </div>
                        <div class="w-1.5 h-1.5 bg-gray-300 rounded-full"></div>
                        <div class="flex items-center gap-1.5 font-medium">
                            <x-heroicon-s-shopping-bag class="w-5 h-5 text-[#8B4513]" />
                            <span>0 terjual</span>
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
                                    @checked(in_array($catName, request('category', []))) onchange="this.form.submit()">
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

            <div class="flex-1">

                <div class="flex flex-col gap-4 mb-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-lg">
                            <span class="text-gray-500 font-medium">Menampilkan</span>
                            <span class="font-bold text-[#0F4C20]">
                                @if(request('search') || request('category') || request('sort_price'))
                                {{ $products->total() }} Produk
                                @else
                                Semua Produk
                                @endif
                            </span>
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

                <div class="grid grid-cols-1 sm:grid-cols-3 xl:grid-cols-3 gap-6">
                    @forelse($products as $product)
                    <x-ui.product-card :item="$product" />
                    @empty
                    <div class="col-span-full py-12 text-center">
                        <div class="inline-flex bg-gray-100 p-4 rounded-full mb-3">
                            <x-heroicon-o-face-frown class="w-8 h-8 text-gray-400" />
                        </div>
                        <h3 class="text-lg font-bold text-gray-700">Produk Tidak Ditemukan</h3>
                        <p class="text-gray-500 text-sm">Toko ini belum memiliki produk dengan kriteria tersebut.</p>
                        <a href="{{ url()->current() }}"
                            class="text-[#0F4C20] font-bold text-sm mt-2 inline-block hover:underline">
                            Lihat Semua Produk Toko
                        </a>
                    </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>

            </div>

        </div>
    </section>

    <x-footer />

</body>

</html>