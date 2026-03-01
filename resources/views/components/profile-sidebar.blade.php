<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden lg:sticky lg:top-28">

    <div class="p-4 md:p-6 border-b border-gray-100 flex items-center gap-3 md:gap-4">
        <div
            class="w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center border border-[#496030] overflow-hidden shrink-0 shadow-sm">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0F4C20&color=fff"
                class="font-bold w-full h-full object-cover">
        </div>
        <div class="overflow-hidden min-w-0 flex-1">
            <p class="text-[10px] md:text-xs text-gray-500 leading-tight">Halo,</p>
            <h3 class="font-bold text-sm md:text-base text-[#2E3B27] truncate">{{ Auth::user()->name }}</h3>
        </div>
    </div>

    <nav class="p-2.5 md:p-3 flex flex-row md:flex-col gap-2 md:gap-1 overflow-x-auto no-scrollbar">

        {{-- Link: Profil Saya --}}
        <a href="{{ route('profile.index') }}"
            class="shrink-0 flex items-center gap-1.5 md:gap-3 px-3 py-2 md:px-4 md:py-3 rounded-lg text-xs md:text-sm font-medium transition border md:border-none
           {{ request()->routeIs('profile.index') ? 'bg-[#0F4C20] border-[#0F4C20] text-white font-bold shadow-sm' : 'text-gray-600 bg-gray-50 border-gray-200 md:bg-transparent hover:bg-green-50 hover:text-[#0F4C20] hover:border-green-100' }}">
            <x-heroicon-s-user class="w-4 h-4 md:w-5 md:h-5" />
            Profil Saya
        </a>

        {{-- Link: Alamat Saya --}}
        <a href="{{ route('profile.address') }}"
            class="shrink-0 flex items-center gap-1.5 md:gap-3 px-3 py-2 md:px-4 md:py-3 rounded-lg text-xs md:text-sm font-medium transition border md:border-none
           {{ request()->routeIs('profile.address') ? 'bg-[#0F4C20] border-[#0F4C20] text-white font-bold shadow-sm' : 'text-gray-600 bg-gray-50 border-gray-200 md:bg-transparent hover:bg-green-50 hover:text-[#0F4C20] hover:border-green-100' }}">
            <x-heroicon-s-map-pin class="w-4 h-4 md:w-5 md:h-5" />
            Alamat Saya
        </a>

        {{-- Link: Pesanan Saya --}}
        <a href="{{ route('profile.orders') }}"
            class="shrink-0 flex items-center gap-1.5 md:gap-3 px-3 py-2 md:px-4 md:py-3 rounded-lg text-xs md:text-sm font-medium transition border md:border-none
           {{ request()->routeIs('profile.orders') ? 'bg-[#0F4C20] border-[#0F4C20] text-white font-bold shadow-sm' : 'text-gray-600 bg-gray-50 border-gray-200 md:bg-transparent hover:bg-green-50 hover:text-[#0F4C20] hover:border-green-100' }}">
            <x-heroicon-s-shopping-bag class="w-4 h-4 md:w-5 md:h-5" />
            Pesanan Saya
        </a>

        <div class="hidden md:block border-t border-gray-100 my-1"></div>

        {{-- Link: Keluar --}}
        <form method="POST" action="{{ route('logout') }}" class="shrink-0">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-1.5 md:gap-3 px-3 py-2 md:px-4 md:py-3 rounded-lg text-xs md:text-sm font-bold text-red-600 bg-red-50 border border-red-100 md:border-none md:bg-transparent hover:bg-red-100 md:hover:bg-red-50 transition text-left">
                <x-heroicon-s-arrow-left-start-on-rectangle class="w-4 h-4 md:w-5 md:h-5" />
                Keluar
            </button>
        </form>

    </nav>
</div>