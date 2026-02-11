@extends('layouts.seller')

@section('title', 'Manajemen Pelanggan')
@section('page-title', 'Manajemen Pelanggan')
@section('page-subtitle', 'Pantau data pelanggan dan riwayat pembelian mereka')

@section('content')
<div class="max-w-7xl">
    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex items-center gap-4">
            <!-- Search Box -->
            <form method="GET" class="flex-1">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <x-heroicon-o-magnifying-glass class="text-gray-400 w-5 h-5" />
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Pelanggan yang terdaftar"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                </div>
            </form>

            <!-- Sort Dropdown -->
            <select
                class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                <option>Urutkan Berdasarkan</option>
                <option>Total Belanja (Tertinggi)</option>
                <option>Total Belanja (Terendah)</option>
                <option>Total Pesanan (Terbanyak)</option>
                <option>Nama (A-Z)</option>
            </select>

        </div>
    </div>

    <!-- Customer Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-[13px]">
            <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                <tr class="text-left">
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Nama Pelanggan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Email</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">No Hp</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Total Pemesanan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Total Belanja</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <!-- Avatar + Name -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm"
                                style="background-color: {{ '#' . substr(md5($customer->name), 0, 6) }}">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $customer->name }}</span>
                        </div>
                    </td>

                    <!-- Email -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-700">{{ $customer->email }}</span>
                    </td>

                    <!-- Phone -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-700">{{ $customer->phone ?? '-' }}</span>
                    </td>

                    <!-- Total Orders Badge -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-400 text-gray-900">
                            {{ $customer->total_orders }} Pesanan
                        </span>
                    </td>

                    <!-- Total Spent -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($customer->total_spent ??
                            0, 0, ',', '.') }}</span>
                    </td>

                    <!-- Action Button -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('seller.customers.show', $customer->id) }}"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#15803D] hover:bg-[#166534] transition text-white"
                            title="Lihat Detail">
                            <x-heroicon-s-eye class="w-4 h-4" />
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                        Belum ada pelanggan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($customers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium">{{ $customers->firstItem() }}</span> sampai
                    <span class="font-medium">{{ $customers->lastItem() }}</span> dari
                    <span class="font-medium">{{ $customers->total() }}</span> data
                </p>

                <div class="flex gap-2">
                    @if($customers->onFirstPage())
                    <button disabled
                        class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">‹</button>
                    @else
                    <a href="{{ $customers->previousPageUrl() }}"
                        class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">‹</a>
                    @endif

                    @if($customers->hasMorePages())
                    <a href="{{ $customers->nextPageUrl() }}"
                        class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">›</a>
                    @else
                    <button disabled
                        class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">›</button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection