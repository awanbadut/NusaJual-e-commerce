@extends('layouts.seller')

@section('title', 'Manajemen Pelanggan')
@section('page-title', 'Manajemen Pelanggan')
@section('page-subtitle', 'Pantau data pelanggan dan riwayat pembelian mereka')

@section('content')
<!-- ✅ WRAPPER dengan max-width yang pas -->
<div class="w-full px-4 sm:px-6 lg:px-8">
    <div class="max-w-full mx-auto">
        
        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
            <form method="GET" action="{{ route('seller.customers.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                <!-- Search Box -->
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama, email, atau nomor HP..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                </div>

                <!-- Sort Dropdown -->
                <select 
                    name="sort" 
                    onchange="this.form.submit()"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm bg-white min-w-[200px]">
                    <option value="">Urutkan Berdasarkan</option>
                    <option value="total_spent_desc" {{ request('sort') == 'total_spent_desc' ? 'selected' : '' }}>Total Belanja (Tertinggi)</option>
                    <option value="total_spent_asc" {{ request('sort') == 'total_spent_asc' ? 'selected' : '' }}>Total Belanja (Terendah)</option>
                    <option value="total_orders_desc" {{ request('sort') == 'total_orders_desc' ? 'selected' : '' }}>Total Pesanan (Terbanyak)</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                </select>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button 
                        type="submit"
                        class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium text-sm transition flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari
                    </button>

                    @if(request('search') || request('sort'))
                    <a 
                        href="{{ route('seller.customers.index') }}"
                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition whitespace-nowrap">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Active Filters Display -->
        @if(request('search') || request('sort'))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 flex flex-wrap items-center gap-2">
            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm text-blue-800 font-medium shrink-0">Filter aktif:</span>
            
            @if(request('search'))
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-blue-300 rounded-full text-xs font-semibold text-blue-800">
                Pencarian: "{{ request('search') }}"
                <a href="{{ route('seller.customers.index', request()->except('search')) }}" class="hover:text-red-600">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </span>
            @endif

            @if(request('sort'))
            @php
                $sortLabels = [
                    'total_spent_desc' => 'Belanja Tertinggi',
                    'total_spent_asc' => 'Belanja Terendah',
                    'total_orders_desc' => 'Pesanan Terbanyak',
                    'name_asc' => 'Nama A-Z',
                ];
            @endphp
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-blue-300 rounded-full text-xs font-semibold text-blue-800">
                Urut: {{ $sortLabels[request('sort')] ?? request('sort') }}
                <a href="{{ route('seller.customers.index', request()->except('sort')) }}" class="hover:text-red-600">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </span>
            @endif
        </div>
        @endif

        <!-- Stats Summary (COMPACT) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-dark">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-90 mb-1">Total Pelanggan</p>
                        <p class="text-2xl font-bold">{{ $customers->total() }}</p>
                    </div>
                    <div class="bg-white/20 p-2.5 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-dark">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-90 mb-1">Total Transaksi</p>
                        <p class="text-2xl font-bold">{{ $customers->sum('total_orders') }}</p>
                    </div>
                    <div class="bg-white/20 p-2.5 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-4 text-dark">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-90 mb-1">Total Pendapatan</p>
                        <p class="text-xl font-bold">Rp {{ number_format($customers->sum('total_spent'), 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white/20 p-2.5 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Table (RESPONSIVE) -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-green-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Pelanggan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No HP</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Pemesanan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Belanja</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Avatar + Name -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm shrink-0" 
                                         style="background-color: {{ '#' . substr(md5($customer->name), 0, 6) }}">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $customer->name }}</span>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-700">{{ $customer->email }}</span>
                            </td>

                            <!-- Phone -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-700">{{ $customer->phone ?? '-' }}</span>
                            </td>

                            <!-- Total Orders Badge -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    {{ $customer->total_orders }} Pesanan
                                </span>
                            </td>

                            <!-- Total Spent -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm font-semibold text-green-600">Rp {{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}</span>
                            </td>

                            <!-- Action Button -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <a href="{{ route('seller.customers.show', $customer->id) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-600 hover:bg-green-700 transition">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-700 mb-1">
                                        @if(request('search'))
                                            Tidak ada pelanggan dengan kata kunci "{{ request('search') }}"
                                        @else
                                            Belum ada pelanggan
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">Pelanggan akan muncul setelah melakukan pesanan pertama</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination (COMPACT) -->
            @if($customers->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-gray-600">
                        Menampilkan <span class="font-medium">{{ $customers->firstItem() }}</span> - 
                        <span class="font-medium">{{ $customers->lastItem() }}</span> dari 
                        <span class="font-medium">{{ $customers->total() }}</span> pelanggan
                    </p>
                    
                    <div class="flex gap-2">
                        @if($customers->onFirstPage())
                            <button disabled class="px-3 py-1.5 border border-gray-300 rounded text-xs text-gray-400 cursor-not-allowed bg-white">‹ Prev</button>
                        @else
                            <a href="{{ $customers->previousPageUrl() }}" class="px-3 py-1.5 border border-gray-300 rounded text-xs text-gray-700 hover:bg-gray-100 bg-white transition">‹ Prev</a>
                        @endif

                        @if($customers->hasMorePages())
                            <a href="{{ $customers->nextPageUrl() }}" class="px-3 py-1.5 border border-gray-300 rounded text-xs text-gray-700 hover:bg-gray-100 bg-white transition">Next ›</a>
                        @else
                            <button disabled class="px-3 py-1.5 border border-gray-300 rounded text-xs text-gray-400 cursor-not-allowed bg-white">Next ›</button>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
