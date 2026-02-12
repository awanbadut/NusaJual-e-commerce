@extends('layouts.seller')

@section('title', 'Manajemen Pelanggan')
@section('page-title', 'Manajemen Pelanggan')
@section('page-subtitle', 'Pantau data pelanggan dan riwayat pembelian mereka')

@section('content')
<div class="space-y-6 px-2 sm:px-0">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white px-5 py-5 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md">
            <p class="text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1 uppercase font-bold tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                Total Pelanggan
            </p>
            <div class="flex items-center justify-between">
                <p class="text-[24px] font-bold text-[#111827] leading-tight">{{ $customers->total() }}</p>
                <div class="bg-blue-50 p-2 rounded-xl text-blue-600">
                    <x-heroicon-o-users class="w-6 h-6" />
                </div>
            </div>
        </div>

        <div class="bg-white px-5 py-5 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md">
            <p class="text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1 uppercase font-bold tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Total Transaksi
            </p>
            <div class="flex items-center justify-between">
                <p class="text-[24px] font-bold text-[#111827] leading-tight">{{ $customers->sum('total_orders') }}</p>
                <div class="bg-green-50 p-2 rounded-xl text-green-600">
                    <x-heroicon-o-shopping-bag class="w-6 h-6" />
                </div>
            </div>
        </div>

        <div class="bg-white px-5 py-5 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md">
            <p class="text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1 uppercase font-bold tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                Total Pendapatan
            </p>
            <div class="flex items-center justify-between">
                <p class="text-[24px] font-bold text-[#111827] leading-tight">Rp {{
                    number_format($customers->sum('total_spent'), 0, ',', '.') }}</p>
                <div class="bg-yellow-50 p-2 rounded-xl text-yellow-600">
                    <x-heroicon-o-currency-dollar class="w-6 h-6" />
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <form method="GET" action="{{ route('seller.customers.index') }}"
            class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="w-full lg:flex-1 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="text-gray-400 w-5 h-5" />
                </div>
                <input type="text" name="search" placeholder="Cari nama, email, atau nomor HP..."
                    value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
            </div>

            <div class="w-full lg:w-64 relative">
                <select name="sort" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none bg-white cursor-pointer">
                    <option value="">Urutkan Berdasarkan</option>
                    <option value="total_spent_desc" {{ request('sort')=='total_spent_desc' ? 'selected' : '' }}>Belanja
                        (Tertinggi)</option>
                    <option value="total_spent_asc" {{ request('sort')=='total_spent_asc' ? 'selected' : '' }}>Belanja
                        (Terendah)</option>
                    <option value="total_orders_desc" {{ request('sort')=='total_orders_desc' ? 'selected' : '' }}>
                        Pesanan (Terbanyak)</option>
                    <option value="name_asc" {{ request('sort')=='name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <x-heroicon-m-chevron-down class="w-5 h-5 text-gray-400" />
                </div>
            </div>

            <div class="flex w-full lg:w-auto gap-2">
                <button type="submit"
                    class="flex-1 lg:flex-none bg-green-800 text-white px-8 py-2.5 rounded-lg hover:bg-green-900 transition font-semibold shadow-sm">
                    Cari
                </button>
                @if(request('search') || request('sort'))
                <a href="{{ route('seller.customers.index') }}"
                    class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr>
                        <th class="px-5 py-4 font-semibold text-[#15803D] text-[13px]">Nama Pelanggan</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D] text-[13px]">Email</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D] text-[13px]">No HP</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D] text-[13px]">Total Pemesanan</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D] text-[13px]">Total Belanja</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D] text-center text-[13px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-[#F9FDF7] transition">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-semibold text-xs shrink-0"
                                    style="background-color: {{ '#' . substr(md5($customer->name), 0, 6) }}">
                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $customer->name }}</p>
                                    <p class="text-[11px] text-gray-500">ID Pelanggan: #{{ str_pad($customer->id, 4,
                                        '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-[13px] text-gray-700">
                            {{ $customer->email }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-[13px] text-gray-700 font-medium">
                            {{ $customer->phone ?? '-' }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-yellow-100 text-yellow-800">
                                {{ $customer->total_orders }} Pesanan
                            </span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-[#0F4C20]">Rp {{ number_format($customer->total_spent ?? 0,
                                0, ',', '.') }}</p>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('seller.customers.show', $customer->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#15803D] hover:bg-[#166534] transition text-white"
                                title="Lihat Detail">
                                <x-heroicon-s-eye class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400 italic">
                                <x-heroicon-o-users class="w-12 h-12 mb-3 opacity-20" />
                                <p class="text-sm">Belum ada data pelanggan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div> @if($customers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/30">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[13px] text-gray-600">
                    Menampilkan <span class="font-medium text-gray-900">{{ $customers->firstItem() }}</span> sampai
                    <span class="font-medium text-gray-900">{{ $customers->lastItem() }}</span> dari
                    <span class="font-medium text-gray-900">{{ $customers->total() }}</span> pelanggan
                </p>

                <div class="flex gap-2">
                    @if($customers->onFirstPage())
                    <button disabled
                        class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed bg-white">‹</button>
                    @else
                    <a href="{{ $customers->previousPageUrl() }}"
                        class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 bg-white transition shadow-sm">‹</a>
                    @endif

                    @if($customers->hasMorePages())
                    <a href="{{ $customers->nextPageUrl() }}"
                        class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 bg-white transition shadow-sm">›</a>
                    @else
                    <button disabled
                        class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed bg-white">›</button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection