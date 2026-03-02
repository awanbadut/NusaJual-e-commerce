@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' - Admin Nusa Belanja')

@section('content')
<div class="px-2 sm:px-0 pb-6 md:pb-10">

    {{-- BREADCRUMB & HEADER --}}
    <div class="mb-4 md:mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-start sm:items-center gap-3 sm:gap-4">
            <a href="{{ route('admin.mitra.show', $store->id) }}"
                class="text-gray-600 hover:text-gray-900 p-1.5 md:p-2 bg-white rounded-lg border border-gray-100 shadow-sm shrink-0 transition">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="min-w-0">
                <nav class="flex text-sm text-gray-600 mb-1 flex-wrap">
                    <a href="{{ route('admin.mitra.index') }}" class="hover:text-green-800">Mitra</a>
                    <span class="mx-1.5 md:mx-2">›</span>
                    <a href="{{ route('admin.mitra.show', $store->id) }}"
                        class="hover:text-green-800 truncate max-w-[100px] sm:max-w-none">{{ $store->store_name }}</a>
                    <span class="mx-1.5 md:mx-2">›</span>
                    <span class="text-gray-900 font-medium">Detail Pesanan</span>
                </nav>
                <h1 class="text-3xl font-bold text-green-800 leading-tight">
                    Detail Pesanan
                    <span class="text-gray-900 font-mono text-2xl block sm:inline mt-1 sm:mt-0">#ORD-{{
                        str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                </h1>
            </div>
        </div>

        <div class="flex flex-row gap-2 md:gap-3 w-full lg:w-auto">
            <a href="{{ route('admin.mitra.show', $store->id) }}"
                class="flex-1 lg:flex-none justify-center px-3 md:px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-50 transition flex items-center gap-2 shadow-sm active:scale-95">
                <x-heroicon-s-arrow-left class="w-4 h-4" />
                Kembali
            </a>
            <button onclick="window.print()"
                class="flex-1 lg:flex-none justify-center px-3 md:px-4 py-2 bg-green-700 text-white rounded-xl text-sm font-bold hover:bg-green-800 transition flex items-center gap-2 shadow-md shadow-green-200 active:scale-95">
                <x-heroicon-s-printer class="w-4 h-4" />
                Cetak Invoice
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 items-start">

        {{-- LEFT COLUMN (2/3 WIDTH) --}}
        <div class="lg:col-span-2 space-y-4 md:space-y-6">

            {{-- ORDER INFO --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-2 bg-green-50 rounded-lg text-green-700">
                        <x-heroicon-o-shopping-bag class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Informasi Pesanan</h3>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal Pesanan</p>
                        <p class="text-sm font-bold text-gray-900">{{ $order->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }} WIB</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status Pesanan</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold border
                            @if($order->status === 'completed') bg-green-50 text-green-700 border-green-200
                            @elseif($order->status === 'processing') bg-blue-50 text-blue-700 border-blue-200
                            @elseif($order->status === 'shipped') bg-purple-50 text-purple-700 border-purple-200
                            @else bg-gray-50 text-gray-700 border-gray-200
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    @if($order->delivered_at)
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal Selesai</p>
                        <p class="text-sm font-bold text-gray-900">{{ $order->delivered_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $order->delivered_at->format('H:i') }} WIB</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status Pembayaran
                        </p>
                        @if($order->payment)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold border
                            @if($order->payment->status == 'confirmed') bg-green-50 text-green-700 border-green-200
                            @else bg-yellow-50 text-yellow-700 border-yellow-200
                            @endif">
                            {{ ucfirst($order->payment->status) }}
                        </span>
                        @else
                        <span class="text-sm text-gray-500">-</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- PRODUCTS --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-archive-box class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Produk Dipesan</h3>
                </div>

                <div class="space-y-3 md:space-y-4">
                    @foreach($order->items as $item)
                    <div
                        class="flex gap-3 md:gap-4 p-3 md:p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-green-200 transition">
                        <div
                            class="w-14 h-14 md:w-16 md:h-16 bg-white rounded-lg flex-shrink-0 overflow-hidden border border-gray-200">
                            @if($item->product->images->first())
                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <x-heroicon-o-photo class="w-6 h-6 md:w-8 md:h-8" />
                            </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 text-sm mb-1 truncate">{{ $item->product->name }}</h3>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-0">
                                <div>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{
                                        number_format($item->price, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Berat: {{ $item->product->weight }}gr</p>
                                </div>
                                <p class="font-bold text-green-700">Rp {{ number_format($item->quantity * $item->price,
                                    0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- SHIPPING INFO --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-700">
                        <x-heroicon-o-truck class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Informasi Pengiriman</h3>
                </div>

                @php
                $hasShippingInfo = $order->recipient_name || $order->shipping_address || $order->shipping_phone ||
                $order->customer_name || $order->customer_phone || $order->customer_address;
                @endphp

                @if($hasShippingInfo)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    {{-- Penerima & Alamat --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Penerima</p>
                            <p class="text-sm font-bold text-gray-900">
                                {{ $order->recipient_name ?? $order->customer_name ?? $order->user->name }}
                            </p>
                            @if($order->recipient_phone || $order->shipping_phone || $order->customer_phone ||
                            $order->user->phone)
                            <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                <x-heroicon-s-phone class="w-3 h-3 shrink-0" />
                                {{ $order->recipient_phone ?? $order->shipping_phone ?? $order->customer_phone ??
                                $order->user->phone }}
                            </p>
                            @endif
                        </div>

                        @if($order->shipping_address || $order->customer_address)
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Alamat Lengkap
                            </p>
                            <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                                <p class="text-sm text-blue-900 leading-relaxed">
                                    {{ $order->shipping_address ?? $order->customer_address }}
                                </p>
                                @php
                                $locationParts = [];
                                if($order->shipping_district ?? $order->customer_district) $locationParts[] =
                                $order->shipping_district ?? $order->customer_district;
                                if($order->shipping_city ?? $order->customer_city) $locationParts[] =
                                $order->shipping_city ?? $order->customer_city;
                                if($order->shipping_province ?? $order->customer_province) $locationParts[] =
                                $order->shipping_province ?? $order->customer_province;
                                if($order->shipping_postal_code ?? $order->customer_postal_code) $locationParts[] =
                                $order->shipping_postal_code ?? $order->customer_postal_code;
                                @endphp
                                @if(count($locationParts) > 0)
                                <p class="text-xs text-blue-700 mt-1 font-semibold">
                                    {{ implode(', ', $locationParts) }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Kurir & Catatan --}}
                    <div class="space-y-4">
                        @if($order->shipping_courier || $order->tracking_number)
                        <div class="bg-gray-50 p-3 md:p-4 rounded-xl border border-gray-100">
                            @if($order->shipping_courier)
                            <div class="flex justify-between items-center text-sm mb-2">
                                <span class="text-gray-500">Kurir</span>
                                <span class="font-bold text-gray-900">{{ strtoupper($order->shipping_courier) }}</span>
                            </div>
                            @endif

                            @if($order->tracking_number)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">No. Resi</span>
                                <span
                                    class="font-mono font-bold text-gray-900 bg-white px-2 py-0.5 rounded border border-gray-200">
                                    {{ $order->tracking_number }}
                                </span>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($order->shipping_notes ?? $order->notes)
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Catatan</p>
                            <div
                                class="bg-yellow-50 p-3 rounded-lg border border-yellow-100 text-sm text-yellow-800 italic">
                                "{{ $order->shipping_notes ?? $order->notes }}"
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="text-center py-6 md:py-8">
                    <x-heroicon-o-map-pin class="w-8 h-8 md:w-10 md:h-10 text-gray-300 mx-auto mb-2" />
                    <p class="text-sm text-gray-500">Informasi pengiriman tidak tersedia</p>
                </div>
                @endif
            </div>

        </div>

        {{-- RIGHT COLUMN (1/3 WIDTH) --}}
        <div class="space-y-4 md:space-y-6 lg:sticky lg:top-6 h-full">

            {{-- CUSTOMER INFO --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-user class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Informasi Pembeli</h3>
                </div>

                <div class="flex items-center gap-3 md:gap-4 mb-4">
                    <div
                        class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-md ring-2 ring-purple-100 shrink-0">
                        {{ strtoupper(substr($order->user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-gray-900 truncate">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $order->user->email }}</p>
                    </div>
                </div>

                @if($order->user->phone)
                <div
                    class="flex items-center gap-2 text-sm text-gray-700 bg-gray-50 p-2.5 md:p-3 rounded-xl border border-gray-100">
                    <x-heroicon-s-phone class="w-4 h-4 text-gray-400 shrink-0" />
                    <span class="font-medium truncate">{{ $order->user->phone }}</span>
                </div>
                @endif
            </div>

            {{-- PAYMENT SUMMARY --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-banknotes class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Rincian Pembayaran</h3>
                </div>

                <div class="space-y-2 md:space-y-3 mb-4 md:mb-6">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Subtotal Produk</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->total_amount -
                            $order->shipping_cost, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Ongkos Kirim</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',',
                            '.') }}</span>
                    </div>
                </div>

                <div
                    class="bg-green-600 rounded-xl md:rounded-2xl p-4 text-white text-center shadow-lg shadow-green-200">
                    <p class="text-green-100 text-sm font-medium mb-1">Total Pembayaran</p>
                    <p class="text-3xl font-black tracking-tight">Rp {{ number_format($order->total_amount, 0, ',', '.')
                        }}</p>
                </div>

                @if($order->payment)
                <div class="mt-4 bg-gray-50 p-3 md:p-4 rounded-xl border border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Metode</p>
                        <span
                            class="text-xs font-bold text-gray-700 bg-white border border-gray-200 px-2 py-0.5 rounded">
                            {{ $order->payment->payment_method ?? 'Transfer Bank' }}
                        </span>
                    </div>

                    @if($order->payment->confirmed_at)
                    <p class="text-xs text-gray-500 mt-1">Dikonfirmasi: <span class="font-medium text-gray-900">{{
                            $order->payment->confirmed_at->format('d M Y') }}</span></p>
                    @endif

                    @if($order->payment->payment_proof)
                    <button onclick="viewProof('{{ asset('storage/' . $order->payment->payment_proof) }}')"
                        class="mt-3 w-full px-3 py-2 md:py-2.5 bg-white border border-gray-300 text-sm text-gray-700 font-bold rounded-lg hover:bg-gray-50 hover:border-gray-400 transition flex items-center justify-center gap-2 shadow-sm active:scale-95">
                        <x-heroicon-o-eye class="w-4 h-4" />
                        Lihat Bukti Transfer
                    </button>
                    @endif
                </div>
                @endif
            </div>

            {{-- TIMELINE --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-clock class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Timeline Pesanan</h3>
                </div>

                <div class="space-y-4">
                    {{-- Delivered --}}
                    @if($order->delivered_at)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-7 h-7 md:w-8 md:h-8 bg-green-50 border-2 border-green-500 rounded-full flex items-center justify-center text-green-600 z-10">
                                <x-heroicon-s-check-badge class="w-3.5 h-3.5 md:w-4 md:h-4" />
                            </div>
                            <div class="w-0.5 flex-1 bg-green-200 my-1"></div>
                        </div>
                        <div class="pb-2">
                            <p class="font-bold text-gray-900 text-sm">Pesanan Selesai</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $order->delivered_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Confirmed --}}
                    @if($order->payment && $order->payment->confirmed_at)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-7 h-7 md:w-8 md:h-8 bg-white border-2 border-green-500 rounded-full flex items-center justify-center text-green-600 z-10">
                                <x-heroicon-s-check class="w-3.5 h-3.5 md:w-4 md:h-4" />
                            </div>
                            <div class="w-0.5 flex-1 bg-green-200 my-1"></div>
                        </div>
                        <div class="pb-2">
                            <p class="font-bold text-gray-900 text-sm">Pembayaran Dikonfirmasi</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $order->payment->confirmed_at->format('d M Y,
                                H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Created --}}
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-7 h-7 md:w-8 md:h-8 bg-white border-2 border-gray-300 rounded-full flex items-center justify-center text-gray-400 z-10">
                                <x-heroicon-s-shopping-cart class="w-3.5 h-3.5 md:w-4 md:h-4" />
                            </div>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-sm">Pesanan Dibuat</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL BUKTI TRANSFER --}}
<div id="proofModal"
    class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-2 md:p-4 backdrop-blur-sm transition-opacity"
    onclick="closeProofModal()">
    <div class="relative max-w-3xl w-full mx-auto flex flex-col items-center">
        <button onclick="closeProofModal()"
            class="absolute -top-10 md:-top-12 right-0 text-white hover:text-gray-300 flex items-center gap-2 transition bg-black/50 px-3 py-1.5 rounded-full">
            <span class="text-sm font-bold">Tutup</span>
            <x-heroicon-s-x-mark class="w-5 h-5" />
        </button>
        <img id="proofImage" src="" alt="Bukti Transfer"
            class="max-w-full max-h-[80vh] rounded-xl md:rounded-2xl shadow-2xl ring-1 ring-white/10 object-contain"
            onclick="event.stopPropagation()">
    </div>
</div>
@endsection

@push('scripts')
<script>
    function viewProof(url) {
    document.getElementById('proofImage').src = url;
    document.getElementById('proofModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeProofModal() {
    document.getElementById('proofModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProofModal();
    }
});
</script>
@endpush