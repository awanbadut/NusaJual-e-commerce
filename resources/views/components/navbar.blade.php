<nav class="fixed top-0 w-full z-50 bg-white border-b border-gray-200 shadow-sm" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">

            <div class="flex items-center gap-3">
                <div class="bg-[#0F4C20] rounded px-3 py-2 text-white font-bold tracking-tight text-label-2">
                    Logo
                </div>
                <span class="text-h5 font-bold text-gray-700">Nusa Belanja</span>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                @if(request()->routeIs('home'))
                <a href="{{ route('home') }}"
                    class="flex items-center gap-2 py-1 text-[#0F4C20] font-bold border-b-2 border-[#0F4C20]">
                    <x-heroicon-s-home class="w-5 h-5" />
                    <span>Home</span>
                </a>
                @else
                <a href="{{ route('home') }}"
                    class="flex items-center gap-2 py-1 text-[#4E582C] font-bold border-b-2 border-transparent hover:text-[#0F4C20] hover:border-[#0F4C20] transition-all">
                    <x-heroicon-o-home class="w-5 h-5" />
                    <span>Home</span>
                </a>
                @endif

                @if(request()->routeIs(['katalog', 'produk.show', 'profil-mitra']))
                <a href="{{ route('katalog') }}"
                    class="flex items-center gap-2 py-1 text-[#0F4C20] font-bold border-b-2 border-[#0F4C20]">
                    <x-heroicon-s-building-storefront class="w-5 h-5" />
                    <span>Katalog</span>
                </a>
                @else
                <a href="{{ route('katalog') }}"
                    class="flex items-center gap-2 py-1 text-[#4E582C] font-bold border-b-2 border-transparent hover:text-[#0F4C20] hover:border-[#0F4C20] transition-all group">
                    <x-heroicon-o-building-storefront class="w-5 h-5 group-hover:text-[#0F4C20]" />
                    <span>Katalog</span>
                </a>
                @endif

                @if(request()->routeIs(['keranjang','checkout','payment','success']))
                <a href="{{ route('keranjang') }}"
                    class="flex items-center gap-2 py-1 text-[#0F4C20] font-bold border-b-2 border-[#0F4C20]">
                    <x-heroicon-s-shopping-cart class="w-5 h-5" />
                    <span>Keranjang</span>
                </a>
                @else
                <a href="{{ route('keranjang') }}"
                    class="flex items-center gap-2 py-1 text-[#4E582C] font-bold border-b-2 border-transparent hover:text-[#0F4C20] hover:border-[#0F4C20] transition-all group">
                    <x-heroicon-o-shopping-cart class="w-5 h-5 group-hover:text-[#0F4C20]" />
                    <span>Keranjang</span>
                </a>
                @endif

                @auth
                <a href="#"
                    class="flex items-center gap-2 py-1 text-[#4E582C] font-bold border-b-2 border-transparent hover:text-[#0F4C20] hover:border-[#0F4C20] transition-all group">
                    <x-heroicon-o-user class="w-5 h-5 group-hover:text-[#0F4C20]" />
                    <span>Profile</span>
                </a>
                @else
                {{-- ✅ FIXED: Line 62 --}}
                <a href="{{ route('login.pembeli') }}"
                    class="flex items-center gap-2 py-1 text-[#4E582C] font-bold border-b-2 border-transparent hover:text-[#0F4C20] hover:border-[#0F4C20] transition-all group">
                    <x-heroicon-o-arrow-right-end-on-rectangle class="w-5 h-5 group-hover:text-[#0F4C20]" />
                    <span>Masuk</span>
                </a>
                @endauth
            </div>

            <div class="md:hidden">
                <button @click="open = !open" class="text-gray-500 hover:text-[#0F4C20] focus:outline-none">
                    <x-heroicon-o-bars-3 class="w-8 h-8" x-show="!open" />
                    <x-heroicon-o-x-mark class="w-8 h-8" x-show="open" style="display: none;" />
                </button>
            </div>

        </div>
    </div>

    <div class="md:hidden bg-white border-t border-gray-100 shadow-lg" x-show="open"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        <div class="px-4 pt-2 pb-4 space-y-1">
            <a href="{{ route('home') }}"
                class="block px-3 py-2 rounded-md text-base font-bold text-[#0F4C20] bg-green-50">Home</a>
            <a href="{{ route('katalog') }}"
                class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs(['katalog', 'detail-produk', 'profil-mitra']) ? 'text-[#0F4C20] bg-green-50 font-bold' : 'text-gray-700 hover:text-[#0F4C20] hover:bg-gray-50' }}">
                Katalog
            </a>
            <a href="{{ route('keranjang') }}"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-[#0F4C20] hover:bg-gray-50">Keranjang</a>
            {{-- ✅ FIXED: Mobile menu --}}
            <a href="{{ route('login.pembeli') }}"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-[#0F4C20] hover:bg-gray-50">Masuk</a>
        </div>
    </div>
</nav>