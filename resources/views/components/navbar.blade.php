<nav class="fixed top-0 w-full z-50 bg-white border-b border-gray-200 shadow-sm" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">

            <div class="flex items-center">
                <img src="{{ asset('img/logo/1.png') }}" alt="Icon Nusa Belanja" class="h-15 w-auto object-contain">

                <img src="{{ asset('img/logo/2.png') }}" alt="Nusa Belanja" class="h-45 w-auto object-contain">
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

                @if(request()->routeIs(['keranjang','checkout.review','payment.success','payment.process','payment.show']))
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
                @if(request()->routeIs(['profile.index', 'profile.update', 'profile.address', 'profile.orders']))
                <a href="{{ route('profile.index') }}"
                    class="flex items-center gap-2 py-1 text-[#0F4C20] font-bold border-b-2 border-[#0F4C20]">
                    <x-heroicon-s-user class="w-5 h-5 group-hover:text-[#0F4C20]" />
                    <span>Profile</span>
                </a>
                @else
                <a href="{{ route('profile.index') }}"
                    class="flex items-center gap-2 py-1 text-[#4E582C] font-bold border-b-2 border-transparent hover:text-[#0F4C20] hover:border-[#0F4C20] transition-all group">
                    <x-heroicon-o-user class="w-5 h-5 group-hover:text-[#0F4C20]" />
                    <span>Profile</span>
                </a>
                @endif
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

        <div class="px-4 pt-2 pb-6 space-y-2">

            <a href="{{ route('home') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-base {{ request()->routeIs('home') ? 'bg-green-50 text-[#0F4C20] font-bold' : 'text-[#4E582C] font-medium hover:bg-gray-50' }}">
                <x-heroicon-o-home
                    class="w-6 h-6 {{ request()->routeIs('home') ? 'text-[#0F4C20]' : 'text-gray-400' }}" />
                <span>Home</span>
            </a>

            <a href="{{ route('katalog') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-base {{ request()->routeIs(['katalog', 'produk.show', 'profil-mitra']) ? 'bg-green-50 text-[#0F4C20] font-bold' : 'text-[#4E582C] font-medium hover:bg-gray-50' }}">
                <x-heroicon-o-building-storefront
                    class="w-6 h-6 {{ request()->routeIs(['katalog', 'produk.show', 'profil-mitra']) ? 'text-[#0F4C20]' : 'text-gray-400' }}" />
                <span>Katalog</span>
            </a>

            <a href="{{ route('keranjang') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-base {{ request()->routeIs(['keranjang','checkout.*','payment.*']) ? 'bg-green-50 text-[#0F4C20] font-bold' : 'text-[#4E582C] font-medium hover:bg-gray-50' }}">
                <x-heroicon-o-shopping-cart
                    class="w-6 h-6 {{ request()->routeIs(['keranjang','checkout.*','payment.*']) ? 'text-[#0F4C20]' : 'text-gray-400' }}" />
                <span>Keranjang</span>
            </a>

            <div class="border-t border-gray-100 my-2"></div>

            @auth
            <a href="{{ route('profile.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-base {{ request()->routeIs('profile.*') ? 'bg-green-50 text-[#0F4C20] font-bold' : 'text-[#4E582C] font-medium hover:bg-gray-50' }}">
                <x-heroicon-o-user
                    class="w-6 h-6 {{ request()->routeIs('profile.*') ? 'text-[#0F4C20]' : 'text-gray-400' }}" />
                <span>Profile Saya</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-base text-red-600 font-bold hover:bg-red-50">
                    <x-heroicon-o-arrow-left-start-on-rectangle class="w-6 h-6 text-red-600" />
                    <span>Keluar</span>
                </button>
            </form>
            @else
            <a href="{{ route('login.pembeli') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-base text-[#4E582C] font-bold hover:bg-gray-50">
                <x-heroicon-o-arrow-right-end-on-rectangle class="w-6 h-6 text-gray-400" />
                <span>Masuk</span>
            </a>
            @endauth

        </div>
    </div>
</nav>