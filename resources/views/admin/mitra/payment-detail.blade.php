@extends('layouts.admin')

@section('title', 'Detail Pembayaran - ' . $store->store_name)

@section('content')
<div class="px-2 sm:px-0 pb-6 md:pb-10">

    {{-- BREADCRUMB & HEADER --}}
    <div class="mb-4 md:mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-start sm:items-center gap-3 sm:gap-4">
            <a href="{{ route('admin.mitra.show', $store->id) }}"
                class="text-gray-600 hover:text-gray-900 p-1.5 md:p-2 bg-white rounded-lg border border-gray-100 shadow-sm shrink-0 transition">
                <svg class="w-5 h-5 md:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="min-w-0">
                <nav class="flex text-[10px] md:text-sm text-gray-600 mb-0.5 md:mb-1 flex-wrap font-medium">
                    <a href="{{ route('admin.mitra.index') }}" class="hover:text-green-800">Mitra</a>
                    <span class="mx-1.5 md:mx-2">›</span>
                    <a href="{{ route('admin.mitra.show', $store->id) }}"
                        class="hover:text-green-800 truncate max-w-[80px] sm:max-w-none">{{ $store->store_name }}</a>
                    <span class="mx-1.5 md:mx-2">›</span>
                    <span class="text-gray-900 truncate">Detail Bayar</span>
                </nav>
                <h1 class="text-xl md:text-3xl font-bold text-green-800 leading-tight">
                    Verifikasi Pembayaran
                    <span class="text-gray-900 font-mono text-base md:text-2xl block sm:inline mt-0.5 sm:mt-0">#PAY-{{
                        str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</span>
                </h1>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 items-start">

        {{-- MAIN CONTENT (2/3) --}}
        <div class="lg:col-span-2 space-y-4 md:space-y-6">

            {{-- STATUS CARD --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-1.5 md:p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-information-circle class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-sm md:text-base font-bold text-gray-900">Status Pembayaran</h3>
                </div>

                <div class="space-y-4 md:space-y-5">
                    <div>
                        <p class="text-[10px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                            Status Saat Ini</p>
                        <div>
                            @if($payment->status === 'confirmed')
                            <span
                                class="w-full flex items-center justify-center px-3 py-2 rounded-lg text-xs md:text-sm font-bold bg-green-50 text-green-700 border border-green-200">
                                <x-heroicon-s-check-circle class="w-4 h-4 mr-2" /> Terkonfirmasi
                            </span>
                            @elseif($payment->status === 'pending')
                            <span
                                class="w-full flex items-center justify-center px-3 py-2 rounded-lg text-xs md:text-sm font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                <x-heroicon-s-clock class="w-4 h-4 mr-2" /> Menunggu Konfirmasi
                            </span>
                            @else
                            <span
                                class="w-full flex items-center justify-center px-3 py-2 rounded-lg text-xs md:text-sm font-bold bg-red-50 text-red-700 border border-red-200">
                                <x-heroicon-s-x-circle class="w-4 h-4 mr-2" /> Ditolak
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <p class="text-[10px] md:text-xs text-gray-500">Tanggal Upload</p>
                            <p class="text-xs md:text-sm font-bold text-gray-900 mt-0.5">{{
                                $payment->created_at->format('d F Y') }}</p>
                            <p class="text-[10px] md:text-xs text-gray-500">{{ $payment->created_at->format('H:i') }}
                                WIB</p>
                        </div>

                        @if($payment->confirmed_at)
                        <div class="bg-green-50 p-3 rounded-xl border border-green-100">
                            <p class="text-[10px] md:text-xs text-green-600">Tanggal Konfirmasi</p>
                            <p class="text-xs md:text-sm font-bold text-green-900 mt-0.5">{{
                                $payment->confirmed_at->format('d F Y') }}</p>
                            <p class="text-[10px] md:text-xs text-green-600">{{ $payment->confirmed_at->format('H:i') }}
                                WIB</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ORDER DETAILS --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-1.5 md:p-2 bg-green-50 rounded-lg text-green-700">
                        <x-heroicon-o-shopping-bag class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-sm md:text-base font-bold text-gray-900">Detail Pesanan</h3>
                </div>

                <div class="grid grid-cols-2 gap-3 md:gap-4 mb-4 md:mb-6">
                    <div>
                        <p class="text-[10px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">ID
                            Pesanan</p>
                        <p
                            class="font-mono font-bold text-gray-900 text-xs md:text-sm bg-gray-50 inline-block px-1.5 md:px-2 py-0.5 md:py-1 rounded border border-gray-100">
                            #NB-{{ str_pad($payment->order->id, 4, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                    <div class="text-right md:text-left">
                        <p class="text-[10px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                            Status Pesanan</p>
                        <span class="inline-flex px-2 md:px-2.5 py-0.5 rounded-md text-[10px] md:text-xs font-bold border
                            @if($payment->order->status === 'completed') bg-green-50 text-green-700 border-green-200
                            @elseif($payment->order->status === 'processing') bg-blue-50 text-blue-700 border-blue-200
                            @elseif($payment->order->status === 'shipped') bg-purple-50 text-purple-700 border-purple-200
                            @else bg-gray-50 text-gray-700 border-gray-200
                            @endif">
                            {{ ucfirst($payment->order->status) }}
                        </span>
                    </div>
                </div>

                {{-- ORDER ITEMS --}}
                <h4 class="text-xs md:text-sm font-bold text-gray-900 mb-2 md:mb-3">Produk Dibeli</h4>
                <div
                    class="space-y-2 md:space-y-3 max-h-80 md:max-h-96 overflow-y-auto custom-scrollbar mb-4 md:mb-6 pr-1">
                    @foreach($orderItems as $item)
                    <div
                        class="flex gap-3 md:gap-4 p-2.5 md:p-3 bg-gray-50 rounded-xl border border-gray-100 hover:border-green-200 transition items-center">
                        @if($item['image'])
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}"
                            class="w-12 h-12 md:w-14 md:h-14 object-cover rounded-lg border border-gray-200 bg-white shrink-0">
                        @else
                        <div
                            class="w-12 h-12 md:w-14 md:h-14 bg-white rounded-lg border border-gray-200 flex items-center justify-center shrink-0">
                            <x-heroicon-o-photo class="w-5 h-5 md:w-6 md:h-6 text-gray-300" />
                        </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 text-xs md:text-sm truncate mb-0.5">{{
                                $item['product_name'] }}</h3>
                            <p class="text-[10px] md:text-xs text-gray-500">
                                {{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs md:text-sm font-bold text-gray-900">Rp {{ number_format($item['subtotal'],
                                0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- PRICE SUMMARY & TOTAL --}}
                <div class="space-y-3 md:space-y-4">
                    <div class="space-y-1.5 md:space-y-2 px-1 md:px-2">
                        <div class="flex justify-between text-xs md:text-sm">
                            <span class="text-gray-500">Subtotal Produk</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($orderItems->sum('subtotal'),
                                0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs md:text-sm">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-semibold text-gray-900">Rp {{
                                number_format($payment->order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div
                        class="bg-green-600 rounded-xl md:rounded-2xl p-3 md:p-4 text-white text-center md:text-right shadow-lg shadow-green-200">
                        <p class="text-green-100 text-[10px] md:text-sm font-medium mb-0.5 md:mb-1">Total Pembayaran</p>
                        <p class="text-xl md:text-3xl font-black tracking-tight">Rp {{
                            number_format($payment->order->total_amount, 0, ',', '.') }}</p>
                        <p
                            class="text-[9px] md:text-xs text-green-200 mt-1.5 md:mt-2 flex items-center justify-center md:justify-end gap-1">
                            <x-heroicon-s-banknotes class="w-3 h-3" /> Transfer Bank
                        </p>
                    </div>
                </div>
            </div>

            {{-- BUYER INFO --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-1.5 md:p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-user class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-sm md:text-base font-bold text-gray-900">Informasi Pembeli</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-4">
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-[10px] md:text-xs text-gray-500 mb-0.5">Nama Lengkap</p>
                        <p class="text-xs md:text-sm font-bold text-gray-900">{{ $payment->order->user->name }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-[10px] md:text-xs text-gray-500 mb-0.5">Email</p>
                        <p class="text-xs md:text-sm font-bold text-gray-900 truncate">{{ $payment->order->user->email
                            }}</p>
                    </div>
                    @if($payment->order->user->phone)
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 md:col-span-2">
                        <p class="text-[10px] md:text-xs text-gray-500 mb-0.5">Nomor Telepon</p>
                        <p class="text-xs md:text-sm font-bold text-gray-900">{{ $payment->order->user->phone }}</p>
                    </div>
                    @endif
                </div>

                @if($payment->order->shippingAddress)
                <div class="mt-4 md:mt-6">
                    <p
                        class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                        <x-heroicon-s-map-pin class="w-3 h-3" /> Alamat Pengiriman
                    </p>
                    <div class="bg-blue-50 p-3 md:p-4 rounded-xl border border-blue-100 text-blue-900">
                        <p class="text-xs md:text-sm font-bold mb-1">{{ $payment->order->shippingAddress->recipient_name
                            }}</p>
                        <p class="text-[11px] md:text-sm leading-relaxed text-blue-800">{{
                            $payment->order->shippingAddress->address }}</p>
                        <p class="text-[11px] md:text-sm text-blue-800 mt-1">{{ $payment->order->shippingAddress->city
                            }}, {{ $payment->order->shippingAddress->province }} {{
                            $payment->order->shippingAddress->postal_code }}</p>
                        @if($payment->order->shippingAddress->phone)
                        <div class="mt-2 flex items-center gap-1 text-[10px] md:text-xs font-semibold text-blue-700">
                            <x-heroicon-s-phone class="w-3 h-3" /> {{ $payment->order->shippingAddress->phone }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

        </div>

        {{-- SIDEBAR (1/3) --}}
        <div class="space-y-4 md:space-y-6 lg:sticky lg:top-6 h-full">

            {{-- STORE INFO --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-1.5 md:p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-building-storefront class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-sm md:text-base font-bold text-gray-900">Informasi Toko</h3>
                </div>

                <div
                    class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 md:p-5 border border-gray-200 relative overflow-hidden group">
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-green-100 rounded-full opacity-50"></div>
                    <div class="relative z-10 text-center">
                        <div
                            class="w-12 h-12 md:w-16 md:h-16 bg-white rounded-xl md:rounded-2xl flex items-center justify-center text-green-700 font-bold text-lg md:text-xl mx-auto mb-2 md:mb-3 shadow-sm border border-gray-200">
                            @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}"
                                class="w-full h-full object-cover rounded-xl md:rounded-2xl">
                            @else
                            {{ strtoupper(substr($store->store_name, 0, 2)) }}
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-900 text-sm md:text-lg">{{ $store->store_name }}</h3>
                        <p class="text-[10px] md:text-xs text-gray-500 mt-1 flex items-center justify-center gap-1">
                            <x-heroicon-s-map-pin class="w-3 h-3 text-gray-400 shrink-0" />
                            <span class="truncate">{{ Str::limit($store->address, 30) }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- BUKTI PEMBAYARAN --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-1.5 md:p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-photo class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-sm md:text-base font-bold text-gray-900">Bukti Pembayaran</h3>
                </div>

                @if($payment->payment_proof)
                <div class="relative group aspect-square md:aspect-auto">
                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Bukti Pembayaran"
                        class="w-full h-full object-cover md:object-contain rounded-xl border-2 border-gray-100 cursor-pointer shadow-sm"
                        onclick="viewProofModal('{{ asset('storage/' . $payment->payment_proof) }}')">

                    <button onclick="viewProofModal('{{ asset('storage/' . $payment->payment_proof) }}')"
                        class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all flex items-center justify-center rounded-xl">
                        <span
                            class="opacity-0 group-hover:opacity-100 transition bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-sm text-gray-900">
                            <x-heroicon-s-magnifying-glass-plus class="w-4 h-4" /> Perbesar
                        </span>
                    </button>
                </div>

                <a href="{{ asset('storage/' . $payment->payment_proof) }}" download
                    class="mt-3 md:mt-4 block w-full text-center bg-green-600 hover:bg-green-700 text-white px-3 py-2 md:py-2.5 rounded-lg text-xs md:text-sm font-semibold transition flex items-center justify-center gap-2">
                    <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5 md:w-4 md:h-4" /> Download Bukti
                </a>
                @else
                <div class="text-center py-6 md:py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <x-heroicon-o-photo class="w-8 h-8 md:w-10 md:h-10 text-gray-300 mx-auto mb-2" />
                    <p class="text-[10px] md:text-xs font-semibold text-gray-500 uppercase tracking-wide">Tidak Ada
                        Bukti</p>
                </div>
                @endif
            </div>

            {{-- TIMELINE --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4 md:mb-5 border-b border-gray-100 pb-2 md:pb-3">
                    <div class="p-1.5 md:p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-clock class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <h3 class="text-sm md:text-base font-bold text-gray-900">Timeline</h3>
                </div>

                <div class="space-y-0 relative ml-2">
                    @foreach($timeline as $index => $event)
                    <div class="flex gap-3 md:gap-4 relative pb-4 md:pb-5">
                        <div class="flex flex-col items-center shrink-0">
                            <div
                                class="w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center border-2 z-10 
                                {{ $event['status'] === 'completed' ? 'bg-green-50 border-green-500 text-green-600' : 'bg-white border-gray-200 text-gray-300' }}">
                                @if($event['icon'] === 'shopping-cart')
                                <x-heroicon-s-shopping-cart class="w-3 h-3 md:w-3.5 md:h-3.5" />
                                @elseif($event['icon'] === 'credit-card')
                                <x-heroicon-s-credit-card class="w-3 h-3 md:w-3.5 md:h-3.5" />
                                @elseif($event['icon'] === 'check-circle')
                                <x-heroicon-s-check-circle class="w-3 h-3 md:w-3.5 md:h-3.5" />
                                @else
                                <x-heroicon-s-check-badge class="w-3 h-3 md:w-3.5 md:h-3.5" />
                                @endif
                            </div>
                            @if($index < count($timeline) - 1) <div
                                class="w-0.5 h-full absolute top-6 md:top-8 {{ $event['status'] === 'completed' ? 'bg-green-200' : 'bg-gray-100' }}">
                        </div>
                        @endif
                    </div>
                    <div class="pt-0.5 md:pt-1 min-w-0">
                        <p class="text-xs md:text-sm font-bold text-gray-900 truncate">{{ $event['title'] }}</p>
                        <p class="text-[10px] md:text-xs text-gray-500 mt-0.5">{{ $event['date']->format('d M Y, H:i')
                            }} WIB</p>
                        @if(isset($event['admin']))
                        <p class="text-[9px] md:text-[10px] text-gray-400 mt-0.5 italic truncate">by {{ $event['admin']
                            }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
</div>

{{-- MODAL BUKTI TRANSFER --}}
<div id="proofModal"
    class="hidden fixed inset-0 bg-black/90 z-[60] flex items-center justify-center p-2 md:p-4 backdrop-blur-sm transition-opacity"
    onclick="closeProofModal()">
    <div class="relative max-w-3xl w-full mx-auto flex flex-col items-center">
        <button onclick="closeProofModal()"
            class="absolute -top-10 right-0 text-white hover:text-gray-300 flex items-center gap-2 transition bg-white/10 px-3 py-1.5 rounded-full">
            <span class="text-xs md:text-sm font-bold">Tutup</span>
            <x-heroicon-s-x-mark class="w-4 h-4 md:w-5 md:h-5" />
        </button>
        <img id="proofImage" src="" alt="Bukti Transfer"
            class="w-full h-auto max-h-[85vh] rounded-xl md:rounded-2xl shadow-2xl ring-1 ring-white/10 object-contain"
            onclick="event.stopPropagation()">
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

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeProofModal();
});
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 4px;
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f9fafb;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #d1d5db;
    }
</style>
@endpush