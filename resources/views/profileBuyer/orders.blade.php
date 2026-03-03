<x-layouts.profile title="Pesanan Saya - Nusa Belanja" headerTitle="Pesanan Saya"
    headerSubtitle="Semua riwayat transaksi Anda tersimpan rapi di sini">

    {{-- Alert Success --}}
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

    {{-- Status Filter Tabs --}}
    <div
        class="flex overflow-x-auto pb-2 md:pb-4 mb-4 md:mb-6 gap-2 md:gap-3 no-scrollbar border-b border-gray-100 w-full">
        @php
        $statuses = [
        'all' => 'Semua',
        'pending' => 'Belum Dibayar',
        'processing' => 'Diproses',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        ];
        $currentStatus = request('status', 'all');
        @endphp

        @foreach($statuses as $key => $label)
        <a href="{{ route('profile.orders', ['status' => $key]) }}" class="whitespace-nowrap px-4 py-2 md:px-5 md:py-2.5 rounded-full text-xs md:text-sm font-bold border transition duration-200 flex-shrink-0
               {{ $currentStatus == $key
                   ? 'bg-[#0F4C20] text-white border-[#0F4C20] shadow-md'
                   : 'bg-white text-gray-500 border-gray-200 hover:border-[#0F4C20] hover:text-[#0F4C20]' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Orders List --}}
    <div class="flex flex-col gap-4 sm:gap-6">
        @forelse($orders as $order)
        <div x-data="{ open: false }"
            class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden transition duration-300">

            {{-- Order Header --}}
            <div
                class="bg-white border-b border-gray-100 p-4 sm:p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
                <div class="w-full sm:w-auto flex justify-between sm:block items-center">
                    <div>
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Nomor
                            Pesanan</span>
                        <p class="text-lg font-bold text-[#2E3B27] font-mono tracking-tight">{{ $order->order_number }}
                        </p>
                    </div>
                    {{-- Status dipindah ke sebelah kanan nomor order untuk mobile agar hemat tempat, atau tetap dibawah
                    sesuai flow --}}
                </div>

                @php
                if ($order->status === 'cancelled') {
                $displayText = 'Dibatalkan';
                $badgeStyle = 'bg-red-50 text-red-700 border-red-200';
                } elseif ($order->status === 'completed') {
                $displayText = 'Selesai';
                $badgeStyle = 'bg-green-50 text-green-700 border-green-200';
                } elseif (!$order->payment || $order->payment->status === 'pending') {
                $displayText = 'Menunggu Pembayaran';
                $badgeStyle = 'bg-orange-50 text-orange-700 border-orange-200';
                } elseif ($order->payment->status === 'paid') {
                $displayText = 'Menunggu Konfirmasi';
                $badgeStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                } elseif ($order->payment->status === 'confirmed') {
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
                $displayText = ucfirst($order->status);
                $badgeStyle = 'bg-gray-50 text-gray-600 border-gray-200';
                }
                @endphp

                <span class="px-4 py-1.5 rounded-full text-xs font-bold border {{ $badgeStyle }} w-fit">
                    {{ $displayText }}
                </span>
            </div>

            {{-- Order Summary (Collapsed View) - OPTIMIZED FOR MOBILE --}}
            {{-- Menggunakan Grid untuk Mobile: Gambar Kiri, Teks Kanan, Harga Bawah --}}
            <div class="p-4 sm:p-5 grid grid-cols-[auto_1fr] sm:flex sm:flex-row gap-4 sm:gap-5 items-start"
                x-show="!open">
                @php $firstItem = $order->items->first(); @endphp

                {{-- Gambar Produk --}}
                <div
                    class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-50 rounded-lg overflow-hidden shrink-0 border border-gray-200">
                    @if($firstItem && $firstItem->product && $firstItem->product->primaryImage)
                    <img src="{{ asset('storage/'.$firstItem->product->primaryImage->image_path) }}"
                        class="w-full h-full object-cover">
                    @else
                    <img src="https://placehold.co/100x100?text=Produk" class="w-full h-full object-cover">
                    @endif
                </div>

                {{-- Detail Produk --}}
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wide truncate">
                        {{ ($firstItem && $firstItem->product) ? ($firstItem->product->store->store_name ?? 'Nusa
                        Belanja') : 'Nusa Belanja' }}
                    </p>
                    <h4 class="font-bold text-[#2E3B27] text-sm sm:text-base line-clamp-1 mb-1">
                        {{ ($firstItem && $firstItem->product) ? $firstItem->product->name : 'Produk tidak tersedia' }}
                    </h4>
                    @if($firstItem)
                    <div class="text-xs sm:text-sm text-gray-500">
                        {{ $firstItem->quantity }} barang x Rp {{ number_format($firstItem->price, 0, ',', '.') }}
                    </div>
                    @endif
                    @if($order->items->count() > 1)
                    <p class="text-xs text-[#0F4C20] font-bold mt-2 bg-green-50 inline-block px-2 py-1 rounded">
                        + {{ $order->items->count() - 1 }} produk lainnya
                    </p>
                    @endif
                </div>

                {{-- Total Harga - Mobile: Full width di bawah, Desktop: Di kanan --}}
                <div
                    class="col-span-2 sm:col-span-1 sm:w-auto text-left sm:text-right border-t border-gray-100 sm:border-0 pt-3 sm:pt-0 mt-0 sm:mt-0 flex justify-between sm:block items-center w-full">
                    <span class="text-xs text-gray-500 font-medium sm:mb-1 block">Total Belanja</span>
                    <span class="text-base sm:text-lg font-bold text-[#0F4C20]">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Order Details (Expanded View) --}}
            <div x-show="open" x-collapse class="bg-gray-50 border-t border-gray-200 p-4 sm:p-6 space-y-6">

                {{-- Progress Tracker --}}
                @if($order->status != 'cancelled')
                <div
                    class="mb-8 sm:mb-12 px-3 sm:px-4 py-5 sm:py-6 bg-white rounded-xl border border-gray-100 shadow-sm overflow-x-hidden">
                    <div class="relative">
                        <div class="absolute top-2 left-0 w-full h-1 bg-gray-100 rounded-full z-0"></div>

                        @php
                        $progressWidth = '0%';
                        $steps = ['pending' => false, 'confirmed' => false, 'packing' => false, 'shipped' => false,
                        'completed' => false];

                        if ($order->status === 'completed') {
                        $progressWidth = '100%';
                        $steps = array_fill_keys(array_keys($steps), true);
                        } elseif ($order->status === 'shipped') {
                        $progressWidth = '75%';
                        $steps['pending'] = $steps['confirmed'] = $steps['packing'] = $steps['shipped'] = true;
                        } elseif ($order->status === 'packing') {
                        $progressWidth = '50%';
                        $steps['pending'] = $steps['confirmed'] = $steps['packing'] = true;
                        } elseif ($order->payment && $order->payment->status === 'confirmed') {
                        $progressWidth = '25%';
                        $steps['pending'] = $steps['confirmed'] = true;
                        } elseif ($order->payment) {
                        $progressWidth = '10%';
                        $steps['pending'] = true;
                        }

                        $activeColor = "bg-[#0F4C20]";
                        $textColor = "text-[#0F4C20]";
                        @endphp

                        <div class="absolute top-2 left-0 h-1 {{ $activeColor }} rounded-full transition-all duration-1000 ease-in-out z-0 shadow-[0_0_8px_rgba(15,76,32,0.3)]"
                            style="width: {{ $progressWidth }}"></div>

                        <div class="relative z-10 flex justify-between w-full">

                            {{-- Step: Dibuat --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div
                                    class="w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 sm:border-4 border-white shadow-md transition-colors duration-500 {{ $steps['pending'] ? $activeColor : 'bg-gray-200' }}">
                                    @if($steps['pending'] && !$steps['confirmed'])
                                    <div class="w-full h-full rounded-full animate-ping {{ $activeColor }} opacity-20">
                                    </div>
                                    @endif
                                </div>
                                <div class="text-center mt-2 sm:mt-3">
                                    <p
                                        class="text-[9px] sm:text-xs font-black uppercase tracking-tight {{ $steps['pending'] ? $textColor : 'text-gray-400' }}">
                                        Dibuat</p>
                                    <p
                                        class="text-[8px] sm:text-[9px] font-medium text-gray-400 mt-0.5 bg-gray-50 px-1 py-0.5 rounded-md inline-block">
                                        {{ $order->created_at->format('d/m H:i') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Step: Dikonfirmasi --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div
                                    class="w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 sm:border-4 border-white shadow-md transition-colors duration-500 {{ $steps['confirmed'] ? $activeColor : 'bg-gray-200' }}">
                                    @if($steps['confirmed'] && !$steps['packing'])
                                    <div class="w-full h-full rounded-full animate-ping {{ $activeColor }} opacity-20">
                                    </div>
                                    @endif
                                </div>
                                <div class="text-center mt-2 sm:mt-3">
                                    <p
                                        class="text-[9px] sm:text-xs font-black uppercase tracking-tight {{ $steps['confirmed'] ? $textColor : 'text-gray-400' }}">
                                        Konfirm</p>
                                    @if($order->payment && $order->payment->confirmed_at)
                                    <p
                                        class="text-[8px] sm:text-[9px] font-medium text-gray-400 mt-0.5 bg-gray-50 px-1 py-0.5 rounded-md inline-block">
                                        {{ $order->payment->confirmed_at->format('d/m H:i') }}
                                    </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Step: Dikemas --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div
                                    class="w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 sm:border-4 border-white shadow-md transition-colors duration-500 {{ $steps['packing'] ? $activeColor : 'bg-gray-200' }}">
                                    @if($steps['packing'] && !$steps['shipped'])
                                    <div class="w-full h-full rounded-full animate-ping {{ $activeColor }} opacity-20">
                                    </div>
                                    @endif
                                </div>
                                <div class="text-center mt-2 sm:mt-3">
                                    <p
                                        class="text-[9px] sm:text-xs font-black uppercase tracking-tight {{ $steps['packing'] ? $textColor : 'text-gray-400' }}">
                                        Kemas</p>
                                </div>
                            </div>

                            {{-- Step: Dikirim --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div
                                    class="w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 sm:border-4 border-white shadow-md transition-colors duration-500 {{ $steps['shipped'] ? $activeColor : 'bg-gray-200' }}">
                                    @if($steps['shipped'] && !$steps['completed'])
                                    <div class="w-full h-full rounded-full animate-ping {{ $activeColor }} opacity-20">
                                    </div>
                                    @endif
                                </div>
                                <div class="text-center mt-2 sm:mt-3">
                                    <p
                                        class="text-[9px] sm:text-xs font-black uppercase tracking-tight {{ $steps['shipped'] ? $textColor : 'text-gray-400' }}">
                                        Kirim</p>
                                    @if($order->shipped_at)
                                    <p
                                        class="text-[8px] sm:text-[9px] font-medium text-gray-400 mt-0.5 bg-gray-50 px-1 py-0.5 rounded-md inline-block">
                                        {{ $order->shipped_at->format('d/m H:i') }}
                                    </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Step: Selesai --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div
                                    class="w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 sm:border-4 border-white shadow-md transition-colors duration-500 {{ $steps['completed'] ? $activeColor : 'bg-gray-200' }}">
                                </div>
                                <div class="text-center mt-2 sm:mt-3">
                                    <p
                                        class="text-[9px] sm:text-xs font-black uppercase tracking-tight {{ $steps['completed'] ? $textColor : 'text-gray-400' }}">
                                        Selesai</p>
                                    @if($order->delivered_at)
                                    <p
                                        class="text-[8px] sm:text-[9px] font-medium text-gray-400 mt-0.5 bg-gray-50 px-1 py-0.5 rounded-md inline-block">
                                        {{ $order->delivered_at->format('d/m H:i') }}
                                    </p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @else
                {{-- Cancelled State --}}
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-lg">
                    <div class="flex items-start gap-3 text-red-700 mb-3">
                        <x-heroicon-s-x-circle class="w-5 h-5 shrink-0 mt-0.5" />
                        <div>
                            <span class="font-bold text-sm block">Pesanan ini telah dibatalkan.</span>
                            @if($order->cancellation_reason)
                            <p class="text-xs mt-1 leading-relaxed">Alasan: {{ $order->cancellation_reason }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- ✅ BARU: Form Isi Rekening jika Seller yang Cancel --}}
                    @if($order->refund && $order->refund->status === 'needs_bank_info')
                    <div x-data="{ showBankForm: false }"
                        class="mt-4 p-3 sm:p-4 bg-yellow-50 border border-yellow-300 rounded-xl">
                        <div class="flex items-start gap-3 mb-3">
                            <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-yellow-800">Harap isi rekening untuk pengembalian dana
                                </p>
                                <p class="text-xs text-yellow-700 mt-1 leading-relaxed">
                                    Pesanan dibatalkan oleh penjual. Masukkan rekening bank Anda agar admin dapat
                                    mentransfer <strong>Rp {{ number_format($order->refund->refund_amount, 0, ',', '.')
                                        }}</strong> (sudah dipotong biaya admin 5%).
                                </p>
                            </div>
                        </div>

                        <button @click="showBankForm = !showBankForm"
                            class="w-full py-2 rounded-lg bg-yellow-500 text-white text-xs sm:text-sm font-bold hover:bg-yellow-600 transition">
                            <span x-text="showBankForm ? '▲ Tutup Form' : '▼ Isi Rekening Sekarang'"></span>
                        </button>

                        <form x-show="showBankForm" x-cloak
                            action="{{ route('profile.orders.refund.bank', $order->id) }}" method="POST"
                            class="mt-4 space-y-3">
                            @csrf

                            {{-- Rincian Refund --}}
                            <div class="p-3 bg-white rounded-lg border border-yellow-200 space-y-1.5 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Total Pesanan</span>
                                    <span class="font-semibold">Rp {{ number_format($order->refund->order_amount, 0,
                                        ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Biaya Admin (5%)</span>
                                    <span class="font-semibold text-red-600">- Rp {{
                                        number_format($order->refund->admin_fee, 0, ',', '.') }}</span>
                                </div>
                                <hr class="border-yellow-200">
                                <div class="flex justify-between">
                                    <span class="font-bold text-gray-800">Dana Dikembalikan</span>
                                    <span class="font-bold text-green-700">Rp {{
                                        number_format($order->refund->refund_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Bank <span
                                        class="text-red-500">*</span></label>
                                <select name="bank_name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-yellow-500">
                                    <option value="">-- Pilih Bank --</option>
                                    <option value="BCA">BCA</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="BNI">BNI</option>
                                    <option value="BRI">BRI</option>
                                    <option value="CIMB Niaga">CIMB Niaga</option>
                                    <option value="Danamon">Danamon</option>
                                    <option value="Permata">Permata</option>
                                    <option value="BTN">BTN</option>
                                    <option value="BSI">Bank Syariah Indonesia</option>
                                    <option value="Jenius">Jenius (BTPN)</option>
                                    <option value="SeaBank">SeaBank</option>
                                    <option value="GoPay">GoPay</option>
                                    <option value="OVO">OVO</option>
                                    <option value="Dana">Dana</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Rekening <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="account_number" required placeholder="Contoh: 1234567890"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-xs sm:text-sm font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Pemilik Rekening
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="account_holder" required value="{{ auth()->user()->name }}"
                                    placeholder="Sesuai dengan buku tabungan"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-yellow-500">
                            </div>

                            <button type="submit"
                                class="w-full py-2.5 rounded-lg bg-[#0F4C20] text-white text-sm font-bold hover:bg-[#0b3a18] transition">
                                💾 Simpan & Kirim ke Admin
                            </button>
                        </form>
                    </div>

                    <p class="text-xs text-gray-500 mb-4 mt-3">
                        💡 Dana akan ditransfer dalam <strong>1-3 hari kerja</strong> setelah verifikasi admin.
                    </p>
                    @endif
                </div>
                @endif

                {{-- Status Refund --}}
                @if($order->refund && $order->refund_status !== 'none' && $order->refund->status !== 'needs_bank_info')
                <div class="p-3 sm:p-4 bg-white border border-gray-200 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-700 mb-1">Status Refund</p>
                            @php
                            $refundBadge = match($order->refund_status) {
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                            'approved' => 'bg-blue-100 text-blue-800 border-blue-300',
                            'processed' => 'bg-green-100 text-green-800 border-green-300',
                            'rejected' => 'bg-red-100 text-red-800 border-red-300',
                            default => 'bg-gray-100 text-gray-800 border-gray-300',
                            };
                            $refundText = match($order->refund_status) {
                            'pending' => 'Menunggu Proses',
                            'approved' => 'Disetujui Admin',
                            'processed' => 'Sudah Ditransfer',
                            'rejected' => 'Ditolak',
                            default => ucfirst($order->refund_status),
                            };
                            @endphp
                            <span
                                class="inline-block px-3 py-1 rounded-full text-[10px] font-bold border {{ $refundBadge }}">
                                {{ $refundText }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-500 mb-1">Total Refund</p>
                            <p class="text-sm font-bold text-red-700">Rp {{ number_format($order->refund_amount, 0, ',',
                                '.') }}</p>
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
                </div>
                @endif

                {{-- Product List --}}
                <div class="space-y-4">
                    <h4 class="font-bold text-[#2E3B27] text-sm flex items-center gap-2">
                        <x-heroicon-s-shopping-bag class="w-4 h-4 text-[#8B4513]" /> Rincian Produk
                    </h4>

                    @foreach($order->items as $item)
                    {{-- Layout Produk di Mobile --}}
                    <div class="flex gap-4 p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="w-16 h-16 bg-gray-50 rounded-md overflow-hidden shrink-0 border border-gray-200">
                            @if(!$item->product)
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            @elseif($item->product->primaryImage)
                            <img src="{{ asset('storage/'.$item->product->primaryImage->image_path) }}"
                                class="w-full h-full object-cover">
                            @else
                            <img src="https://placehold.co/100x100?text=Produk" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            @if($item->product)
                            <p class="text-[10px] font-bold text-gray-400 uppercase">
                                {{ $item->product->category->name ?? 'Umum' }}
                            </p>
                            <h5 class="font-bold text-[#2E3B27] text-sm line-clamp-1">{{ $item->product->name }}</h5>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-[#8B4513] font-bold">
                                    {{ $item->quantity }} x {{ $item->product->unit ?? 'pcs' }}
                                </p>
                                <span class="text-sm font-bold text-gray-600">
                                    Rp {{ number_format($item->total, 0, ',', '.') }}
                                </span>
                            </div>
                            @else
                            <p class="text-xs text-gray-400 italic">Produk tidak tersedia</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $item->quantity }}x</p>
                            <span class="text-sm font-bold text-gray-400 block mt-1">Rp {{ number_format($item->total,
                                0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Shipping & Payment Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    {{-- Alamat --}}
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <h4
                            class="font-bold text-[#2E3B27] text-sm flex items-center gap-2 border-b border-gray-100 pb-2 mb-3">
                            <x-heroicon-s-map-pin class="w-4 h-4 text-[#8B4513]" /> Alamat Pengiriman
                        </h4>
                        <div class="text-xs text-gray-600 space-y-1">
                            <p class="font-bold text-gray-800 text-sm">{{ $order->recipient_name }}
                                <span class="font-normal text-gray-500">({{ $order->recipient_phone }})</span>
                            </p>
                            <p class="leading-relaxed">{{ $order->shipping_address }}</p>
                            @if($order->notes)
                            <p class="italic text-gray-400 mt-2 bg-gray-50 p-2 rounded">"{{ $order->notes }}"</p>
                            @endif
                        </div>

                        @if($order->courier || $order->tracking_number)
                        <div class="mt-4 pt-3 border-t border-gray-100 space-y-3">
                            @if($order->courier)
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Kurir</span>
                                <span class="font-bold text-gray-900">{{ strtoupper($order->courier) }}</span>
                            </div>
                            @endif

                            @if($order->tracking_number)
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <p class="text-[10px] text-gray-500 font-medium">Nomor Resi</p>
                                    <p class="text-xs font-bold text-gray-800 font-mono">{{ $order->tracking_number }}
                                    </p>
                                </div>
                                <button
                                    onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}'); alert('✅ Resi disalin: {{ $order->tracking_number }}')"
                                    class="px-2 py-1 bg-green-50 text-green-700 rounded text-[10px] font-bold border border-green-200">
                                    Salin
                                </button>
                            </div>
                            @endif

                            {{-- Tracking Button Code kept same --}}
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
                            default => null,
                            };
                            @endphp

                            @if($trackingUrl)
                            <a href="{{ $trackingUrl }}" target="_blank"
                                class="flex items-center justify-center gap-2 w-full py-2 rounded-md bg-[#0F4C20] text-white text-xs font-bold hover:bg-[#0b3a18] transition mt-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                                    <path
                                        d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                                </svg>
                                Lacak Paket
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- Rincian Pembayaran --}}
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <h4
                            class="font-bold text-[#2E3B27] text-sm flex items-center gap-2 border-b border-gray-100 pb-2 mb-3">
                            <x-heroicon-s-receipt-percent class="w-4 h-4 text-[#8B4513]" /> Rincian Pembayaran
                        </h4>
                        <div class="space-y-2 text-xs text-gray-600">
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

                        @if($order->payment && $order->payment->payment_proof)
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <a href="{{ asset('storage/' . $order->payment->payment_proof) }}" target="_blank"
                                class="flex items-center justify-center gap-2 w-full py-2 rounded-md bg-[#0F4C20] text-white text-xs font-bold hover:bg-[#0b3a18] transition">
                                <x-heroicon-o-document-check class="w-4 h-4" />
                                Bukti Pembayaran
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Action Buttons --}}
            <div
                class="bg-gray-50 px-4 py-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-between gap-3 items-center">
                <button @click="open = !open"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm font-bold hover:bg-gray-100 transition shadow-sm flex items-center justify-center gap-2">
                    <span x-text="open ? 'Tutup Detail' : 'Lihat Detail'"></span>
                    <x-heroicon-m-chevron-down class="w-4 h-4 transition-transform duration-300"
                        ::class="open ? 'rotate-180' : ''" />
                </button>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    @if($order->status === 'cancelled' && $order->refund && $order->refund->status ===
                    'needs_bank_info')
                    <button
                        @click="open = true; $nextTick(() => { document.querySelector('[x-data]').scrollIntoView({behavior: 'smooth'}) })"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-lg bg-yellow-500 text-white text-sm font-bold hover:bg-yellow-600 shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                            <path fill-rule="evenodd"
                                d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                clip-rule="evenodd" />
                        </svg>
                        Isi Rekening Refund
                    </button>
                    @endif

                    @php
                    $canCancel = $order->canBeCancelled();
                    $remainingMinutes = $order->getCancelTimeRemaining();
                    $needsRefund = $order->payment && $order->payment->status === 'paid';
                    @endphp

                    @if($canCancel)
                    <div x-data="{ showModal: false }" class="w-full sm:w-auto">
                        <button @click="showModal = true"
                            class="w-full px-5 py-2.5 rounded-lg bg-red-600 text-white text-sm font-bold hover:bg-red-700 shadow-md transition flex items-center justify-center gap-2">
                            <x-heroicon-s-x-circle class="w-4 h-4" />
                            Batalkan
                            @if($remainingMinutes && $remainingMinutes > 0)
                            <span class="text-xs opacity-80">({{ floor($remainingMinutes / 60) }}j {{ $remainingMinutes
                                % 60 }}m)</span>
                            @endif
                        </button>

                        {{-- Cancel Modal (Responsive) --}}
                        <div x-show="showModal" x-cloak @click.away="showModal = false"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity p-4">
                            <div @click.stop
                                class="bg-white rounded-xl shadow-2xl max-w-md w-full p-5 sm:p-6 max-h-[90vh] overflow-y-auto">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                                        <x-heroicon-s-exclamation-triangle class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" />
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Batalkan Pesanan?</h3>
                                        <p class="text-sm text-gray-500">{{ $order->order_number }}</p>
                                    </div>
                                </div>

                                <form action="{{ route('profile.orders.cancel', $order->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Pembatalan
                                            (Opsional)</label>
                                        <textarea name="reason" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                            placeholder="Contoh: Salah memesan, ingin ganti produk, dll..."></textarea>
                                    </div>

                                    @if($needsRefund)
                                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
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

                                    @php
                                    $orderAmount = $order->total_amount;
                                    $adminFee = $orderAmount * 0.05;
                                    $refundAmount = $orderAmount - $adminFee;
                                    @endphp

                                    <div class="mb-4 p-3 bg-gray-50 rounded-lg space-y-2 text-xs sm:text-sm">
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
                                            <span class="font-bold text-green-600 text-sm sm:text-base">Rp {{
                                                number_format($refundAmount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    {{-- Bank Account Form in Modal --}}
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
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-yellow-500">
                                                <option value="">-- Pilih Bank --</option>
                                                <option value="BCA">BCA</option>
                                                <option value="Mandiri">Mandiri</option>
                                                <option value="BNI">BNI</option>
                                                <option value="BRI">BRI</option>
                                                <option value="CIMB Niaga">CIMB Niaga</option>
                                                <option value="Danamon">Danamon</option>
                                                <option value="Permata">Permata</option>
                                                <option value="BTN">BTN</option>
                                                <option value="BSI">Bank Syariah Indonesia</option>
                                                <option value="Jenius">Jenius (BTPN)</option>
                                                <option value="SeaBank">SeaBank</option>
                                                <option value="GoPay">GoPay</option>
                                                <option value="OVO">OVO</option>
                                                <option value="Dana">Dana</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Nomor Rekening
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" name="account_number" required
                                                placeholder="Contoh: 1234567890"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-xs sm:text-sm font-mono">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pemilik
                                                Rekening <span class="text-red-500">*</span></label>
                                            <input type="text" name="account_holder" required
                                                value="{{ auth()->user()->name }}"
                                                placeholder="Sesuai dengan buku tabungan"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-xs sm:text-sm">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-4">💡 Dana akan ditransfer dalam <strong>1-3 hari
                                            kerja</strong> setelah verifikasi admin.</p>
                                    @endif

                                    <div class="flex gap-3">
                                        <button type="button" @click="showModal = false"
                                            class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition text-xs sm:text-sm">Batal</button>
                                        <button type="submit"
                                            class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition text-xs sm:text-sm">Ya,
                                            Batalkan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($order->canBeCompleted())
                    <div x-data="{ showModal: false }" class="w-full sm:w-auto">
                        <button @click="showModal = true"
                            class="w-full px-5 py-2.5 rounded-lg bg-green-600 text-white text-sm font-bold hover:bg-green-700 shadow-md transition flex items-center justify-center gap-2">
                            <x-heroicon-s-check-circle class="w-4 h-4" />
                            Pesanan Selesai
                        </button>
                        <div x-show="showModal" x-cloak @click.away="showModal = false"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity p-4">
                            <div @click.stop class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                                        <x-heroicon-s-check-circle class="w-6 h-6 text-green-600" />
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Konfirmasi Selesai?</h3>
                                        <p class="text-sm text-gray-500">{{ $order->order_number }}</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mb-6">Pastikan Anda sudah menerima pesanan dalam kondisi
                                    baik. Setelah konfirmasi, dana akan diteruskan ke penjual.</p>
                                <form action="{{ route('profile.orders.complete', $order->id) }}" method="POST">
                                    @csrf
                                    <div class="flex gap-3">
                                        <button type="button" @click="showModal = false"
                                            class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition text-xs sm:text-sm">Batal</button>
                                        <button type="submit"
                                            class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition text-xs sm:text-sm">Ya,
                                            Selesai</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if((!$order->payment || $order->payment->status === 'pending') && $order->status != 'cancelled')
                    <a href="{{ route('payment.show', $order->id) }}"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-lg bg-[#0F4C20] text-white text-sm font-bold hover:bg-[#0b3a18] shadow-md transition flex items-center justify-center gap-2">
                        <x-heroicon-s-credit-card class="w-4 h-4" />
                        Bayar Sekarang
                    </a>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <div
            class="flex flex-col items-center justify-center py-16 bg-white rounded-xl border border-gray-200 border-dashed m-4">
            <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-6">
                <x-heroicon-o-shopping-bag class="w-10 h-10 text-[#0F4C20]" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada pesanan</h3>
            <p class="text-gray-500 text-sm mb-8 text-center max-w-sm">Keranjang belanja Anda masih kosong. Yuk, mulai
                jelajahi produk lokal terbaik kami!</p>
            <a href="{{ route('katalog') }}"
                class="px-8 py-3 bg-[#0F4C20] text-white rounded-lg font-bold text-sm hover:bg-[#0b3a18] transition shadow-lg flex items-center gap-2">
                Mulai Belanja
                <x-heroicon-m-arrow-right class="w-4 h-4" />
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $orders->appends(request()->query())->links() }}
    </div>

</x-layouts.profile>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>