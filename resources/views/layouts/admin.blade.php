<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Nusa Belanja')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')

    <style>
        /* Sembunyikan scrollbar sidebar agar lebih rapi */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-[#F8FCF8] font-sans text-gray-800" x-data="{ sidebarOpen: false }">
    <div class="flex flex-col h-screen overflow-hidden">

        <nav
            class="bg-white border-b border-gray-200 h-16 flex items-center px-4 sm:px-6 sticky top-0 z-30 flex-shrink-0 shadow-sm">
            <div class="flex justify-between items-center w-full">

                <div class="flex items-center gap-3 w-auto lg:w-56">
                    <button @click="sidebarOpen = true"
                        class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <div class="flex items-center gap-2 sm:gap-3">
                        <div
                            class="bg-green-800 text-white px-2 py-1.5 sm:px-3 sm:py-2 font-bold text-xs sm:text-sm rounded">
                            Logo</div>
                        <span class="text-sm sm:text-base font-bold text-gray-900 truncate">Nusa Belanja</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <span class="hidden sm:block text-sm font-medium text-gray-700">
                        {{ auth()->user()->name ?? 'Admin Nusa Belanja' }}
                    </span>
                    <div
                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-green-800 text-white flex items-center justify-center font-semibold text-sm">
                        {{ isset(auth()->user()->name) ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'A' }}
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex flex-1 overflow-hidden relative">

            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
                class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden backdrop-blur-sm" style="display: none;">
            </div>

            <aside
                class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 overflow-y-auto transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:w-56 lg:inset-auto flex-shrink-0 h-full no-scrollbar flex flex-col"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

                <div
                    class="flex items-center justify-between px-4 h-16 border-b border-gray-200 lg:hidden flex-shrink-0">
                    <span class="font-bold text-lg text-green-800">Menu Admin</span>
                    <button @click="sidebarOpen = false"
                        class="p-2 text-gray-500 hover:text-red-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-3 space-y-1 flex-1 overflow-y-auto">

                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition
                               {{ request()->routeIs('admin.dashboard') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                        </svg>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>

                    {{-- Mitra (Dropdown) --}}
                    <div x-data="{ open: {{ request()->routeIs('admin.mitra.*') ? 'true' : 'false' }} }">
                        <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition
                                   {{ request()->routeIs('admin.mitra.*') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm font-medium">Mitra</span>
                            </div>

                            <div class="transition-transform duration-200" :class="open ? 'rotate-180' : ''">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        <div x-show="open" x-collapse class="mt-1 ml-4 space-y-1">
                            {{-- Link semua mitra --}}
                            <a href="{{ route('admin.mitra.index') }}"
                                class="block px-3 py-2 rounded-lg text-xs font-medium border-l-2
                                       {{ request()->routeIs('admin.mitra.index') ? 'border-green-800 bg-green-50 text-green-800' : 'border-transparent text-gray-600 hover:bg-gray-50' }}">
                                Semua Mitra
                            </a>

                            {{-- 5 mitra pertama --}}
                            @foreach($sidebarStores ?? [] as $store)
                            <a href="{{ route('admin.mitra.show', $store->id) }}" class="block px-3 py-2 rounded-lg text-xs border-l-2 truncate
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

                    {{-- ✅ PENCAIRAN DANA --}}
                    @php
                    $pendingWithdrawalsCount = \App\Models\Withdrawal::where('status', 'pending')->count();
                    @endphp
                    <a href="{{ route('admin.withdrawals.index') }}"
                        class="flex items-center justify-between px-3 py-2.5 rounded-lg transition
                               {{ request()->routeIs('admin.withdrawals.*') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium">Pencairan Dana</span>
                        </div>
                        @if($pendingWithdrawalsCount > 0)
                        <span
                            class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                     {{ request()->routeIs('admin.withdrawals.*') ? 'bg-white text-green-800' : 'bg-yellow-500 text-white' }}">
                            {{ $pendingWithdrawalsCount }}
                        </span>
                        @endif
                    </a>

                    {{-- Refund Management --}}
                    @php
                    $pendingRefundsCount = \App\Models\Refund::where('status', 'pending')->count();
                    @endphp
                    <a href="{{ route('admin.refunds.index') }}"
                        class="flex items-center justify-between px-3 py-2.5 rounded-lg transition
                               {{ request()->routeIs('admin.refunds.*') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium">Refund</span>
                        </div>
                        @if($pendingRefundsCount > 0)
                        <span
                            class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                     {{ request()->routeIs('admin.refunds.*') ? 'bg-white text-green-800' : 'bg-red-600 text-white' }}">
                            {{ $pendingRefundsCount }}
                        </span>
                        @endif
                    </a>

                    {{-- Logout --}}
                    <form action="{{ route('admin.logout') }}" method="POST" class="pt-4 mt-4 border-t border-gray-100">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-600 hover:bg-red-50 w-full text-left transition">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium">Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <main class="flex-1 overflow-y-auto w-full">
                <div class="p-4 sm:p-6 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Modal Semua Mitra --}}
    @if(isset($allSidebarStores))
    <div id="allMitraModal"
        class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md max-h-[80vh] flex flex-col mx-4">
            <div class="px-4 py-3 border-b flex justify-between items-center">
                <h3 class="text-[14px] font-semibold text-gray-900">Semua Mitra Terdaftar</h3>
                <button type="button" onclick="document.getElementById('allMitraModal').classList.add('hidden');"
                    class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="p-3 overflow-y-auto">
                <a href="{{ route('admin.mitra.index') }}"
                    class="block px-3 py-2 mb-1 rounded-lg text-xs font-medium bg-green-50 text-green-800">
                    Semua Mitra
                </a>
                @foreach($allSidebarStores as $store)
                <a href="{{ route('admin.mitra.show', $store->id) }}"
                    onclick="document.getElementById('allMitraModal').classList.add('hidden');"
                    class="block px-3 py-2 mb-1 rounded-lg text-xs hover:bg-gray-50 text-gray-600">
                    {{ $store->store_name }}
                </a>
                @endforeach
            </div>
            <div class="px-4 py-3 border-t flex justify-end">
                <button type="button" onclick="document.getElementById('allMitraModal').classList.add('hidden');"
                    class="px-4 py-1.5 text-xs rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif

    @stack('scripts')
</body>

</html>