<x-layouts.profile title="Pesanan Saya - Nusa Belanja" headerTitle="Pesanan Saya"
    headerSubtitle="Semua riwayat transaksi Anda tersimpan rapi di sini">

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

    <div class="flex flex-col gap-6">
        @forelse($orders as $order)
        <div x-data="{ open: false }"
            class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden transition duration-300">

            <div
                class="bg-white border-b border-gray-100 p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Nomor
                        Pesanan</span>
                    <p class="text-lg font-bold text-[#2E3B27] font-mono tracking-tight">{{ $order->order_number }}</p>
                </div>

                @php
                $badgeStyle = match($order->status) {
                'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                'processing', 'shipped' => 'bg-blue-50 text-blue-700 border-blue-200',
                'completed' => 'bg-green-50 text-green-700 border-green-200',
                'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                default => 'bg-gray-50 text-gray-600 border-gray-200'
                };

                if($order->payment_status == 'pending' && $order->status != 'cancelled') {
                $displayText = 'Menunggu Pembayaran';
                $badgeStyle = 'bg-orange-50 text-orange-700 border-orange-200';
                } elseif ($order->payment_status == 'paid' && $order->status == 'pending') {
                $displayText = 'Menunggu Konfirmasi';
                $badgeStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                } else {
                $displayText = ucfirst($order->status);
                }
                @endphp

                <span class="px-4 py-1.5 rounded-full text-xs font-bold border {{ $badgeStyle }}">
                    {{ $displayText }}
                </span>
            </div>

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

            <div x-show="open" x-collapse class="bg-gray-50 border-t border-gray-200 p-6 space-y-6">

                @if($order->status != 'cancelled')
                <div class="mb-8 px-2">
                    <div class="relative">
                        <div class="absolute top-1/2 left-0 w-full h-1.5 bg-gray-200 -translate-y-1/2 rounded-full z-0">
                        </div>

                        @php
                        $width = match($order->status) {
                        'pending' => '0%',
                        'processing' => '33%',
                        'shipped' => '66%',
                        'completed' => '100%',
                        default => '0%'
                        };

                        // Logika Khusus: Jika sudah bayar tapi admin belum ubah status (masih pending)
                        // Kita anggap sudah masuk tahap 2 (Menunggu Konfirmasi) agar user tenang
                        if($order->payment_status == 'paid' && $order->status == 'pending') {
                        $width = '33%';
                        }
                        @endphp
                        <div class="absolute top-1/2 left-0 h-1.5 bg-[#8B4513] -translate-y-1/2 rounded-full transition-all duration-1000 ease-out z-0"
                            style="width: {{ $width }}"></div>

                        <div class="relative z-10 flex justify-between w-full">

                            <div class="flex flex-col items-center gap-2">
                                <div
                                    class="w-5 h-5 rounded-full border-4 border-white shadow-sm flex items-center justify-center 
                    {{ in_array($order->status, ['pending', 'processing', 'shipped', 'completed']) ? 'bg-[#8B4513]' : 'bg-gray-300' }}">
                                </div>
                                <span class="text-[10px] sm:text-xs font-bold text-[#8B4513] text-center w-20">Belum
                                    Bayar</span>
                            </div>

                            <div class="flex flex-col items-center gap-2">
                                <div
                                    class="w-5 h-5 rounded-full border-4 border-white shadow-sm flex items-center justify-center
                    {{ ($order->payment_status == 'paid' || in_array($order->status, ['processing', 'shipped', 'completed'])) ? 'bg-[#8B4513]' : 'bg-gray-300' }}">
                                </div>
                                <span
                                    class="text-[10px] sm:text-xs font-bold {{ ($order->payment_status == 'paid' || in_array($order->status, ['processing', 'shipped', 'completed'])) ? 'text-gray-700' : 'text-gray-400' }} text-center w-20">
                                    Diproses
                                </span>
                            </div>

                            <div class="flex flex-col items-center gap-2">
                                <div class="w-5 h-5 rounded-full border-4 border-white shadow-sm flex items-center justify-center
                    {{ in_array($order->status, ['shipped', 'completed']) ? 'bg-[#8B4513]' : 'bg-gray-300' }}">
                                </div>
                                <span
                                    class="text-[10px] sm:text-xs font-bold {{ in_array($order->status, ['shipped', 'completed']) ? 'text-gray-700' : 'text-gray-400' }} text-center w-20">
                                    Dikirim
                                </span>
                            </div>

                            <div class="flex flex-col items-center gap-2">
                                <div class="w-5 h-5 rounded-full border-4 border-white shadow-sm flex items-center justify-center
                    {{ $order->status == 'completed' ? 'bg-[#8B4513]' : 'bg-gray-300' }}">
                                </div>
                                <span
                                    class="text-[10px] sm:text-xs font-bold {{ $order->status == 'completed' ? 'text-gray-700' : 'text-gray-400' }} text-center w-20">
                                    Selesai
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
                @else
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-lg flex items-center gap-3 text-red-700">
                    <x-heroicon-s-x-circle class="w-6 h-6" />
                    <span class="font-bold text-sm">Pesanan ini telah dibatalkan.</span>
                </div>
                @endif

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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

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

                        @if($order->payment)
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <a href="{{ asset('storage/' . $order->payment->payment_proof) }}" target="_blank"
                                class="flex items-center justify-center gap-2 w-full py-2 rounded-md bg-[#0F4C20] text-white text-xs font-bold hover:bg-[#0b3a18] transition">
                                <x-heroicon-o-document-check class="w-4 h-4" />
                                Lihat Bukti Pembayaran
                            </a>
                        </div>
                        @endif
                    </div>

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
                                <span>Layanan</span>
                                <span class="font-bold text-gray-800">Rp 1.000</span>
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

            <div class="bg-gray-50 px-5 py-4 border-t border-gray-100 flex justify-end gap-3 items-center">

                <button @click="open = !open"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm font-bold hover:bg-gray-100 hover:border-gray-400 transition shadow-sm flex items-center gap-2">
                    <span x-text="open ? 'Tutup Detail' : 'Lihat Detail'"></span>
                    <x-heroicon-m-chevron-down class="w-4 h-4 transition-transform duration-300"
                        ::class="open ? 'rotate-180' : ''" />
                </button>

                @if($order->payment_status == 'pending' && $order->status != 'cancelled')
                <a href="{{ route('payment.show', $order->id) }}"
                    class="px-5 py-2.5 rounded-lg bg-[#0F4C20] text-white text-sm font-bold hover:bg-[#0b3a18] shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <x-heroicon-s-credit-card class="w-4 h-4" />
                    Bayar Sekarang
                </a>
                @endif
            </div>

        </div>
        @empty
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

    <div class="mt-8">
        {{ $orders->appends(request()->query())->links() }}
    </div>

</x-layouts.profile>