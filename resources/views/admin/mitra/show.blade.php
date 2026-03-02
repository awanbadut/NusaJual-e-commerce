@extends('layouts.admin')

@section('title', $store->store_name . ' - Admin Nusa Belanja')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="container mx-auto space-y-5 md:space-y-8">

    {{-- ✅ HEADER WITH EXPORT BUTTON (Compact di Mobile) --}}
    <div class="bg-gradient-to-br from-[#D1FAE5] to-[#DCFCE7] p-4 sm:p-6 rounded-2xl md:rounded-3xl shadow-sm">
        <div class="flex flex-col lg:flex-row justify-between items-start gap-4 md:gap-6">

            <div class="flex items-center sm:items-start gap-4 sm:gap-5 w-full">
                <div
                    class="w-16 h-16 sm:w-24 sm:h-24 md:w-32 md:h-32 bg-[#A78BFA] rounded-xl md:rounded-2xl flex items-center justify-center text-white font-bold text-2xl sm:text-3xl md:text-4xl shadow-md overflow-hidden flex-shrink-0">
                    @if($store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}"
                        class="w-full h-full object-cover">
                    @else
                    {{ strtoupper(substr($store->store_name, 0, 2)) }}
                    @endif
                </div>

                <div class="text-left w-full min-w-0">
                    <h1 class="text-xl sm:text-2xl md:text-[28px] font-bold text-[#111827] mb-0.5 md:mb-2 truncate">
                        Mitra {{ $store->store_name }}</h1>
                    <p class="hidden sm:block text-xs md:text-[13px] text-[#78716C] mb-3">Pantau Progress Mitra dan
                        Rekening Bersama Disini</p>

                    <div class="space-y-1.5 md:space-y-2">
                        <div class="flex items-start gap-1.5 text-[10px] md:text-xs text-[#111827]">
                            <x-heroicon-s-map-pin class="w-3.5 h-3.5 md:w-4 md:h-4 text-[#6B7280] shrink-0 mt-0.5" />
                            <span class="leading-tight line-clamp-2 md:line-clamp-none">{{ $store->address }}</span>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 text-[10px] md:text-xs">
                            <div class="flex items-center gap-1">
                                <x-heroicon-s-cube class="w-3.5 h-3.5 md:w-4 md:h-4 text-[#6B7280]" />
                                <span class="text-[#111827]">{{ $store->products->count() }} Produk</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <x-heroicon-s-shopping-bag class="w-3.5 h-3.5 md:w-4 md:h-4 text-[#6B7280]" />
                                <span class="text-[#111827]">{{ number_format($totalItemsSold) }}+ Terjual</span>
                            </div>
                        </div>

                        @if($store->bankAccounts->first())
                        <div
                            class="flex flex-wrap items-center gap-1.5 text-[10px] md:text-xs mt-1 bg-white/50 px-2 py-1 rounded-lg w-fit border border-green-50">
                            <span class="text-[#78716C]">Rekening:</span>
                            <span class="font-semibold text-[#111827]">{{ $store->bankAccounts->first()->bank_name
                                }}</span>
                            <span class="text-[#111827] font-mono">{{ $store->bankAccounts->first()->account_number
                                }}</span>
                            @if($totalBankAccounts > 1)
                            <span class="text-[#15803D] underline cursor-pointer ml-1 font-semibold"
                                title="Lihat lainnya">
                                +{{ $totalBankAccounts - 1 }} lainnya
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto mt-2 lg:mt-0">
                <a href="{{ route('admin.mitra.export', $store->id) }}"
                    class="bg-[#15803D] text-white px-3 md:px-4 py-2 rounded-lg hover:bg-[#166534] transition text-[11px] md:text-xs font-medium flex items-center justify-center gap-1.5 shadow-sm active:scale-95">
                    <x-heroicon-s-arrow-down-tray class="w-3.5 h-3.5 md:w-4 md:h-4" />
                    Laporan Lengkap
                </a>

                <div class="grid grid-cols-2 sm:flex gap-2">
                    @if($store->user && $store->user->instagram)
                    <a href="{{ $store->user->instagram }}" target="_blank"
                        class="bg-gradient-to-r from-[#E91E63] to-[#F06292] text-white px-3 md:px-4 py-2 rounded-lg text-[11px] md:text-xs font-medium flex items-center justify-center gap-1.5 shadow-sm hover:opacity-90 transition active:scale-95">
                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                        Instagram
                    </a>
                    @endif

                    @if($store->user && $store->user->phone)
                    <a href="{{ $store->whatsapp_url }}" target="_blank"
                        class="bg-[#0F4C20] text-white px-3 md:px-4 py-2 rounded-lg text-[11px] md:text-xs font-medium flex items-center justify-center gap-1.5 shadow-sm hover:bg-[#0b3a18] transition active:scale-95">
                        <img src="https://img.icons8.com/ios-filled/50/ffffff/whatsapp.png"
                            class="w-3.5 h-3.5 md:w-4 md:h-4">
                        Whatsapp
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div
        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 md:p-4 rounded-xl text-[11px] md:text-xs flex items-center gap-2 shadow-sm">
        <x-heroicon-s-check-circle class="w-4 h-4 md:w-5 md:h-5 shrink-0" />
        {{ session('success') }}
    </div>
    @endif

    {{-- ✅ STATISTICS CARDS (Mobile: 2 Columns) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5">
        <div
            class="bg-white px-4 md:px-5 py-3 md:py-4 rounded-xl md:rounded-2xl shadow-sm border border-[#E5E7EB] flex flex-col justify-between">
            <p class="text-[9px] md:text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#EF4444]"></span> Total Penjualan
            </p>
            <p class="text-base md:text-xl font-bold text-[#111827] leading-none mb-1 truncate">
                Rp{{ number_format($store->total_sales ?? 0, 0, ',', '.') }}
            </p>
            <p
                class="text-[8px] md:text-[10px] {{ $salesGrowth >= 0 ? 'text-[#15803D]' : 'text-[#EF4444]' }} font-medium">
                {{ $salesGrowth >= 0 ? '+' : '' }}{{ number_format($salesGrowth, 1) }}% vs bln lalu
            </p>
        </div>

        <div
            class="bg-white px-4 md:px-5 py-3 md:py-4 rounded-xl md:rounded-2xl shadow-sm border border-[#E5E7EB] flex flex-col justify-between">
            <p class="text-[9px] md:text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span> Total Transaksi
            </p>
            <p class="text-base md:text-xl font-bold text-[#111827] leading-none mb-1">
                {{ number_format($store->orders_count ?? 0) }}
            </p>
            <p
                class="text-[8px] md:text-[10px] {{ $ordersGrowth >= 0 ? 'text-[#15803D]' : 'text-[#EF4444]' }} font-medium">
                {{ $ordersGrowth >= 0 ? '+' : '' }}{{ number_format($ordersGrowth, 1) }}% vs bln lalu
            </p>
        </div>

        <div
            class="bg-white px-4 md:px-5 py-3 md:py-4 rounded-xl md:rounded-2xl shadow-sm border border-[#E5E7EB] flex flex-col justify-between">
            <p class="text-[9px] md:text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#3B82F6]"></span> Jml Pencairan
            </p>
            <p class="text-base md:text-xl font-bold text-[#111827] leading-none mb-1">
                {{ $totalPencairan }}
            </p>
            <p class="text-[8px] md:text-[10px] text-[#78716C] font-medium">Penarikan selesai</p>
        </div>

        <div
            class="bg-white px-4 md:px-5 py-3 md:py-4 rounded-xl md:rounded-2xl shadow-sm border border-[#E5E7EB] flex flex-col justify-between ring-1 ring-green-50">
            <p class="text-[9px] md:text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span> Dana Tersedia
            </p>
            <p class="text-base md:text-[18px] font-bold text-[#15803D] leading-none mb-2 truncate">
                Rp{{ number_format($sisaDana, 0, ',', '.') }}
            </p>
            <div class="pt-1.5 border-t border-dashed border-[#E5E7EB] flex justify-between items-center mt-auto">
                <p class="text-[8px] md:text-[10px] text-[#78716C]">Teralokasi</p>
                <p class="text-[9px] md:text-[12px] font-bold text-[#F97316]">Rp{{ number_format($danaTeralokasi, 0,
                    ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- ✅ PERFORMANCE CHART --}}
    <div class="bg-white px-4 sm:px-6 py-4 sm:py-5 rounded-xl md:rounded-2xl shadow-sm border border-[#E5E7EB]">
        <h2 class="font-bold text-sm md:text-[16px] text-[#111827] mb-3 md:mb-4">Progress Penjualan</h2>
        <div class="h-48 md:h-[280px] w-full relative">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

    {{-- ✅ PENDING PAYMENTS --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="px-4 md:px-5 py-3 md:py-4 bg-[#F9FDF7] border-b border-green-50">
            <h2 class="font-bold text-sm md:text-[16px] text-[#111827]">Persetujuan Bukti Bayar</h2>
            <p class="text-[10px] md:text-[12px] text-[#78716C] mt-0.5">Daftar transaksi menunggu validasi admin.</p>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left text-[11px] md:text-[13px] min-w-[750px] md:min-w-[900px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">ID Pesanan
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">Nama
                            Pembeli</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-right">
                            Jumlah</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-center">
                            Bukti</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-center">
                            Status</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] text-center whitespace-nowrap">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($pendingPayments as $payment)
                    <tr class="hover:bg-[#F9FDF7] transition duration-200">
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#111827] whitespace-nowrap">
                            #NB-{{ str_pad($payment->order->id, 4, '0', STR_PAD_LEFT) }}-{{ str_pad($payment->id, 4,
                            '0', STR_PAD_LEFT) }}
                        </td>
                        <td
                            class="px-4 py-3 md:px-5 md:py-4 text-[#111827] whitespace-nowrap truncate max-w-[150px] md:max-w-none">
                            {{ $payment->order->user->name }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#111827] whitespace-nowrap text-right">
                            Rp{{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-center">
                            @if($payment->payment_proof)
                            <button onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')"
                                class="text-[#2563EB] hover:text-[#1E40AF] inline-flex items-center gap-1 transition bg-blue-50 px-2 py-1 rounded-md border border-blue-100">
                                <x-heroicon-o-photo class="w-3.5 h-3.5" /> Lihat
                            </button>
                            @else
                            <span class="text-[#9CA3AF] text-[10px] italic">Kosong</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-center">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-[9px] md:text-[11px] font-semibold border border-yellow-200 uppercase tracking-tight">
                                Verifikasi
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            <button onclick="confirmPayment({{ $payment->id }})"
                                class="bg-[#FBBF24] text-white px-3 py-1.5 md:px-4 md:py-1.5 rounded-lg hover:bg-[#F59E0B] text-[10px] md:text-[11px] font-semibold transition shadow-sm active:scale-95">
                                Proses
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-[#9CA3AF]">
                            <div class="flex flex-col items-center justify-center opacity-40">
                                <x-heroicon-o-check-circle class="w-10 h-10 mb-2" />
                                <p class="text-xs font-semibold uppercase tracking-widest">Tidak ada antrean</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pendingPayments->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-white">
            {{ $pendingPayments->appends(['payments_page' => request('payments_page'), 'confirmed_page' =>
            request('confirmed_page'), 'orders_page' => request('orders_page')])->links() }}
        </div>
        @endif
    </div>

    {{-- ✅ CONFIRMED PAYMENTS HISTORY --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div
            class="px-4 md:px-5 py-3 md:py-4 bg-white border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-bold text-sm md:text-[16px] text-[#111827]">Histori Pembayaran</h2>
                <p class="text-[10px] md:text-[12px] text-[#78716C] mt-0.5">Catatan transaksi yang telah diproses.</p>
            </div>
            <a href="{{ route('admin.mitra.exportConfirmedPayments', $store->id) }}"
                class="px-3 md:px-4 py-1.5 md:py-2 rounded-lg border border-[#D1D5DB] text-[10px] md:text-[12px] text-[#111827] hover:bg-[#F3F4F6] transition font-medium inline-flex items-center gap-1.5 shadow-sm active:scale-95">
                <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5 md:w-4 md:h-4" /> Unduh
            </a>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left text-[11px] md:text-[13px] min-w-[650px] md:min-w-[800px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">Tanggal
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">ID Pesanan
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-right">
                            Total Transaksi</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-center">
                            Status</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] text-center whitespace-nowrap">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($confirmedPayments as $payment)
                    <tr class="hover:bg-[#F9FDF7] transition duration-200">
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
                                class="inline-flex items-center px-2 py-0.5 md:px-3 md:py-1 rounded-full bg-[#15803D] text-white text-[9px] md:text-[11px] font-semibold shadow-sm uppercase tracking-tight">
                                Confirmed
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            <a href="{{ route('admin.mitra.payments.show', ['storeId' => $store->id, 'paymentId' => $payment->id]) }}"
                                class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-lg bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm active:scale-95">
                                <x-heroicon-s-eye class="w-3.5 h-3.5 md:w-4 md:h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-[#9CA3AF] text-xs font-medium italic">Belum
                            ada histori pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($confirmedPayments->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-white">
            {{ $confirmedPayments->appends(['payments_page' => request('payments_page'), 'withdrawals_page' =>
            request('withdrawals_page'), 'orders_page' => request('orders_page')])->links() }}
        </div>
        @endif
    </div>

    {{-- ✅ WITHDRAWALS QUEUE --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div
            class="px-4 md:px-5 py-3 md:py-4 bg-[#F9FDF7] border-b border-green-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-bold text-sm md:text-[16px] text-[#111827]">Antrean Penarikan Saldo</h2>
                <p class="text-[10px] md:text-[12px] text-[#78716C] mt-0.5">Permintaan transfer dana ke rekening mitra.
                </p>
            </div>
            <a href="{{ route('admin.withdrawals.index') }}"
                class="px-3 md:px-4 py-1.5 md:py-2 rounded-lg border border-[#D1D5DB] bg-white text-[10px] md:text-[12px] text-[#111827] hover:bg-gray-50 transition font-medium shadow-sm active:scale-95">
                Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left text-[11px] md:text-[13px] min-w-[750px] md:min-w-[900px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">Tanggal
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">ID WD</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">Bank Tujuan
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-right">
                            Transfer</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-center">
                            Status</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] text-center whitespace-nowrap">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($withdrawals as $withdrawal)
                    <tr class="hover:bg-[#F9FDF7] transition duration-200">
                        <td class="px-4 py-3 md:px-5 md:py-4 text-[#111827] whitespace-nowrap">{{
                            $withdrawal->requested_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#111827] whitespace-nowrap">#WD-{{
                            str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-[#111827] whitespace-nowrap">
                            <div class="text-[11px] md:text-[13px] font-bold">{{ $withdrawal->bankAccount->bank_name }}
                            </div>
                            <div class="text-[9px] md:text-[11px] text-[#6B7280] font-mono">{{
                                $withdrawal->bankAccount->account_number }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-right">
                            <p class="font-bold text-[#15803D]">Rp{{ number_format($withdrawal->total_received, 0, ',',
                                '.') }}</p>
                            <p class="text-[9px] text-red-500 font-medium">Fee: Rp{{
                                number_format($withdrawal->admin_fee, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-center">
                            @php
                            $wStatus = $withdrawal->status;
                            $wClasses = [
                            'pending' => 'bg-[#FEF3C7] text-[#92400E] border-yellow-200',
                            'approved' => 'bg-[#DBEAFE] text-[#1E40AF] border-blue-200',
                            'completed' => 'bg-[#15803D] text-white border-transparent',
                            'rejected' => 'bg-[#FEE2E2] text-[#991B1B] border-red-200'
                            ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2 py-0.5 md:px-2.5 md:py-1 rounded-full text-[9px] md:text-[11px] font-semibold border uppercase tracking-tight {{ $wClasses[$wStatus] ?? 'bg-gray-100' }}">
                                {{ substr($wStatus, 0, 5) }}.
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            @if($withdrawal->status == 'pending')
                            <button onclick="openWithdrawalModal({{ $withdrawal->id }})"
                                class="bg-[#FBBF24] text-white px-3 py-1.5 md:px-4 md:py-1.5 rounded-lg hover:bg-[#F59E0B] text-[10px] md:text-[11px] font-semibold transition shadow-sm active:scale-95">
                                Proses
                            </button>
                            @elseif($withdrawal->status == 'completed' && $withdrawal->withdrawal_proof)
                            <button onclick="viewProof('{{ asset('storage/' . $withdrawal->withdrawal_proof) }}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white transition shadow-sm border border-green-200 text-[10px] font-medium active:scale-95">
                                <x-heroicon-o-photo class="w-3.5 h-3.5" /> Bukti
                            </button>
                            @else
                            <span class="text-[#9CA3AF] text-[10px] italic">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-[#9CA3AF] text-xs font-medium italic">Belum
                            ada permintaan pencairan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($withdrawals->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-white">
            {{ $withdrawals->appends(['payments_page' => request('payments_page'), 'confirmed_page' =>
            request('confirmed_page'), 'orders_page' => request('orders_page')])->links() }}
        </div>
        @endif
    </div>

    {{-- ✅ COMPLETED ORDERS --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div
            class="px-4 md:px-5 py-3 md:py-4 bg-white border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-bold text-sm md:text-[16px] text-[#111827]">Pesanan Selesai</h2>
                <p class="text-[10px] md:text-[12px] text-[#78716C] mt-0.5">Daftar pesanan yang telah diterima
                    pelanggan.</p>
            </div>
            <a href="{{ route('admin.mitra.exportCompletedOrders', $store->id) }}"
                class="bg-[#15803D] text-white px-3 md:px-4 py-1.5 md:py-2 rounded-lg text-[10px] md:text-[12px] font-medium flex items-center gap-1.5 hover:bg-[#166534] transition shadow-sm active:scale-95">
                <x-heroicon-s-arrow-down-tray class="w-3.5 h-3.5 md:w-4 md:h-4" /> Unduh CSV
            </a>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left text-[11px] md:text-[13px] min-w-[600px] md:min-w-[800px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">Tanggal
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">ID Order
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-right">
                            Total</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-center">
                            Status</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($completedOrders as $order)
                    <tr class="hover:bg-[#F9FDF7] transition duration-200">
                        <td class="px-4 py-3 md:px-5 md:py-4 text-[#111827] whitespace-nowrap">{{
                            $order->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#111827] whitespace-nowrap">#ORD-{{
                            str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#111827] whitespace-nowrap text-right">
                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-center">
                            <span
                                class="inline-flex items-center px-2 py-0.5 md:px-3 md:py-1 rounded-full bg-[#15803D] text-white text-[9px] md:text-[11px] font-semibold shadow-sm uppercase tracking-tight">
                                Selesai
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            <a href="{{ route('admin.mitra.orders.show', ['storeId' => $store->id, 'orderId' => $order->id]) }}"
                                class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-lg bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm active:scale-95">
                                <x-heroicon-s-eye class="w-3.5 h-3.5 md:w-4 md:h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-[#9CA3AF] text-xs font-medium italic">Belum
                            ada pesanan selesai</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($completedOrders->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-white">
            {{ $completedOrders->appends(['payments_page' => request('payments_page'), 'confirmed_page' =>
            request('confirmed_page'), 'withdrawals_page' => request('withdrawals_page')])->links() }}
        </div>
        @endif
    </div>
</div>

<div id="withdrawalModal"
    class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-3 md:p-4">
    <div
        class="bg-white rounded-2xl md:rounded-3xl max-w-md w-full shadow-2xl relative max-h-[90vh] overflow-y-auto animate-in zoom-in-95 duration-200">
        <button type="button" onclick="closeWithdrawalModal()"
            class="absolute top-3 md:top-4 right-3 md:right-4 text-gray-400 hover:text-gray-600 z-20 bg-white rounded-full p-1 shadow-sm">
            <x-heroicon-m-x-mark class="w-5 h-5 md:w-6 md:h-6" />
        </button>

        <div
            class="text-center pt-5 md:pt-6 pb-3 md:pb-4 px-4 md:px-6 border-b border-gray-100 sticky top-0 bg-white/95 backdrop-blur z-10">
            <div
                class="w-10 h-10 md:w-12 md:h-12 bg-[#DCFCE7] rounded-full flex items-center justify-center mx-auto mb-2 md:mb-3 shadow-sm">
                <x-heroicon-s-banknotes class="w-5 h-5 md:w-6 md:h-6 text-[#15803D]" />
            </div>
            <h2 class="text-base md:text-[18px] font-bold text-[#111827]">Konfirmasi Pencairan</h2>
            <p class="text-[10px] md:text-[11px] text-[#78716C] mt-0.5">Verifikasi detail pencairan dan unggah bukti
                transfer</p>
        </div>

        <form id="withdrawalForm" action="" method="POST" enctype="multipart/form-data" class="p-4 md:p-6 space-y-4">
            @csrf
            <div class="bg-[#F9FAFB] px-4 py-3 rounded-xl border border-gray-100">
                <p class="text-[9px] md:text-[10px] text-[#78716C] mb-1 font-bold uppercase">Mitra</p>
                <p class="text-xs md:text-[13px] font-bold text-[#111827] truncate" id="modal_mitra_name">-</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-[9px] md:text-[10px] text-[#78716C] mb-1 font-bold uppercase">No. WD</p>
                    <p class="text-xs md:text-[12px] font-mono font-bold text-[#111827]" id="modal_withdrawal_number">-
                    </p>
                </div>
                <div>
                    <p class="text-[9px] md:text-[10px] text-[#78716C] mb-1 font-bold uppercase">Bank</p>
                    <p class="text-xs md:text-[12px] font-bold text-[#111827]" id="modal_bank_name">-</p>
                </div>
            </div>

            <div>
                <p class="text-[9px] md:text-[10px] text-[#78716C] mb-1 font-bold uppercase">Nomor Rekening</p>
                <p class="text-xs md:text-[12px] font-mono font-bold text-[#111827]" id="modal_account_number">-</p>
                <p class="text-[10px] md:text-[11px] text-[#78716C] mt-0.5 italic">a.n. <span id="modal_account_holder"
                        class="font-semibold text-gray-800">-</span></p>
            </div>

            <hr class="border-dashed border-[#E5E7EB]">

            <div class="bg-[#F9FAFB] px-4 py-3 rounded-xl border border-gray-100 space-y-2">
                <div class="flex justify-between text-[11px] md:text-[12px]">
                    <span class="text-[#78716C] font-medium">Jumlah Pencairan:</span>
                    <span class="font-bold text-[#111827]" id="modal_amount">Rp 0</span>
                </div>
                <div class="flex justify-between text-[11px] md:text-[12px]">
                    <span class="text-[#78716C] font-medium">Biaya Admin:</span>
                    <span class="font-bold text-red-600" id="modal_admin_fee">Rp 0</span>
                </div>
                <hr class="border-dashed border-[#D1D5DB] my-1">
                <div class="flex justify-between text-[13px] md:text-[14px] items-center">
                    <span class="font-black text-[#111827]">Dana Transfer:</span>
                    <span class="font-black text-[#15803D]" id="modal_total_amount">Rp 0</span>
                </div>
            </div>

            <div class="bg-[#FEF3C7] px-4 py-3 rounded-xl border border-[#FCD34D]">
                <p
                    class="text-[9px] md:text-[10px] text-[#92400E] mb-1 font-black flex items-center gap-1 uppercase tracking-widest">
                    <x-heroicon-s-exclamation-triangle class="w-3.5 h-3.5" /> Perhatian
                </p>
                <p class="text-[10px] md:text-[11px] text-[#92400E] font-medium leading-relaxed">
                    Transfer sejumlah <strong class="font-black text-red-700" id="modal_transfer_amount">Rp 0</strong>
                    ke rekening mitra
                </p>
            </div>

            <div>
                <label class="text-[10px] md:text-[11px] font-bold text-[#111827] mb-2 block uppercase tracking-wide">
                    Upload Bukti <span class="text-red-500">*</span>
                </label>
                <input type="file" id="withdrawal_proof" name="withdrawal_proof" accept="image/jpeg,image/png,image/jpg"
                    required
                    class="w-full px-3 py-2 border border-[#D1D5DB] rounded-lg text-[11px] md:text-[12px] focus:ring-2 focus:ring-[#15803D] focus:border-transparent bg-gray-50 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-[#DCFCE7] file:text-[#15803D]">
            </div>

            <div>
                <label class="text-[10px] md:text-[11px] font-bold text-[#111827] mb-2 block uppercase tracking-wide">
                    Catatan Admin (Opsional)
                </label>
                <textarea name="admin_notes" rows="2"
                    class="w-full px-3 py-2 border border-[#D1D5DB] rounded-lg text-[11px] md:text-[12px] focus:ring-2 focus:ring-[#15803D] focus:border-transparent bg-gray-50 focus:bg-white transition"
                    placeholder="Catatan penolakan/persetujuan..."></textarea>
            </div>

            <div class="flex gap-3 sticky bottom-0 bg-white pt-2 pb-1 border-t border-gray-100 mt-2">
                <button type="button" onclick="closeWithdrawalModal()"
                    class="flex-1 px-4 py-2.5 border border-[#D1D5DB] text-[#111827] rounded-xl text-xs md:text-[13px] font-bold hover:bg-[#F3F4F6] transition active:scale-95 shadow-sm">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-[#15803D] text-white rounded-xl text-xs md:text-[13px] font-bold hover:bg-[#166534] transition active:scale-95 shadow-md">
                    Proses
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // JS Logic exactly same as your code...
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
                    borderColor: '#FCA5A5', backgroundColor: 'rgba(252, 165, 165, 0.15)',
                    fill: true, tension: 0.4, borderWidth: 2
                },
                {
                    label: chartData.lastYearLabel.toString(),
                    data: chartData.lastYear,
                    borderColor: '#C084FC', backgroundColor: 'rgba(192, 132, 252, 0.15)',
                    fill: true, tension: 0.4, borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'bottom', labels: { font: { size: 10 }, color: '#78716C', padding: 15, usePointStyle: false } },
                tooltip: { callbacks: { label: function(c) { return c.dataset.label + ': Rp ' + c.parsed.y.toFixed(2) + ' jt'; } } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#9CA3AF' } },
                y: { beginAtZero: true, grid: { color: '#F3F4F6', drawBorder: false }, ticks: { font: { size: 10 }, color: '#9CA3AF', callback: function(v) { return 'Rp ' + v + ' jt'; } } }
            }
        }
    });

    function viewProof(url) { window.open(url, '_blank', 'width=800,height=600'); }
    function confirmPayment(id) {
        if(confirm('Konfirmasi pembayaran ini?')) {
            const form = document.createElement('form');
            form.method = 'POST'; form.action = `/admin/payments/${id}/confirm`;
            const csrf = document.createElement('input'); csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf); document.body.appendChild(form); form.submit();
        }
    }
    function openWithdrawalModal(id) {
        fetch(`/admin/withdrawals/${id}/details`).then(r => r.json()).then(data => {
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
            document.getElementById('modal_withdrawal_date').textContent = today.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            document.getElementById('withdrawalForm').action = `/admin/withdrawals/${id}/process`;
            document.getElementById('withdrawalModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }).catch(e => { console.error(e); alert('Gagal memuat data pencairan.'); });
    }
    function closeWithdrawalModal() {
        document.getElementById('withdrawalModal').classList.add('hidden');
        document.getElementById('withdrawalForm').reset();
        document.body.style.overflow = 'auto';
    }
    document.getElementById('withdrawalModal')?.addEventListener('click', function(e) { if (e.target === this) closeWithdrawalModal(); });
    document.getElementById('withdrawalForm')?.addEventListener('submit', function(e) {
        if (!document.getElementById('withdrawal_proof').files.length) { e.preventDefault(); alert('Silakan upload bukti pencairan!'); return false; }
    });
</script>
@endpush