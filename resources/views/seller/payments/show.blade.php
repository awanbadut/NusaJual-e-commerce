@extends('layouts.seller')

@section('title', 'Detail Pembayaran')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-0 pb-10">
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('seller.payments.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex-1">
                <nav class="flex text-sm text-gray-600 mb-1">
                    <a href="{{ route('seller.payments.index') }}" class="hover:text-green-600">Pembayaran</a>
                    <span class="mx-2">›</span>
                    <span class="text-gray-900 font-medium">Detail Pembayaran</span>
                </nav>
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                    <h1 class="text-xl sm:text-2xl font-bold text-green-800">
                        Detail Pembayaran <span class="text-gray-900 font-mono">#{{ str_pad($payment->id, 4, '0',
                            STR_PAD_LEFT) }}</span>
                    </h1>

                    @if($payment->status == 'pending')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200 w-fit">
                        Belum Bayar
                    </span>
                    @elseif($payment->status == 'paid')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200 w-fit">
                        Menunggu Konfirmasi
                    </span>
                    @elseif($payment->status == 'confirmed')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200 w-fit">
                        Terkonfirmasi
                    </span>
                    @else
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200 w-fit">
                        Ditolak
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1 uppercase tracking-wide">Total Pembayaran</p>
                        <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.')
                            }}</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-sm font-semibold text-gray-500 mb-1 uppercase tracking-wide">ID Pembayaran</p>
                        <p class="text-base font-mono font-bold text-gray-900">#PAY-{{ str_pad($payment->id, 6, '0',
                            STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                <div class="relative border-l-2 border-gray-100 ml-3 space-y-6 pb-2">
                    <div class="relative pl-6">
                        <div
                            class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-green-100 border-2 border-green-500">
                        </div>
                        <p class="text-sm font-bold text-gray-900">Pembayaran Dibuat</p>
                        <p class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>

                    @if($payment->paid_at)
                    <div class="relative pl-6">
                        <div
                            class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-blue-100 border-2 border-blue-500">
                        </div>
                        <p class="text-sm font-bold text-gray-900">Bukti Pembayaran Diupload</p>
                        <p class="text-xs text-gray-500">{{ $payment->paid_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    @endif

                    @if($payment->confirmed_at)
                    <div class="relative pl-6">
                        <div
                            class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-green-100 border-2 border-green-600">
                        </div>
                        <p class="text-sm font-bold text-gray-900">Dikonfirmasi Admin</p>
                        <p class="text-xs text-gray-500">
                            {{ $payment->confirmed_at->format('d M Y, H:i') }} WIB
                            @if($payment->confirmedBy)
                            <span class="text-gray-400">• oleh {{ $payment->confirmedBy->name }}</span>
                            @endif
                        </p>
                    </div>
                    @endif

                    @if($payment->rejected_at)
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-red-100 border-2 border-red-600">
                        </div>
                        <p class="text-sm font-bold text-red-700">Ditolak Admin</p>
                        <p class="text-xs text-gray-500">
                            {{ $payment->rejected_at->format('d M Y, H:i') }} WIB
                            @if($payment->rejectedBy)
                            <span class="text-gray-400">• oleh {{ $payment->rejectedBy->name }}</span>
                            @endif
                        </p>
                        @if($payment->rejection_reason)
                        <div class="mt-2 p-3 bg-red-50 rounded-lg text-xs text-red-700 italic border border-red-100">
                            "{{ $payment->rejection_reason }}"
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-2 mb-4 border-b border-gray-100 pb-4">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-green-800">Informasi Order Terkait</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nomor Order</p>
                        <a href="{{ route('seller.orders.show', $payment->order_id) }}"
                            class="text-lg font-bold text-green-600 hover:text-green-800 hover:underline transition flex items-center gap-1">
                            #ORD-{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                </path>
                            </svg>
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Status Order Saat Ini</p>
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
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $orderStatusClasses[$payment->order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($payment->order->status) }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100">
                    <p class="text-sm font-semibold text-gray-900 mb-3">Rincian Tagihan</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($payment->order->sub_total ?? 0,
                                0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ongkir</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($payment->order->shipping_cost,
                                0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-bold border-t border-gray-100 pt-2 mt-2">
                            <span class="text-gray-900">Total Tagihan</span>
                            <span class="text-green-600">Rp {{ number_format($payment->order->total_amount, 0, ',', '.')
                                }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-green-800 mb-4">Pelanggan</h3>

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0"
                        style="background-color: {{ '#' . substr(md5($payment->order->user->name), 0, 6) }}">
                        {{ strtoupper(substr($payment->order->user->name, 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-base font-semibold text-gray-900 truncate">{{ $payment->order->user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $payment->order->user->email }}</p>
                    </div>
                </div>

                <div class="space-y-3 pt-3 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">No. Telepon</p>
                        <p class="text-sm font-medium text-gray-900">{{ $payment->order->user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Alamat Pengiriman</p>
                        <p class="text-sm font-medium text-gray-900 leading-relaxed">
                            {{ $payment->order->shipping_address ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-green-800 mb-4">Bukti Pembayaran</h3>

                @if($payment->payment_proof)
                <div
                    class="aspect-[4/3] rounded-lg overflow-hidden bg-gray-50 border border-gray-100 mb-4 relative group">
                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Bukti Pembayaran"
                        class="w-full h-full object-contain cursor-pointer transition transform group-hover:scale-105"
                        onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')">
                    <div
                        class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center pointer-events-none">
                        <span class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full">Klik untuk
                            memperbesar</span>
                    </div>
                </div>
                <button onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')"
                    class="w-full py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium text-sm transition flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Lihat Fullsize
                </button>
                @else
                <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-sm font-medium text-gray-500">Belum ada bukti</p>
                </div>
                @endif

                @if($payment->notes)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Catatan Pembeli</p>
                    <p class="text-sm text-gray-700 italic">"{{ $payment->notes }}"</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<div id="proofModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-opacity duration-300"
    onclick="closeProofModal()">
    <div class="max-w-4xl w-full max-h-[90vh] relative bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col"
        onclick="event.stopPropagation()">
        <div class="p-4 border-b flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-900">Bukti Pembayaran</h3>
            <button onclick="closeProofModal()" class="text-gray-500 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-auto p-2 bg-gray-900 flex items-center justify-center">
            <img id="proofImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-full object-contain">
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function viewProof(url) {
    document.getElementById('proofImage').src = url;
    const modal = document.getElementById('proofModal');
    modal.classList.remove('hidden');
    // Animasi fade-in
    setTimeout(() => { modal.classList.remove('opacity-0'); }, 10);
    document.body.style.overflow = 'hidden';
}

function closeProofModal() {
    const modal = document.getElementById('proofModal');
    modal.classList.add('opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
    document.body.style.overflow = 'auto';
}
</script>
@endpush