@extends('layouts.seller')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Tindak lanjuti pesanan pelanggan dengan cepat dan tepat')

@section('content')
<div class="max-w-7xl">
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd"></path>
            </svg>
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Pending</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Processing</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['processing'] }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z" />
                        <path d="M16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Shipped</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['shipped'] }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                        <path
                            d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Completed</p>
                    <p class="text-2xl font-bold text-[#15803D]">{{ $stats['completed'] }}</p>
                </div>
                <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#15803D]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="text-gray-400 w-5 h-5" />
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor pesanan, nama atau email..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
            </div>

            <div class="relative">
                <select name="payment_status"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm appearance-none pr-10 cursor-pointer"
                    onchange="this.form.submit()">
                    <option value="">Semua Status Pembayaran</option>
                    <option value="pending" {{ request('payment_status')=='pending' ? 'selected' : '' }}>Menunggu
                        Pembayaran</option>
                    <option value="paid" {{ request('payment_status')=='paid' ? 'selected' : '' }}>Menunggu Konfirmasi
                    </option>
                    <option value="confirmed" {{ request('payment_status')=='confirmed' ? 'selected' : '' }}>
                        Terkonfirmasi</option>
                    <option value="rejected" {{ request('payment_status')=='rejected' ? 'selected' : '' }}>Ditolak
                    </option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <x-heroicon-m-chevron-down class="w-5 h-5 text-gray-400" />
                </div>
            </div>

            <div class="relative">
                <select name="status"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm appearance-none pr-10 cursor-pointer"
                    onchange="this.form.submit()">
                    <option value="">Semua Status Pesanan</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status')=='confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ request('status')=='processing' ? 'selected' : '' }}>Processing
                    </option>
                    <option value="packing" {{ request('status')=='packing' ? 'selected' : '' }}>Packing</option>
                    <option value="shipped" {{ request('status')=='shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <x-heroicon-m-chevron-down class="w-5 h-5 text-gray-400" />
                </div>
            </div>

            <button type="submit"
                class="px-6 py-2.5 bg-[#15803D] text-white rounded-lg hover:bg-[#166534] font-semibold text-sm transition">
                Cari
            </button>

            @if(request()->hasAny(['search', 'payment_status', 'status']))
            <a href="{{ route('seller.orders.index') }}"
                class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-sm text-center transition">
                Reset
            </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-[13px]">
            <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                <tr class="text-left">
                    <th class="px-5 py-4 font-semibold text-[#15803D]">ID Pesanan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Pelanggan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Total Belanja</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Status Pembayaran</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Status Pesanan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Tanggal</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-[#F9FDF7] transition">
                    <td class="px-5 py-4 whitespace-nowrap">
                        <p class="text-sm font-bold text-gray-900 font-mono">{{ $order->order_number }}</p>
                        <p class="text-[11px] text-gray-500">{{ $order->created_at->format('H:i WIB') }}</p>
                    </td>

                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-semibold text-xs shrink-0"
                                style="background-color: {{ '#' . substr(md5($order->user->name), 0, 6) }}">
                                {{ strtoupper(substr($order->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                                <p class="text-[11px] text-gray-500">{{ Str::limit($order->user->email, 20) }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-5 py-4 whitespace-nowrap">
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',',
                            '.') }}</p>
                        <p class="text-[11px] text-gray-500">{{ $order->items->count() }} item</p>
                    </td>

                    <td class="px-5 py-4 whitespace-nowrap">
                        @if($order->status == 'cancelled')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-800">Dibatalkan</span>
                        @elseif(!$order->payment)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-orange-100 text-orange-800">Pending</span>
                        @elseif($order->payment->status == 'confirmed')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-green-100 text-green-800">Terkonfirmasi</span>
                        @elseif($order->payment->status == 'paid')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-yellow-100 text-yellow-800">Menunggu
                            Konfirmasi</span>
                        @elseif($order->payment->status == 'rejected')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-red-100 text-red-800">Ditolak</span>
                        @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-orange-100 text-orange-800">Pending</span>
                        @endif
                    </td>

                    <td class="px-5 py-4 whitespace-nowrap">
                        @if($order->status == 'completed')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-[#DCFCE7] text-[#15803D]">Selesai</span>
                        @elseif($order->status == 'shipped')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-purple-100 text-purple-800">Dikirim</span>
                        @elseif($order->status == 'packing')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-100 text-blue-800">Dikemas</span>
                        @elseif($order->status == 'processing')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-yellow-100 text-yellow-800">Diproses</span>
                        @elseif($order->status == 'confirmed')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-teal-100 text-teal-800">Confirmed</span>
                        @elseif($order->status == 'pending')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-orange-100 text-orange-800">Pending</span>
                        @elseif($order->status == 'cancelled')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-red-100 text-red-800">Dibatalkan</span>
                        @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-800">{{
                            ucfirst($order->status) }}</span>
                        @endif
                    </td>

                    <td class="px-5 py-4 whitespace-nowrap text-[13px] text-gray-600">
                        {{ $order->created_at->format('d M Y') }}
                    </td>

                    <td class="px-5 py-4">
                        <a href="{{ route('seller.orders.show', $order->id) }}"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#15803D] hover:bg-[#166534] transition text-white"
                            title="Lihat Detail">
                            <x-heroicon-s-eye class="w-4 h-4" />
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <x-heroicon-o-shopping-bag class="w-12 h-12 text-gray-300 mb-3" />
                            <p class="text-sm font-medium text-gray-900">Belum ada pesanan</p>
                            <p class="text-xs text-gray-500 mt-1">Pesanan dari pelanggan akan muncul di sini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[13px] text-gray-600">
                    Menampilkan <span class="font-medium text-gray-900">{{ $orders->firstItem() }}</span> sampai
                    <span class="font-medium text-gray-900">{{ $orders->lastItem() }}</span> dari
                    <span class="font-medium text-gray-900">{{ $orders->total() }}</span> pesanan
                </p>

                <div class="flex gap-2">
                    @if($orders->onFirstPage())
                    <button disabled
                        class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">‹</button>
                    @else
                    <a href="{{ $orders->previousPageUrl() }}"
                        class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">‹</a>
                    @endif

                    @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}"
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