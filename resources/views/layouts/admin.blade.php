<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Nusa Belanja')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head')
</head>
<body class="bg-[#F5F7FB]">
    
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="px-6 py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-[#0BA95B] text-white px-4 py-2 font-semibold rounded-lg">
                    Logo
                </div>
                <span class="font-semibold text-[18px] text-[#111827]">Nusa Belanja</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[14px] text-[#111827] font-medium">
                    {{ auth()->user()->name ?? 'Admin Nusa Belanja' }}
                </span>
                <div class="bg-[#16A34A] rounded-full w-9 h-9 flex items-center justify-center text-white font-semibold text-sm">
                    {{ isset(auth()->user()->name) ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'A' }}
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white min-h-screen border-r">
            <div class="p-3 text-[13px]">
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-2 px-3 py-2 rounded-md mb-1 font-medium
                          {{ request()->routeIs('admin.dashboard') ? 'bg-[#0BA95B] text-white' : 'hover:bg-gray-100 text-[#111827]' }}">
                    <span class="inline-block w-2 h-2 rounded-full 
                                 {{ request()->routeIs('admin.dashboard') ? 'bg-white' : 'border border-gray-400' }}"></span>
                    <span>Dashboard</span>
                </a>

                {{-- Mitra (dropdown) --}}
                <div class="mt-1">
                    @php
                        $isMitraActive = request()->routeIs('admin.mitra.*');
                    @endphp

                    <button type="button"
                            onclick="document.getElementById('mitraDropdown').classList.toggle('hidden');
                                     document.getElementById('mitraArrow').classList.toggle('rotate-180');"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-md mb-1 font-medium
                                   {{ $isMitraActive ? 'bg-[#0BA95B] text-white' : 'hover:bg-gray-100 text-[#111827]' }}">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full 
                                         {{ $isMitraActive ? 'bg-white' : 'border border-gray-400' }}"></span>
                            <span>Mitra</span>
                        </div>
                        <svg id="mitraArrow" class="w-3 h-3 text-current transition-transform {{ $isMitraActive ? 'rotate-180' : '' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.939l3.71-3.71a.75.75 0 111.06 1.061l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div id="mitraDropdown" class="mt-1 ml-4 space-y-1 {{ $isMitraActive ? '' : 'hidden' }}">
                        {{-- Link semua mitra --}}
                        <a href="{{ route('admin.mitra.index') }}"
                           class="block px-3 py-1.5 rounded-md text-[12px] font-medium
                                  {{ request()->routeIs('admin.mitra.index') ? 'bg-[#DCFCE7] text-[#15803D]' : 'hover:bg-gray-50 text-[#4B5563]' }}">
                            Semua Mitra
                        </a>

                        {{-- 5 mitra pertama dari database --}}
                        @foreach($sidebarStores ?? [] as $store)
                            <a href="{{ route('admin.mitra.show', $store->id) }}"
                               class="block px-3 py-1.5 rounded-md text-[12px]
                                      {{ request()->routeIs('admin.mitra.show') && request()->route('id') == $store->id
                                            ? 'bg-[#E5E7EB] text-[#111827] font-medium'
                                            : 'hover:bg-gray-50 text-[#4B5563]' }}">
                                {{ $store->store_name }}
                            </a>
                        @endforeach

                        {{-- Tombol lihat semua jika mitra lebih dari 5 --}}
                        @if(($allSidebarStores ?? collect())->count() > 5)
                        <button type="button"
                                onclick="document.getElementById('allMitraModal').classList.remove('hidden');"
                                class="w-full text-left px-3 py-1.5 rounded-md text-[12px] text-[#2563EB] hover:bg-gray-50 font-medium">
                            Lihat semua mitra…
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Logout --}}
                <form action="{{ route('admin.logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-[#DC2626] hover:bg-red-50 font-medium">
                        <span class="inline-block w-2 h-2 rounded-full border border-[#DC2626]"></span>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    {{-- Modal Semua Mitra --}}
    @if(isset($allSidebarStores))
    <div id="allMitraModal" class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md max-h-[80vh] flex flex-col">
            <div class="px-4 py-3 border-b flex justify-between items-center">
                <h3 class="text-[14px] font-semibold text-[#111827]">Semua Mitra Terdaftar</h3>
                <button type="button"
                        onclick="document.getElementById('allMitraModal').classList.add('hidden');"
                        class="text-[24px] leading-none text-[#6B7280] hover:text-[#111827]">
                    ×
                </button>
            </div>
            <div class="p-3 overflow-y-auto">
                <a href="{{ route('admin.mitra.index') }}"
                   class="block px-3 py-1.5 mb-1 rounded-md text-[12px] font-medium bg-[#DCFCE7] text-[#15803D]">
                    Semua Mitra
                </a>

                @foreach($allSidebarStores as $store)
                    <a href="{{ route('admin.mitra.show', $store->id) }}"
                       onclick="document.getElementById('allMitraModal').classList.add('hidden');"
                       class="block px-3 py-1.5 mb-1 rounded-md text-[12px]
                              {{ request()->routeIs('admin.mitra.show') && request()->route('id') == $store->id
                                    ? 'bg-[#E5E7EB] text-[#111827] font-medium'
                                    : 'hover:bg-gray-50 text-[#4B5563]' }}">
                        {{ $store->store_name }}
                    </a>
                @endforeach
            </div>
            <div class="px-4 py-3 border-t flex justify-end">
                <button type="button"
                        onclick="document.getElementById('allMitraModal').classList.add('hidden');"
                        class="px-4 py-1.5 text-[12px] rounded-md border border-gray-300 text-[#4B5563] hover:bg-gray-50">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif

    @stack('scripts')
</body>
</html>
