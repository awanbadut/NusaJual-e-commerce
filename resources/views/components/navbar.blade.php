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

                {{-- @auth
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false"
                        class="flex items-center gap-2 py-1 text-[#4E582C] font-bold border-b-2 border-transparent hover:text-[#0F4C20] hover:border-[#0F4C20] transition-all group">

                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0F4C20&color=fff"
                            class="w-6 h-6 rounded-full border border-gray-200">

                        <span class="max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                        <x-heroicon-s-chevron-down class="w-3 h-3 text-gray-400 group-hover:text-[#0F4C20]" />
                    </button>

                    <div x-show="profileOpen"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.08)] py-2 border border-gray-100 z-50 origin-top-right"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        style="display: none;">

                        <div class="px-4 py-2 border-b border-gray-50 mb-1">
                            <p class="text-xs text-gray-400">Halo,</p>
                            <p class="font-bold text-[#0F4C20] truncate">{{ Auth::user()->name }}</p>
                        </div>

                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#0F4C20]">
                            Profile Saya
                        </a>

                        {{-- Pastikan route 'orders.index' sudah ada, kalau belum hapus href-nya jadi # --}}
                        {{-- <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#0F4C20]">
                            Pesanan Saya
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold flex items-center gap-2">
                                <x-heroicon-o-arrow-left-start-on-rectangle class="w-4 h-4" />
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @else --}}
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