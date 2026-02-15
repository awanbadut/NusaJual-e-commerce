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

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-5 border border-gray-100 transition hover:shadow-md">
            <div class="flex items-center justify-between mb-1 sm:mb-2">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 sm:p-2 bg-orange-50 rounded-lg text-orange-600">
                        <x-heroicon-o-clock class="w-4 h-4 sm:w-5 sm:h-5" />
                    </div>
                    <span class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">Pending</span>
                </div>
            </div>
            <p class="text-xl sm:text-2xl font-black text-gray-900 mt-1 sm:mt-2">{{ $stats['pending'] }}</p>
            <p class="text-[10px] sm:text-xs text-gray-500 mt-1 truncate">Pesanan menunggu</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-5 border border-gray-100 transition hover:shadow-md">
            <div class="flex items-center justify-between mb-1 sm:mb-2">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 sm:p-2 bg-yellow-50 rounded-lg text-yellow-600">
                        <x-heroicon-o-arrow-path class="w-4 h-4 sm:w-5 sm:h-5" />
                    </div>
                    <span
                        class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">Processing</span>
                </div>
            </div>
            <p class="text-xl sm:text-2xl font-black text-gray-900 mt-1 sm:mt-2">{{ $stats['processing'] }}</p>
            <p class="text-[10px] sm:text-xs text-gray-500 mt-1 truncate">Sedang diproses</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-5 border border-gray-100 transition hover:shadow-md">
            <div class="flex items-center justify-between mb-1 sm:mb-2">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 sm:p-2 bg-purple-50 rounded-lg text-purple-600">
                        <x-heroicon-o-truck class="w-4 h-4 sm:w-5 sm:h-5" />
                    </div>
                    <span class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">Shipped</span>
                </div>
            </div>
            <p class="text-xl sm:text-2xl font-black text-gray-900 mt-1 sm:mt-2">{{ $stats['shipped'] }}</p>
            <p class="text-[10px] sm:text-xs text-gray-500 mt-1 truncate">Dalam pengiriman</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-5 border border-gray-100 transition hover:shadow-md">
            <div class="flex items-center justify-between mb-1 sm:mb-2">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 sm:p-2 bg-green-50 rounded-lg text-green-600">
                        <x-heroicon-o-check-circle class="w-4 h-4 sm:w-5 sm:h-5" />
                    </div>
                    <span
                        class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">Completed</span>
                </div>
            </div>
            <p class="text-xl sm:text-2xl font-black text-gray-900 mt-1 sm:mt-2">{{ $stats['completed'] }}</p>
            <p class="text-[10px] sm:text-xs text-gray-500 mt-1 truncate">Selesai</p>
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

            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit"
                    class="flex-1 md:flex-none px-6 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-semibold shadow-sm active:scale-95">
                    Cari
                </button>

                @if(request()->hasAny(['search', 'payment_status', 'status']))
                <a href="{{ route('seller.orders.index') }}"
                    class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm transition flex items-center gap-1">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-[13px] min-w-[1000px]">
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

                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('seller.orders.show', $order->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#15803D] hover:bg-[#166534] transition text-white"
                                title="Lihat Detail">
                                <x-heroicon-s-eye class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 italic">
                            Belum ada pesanan masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        {{ $orders->appends(request()->query())->links() }}
        @endif
    </div>
</div>
@endsection