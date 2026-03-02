@extends('layouts.seller')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Tindak lanjuti pesanan pelanggan dengan cepat dan tepat')

@section('content')
<div class="max-w-7xl px-2 sm:px-0 space-y-4 md:space-y-6">

    @if(session('success'))
    <div class="mb-4 md:mb-6 bg-green-50 border-l-4 border-green-500 p-3 md:p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <x-heroicon-s-check-circle class="w-5 h-5 md:w-6 md:h-6 text-green-500 mr-2 md:mr-3 shrink-0" />
            <p class="text-xs md:text-sm font-semibold text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 md:mb-6 bg-red-50 border-l-4 border-red-500 p-3 md:p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <x-heroicon-s-x-circle class="w-5 h-5 md:w-6 md:h-6 text-red-500 mr-2 md:mr-3 shrink-0" />
            <p class="text-xs md:text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 transition hover:shadow-md">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-orange-50 rounded-lg text-orange-600">
                    <x-heroicon-o-clock class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider">Pending</span>
            </div>
            <p class="text-xl md:text-2xl font-black text-gray-900 leading-none">{{ $stats['pending'] }}</p>
            <p class="text-[9px] md:text-xs text-gray-400 mt-1 truncate">Menunggu bayar</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 transition hover:shadow-md">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-yellow-50 rounded-lg text-yellow-600">
                    <x-heroicon-o-arrow-path class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider">Proses</span>
            </div>
            <p class="text-xl md:text-2xl font-black text-gray-900 leading-none">{{ $stats['processing'] }}</p>
            <p class="text-[9px] md:text-xs text-gray-400 mt-1 truncate">Sedang dikemas</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 transition hover:shadow-md">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-purple-50 rounded-lg text-purple-600">
                    <x-heroicon-o-truck class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider">Shipped</span>
            </div>
            <p class="text-xl md:text-2xl font-black text-gray-900 leading-none">{{ $stats['shipped'] }}</p>
            <p class="text-[9px] md:text-xs text-gray-400 mt-1 truncate">Dalam kurir</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 transition hover:shadow-md">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-green-50 rounded-lg text-green-600">
                    <x-heroicon-o-check-circle class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Selesai</span>
            </div>
            <p class="text-xl md:text-2xl font-black text-gray-900 leading-none">{{ $stats['completed'] }}</p>
            <p class="text-[9px] md:text-xs text-gray-400 mt-1 truncate">Diterima pembeli</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border border-gray-100">
        <form method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 md:gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari order, nama, email..."
                    class="w-full pl-9 md:pl-10 pr-4 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-xs md:text-sm">
            </div>

            <div class="grid grid-cols-2 lg:flex gap-2 md:gap-3">
                <div class="relative">
                    <select name="payment_status"
                        class="w-full pl-3 pr-8 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-[11px] md:text-sm appearance-none bg-white cursor-pointer"
                        onchange="this.form.submit()">
                        <option value="">Pembayaran</option>
                        <option value="pending" {{ request('payment_status')=='pending' ? 'selected' : '' }}>Pending
                        </option>
                        <option value="paid" {{ request('payment_status')=='paid' ? 'selected' : '' }}>Verifikasi
                        </option>
                        <option value="confirmed" {{ request('payment_status')=='confirmed' ? 'selected' : '' }}>
                            Confirmed</option>
                        <option value="rejected" {{ request('payment_status')=='rejected' ? 'selected' : '' }}>Ditolak
                        </option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                        <x-heroicon-m-chevron-down class="w-4 h-4" />
                    </div>
                </div>

                <div class="relative">
                    <select name="status"
                        class="w-full pl-3 pr-8 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-[11px] md:text-sm appearance-none bg-white cursor-pointer"
                        onchange="this.form.submit()">
                        <option value="">Status Order</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status')=='processing' ? 'selected' : '' }}>Proses
                        </option>
                        <option value="shipped" {{ request('status')=='shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Selesai
                        </option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                        <x-heroicon-m-chevron-down class="w-4 h-4" />
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 lg:flex-none px-6 py-2 md:py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-xs md:text-sm font-bold shadow-md active:scale-95">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'payment_status', 'status']))
                <a href="{{ route('seller.orders.index') }}"
                    class="px-4 py-2 md:py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-bold text-xs md:text-sm transition flex items-center justify-center">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left whitespace-nowrap min-w-[900px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[12px] tracking-wider">
                            ID Order</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[12px] tracking-wider">
                            Pelanggan</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[12px] tracking-wider">
                            Total</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[12px] tracking-wider">
                            Status Bayar</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[12px] tracking-wider">
                            Status Order</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[12px] tracking-wider">
                            Tanggal</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[12px] tracking-wider text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-xs md:text-sm">
                    @forelse($orders as $order)
                    <tr class="hover:bg-[#F9FDF7] transition">
                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <p class="font-bold text-gray-900 font-mono tracking-tighter">#{{ $order->order_number }}
                            </p>
                            <p class="text-[10px] md:text-[11px] text-gray-400">{{ $order->created_at->format('H:i') }}
                                WIB</p>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <div class="flex items-center gap-2.5 md:gap-3">
                                <div class="w-8 h-8 md:w-9 md:h-9 rounded-full flex items-center justify-center text-white font-bold text-[10px] md:text-xs shrink-0"
                                    style="background-color: {{ '#' . substr(md5($order->user->name), 0, 6) }}">
                                    {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900 truncate max-w-[120px]">{{ $order->user->name }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 truncate max-w-[120px]">{{ $order->user->email
                                        }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <p class="font-black text-[#0F4C20]">Rp {{ number_format($order->total_amount, 0, ',', '.')
                                }}</p>
                            <p class="text-[10px] text-gray-400">{{ $order->items->count() }} item</p>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4">
                            @if($order->status == 'cancelled')
                            <span
                                class="px-2 py-0.5 rounded-full text-[9px] md:text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">Dibatalkan</span>
                            @elseif(!$order->payment)
                            <span
                                class="px-2 py-0.5 rounded-full text-[9px] md:text-[10px] font-bold bg-orange-50 text-orange-600 border border-orange-100">Pending</span>
                            @else
                            @php
                            $pStatus = $order->payment->status;
                            $pClasses = [
                            'confirmed' => 'bg-green-50 text-green-700 border-green-100',
                            'paid' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                            'rejected' => 'bg-red-50 text-red-700 border-red-100'
                            ];
                            $pLabels = ['confirmed' => 'Terkonfirmasi', 'paid' => 'Verifikasi', 'rejected' =>
                            'Ditolak'];
                            @endphp
                            <span
                                class="px-2 py-0.5 rounded-full text-[9px] md:text-[10px] font-bold border {{ $pClasses[$pStatus] ?? 'bg-gray-50' }}">
                                {{ $pLabels[$pStatus] ?? ucfirst($pStatus) }}
                            </span>
                            @endif
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4">
                            @php
                            $oStatus = $order->status;
                            $oClasses = [
                            'completed' => 'bg-green-100 text-green-800',
                            'shipped' => 'bg-purple-100 text-purple-800',
                            'packing' => 'bg-blue-100 text-blue-800',
                            'processing' => 'bg-yellow-100 text-yellow-800',
                            'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            @endphp
                            <span
                                class="px-2 py-0.5 rounded-full text-[9px] md:text-[10px] font-black border uppercase {{ $oClasses[$oStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $oStatus == 'packing' ? 'Dikemas' : ($oStatus == 'shipped' ? 'Dikirim' : ($oStatus ==
                                'processing' ? 'Proses' : ucfirst($oStatus))) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4 text-gray-500 font-medium">
                            {{ $order->created_at->format('d M Y') }}
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4 text-center">
                            <a href="{{ route('seller.orders.show', $order->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-lg bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm"
                                title="Lihat Detail">
                                <x-heroicon-s-eye class="w-3.5 h-3.5 md:w-4 md:h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">
                            Belum ada pesanan masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="px-4 py-3 bg-white border-t border-gray-100">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection