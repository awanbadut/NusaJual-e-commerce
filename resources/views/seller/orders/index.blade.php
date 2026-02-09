@extends('layouts.seller')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Tindak lanjuti pesanan pelanggan dengan cepat dan tepat')

@section('content')
<div class="max-w-7xl">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-4">
            <!-- Search Box -->
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nomor pesanan, nama atau email pelanggan..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
            </div>

            <!-- Filter Payment Status -->
            <select name="payment_status" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm" onchange="this.form.submit()">
                <option value="">Semua Status Pembayaran</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="confirmed" {{ request('payment_status') == 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Gagal</option>
            </select>

            <!-- Filter Order Status -->
            <select name="status" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm" onchange="this.form.submit()">
                <option value="">Semua Status Pesanan</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="packing" {{ request('status') == 'packing' ? 'selected' : '' }}>Packing</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <!-- Search Button -->
            <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-sm transition">
                Cari
            </button>

            <!-- Reset Button -->
            @if(request()->hasAny(['search', 'payment_status', 'status']))
            <a href="{{ route('seller.orders.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-sm text-center transition">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-green-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Belanja</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Order Number -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-semibold text-gray-900">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                        </td>

                        <!-- Customer -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-xs" 
                                     style="background-color: {{ '#' . substr(md5($order->user->name), 0, 6) }}">
                                    {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($order->user->email, 20) }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Total -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $order->items->count() }} item</p>
                        </td>

                        <!-- Payment Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->payment_status == 'confirmed')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white">Terkonfirmasi</span>
                            @elseif($order->payment_status == 'paid')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-400 text-gray-900">Menunggu Konfirmasi</span>
                            @elseif($order->payment_status == 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-500 text-white">Pending</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white">Gagal</span>
                            @endif
                        </td>

                        <!-- Order Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status == 'completed')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white">Selesai</span>
                            @elseif($order->status == 'shipped')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-600 text-white">Dikirim</span>
                            @elseif($order->status == 'packing')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-500 text-white">Dikemas</span>
                            @elseif($order->status == 'processing')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500 text-gray-900">Diproses</span>
                            @elseif($order->status == 'confirmed')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-teal-500 text-white">Confirmed</span>
                            @elseif($order->status == 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-500 text-white">Pending</span>
                            @elseif($order->status == 'cancelled')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white">Dibatalkan</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-500 text-white">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $order->created_at->diffForHumans() }}
                        </td>

                        <!-- Action -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('seller.orders.show', $order->id) }}" 
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-green-600 hover:bg-green-700 transition"
                               title="Lihat Detail">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="mt-4 text-sm font-medium text-gray-900">Belum ada pesanan</p>
                            <p class="mt-1 text-sm text-gray-500">Pesanan dari pelanggan akan muncul di sini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold text-gray-900">{{ $orders->firstItem() }}</span> sampai 
                    <span class="font-semibold text-gray-900">{{ $orders->lastItem() }}</span> dari 
                    <span class="font-semibold text-gray-900">{{ $orders->total() }}</span> pesanan
                </p>
                
                <div class="flex items-center gap-2">
                    @if($orders->onFirstPage())
                        <button disabled class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed bg-gray-100">
                            ← Previous
                        </button>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">
                            ← Previous
                        </a>
                    @endif

                    <!-- Page Numbers -->
                    <div class="hidden sm:flex items-center gap-1">
                        @foreach(range(1, min(5, $orders->lastPage())) as $page)
                            @if($page == $orders->currentPage())
                                <span class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold">{{ $page }}</span>
                            @else
                                <a href="{{ $orders->url($page) }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">{{ $page }}</a>
                            @endif
                        @endforeach
                    </div>

                    @if($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">
                            Next →
                        </a>
                    @else
                        <button disabled class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed bg-gray-100">
                            Next →
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
