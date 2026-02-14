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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Order Info & Customer -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Order Info Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pesanan</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor Order</p>
                        <p class="text-sm font-bold text-gray-900 font-mono mt-1">{{ $order->order_number }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Pesanan</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 mt-1">
                            Selesai
                        </span>
                    </div>
                    
                    @if($order->payment)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Pembayaran</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 mt-1">
                            {{ ucfirst($order->payment->status) }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Info Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pelanggan</h3>
                
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-sm"
                        style="background-color: {{ '#' . substr(md5($order->user->name), 0, 6) }}">
                        {{ strtoupper(substr($order->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $order->user->email }}</p>
                    </div>
                </div>
                
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-2">
                        <x-heroicon-o-phone class="w-4 h-4 text-gray-400 mt-0.5" />
                        <div>
                            <p class="text-xs font-semibold text-gray-500">Telepon</p>
                            <p class="text-gray-900">{{ $order->recipient_phone }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-2">
                        <x-heroicon-o-map-pin class="w-4 h-4 text-gray-400 mt-0.5" />
                        <div>
                            <p class="text-xs font-semibold text-gray-500">Alamat Pengiriman</p>
                            <p class="text-gray-900">{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Order Items & Summary -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Produk</h3>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex gap-4 p-4 border border-gray-200 rounded-lg hover:border-green-200 transition">
                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            @if($item->product->primaryImage)
                            <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" 
                                alt="{{ $item->product->name }}"
                                class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <x-heroicon-o-photo class="w-8 h-8" />
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-bold text-gray-900">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="mt-6 space-y-2 border-t border-gray-200 pt-4">
                    @php
                        $calculatedSubtotal = $order->items->sum(function($item) {
                            return $item->quantity * $item->price;
                        });
                        $displaySubtotal = $order->sub_total ?? $calculatedSubtotal;
                    @endphp
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($displaySubtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($order->shipping_cost > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Ongkir</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2">
                        <span class="text-gray-900">Total</span>
                        <span class="text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
