<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Nusa Belanja')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head')
</head>

<body class="bg-[#F8FCF8]">
    <div class="flex flex-col h-screen overflow-hidden">

        <nav
            class="bg-white border-b border-gray-200 h-16 flex items-center px-6 sticky top-0 z-50 flex-shrink-0 shadow-sm">
            <div class="flex justify-between items-center w-full">
                <div class="flex items-center gap-3 w-56">
                    <div class="bg-green-800 text-white px-3 py-2 font-bold text-sm rounded"> Logo
                    </div>
                    <span class="text-base font-bold text-gray-900">Nusa Belanja</span>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">
                        {{ auth()->user()->name ?? 'Admin Nusa Belanja' }}
                    </span>
                    <div
                        class="w-9 h-9 rounded-full bg-green-800 text-white flex items-center justify-center font-semibold text-sm">
                        {{ isset(auth()->user()->name) ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'A' }}
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex flex-1 overflow-hidden">

            <aside class="w-56 bg-white border-r border-gray-200 overflow-y-auto flex-shrink-0">
                <div class="p-3 space-y-1">

                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition
                               {{ request()->routeIs('admin.dashboard') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">

                        <x-heroicon-o-squares-2x2 class="w-5 h-5" />
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>

                    {{-- Mitra (dropdown) --}}
                    <div> @php
                        $isMitraActive = request()->routeIs('admin.mitra.*');
                        @endphp

                        <button type="button"
                            onclick="document.getElementById('mitraDropdown').classList.toggle('hidden');
                                                     document.getElementById('mitraArrow').classList.toggle('rotate-180');"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition
                                   {{ $isMitraActive ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                            <div class="flex items-center gap-3">
                                <x-heroicon-o-building-storefront class="w-5 h-5" />
                                <span class="text-sm font-medium">Mitra</span>
                            </div>

                            <div id="mitraArrow"
                                class="transition-transform duration-200 {{ $isMitraActive ? 'rotate-180' : '' }}">
                                <x-heroicon-o-chevron-down class="w-3 h-3" />
                            </div>
                        </button>

                        <div id="mitraDropdown" class="mt-1 ml-4 space-y-1 {{ $isMitraActive ? '' : 'hidden' }}">
                            {{-- Link semua mitra --}}
                            <a href="{{ route('admin.mitra.index') }}"
                                class="block px-3 py-2 rounded-lg text-xs font-medium border-l-2
                                       {{ request()->routeIs('admin.mitra.index') ? 'border-green-800 bg-green-50 text-green-800' : 'border-transparent text-gray-600 hover:bg-gray-50' }}">
                                Semua Mitra
                            </a>

                            {{-- 5 mitra pertama --}}
                            @foreach($sidebarStores ?? [] as $store)
                            <a href="{{ route('admin.mitra.show', $store->id) }}" class="block px-3 py-2 rounded-lg text-xs border-l-2
                                      {{ request()->routeIs('admin.mitra.show') && request()->route('id') == $store->id
                                            ? 'border-green-800 bg-green-50 text-green-800 font-medium'
                                            : 'border-transparent text-gray-600 hover:bg-gray-50' }}">
                                {{ $store->store_name }}
                            </a>
                            @endforeach

                            {{-- Tombol lihat semua --}}
                            @if(($allSidebarStores ?? collect())->count() > 5)
                            <button type="button"
                                onclick="document.getElementById('allMitraModal').classList.remove('hidden');"
                                class="w-full text-left px-3 py-2 rounded-lg text-xs text-blue-600 hover:bg-blue-50 font-medium pl-3.5">
                                Lihat semua mitra…
                            </button>
                            @endif
                        </div>
                    </div>

                    {{-- Logout --}}
                    <form action="{{ route('admin.logout') }}" method="POST" class="pt-4 mt-4 border-t border-gray-100">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-600 hover:bg-red-50 w-full text-left transition">
                            <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5" />
                            <span class="text-sm font-medium">Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <main class="flex-1 overflow-y-auto">
                <div class="p-8"> @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Modal Semua Mitra --}}
    @if(isset($allSidebarStores))
    <div id="allMitraModal" class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md max-h-[80vh] flex flex-col">
            <div class="px-4 py-3 border-b flex justify-between items-center">
                <h3 class="text-[14px] font-semibold text-gray-900">Semua Mitra Terdaftar</h3>
                <button type="button" onclick="document.getElementById('allMitraModal').classList.add('hidden');"
                    class="text-gray-500 hover:text-gray-700">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>
            <div class="p-3 overflow-y-auto">
                <a href="{{ route('admin.mitra.index') }}"
                    class="block px-3 py-2 mb-1 rounded-lg text-xs font-medium bg-green-50 text-green-800">Semua
                    Mitra</a>
                @foreach($allSidebarStores as $store)
                <a href="{{ route('admin.mitra.show', $store->id) }}"
                    onclick="document.getElementById('allMitraModal').classList.add('hidden');"
                    class="block px-3 py-2 mb-1 rounded-lg text-xs hover:bg-gray-50 text-gray-600">{{
                    $store->store_name }}</a>
                @endforeach
            </div>
            <div class="px-4 py-3 border-t flex justify-end">
                <button type="button" onclick="document.getElementById('allMitraModal').classList.add('hidden');"
                    class="px-4 py-1.5 text-xs rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50">Tutup</button>
            </div>
        </div>
    </div>
    @endif

    @stack('scripts')
</body>

</html>