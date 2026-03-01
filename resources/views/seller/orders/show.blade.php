@extends('layouts.seller')

@section('title', 'Detail Pesanan')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="max-w-6xl mx-auto px-2 sm:px-0">
    <div class="mb-4 md:mb-6">
        <div class="flex items-center gap-2 md:gap-3 mb-2">
            <a href="{{ route('seller.orders.index') }}"
                class="p-1.5 md:p-2 hover:bg-gray-100 rounded-lg transition bg-white border border-gray-100 shadow-sm">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex-1 min-w-0">
                <nav class="flex text-[10px] md:text-sm text-gray-500 mb-0.5">
                    <a href="{{ route('seller.orders.index') }}" class="hover:text-green-600">Pesanan</a>
                    <span class="mx-1.5">›</span>
                    <span class="text-gray-900 font-medium truncate">Detail</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-base md:text-2xl font-bold text-green-800 truncate">
                        Pesanan <span class="text-gray-900">#{{ $order->order_number }}</span>
                    </h1>
                    @php
                    $statusColors = [
                    'processing' => 'bg-yellow-500',
                    'packing' => 'bg-blue-500',
                    'shipped' => 'bg-purple-600',
                    'completed' => 'bg-green-600',
                    'pending' => 'bg-orange-600',
                    'cancelled' => 'bg-red-600'
                    ];
                    $statusLabels = [
                    'processing' => 'Diproses', 'packing' => 'Dikemas', 'shipped' => 'Dikirim',
                    'completed' => 'Selesai', 'pending' => 'Pending', 'cancelled' => 'Batal'
                    ];
                    @endphp
                    <span
                        class="px-2 py-0.5 rounded-full text-[9px] md:text-xs font-bold text-white {{ $statusColors[$order->status] ?? 'bg-gray-500' }}">
                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success') || session('error') || $errors->any())
    <div class="mb-4 md:mb-6">
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-3 rounded-lg flex items-center gap-3">
            <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 shrink-0" />
            <p class="text-xs md:text-sm font-semibold text-green-800">{{ session('success') }}</p>
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded-lg flex items-center gap-3">
            <x-heroicon-s-x-circle class="w-5 h-5 text-red-500 shrink-0" />
            <p class="text-xs md:text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
        @endif
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        <div class="lg:col-span-2 space-y-4 md:space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 md:p-6 border-b border-gray-50 flex items-center gap-2">
                    <x-heroicon-s-shopping-bag class="w-5 h-5 text-green-700" />
                    <h2 class="text-sm md:text-lg font-bold text-gray-900">Ringkasan Produk</h2>
                </div>

                <div class="p-3 md:p-6 space-y-3">
                    @foreach($order->items as $item)
                    <div
                        class="flex items-center gap-3 md:gap-4 p-3 border border-gray-100 rounded-xl hover:border-green-200 transition">
                        <div
                            class="w-14 h-14 md:w-20 md:h-20 bg-gray-50 rounded-lg overflow-hidden shrink-0 border border-gray-100">
                            @if($item->product->images->count() > 0)
                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <x-heroicon-o-photo class="w-6 h-6" />
                            </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-[9px] md:text-xs text-gray-400 font-bold uppercase truncate">{{
                                $item->product->category->name ?? 'Produk' }}</p>
                            <p class="text-xs md:text-sm font-bold text-gray-900 line-clamp-1 mb-1">{{
                                $item->product->name }}</p>
                            <p class="text-[10px] md:text-sm text-gray-500 font-medium">{{ $item->quantity }} {{
                                $item->product->unit ?? 'Pcs' }}</p>
                        </div>

                        <div class="text-right shrink-0">
                            <p class="text-xs md:text-base font-black text-gray-900">Rp{{ number_format($item->total, 0,
                                ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                <h3 class="text-sm md:text-lg font-bold text-gray-900 mb-5 md:mb-6">Status Perjalanan</h3>
                @php
                $statuses = [
                ['key' => 'pending', 'label' => 'Antrean'],
                ['key' => 'processing', 'label' => 'Proses'],
                ['key' => 'shipped', 'label' => 'Dikirim'],
                ['key' => 'completed', 'label' => 'Selesai']
                ];
                $statusMap = ['pending' => 1, 'processing' => 2, 'packing' => 2, 'shipped' => 3, 'completed' => 4,
                'cancelled' => 0];
                $currentStep = $statusMap[$order->status] ?? 1;
                @endphp

                <div class="relative px-2">
                    <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-100" style="margin: 0 10%;">
                        <div class="h-full bg-green-600 transition-all duration-700 shadow-[0_0_8px_rgba(22,163,74,0.4)]"
                            style="width: {{ $currentStep > 0 ? (($currentStep - 1) / 3) * 100 : 0 }}%"></div>
                    </div>

                    <div class="relative flex justify-between">
                        @foreach($statuses as $index => $status)
                        <div class="flex flex-col items-center z-10 w-1/4">
                            <div
                                class="w-7 h-7 md:w-8 md:h-8 rounded-full flex items-center justify-center mb-2 {{ ($index + 1) <= $currentStep ? 'bg-green-600 shadow-md scale-110' : 'bg-gray-200' }} transition-all">
                                @if(($index + 1)
                                <= $currentStep) <x-heroicon-s-check class="w-4 h-4 text-white" />
                                @else
                                <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                                @endif
                            </div>
                            <span
                                class="text-[8px] md:text-xs text-center font-bold {{ ($index + 1) <= $currentStep ? 'text-green-700' : 'text-gray-400' }}">{{
                                $status['label'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-4 md:space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <x-heroicon-s-user class="w-4 h-4 text-green-700" />
                    <h3 class="text-sm font-bold text-gray-900">Informasi Pembeli</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm"
                            style="background-color: {{ '#' . substr(md5($order->user->name), 0, 6) }}">
                            {{ strtoupper(substr($order->user->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $order->user->name }}</p>
                            <p class="text-[10px] text-gray-500 truncate">{{ $order->user->email }}</p>
                        </div>
                    </div>

                    <div class="bg-[#F9FDF7] p-3 rounded-lg border border-green-100">
                        <p class="text-[10px] font-bold text-green-800 uppercase tracking-widest mb-1">Alamat Kirim</p>
                        <p class="text-xs font-bold text-gray-800 mb-1">{{ $order->recipient_phone }}</p>
                        <p class="text-xs text-gray-600 leading-relaxed">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                <h3 class="text-sm md:text-lg font-bold text-gray-900 mb-4">Rincian Pembayaran</h3>
                <div class="space-y-2 text-xs md:text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span class="font-bold text-gray-800">Rp{{ number_format($order->sub_total, 0, ',', '.')
                            }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Ongkos Kirim</span>
                        <span class="font-bold text-gray-800">Rp{{ number_format($order->shipping_cost, 0, ',', '.')
                            }}</span>
                    </div>
                    <div class="pt-3 border-t border-dashed border-gray-200 mt-2 flex justify-between items-center">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="text-base md:text-xl font-black text-green-600">Rp{{
                            number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($order->payment && $order->payment->payment_proof)
                <div class="mt-4 pt-4 border-t border-gray-50">
                    <a href="{{ asset('storage/' . $order->payment->payment_proof) }}" target="_blank"
                        class="flex items-center justify-center gap-2 w-full py-2 rounded-lg bg-green-50 text-green-700 text-xs font-bold hover:bg-green-100 transition border border-green-200">
                        <x-heroicon-o-document-magnifying-glass class="w-4 h-4" /> Lihat Bukti Bayar
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($order->payment && $order->payment->status == 'confirmed' && !in_array($order->status, ['completed',
    'cancelled']))
    <div class="bg-white rounded-xl shadow-lg border-2 border-green-100 p-5 md:p-8 mt-6 md:mt-8">
        <h3 class="text-sm md:text-lg font-bold text-[#0F4C20] mb-4 flex items-center gap-2">
            <x-heroicon-s-pencil-square class="w-5 h-5" /> Kelola Status Pesanan
        </h3>

        <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST"
            x-data="{ status: '{{ $order->status }}' }">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-[11px] md:text-sm font-bold text-gray-700 mb-1.5 md:mb-2">Pilih Status Baru
                        *</label>
                    <select name="status" required x-model="status"
                        class="w-full px-3 py-2.5 md:px-4 md:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600 focus:border-green-600 text-xs md:text-sm bg-gray-50">
                        <option value="">-- Pilih Status --</option>
                        <option value="processing">Diproses (Processing)</option>
                        <option value="packing">Dikemas (Packing)</option>
                        <option value="shipped">Dikirim (Shipped)</option>
                        <option value="completed">Selesai (Completed)</option>
                        <option value="cancelled">Batalkan (Cancelled)</option>
                    </select>
                </div>

                <div x-show="status === 'shipped'" x-cloak class="space-y-4 md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] md:text-sm font-bold text-gray-700 mb-1.5">Kurir
                                Terpilih</label>
                            <input type="text" name="courier" readonly value="{{ $order->courier ?? 'N/A' }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl bg-gray-100 text-gray-500 cursor-not-allowed text-xs md:text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] md:text-sm font-bold text-gray-700 mb-1.5">Nomor Resi
                                *</label>
                            <input type="text" name="tracking_number" placeholder="Masukkan nomor resi..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600 text-xs md:text-sm">
                        </div>
                    </div>
                </div>

                <div x-show="status === 'cancelled'" x-cloak class="md:col-span-2">
                    <label class="block text-[11px] md:text-sm font-bold text-gray-700 mb-1.5">Alasan Pembatalan
                        *</label>
                    <textarea name="cancellation_reason" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600 text-xs md:text-sm"
                        placeholder="Jelaskan alasan pembatalan..."></textarea>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition shadow-md active:scale-95 text-xs md:text-sm">
                    Simpan Perubahan Status
                </button>
                <a href="{{ route('seller.orders.index') }}"
                    class="px-6 py-3 border border-gray-300 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition text-center text-xs md:text-sm">
                    Kembali
                </a>
            </div>
        </form>
    </div>
    @endif

    @if(!$order->payment || ($order->payment->status !== 'confirmed' && $order->status !== 'cancelled'))
    <div class="bg-yellow-50 border border-yellow-200 p-4 mt-6 rounded-xl flex items-start gap-3">
        <x-heroicon-s-exclamation-triangle class="w-5 h-5 text-yellow-600 shrink-0" />
        <div>
            <p class="text-xs md:text-sm font-bold text-yellow-800">Menunggu Konfirmasi Admin</p>
            <p class="text-[10px] md:text-xs text-yellow-700 mt-0.5">Status belum bisa diubah hingga pembayaran
                divalidasi oleh sistem/admin.</p>
        </div>
    </div>
    @elseif(in_array($order->status, ['completed', 'cancelled']))
    <div class="bg-gray-50 border border-gray-200 p-4 mt-6 rounded-xl flex items-center gap-3">
        <x-heroicon-s-information-circle class="w-5 h-5 text-gray-500 shrink-0" />
        <p class="text-xs md:text-sm font-bold text-gray-600 uppercase tracking-tight">
            Status Pesanan: {{ $order->status == 'completed' ? 'Sudah Selesai' : 'Sudah Dibatalkan' }}
        </p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush