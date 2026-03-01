@extends('layouts.seller')

@section('title', 'Detail Penjualan')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="max-w-7xl px-2 sm:px-0">
    <div class="mb-4 md:mb-6 flex items-center gap-3 md:gap-4">
        <a href="{{ route('seller.sales.index') }}"
            class="p-1.5 md:p-2 hover:bg-gray-100 rounded-lg transition bg-white border border-gray-100 shadow-sm shrink-0">
            <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="min-w-0">
            <nav class="flex text-[10px] md:text-sm text-gray-500 mb-0.5">
                <a href="{{ route('seller.sales.index') }}" class="hover:text-green-800">Riwayat Penjualan</a>
                <span class="mx-1.5">›</span>
                <span class="text-gray-900 font-medium truncate">Detail</span>
            </nav>
            <h1 class="text-lg md:text-3xl font-bold text-[#0F4C20] truncate">
                Pesanan <span class="text-gray-900">#{{ $order->order_number }}</span>
            </h1>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">
        <div
            class="bg-white p-4 md:p-5 rounded-xl md:rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-center gap-2 md:gap-3 mb-3">
                <div class="p-1.5 md:p-2 bg-blue-50 rounded-lg text-blue-600 shrink-0">
                    <x-heroicon-s-check-circle class="w-4 h-4 md:w-6 md:h-6" />
                </div>
                <h3 class="font-bold text-gray-700 text-xs md:text-sm uppercase tracking-wider">Status</h3>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center text-xs md:text-sm">
                    <span class="text-gray-500">Order:</span>
                    <span
                        class="px-2 py-0.5 rounded-lg font-bold bg-green-100 text-green-700 border border-green-200">Selesai</span>
                </div>
                @if($order->payment)
                <div class="flex justify-between items-center text-xs md:text-sm">
                    <span class="text-gray-500">Bayar:</span>
                    <span class="px-2 py-0.5 rounded-lg font-bold bg-blue-50 text-blue-700 border border-blue-100">
                        {{ ucfirst($order->payment->status) }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        <div
            class="bg-white p-4 md:p-5 rounded-xl md:rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center text-white font-bold text-[10px] md:text-xs shadow-sm ring-2 ring-gray-50 shrink-0"
                    style="background-color: {{ '#' . substr(md5($order->user->name), 0, 6) }}">
                    {{ strtoupper(substr($order->user->name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-gray-900 text-xs md:text-sm truncate">{{ $order->user->name }}</p>
                    <p class="text-[10px] text-gray-500 truncate">{{ $order->user->email }}</p>
                </div>
            </div>
            <div
                class="flex items-center gap-2 text-[11px] md:text-sm text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-100">
                <x-heroicon-o-phone class="w-3.5 h-3.5 text-gray-400" />
                <span class="font-medium">{{ $order->recipient_phone }}</span>
            </div>
        </div>

        <div
            class="bg-white p-4 md:p-5 rounded-xl md:rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-center gap-2 mb-2 text-gray-700">
                <x-heroicon-o-map-pin class="w-4 h-4 text-red-500 shrink-0" />
                <h3 class="font-bold text-xs md:text-sm uppercase tracking-wider">Alamat Kirim</h3>
            </div>
            <p class="text-[11px] md:text-sm text-gray-600 leading-relaxed line-clamp-2 md:line-clamp-3 italic">
                {{ $order->shipping_address }}
            </p>
        </div>

        <div
            class="md:col-span-2 bg-white rounded-xl md:rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div
                class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-50 bg-[#F9FDF7] flex justify-between items-center">
                <h3 class="font-bold text-[#2E3B27] text-xs md:text-base uppercase tracking-tight">Rincian Produk</h3>
                <span
                    class="text-[10px] md:text-xs font-bold bg-white text-green-700 border border-green-200 px-2 py-0.5 rounded-full shadow-sm">{{
                    $order->items->count() }} Item</span>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                <div class="p-3 md:p-4 flex gap-3 md:gap-4 hover:bg-gray-50 transition items-center">
                    <div
                        class="w-12 h-12 md:w-16 md:h-16 rounded-lg overflow-hidden bg-gray-50 shrink-0 border border-gray-100 shadow-sm">
                        @if($item->product->primaryImage)
                        <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}"
                            alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <x-heroicon-o-photo class="w-5 h-5" />
                        </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 text-xs md:text-sm truncate mb-0.5">{{ $item->product->name }}
                        </p>
                        <p class="text-[10px] md:text-xs text-gray-500 font-medium">
                            {{ $item->quantity }} x <span class="text-[#8B4513]">Rp {{ number_format($item->price, 0,
                                ',', '.') }}</span>
                        </p>
                    </div>

                    <div class="text-right shrink-0">
                        <p class="text-xs md:text-sm font-black text-[#0F4C20]">
                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div
            class="bg-gray-50 p-5 md:p-6 rounded-xl md:rounded-2xl border border-gray-200 flex flex-col justify-center shadow-sm">
            <h3 class="font-bold text-gray-900 text-xs md:text-sm uppercase tracking-widest mb-4">Ringkasan Biaya</h3>

            @php
            $calculatedSubtotal = $order->items->sum(function($item) { return $item->quantity * $item->price; });
            $displaySubtotal = $order->sub_total ?? $calculatedSubtotal;
            @endphp

            <div class="space-y-3 text-xs md:text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-bold text-gray-900">Rp{{ number_format($displaySubtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Ongkos Kirim</span>
                    <span class="font-bold text-gray-900">{{ $order->shipping_cost > 0 ? 'Rp' .
                        number_format($order->shipping_cost, 0, ',', '.') : 'Gratis' }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Biaya Layanan</span>
                    <span class="font-bold text-gray-900">Rp1.000</span>
                </div>
            </div>

            <div class="border-t border-dashed border-gray-300 pt-4 mt-4">
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Total Diterima</span>
                    <span class="text-xl md:text-2xl font-black text-[#0F4C20]">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection