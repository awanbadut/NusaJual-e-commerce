@extends('layouts.admin')

@section('title', 'Detail Pembayaran - ' . $store->store_name)

@section('content')
<div>

    {{-- BREADCRUMB & HEADER --}}
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.mitra.show', $store->id) }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <nav class="flex text-sm text-gray-600 mb-1">
                <a href="{{ route('admin.mitra.index') }}" class="hover:text-green-800">Mitra</a>
                <span class="mx-2">›</span>
                <a href="{{ route('admin.mitra.show', $store->id) }}" class="hover:text-green-800">{{ $store->store_name
                    }}</a>
                <span class="mx-2">›</span>
                <span class="text-gray-900 font-medium">Detail Pembayaran</span>
            </nav>
            <h1 class="text-3xl font-bold text-green-800">
                Verifikasi Pembayaran
                <span class="text-gray-900 font-mono text-2xl">#PAY-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT)
                    }}</span>
            </h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- MAIN CONTENT (2/3) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- STATUS CARD --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-information-circle class="w-5 h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Status Pembayaran</h3>
                </div>

                <div class="space-y-5">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Status Saat Ini
                        </p>
                        <div>
                            @if($payment->status === 'confirmed')
                            <span
                                class="w-full flex items-center justify-center px-3 py-2 rounded-lg text-sm font-bold bg-green-50 text-green-700 border border-green-200">
                                <x-heroicon-s-check-circle class="w-4 h-4 mr-2" /> Terkonfirmasi
                            </span>
                            @elseif($payment->status === 'pending')
                            <span
                                class="w-full flex items-center justify-center px-3 py-2 rounded-lg text-sm font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                <x-heroicon-s-clock class="w-4 h-4 mr-2" /> Menunggu Konfirmasi
                            </span>
                            @else
                            <span
                                class="w-full flex items-center justify-center px-3 py-2 rounded-lg text-sm font-bold bg-red-50 text-red-700 border border-red-200">
                                <x-heroicon-s-x-circle class="w-4 h-4 mr-2" /> Ditolak
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <p class="text-xs text-gray-500">Tanggal Upload</p>
                            <p class="text-sm font-bold text-gray-900">{{ $payment->created_at->format('d F Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }} WIB</p>
                        </div>

                        @if($payment->confirmed_at)
                        <div class="bg-green-50 p-3 rounded-xl border border-green-100">
                            <p class="text-xs text-green-600">Tanggal Konfirmasi</p>
                            <p class="text-sm font-bold text-green-900">{{ $payment->confirmed_at->format('d F Y') }}
                            </p>
                            <p class="text-xs text-green-600">{{ $payment->confirmed_at->format('H:i') }} WIB</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ORDER DETAILS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-3">
                    <div class="p-2 bg-green-50 rounded-lg text-green-700">
                        <x-heroicon-o-shopping-bag class="w-5 h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Detail Pesanan</h3>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">ID Pesanan</p>
                        <p class="font-mono font-bold text-gray-900 text-sm bg-gray-50 inline-block px-2 py-1 rounded">
                            #NB-{{ str_pad($payment->order->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status Pesanan</p>
                        <span class="inline-flex px-2.5 py-0.5 rounded-md text-xs font-bold border
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
                <h4 class="text-sm font-bold text-gray-900 mb-3">Produk Dibeli</h4>
                <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar mb-6">
                    @foreach($orderItems as $item)
                    <div
                        class="flex gap-4 p-3 bg-gray-50 rounded-xl border border-gray-100 hover:border-green-200 transition">
                        @if($item['image'])
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}"
                            class="w-14 h-14 object-cover rounded-lg border border-gray-200 bg-white">
                        @else
                        <div
                            class="w-14 h-14 bg-white rounded-lg border border-gray-200 flex items-center justify-center">
                            <x-heroicon-o-photo class="w-6 h-6 text-gray-300" />
                        </div>
                        @endif
                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                            <h3 class="font-bold text-gray-900 text-sm truncate">{{ $item['product_name'] }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex flex-col justify-center text-right">
                            <p class="text-sm font-bold text-gray-900">Rp {{ number_format($item['subtotal'], 0, ',',
                                '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- PRICE SUMMARY & TOTAL --}}
                <div class="space-y-4">
                    <div class="space-y-2 px-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal Produk</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($orderItems->sum('subtotal'),
                                0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-semibold text-gray-900">Rp {{
                                number_format($payment->order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Total Block (Consistent with Withdrawal Page) --}}
                    <div
                        class="bg-green-600 rounded-2xl p-4 text-white text-center md:text-right shadow-lg shadow-green-200">
                        <p class="text-green-100 text-sm font-medium mb-1">Total Pembayaran</p>
                        <p class="text-3xl font-black tracking-tight">Rp {{ number_format($payment->order->total_amount,
                            0, ',', '.') }}</p>
                        <p class="text-xs text-green-200 mt-2 flex items-center justify-center md:justify-end gap-1">
                            <x-heroicon-s-banknotes class="w-3 h-3" /> Transfer Bank
                        </p>
                    </div>
                </div>
            </div>

            {{-- BUYER INFO --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-user class="w-5 h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Informasi Pembeli</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">Nama Lengkap</p>
                        <p class="text-sm font-bold text-gray-900">{{ $payment->order->user->name }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="text-sm font-bold text-gray-900">{{ $payment->order->user->email }}</p>
                    </div>
                    @if($payment->order->user->phone)
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">Nomor Telepon</p>
                        <p class="text-sm font-bold text-gray-900">{{ $payment->order->user->phone }}</p>
                    </div>
                    @endif
                </div>

                @if($payment->order->shippingAddress)
                <div class="mt-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                        <x-heroicon-s-map-pin class="w-3 h-3" /> Alamat Pengiriman
                    </p>
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-blue-900">
                        <p class="text-sm font-bold mb-1">{{ $payment->order->shippingAddress->recipient_name }}</p>
                        <p class="text-sm leading-relaxed text-blue-800">{{ $payment->order->shippingAddress->address }}
                        </p>
                        <p class="text-sm text-blue-800 mt-1">{{ $payment->order->shippingAddress->city }}, {{
                            $payment->order->shippingAddress->province }} {{
                            $payment->order->shippingAddress->postal_code }}</p>
                        @if($payment->order->shippingAddress->phone)
                        <div class="mt-2 flex items-center gap-1 text-xs font-semibold text-blue-700">
                            <x-heroicon-s-phone class="w-3 h-3" /> {{ $payment->order->shippingAddress->phone }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

        </div>

        {{-- SIDEBAR (1/3) --}}
        <div class="space-y-6 lg:sticky lg:top-6 h-full">

            {{-- STORE INFO (Styled like 'Rekening Tujuan' in Source) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-building-storefront class="w-5 h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Informasi Toko</h3>
                </div>

                <div
                    class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-5 border border-gray-200 relative overflow-hidden group hover:border-green-300 transition duration-300">
                    <div
                        class="absolute -top-6 -right-6 w-20 h-20 bg-green-100 rounded-full opacity-50 group-hover:scale-110 transition">
                    </div>

                    <div class="relative z-10 text-center">
                        <div
                            class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-green-700 font-bold text-xl mx-auto mb-3 shadow-sm border border-gray-200">
                            @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}"
                                class="w-full h-full object-cover rounded-2xl">
                            @else
                            {{ strtoupper(substr($store->store_name, 0, 2)) }}
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">{{ $store->store_name }}</h3>
                        <p class="text-xs text-gray-500 mt-1 flex items-center justify-center gap-1">
                            <x-heroicon-s-map-pin class="w-3 h-3 text-gray-400" />
                            {{ Str::limit($store->address, 30) }}
                        </p>

                        <a href="{{ route('admin.mitra.show', $store->id) }}"
                            class="mt-4 block w-full text-center bg-white border border-green-600 text-green-700 hover:bg-green-50 px-4 py-2 rounded-lg text-sm font-bold transition">
                            Lihat Toko
                        </a>
                    </div>
                </div>
            </div>

            {{-- BUKTI PEMBAYARAN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-photo class="w-5 h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Bukti Pembayaran</h3>
                </div>

                @if($payment->payment_proof)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Bukti Pembayaran"
                        class="w-full rounded-xl border-2 border-gray-100 cursor-pointer hover:border-green-500 transition shadow-sm"
                        onclick="viewProofModal('{{ asset('storage/' . $payment->payment_proof) }}')">

                    <button onclick="viewProofModal('{{ asset('storage/' . $payment->payment_proof) }}')"
                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-center justify-center rounded-xl">
                        <span
                            class="opacity-0 group-hover:opacity-100 transition bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 shadow-sm text-gray-900">
                            <x-heroicon-s-magnifying-glass-plus class="w-5 h-5" />
                            Perbesar
                        </span>
                    </button>
                </div>

                <a href="{{ asset('storage/' . $payment->payment_proof) }}" download
                    class="mt-4 block w-full text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center justify-center gap-2">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                    Download Bukti
                </a>
                @else
                <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <x-heroicon-o-photo class="w-10 h-10 text-gray-300 mx-auto mb-2" />
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tidak Ada Bukti</p>
                </div>
                @endif
            </div>

            {{-- TIMELINE --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-clock class="w-5 h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Timeline</h3>
                </div>

                <div class="space-y-4">
                    @foreach($timeline as $index => $event)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center border-2
                                {{ $event['status'] === 'completed' ? 'bg-green-50 border-green-500 text-green-600' : 'bg-white border-gray-200 text-gray-300' }}">
                                @if($event['icon'] === 'shopping-cart')
                                <x-heroicon-s-shopping-cart class="w-3.5 h-3.5" />
                                @elseif($event['icon'] === 'credit-card')
                                <x-heroicon-s-credit-card class="w-3.5 h-3.5" />
                                @elseif($event['icon'] === 'check-circle')
                                <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                                @else
                                <x-heroicon-s-check-badge class="w-3.5 h-3.5" />
                                @endif
                            </div>
                            @if($index < count($timeline) - 1) <div
                                class="w-0.5 flex-1 {{ $event['status'] === 'completed' ? 'bg-green-200' : 'bg-gray-100' }} my-1">
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 pb-4">
                        <p class="text-sm font-bold text-gray-900">{{ $event['title'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $event['date']->format('d M Y, H:i') }} WIB</p>
                        @if(isset($event['admin']))
                        <p class="text-xs text-gray-400 mt-0.5 italic">by {{ $event['admin'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
</div>

{{-- MODAL VIEW PROOF --}}
<div id="proofModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4 backdrop-blur-sm"
    onclick="closeProofModal()">
    <div class="relative max-w-4xl w-full">
        <button onclick="closeProofModal()"
            class="absolute -top-12 right-0 text-white hover:text-gray-300 flex items-center gap-2 transition">
            <span class="text-sm font-bold">Tutup</span>
            <div class="bg-white/10 rounded-full p-1">
                <x-heroicon-s-x-mark class="w-6 h-6" />
            </div>
        </button>
        <img id="proofImage" src="" alt="Bukti Pembayaran" class="w-full rounded-2xl shadow-2xl ring-1 ring-white/10">
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