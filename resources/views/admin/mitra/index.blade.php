@extends('layouts.admin')

@section('title', 'Daftar Mitra - Admin Nusa Belanja')

@section('content')
<div class="container gap-3 md:gap-5 flex flex-col">
    <div class="mb-2 md:mb-6">
        <h1 class="text-lg md:text-[28px] font-bold text-[#15803D] mb-0.5 md:mb-1 tracking-tight">Daftar Mitra</h1>
        <p class="text-[10px] md:text-[13px] text-[#78716C] font-medium">Pantau performa semua mitra kamu di sini</p>
    </div>

    <div class="bg-white p-3 md:p-5 rounded-xl md:rounded-2xl shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('admin.mitra.index') }}" class="flex flex-col lg:flex-row gap-3 md:gap-4">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-[#9CA3AF]" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Mitra yang terdaftar..."
                    class="w-full pl-9 md:pl-10 pr-3 md:pr-4 py-2.5 border border-[#D1D5DB] rounded-lg focus:ring-1 focus:ring-[#15803D] focus:border-transparent text-xs md:text-[13px] text-[#111827] bg-gray-50 md:bg-white">
            </div>

            <div class="flex gap-2 md:gap-4 w-full lg:w-auto">
                <div class="relative flex-1 lg:w-auto lg:min-w-[200px]">
                    <select name="sort" onchange="this.form.submit()"
                        class="w-full appearance-none px-3 md:px-4 py-2.5 border border-[#D1D5DB] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#15803D] text-[11px] md:text-[13px] text-[#15803D] font-bold md:font-medium bg-white cursor-pointer pr-8 md:pr-10 truncate">
                        <option value="">Urutkan Berdasarkan</option>
                        <option value="sales_desc" {{ request('sort')=='sales_desc' ? 'selected' : '' }}>Penjualan
                            Tertinggi</option>
                        <option value="orders_desc" {{ request('sort')=='orders_desc' ? 'selected' : '' }}>Pesanan
                            Terbanyak</option>
                        <option value="newest" {{ request('sort')=='newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort')=='oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>
                    <div
                        class="absolute inset-y-0 right-0 flex items-center pr-2 md:pr-3 pointer-events-none text-[#15803D]">
                        <x-heroicon-m-chevron-down class="w-4 h-4" />
                    </div>
                </div>

                <a href="{{ route('admin.mitra.exportAll', request()->query()) }}"
                    class="flex-1 lg:flex-none lg:w-auto bg-green-700 text-white px-3 md:px-6 py-2.5 rounded-lg hover:bg-green-800 transition text-[11px] md:text-sm font-bold flex items-center justify-center gap-1.5 md:gap-2 shadow-sm whitespace-nowrap active:scale-95">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 md:w-5 md:h-5" />
                    <span>Export <span class="hidden md:inline">Semua Mitra</span></span>
                </a>
            </div>
        </form>
    </div>

    @if(session('success'))
    <div
        class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 md:p-4 rounded-xl text-[11px] md:text-[13px] flex items-center gap-2 md:gap-3 shadow-sm">
        <x-heroicon-s-check-circle class="w-4 h-4 md:w-5 md:h-5 shrink-0" />
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left text-xs md:text-[13px] min-w-[700px] md:min-w-[800px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Nama Mitra</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Tanggal Masuk</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Alamat</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Penjualan</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Total Omzet</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] text-center whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7] divide-y divide-gray-100">
                    @forelse($stores as $index => $store)
                    @php
                    $colors = [
                    'bg-[#FEF3C7] text-[#92400E]',
                    'bg-[#DBEAFE] text-[#1E40AF]',
                    'bg-[#FCE7F3] text-[#9F1239]',
                    'bg-[#E0E7FF] text-[#3730A3]',
                    'bg-[#FEF3C7] text-[#92400E]',
                    'bg-[#D1FAE5] text-[#065F46]',
                    ];
                    $colorIndex = (($stores->currentPage() - 1) * $stores->perPage() + $loop->index) % count($colors);
                    $colorClass = $colors[$colorIndex];
                    @endphp
                    <tr class="hover:bg-green-50/30 transition duration-200">
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2.5 md:gap-3">
                                <div
                                    class="w-8 h-8 md:w-11 md:h-11 {{ $colorClass }} rounded-full flex items-center justify-center font-black text-[10px] md:text-[15px] flex-shrink-0 shadow-sm border border-white">
                                    {{ strtoupper(substr($store->store_name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-[#111827] text-xs md:text-sm truncate">{{
                                        $store->store_name }}</p>
                                    <p class="text-[9px] md:text-xs text-[#9CA3AF] font-mono mt-0.5 tracking-tight">ID:
                                        MT-{{ str_pad($store->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-gray-600 whitespace-nowrap font-medium">
                            {{ $store->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-gray-600 max-w-[150px] md:max-w-[200px] truncate italic text-[11px] md:text-[13px]"
                            title="{{ $store->address }}">
                            {{ $store->address }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2 md:px-2.5 py-0.5 md:py-1 rounded-full bg-yellow-50 text-yellow-800 border border-yellow-200 text-[10px] md:text-xs font-bold shadow-sm">
                                {{ number_format($store->orders_count) }} Trx
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-black text-[#0F4C20] whitespace-nowrap">
                            Rp{{ number_format($store->total_sales ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            <a href="{{ route('admin.mitra.show', $store->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-lg bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm active:scale-95"
                                title="Lihat Detail">
                                <x-heroicon-s-eye class="w-3.5 h-3.5 md:w-4 md:h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 md:px-5 md:py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <x-heroicon-o-users class="w-10 h-10 md:w-12 md:h-12 mb-2 md:mb-3 opacity-20" />
                                <p class="text-[11px] md:text-sm font-medium">
                                    @if(request('search'))
                                    Tidak ada mitra yang sesuai dengan "{{ request('search') }}"
                                    @else
                                    Belum ada mitra terdaftar
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div
        class="flex flex-col sm:flex-row justify-between items-center gap-3 md:gap-4 text-[10px] md:text-xs text-gray-500 px-1 md:px-0">
        <div>
            @if($stores->total() > 0)
            Menampilkan <span class="font-bold text-gray-900">{{ $stores->firstItem() }}</span> -
            <span class="font-bold text-gray-900">{{ $stores->lastItem() }}</span> dari
            <span class="font-bold text-gray-900">{{ $stores->total() }}</span> data
            @endif
        </div>

        @if($stores->hasPages())
        <div class="flex gap-1">
            {{-- Previous --}}
            @if ($stores->onFirstPage())
            <span
                class="px-2.5 py-1 md:px-3 md:py-1.5 rounded-md border border-gray-200 text-gray-300 cursor-not-allowed bg-white">‹</span>
            @else
            <a href="{{ $stores->previousPageUrl() }}"
                class="px-2.5 py-1 md:px-3 md:py-1.5 rounded-md border border-gray-300 hover:bg-gray-50 text-gray-700 bg-white transition shadow-sm">‹</a>
            @endif

            {{-- Page Numbers (Hidden on mobile) --}}
            <div class="hidden sm:flex gap-1">
                @foreach ($stores->getUrlRange(1, $stores->lastPage()) as $page => $url)
                <a href="{{ $url }}"
                    class="px-3 py-1.5 rounded-md border shadow-sm {{ $page == $stores->currentPage() ? 'bg-green-700 border-green-700 text-white font-bold' : 'border-gray-300 hover:bg-gray-50 text-gray-700 bg-white font-medium' }} transition">
                    {{ $page }}
                </a>
                @endforeach
            </div>

            {{-- Mobile Page Indicator --}}
            <span
                class="sm:hidden px-3 py-1 font-bold text-gray-900 flex items-center bg-white border border-gray-200 rounded-md shadow-sm">
                Hal {{ $stores->currentPage() }}
            </span>

            {{-- Next --}}
            @if ($stores->hasMorePages())
            <a href="{{ $stores->nextPageUrl() }}"
                class="px-2.5 py-1 md:px-3 md:py-1.5 rounded-md border border-gray-300 hover:bg-gray-50 text-gray-700 bg-white transition shadow-sm">›</a>
            @else
            <span
                class="px-2.5 py-1 md:px-3 md:py-1.5 rounded-md border border-gray-200 text-gray-300 cursor-not-allowed bg-white">›</span>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection