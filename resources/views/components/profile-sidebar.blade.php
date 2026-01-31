<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden sticky top-28">

    <div class="p-6 border-b border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full flex items-center justify-center border border-[#496030] overflow-hidden">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0F4C20&color=fff"
                class="font-bold">
        </div>
        <div class="overflow-hidden">
            <p class="text-xs text-gray-500">Halo,</p>
            <h3 class="font-bold text-[#2E3B27] truncate">{{ Auth::user()->name }}</h3>
        </div>
    </div>

    <nav class="p-3 flex flex-col gap-1">

        {{-- Link: Profil Saya --}}
        <a href="{{ route('profile.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition
           {{ request()->routeIs('profile.index') ? 'bg-[#0F4C20] text-white font-bold' : 'text-gray-600 hover:bg-green-50 hover:text-[#0F4C20]' }}">
            <x-heroicon-s-user class="w-5 h-5" />
            Profil Saya
        </a>

        {{-- Link: Alamat Saya (Nanti dibuat routenya) --}}
        <a href="{{ route('profile.address') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition
           {{ request()->routeIs('profile.address') ? 'bg-[#0F4C20] text-white font-bold' : 'text-gray-600 hover:bg-green-50 hover:text-[#0F4C20]' }}">
            <x-heroicon-s-map-pin class="w-5 h-5" />
            Alamat Saya
        </a>

        {{-- Link: Pesanan Saya --}}
        <a href="{{ route('profile.orders') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition
           {{ request()->routeIs('profile.orders') ? 'bg-[#0F4C20] text-white font-bold' : 'text-gray-600 hover:bg-green-50 hover:text-[#0F4C20]' }}">
            <x-heroicon-s-shopping-bag class="w-5 h-5" />
            Pesanan Saya
        </a>

        <div class="border-t border-gray-100 my-1"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-bold text-red-600 hover:bg-red-50 transition text-left">
                <x-heroicon-s-arrow-left-start-on-rectangle class="w-5 h-5" />
                Keluar
            </button>
        </form>
    </nav>
</div>