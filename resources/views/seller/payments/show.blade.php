@extends('layouts.seller')

@section('title', 'Detail Pembayaran')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="max-w-6xl mx-auto px-2 sm:px-0 pb-10">
    <div class="mb-4 md:mb-6 flex items-center gap-3 md:gap-4">
        <a href="{{ route('seller.payments.index') }}"
            class="p-1.5 md:p-2 hover:bg-gray-100 rounded-lg transition bg-white border border-gray-100 shadow-sm shrink-0">
            <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="min-w-0">
            <nav class="flex text-[10px] md:text-sm text-gray-500 mb-0.5">
                <a href="{{ route('seller.payments.index') }}" class="hover:text-green-800 font-medium">Pembayaran</a>
                <span class="mx-1.5">›</span>
                <span class="text-gray-900 font-medium truncate">Detail #{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT)
                    }}</span>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">

        <div class="lg:col-span-2 space-y-4 md:space-y-6">

            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6">
                <div class="flex flex-row items-start justify-between gap-4 mb-6">
                    <div class="min-w-0">
                        <p class="text-[10px] md:text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Total
                            Pembayaran</p>
                        <p class="text-2xl md:text-3xl font-black text-[#0F4C20]">Rp {{ number_format($payment->amount,
                            0, ',', '.') }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[10px] md:text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">ID
                            Bayar</p>
                        <p class="text-xs md:text-base font-mono font-bold text-gray-800">#PAY-{{ str_pad($payment->id,
                            6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                <div class="relative border-l-2 border-green-50 ml-3 space-y-5 md:space-y-6 pb-2">
                    <div class="relative pl-6">
                        <div
                            class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-green-50 border-2 border-green-500">
                        </div>
                        <p class="text-xs md:text-sm font-bold text-gray-900 leading-tight">Pesanan Dibuat</p>
                        <p class="text-[10px] md:text-xs text-gray-400 mt-0.5">{{ $payment->created_at->format('d M Y,
                            H:i') }} WIB</p>
                    </div>

                    @if($payment->paid_at)
                    <div class="relative pl-6">
                        <div
                            class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-blue-50 border-2 border-blue-500">
                        </div>
                        <p class="text-xs md:text-sm font-bold text-gray-900 leading-tight">Bukti Diupload</p>
                        <p class="text-[10px] md:text-xs text-gray-400 mt-0.5">{{ $payment->paid_at->format('d M Y,
                            H:i') }} WIB</p>
                    </div>
                    @endif

                    @if($payment->confirmed_at)
                    <div class="relative pl-6">
                        <div
                            class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-green-50 border-2 border-green-600">
                        </div>
                        <p class="text-xs md:text-sm font-bold text-green-700 leading-tight">Dikonfirmasi Admin</p>
                        <p class="text-[10px] md:text-xs text-gray-400 mt-0.5">{{ $payment->confirmed_at->format('d M Y,
                            H:i') }} WIB</p>
                    </div>
                    @elseif($payment->rejected_at)
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-red-50 border-2 border-red-600">
                        </div>
                        <p class="text-xs md:text-sm font-bold text-red-700 leading-tight">Pembayaran Ditolak</p>
                        <p class="text-[10px] md:text-xs text-gray-400 mt-0.5">{{ $payment->rejected_at->format('d M Y,
                            H:i') }} WIB</p>
                        @if($payment->rejection_reason)
                        <div
                            class="mt-2 p-2.5 bg-red-50 rounded-lg text-[10px] md:text-xs text-red-800 border border-red-100 italic">
                            "{{ $payment->rejection_reason }}"
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6">
                <div class="flex items-center gap-2 mb-4 border-b border-gray-50 pb-3">
                    <x-heroicon-s-shopping-bag class="w-4 h-4 text-green-600" />
                    <h3 class="text-xs md:text-base font-bold text-gray-900 uppercase tracking-widest">Detail Orderan
                    </h3>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-[10px] md:text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Nomor
                            Order</p>
                        <a href="{{ route('seller.orders.show', $payment->order_id) }}"
                            class="text-xs md:text-sm font-bold text-green-700 hover:text-green-800 hover:underline flex items-center gap-1">
                            #ORD-{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
                            <x-heroicon-m-arrow-top-right-on-square class="w-3 h-3 md:w-4 md:h-4" />
                        </a>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] md:text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Status
                            Order</p>
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] md:text-xs font-black uppercase bg-gray-100 text-gray-600 border border-gray-200">
                            {{ $payment->order->status }}
                        </span>
                    </div>
                </div>

                <div class="space-y-2 text-[11px] md:text-sm pt-4 border-t border-dashed border-gray-200">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal Produk</span>
                        <span class="font-bold text-gray-900">Rp{{ number_format($payment->order->sub_total ?? 0, 0,
                            ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Ongkos Kirim</span>
                        <span class="font-bold text-gray-900">Rp{{ number_format($payment->order->shipping_cost, 0, ',',
                            '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[#0F4C20] font-black pt-2 text-base">
                        <span>Total Tagihan</span>
                        <span>Rp{{ number_format($payment->order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-4 md:space-y-6">

            <div
                class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6 hover:shadow-md transition">
                <h3 class="text-xs md:text-sm font-black text-[#0F4C20] uppercase tracking-widest mb-4">Informasi
                    Pelanggan</h3>

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center text-white font-black text-sm shrink-0 shadow-sm"
                        style="background-color: {{ '#' . substr(md5($payment->order->user->name), 0, 6) }}">
                        {{ strtoupper(substr($payment->order->user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ $payment->order->user->name }}</p>
                        <p class="text-[10px] md:text-xs text-gray-400 truncate">{{ $payment->order->user->email }}</p>
                    </div>
                </div>

                <div class="space-y-3 pt-3 border-t border-gray-50">
                    <div class="flex items-center gap-2 text-[11px] md:text-sm text-gray-600">
                        <x-heroicon-o-phone class="w-4 h-4 text-gray-400 shrink-0" />
                        <span class="font-medium">{{ $payment->order->user->phone ?? '-' }}</span>
                    </div>
                    <div class="bg-[#F9FDF7] p-2.5 rounded-lg border border-green-50">
                        <p class="text-[9px] font-bold text-green-800 uppercase tracking-widest mb-1">Alamat Kirim</p>
                        <p class="text-[11px] md:text-xs text-gray-600 leading-relaxed line-clamp-3">
                            {{ $payment->order->shipping_address ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6">
                <h3 class="text-xs md:text-sm font-black text-[#0F4C20] uppercase tracking-widest mb-4">Bukti Transfer
                </h3>

                @if($payment->payment_proof)
                <div class="aspect-square md:aspect-[4/3] rounded-xl overflow-hidden bg-gray-50 border border-gray-100 mb-4 relative group cursor-pointer shadow-inner"
                    onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')">
                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Bukti Pembayaran"
                        class="w-full h-full object-contain transition transform group-hover:scale-105">
                    <div
                        class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                        <span
                            class="text-white text-[10px] font-bold bg-black/40 px-3 py-1 rounded-full backdrop-blur-sm">Lihat
                            Detail</span>
                    </div>
                </div>
                <button onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')"
                    class="w-full py-2.5 bg-green-50 text-green-700 rounded-lg font-bold text-xs hover:bg-green-100 transition border border-green-200 active:scale-95 shadow-sm">
                    🔍 Perbesar Gambar
                </button>
                @else
                <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <x-heroicon-o-camera class="w-10 h-10 text-gray-300 mx-auto mb-2" />
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada bukti
                    </p>
                </div>
                @endif

                @if($payment->notes)
                <div class="mt-4 p-3 bg-blue-50/50 rounded-lg border border-blue-100">
                    <p class="text-[10px] text-blue-800 font-bold uppercase tracking-widest mb-1">Catatan Pembeli</p>
                    <p class="text-xs text-blue-900 leading-relaxed italic">"{{ $payment->notes }}"</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<div id="proofModal"
    class="hidden fixed inset-0 z-[60] flex items-center justify-center p-3 md:p-6 bg-black/90 backdrop-blur-sm transition-all duration-300 opacity-0"
    onclick="closeProofModal()">
    <div class="max-w-4xl w-full max-h-[85vh] relative bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col animate-in zoom-in-95 duration-300"
        onclick="event.stopPropagation()">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="font-bold text-gray-900 text-sm md:text-base">Bukti Pembayaran Customer</h3>
            <button onclick="closeProofModal()"
                class="p-1.5 bg-gray-100 text-gray-500 rounded-full hover:bg-red-50 hover:text-red-600 transition">
                <x-heroicon-m-x-mark class="w-5 h-5" />
            </button>
        </div>
        <div class="flex-1 overflow-auto bg-gray-50 flex items-center justify-center p-2">
            <img id="proofImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-full object-contain">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function viewProof(url) {
    const modal = document.getElementById('proofModal');
    const img = document.getElementById('proofImage');
    img.src = url;
    modal.classList.remove('hidden');
    setTimeout(() => { modal.classList.add('opacity-100'); }, 10);
    document.body.style.overflow = 'hidden';
}

function closeProofModal() {
    const modal = document.getElementById('proofModal');
    modal.classList.remove('opacity-100');
    setTimeout(() => { modal.classList.add('hidden'); }, 300);
    document.body.style.overflow = 'auto';
}
</script>
@endpush