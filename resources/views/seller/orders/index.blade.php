@extends('layouts.seller')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Tindak lanjuti pesanan pelanggan dengan cepat dan tepat')

@section('content')
<div class="max-w-7xl">
    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex items-center gap-4">
            <!-- Search Box -->
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari Pesanan yang terdaftar"
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
            </div>

            <!-- Filter Dropdowns -->
            <select name="payment_status" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm" onchange="this.form.submit()">
                <option value="">Status Pemabayaran</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pembayaran Lunas</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Pembayaran Tertunda</option>
            </select>

            <select name="status" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm" onchange="this.form.submit()">
                <option value="">Status Pesanan</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-green-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Belanja</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status Pembayaran</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <!-- Order Number -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $order->order_number }}
                    </td>

                    <!-- Customer -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->user->email }}</p>
                        </div>
                    </td>

                    <!-- Total -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>

                    <!-- Payment Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->payment_status == 'paid')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-400 text-white">Pembayaran Lunas</span>
                        @elseif($order->payment_status == 'pending')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-gray-900">Menunggu Konfirmasi</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-700 text-white">Pembayaran Tertunda</span>
                        @endif
                    </td>

                    <!-- Order Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->status == 'completed')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white">Selesai</span>
                        @elseif($order->status == 'processing')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-700 text-white">Menunggu Pembayaran</span>
                        @elseif($order->status == 'pending')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500 text-gray-900">Diproses</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white">Dibatalkan</span>
                        @endif
                    </td>

                    <!-- Action -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('seller.orders.show', $order->id) }}" 
                           class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-600 hover:bg-yellow-700 transition">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                        Belum ada pesanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium">{{ $orders->firstItem() }}</span> sampai 
                    <span class="font-medium">{{ $orders->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $orders->total() }}</span> data
                </p>
                
                <div class="flex gap-2">
                    @if($orders->onFirstPage())
                        <button disabled class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">‹</button>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">‹</a>
                    @endif

                    @if($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">›</a>
                    @else
                        <button disabled class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">›</button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
