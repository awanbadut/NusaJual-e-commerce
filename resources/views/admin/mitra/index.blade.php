@extends('layouts.admin')

@section('title', 'Daftar Mitra - Admin Nusa Belanja')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl sm:text-[28px] font-bold text-[#15803D] mb-1">Daftar Mitra</h1>
    <p class="text-xs sm:text-[13px] text-[#78716C]">Pantau performa semua mitra kamu di sini</p>
</div>

<div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm mb-6">
    <form method="GET" action="{{ route('admin.mitra.index') }}" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-[#9CA3AF]" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Mitra yang terdaftar"
                class="w-full pl-10 pr-4 py-2.5 border border-[#D1D5DB] rounded-lg focus:ring-2 focus:ring-[#15803D] focus:border-transparent text-sm sm:text-[13px] text-[#111827]">
        </div>

        <div class="relative w-full sm:w-auto sm:min-w-[200px]">
            <select name="sort" onchange="this.form.submit()"
                class="w-full appearance-none px-4 py-2.5 border border-[#D1D5DB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#15803D] text-sm sm:text-[13px] text-[#15803D] font-medium bg-white cursor-pointer pr-10">
                <option value="">Urutkan Berdasarkan</option>
                <option value="sales_desc" {{ request('sort')=='sales_desc' ? 'selected' : '' }}>Penjualan Tertinggi
                </option>
                <option value="orders_desc" {{ request('sort')=='orders_desc' ? 'selected' : '' }}>Pesanan Terbanyak
                </option>
                <option value="newest" {{ request('sort')=='newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ request('sort')=='oldest' ? 'selected' : '' }}>Terlama</option>
            </select>

            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-[#15803D]">
                <x-heroicon-m-chevron-down class="w-5 h-5" />
            </div>
        </div>
    </form>
</div>

@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-xs sm:text-[13px]">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left text-[13px] min-w-[800px]">
            <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                <tr>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Nama Mitra</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Tanggal Masuk</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Alamat</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Banyak Penjualan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Total Penjualan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] text-center whitespace-nowrap">Aksi</th>
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
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 sm:w-11 sm:h-11 {{ $colorClass }} rounded-full flex items-center justify-center font-bold text-sm sm:text-[15px] flex-shrink-0">
                                {{ strtoupper(substr($store->store_name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-bold text-[#111827] text-sm">{{ $store->store_name }}</p>
                                <p class="text-xs text-[#9CA3AF] font-mono mt-0.5">ID: MT-{{ str_pad($store->id, 5, '0',
                                    STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                        {{ $store->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-5 py-4 text-gray-600 max-w-[200px] truncate" title="{{ $store->address }}">
                        {{ $store->address }}
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200 text-xs font-bold shadow-sm">
                            {{ number_format($store->orders_count) }} Transaksi
                        </span>
                    </td>
                    <td class="px-5 py-4 font-bold text-[#0F4C20] whitespace-nowrap">
                        Rp {{ number_format($store->total_sales ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 text-center whitespace-nowrap">
                        <a href="{{ route('admin.mitra.show', $store->id) }}"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white transition-all shadow-sm">
                            <x-heroicon-s-eye class="w-4 h-4" />
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <x-heroicon-o-users class="w-12 h-12 mb-3 opacity-20" />
                            <p class="text-sm italic">
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

<div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-gray-500">
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
        <span class="px-3 py-1.5 rounded-md border border-gray-200 text-gray-300 cursor-not-allowed bg-white">‹</span>
        @else
        <a href="{{ $stores->previousPageUrl() }}"
            class="px-3 py-1.5 rounded-md border border-gray-300 hover:bg-gray-50 text-gray-700 bg-white transition">‹</a>
        @endif

        {{-- Simple Page Numbers (Hide on mobile if too many) --}}
        <div class="hidden sm:flex gap-1">
            @foreach ($stores->getUrlRange(1, $stores->lastPage()) as $page => $url)
            <a href="{{ $url }}"
                class="px-3 py-1.5 rounded-md border {{ $page == $stores->currentPage() ? 'bg-green-700 border-green-700 text-white' : 'border-gray-300 hover:bg-gray-50 text-gray-700 bg-white' }} transition">
                {{ $page }}
            </a>
            @endforeach
        </div>

        {{-- Mobile Current Page Indicator --}}
        <span class="sm:hidden px-3 py-1.5 font-bold text-gray-900">
            Halaman {{ $stores->currentPage() }}
        </span>

        {{-- Next --}}
        @if ($stores->hasMorePages())
        <a href="{{ $stores->nextPageUrl() }}"
            class="px-3 py-1.5 rounded-md border border-gray-300 hover:bg-gray-50 text-gray-700 bg-white transition">›</a>
        @else
        <span class="px-3 py-1.5 rounded-md border border-gray-200 text-gray-300 cursor-not-allowed bg-white">›</span>
        @endif
    </div>
    @endif
</div>

@endsection