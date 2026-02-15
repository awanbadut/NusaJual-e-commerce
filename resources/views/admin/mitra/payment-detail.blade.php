@extends('layouts.admin')

@section('title', 'Detail Pembayaran - ' . $store->store_name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
    
    {{-- BREADCRUMB & HEADER --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.mitra.show', $store->id) }}" 
           class="p-2 hover:bg-gray-100 rounded-lg transition">
            <x-heroicon-o-arrow-left class="w-6 h-6 text-gray-600" />
        </a>
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.mitra.index') }}" class="hover:text-gray-700">Mitra</a>
                <span>›</span>
                <a href="{{ route('admin.mitra.show', $store->id) }}" class="hover:text-gray-700">{{ $store->store_name }}</a>
                <span>›</span>
                <span class="text-gray-900 font-semibold">Detail Pembayaran</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Verifikasi Pembayaran</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- MAIN CONTENT (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- STATUS CARD --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Status Pembayaran</h2>
                        <p class="text-xs text-gray-500 mt-1">ID Pembayaran: #PAY-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    @if($payment->status === 'confirmed')
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-[#15803D] text-white text-sm font-bold shadow-sm">
                        <x-heroicon-s-check-circle class="w-5 h-5 mr-2" />
                        Terkonfirmasi
                    </span>
                    @elseif($payment->status === 'pending')
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 text-sm font-bold border border-yellow-200">
                        <x-heroicon-s-clock class="w-5 h-5 mr-2" />
                        Menunggu
                    </span>
                    @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-red-100 text-red-800 text-sm font-bold border border-red-200">
                        <x-heroicon-s-x-circle class="w-5 h-5 mr-2" />
                        Ditolak
                    </span>
                    @endif
                </div>

                {{-- PAYMENT INFO GRID --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                            <x-heroicon-s-calendar class="w-3 h-3" />
                            Tanggal Upload
                        </p>
                        <p class="text-sm font-semibold text-gray-900">{{ $payment->created_at->format('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }} WIB</p>
                    </div>
                    
                    @if($payment->confirmed_at)
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-xs text-green-700 mb-1 flex items-center gap-1">
                            <x-heroicon-s-check-circle class="w-3 h-3" />
                            Tanggal Konfirmasi
                        </p>
                        <p class="text-sm font-semibold text-green-900">{{ $payment->confirmed_at->format('d F Y') }}</p>
                        <p class="text-xs text-green-700">{{ $payment->confirmed_at->format('H:i') }} WIB</p>
                    </div>
                    @endif
                </div>

                {{-- AMOUNT --}}
                <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-green-700 mb-1 font-semibold">Total Pembayaran</p>
                            <p class="text-3xl font-bold text-green-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center">
                            <x-heroicon-s-check class="w-10 h-10 text-white" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ORDER DETAILS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-s-shopping-bag class="w-6 h-6 text-green-600" />
                    Detail Pesanan
                </h2>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">ID Pesanan</p>
                        <p class="font-mono font-semibold text-gray-900 text-sm">#NB-{{ str_pad($payment->order->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Tanggal Pesanan</p>
                        <p class="font-semibold text-gray-900 text-sm">{{ $payment->order->created_at->format('d F Y') }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Status Pesanan</p>
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold
                            @if($payment->order->status === 'completed') bg-green-100 text-green-800
                            @elseif($payment->order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($payment->order->status === 'shipped') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($payment->order->status) }}
                        </span>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
                        <p class="font-semibold text-gray-900 text-sm">Transfer Bank</p>
                    </div>
                </div>

                <hr class="my-4 border-gray-200">

                {{-- ORDER ITEMS --}}
                <h3 class="text-sm font-bold text-gray-900 mb-3">Produk Dibeli</h3>
                <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
                    @foreach($orderItems as $item)
                    <div class="flex gap-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        @if($item['image'])
                        <img src="{{ asset('storage/' . $item['image']) }}" 
                             alt="{{ $item['product_name'] }}" 
                             class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <x-heroicon-o-photo class="w-8 h-8 text-gray-400" />
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $item['product_name'] }}</h3>
                            <p class="text-xs text-gray-600 mt-1">
                                <span class="font-semibold">{{ $item['quantity'] }}</span> × 
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </p>
                            <p class="text-sm font-bold text-green-600 mt-1">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <hr class="my-4 border-gray-200">

                {{-- PRICE SUMMARY --}}
                <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal Produk:</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($orderItems->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim:</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($payment->order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-dashed border-gray-300">
                    <div class="flex justify-between text-base pt-2">
                        <span class="font-bold text-gray-900">Total Pembayaran:</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($payment->order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- BUYER INFO --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-s-user class="w-6 h-6 text-green-600" />
                    Informasi Pembeli
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Nama Lengkap</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $payment->order->user->name }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $payment->order->user->email }}</p>
                    </div>
                    @if($payment->order->user->phone)
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Nomor Telepon</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $payment->order->user->phone }}</p>
                    </div>
                    @endif
                </div>

                @if($payment->order->shippingAddress)
                <hr class="my-4 border-gray-200">
                <div>
                    <p class="text-xs text-gray-500 mb-2 font-semibold flex items-center gap-1">
                        <x-heroicon-s-map-pin class="w-3 h-3" />
                        Alamat Pengiriman
                    </p>
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                        <p class="text-sm font-semibold text-blue-900 mb-1">{{ $payment->order->shippingAddress->recipient_name }}</p>
                        <p class="text-sm text-blue-800">{{ $payment->order->shippingAddress->address }}</p>
                        <p class="text-sm text-blue-800">{{ $payment->order->shippingAddress->city }}, {{ $payment->order->shippingAddress->province }} {{ $payment->order->shippingAddress->postal_code }}</p>
                        @if($payment->order->shippingAddress->phone)
                        <p class="text-sm text-blue-800 mt-2 flex items-center gap-1">
                            <x-heroicon-s-phone class="w-3 h-3" />
                            {{ $payment->order->shippingAddress->phone }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>

        </div>

        {{-- SIDEBAR (1/3) --}}
        <div class="space-y-6">
            
            {{-- BUKTI PEMBAYARAN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-s-photo class="w-6 h-6 text-green-600" />
                    Bukti Pembayaran
                </h2>

                @if($payment->payment_proof)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" 
                         alt="Bukti Pembayaran" 
                         class="w-full rounded-lg border-2 border-gray-200 cursor-pointer hover:border-green-500 transition"
                         onclick="viewProofModal('{{ asset('storage/' . $payment->payment_proof) }}')">
                    
                    <button onclick="viewProofModal('{{ asset('storage/' . $payment->payment_proof) }}')"
                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition flex items-center justify-center rounded-lg">
                        <span class="opacity-0 group-hover:opacity-100 transition bg-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2">
                            <x-heroicon-s-magnifying-glass-plus class="w-5 h-5" />
                            Perbesar
                        </span>
                    </button>
                </div>

                <a href="{{ asset('storage/' . $payment->payment_proof) }}" 
                   download
                   class="mt-4 block w-full text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center justify-center gap-2">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                    Download Bukti
                </a>
                @else
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <x-heroicon-o-photo class="w-12 h-12 text-gray-300 mx-auto mb-2" />
                    <p class="text-sm text-gray-500">Bukti pembayaran tidak tersedia</p>
                </div>
                @endif
            </div>

            {{-- TIMELINE --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-s-clock class="w-6 h-6 text-green-600" />
                    Timeline Transaksi
                </h2>

                <div class="space-y-4">
                    @foreach($timeline as $index => $event)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                {{ $event['status'] === 'completed' ? 'bg-green-100 text-green-600 ring-4 ring-green-50' : 'bg-gray-100 text-gray-400' }}">
                                @if($event['icon'] === 'shopping-cart')
                                <x-heroicon-s-shopping-cart class="w-5 h-5" />
                                @elseif($event['icon'] === 'credit-card')
                                <x-heroicon-s-credit-card class="w-5 h-5" />
                                @elseif($event['icon'] === 'check-circle')
                                <x-heroicon-s-check-circle class="w-5 h-5" />
                                @else
                                <x-heroicon-s-check-badge class="w-5 h-5" />
                                @endif
                            </div>
                            @if($index < count($timeline) - 1)
                            <div class="w-0.5 h-10 {{ $event['status'] === 'completed' ? 'bg-green-200' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $event['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $event['date']->format('d F Y, H:i') }} WIB</p>
                            @if(isset($event['admin']))
                            <p class="text-xs text-gray-500 mt-0.5">oleh {{ $event['admin'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- STORE INFO --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-s-building-storefront class="w-6 h-6 text-green-600" />
                    Informasi Toko
                </h2>

                <div class="text-center mb-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto mb-3 shadow-lg">
                        @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}" class="w-full h-full object-cover rounded-2xl">
                        @else
                        {{ strtoupper(substr($store->store_name, 0, 2)) }}
                        @endif
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg">{{ $store->store_name }}</h3>
                    <p class="text-xs text-gray-500 mt-1 flex items-center justify-center gap-1">
                        <x-heroicon-s-map-pin class="w-3 h-3" />
                        {{ $store->address }}
                    </p>
                </div>

                <a href="{{ route('admin.mitra.show', $store->id) }}"
                   class="block w-full text-center bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                    Lihat Detail Toko
                </a>
            </div>

        </div>
    </div>
</div>

{{-- MODAL VIEW PROOF --}}
<div id="proofModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4" onclick="closeProofModal()">
    <div class="relative max-w-4xl w-full">
        <button onclick="closeProofModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 flex items-center gap-2">
            <span class="text-sm font-semibold">Tutup</span>
            <x-heroicon-s-x-mark class="w-8 h-8" />
        </button>
        <img id="proofImage" src="" alt="Bukti Pembayaran" class="w-full rounded-lg shadow-2xl">
    </div>
</div>

@endsection

@push('scripts')
<script>
function viewProofModal(url) {
    document.getElementById('proofImage').src = url;
    document.getElementById('proofModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeProofModal() {
    document.getElementById('proofModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// ESC key to close
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProofModal();
    }
});
</script>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #15803D;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #166534;
}
</style>
@endpush
