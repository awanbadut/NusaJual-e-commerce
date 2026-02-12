@extends('layouts.seller')

@section('title', 'Detail Pembayaran')
@section('page-title', 'Detail Pembayaran #' . str_pad($payment->id, 4, '0', STR_PAD_LEFT))
@section('page-subtitle', 'Informasi lengkap pembayaran dari customer')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8">
    <div class="max-w-full mx-auto">

        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('seller.payments.index') }}" 
               class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Pembayaran
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Payment Info -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Payment Status Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Status Pembayaran</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Status Saat Ini</p>
                                @if($payment->status == 'pending')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Belum Bayar
                                </span>
                                @elseif($payment->status == 'paid')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 border border-yellow-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Menunggu Konfirmasi Admin
                                </span>
                                @elseif($payment->status == 'confirmed')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Terkonfirmasi
                                </span>
                                @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 border border-red-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Ditolak
                                </span>
                                @endif
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-gray-500 mb-1">Jumlah Pembayaran</p>
                                <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Pembayaran Dibuat</p>
                                    <p class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y, H:i') }} WIB</p>
                                </div>
                            </div>

                            @if($payment->paid_at)
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Bukti Pembayaran Diupload</p>
                                    <p class="text-xs text-gray-500">{{ $payment->paid_at->format('d M Y, H:i') }} WIB</p>
                                </div>
                            </div>
                            @endif

                            @if($payment->confirmed_at)
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Dikonfirmasi oleh Admin</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $payment->confirmed_at->format('d M Y, H:i') }} WIB
                                        @if($payment->confirmedBy)
                                        <span class="ml-1">oleh {{ $payment->confirmedBy->name }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if($payment->rejected_at)
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Ditolak oleh Admin</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $payment->rejected_at->format('d M Y, H:i') }} WIB
                                        @if($payment->rejectedBy)
                                        <span class="ml-1">oleh {{ $payment->rejectedBy->name }}</span>
                                        @endif
                                    </p>
                                    @if($payment->rejection_reason)
                                    <p class="text-xs text-red-600 mt-1 italic">"{{ $payment->rejection_reason }}"</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Informasi Order</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Nomor Order</p>
                                <a href="{{ route('seller.orders.show', $payment->order_id) }}" 
                                   class="text-lg font-bold text-green-600 hover:underline">
                                    #ORD-{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Status Order</p>
                                @php
                                    $orderStatusClasses = [
                                        'pending' => 'bg-gray-100 text-gray-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'processing' => 'bg-yellow-100 text-yellow-800',
                                        'shipped' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $orderStatusClasses[$payment->order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($payment->order->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tanggal Order</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->order->created_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Metode Pembayaran</p>
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($payment->order->payment_method ?? 'Transfer Bank') }}</p>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <h4 class="text-sm font-bold text-gray-900 mb-4">Item Order</h4>
                            <div class="space-y-3">
                                @foreach($payment->order->items as $item)
                                <div class="flex items-center gap-4 pb-3 border-b border-gray-100 last:border-0">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                                        @if($item->product && $item->product->primaryImage)
                                        <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" 
                                             alt="{{ $item->product_name }}"
                                             class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $item->product_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900 shrink-0">
                                        Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                                @endforeach
                            </div>

                            <!-- Order Summary -->
                            <div class="mt-6 space-y-2 border-t border-gray-200 pt-4">
                                @php
                                    // ✅ FIX: Gunakan sub_total (dengan underscore)
                                    $calculatedSubtotal = $payment->order->items->sum(function($item) {
                                        return $item->quantity * $item->price;
                                    });
                                    
                                    // Cek kolom sub_total atau subtotal
                                    $displaySubtotal = $payment->order->sub_total ?? $calculatedSubtotal;
                                @endphp
                                
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">Rp {{ number_format($displaySubtotal, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($payment->order->shipping_cost > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Ongkir</span>
                                    <span class="font-medium text-gray-900">Rp {{ number_format($payment->order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                
                                @if($payment->order->tax ?? 0 > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Pajak</span>
                                    <span class="font-medium text-gray-900">Rp {{ number_format($payment->order->tax, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                
                                <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2">
                                    <span class="text-gray-900">Total</span>
                                    <span class="text-green-600">Rp {{ number_format($payment->order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Customer & Proof -->
            <div class="space-y-6">
                
                <!-- Customer Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Customer</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center text-white font-bold text-xl shrink-0" 
                                 style="background-color: {{ '#' . substr(md5($payment->order->user->name), 0, 6) }}">
                                {{ strtoupper(substr($payment->order->user->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-lg font-bold text-gray-900 truncate">{{ $payment->order->user->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $payment->order->user->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">No. Telepon</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->order->user->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Alamat Pengiriman</p>
                                <p class="text-sm font-medium text-gray-900 leading-relaxed">
                                    {{ $payment->order->shipping_address ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Proof -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Bukti Pembayaran</h3>
                    </div>
                    
                    <div class="p-6">
                        @if($payment->payment_proof)
                        <div class="aspect-[4/3] rounded-lg overflow-hidden bg-gray-100 mb-4">
                            <img src="{{ asset('storage/' . $payment->payment_proof) }}" 
                                 alt="Bukti Pembayaran"
                                 class="w-full h-full object-contain cursor-pointer hover:scale-105 transition"
                                 onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')">
                        </div>
                        <button onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')"
                                class="w-full py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium text-sm transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Lihat Fullsize
                        </button>
                        @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm font-medium text-gray-700 mb-1">Belum ada bukti pembayaran</p>
                            <p class="text-xs text-gray-500">Customer belum upload bukti transfer</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Detail Pembayaran</h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">ID Pembayaran</p>
                            <p class="text-sm font-mono font-bold text-gray-900">#PAY-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Metode Pembayaran</p>
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($payment->payment_method ?? 'Transfer Bank') }}</p>
                        </div>
                        @if($payment->notes)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Catatan</p>
                            <p class="text-sm font-medium text-gray-900 leading-relaxed">{{ $payment->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal for Full Size Image -->
<div id="proofModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm transition-opacity duration-300"
    onclick="closeProofModal()">
    <div class="max-w-5xl max-h-[95vh] relative bg-white rounded-lg shadow-2xl overflow-hidden"
        onclick="event.stopPropagation()">
        <button onclick="closeProofModal()"
            class="absolute top-4 right-4 p-2 bg-black/50 text-white rounded-full hover:bg-black/70 transition z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="proofImage" src="" alt="Bukti Pembayaran" class="w-full h-full object-contain max-h-[90vh]">
    </div>
</div>

@endsection

@push('scripts')
<script>
function viewProof(url) {
    document.getElementById('proofImage').src = url;
    const modal = document.getElementById('proofModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('opacity-100');
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closeProofModal() {
    const modal = document.getElementById('proofModal');
    modal.classList.remove('opacity-100');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
    document.body.style.overflow = 'auto';
}
</script>
@endpush
