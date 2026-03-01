<x-layouts.profile title="Pesanan Saya - Nusa Belanja" headerTitle="Pesanan Saya"
    headerSubtitle="Semua riwayat transaksi Anda tersimpan rapi di sini">

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="mb-4 md:mb-6 bg-green-50 border-l-4 border-green-500 p-3 md:p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <x-heroicon-s-check-circle class="w-5 h-5 md:w-6 md:h-6 text-green-500 mr-2 md:mr-3 shrink-0" />
            <p class="text-xs md:text-sm font-semibold text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 md:mb-6 bg-red-50 border-l-4 border-red-500 p-3 md:p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <x-heroicon-s-x-circle class="w-5 h-5 md:w-6 md:h-6 text-red-500 mr-2 md:mr-3 shrink-0" />
            <p class="text-xs md:text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- ✅ Status Filter Tabs (Lebih Ringkas di Mobile) --}}
    <div class="flex overflow-x-auto pb-3 mb-4 md:mb-6 gap-2 md:gap-3 no-scrollbar border-b border-gray-100 w-full">
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
        <a href="{{ route('profile.orders', ['status' => $key]) }}"
            class="whitespace-nowrap px-3.5 py-1.5 md:px-5 md:py-2.5 rounded-full text-[11px] md:text-sm font-bold border transition duration-200 flex-shrink-0
                {{ $currentStatus == $key
                    ? 'bg-[#0F4C20] text-white border-[#0F4C20] shadow-sm'
                    : 'bg-white text-gray-500 border-gray-200 hover:border-[#0F4C20] hover:text-[#0F4C20] hover:bg-green-50' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Orders List --}}
    <div class="flex flex-col gap-4 md:gap-6">
        @forelse($orders as $order)
        <div x-data="{ open: false }"
            class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden transition duration-300">

            {{-- ✅ Order Header (Dirapatkan & Flex-wrap di mobile) --}}
            <div class="bg-white border-b border-gray-100 p-3 md:p-5 flex flex-row justify-between items-center gap-2">
                <div class="min-w-0 flex-1">
                    <span
                        class="text-[9px] md:text-xs text-gray-400 font-bold uppercase tracking-wider block mb-0.5">No.
                        Pesanan</span>
                    <p class="text-sm md:text-lg font-bold text-[#2E3B27] font-mono tracking-tight truncate">{{
                        $order->order_number }}</p>
                </div>

                @php
                if ($order->status === 'cancelled') {
                $displayText = 'Dibatalkan';
                $badgeStyle = 'bg-red-50 text-red-700 border-red-200';
                } elseif ($order->status === 'completed') {
                $displayText = 'Selesai';
                $badgeStyle = 'bg-green-50 text-green-700 border-green-200';
                } elseif (!$order->payment || $order->payment->status === 'pending') {
                $displayText = 'Blm Dibayar';
                $badgeStyle = 'bg-orange-50 text-orange-700 border-orange-200';
                } elseif ($order->payment->status === 'paid') {
                $displayText = 'Menunggu Verifikasi';
                $badgeStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                } elseif ($order->payment->status === 'confirmed') {
                if ($order->status === 'processing' || $order->status === 'packing') {
                $displayText = 'Diproses';
                $badgeStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                } elseif ($order->status === 'shipped') {
                $displayText = 'Dikirim';
                $badgeStyle = 'bg-purple-50 text-purple-700 border-purple-200';
                } else {
                $displayText = 'Dikonfirmasi';
                $badgeStyle = 'bg-green-50 text-green-700 border-green-200';
                }
                } elseif ($order->payment->status === 'rejected') {
                $displayText = 'Ditolak';
                $badgeStyle = 'bg-red-50 text-red-700 border-red-200';
                } else {
                $displayText = ucfirst($order->status);
                $badgeStyle = 'bg-gray-50 text-gray-600 border-gray-200';
                }
                @endphp

                <span
                    class="px-2.5 py-1 md:px-4 md:py-1.5 rounded-full text-[9px] md:text-xs font-bold border text-center shrink-0 {{ $badgeStyle }}">
                    {{ $displayText }}
                </span>
            </div>

            {{-- ✅ Order Summary (Collapsed View) --}}
            <div class="p-3 md:p-5 flex flex-col sm:flex-row gap-3 md:gap-5 items-start" x-show="!open">
                @php $firstItem = $order->items->first(); @endphp

                <div class="flex flex-row gap-3 w-full">
                    <div
                        class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 rounded-lg overflow-hidden shrink-0 border border-gray-200">
                        @if($firstItem && $firstItem->product && $firstItem->product->primaryImage)
                        <img src="{{ asset('storage/'.$firstItem->product->primaryImage->image_path) }}"
                            class="w-full h-full object-cover">
                        @else
                        <img src="https://placehold.co/100x100?text=Produk" class="w-full h-full object-cover">
                        @endif
                    </div>

                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <p
                            class="text-[9px] md:text-xs font-bold text-gray-400 mb-0.5 md:mb-1 uppercase tracking-wide truncate">
                            {{ ($firstItem && $firstItem->product) ? ($firstItem->product->store->store_name ?? 'Nusa
                            Belanja') : 'Nusa Belanja' }}
                        </p>
                        <h4 class="font-bold text-[#2E3B27] text-xs md:text-base line-clamp-1 mb-0.5 md:mb-1">
                            {{ ($firstItem && $firstItem->product) ? $firstItem->product->name : 'Produk tidak tersedia'
                            }}
                        </h4>
                        @if($firstItem)
                        <div class="text-[10px] md:text-sm text-gray-500 font-medium">
                            {{ $firstItem->quantity }} brg x Rp {{ number_format($firstItem->price, 0, ',', '.') }}
                        </div>
                        @endif
                        @if($order->items->count() > 1)
                        <div class="mt-1.5">
                            <span
                                class="text-[9px] md:text-xs text-[#0F4C20] font-bold bg-green-50 px-2 py-0.5 md:py-1 rounded inline-block">
                                + {{ $order->items->count() - 1 }} produk lainnya
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <div
                    class="w-full sm:w-auto flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center border-t sm:border-t-0 border-gray-100 pt-2.5 sm:pt-0 mt-1 sm:mt-0 shrink-0">
                    <span class="text-[10px] md:text-xs text-gray-500 font-medium md:mb-1">Total Belanja</span>
                    <span class="text-sm md:text-lg font-bold text-[#0F4C20]">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- ✅ Order Details (Expanded View) --}}
            <div x-show="open" x-cloak x-collapse
                class="bg-gray-50 border-t border-gray-200 p-3 md:p-6 space-y-4 md:space-y-6">

                {{-- Progress Tracker (Ukuran Mikro di Mobile) --}}
                @if($order->status != 'cancelled')
                <div class="px-2 py-4 md:px-4 md:py-6 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div class="relative max-w-2xl mx-auto">
                        <div
                            class="absolute top-[7px] md:top-2 left-[10%] right-[10%] h-0.5 md:h-1 bg-gray-100 rounded-full z-0">
                        </div>

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

                        <div class="absolute top-[7px] md:top-2 left-[10%] h-0.5 md:h-1 {{ $activeColor }} rounded-full transition-all duration-1000 ease-in-out z-0 shadow-[0_0_8px_rgba(15,76,32,0.3)] max-w-[80%]"
                            style="width: {{ $progressWidth }}"></div>

                        <div class="relative z-10 flex justify-between w-full">

                            {{-- Step: Dibuat --}}
                            <div class="flex flex-col items-center w-1/5">
                                <div
                                    class="w-4 h-4 md:w-5 md:h-5 rounded-full border-2 md:border-4 border-white shadow-sm transition-colors duration-500 {{ $steps['pending'] ? $activeColor : 'bg-gray-200' }}">
                                    @if($steps['pending'] && !$steps['confirmed'])
                                    <div class="w-full h-full rounded-full animate-ping {{ $activeColor }} opacity-20">
                                    </div>
                                    @endif
                                </div>
                                <div class="text-center mt-1.5 md:mt-3">
                                    <p
                                        class="text-[8px] md:text-xs font-black uppercase tracking-tight {{ $steps['pending'] ? $textColor : 'text-gray-400' }}">
                                        Dibuat</p>
                                    <p
                                        class="text-[7px] md:text-[9px] font-medium text-gray-400 mt-0.5 bg-gray-50 px-1 md:px-1.5 py-0.5 rounded md:rounded-md inline-block whitespace-nowrap">
                                        {{ $order->created_at->format('d/m H:i') }}</p>
                                </div>
                            </div>

                            {{-- Step: Dikonfirmasi --}}
                            <div class="flex flex-col items-center w-1/5">
                                <div
                                    class="w-4 h-4 md:w-5 md:h-5 rounded-full border-2 md:border-4 border-white shadow-sm transition-colors duration-500 {{ $steps['confirmed'] ? $activeColor : 'bg-gray-200' }}">
                                    @if($steps['confirmed'] && !$steps['packing'])
                                    <div class="w-full h-full rounded-full animate-ping {{ $activeColor }} opacity-20">
                                    </div>
                                    @endif
                                </div>
                                <div class="text-center mt-1.5 md:mt-3">
                                    <p
                                        class="text-[8px] md:text-xs font-black uppercase tracking-tight {{ $steps['confirmed'] ? $textColor : 'text-gray-400' }}">
                                        Dikonfirmasi</p>
                                    @if($order->payment && $order->payment->confirmed_at)
                                    <p
                                        class="text-[7px] md:text-[9px] font-medium text-gray-400 mt-0.5 bg-gray-50 px-1 md:px-1.5 py-0.5 rounded md:rounded-md inline-block whitespace-nowrap">
                                        {{ $order->payment->confirmed_at->format('d/m H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Step: Dikemas --}}
                            <div class="flex flex-col items-center w-1/5">
                                <div
                                    class="w-4 h-4 md:w-5 md:h-5 rounded-full border-2 md:border-4 border-white shadow-sm transition-colors duration-500 {{ $steps['packing'] ? $activeColor : 'bg-gray-200' }}">
                                    @if($steps['packing'] && !$steps['shipped'])
                                    <div class="w-full h-full rounded-full animate-ping {{ $activeColor }} opacity-20">
                                    </div>
                                    @endif
                                </div>
                                <div class="text-center mt-1.5 md:mt-3">
                                    <p
                                        class="text-[8px] md:text-xs font-black uppercase tracking-tight {{ $steps['packing'] ? $textColor : 'text-gray-400' }}">
                                        Dikemas</p>
                                    <p class="text-[7px] md:text-[9px] font-medium text-gray-300 mt-0.5 italic">Proses
                                    </p>
                                </div>
                            </div>

                            {{-- Step: Dikirim --}}
                            <div class="flex flex-col items-center w-1/5">
                                <div
                                    class="w-4 h-4 md:w-5 md:h-5 rounded-full border-2 md:border-4 border-white shadow-sm transition-colors duration-500 {{ $steps['shipped'] ? $activeColor : 'bg-gray-200' }}">
                                    @if($steps['shipped'] && !$steps['completed'])
                                    <div class="w-full h-full rounded-full animate-ping {{ $activeColor }} opacity-20">
                                    </div>
                                    @endif
                                </div>
                                <div class="text-center mt-1.5 md:mt-3">
                                    <p
                                        class="text-[8px] md:text-xs font-black uppercase tracking-tight {{ $steps['shipped'] ? $textColor : 'text-gray-400' }}">
                                        Dikirim</p>
                                    @if($order->shipped_at)
                                    <p
                                        class="text-[7px] md:text-[9px] font-medium text-gray-400 mt-0.5 bg-gray-50 px-1 md:px-1.5 py-0.5 rounded md:rounded-md inline-block whitespace-nowrap">
                                        {{ $order->shipped_at->format('d/m H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Step: Selesai --}}
                            <div class="flex flex-col items-center w-1/5">
                                <div
                                    class="w-4 h-4 md:w-5 md:h-5 rounded-full border-2 md:border-4 border-white shadow-sm transition-colors duration-500 {{ $steps['completed'] ? $activeColor : 'bg-gray-200' }}">
                                </div>
                                <div class="text-center mt-1.5 md:mt-3">
                                    <p
                                        class="text-[8px] md:text-xs font-black uppercase tracking-tight {{ $steps['completed'] ? $textColor : 'text-gray-400' }}">
                                        Selesai</p>
                                    @if($order->delivered_at)
                                    <p
                                        class="text-[7px] md:text-[9px] font-medium text-gray-400 mt-0.5 bg-gray-50 px-1 md:px-1.5 py-0.5 rounded md:rounded-md inline-block whitespace-nowrap">
                                        {{ $order->delivered_at->format('d/m H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @else
                {{-- Cancelled State (Diperkecil di Mobile) --}}
                <div class="p-3 md:p-4 bg-red-50 border border-red-100 rounded-lg">
                    <div class="flex items-start md:items-center gap-2 md:gap-3 text-red-700 mb-2 md:mb-3">
                        <x-heroicon-s-x-circle class="w-5 h-5 md:w-6 md:h-6 shrink-0 mt-0.5 md:mt-0" />
                        <div>
                            <span class="font-bold text-xs md:text-sm">Pesanan ini telah dibatalkan.</span>
                            @if($order->cancellation_reason)
                            <p class="text-[10px] md:text-xs mt-0.5">Alasan: {{ $order->cancellation_reason }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Form Isi Rekening jika Seller yang Cancel --}}
                    @if($order->refund && $order->refund->status === 'needs_bank_info')
                    <div x-data="{ showBankForm: false }"
                        class="mt-3 md:mt-4 p-3 md:p-4 bg-yellow-50 border border-yellow-300 rounded-xl">
                        <div class="flex items-start gap-2 md:gap-3 mb-3">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-yellow-600 shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-xs md:text-sm font-bold text-yellow-800">Isi rekening untuk pengembalian
                                    dana</p>
                                <p class="text-[10px] md:text-xs text-yellow-700 mt-1">
                                    Pesanan dibatalkan penjual. Masukkan rekening Anda agar admin dapat mentransfer
                                    <strong>Rp {{ number_format($order->refund->refund_amount, 0, ',', '.') }}</strong>
                                    (dipotong admin 5%).
                                </p>
                            </div>
                        </div>

                        <button @click="showBankForm = !showBankForm"
                            class="w-full py-2 rounded-lg bg-yellow-500 text-white text-[11px] md:text-sm font-bold hover:bg-yellow-600 transition">
                            <span x-text="showBankForm ? '▲ Tutup Form' : '▼ Isi Rekening Sekarang'"></span>
                        </button>

                        <form x-show="showBankForm" x-cloak
                            action="{{ route('profile.orders.refund.bank', $order->id) }}" method="POST"
                            class="mt-4 space-y-3">
                            @csrf
                            <div
                                class="p-2 md:p-3 bg-white rounded-lg border border-yellow-200 space-y-1.5 text-[10px] md:text-xs">
                                <div class="flex justify-between"><span class="text-gray-500">Total</span><span
                                        class="font-semibold">Rp {{ number_format($order->refund->order_amount, 0, ',',
                                        '.') }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Biaya Admin
                                        (5%)</span><span class="font-semibold text-red-600">- Rp {{
                                        number_format($order->refund->admin_fee, 0, ',', '.') }}</span></div>
                                <hr class="border-yellow-200">
                                <div class="flex justify-between"><span class="font-bold text-gray-800">Dana
                                        Kembali</span><span class="font-bold text-green-700">Rp {{
                                        number_format($order->refund->refund_amount, 0, ',', '.') }}</span></div>
                            </div>
                            <div>
                                <label class="block text-[10px] md:text-xs font-semibold text-gray-700 mb-1">Nama Bank
                                    <span class="text-red-500">*</span></label>
                                <select name="bank_name" required
                                    class="w-full px-2 py-1.5 md:px-3 md:py-2 border border-gray-300 rounded-lg text-xs md:text-sm focus:ring-yellow-500">
                                    <option value="">-- Pilih Bank --</option>
                                    <option value="BCA">BCA</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="BNI">BNI</option>
                                    <option value="BRI">BRI</option>
                                    <option value="CIMB Niaga">CIMB Niaga</option>
                                    <option value="Danamon">Danamon</option>
                                    <option value="Permata">Permata</option>
                                    <option value="BTN">BTN</option>
                                    <option value="BSI">BSI</option>
                                    <option value="Jenius">Jenius</option>
                                    <option value="SeaBank">SeaBank</option>
                                    <option value="GoPay">GoPay</option>
                                    <option value="OVO">OVO</option>
                                    <option value="Dana">Dana</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] md:text-xs font-semibold text-gray-700 mb-1">Nomor
                                    Rekening <span class="text-red-500">*</span></label>
                                <input type="text" name="account_number" required placeholder="Contoh: 1234567890"
                                    class="w-full px-2 py-1.5 md:px-3 md:py-2 border border-gray-300 rounded-lg text-xs md:text-sm focus:ring-yellow-500">
                            </div>
                            <div>
                                <label class="block text-[10px] md:text-xs font-semibold text-gray-700 mb-1">Nama
                                    Pemilik <span class="text-red-500">*</span></label>
                                <input type="text" name="account_holder" required value="{{ auth()->user()->name }}"
                                    class="w-full px-2 py-1.5 md:px-3 md:py-2 border border-gray-300 rounded-lg text-xs md:text-sm focus:ring-yellow-500">
                            </div>
                            <button type="submit"
                                class="w-full py-2 md:py-2.5 rounded-lg bg-[#0F4C20] text-white text-[11px] md:text-sm font-bold hover:bg-[#0b3a18] transition mt-2">
                                💾 Simpan & Kirim
                            </button>
                        </form>
                    </div>
                    @endif

                    {{-- Status Refund --}}
                    @if($order->refund && $order->refund_status !== 'none' && $order->refund->status !==
                    'needs_bank_info')
                    <div class="mt-3 pt-3 border-t border-red-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[9px] md:text-xs font-semibold text-red-800 mb-0.5 md:mb-1">Status
                                    Pengembalian Dana</p>
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
                                    class="inline-block px-2 py-0.5 md:px-3 md:py-1 rounded-full text-[8px] md:text-[10px] font-bold border {{ $refundBadge }}">
                                    {{ $refundText }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] md:text-[10px] text-red-600 mb-0.5 md:mb-1">Dana Kembali</p>
                                <p class="text-xs md:text-sm font-bold text-red-800">Rp {{
                                    number_format($order->refund_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($order->refund_status === 'processed' && $order->refund && $order->refund->refund_proof)
                        <div class="mt-2 md:mt-3">
                            <a href="{{ asset('storage/' . $order->refund->refund_proof) }}" target="_blank"
                                class="flex items-center justify-center gap-1.5 w-full py-1.5 md:py-2 rounded-md bg-red-600 text-white text-[10px] md:text-xs font-bold hover:bg-red-700 transition">
                                <x-heroicon-o-document-check class="w-3 h-3 md:w-4 md:h-4" /> Lihat Bukti
                            </a>
                        </div>
                        @endif

                        @if($order->refund_status === 'pending')
                        <p class="text-[9px] md:text-xs text-red-600 mt-2 flex items-center gap-1">
                            <x-heroicon-s-information-circle class="w-3 h-3 shrink-0" />
                            Transfer dlm 1-3 hari kerja.
                        </p>
                        @endif
                    </div>
                    @endif
                </div>
                @endif

                {{-- Product List --}}
                <div class="space-y-3 md:space-y-4">
                    <h4 class="font-bold text-[#2E3B27] text-xs md:text-sm flex items-center gap-1.5 md:gap-2">
                        <x-heroicon-s-shopping-bag class="w-3.5 h-3.5 md:w-4 md:h-4 text-[#8B4513]" /> Rincian Produk
                    </h4>

                    @foreach($order->items as $item)
                    @if(!$item->product)
                    <div
                        class="flex gap-2.5 md:gap-4 p-2 md:p-3 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                        <div
                            class="w-12 h-12 md:w-16 md:h-16 bg-gray-200 rounded-md flex items-center justify-center shrink-0">
                            <x-heroicon-o-cube class="w-5 h-5 text-gray-400" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] md:text-xs text-gray-400 italic line-clamp-1">Produk tidak tersedia
                            </p>
                            <p class="text-[9px] md:text-xs text-gray-500 mt-0.5 md:mt-1">{{ $item->quantity }}x • Rp {{
                                number_format($item->total, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right flex flex-col justify-center shrink-0">
                            <span class="text-[11px] md:text-sm font-bold text-gray-400">Rp {{
                                number_format($item->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @continue
                    @endif

                    <div class="flex gap-2.5 md:gap-4 p-2 md:p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div
                            class="w-12 h-12 md:w-16 md:h-16 bg-gray-50 rounded-md overflow-hidden shrink-0 border border-gray-200">
                            @if($item->product->primaryImage)
                            <img src="{{ asset('storage/'.$item->product->primaryImage->image_path) }}"
                                class="w-full h-full object-cover">
                            @else
                            <img src="https://placehold.co/100x100?text=Produk" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase line-clamp-1">
                                {{ $item->product->category->name ?? 'Umum' }}
                            </p>
                            <h5 class="font-bold text-[#2E3B27] text-xs md:text-sm line-clamp-1 md:line-clamp-2 mt-0.5">
                                {{ $item->product->name }}</h5>
                            <p class="text-[9px] md:text-xs text-[#8B4513] font-bold mt-0.5 md:mt-1">
                                {{ $item->quantity }} x {{ $item->product->unit ?? 'pcs' }}
                            </p>
                        </div>
                        <div class="text-right flex flex-col justify-center shrink-0 pl-2">
                            <span class="text-xs md:text-sm font-bold text-gray-600">
                                Rp {{ number_format($item->total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Shipping & Payment Info (1 Kolom di Mobile, 2 Kolom di Desktop) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

                    {{-- Alamat Pengiriman + Kurir --}}
                    <div class="bg-white p-3 md:p-4 rounded-lg border border-gray-200 shadow-sm h-full flex flex-col">
                        <h4
                            class="font-bold text-[#2E3B27] text-xs md:text-sm flex items-center gap-1.5 border-b border-gray-100 pb-2 mb-2">
                            <x-heroicon-s-map-pin class="w-3.5 h-3.5 md:w-4 md:h-4 text-[#8B4513]" /> Pengiriman
                        </h4>
                        <div class="text-[10px] md:text-xs text-gray-600 space-y-1 flex-1">
                            <p class="font-bold text-gray-800 text-xs md:text-sm">{{ $order->recipient_name }}
                                <span class="font-normal text-gray-500">({{ $order->recipient_phone }})</span>
                            </p>
                            <p class="leading-relaxed">{{ $order->shipping_address }}</p>
                            @if($order->notes)
                            <p class="italic text-gray-400 mt-1">"{{ $order->notes }}"</p>
                            @endif
                        </div>

                        @if($order->courier || $order->tracking_number)
                        <div class="mt-3 pt-2 md:pt-3 border-t border-gray-100 space-y-2 md:space-y-3">
                            @if($order->courier)
                            <div class="flex items-center gap-2">
                                <x-heroicon-s-truck class="w-3.5 h-3.5 md:w-4 md:h-4 text-[#8B4513] shrink-0" />
                                <div>
                                    <p class="text-[9px] md:text-[10px] text-gray-500 font-medium">Kurir</p>
                                    <p class="text-[10px] md:text-xs font-bold text-gray-800">{{
                                        strtoupper($order->courier) }}</p>
                                </div>
                            </div>
                            @endif

                            @if($order->tracking_number)
                            <div class="flex items-center gap-2">
                                <x-heroicon-m-document-text class="w-3.5 h-3.5 md:w-4 md:h-4 text-[#8B4513] shrink-0" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-[9px] md:text-[10px] text-gray-500 font-medium">No. Resi</p>
                                    <p class="text-[10px] md:text-xs font-bold text-gray-800 font-mono truncate">{{
                                        $order->tracking_number }}</p>
                                </div>
                                <button
                                    onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}'); alert('✅ Resi disalin: {{ $order->tracking_number }}')"
                                    class="px-2 py-1 md:px-2 md:py-1 bg-green-50 text-green-700 rounded text-[9px] md:text-[10px] font-bold hover:bg-green-100 transition shrink-0 border border-green-200">
                                    Salin
                                </button>
                            </div>
                            @endif

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
                                class="flex items-center justify-center gap-1.5 w-full py-1.5 md:py-2 rounded-md bg-[#0F4C20] text-white text-[9px] md:text-xs font-bold hover:bg-[#0b3a18] transition mt-1">
                                <x-heroicon-o-magnifying-glass class="w-3 h-3 md:w-4 md:h-4" /> Lacak Paket
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- Rincian Pembayaran --}}
                    <div class="bg-white p-3 md:p-4 rounded-lg border border-gray-200 shadow-sm h-full flex flex-col">
                        <h4
                            class="font-bold text-[#2E3B27] text-xs md:text-sm flex items-center gap-1.5 border-b border-gray-100 pb-2 mb-2 md:mb-3">
                            <x-heroicon-s-receipt-percent class="w-3.5 h-3.5 md:w-4 md:h-4 text-[#8B4513]" /> Pembayaran
                        </h4>
                        <div class="space-y-1.5 md:space-y-2 text-[10px] md:text-xs text-gray-600 flex-1">
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
                        <div
                            class="border-t border-gray-100 mt-2 pt-2 md:mt-3 md:pt-3 flex justify-between items-center">
                            <span class="font-bold text-gray-800 text-xs md:text-sm">Total</span>
                            <span class="text-sm md:text-base font-bold text-[#0F4C20]">Rp {{
                                number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>

                        @if($order->payment && $order->payment->payment_proof)
                        <div class="mt-3 md:mt-4 pt-2 md:pt-3 border-t border-gray-100">
                            <a href="{{ asset('storage/' . $order->payment->payment_proof) }}" target="_blank"
                                class="flex items-center justify-center gap-1.5 w-full py-1.5 md:py-2 rounded-md bg-[#0F4C20] text-white text-[9px] md:text-xs font-bold hover:bg-[#0b3a18] transition">
                                <x-heroicon-o-document-check class="w-3 h-3 md:w-4 md:h-4" /> Lihat Bukti
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ✅ Action Buttons (Bottom Bar di dalam Card) --}}
            <div
                class="bg-gray-50 px-3 py-3 md:px-5 md:py-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-between gap-2 md:gap-3 items-center w-full">

                {{-- Toggle Detail (Full width di Mobile) --}}
                <button @click="open = !open"
                    class="w-full sm:w-auto justify-center px-4 py-2 md:px-5 md:py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 text-xs md:text-sm font-bold hover:bg-gray-100 transition shadow-sm flex items-center gap-1.5">
                    <span x-text="open ? 'Tutup Detail' : 'Lihat Detail'"></span>
                    <x-heroicon-m-chevron-down class="w-3.5 h-3.5 transition-transform duration-300"
                        ::class="open ? 'rotate-180' : ''" />
                </button>

                {{-- Right Actions (Full width di Mobile) --}}
                <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-2 items-center">

                    {{-- Isi Rekening Shortcut --}}
                    @if($order->status === 'cancelled' && $order->refund && $order->refund->status ===
                    'needs_bank_info')
                    <button @click="open = true"
                        class="w-full sm:w-auto justify-center px-4 py-2 md:px-5 md:py-2.5 rounded-lg bg-yellow-500 text-white text-xs md:text-sm font-bold hover:bg-yellow-600 shadow-sm transition flex items-center gap-1.5">
                        <x-heroicon-o-banknotes class="w-4 h-4" /> Isi Rekening Refund
                    </button>
                    @endif

                    {{-- Batalkan Pesanan --}}
                    @php
                    $canCancel = $order->canBeCancelled();
                    $remainingMinutes = $order->getCancelTimeRemaining();
                    $needsRefund = $order->payment && $order->payment->status === 'paid';
                    @endphp

                    @if($canCancel)
                    <div x-data="{ showModal: false }" class="w-full sm:w-auto">
                        <button @click="showModal = true"
                            class="w-full sm:w-auto justify-center px-4 py-2 md:px-5 md:py-2.5 rounded-lg bg-red-600 text-white text-xs md:text-sm font-bold hover:bg-red-700 shadow-sm transition flex items-center gap-1.5">
                            <x-heroicon-s-x-circle class="w-3.5 h-3.5" /> Batalkan
                            @if($remainingMinutes && $remainingMinutes > 0)
                            <span class="text-[9px] opacity-80">({{ floor($remainingMinutes / 60) }}j {{
                                $remainingMinutes % 60 }}m)</span>
                            @endif
                        </button>

                        {{-- Cancel Modal --}}
                        <div x-show="showModal" x-cloak @click.away="showModal = false"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity p-4 text-left">
                            <div @click.stop
                                class="bg-white rounded-xl shadow-2xl max-w-md w-full p-4 md:p-6 max-h-[90vh] overflow-y-auto">
                                <div class="flex items-center gap-2.5 mb-4">
                                    <div
                                        class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                                        <x-heroicon-s-exclamation-triangle class="w-5 h-5 md:w-6 md:h-6 text-red-600" />
                                    </div>
                                    <div>
                                        <h3 class="text-base md:text-lg font-bold text-gray-900 leading-tight">Batalkan
                                            Pesanan?</h3>
                                        <p class="text-[10px] md:text-sm text-gray-500">{{ $order->order_number }}</p>
                                    </div>
                                </div>

                                <form action="{{ route('profile.orders.cancel', $order->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3 md:mb-4">
                                        <label
                                            class="block text-[10px] md:text-sm font-semibold text-gray-700 mb-1.5">Alasan
                                            Pembatalan (Opsional)</label>
                                        <textarea name="reason" rows="2"
                                            class="w-full px-2.5 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 text-xs md:text-sm"
                                            placeholder="Contoh: Salah memesan..."></textarea>
                                    </div>

                                    @if($needsRefund)
                                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="text-[10px] md:text-xs font-semibold text-yellow-800">Dana akan
                                            dikembalikan dipotong biaya admin 5%.</p>
                                    </div>
                                    @endif

                                    <div class="flex flex-col-reverse sm:flex-row gap-2 md:gap-3">
                                        <button type="button" @click="showModal = false"
                                            class="w-full py-2 border border-gray-300 rounded-lg text-gray-700 font-bold text-xs md:text-sm hover:bg-gray-50 transition">Batal</button>
                                        <button type="submit"
                                            class="w-full py-2 bg-red-600 text-white rounded-lg font-bold text-xs md:text-sm hover:bg-red-700 transition">Ya,
                                            Batalkan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Selesaikan Pesanan --}}
                    @if($order->canBeCompleted())
                    <div x-data="{ showModal: false }" class="w-full sm:w-auto">
                        <button @click="showModal = true"
                            class="w-full sm:w-auto justify-center px-4 py-2 md:px-5 md:py-2.5 rounded-lg bg-green-600 text-white text-xs md:text-sm font-bold hover:bg-green-700 shadow-sm transition flex items-center gap-1.5">
                            <x-heroicon-s-check-circle class="w-3.5 h-3.5" /> Pesanan Selesai
                        </button>

                        <div x-show="showModal" x-cloak @click.away="showModal = false"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity p-4 text-left">
                            <div @click.stop class="bg-white rounded-xl shadow-2xl max-w-md w-full p-4 md:p-6">
                                <div class="flex items-center gap-2.5 mb-3">
                                    <div
                                        class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                                        <x-heroicon-s-check-circle class="w-5 h-5 md:w-6 md:h-6 text-green-600" />
                                    </div>
                                    <div>
                                        <h3 class="text-base md:text-lg font-bold text-gray-900 leading-tight">
                                            Konfirmasi Selesai?</h3>
                                        <p class="text-[10px] md:text-sm text-gray-500">{{ $order->order_number }}</p>
                                    </div>
                                </div>
                                <p class="text-[10px] md:text-sm text-gray-600 mb-4">Pastikan pesanan sudah diterima
                                    dengan baik. Dana akan diteruskan ke penjual.</p>
                                <form action="{{ route('profile.orders.complete', $order->id) }}" method="POST">
                                    @csrf
                                    <div class="flex flex-col-reverse sm:flex-row gap-2 md:gap-3">
                                        <button type="button" @click="showModal = false"
                                            class="w-full py-2 border border-gray-300 rounded-lg text-gray-700 font-bold text-xs md:text-sm hover:bg-gray-50 transition">Batal</button>
                                        <button type="submit"
                                            class="w-full py-2 bg-green-600 text-white rounded-lg font-bold text-xs md:text-sm hover:bg-green-700 transition">Ya,
                                            Selesai</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Bayar Sekarang --}}
                    @if((!$order->payment || $order->payment->status === 'pending') && $order->status != 'cancelled')
                    <a href="{{ route('payment.show', $order->id) }}"
                        class="w-full sm:w-auto flex justify-center px-4 py-2 md:px-5 md:py-2.5 rounded-lg bg-[#0F4C20] text-white text-xs md:text-sm font-bold hover:bg-[#0b3a18] shadow-sm transition items-center gap-1.5">
                        <x-heroicon-s-credit-card class="w-3.5 h-3.5" /> Bayar Sekarang
                    </a>
                    @endif

                </div>
            </div>

        </div>
        @empty
        <div
            class="flex flex-col items-center justify-center py-12 md:py-16 bg-white rounded-xl border border-gray-200 border-dashed mx-2 md:mx-0">
            <div
                class="w-16 h-16 md:w-20 md:h-20 bg-green-50 rounded-full flex items-center justify-center mb-4 md:mb-6">
                <x-heroicon-o-shopping-bag class="w-8 h-8 md:w-10 md:h-10 text-[#0F4C20]" />
            </div>
            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-1 md:mb-2">Belum ada pesanan</h3>
            <p class="text-xs md:text-sm text-gray-500 mb-6 md:mb-8 text-center max-w-xs px-4">
                Keranjang belanja Anda masih kosong. Yuk, mulai jelajahi produk lokal terbaik kami!
            </p>
            <a href="{{ route('katalog') }}"
                class="px-6 py-2.5 md:px-8 md:py-3 bg-[#0F4C20] text-white rounded-lg font-bold text-xs md:text-sm hover:bg-[#0b3a18] transition shadow-md flex items-center gap-1.5">
                Mulai Belanja
                <x-heroicon-m-arrow-right class="w-3 h-3 md:w-4 md:h-4" />
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6 md:mt-8 flex justify-center overflow-x-auto pb-4">
        {{ $orders->appends(request()->query())->links() }}
    </div>

</x-layouts.profile>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>