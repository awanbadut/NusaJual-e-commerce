@extends('layouts.seller')

@section('title', 'Detail Penjualan')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="max-w-7xl">
    <!-- Breadcrumb & Back Button -->
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('seller.sales.index') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <nav class="flex text-sm text-gray-600 mb-1">
                <a href="{{ route('seller.sales.index') }}" class="hover:text-green-800">Riwayat Penjualan</a>
                <span class="mx-2">›</span>
                <span class="text-gray-900 font-medium">Detail Penjualan</span>
            </nav>
            <h1 class="text-3xl font-bold text-green-800">
                Detail Penjualan
                <span class="text-gray-900">{{ $order->order_number }}</span>
            </h1>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div
            class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-full hover:shadow-md transition duration-200">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-700">Status Pesanan</h3>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Order:</span>
                    <span
                        class="px-2.5 py-1 rounded-lg text-xs font-bold bg-green-100 text-green-700 border border-green-200">Selesai</span>
                </div>
                @if($order->payment)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Bayar:</span>
                    <span
                        class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                        {{ ucfirst($order->payment->status) }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        <div
            class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-full hover:shadow-md transition duration-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm ring-2 ring-gray-50"
                    style="background-color: {{ '#' . substr(md5($order->user->name), 0, 6) }}">
                    {{ strtoupper(substr($order->user->name, 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="font-bold text-gray-900 truncate">{{ $order->user->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $order->user->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 p-2 rounded-lg">
                <x-heroicon-o-phone class="w-4 h-4 text-gray-400" />
                <span>{{ $order->recipient_phone }}</span>
            </div>
        </div>

        <div
            class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col h-full hover:shadow-md transition duration-200">
            <div class="flex items-center gap-2 mb-3 text-gray-700">
                <x-heroicon-o-map-pin class="w-5 h-5 text-red-500" />
                <h3 class="font-bold text-sm">Alamat Pengiriman</h3>
            </div>
            <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">
                {{ $order->shipping_address }}
            </p>
        </div>

        <div class="md:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Detail Produk</h3>
                <span class="text-xs font-semibold bg-gray-200 text-gray-600 px-2 py-1 rounded-md">{{
                    $order->items->count() }} Item</span>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                <div class="p-4 flex gap-4 hover:bg-gray-50 transition items-center">
                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-200">
                        @if($item->product->primaryImage)
                        <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}"
                            alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <x-heroicon-o-photo class="w-6 h-6" />
                        </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 text-sm truncate">{{ $item->product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price,
                            0, ',', '.') }}</p>
                    </div>

                    <div class="text-right font-medium text-gray-900 text-sm">
                        Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 flex flex-col justify-center gap-4">
            <h3 class="font-bold text-gray-900 mb-2">Ringkasan Biaya</h3>

            @php
            $calculatedSubtotal = $order->items->sum(function($item) { return $item->quantity * $item->price; });
            $displaySubtotal = $order->sub_total ?? $calculatedSubtotal;
            @endphp

            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($displaySubtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Ongkos Kirim</span>
                    <span class="font-medium">{{ $order->shipping_cost > 0 ? 'Rp ' .
                        number_format($order->shipping_cost, 0, ',', '.') : '-' }}</span>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4 mt-2">
                <div class="flex flex-col gap-1">
                    <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-green-700">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection