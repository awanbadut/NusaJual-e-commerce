<x-layouts.profile title="Pesanan Saya - Nusa Belanja" headerTitle="Pesanan Saya"
    headerSubtitle="Semua riwayat transaksi Anda tersimpan rapi di sini">

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-center">
            <x-heroicon-s-check-circle class="w-6 h-6 text-green-500 mr-3" />
            <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-center">
            <x-heroicon-s-x-circle class="w-6 h-6 text-red-500 mr-3" />
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Status Filter Tabs -->
    <div class="flex overflow-x-auto pb-4 mb-6 gap-3 no-scrollbar border-b border-gray-100">
        @php
        $statuses = [
        'all' => 'Semua',
        'pending' => 'Belum Dibayar',
        'processing' => 'Diproses',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan'
        ];
        $currentStatus = request('status', 'all');
        @endphp

        @foreach($statuses as $key => $label)
        <a href="{{ route('profile.orders', ['status' => $key]) }}" class="whitespace-nowrap px-5 py-2.5 rounded-full text-sm font-bold border transition duration-200
            {{ $currentStatus == $key 
                ? 'bg-[#0F4C20] text-white border-[#0F4C20] shadow-md' 
                : 'bg-white text-gray-500 border-gray-200 hover:border-[#0F4C20] hover:text-[#0F4C20]' 
            }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <!-- Orders List -->
    <div class="flex flex-col gap-6">
        @forelse($orders as $order)
        <div x-data="{ open: false }"
            class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden transition duration-300">

            <!-- Order Header -->
            <div
                class="bg-white border-b border-gray-100 p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Nomor
                        Pesanan</span>
                    <p class="text-lg font-bold text-[#2E3B27] font-mono tracking-tight">{{ $order->order_number }}</p>
                </div>

                @php
                // ✅ FIXED: Badge logic berdasarkan payment->status
                if ($order->status === 'cancelled') {
                $displayText = 'Dibatalkan';
                $badgeStyle = 'bg-red-50 text-red-700 border-red-200';
                } elseif ($order->status === 'completed') {
                $displayText = 'Selesai';
                $badgeStyle = 'bg-green-50 text-green-700 border-green-200';
                } elseif (!$order->payment || $order->payment->status === 'pending') {
                // Belum bayar
                $displayText = 'Menunggu Pembayaran';
                $badgeStyle = 'bg-orange-50 text-orange-700 border-orange-200';
                } elseif ($order->payment->status === 'paid') {
                // Sudah upload bukti, tunggu konfirmasi admin
                $displayText = 'Menunggu Konfirmasi';
                $badgeStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                } elseif ($order->payment->status === 'confirmed') {
                // Payment confirmed, cek order status
                if ($order->status === 'processing' || $order->status === 'packing') {
                $displayText = 'Diproses';
                $badgeStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                } elseif ($order->status === 'shipped') {
                $displayText = 'Dalam Pengiriman';
                $badgeStyle = 'bg-purple-50 text-purple-700 border-purple-200';
                } else {
                $displayText = 'Dikonfirmasi';
                $badgeStyle = 'bg-green-50 text-green-700 border-green-200';
                }
                } elseif ($order->payment->status === 'rejected') {
                $displayText = 'Pembayaran Ditolak';
                $badgeStyle = 'bg-red-50 text-red-700 border-red-200';
                } else {
                // Default fallback
                $displayText = ucfirst($order->status);
                $badgeStyle = 'bg-gray-50 text-gray-600 border-gray-200';
                }
                @endphp

                <span class="px-4 py-1.5 rounded-full text-xs font-bold border {{ $badgeStyle }}">
                    {{ $displayText }}
                </span>
            </div>

            <!-- Order Summary (Collapsed View) -->
            <div class="p-5 flex flex-col sm:flex-row gap-5 items-center sm:items-start" x-show="!open">
                @php $firstItem = $order->items->first(); @endphp
                <div class="w-20 h-20 bg-gray-50 rounded-lg overflow-hidden shrink-0 border border-gray-200">
                    @if($firstItem && $firstItem->product->primaryImage)
                    <img src="{{ asset('storage/'.$firstItem->product->primaryImage->image_path) }}"
                        class="w-full h-full object-cover">
                    @else
                    <img src="https://placehold.co/100x100?text=Produk" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1 w-full text-center sm:text-left">
                    <p class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wide">
                        {{ $firstItem->product->store->store_name ?? 'Nusa Belanja' }}
                    </p>
                    <h4 class="font-bold text-[#2E3B27] text-base line-clamp-1 mb-1">{{ $firstItem->product->name }}
                    </h4>
                    <div class="text-sm text-gray-500">
                        {{ $firstItem->quantity }} barang x Rp {{ number_format($firstItem->price, 0, ',', '.') }}
                    </div>
                    @if($order->items->count() > 1)
                    <p class="text-xs text-[#0F4C20] font-bold mt-2 bg-green-50 inline-block px-2 py-1 rounded">
                        + {{ $order->items->count() - 1 }} produk lainnya
                    </p>
                    @endif
                </div>
                <div
                    class="text-right flex flex-col items-center sm:items-end justify-center h-full w-full sm:w-auto border-t sm:border-t-0 border-gray-100 pt-4 sm:pt-0 mt-2 sm:mt-0">
                    <span class="text-xs text-gray-500 font-medium mb-1">Total Belanja</span>
                    <span class="text-lg font-bold text-[#0F4C20]">Rp {{ number_format($order->total_amount, 0, ',',
                        '.') }}</span>
                </div>
            </div>

            <!-- Order Details (Expanded View) -->
            <div x-show="open" x-collapse class="bg-gray-50 border-t border-gray-200 p-6 space-y-6">

                <!-- Progress Tracker -->
                @if($order->status != 'cancelled')
                <div class="mb-8 px-2">
                    <div class="relative">
                        <div class="absolute top-1/2 left-0 w-full h-1.5 bg-gray-200 -translate-y-1/2 rounded-full z-0">
                        </div>

                        @php
                        // ✅ FIXED: Width berdasarkan payment & order status
                        $progressWidth = '0%';

                        if ($order->status === 'completed') {
                        $progressWidth = '100%';
                        } elseif ($order->status === 'shipped') {
                        $progressWidth = '66%';
                        } elseif ($order->payment && $order->payment->status === 'confirmed') {
                        $progressWidth = '33%';
                        } elseif ($order->payment && $order->payment->status === 'paid') {
                        $progressWidth = '16%'; // Sudah bayar tapi belum confirmed
                        } else {
                        $progressWidth = '0%'; // Belum bayar
                        }
                        @endphp

                        <div class="absolute top-1/2 left-0 h-1.5 bg-[#8B4513] -translate-y-1/2 rounded-full transition-all duration-1000 ease-out z-0"
                            style="width: {{ $progressWidth }}"></div>

                        <div class="relative z-10 flex justify-between w-full">
                            <!-- Step 1: Belum Bayar -->
                            <div class="flex flex-col items-center gap-2">
                                <div
                                    class="w-5 h-5 rounded-full border-4 border-white shadow-sm flex items-center justify-center 
                                    {{ $order->payment && $order->payment->status !== 'pending' ? 'bg-[#8B4513]' : 'bg-gray-300' }}">
                                </div>
                                <span
                                    class="text-[10px] sm:text-xs font-bold text-center w-20 
                                    {{ $order->payment && $order->payment->status !== 'pending' ? 'text-[#8B4513]' : 'text-gray-400' }}">
                                    Belum Bayar
                                </span>
                            </div>

                            <!-- Step 2: Diproses -->
                            <div class="flex flex-col items-center gap-2">
                                <div
                                    class="w-5 h-5 rounded-full border-4 border-white shadow-sm flex items-center justify-center
                                    {{ $order->payment && $order->payment->status === 'confirmed' ? 'bg-[#8B4513]' : 'bg-gray-300' }}">
                                </div>
                                <span
                                    class="text-[10px] sm:text-xs font-bold text-center w-20
                                    {{ $order->payment && $order->payment->status === 'confirmed' ? 'text-gray-700' : 'text-gray-400' }}">
                                    Diproses
                                </span>
                            </div>

                            <!-- Step 3: Dikirim -->
                            <div class="flex flex-col items-center gap-2">
                                <div
                                    class="w-5 h-5 rounded-full border-4 border-white shadow-sm flex items-center justify-center
                                    {{ in_array($order->status, ['shipped', 'completed']) ? 'bg-[#8B4513]' : 'bg-gray-300' }}">
                                </div>
                                <span
                                    class="text-[10px] sm:text-xs font-bold text-center w-20
                                    {{ in_array($order->status, ['shipped', 'completed']) ? 'text-gray-700' : 'text-gray-400' }}">
                                    Dikirim
                                </span>
                            </div>

                            <!-- Step 4: Selesai -->
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-5 h-5 rounded-full border-4 border-white shadow-sm flex items-center justify-center
                                    {{ $order->status == 'completed' ? 'bg-[#8B4513]' : 'bg-gray-300' }}">
                                </div>
                                <span class="text-[10px] sm:text-xs font-bold text-center w-20
                                    {{ $order->status == 'completed' ? 'text-gray-700' : 'text-gray-400' }}">
                                    Selesai
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Cancelled State -->
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-lg">
                    <div class="flex items-center gap-3 text-red-700 mb-3">
                        <x-heroicon-s-x-circle class="w-6 h-6 shrink-0" />
                        <div>
                            <span class="font-bold text-sm">Pesanan ini telah dibatalkan.</span>
                            @if($order->cancellation_reason)
                            <p class="text-xs mt-1">Alasan: {{ $order->cancellation_reason }}</p>
                            @endif
                        </div>
                    </div>

                    @if($order->refund && $order->refund_status !== 'none')
                    <div class="mt-3 pt-3 border-t border-red-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-red-800 mb-1">Status Pengembalian Dana</p>
                                @php
                                $refundBadge = match($order->refund_status) {
                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                'approved' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'processed' => 'bg-green-100 text-green-800 border-green-300',
                                'rejected' => 'bg-red-100 text-red-800 border-red-300',
                                default => 'bg-gray-100 text-gray-800 border-gray-300'
                                };
                                $refundText = match($order->refund_status) {
                                'pending' => 'Menunggu Proses',
                                'approved' => 'Disetujui Admin',
                                'processed' => 'Sudah Ditransfer',
                                'rejected' => 'Ditolak',
                                default => ucfirst($order->refund_status)
                                };
                                @endphp
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-[10px] font-bold border {{ $refundBadge }}">
                                    {{ $refundText }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-red-600 mb-1">Dana Dikembalikan</p>
                                <p class="text-sm font-bold text-red-800">Rp {{ number_format($order->refund_amount, 0,
                                    ',', '.') }}</p>
                            </div>
                        </div>

                        @if($order->refund_status === 'processed' && $order->refund && $order->refund->refund_proof)
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $order->refund->refund_proof) }}" target="_blank"
                                class="flex items-center justify-center gap-2 w-full py-2 rounded-md bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                        clip-rule="evenodd" />
                                </svg>
                                Lihat Bukti Transfer
                            </a>
                        </div>
                        @endif

                        @if($order->refund_status === 'pending')
                        <p class="text-xs text-red-600 mt-3 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Dana akan ditransfer dalam 1-3 hari kerja setelah verifikasi admin.
                        </p>
                        @endif

                        @if($order->refund_status === 'rejected' && $order->refund && $order->refund->rejection_reason)
                        <div class="mt-3 p-2 bg-red-100 rounded text-xs text-red-800">
                            <p class="font-semibold mb-1">Alasan Penolakan:</p>
                            <p>{{ $order->refund->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                @endif

                <!-- Product List -->
                <div class="space-y-4">
                    <h4 class="font-bold text-[#2E3B27] text-sm flex items-center gap-2">
                        <x-heroicon-s-shopping-bag class="w-4 h-4 text-[#8B4513]" /> Rincian Produk
                    </h4>
                    @foreach($order->items as $item)
                    <div class="flex gap-4 p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="w-16 h-16 bg-gray-50 rounded-md overflow-hidden shrink-0 border border-gray-200">
                            @if($item->product->primaryImage)
                            <img src="{{ asset('storage/'.$item->product->primaryImage->image_path) }}"
                                class="w-full h-full object-cover">
                            @else
                            <img src="https://placehold.co/100x100?text=Produk" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $item->product->category->name
                                ?? 'Umum' }}</p>
                            <h5 class="font-bold text-[#2E3B27] text-sm line-clamp-1">{{ $item->product->name }}</h5>
                            <p class="text-xs text-[#8B4513] font-bold mt-1">{{ $item->quantity }} x {{
                                $item->product->unit }}</p>
                        </div>
                        <div class="text-right flex flex-col justify-center">
                            <span class="text-sm font-bold text-gray-600">Rp {{ number_format($item->total, 0, ',', '.')
                                }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Shipping & Payment Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ✅ SHIPPING ADDRESS WITH COURIER & TRACKING -->
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm h-full flex flex-col">
                        <h4
                            class="font-bold text-[#2E3B27] text-sm flex items-center gap-2 border-b border-gray-100 pb-2 mb-3">
                            <x-heroicon-s-map-pin class="w-4 h-4 text-[#8B4513]" /> Alamat Pengiriman
                        </h4>
                        <div class="text-xs text-gray-600 space-y-1 flex-1">
                            <p class="font-bold text-gray-800 text-sm">{{ $order->recipient_name }} <span
                                    class="font-normal text-gray-500">({{ $order->recipient_phone }})</span></p>
                            <p class="leading-relaxed">{{ $order->shipping_address }}</p>
                            @if($order->notes)
                            <p class="italic text-gray-400 mt-2">"{{ $order->notes }}"</p>
                            @endif
                        </div>

                        {{-- ✅ NEW: Shipping Info (Kurir & Resi) --}}
                        @if($order->courier || $order->tracking_number)
                        <div class="mt-4 pt-3 border-t border-gray-100 space-y-3">
                            @if($order->courier)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#8B4513] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                    <path
                                        d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                                </svg>
                                <div>
                                    <p class="text-[10px] text-gray-500 font-medium">Kurir Pengiriman</p>
                                    <p class="text-xs font-bold text-gray-800">{{ strtoupper($order->courier) }}</p>
                                </div>
                            </div>
                            @endif

                            @if($order->tracking_number)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#8B4513] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-[10px] text-gray-500 font-medium">Nomor Resi</p>
                                    <p class="text-xs font-bold text-gray-800 font-mono">{{ $order->tracking_number }}
                                    </p>
                                </div>
                                {{-- Copy Button --}}
                                <button
                                    onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}'); alert('✅ Resi disalin: {{ $order->tracking_number }}')"
                                    class="px-2 py-1 bg-green-50 text-green-700 rounded text-[10px] font-bold hover:bg-green-100 transition">
                                    Salin
                                </button>
                            </div>
                            @endif

                            {{-- ✅ Track Shipment Link (JNE, J&T, dll) --}}
                            @php
                            $trackingUrl = match(strtoupper($order->courier ?? '')) {
                            'JNE' => 'https://www.jne.co.id/id/tracking/trace',
                            'J&T', 'J&T EXPRESS', 'JNT' => 'https://www.jet.co.id/track',
                            'SICEPAT' => 'https://www.sicepat.com/checkAwb',
                            'TIKI' => 'https://www.tiki.id/id/track',
                            'ANTERAJA' => 'https://anteraja.id/tracking',
                            'NINJA', 'NINJA EXPRESS' => 'https://www.ninjaxpress.co/id-id/tracking',
                            'POS INDONESIA', 'POS' => 'https://www.posindonesia.co.id/id/tracking',
                            'LION PARCEL' => 'https://www.lionparcel.com/tracking',
                            default => null
                            };
                            @endphp

                            @if($trackingUrl)
                            <a href="{{ $trackingUrl }}" target="_blank"
                                class="flex items-center justify-center gap-2 w-full py-2 rounded-md bg-[#0F4C20] text-white text-xs font-bold hover:bg-[#0b3a18] transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                                    <path
                                        d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                                </svg>
                                Lacak Paket di {{ strtoupper($order->courier) }}
                            </a>
                            @endif
                        </div>
                        @endif

                        @if($order->payment && $order->payment->payment_proof)
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <a href="{{ asset('storage/' . $order->payment->payment_proof) }}" target="_blank"
                                class="flex items-center justify-center gap-2 w-full py-2 rounded-md bg-[#0F4C20] text-white text-xs font-bold hover:bg-[#0b3a18] transition">
                                <x-heroicon-o-document-check class="w-4 h-4" />
                                Lihat Bukti Pembayaran
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- Payment Summary -->
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm h-full flex flex-col">
                        <h4
                            class="font-bold text-[#2E3B27] text-sm flex items-center gap-2 border-b border-gray-100 pb-2 mb-3">
                            <x-heroicon-s-receipt-percent class="w-4 h-4 text-[#8B4513]" /> Rincian Pembayaran
                        </h4>
                        <div class="space-y-2 text-xs text-gray-600 flex-1">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="font-bold text-gray-800">Rp {{ number_format($order->sub_total, 0, ',',
                                    '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pengiriman</span>
                                <span class="font-bold text-gray-800">Rp {{ number_format($order->shipping_cost, 0, ',',
                                    '.') }}</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-100 mt-3 pt-3 flex justify-between items-center">
                            <span class="font-bold text-gray-800 text-sm">Total</span>
                            <span class="text-base font-bold text-[#0F4C20]">Rp {{ number_format($order->total_amount,
                                0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div
                class="bg-gray-50 px-5 py-4 border-t border-gray-100 flex justify-between gap-3 items-center flex-wrap">
                <!-- Toggle Detail -->
                <button @click="open = !open"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm font-bold hover:bg-gray-100 hover:border-gray-400 transition shadow-sm flex items-center gap-2">
                    <span x-text="open ? 'Tutup Detail' : 'Lihat Detail'"></span>
                    <x-heroicon-m-chevron-down class="w-4 h-4 transition-transform duration-300"
                        ::class="open ? 'rotate-180' : ''" />
                </button>

                <!-- Right Actions -->
                <div class="flex gap-3 items-center">
                    <!-- Cancel Order Button -->
                    @php
                    $canCancel = $order->canBeCancelled();
                    $remainingMinutes = $order->getCancelTimeRemaining();
                    $needsRefund = $order->payment && $order->payment->status === 'paid';
                    @endphp

                    @if($canCancel)
                    <div x-data="{ showModal: false }">
                        <button @click="showModal = true"
                            class="px-5 py-2.5 rounded-lg bg-red-600 text-white text-sm font-bold hover:bg-red-700 shadow-md hover:shadow-lg transition flex items-center gap-2">
                            <x-heroicon-s-x-circle class="w-4 h-4" />
                            Batalkan
                            @if($remainingMinutes && $remainingMinutes > 0)
                            <span class="text-xs opacity-80">({{ floor($remainingMinutes / 60) }}j {{ $remainingMinutes
                                % 60 }}m)</span>
                            @endif
                        </button>

                        <!-- Cancel Modal WITH REFUND FORM -->
                        <div x-show="showModal" x-cloak @click.away="showModal = false"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity p-4">
                            <div @click.stop
                                class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                                        <x-heroicon-s-exclamation-triangle class="w-6 h-6 text-red-600" />
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Batalkan Pesanan?</h3>
                                        <p class="text-sm text-gray-500">{{ $order->order_number }}</p>
                                    </div>
                                </div>

                                <form action="{{ route('profile.orders.cancel', $order->id) }}" method="POST">
                                    @csrf

                                    <div class="mb-4">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Alasan Pembatalan (Opsional)
                                        </label>
                                        <textarea name="reason" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                            placeholder="Contoh: Salah memesan, ingin ganti produk, dll..."></textarea>
                                    </div>

                                    @if($needsRefund)
                                    <!-- Refund Warning -->
                                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex gap-2 mb-2">
                                            <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-semibold text-yellow-800 mb-1">Pengembalian Dana
                                                </p>
                                                <p class="text-xs text-yellow-700">Pesanan sudah dibayar. Dana akan
                                                    dikembalikan setelah dikurangi biaya admin.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Refund Calculation -->
                                    @php
                                    $orderAmount = $order->total_amount;
                                    $adminFee = $orderAmount * 0.05;
                                    $refundAmount = $orderAmount - $adminFee;
                                    @endphp

                                    <div class="mb-4 p-4 bg-gray-50 rounded-lg space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Total Pesanan</span>
                                            <span class="font-semibold text-gray-900">Rp {{ number_format($orderAmount,
                                                0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Biaya Admin (5%)</span>
                                            <span class="font-semibold text-red-600">- Rp {{ number_format($adminFee, 0,
                                                ',', '.') }}</span>
                                        </div>
                                        <hr class="border-gray-300">
                                        <div class="flex justify-between">
                                            <span class="font-bold text-gray-900">Dana Dikembalikan</span>
                                            <span class="font-bold text-green-600 text-base">Rp {{
                                                number_format($refundAmount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <!-- Bank Account Form -->
                                    <div class="mb-4 space-y-3">
                                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                                <path fill-rule="evenodd"
                                                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Rekening Pengembalian Dana
                                        </h4>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Bank <span
                                                    class="text-red-500">*</span></label>
                                            <select name="bank_name" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                                                <option value="">Pilih Bank</option>
                                                <option value="BCA">BCA</option>
                                                <option value="Mandiri">Mandiri</option>
                                                <option value="BNI">BNI</option>
                                                <option value="BRI">BRI</option>
                                                <option value="CIMB Niaga">CIMB Niaga</option>
                                                <option value="Danamon">Danamon</option>
                                                <option value="Permata">Permata</option>
                                                <option value="BTN">BTN</option>
                                                <option value="BSI">Bank Syariah Indonesia</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Nomor Rekening
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" name="account_number" required
                                                placeholder="Contoh: 1234567890"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pemilik
                                                Rekening <span class="text-red-500">*</span></label>
                                            <input type="text" name="account_holder" required
                                                placeholder="Sesuai dengan buku tabungan"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                                        </div>
                                    </div>

                                    <p class="text-xs text-gray-500 mb-4">
                                        💡 Dana akan ditransfer dalam <strong>1-3 hari kerja</strong> setelah verifikasi
                                        admin.
                                    </p>
                                    @endif

                                    <div class="flex gap-3">
                                        <button type="button" @click="showModal = false"
                                            class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                                            Ya, Batalkan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Complete Order Button -->
                    @if($order->canBeCompleted())
                    <div x-data="{ showModal: false }">
                        <button @click="showModal = true"
                            class="px-5 py-2.5 rounded-lg bg-green-600 text-white text-sm font-bold hover:bg-green-700 shadow-md hover:shadow-lg transition flex items-center gap-2">
                            <x-heroicon-s-check-circle class="w-4 h-4" />
                            Pesanan Selesai
                        </button>

                        <!-- Complete Modal -->
                        <div x-show="showModal" x-cloak @click.away="showModal = false"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity p-4">
                            <div @click.stop class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                        <x-heroicon-s-check-circle class="w-6 h-6 text-green-600" />
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Konfirmasi Pesanan Selesai?</h3>
                                        <p class="text-sm text-gray-500">{{ $order->order_number }}</p>
                                    </div>
                                </div>

                                <p class="text-sm text-gray-600 mb-6">
                                    Pastikan Anda sudah menerima pesanan dalam kondisi baik. Setelah konfirmasi, dana
                                    akan diteruskan ke penjual.
                                </p>

                                <form action="{{ route('profile.orders.complete', $order->id) }}" method="POST">
                                    @csrf

                                    <div class="flex gap-3">
                                        <button type="button" @click="showModal = false"
                                            class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                                            Ya, Selesai
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Pay Now Button -->
                    @if((!$order->payment || $order->payment->status === 'pending') && $order->status != 'cancelled')
                    <a href="{{ route('payment.show', $order->id) }}"
                        class="px-5 py-2.5 rounded-lg bg-[#0F4C20] text-white text-sm font-bold hover:bg-[#0b3a18] shadow-md hover:shadow-lg transition flex items-center gap-2">
                        <x-heroicon-s-credit-card class="w-4 h-4" />
                        Bayar Sekarang
                    </a>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <!-- Empty State -->
        <div
            class="flex flex-col items-center justify-center py-16 bg-white rounded-xl border border-gray-200 border-dashed">
            <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-6">
                <x-heroicon-o-shopping-bag class="w-10 h-10 text-[#0F4C20]" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada pesanan</h3>
            <p class="text-gray-500 text-sm mb-8 text-center max-w-sm">
                Keranjang belanja Anda masih kosong. Yuk, mulai jelajahi produk lokal terbaik kami!
            </p>
            <a href="{{ route('katalog') }}"
                class="px-8 py-3 bg-[#0F4C20] text-white rounded-lg font-bold text-sm hover:bg-[#0b3a18] transition shadow-lg hover:shadow-xl flex items-center gap-2">
                Mulai Belanja
                <x-heroicon-m-arrow-right class="w-4 h-4" />
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $orders->appends(request()->query())->links() }}
    </div>

</x-layouts.profile>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>