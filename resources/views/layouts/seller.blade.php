<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - NusaBelanja</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

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

<body class="bg-[#F8FCF8] text-gray-800 font-sans">
    <div class="flex flex-col h-screen overflow-hidden">

        <header
            class="bg-white border-b border-gray-200 h-16 flex items-center px-4 sm:px-6 sticky top-0 z-30 flex-shrink-0">
            <div class="flex items-center justify-between w-full">

                <div class="flex items-center gap-3 w-auto lg:w-56 transition-all duration-300">
                    <button id="sidebarToggle"
                        class="lg:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>


                    <div class="flex items-center justify-center">
                        <img src="{{ asset('img/logo/3.png') }}" alt="Icon Nusa Belanja"
                            class="h-10 w-auto object-contain">
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <span class="hidden sm:block text-sm font-medium text-gray-700 truncate max-w-[150px]">
                        {{ auth()->user()->store->store_name }}
                    </span>

                    <div class="relative">
                        <button class="flex items-center gap-2 focus:outline-none" id="userMenuButton">
                            <div
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-green-800 text-white flex items-center justify-center font-semibold text-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>

                        <div id="userDropdown"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('seller.profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Profile Toko
                            </a>
                            <form action="{{ route('seller.logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden relative">

            <div id="sidebarOverlay"
                class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 hidden lg:hidden transition-opacity backdrop-blur-sm">
            </div>

            <aside id="sidebar"
                class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 overflow-y-auto transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:w-56 lg:inset-auto flex-shrink-0 h-full no-scrollbar">

                <div class="flex items-center justify-between px-4 h-16 border-b border-gray-200 lg:hidden">
                    <span class="font-bold text-lg text-green-800">Menu</span>
                    <button id="sidebarClose" class="p-2 text-gray-500 hover:text-red-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <nav class="p-3 space-y-1">
                    <a href="{{ route('seller.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('seller.dashboard') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('seller.products.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('seller.products.*') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-sm font-medium">Produk</span>
                    </a>

                    <a href="{{ route('seller.customers.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('seller.customers.*') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">Pelanggan</span>
                    </a>

                    <a href="{{ route('seller.orders.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('seller.orders.*') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">Pesanan</span>
                    </a>

                    <a href="{{ route('seller.sales.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('seller.sales.*') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">Penjualan</span>
                    </a>

                    <a href="{{ route('seller.payments.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('seller.payments.*') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">Pembayaran</span>
                    </a>

                    <a href="{{ route('seller.withdrawals.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('seller.withdrawals.*') ? 'bg-green-800 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">Pencairan Dana</span>
                    </a>

                    <form action="{{ route('seller.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-600 hover:bg-red-50 w-full text-left transition">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            <span class="text-sm font-medium">Keluar</span>
                        </button>
                    </form>
                </nav>
            </aside>

            <main class="flex-1 overflow-y-auto w-full">
                <div class="p-4 sm:p-6 lg:p-8">

                    <div class="mb-6">
                        <h1 class="text-xl sm:text-2xl font-bold text-green-800 mb-1">@yield('page-title', 'Dashboard')
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-600">@yield('page-subtitle', '')</p>
                    </div>

                    @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 rounded-r-lg p-4 mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-r-lg p-4 mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="w-full">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User Dropdown Logic
            const userMenuButton = document.getElementById('userMenuButton');
            const userDropdown = document.getElementById('userDropdown');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });
                
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
                
                userDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // --- MOBILE SIDEBAR LOGIC (FIXED) ---
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                if(sidebar && sidebarOverlay) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                }
            }

            function closeSidebar() {
                if(sidebar && sidebarOverlay) {
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    openSidebar();
                });
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeSidebar();
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Close when resizing to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) { // lg breakpoint
                    if (sidebarOverlay) sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        });
    </script>

    @stack('scripts')

</body>

</html>