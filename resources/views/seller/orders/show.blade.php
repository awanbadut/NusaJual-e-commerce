@extends('layouts.seller')

@section('title', 'Detail Pesanan')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="max-w-6xl">
    <!-- Breadcrumb & Back Button -->
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('seller.orders.index') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <nav class="flex text-sm text-gray-600 mb-1">
                <a href="{{ route('seller.orders.index') }}" class="hover:text-green-800">Pesanan</a>
                <span class="mx-2">›</span>
                <span class="text-gray-900 font-medium">Detail Pesanan</span>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-green-800">Detail Pesanan 
                    <span class="text-gray-900">{{ $order->order_number }}</span>
                </h1>
                @if($order->status == 'processing')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500 text-gray-900">Diproses</span>
                @elseif($order->status == 'packing')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-500 text-white">Dikemas</span>
                @elseif($order->status == 'shipped')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-600 text-white">Dikirim</span>
                @elseif($order->status == 'completed')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white">Selesai</span>
                @elseif($order->status == 'pending')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-600 text-white">Menunggu Pembayaran</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white">Dibatalkan</span>
                @endif
            </div>
        </div>
    </div>

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

    @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <ul class="list-disc list-inside text-sm text-red-800">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Info Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Nomor Pesanan</h2>
                </div>
                <p class="text-xl font-bold text-gray-900">{{ $order->order_number }}</p>
                
                @if($order->tracking_number)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Kurir</p>
                            <p class="text-base font-semibold text-gray-900">{{ $order->courier }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Nomor Resi</p>
                            <p class="text-base font-semibold text-gray-900">{{ $order->tracking_number }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Products List -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-green-800">Ringkasan Produk</h2>
                </div>

                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 p-3 border border-gray-200 rounded-lg">
                        <!-- Product Image -->
                        @if($item->product->images->count() > 0)
                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                 class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Product Info -->
                        <div class="flex-1">
                            <p class="text-xs text-gray-500">{{ $item->product->category->name ?? 'Produk' }}</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-600">{{ $item->quantity }} {{ $item->product->unit ?? 'pcs' }}</p>
                        </div>

                        <!-- Price -->
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Status Progress -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status Pesanan</h3>
                
                <!-- Progress Bar -->
                <div class="relative">
                    <div class="flex items-center justify-between mb-2">
                        @php
                            $statuses = [
                                'pending' => 'Menunggu Pembayaran',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'completed' => 'Selesai'
                            ];
                            $currentStep = match($order->status) {
                                'pending' => 1,
                                'processing', 'packing' => 2,
                                'shipped' => 3,
                                'completed' => 4,
                                default => 1
                            };
                        @endphp

                        @foreach($statuses as $key => $label)
                        <div class="flex flex-col items-center {{ $loop->first ? '' : 'flex-1' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $loop->iteration <= $currentStep ? 'bg-green-600' : 'bg-gray-300' }}">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Progress Line -->
                    <div class="absolute top-4 left-0 right-0 h-1 bg-gray-300 -z-10" style="width: calc(100% - 2rem); margin-left: 1rem;">
                        <div class="h-full bg-green-600 transition-all" style="width: {{ (($currentStep - 1) / 3) * 100 }}%"></div>
                    </div>

                    <!-- Labels -->
                    <div class="flex items-start justify-between mt-3">
                        @foreach($statuses as $label)
                        <span class="text-xs text-gray-600 text-center" style="max-width: 100px;">{{ $label }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-green-800">Alamat Pengiriman</h3>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 mb-1">{{ $order->recipient_name ?? $order->user->name }}</p>
                    <p class="text-sm text-gray-600 mb-2">{{ $order->recipient_phone }}</p>
                    <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Summary & Customer Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-bold text-green-800 mb-4">Informasi Pesanan</h3>
                
                <div class="space-y-3 text-sm mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Pesanan</span>
                        <span class="font-semibold text-gray-900">{{ $order->created_at->format('d M Y') }}</span>
                    </div>
                    @if($order->payment && $order->payment->payment_proof)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bukti Pembayaran</span>
                        <a href="{{ asset('storage/' . $order->payment->payment_proof) }}" target="_blank" 
                           class="text-green-800 font-semibold underline hover:text-green-900">
                            Lihat Bukti
                        </a>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Status Pembayaran</span>
                        @if($order->payment_status == 'confirmed')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-500 text-white">Terkonfirmasi</span>
                        @elseif($order->payment_status == 'paid')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-400 text-gray-900">Menunggu Konfirmasi</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-orange-600 text-white">Pending</span>
                        @endif
                    </div>
                </div>

                <hr class="my-4">

                <h3 class="text-lg font-bold text-green-800 mb-4">Pelanggan</h3>
                
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm" 
                         style="background-color: {{ '#' . substr(md5($order->user->name), 0, 6) }}">
                        {{ strtoupper(substr($order->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->user->name }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-800 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-600">Email</p>
                            <p class="text-sm text-gray-900">{{ $order->user->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-800 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-600">Telepon</p>
                            <p class="text-sm text-gray-900">{{ $order->recipient_phone ?? $order->user->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-green-800">Rincian Pembayaran</h3>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sub Total</span>
                        <span class="text-gray-900">Rp {{ number_format($order->sub_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pengiriman</span>
                        <span class="text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    
                    <hr class="my-2">
                    
                    <div class="flex justify-between text-base font-bold">
                        <span class="text-gray-900">Total</span>
                        <span class="text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- UPDATE STATUS FORM -->
    @if($order->payment_status == 'confirmed' && !in_array($order->status, ['completed', 'cancelled']))
    <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
        <h3 class="text-lg font-bold text-green-800 mb-4">Update Status Pesanan</h3>
        
        <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST" x-data="orderStatusForm()">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Status -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Pesanan *</label>
                    <select name="status" required x-model="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600">
                        <option value="">Pilih Status</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                            Processing - Sedang Diproses
                        </option>
                        <option value="packing" {{ $order->status == 'packing' ? 'selected' : '' }}>
                            Packing - Sedang Dikemas
                        </option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>
                            Shipped - Dalam Pengiriman
                        </option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                            Completed - Selesai/Diterima
                        </option>
                        <option value="cancelled">Cancelled - Dibatalkan</option>
                    </select>
                </div>

                <!-- Courier (show only when shipped) -->
                <div x-show="status === 'shipped'" x-cloak>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kurir *</label>
                    <select name="courier"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600">
                        <option value="">Pilih Kurir</option>
                        <option value="JNE" {{ $order->courier == 'JNE' ? 'selected' : '' }}>JNE</option>
                        <option value="J&T" {{ $order->courier == 'J&T' ? 'selected' : '' }}>J&T Express</option>
                        <option value="SiCepat" {{ $order->courier == 'SiCepat' ? 'selected' : '' }}>SiCepat</option>
                        <option value="POS" {{ $order->courier == 'POS' ? 'selected' : '' }}>POS Indonesia</option>
                        <option value="TIKI" {{ $order->courier == 'TIKI' ? 'selected' : '' }}>TIKI</option>
                        <option value="AnterAja" {{ $order->courier == 'AnterAja' ? 'selected' : '' }}>AnterAja</option>
                    </select>
                </div>

                <!-- Tracking Number -->
                <div x-show="status === 'shipped'" x-cloak>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Resi *</label>
                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600"
                           placeholder="Contoh: JNE12345678">
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                    Update Status
                </button>
                <a href="{{ route('seller.orders.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-semibold text-center transition">
                    Kembali ke Daftar
                </a>
            </div>
        </form>
    </div>

    @elseif($order->payment_status !== 'confirmed' && $order->status !== 'cancelled')
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mt-6 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="text-sm font-semibold text-yellow-800">Pembayaran belum dikonfirmasi admin</p>
                <p class="text-xs text-yellow-700 mt-1">Anda belum bisa mengubah status pesanan sampai admin mengkonfirmasi pembayaran.</p>
            </div>
        </div>
    </div>
    @endif

    @if(in_array($order->status, ['completed', 'cancelled']))
    <div class="bg-gray-50 border-l-4 border-gray-400 p-4 mt-6 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-gray-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-sm font-semibold text-gray-700">
                Pesanan sudah {{ $order->status == 'completed' ? 'selesai' : 'dibatalkan' }}. Status tidak bisa diubah lagi.
            </p>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function orderStatusForm() {
        return {
            status: '{{ $order->status }}'
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
