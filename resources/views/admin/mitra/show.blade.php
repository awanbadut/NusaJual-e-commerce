@extends('layouts.admin')

@section('title', $store->store_name . ' - Admin Nusa Belanja')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
{{-- CONTAINER UTAMA (Added padding for clean look) --}}
<div class="px-2 sm:px-0">

    {{-- HEADER WITH EXPORT BUTTON --}}
    <div
        class="bg-gradient-to-br from-[#D1FAE5] to-[#DCFCE7] p-4 sm:p-6 rounded-2xl sm:rounded-3xl mb-4 sm:mb-6 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between items-start gap-4 sm:gap-6">
            <div class="flex flex-row sm:flex-row gap-4 sm:gap-5 items-center sm:items-start w-full">
                {{-- Logo: Smaller on mobile --}}
                <div
                    class="w-16 h-16 sm:w-32 sm:h-32 bg-[#A78BFA] rounded-xl sm:rounded-2xl flex items-center justify-center text-white font-bold text-xl sm:text-4xl shadow-md overflow-hidden flex-shrink-0">
                    @if($store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}"
                        class="w-full h-full object-cover">
                    @else
                    {{ strtoupper(substr($store->store_name, 0, 2)) }}
                    @endif
                </div>

                <div class="text-left w-full">
                    <h1 class="text-lg sm:text-[28px] font-bold text-[#111827] mb-0.5 sm:mb-2 leading-tight">
                        Mitra {{ $store->store_name }}
                    </h1>
                    <p class="text-[10px] sm:text-[13px] text-[#78716C] mb-2 sm:mb-3 leading-snug">
                        Pantau Progress Mitra dan Rekening Bersama Disini
                    </p>

                    <div class="space-y-1 sm:space-y-2 flex flex-col items-start">
                        <div class="flex items-center gap-1.5 sm:gap-2 text-[10px] sm:text-[12px] text-[#111827]">
                            <x-heroicon-s-map-pin class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#6B7280] shrink-0" />
                            <span class="text-left line-clamp-1">{{ $store->address }}</span>
                        </div>

                        <div
                            class="flex flex-wrap justify-start items-center gap-3 sm:gap-4 text-[10px] sm:text-[12px]">
                            <div class="flex items-center gap-1 sm:gap-1.5">
                                <x-heroicon-s-cube class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#6B7280]" />
                                <span class="text-[#111827]">{{ $store->products->count() }} Produk</span>
                            </div>
                            <div class="flex items-center gap-1 sm:gap-1.5">
                                <x-heroicon-s-shopping-bag class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#6B7280]" />
                                <span class="text-[#111827]">{{ number_format($totalItemsSold) }}+ Terjual</span>
                            </div>
                        </div>

                        @if($store->bankAccounts->first())
                        <div
                            class="flex flex-wrap justify-start items-center gap-1.5 sm:gap-2 text-[10px] sm:text-[12px] mt-1 bg-white/60 px-2 py-1 rounded-lg w-fit">
                            <span class="text-[#78716C]">Rekening:</span>
                            <span class="font-semibold text-[#111827]">{{ $store->bankAccounts->first()->bank_name
                                }}</span>
                            <span class="text-[#111827] font-mono">{{ $store->bankAccounts->first()->account_number
                                }}</span>
                            @if($totalBankAccounts > 1)
                            <span class="text-[#15803D] underline cursor-pointer ml-1" title="Lihat lainnya">
                                +{{ $totalBankAccounts - 1 }} lainnya
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex flex-wrap sm:flex-nowrap justify-start sm:justify-end gap-2 w-full md:w-auto mt-2 sm:mt-0">
                <a href="{{ route('admin.mitra.export', $store->id) }}"
                    class="bg-[#15803D] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#166534] transition text-[10px] sm:text-[12px] font-medium flex items-center gap-1.5 sm:gap-2 shadow-sm flex-1 sm:flex-none justify-center whitespace-nowrap">
                    <x-heroicon-s-arrow-down-tray class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                    Download Laporan
                </a>

                @if($store->user && $store->user->instagram)
                <a href="{{ $store->user->instagram }}" target="_blank"
                    class="bg-gradient-to-r from-[#E91E63] to-[#F06292] text-white px-3 sm:px-4 py-2 rounded-lg text-[10px] sm:text-[12px] font-medium flex items-center gap-1.5 sm:gap-2 shadow-sm hover:opacity-90 transition flex-1 sm:flex-none justify-center">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                    </svg>
                    IG
                </a>
                @endif

                @if($store->user && $store->user->phone)
                <a href="{{ $store->whatsapp_url }}" target="_blank"
                    class="flex items-center gap-1.5 sm:gap-2 text-white px-3 sm:px-4 py-2 rounded-lg text-[10px] sm:text-[12px] font-medium shadow-sm hover:opacity-90 transition bg-[#0F4C20] hover:bg-[#0b3a18] flex-1 sm:flex-none justify-center">
                    <img src="https://img.icons8.com/ios-filled/50/ffffff/whatsapp.png"
                        class="w-3.5 h-3.5 sm:w-5 sm:h-5">
                    WA
                </a>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
    <div
        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 sm:p-4 rounded-xl mb-4 sm:mb-6 text-[11px] sm:text-[13px] flex items-center gap-2 sm:gap-3">
        <x-heroicon-s-check-circle class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" />
        {{ session('success') }}
    </div>
    @endif

    {{-- STATISTICS CARDS (Grid 2 Kolom di Mobile) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 mb-6 sm:mb-8">
        <div class="bg-white p-3 sm:px-5 sm:py-4 rounded-xl sm:rounded-2xl shadow-sm border border-[#E5E7EB]">
            <p class="text-[9px] sm:text-[11px] text-[#78716C] mb-1 sm:mb-1.5 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#EF4444]"></span>
                Total Penjualan
            </p>
            <p class="text-base sm:text-[20px] font-bold text-[#111827] leading-tight mb-0.5 sm:mb-1 truncate">
                Rp {{ number_format($store->total_sales ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-[9px] sm:text-[10px] {{ $salesGrowth >= 0 ? 'text-[#15803D]' : 'text-[#EF4444]' }} truncate">
                {{ $salesGrowth >= 0 ? '+' : '' }}{{ number_format($salesGrowth, 1) }}% vs bulan lalu
            </p>
        </div>

        <div class="bg-white p-3 sm:px-5 sm:py-4 rounded-xl sm:rounded-2xl shadow-sm border border-[#E5E7EB]">
            <p class="text-[9px] sm:text-[11px] text-[#78716C] mb-1 sm:mb-1.5 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                Total Transaksi
            </p>
            <p class="text-base sm:text-[20px] font-bold text-[#111827] leading-tight mb-0.5 sm:mb-1">
                {{ number_format($store->orders_count ?? 0) }}
            </p>
            <p
                class="text-[9px] sm:text-[10px] {{ $ordersGrowth >= 0 ? 'text-[#15803D]' : 'text-[#EF4444]' }} truncate">
                {{ $ordersGrowth >= 0 ? '+' : '' }}{{ number_format($ordersGrowth, 1) }}% vs bulan lalu
            </p>
        </div>

        <div class="bg-white p-3 sm:px-5 sm:py-4 rounded-xl sm:rounded-2xl shadow-sm border border-[#E5E7EB]">
            <p class="text-[9px] sm:text-[11px] text-[#78716C] mb-1 sm:mb-1.5 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#3B82F6]"></span>
                Banyak Pencairan
            </p>
            <p class="text-base sm:text-[20px] font-bold text-[#111827] leading-tight mb-0.5 sm:mb-1">
                {{ $totalPencairan }}
            </p>
            <p class="text-[9px] sm:text-[10px] text-[#78716C] truncate">Total penarikan selesai</p>
        </div>

        <div class="bg-white p-3 sm:px-5 sm:py-4 rounded-xl sm:rounded-2xl shadow-sm border border-[#E5E7EB]">
            <p class="text-[9px] sm:text-[11px] text-[#78716C] mb-1 sm:mb-1.5 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                Dana Tersedia
            </p>
            <p class="text-base sm:text-[18px] font-bold text-[#15803D] leading-tight mb-0.5 sm:mb-1 truncate">
                Rp {{ number_format($sisaDana, 0, ',', '.') }}
            </p>
            <hr class="my-1 sm:my-1.5 border-[#E5E7EB]">
            <div class="flex justify-between items-center">
                <p class="text-[8px] sm:text-[10px] text-[#78716C]">Teralokasi</p>
                <p class="text-[9px] sm:text-[12px] font-bold text-[#F97316] truncate">Rp {{
                    number_format($danaTeralokasi, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- PERFORMANCE CHART --}}
    <div class="bg-white p-4 sm:px-6 sm:py-5 rounded-2xl shadow-sm mb-6 sm:mb-8 border border-[#E5E7EB]">
        <h2 class="font-bold text-sm sm:text-[16px] text-[#111827] mb-3 sm:mb-4">Progress Penjualan</h2>
        <div class="h-48 sm:h-[280px] w-full">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

    {{-- PENDING PAYMENTS --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6 sm:mb-8 border border-gray-100">
        <div class="px-4 sm:px-5 py-3 sm:py-4 bg-white border-b border-gray-100">
            <h2 class="font-bold text-sm sm:text-[16px] text-[#111827]">Persetujuan Bukti Bayar Masuk</h2>
            <p class="text-[10px] sm:text-[12px] text-[#78716C] mt-0.5 sm:mt-1">Daftar transaksi dari pembeli yang
                menunggu validasi admin.</p>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left text-[11px] sm:text-[13px] min-w-[800px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th class="px-4 py-3 sm:px-5 sm:py-4 font-semibold text-[#15803D] whitespace-nowrap">ID Pesanan
                        </th>
                        <th class="px-4 py-3 sm:px-5 sm:py-4 font-semibold text-[#15803D] whitespace-nowrap">Nama
                            Pembeli</th>
                        <th class="px-4 py-3 sm:px-5 sm:py-4 font-semibold text-[#15803D] whitespace-nowrap">Jumlah
                            Pembayaran</th>
                        <th class="px-4 py-3 sm:px-5 sm:py-4 font-semibold text-[#15803D] whitespace-nowrap">Bukti
                            Pembayaran</th>
                        <th class="px-4 py-3 sm:px-5 sm:py-4 font-semibold text-[#15803D] whitespace-nowrap">Status</th>
                        <th
                            class="px-4 py-3 sm:px-5 sm:py-4 font-semibold text-[#15803D] text-center whitespace-nowrap">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($pendingPayments as $payment)
                    <tr class="hover:bg-[#F9FDF7] transition duration-200">
                        <td class="px-4 py-3 sm:px-5 sm:py-4 font-mono text-[#111827] whitespace-nowrap">
                            #NB-{{ str_pad($payment->order->id, 4, '0', STR_PAD_LEFT) }}-{{ str_pad($payment->id, 4,
                            '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3 sm:px-5 sm:py-4 text-[#111827] whitespace-nowrap">{{
                            $payment->order->user->name }}</td>
                        <td class="px-4 py-3 sm:px-5 sm:py-4 font-semibold text-[#111827] whitespace-nowrap">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 sm:px-5 sm:py-4 whitespace-nowrap">
                            @if($payment->payment_proof)
                            <button onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')"
                                class="text-[#2563EB] hover:text-[#1E40AF] hover:underline inline-flex items-center gap-1 transition text-[10px] sm:text-[13px]">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Lihat
                            </button>
                            @else
                            <span class="text-[#9CA3AF]">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 sm:px-5 sm:py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-[9px] sm:text-[11px] font-semibold border border-yellow-200">
                                Menunggu Konfirmasi
                            </span>
                        </td>
                        <td class="px-4 py-3 sm:px-5 sm:py-4 text-center whitespace-nowrap">
                            <button onclick="confirmPayment({{ $payment->id }})"
                                class="bg-[#FBBF24] text-white px-3 py-1 sm:px-4 sm:py-1.5 rounded-lg hover:bg-[#F59E0B] text-[10px] sm:text-[11px] font-semibold transition shadow-sm active:scale-95">
                                Proses
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 sm:px-5 sm:py-12 text-center text-[#9CA3AF]">
                            <div class="flex flex-col items-center justify-center">
                                <x-heroicon-o-check-circle class="w-8 h-8 sm:w-10 sm:h-10 mb-2 opacity-20" />
                                <p class="text-[10px] sm:text-sm">Tidak ada pembayaran yang perlu diverifikasi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
        <div class="px-4 sm:px-5 py-3 sm:py-4 border-t border-[#E5E7EB] bg-white">
            {{ $withdrawals->appends(['payments_page' => request('payments_page'), 'confirmed_page' =>
            request('confirmed_page'), 'orders_page' => request('orders_page')])->links() }}
        </div>
        @endif
    </div>

    {{-- 1. PENDING PAYMENTS TABLE --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8">
        <div class="px-4 sm:px-5 py-3 sm:py-4 bg-white border-b border-gray-100">
            <h2 class="font-bold text-sm sm:text-[16px] text-[#111827]">Persetujuan Bukti Bayar</h2>
            <p class="text-[10px] sm:text-[12px] text-[#78716C] mt-0.5 sm:mt-1">Daftar transaksi menunggu validasi
                admin.</p>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left text-xs md:text-[13px] min-w-[800px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            ID Pesanan</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Nama Pembeli</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide text-right">
                            Jumlah</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide text-center">
                            Bukti</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide text-center">
                            Status</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] text-center whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7] divide-y divide-gray-100">
                    @forelse($pendingPayments as $payment)
                    <tr class="hover:bg-green-50/40 transition duration-200">
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#111827] whitespace-nowrap">
                            #NB-{{ str_pad($payment->order->id, 4, '0', STR_PAD_LEFT) }}-{{ str_pad($payment->id, 4,
                            '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-[#111827] whitespace-nowrap">{{
                            $payment->order->user->name }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#111827] whitespace-nowrap text-right">
                            Rp{{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-center">
                            @if($payment->payment_proof)
                            <button onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')"
                                class="text-[#2563EB] hover:text-[#1E40AF] inline-flex items-center gap-1 transition bg-blue-50 px-2 py-1 rounded-md border border-blue-100 font-medium text-[10px] md:text-xs">
                                <x-heroicon-o-photo class="w-3.5 h-3.5" /> Lihat
                            </button>
                            @else
                            <span class="text-[#9CA3AF] text-[10px] italic">Kosong</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-center">
                            <span
                                class="inline-flex items-center px-2 py-0.5 md:px-2.5 md:py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-[9px] md:text-[10px] font-bold border border-yellow-200 uppercase tracking-tight">
                                Verifikasi
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            <button onclick="confirmPayment({{ $payment->id }})"
                                class="bg-[#FBBF24] text-white px-3 py-1 md:px-4 md:py-1.5 rounded-lg hover:bg-[#F59E0B] text-[10px] md:text-[11px] font-bold transition shadow-sm active:scale-95">
                                Proses
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-[#9CA3AF]">
                            <div class="flex flex-col items-center justify-center opacity-40">
                                <x-heroicon-o-check-circle class="w-8 h-8 md:w-10 md:h-10 mb-2" />
                                <p class="text-[10px] md:text-xs font-semibold uppercase tracking-widest">Tidak ada
                                    antrean</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
        <div class="px-4 sm:px-5 py-3 sm:py-4 border-t border-[#E5E7EB] bg-white">
            {{ $withdrawals->appends(['payments_page' => request('payments_page'), 'confirmed_page' =>
            request('confirmed_page'), 'orders_page' => request('orders_page')])->links() }}
        </div>
        @endif
    </div>

    {{-- 2. CONFIRMED PAYMENTS TABLE --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8">
        <div
            class="px-4 sm:px-5 py-3 sm:py-4 bg-white border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h2 class="font-bold text-sm sm:text-[16px] text-[#111827]">Histori Pembayaran</h2>
                <p class="text-[10px] sm:text-[12px] text-[#78716C] mt-0.5 sm:mt-1">Catatan transaksi yang telah
                    diproses.</p>
            </div>
            <a href="{{ route('admin.mitra.exportConfirmedPayments', $store->id) }}"
                class="px-3 sm:px-4 py-1.5 rounded-lg border border-[#D1D5DB] text-[10px] sm:text-[12px] text-[#111827] hover:bg-[#F3F4F6] transition font-bold inline-flex items-center gap-1.5 sm:gap-2 shadow-sm self-start sm:self-auto active:scale-95 bg-white">
                <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                Unduh
            </a>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left text-xs md:text-[13px] min-w-[750px] md:min-w-[800px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Tanggal</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            ID Pesanan</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide text-right">
                            Total Transaksi</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide text-center">
                            Status</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] text-center whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7] divide-y divide-gray-100">
                    @forelse($confirmedPayments as $payment)
                    <tr class="hover:bg-green-50/40 transition duration-200">
                        <td class="px-4 py-3 md:px-5 md:py-4 text-[#111827] whitespace-nowrap">
                            {{ $payment->confirmed_at ? $payment->confirmed_at->format('d/m/Y') :
                            $payment->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#111827] whitespace-nowrap">
                            #NB-{{ str_pad($payment->order->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#0F4C20] whitespace-nowrap text-right">
                            Rp{{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-center">
                            <span
                                class="inline-flex items-center px-2 py-0.5 md:px-2.5 md:py-1 rounded-full bg-[#15803D] text-white text-[9px] md:text-[10px] font-bold shadow-sm uppercase tracking-tight">
                                Confirmed
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            <a href="{{ route('admin.mitra.payments.show', ['storeId' => $store->id, 'paymentId' => $payment->id]) }}"
                                class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-lg bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm active:scale-95"
                                title="Lihat Detail">
                                <x-heroicon-s-eye class="w-3.5 h-3.5 md:w-4 md:h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-[#9CA3AF] text-[10px] md:text-sm italic">
                            Belum ada histori pembayaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($confirmedPayments->hasPages())
        <div class="px-4 sm:px-5 py-3 sm:py-4 border-t border-[#E5E7EB] bg-white">
            {{ $confirmedPayments->appends(['payments_page' => request('payments_page'), 'withdrawals_page' =>
            request('withdrawals_page'), 'orders_page' => request('orders_page')])->links() }}
        </div>
        @endif
    </div>

    {{-- 3. WITHDRAWALS TABLE --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8">
        <div
            class="px-4 sm:px-5 py-3 sm:py-4 bg-white border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h2 class="font-bold text-sm sm:text-[16px] text-[#111827]">Antrean Penarikan Saldo</h2>
                <p class="text-[10px] sm:text-[12px] text-[#78716C] mt-0.5 sm:mt-1">Permintaan transfer dana ke rekening
                    mitra.</p>
            </div>
            <a href="{{ route('admin.withdrawals.index') }}"
                class="px-3 sm:px-4 py-1.5 rounded-lg border border-[#D1D5DB] text-[10px] sm:text-[12px] text-[#111827] hover:bg-[#F3F4F6] transition font-bold self-start sm:self-auto active:scale-95 bg-white">
                Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left text-xs md:text-[13px] min-w-[750px] md:min-w-[900px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Tanggal</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            ID WD</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Bank Tujuan</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide text-right">
                            Transfer</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide text-center">
                            Status</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] text-center whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7] divide-y divide-gray-100">
                    @forelse($withdrawals as $withdrawal)
                    <tr class="hover:bg-green-50/40 transition duration-200">
                        <td class="px-4 py-3 md:px-5 md:py-4 text-[#111827] whitespace-nowrap">
                            {{ $withdrawal->requested_at->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#111827] whitespace-nowrap">
                            #WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-[#111827]">
                            <div class="text-[11px] md:text-[13px] font-bold">{{ $withdrawal->bankAccount->bank_name }}
                            </div>
                            <div class="text-[9px] md:text-[11px] text-[#6B7280] font-mono mt-0.5">{{
                                $withdrawal->bankAccount->account_number }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-right">
                            <div class="font-bold text-[#15803D]">Rp{{ number_format($withdrawal->total_received, 0,
                                ',', '.') }}</div>
                            <div class="text-[9px] md:text-[10px] text-red-500 mt-0.5 font-medium">Fee: Rp{{
                                number_format($withdrawal->admin_fee, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-center">
                            @php
                            $statusClass = match($withdrawal->status) {
                            'pending' => 'bg-[#FEF3C7] text-[#92400E] border-yellow-200',
                            'approved' => 'bg-[#DBEAFE] text-[#1E40AF] border-blue-200',
                            'completed' => 'bg-[#15803D] text-white',
                            default => 'bg-[#FEE2E2] text-[#991B1B] border-red-200',
                            };
                            $statusText = ucfirst($withdrawal->status);
                            if ($withdrawal->status == 'completed') $statusText = 'Selesai';
                            if ($withdrawal->status == 'approved') $statusText = 'Disetujui';
                            if ($withdrawal->status == 'rejected') $statusText = 'Ditolak';
                            @endphp
                            <span
                                class="inline-flex items-center px-2 py-0.5 md:px-2.5 md:py-1 rounded-full {{ $statusClass }} text-[9px] md:text-[10px] font-bold border border-transparent {{ $withdrawal->status != 'completed' ? 'border' : '' }} uppercase tracking-tight">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            @if($withdrawal->status == 'pending')
                            <button onclick="openWithdrawalModal({{ $withdrawal->id }})"
                                class="bg-[#FBBF24] text-white px-3 py-1 md:px-4 md:py-1.5 rounded-lg hover:bg-[#F59E0B] text-[10px] md:text-[11px] font-bold transition shadow-sm active:scale-95">
                                Proses
                            </button>
                            @elseif($withdrawal->status == 'completed' && $withdrawal->withdrawal_proof)
                            <button onclick="viewProof('{{ asset('storage/' . $withdrawal->withdrawal_proof) }}')"
                                class="inline-flex items-center gap-1.5 px-2 py-1 md:px-3 md:py-1.5 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white transition-all shadow-sm border border-green-200 text-[9px] md:text-[11px] font-medium active:scale-95">
                                <x-heroicon-o-photo class="w-3.5 h-3.5" />
                                Bukti
                            </button>
                            @else
                            <span class="text-[#9CA3AF] text-[10px] italic">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-[#9CA3AF] text-[10px] md:text-sm italic">
                            Belum ada permintaan pencairan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
        <div class="px-4 sm:px-5 py-3 sm:py-4 border-t border-[#E5E7EB] bg-white">
            {{ $withdrawals->appends(['payments_page' => request('payments_page'), 'confirmed_page' =>
            request('confirmed_page'), 'orders_page' => request('orders_page')])->links() }}
        </div>
        @endif
    </div>

    {{-- 4. COMPLETED ORDERS TABLE --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8">
        <div
            class="px-4 sm:px-5 py-3 sm:py-4 bg-white border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h2 class="font-bold text-sm sm:text-[16px] text-[#111827]">Rekapitulasi Pesanan</h2>
                <p class="text-[10px] sm:text-[12px] text-[#78716C] mt-0.5 sm:mt-1">Daftar pesanan yang telah diterima
                    dan selesai.</p>
            </div>
            <a href="{{ route('admin.mitra.exportCompletedOrders', $store->id) }}"
                class="bg-[#15803D] text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-[10px] sm:text-[12px] font-medium flex items-center gap-1.5 sm:gap-2 hover:bg-[#166534] transition shadow-sm active:scale-95 self-start sm:self-auto">
                <x-heroicon-s-arrow-down-tray class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                Download CSV
            </a>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left text-xs md:text-[13px] min-w-[700px] md:min-w-[800px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Tanggal</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            ID Order</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide text-right">
                            Total</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide text-center">
                            Status</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] text-center whitespace-nowrap text-[10px] md:text-[13px] uppercase tracking-wide">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7] divide-y divide-gray-100">
                    @forelse($completedOrders as $order)
                    <tr class="hover:bg-green-50/40 transition duration-200">
                        <td class="px-4 py-3 md:px-5 md:py-4 text-[#111827] whitespace-nowrap">
                            {{ $order->created_at->format('d F Y') }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#111827] whitespace-nowrap">
                            #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#0F4C20] whitespace-nowrap text-right">
                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-center">
                            <span
                                class="inline-flex items-center px-2 py-0.5 md:px-2.5 md:py-1 rounded-full bg-[#15803D] text-white text-[9px] md:text-[10px] font-bold uppercase tracking-tight shadow-sm">
                                Selesai
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            <a href="{{ route('admin.mitra.orders.show', ['storeId' => $store->id, 'orderId' => $order->id]) }}"
                                class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-lg bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm active:scale-95"
                                title="Lihat Detail">
                                <x-heroicon-s-eye class="w-3.5 h-3.5 md:w-4 md:h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-[#9CA3AF] text-[10px] md:text-sm italic">
                            Belum ada penjualan selesai
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($completedOrders->hasPages())
        <div class="px-4 sm:px-5 py-3 sm:py-4 border-t border-[#E5E7EB] bg-white">
            {{ $completedOrders->appends(['payments_page' => request('payments_page'), 'confirmed_page' =>
            request('confirmed_page'), 'withdrawals_page' => request('withdrawals_page')])->links() }}
        </div>
        @endif
    </div>
    {{-- WITHDRAWAL MODAL (Responsive) --}}
    <div id="withdrawalModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
        <div
            class="bg-white rounded-2xl sm:rounded-3xl max-w-md w-full shadow-2xl relative max-h-[90vh] overflow-y-auto transform transition-all scale-100">
            <button type="button" onclick="closeWithdrawalModal()"
                class="absolute top-3 right-3 sm:top-4 sm:right-4 text-gray-400 hover:text-gray-600 z-10 bg-white rounded-full p-1">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <div
                class="text-center pt-5 sm:pt-6 pb-3 sm:pb-4 px-4 sm:px-6 border-b border-gray-100 sticky top-0 bg-white z-10 rounded-t-2xl sm:rounded-t-3xl">
                <div
                    class="w-10 h-10 sm:w-12 sm:h-12 bg-[#DCFCE7] rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#15803D]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                        <path fill-rule="evenodd"
                            d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="text-base sm:text-[18px] font-bold text-[#111827]">Konfirmasi Pencairan Dana</h2>
                <p class="text-[10px] sm:text-[11px] text-[#78716C] mt-0.5 sm:mt-1">Verifikasi detail pencairan dan
                    unggah bukti transfer</p>
            </div>

            <form id="withdrawalForm" action="" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf
                <div class="space-y-3 mb-5">
                    <div class="bg-[#F9FAFB] px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl border border-gray-100">
                        <p class="text-[10px] text-[#78716C] mb-0.5 sm:mb-1">Mitra</p>
                        <p class="text-xs sm:text-[13px] font-semibold text-[#111827]" id="modal_mitra_name">-</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-[10px] text-[#78716C] mb-0.5 sm:mb-1">Nomor Pencairan</p>
                            <p class="text-[11px] sm:text-[12px] font-mono font-semibold text-[#111827]"
                                id="modal_withdrawal_number">-</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-[#78716C] mb-0.5 sm:mb-1">Bank</p>
                            <p class="text-[11px] sm:text-[12px] font-semibold text-[#111827]" id="modal_bank_name">-
                            </p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] text-[#78716C] mb-0.5 sm:mb-1">Nomor Rekening</p>
                        <p class="text-[11px] sm:text-[12px] font-mono font-semibold text-[#111827]"
                            id="modal_account_number">-</p>
                        <p class="text-[10px] sm:text-[11px] text-[#78716C] mt-0.5 truncate">a.n. <span
                                id="modal_account_holder">-</span></p>
                    </div>

                    <hr class="border-dashed border-[#E5E7EB]">

                    <div class="bg-[#F9FAFB] px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl space-y-2">
                        <div class="flex justify-between text-[11px] sm:text-[12px]">
                            <span class="text-[#78716C]">Jumlah Pencairan:</span>
                            <span class="font-semibold text-[#111827]" id="modal_amount">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-[11px] sm:text-[12px]">
                            <span class="text-[#78716C]">Biaya Admin:</span>
                            <span class="font-semibold text-red-600" id="modal_admin_fee">Rp 0</span>
                        </div>
                        <hr class="border-dashed border-[#E5E7EB]">
                        <div class="flex justify-between text-[13px] sm:text-[14px]">
                            <span class="font-bold text-[#111827]">Dana Transfer:</span>
                            <span class="font-bold text-[#15803D]" id="modal_total_amount">Rp 0</span>
                        </div>
                    </div>

                    <div class="bg-[#FEF3C7] px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl border border-[#FCD34D]">
                        <p
                            class="text-[9px] sm:text-[10px] text-[#78716C] mb-0.5 sm:mb-1 font-semibold flex items-center gap-1">
                            <x-heroicon-s-exclamation-triangle class="w-3 h-3" /> Perhatian
                        </p>
                        <p class="text-[10px] sm:text-[11px] text-[#92400E] font-medium leading-snug">
                            Transfer sejumlah <strong id="modal_transfer_amount">Rp 0</strong> ke rekening mitra
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] text-[#78716C] mb-0.5 sm:mb-1">Tanggal Pencairan</p>
                        <p class="text-[11px] sm:text-[12px] font-semibold text-[#111827]" id="modal_withdrawal_date">-
                        </p>
                    </div>

                    <hr class="border-dashed border-[#E5E7EB]">

                    <div>
                        <label
                            class="text-[10px] sm:text-[11px] font-semibold text-[#111827] mb-1.5 sm:mb-2 block uppercase tracking-wide">
                            Upload Bukti Pencairan <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="withdrawal_proof" name="withdrawal_proof"
                            accept="image/jpeg,image/png,image/jpg" required
                            class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-xl p-1">
                        <p class="text-[9px] sm:text-[10px] text-[#78716C] mt-1">Format: JPG, PNG (Max 2MB)</p>
                    </div>

                    <div>
                        <label
                            class="text-[10px] sm:text-[11px] font-semibold text-[#111827] mb-1.5 sm:mb-2 block uppercase tracking-wide">
                            Catatan Admin (Opsional)
                        </label>
                        <textarea name="admin_notes" rows="2"
                            class="w-full px-3 py-2 border border-[#D1D5DB] rounded-lg text-[11px] sm:text-[12px] focus:ring-2 focus:ring-[#15803D] focus:border-transparent bg-gray-50 focus:bg-white transition"
                            placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>

                <div class="flex gap-3 sticky bottom-0 bg-white pt-2 pb-1 border-t border-gray-50">
                    <button type="button" onclick="closeWithdrawalModal()"
                        class="flex-1 px-4 py-2.5 sm:py-3 border border-[#D1D5DB] text-[#111827] rounded-xl text-[12px] sm:text-[13px] font-semibold hover:bg-[#F3F4F6] transition active:scale-95">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 sm:py-3 bg-[#15803D] text-white rounded-xl text-[12px] sm:text-[13px] font-semibold hover:bg-[#166534] transition active:scale-95 shadow-sm">
                        Proses Pencairan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Chart Configuration
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const chartData = @json($chartData);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: chartData.currentYearLabel.toString(),
                    data: chartData.currentYear,
                    borderColor: '#FCA5A5',
                    backgroundColor: 'rgba(252, 165, 165, 0.15)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                },
                {
                    label: chartData.lastYearLabel.toString(),
                    data: chartData.lastYear,
                    borderColor: '#C084FC',
                    backgroundColor: 'rgba(192, 132, 252, 0.15)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        font: { size: 10 },
                        color: '#78716C',
                        padding: 15,
                        usePointStyle: false
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toFixed(2) + ' juta';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 9 }, color: '#9CA3AF' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#F3F4F6', drawBorder: false },
                    ticks: { 
                        font: { size: 9 }, 
                        color: '#9CA3AF',
                        callback: function(value) {
                            return 'Rp ' + value + ' jt';
                        }
                    }
                }
            }
        }
    });

    // View Payment Proof
    function viewProof(url) {
        window.open(url, '_blank', 'width=800,height=600');
    }

    // Confirm Payment
    function confirmPayment(id) {
        if(confirm('Konfirmasi pembayaran ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/payments/${id}/confirm`;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Open Withdrawal Modal
    function openWithdrawalModal(id) {
        fetch(`/admin/withdrawals/${id}/details`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modal_mitra_name').textContent = data.store_name;
                document.getElementById('modal_withdrawal_number').textContent = '#WD-' + String(id).padStart(4, '0');
                document.getElementById('modal_bank_name').textContent = data.bank_name || '-';
                document.getElementById('modal_account_number').textContent = data.account_number || '-';
                document.getElementById('modal_account_holder').textContent = data.account_holder || '-';
                
                document.getElementById('modal_amount').textContent = 'Rp ' + data.amount.toLocaleString('id-ID');
                document.getElementById('modal_admin_fee').textContent = 'Rp ' + data.admin_fee.toLocaleString('id-ID');
                document.getElementById('modal_total_amount').textContent = 'Rp ' + data.total_received.toLocaleString('id-ID');
                document.getElementById('modal_transfer_amount').textContent = 'Rp ' + data.total_received.toLocaleString('id-ID');
                
                const today = new Date();
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                document.getElementById('modal_withdrawal_date').textContent = today.toLocaleDateString('id-ID', options);

                document.getElementById('withdrawalForm').action = `/admin/withdrawals/${id}/process`;
                document.getElementById('withdrawalModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data pencairan. Silakan coba lagi.');
            });
    }

    // Close Withdrawal Modal
    function closeWithdrawalModal() {
        document.getElementById('withdrawalModal').classList.add('hidden');
        document.getElementById('withdrawalForm').reset();
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('withdrawalModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeWithdrawalModal();
    });

    // Validate form before submit
    document.getElementById('withdrawalForm')?.addEventListener('submit', function(e) {
        const fileInput = document.getElementById('withdrawal_proof');
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Silakan upload bukti pencairan terlebih dahulu!');
            return false;
        }
    });
</script>
@endpush