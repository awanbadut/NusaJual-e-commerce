@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' - Admin Nusa Belanja')

@section('content')
{{-- BREADCRUMB --}}
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-[#15803D]">Dashboard</a>
        <span>/</span>
        <a href="{{ route('admin.mitra.index') }}" class="hover:text-[#15803D]">Mitra</a>
        <span>/</span>
        <a href="{{ route('admin.mitra.show', $store->id) }}" class="hover:text-[#15803D]">{{ $store->store_name }}</a>
        <span>/</span>
        <span class="text-[#15803D] font-medium">Detail Pesanan</span>
    </div>
</div>

{{-- HEADER --}}
<div class="bg-gradient-to-br from-[#D1FAE5] to-[#DCFCE7] p-6 rounded-3xl mb-6 shadow-sm">
    <div class="flex flex-col md:flex-row justify-between items-start gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#111827] mb-2">
                Detail Pesanan #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
            </h1>
            <p class="text-sm text-[#78716C]">
                Mitra: <span class="font-semibold text-[#111827]">{{ $store->store_name }}</span>
            </p>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.mitra.show', $store->id) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition flex items-center gap-2">
                <x-heroicon-s-arrow-left class="w-4 h-4" />
                Kembali
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-[#15803D] text-white rounded-lg text-sm font-medium hover:bg-[#166534] transition flex items-center gap-2">
                <x-heroicon-s-printer class="w-4 h-4" />
                Print
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- LEFT COLUMN (2/3 WIDTH) --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- ORDER INFO --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-lg text-[#111827] mb-4">Informasi Pesanan</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-[#78716C] mb-1">Tanggal Pesanan</p>
                    <p class="text-sm font-semibold text-[#111827]">{{ $order->created_at->format('d F Y, H:i') }}</p>
                </div>
                
                <div>
                    <p class="text-xs text-[#78716C] mb-1">Status Pesanan</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                        {{ $order->status == 'completed' ? 'bg-[#15803D] text-white' : 'bg-gray-200 text-gray-700' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                
                @if($order->delivered_at)
                <div>
                    <p class="text-xs text-[#78716C] mb-1">Tanggal Selesai</p>
                    <p class="text-sm font-semibold text-[#111827]">{{ $order->delivered_at->format('d F Y, H:i') }}</p>
                </div>
                @endif
                
                <div>
                    <p class="text-xs text-[#78716C] mb-1">Status Pembayaran</p>
                    @if($order->payment)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                        {{ $order->payment->status == 'confirmed' ? 'bg-[#15803D] text-white' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($order->payment->status) }}
                    </span>
                    @else
                    <span class="text-sm text-gray-500">-</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- PRODUCTS --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-lg text-[#111827]">Produk Dipesan</h2>
            </div>
            
            <div class="p-6 space-y-4">
                @foreach($order->items as $item)
                <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
                        @if($item->product->images->first())
                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <x-heroicon-o-photo class="w-8 h-8" />
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="font-semibold text-[#111827] text-sm mb-1">{{ $item->product->name }}</h3>
                        <p class="text-xs text-[#78716C] mb-2">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-[#78716C]">Berat: {{ $item->product->weight }}gr</span>
                            <span class="font-bold text-[#15803D]">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- SHIPPING INFO --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-lg text-[#111827] mb-4">Informasi Pengiriman</h2>
            
            @php
                // Cek apakah ada data shipping di order
                $hasShippingInfo = 
                    $order->recipient_name || 
                    $order->shipping_address || 
                    $order->shipping_phone ||
                    $order->customer_name ||
                    $order->customer_phone ||
                    $order->customer_address;
            @endphp
            
            @if($hasShippingInfo)
            <div class="space-y-3">
                {{-- Penerima --}}
                @if($order->recipient_name || $order->customer_name || $order->user->name)
                <div>
                    <p class="text-xs text-[#78716C] mb-1">Penerima</p>
                    <p class="text-sm font-semibold text-[#111827]">
                        {{ $order->recipient_name ?? $order->customer_name ?? $order->user->name }}
                    </p>
                    @if($order->recipient_phone || $order->shipping_phone || $order->customer_phone || $order->user->phone)
                    <p class="text-sm text-[#111827]">
                        {{ $order->recipient_phone ?? $order->shipping_phone ?? $order->customer_phone ?? $order->user->phone }}
                    </p>
                    @endif
                </div>
                @endif
                
                {{-- Alamat --}}
                @if($order->shipping_address || $order->customer_address)
                <div>
                    <p class="text-xs text-[#78716C] mb-1">Alamat Lengkap</p>
                    <p class="text-sm text-[#111827]">
                        {{ $order->shipping_address ?? $order->customer_address }}
                    </p>
                    
                    {{-- Kota, Provinsi, Kode Pos --}}
                    @php
                        $locationParts = [];
                        if($order->shipping_district ?? $order->customer_district) $locationParts[] = $order->shipping_district ?? $order->customer_district;
                        if($order->shipping_city ?? $order->customer_city) $locationParts[] = $order->shipping_city ?? $order->customer_city;
                        if($order->shipping_province ?? $order->customer_province) $locationParts[] = $order->shipping_province ?? $order->customer_province;
                        if($order->shipping_postal_code ?? $order->customer_postal_code) $locationParts[] = $order->shipping_postal_code ?? $order->customer_postal_code;
                    @endphp
                    
                    @if(count($locationParts) > 0)
                    <p class="text-sm text-[#78716C]">
                        {{ implode(', ', $locationParts) }}
                    </p>
                    @endif
                </div>
                @endif
                
                {{-- Catatan --}}
                @if($order->shipping_notes ?? $order->notes)
                <div>
                    <p class="text-xs text-[#78716C] mb-1">Catatan</p>
                    <p class="text-sm text-[#111827] italic">"{{ $order->shipping_notes ?? $order->notes }}"</p>
                </div>
                @endif
                
                {{-- Kurir & Resi --}}
                @if($order->shipping_courier || $order->tracking_number)
                <div class="bg-gray-50 p-3 rounded-lg space-y-2">
                    @if($order->shipping_courier)
                    <div class="flex justify-between text-sm">
                        <span class="text-[#78716C]">Kurir</span>
                        <span class="font-semibold text-[#111827]">{{ strtoupper($order->shipping_courier) }}</span>
                    </div>
                    @endif
                    
                    @if($order->tracking_number)
                    <div class="flex justify-between text-sm">
                        <span class="text-[#78716C]">No. Resi</span>
                        <span class="font-mono text-[#111827]">{{ $order->tracking_number }}</span>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @else
            <div class="text-center py-8">
                <x-heroicon-o-map-pin class="w-12 h-12 text-gray-300 mx-auto mb-2" />
                <p class="text-sm text-gray-500">Informasi pengiriman tidak tersedia</p>
            </div>
            @endif
        </div>
        
    </div>

    {{-- RIGHT COLUMN (1/3 WIDTH) --}}
    <div class="space-y-6">
        
        {{-- CUSTOMER INFO --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-lg text-[#111827] mb-4">Informasi Pembeli</h2>
            
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-[#A78BFA] rounded-full flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($order->user->name, 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-[#111827]">{{ $order->user->name }}</p>
                    <p class="text-xs text-[#78716C]">{{ $order->user->email }}</p>
                </div>
            </div>
            
            @if($order->user->phone)
            <div class="flex items-center gap-2 text-sm text-[#111827] bg-gray-50 p-3 rounded-lg">
                <x-heroicon-s-phone class="w-4 h-4 text-[#6B7280]" />
                {{ $order->user->phone }}
            </div>
            @endif
        </div>

        {{-- PAYMENT SUMMARY --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-lg text-[#111827] mb-4">Rincian Pembayaran</h2>
            
            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-[#78716C]">Subtotal Produk</span>
                    <span class="font-semibold text-[#111827]">Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between text-sm">
                    <span class="text-[#78716C]">Ongkos Kirim</span>
                    <span class="font-semibold text-[#111827]">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                
                <hr class="border-dashed">
                
                <div class="flex justify-between text-base">
                    <span class="font-bold text-[#111827]">Total Pembayaran</span>
                    <span class="font-bold text-[#15803D] text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
            
            @if($order->payment)
            <div class="bg-[#F9FAFB] p-4 rounded-xl">
                <p class="text-xs text-[#78716C] mb-2">Metode Pembayaran</p>
                <p class="text-sm font-semibold text-[#111827]">{{ $order->payment->payment_method ?? 'Transfer Bank' }}</p>
                
                @if($order->payment->confirmed_at)
                <p class="text-xs text-[#78716C] mt-2">Dikonfirmasi pada: {{ $order->payment->confirmed_at->format('d M Y, H:i') }}</p>
                @endif
                
                @if($order->payment->payment_proof)
                <button onclick="viewProof('{{ asset('storage/' . $order->payment->payment_proof) }}')" 
                        class="mt-3 w-full px-3 py-2 bg-white border border-gray-300 text-sm text-[#111827] rounded-lg hover:bg-gray-50 transition flex items-center justify-center gap-2">
                    <x-heroicon-o-eye class="w-4 h-4" />
                    Lihat Bukti Transfer
                </button>
                @endif
            </div>
            @endif
        </div>

        {{-- TIMELINE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-lg text-[#111827] mb-4">Timeline Pesanan</h2>
            
            <div class="space-y-4">
                @if($order->delivered_at)
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 bg-[#15803D] rounded-full flex items-center justify-center">
                            <x-heroicon-s-check class="w-5 h-5 text-white" />
                        </div>
                        <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                    </div>
                    <div class="pb-4">
                        <p class="font-semibold text-[#111827] text-sm">Pesanan Selesai</p>
                        <p class="text-xs text-[#78716C]">{{ $order->delivered_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endif
                
                @if($order->payment && $order->payment->confirmed_at)
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 bg-[#15803D] rounded-full flex items-center justify-center">
                            <x-heroicon-s-check class="w-5 h-5 text-white" />
                        </div>
                        <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                    </div>
                    <div class="pb-4">
                        <p class="font-semibold text-[#111827] text-sm">Pembayaran Dikonfirmasi</p>
                        <p class="text-xs text-[#78716C]">{{ $order->payment->confirmed_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endif
                
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 bg-[#15803D] rounded-full flex items-center justify-center">
                            <x-heroicon-s-check class="w-5 h-5 text-white" />
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-[#111827] text-sm">Pesanan Dibuat</p>
                        <p class="text-xs text-[#78716C]">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

{{-- MODAL BUKTI TRANSFER --}}
<div id="proofModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-auto">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="font-bold text-lg">Bukti Transfer</h3>
            <button onclick="closeProofModal()" class="text-gray-400 hover:text-gray-600">
                <x-heroicon-s-x-mark class="w-6 h-6" />
            </button>
        </div>
        <div class="p-4">
            <img id="proofImage" src="" alt="Bukti Transfer" class="w-full h-auto rounded-lg">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewProof(url) {
    document.getElementById('proofImage').src = url;
    document.getElementById('proofModal').classList.remove('hidden');
}

function closeProofModal() {
    document.getElementById('proofModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('proofModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeProofModal();
});
</script>
@endpush
