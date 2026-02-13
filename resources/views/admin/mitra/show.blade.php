@extends('layouts.admin')

@section('title', $store->store_name . ' - Admin Nusa Belanja')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="bg-gradient-to-br from-[#D1FAE5] to-[#DCFCE7] p-4 sm:p-6 rounded-3xl mb-6 shadow-sm">
    <div class="flex flex-col md:flex-row justify-between items-start gap-6">
        <div class="flex flex-col sm:flex-row gap-5 items-start w-full">
            <div
                class="w-24 h-24 sm:w-32 sm:h-32 bg-[#A78BFA] rounded-2xl flex items-center justify-center text-white font-bold text-3xl sm:text-4xl shadow-md overflow-hidden flex-shrink-0 mx-auto sm:mx-0">
                @if($store->logo)
                <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}"
                    class="w-full h-full object-cover">
                @else
                {{ strtoupper(substr($store->store_name, 0, 2)) }}
                @endif
            </div>

            <div class="text-center sm:text-left w-full">
                <h1 class="text-2xl sm:text-[28px] font-bold text-[#111827] mb-1 sm:mb-2">Mitra {{ $store->store_name }}
                </h1>
                <p class="text-xs sm:text-[13px] text-[#78716C] mb-3">Pantau Progress Mitra dan Rekening Bersama Disini
                </p>

                <div class="space-y-2 flex flex-col items-center sm:items-start">
                    <div class="flex items-center gap-2 text-xs sm:text-[12px] text-[#111827]">
                        <x-heroicon-s-map-pin class="w-4 h-4 text-[#6B7280]" />
                        <span class="text-left">{{ $store->address }}</span>
                    </div>

                    <div
                        class="flex flex-wrap justify-center sm:justify-start items-center gap-4 text-xs sm:text-[12px]">
                        <div class="flex items-center gap-1.5">
                            <x-heroicon-s-cube class="w-4 h-4 text-[#6B7280]" />
                            <span class="text-[#111827]">{{ $store->products->count() }} Produk</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <x-heroicon-s-shopping-bag class="w-4 h-4 text-[#6B7280]" />
                            <span class="text-[#111827]">{{ number_format($totalItemsSold) }}+ Terjual</span>
                        </div>
                    </div>

                    @if($store->bankAccounts->first())
                    <div
                        class="flex flex-wrap justify-center sm:justify-start items-center gap-2 text-xs sm:text-[12px] mt-1 bg-white/50 px-2 py-1 rounded-lg w-fit">
                        <span class="text-[#78716C]">Rekening:</span>
                        <span class="font-semibold text-[#111827]">{{ $store->bankAccounts->first()->bank_name }}</span>
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

        <div class="flex flex-wrap justify-center sm:justify-end gap-2 w-full md:w-auto mt-4 md:mt-0">
            <button
                class="bg-[#15803D] text-white px-4 py-2 rounded-lg hover:bg-[#166534] transition text-xs sm:text-[12px] font-medium flex items-center gap-2 shadow-sm">
                <x-heroicon-s-arrow-down-tray class="w-4 h-4" />
                Download CSV
            </button>

            @if($store->user && $store->user->instagram)
            <a href="{{ $store->user->instagram }}" target="_blank"
                class="bg-gradient-to-r from-[#E91E63] to-[#F06292] text-white px-4 py-2 rounded-lg text-xs sm:text-[12px] font-medium flex items-center gap-2 shadow-sm hover:opacity-90 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                </svg>
                Instagram
            </a>
            @endif

            @if($store->user && $store->user->phone)
            <a href="{{ $store->whatsapp_url }}" target="_blank"
                class="flex items-center gap-2 text-white px-4 py-2 rounded-lg text-xs sm:text-[12px] font-medium flex items-center gap-2 shadow-sm hover:opacity-90 transition rounded-lg bg-[#0F4C20] hover:bg-[#0b3a18] shadow-sm">
                <img src="https://img.icons8.com/ios-filled/50/ffffff/whatsapp.png" class="w-5 h-5">
                Whatsapp
            </a>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<div
    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-xs sm:text-[13px] flex items-center gap-3">
    <x-heroicon-s-check-circle class="w-5 h-5" />
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
    <div class="bg-white px-5 py-4 rounded-2xl shadow-sm border border-[#E5E7EB]">
        <p class="text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-[#EF4444]"></span>
            Total Penjualan
        </p>
        <p class="text-xl sm:text-[20px] font-bold text-[#111827] leading-tight mb-1">
            Rp {{ number_format($store->total_sales ?? 0, 0, ',', '.') }}
        </p>
        <p class="text-[10px] {{ $salesGrowth >= 0 ? 'text-[#15803D]' : 'text-[#EF4444]' }}">
            {{ $salesGrowth >= 0 ? '+' : '' }}{{ number_format($salesGrowth, 1) }}% vs bulan lalu
        </p>
    </div>

    <div class="bg-white px-5 py-4 rounded-2xl shadow-sm border border-[#E5E7EB]">
        <p class="text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
            Total Transaksi
        </p>
        <p class="text-xl sm:text-[20px] font-bold text-[#111827] leading-tight mb-1">
            {{ number_format($store->orders_count ?? 0) }}
        </p>
        <p class="text-[10px] {{ $ordersGrowth >= 0 ? 'text-[#15803D]' : 'text-[#EF4444]' }}">
            {{ $ordersGrowth >= 0 ? '+' : '' }}{{ number_format($ordersGrowth, 1) }}% vs bulan lalu
        </p>
    </div>

    <div class="bg-white px-5 py-4 rounded-2xl shadow-sm border border-[#E5E7EB]">
        <p class="text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-[#3B82F6]"></span>
            Banyak Pencairan
        </p>
        <p class="text-xl sm:text-[20px] font-bold text-[#111827] leading-tight mb-1">
            {{ $totalPencairan }}
        </p>
        <p class="text-[10px] text-[#78716C]">Total penarikan selesai</p>
    </div>

    <div class="bg-white px-5 py-4 rounded-2xl shadow-sm border border-[#E5E7EB]">
        <p class="text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
            Dana Tersedia
        </p>
        <p class="text-[18px] font-bold text-[#15803D] leading-tight mb-1">
            Rp {{ number_format($sisaDana, 0, ',', '.') }}
        </p>
        <hr class="my-1.5 border-[#E5E7EB]">
        <div class="flex justify-between items-center">
            <p class="text-[10px] text-[#78716C]">Teralokasi</p>
            <p class="text-[12px] font-bold text-[#F97316]">Rp {{ number_format($danaTeralokasi, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="bg-white px-4 sm:px-6 py-5 rounded-2xl shadow-sm mb-8 border border-[#E5E7EB]">
    <h2 class="font-bold text-[16px] text-[#111827] mb-4">Progress Penjualan</h2>
    <div style="height: 280px; width: 100%;">
        <canvas id="performanceChart"></canvas>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8 border border-gray-100">
    <div class="px-5 py-4 bg-white border-b border-gray-100">
        <h2 class="font-bold text-[16px] text-[#111827]">Persetujuan Bukti Bayar Masuk</h2>
        <p class="text-[12px] text-[#78716C] mt-1">Daftar transaksi dari pembeli yang menunggu validasi admin.</p>
    </div>

    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left text-[13px] min-w-[900px]">
            <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                <tr>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">ID Pesanan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Nama Pembeli</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Jumlah Pembayaran</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Bukti Pembayaran</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Status</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($pendingPayments as $payment)
                <tr class="hover:bg-[#F9FDF7] transition duration-200">
                    <td class="px-5 py-4 font-mono text-[#111827] whitespace-nowrap">
                        #NB-{{ str_pad($payment->order->id, 4, '0', STR_PAD_LEFT) }}-{{ str_pad($payment->id, 4, '0',
                        STR_PAD_LEFT) }}
                    </td>
                    <td class="px-5 py-4 text-[#111827] whitespace-nowrap">{{ $payment->order->user->name }}</td>
                    <td class="px-5 py-4 font-semibold text-[#111827] whitespace-nowrap">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        @if($payment->payment_proof)
                        <button onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')"
                            class="text-[#2563EB] hover:text-[#1E40AF] hover:underline inline-flex items-center gap-1 transition">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
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
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-[11px] font-semibold border border-yellow-200">
                            Menunggu Konfirmasi
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center whitespace-nowrap">
                        <button onclick="confirmPayment({{ $payment->id }})"
                            class="bg-[#FBBF24] text-white px-4 py-1.5 rounded-lg hover:bg-[#F59E0B] text-[11px] font-semibold transition shadow-sm">
                            Proses
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-[#9CA3AF]">
                        <div class="flex flex-col items-center justify-center">
                            <x-heroicon-o-check-circle class="w-10 h-10 mb-2 opacity-20" />
                            <p>Tidak ada pembayaran yang perlu diverifikasi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pendingPayments->hasPages())
    <div class="px-5 py-4 border-t border-[#E5E7EB] bg-white">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-[#78716C]">
                Menampilkan <span class="font-semibold text-[#111827]">{{ $pendingPayments->firstItem() }}</span> -
                <span class="font-semibold text-[#111827]">{{ $pendingPayments->lastItem() }}</span> dari
                <span class="font-semibold text-[#111827]">{{ $pendingPayments->total() }}</span> data
            </p>

            <div class="flex gap-2">
                {{-- Previous --}}
                @if($pendingPayments->onFirstPage())
                <button disabled
                    class="px-3 py-1.5 border border-gray-200 rounded text-[11px] text-gray-400 cursor-not-allowed bg-white">
                    ‹
                </button>
                @else
                <a href="{{ $pendingPayments->previousPageUrl() }}"
                    class="px-3 py-1.5 border border-gray-300 rounded text-[11px] text-[#111827] hover:bg-gray-50 bg-white transition font-medium">
                    ‹
                </a>
                @endif

                {{-- Next --}}
                @if($pendingPayments->hasMorePages())
                <a href="{{ $pendingPayments->nextPageUrl() }}"
                    class="px-3 py-1.5 border border-gray-300 rounded text-[11px] text-[#111827] hover:bg-gray-50 bg-white transition font-medium">
                    ›
                </a>
                @else
                <button disabled
                    class="px-3 py-1.5 border border-gray-200 rounded text-[11px] text-gray-400 cursor-not-allowed bg-white">
                    ›
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8 border border-gray-100">
    <div
        class="px-5 py-4 bg-white border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="font-bold text-[16px] text-[#111827]">Histori Verifikasi Pembayaran</h2>
            <p class="text-[12px] text-[#78716C] mt-1">Catatan transaksi yang telah diproses.</p>
        </div>
        <button
            class="px-4 py-1.5 rounded-lg border border-[#D1D5DB] text-[12px] text-[#111827] hover:bg-[#F3F4F6] transition font-medium">
            Unduh Laporan
        </button>
    </div>

    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left text-[13px] min-w-[800px]">
            <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                <tr>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Tanggal Transaksi</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">ID Pesanan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Total Transaksi</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Status</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($confirmedPayments as $payment)
                <tr class="hover:bg-[#F9FDF7] transition duration-200">
                    <td class="px-5 py-4 text-[#111827] whitespace-nowrap">
                        {{ $payment->confirmed_at ? $payment->confirmed_at->format('d F Y') :
                        $payment->created_at->format('d F Y') }}
                    </td>
                    <td class="px-5 py-4 font-mono text-[#111827] whitespace-nowrap">
                        #NB-{{ str_pad($payment->order->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-5 py-4 font-bold text-[#0F4C20] whitespace-nowrap">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full bg-[#15803D] text-white text-[11px] font-semibold shadow-sm">
                            Terkonfirmasi
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center whitespace-nowrap">
                        <a href=""
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white transition-all shadow-sm">
                            <x-heroicon-s-eye class="w-4 h-4" />
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-[#9CA3AF]">
                        Belum ada histori pembayaran
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($confirmedPayments->hasPages())
    <div class="px-5 py-4 border-t border-[#E5E7EB] bg-white">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-[#78716C]">
                Menampilkan <span class="font-semibold text-[#111827]">{{ $confirmedPayments->firstItem() }}</span> -
                <span class="font-semibold text-[#111827]">{{ $confirmedPayments->lastItem() }}</span> dari
                <span class="font-semibold text-[#111827]">{{ $confirmedPayments->total() }}</span> data
            </p>

            <div class="flex gap-2">
                {{-- Previous --}}
                @if($confirmedPayments->onFirstPage())
                <button disabled
                    class="px-3 py-1.5 border border-gray-200 rounded text-[11px] text-gray-400 cursor-not-allowed bg-white">
                    ‹
                </button>
                @else
                <a href="{{ $confirmedPayments->previousPageUrl() }}"
                    class="px-3 py-1.5 border border-gray-300 rounded text-[11px] text-[#111827] hover:bg-gray-50 bg-white transition font-medium">
                    ‹
                </a>
                @endif

                {{-- Next --}}
                @if($confirmedPayments->hasMorePages())
                <a href="{{ $confirmedPayments->nextPageUrl() }}"
                    class="px-3 py-1.5 border border-gray-300 rounded text-[11px] text-[#111827] hover:bg-gray-50 bg-white transition font-medium">
                    ›
                </a>
                @else
                <button disabled
                    class="px-3 py-1.5 border border-gray-200 rounded text-[11px] text-gray-400 cursor-not-allowed bg-white">
                    ›
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8 border border-gray-100">
    <div
        class="px-5 py-4 bg-white border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="font-bold text-[16px] text-[#111827]">Antrean Penarikan Saldo</h2>
            <p class="text-[12px] text-[#78716C] mt-1">Permintaan transfer dana ke rekening mitra.</p>
        </div>
        <a href="{{ route('admin.withdrawals.index') }}"
            class="px-4 py-1.5 rounded-lg border border-[#D1D5DB] text-[12px] text-[#111827] hover:bg-[#F3F4F6] transition font-medium">
            Lihat Semua
        </a>
    </div>

    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left text-[13px] min-w-[900px]">
            <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                <tr>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Tanggal Request</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">ID Pencairan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Bank Tujuan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Jumlah</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Biaya Admin</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Total Transfer</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Status</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D] text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($withdrawals as $withdrawal)
                <tr class="hover:bg-[#F9FDF7] transition duration-200">
                    <td class="px-5 py-4 text-[#111827] whitespace-nowrap">
                        {{ $withdrawal->requested_at->format('d F Y') }}
                    </td>
                    <td class="px-5 py-4 font-mono text-[#111827] whitespace-nowrap">
                        #WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-5 py-4 text-[#111827] whitespace-nowrap">
                        <div class="text-[13px] font-bold">{{ $withdrawal->bankAccount->bank_name }}</div>
                        <div class="text-[11px] text-[#6B7280]">{{ $withdrawal->bankAccount->account_number }}</div>
                    </td>
                    <td class="px-5 py-4 font-medium text-[#111827] whitespace-nowrap">
                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 text-red-600 font-medium whitespace-nowrap">
                        -Rp {{ number_format($withdrawal->admin_fee, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 font-bold text-[#15803D] whitespace-nowrap">
                        Rp {{ number_format($withdrawal->total_received, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        @if($withdrawal->status == 'pending')
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-[11px] font-semibold border border-yellow-200">
                            Pending
                        </span>
                        @elseif($withdrawal->status == 'approved')
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#DBEAFE] text-[#1E40AF] text-[11px] font-semibold border border-blue-200">
                            Disetujui
                        </span>
                        @elseif($withdrawal->status == 'completed')
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#15803D] text-white text-[11px] font-semibold shadow-sm">
                            Selesai
                        </span>
                        @else
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#FEE2E2] text-[#991B1B] text-[11px] font-semibold border border-red-200">
                            Ditolak
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center whitespace-nowrap">
                        @if($withdrawal->status == 'pending')
                        <button onclick="openWithdrawalModal({{ $withdrawal->id }})"
                            class="bg-[#FBBF24] text-white px-4 py-1.5 rounded-lg hover:bg-[#F59E0B] text-[11px] font-semibold transition shadow-sm">
                            Proses
                        </button>
                        @elseif($withdrawal->status == 'completed' && $withdrawal->withdrawal_proof)
                        <button onclick="viewProof('{{ asset('storage/' . $withdrawal->withdrawal_proof) }}')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 text-green-700  hover:bg-green-600 hover:text-white transition-all shadow-sm border border-green-200 transition-all shadow-sm text-[11px] font-medium">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd"
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Bukti
                        </button>
                        @else
                        <span class="text-[#9CA3AF] text-[11px]">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-[#9CA3AF]">
                        Belum ada permintaan pencairan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($withdrawals->hasPages())
    <div class="px-5 py-4 border-t border-[#E5E7EB] bg-white">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-[#78716C]">
                Menampilkan <span class="font-semibold text-[#111827]">{{ $withdrawals->firstItem() }}</span> -
                <span class="font-semibold text-[#111827]">{{ $withdrawals->lastItem() }}</span> dari
                <span class="font-semibold text-[#111827]">{{ $withdrawals->total() }}</span> data
            </p>

            <div class="flex gap-2">
                {{-- Previous --}}
                @if($withdrawals->onFirstPage())
                <button disabled
                    class="px-3 py-1.5 border border-gray-200 rounded text-[11px] text-gray-400 cursor-not-allowed bg-white">
                    ‹
                </button>
                @else
                <a href="{{ $withdrawals->previousPageUrl() }}"
                    class="px-3 py-1.5 border border-gray-300 rounded text-[11px] text-[#111827] hover:bg-gray-50 bg-white transition font-medium">
                    ‹
                </a>
                @endif

                {{-- Next --}}
                @if($withdrawals->hasMorePages())
                <a href="{{ $withdrawals->nextPageUrl() }}"
                    class="px-3 py-1.5 border border-gray-300 rounded text-[11px] text-[#111827] hover:bg-gray-50 bg-white transition font-medium">
                    ›
                </a>
                @else
                <button disabled
                    class="px-3 py-1.5 border border-gray-200 rounded text-[11px] text-gray-400 cursor-not-allowed bg-white">
                    ›
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>



<div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8 border border-[#E5E7EB]">
    <div class="px-5 py-4 bg-white border-b border-[#E5E7EB] flex justify-between items-center">
        <div>
            <h2 class="font-bold text-[16px] text-[#111827]">Rekapitulasi Pesanan Selesai</h2>
            <p class="text-[12px] text-[#78716C] mt-1">Daftar pesanan yang telah diterima oleh pelanggan dan status
                transaksinya selesai.</p>
        </div>
        <button class="bg-[#15803D] text-white px-4 py-2 rounded-lg text-[12px] font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
            Download CSV
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-[13px]">
            <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                <tr class="text-left">
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Tanggal Pembelian</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">ID Order</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Jumlah Dana</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Status</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($completedOrders as $order)
                <tr class=" hover:bg-[#F9FDF7] transition duration-200 border-b">
                    <td class="px-5 py-4 text-[#111827]">{{ $order->created_at->format('d F Y') }}</td>
                    <td class="px-5 py-4 font-mono text-[#111827]">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-5 py-4 font-semibold text-[#111827]">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full bg-[#15803D] text-white text-[11px] font-semibold">
                            Selesai
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <a href=""
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white transition-all shadow-sm">
                            <x-heroicon-s-eye class="w-4 h-4" />
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-[#9CA3AF]">
                        Belum ada penjualan selesai
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($completedOrders->hasPages())
    <div class="px-5 py-4 border-t border-[#E5E7EB] bg-white">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-[#78716C]">
                Menampilkan <span class="font-semibold text-[#111827]">{{ $completedOrders->firstItem() }}</span> -
                <span class="font-semibold text-[#111827]">{{ $completedOrders->lastItem() }}</span> dari
                <span class="font-semibold text-[#111827]">{{ $completedOrders->total() }}</span> pesanan
            </p>

            <div class="flex gap-2">
                {{-- Tombol Previous --}}
                @if($completedOrders->onFirstPage())
                <button disabled
                    class="px-3 py-1.5 border border-gray-200 rounded text-[11px] text-gray-400 cursor-not-allowed bg-white">
                    ‹
                </button>
                @else
                <a href="{{ $completedOrders->previousPageUrl() }}"
                    class="px-3 py-1.5 border border-gray-300 rounded text-[11px] text-[#111827] hover:bg-gray-50 bg-white transition font-medium">
                    ‹
                </a>
                @endif

                {{-- Tombol Next --}}
                @if($completedOrders->hasMorePages())
                <a href="{{ $completedOrders->nextPageUrl() }}"
                    class="px-3 py-1.5 border border-gray-300 rounded text-[11px] text-[#111827] hover:bg-gray-50 bg-white transition font-medium">
                    ›
                </a>
                @else
                <button disabled
                    class="px-3 py-1.5 border border-gray-200 rounded text-[11px] text-gray-400 cursor-not-allowed bg-white">
                    ›
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<div id="withdrawalModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeWithdrawalModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <div class="text-center pt-6 pb-4 px-6 border-b border-gray-100 sticky top-0 bg-white rounded-t-3xl z-10">
            <div class="w-12 h-12 bg-[#DCFCE7] rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-[#15803D]" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                    <path fill-rule="evenodd"
                        d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="text-[18px] font-bold text-[#111827]">Konfirmasi Pencairan Dana</h2>
            <p class="text-[11px] text-[#78716C] mt-1">Verifikasi detail pencairan dan unggah bukti transfer</p>
        </div>

        <form id="withdrawalForm" action="" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="space-y-3 mb-5">
                <div class="bg-[#F9FAFB] px-4 py-3 rounded-xl">
                    <p class="text-[10px] text-[#78716C] mb-1">Mitra</p>
                    <p class="text-[13px] font-semibold text-[#111827]" id="modal_mitra_name">-</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-[10px] text-[#78716C] mb-1">Nomor Pencairan</p>
                        <p class="text-[12px] font-mono font-semibold text-[#111827]" id="modal_withdrawal_number">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-[#78716C] mb-1">Bank</p>
                        <p class="text-[12px] font-semibold text-[#111827]" id="modal_bank_name">-</p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] text-[#78716C] mb-1">Nomor Rekening</p>
                    <p class="text-[12px] font-mono font-semibold text-[#111827]" id="modal_account_number">-</p>
                    <p class="text-[11px] text-[#78716C] mt-0.5">a.n. <span id="modal_account_holder">-</span></p>
                </div>

                <hr class="border-dashed border-[#E5E7EB]">

                <div class="bg-[#F9FAFB] px-4 py-3 rounded-xl space-y-2">
                    <div class="flex justify-between text-[12px]">
                        <span class="text-[#78716C]">Jumlah Pencairan:</span>
                        <span class="font-semibold text-[#111827]" id="modal_amount">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-[12px]">
                        <span class="text-[#78716C]">Biaya Admin:</span>
                        <span class="font-semibold text-red-600" id="modal_admin_fee">Rp 0</span>
                    </div>
                    <hr class="border-dashed border-[#E5E7EB]">
                    <div class="flex justify-between text-[14px]">
                        <span class="font-bold text-[#111827]">Dana Transfer:</span>
                        <span class="font-bold text-[#15803D]" id="modal_total_amount">Rp 0</span>
                    </div>
                </div>

                <div class="bg-[#FEF3C7] px-4 py-3 rounded-xl border border-[#FCD34D]">
                    <p class="text-[10px] text-[#78716C] mb-1 font-semibold">⚠️ Perhatian</p>
                    <p class="text-[11px] text-[#92400E] font-medium">
                        Transfer sejumlah <strong id="modal_transfer_amount">Rp 0</strong> ke rekening mitra
                    </p>
                </div>

                <div>
                    <p class="text-[10px] text-[#78716C] mb-1">Tanggal Pencairan</p>
                    <p class="text-[12px] font-semibold text-[#111827]" id="modal_withdrawal_date">-</p>
                </div>

                <hr class="border-dashed border-[#E5E7EB]">

                <div>
                    <label class="text-[11px] font-semibold text-[#111827] mb-2 block">
                        Upload Bukti Pencairan <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="withdrawal_proof" name="withdrawal_proof"
                        accept="image/jpeg,image/png,image/jpg" required
                        class="w-full px-4 py-3 border border-[#D1D5DB] rounded-xl text-[12px] focus:ring-2 focus:ring-[#15803D] focus:border-transparent">
                    <p class="text-[10px] text-[#78716C] mt-1">Format: JPG, PNG (Max 2MB)</p>
                </div>

                <div>
                    <label class="text-[11px] font-semibold text-[#111827] mb-2 block">
                        Catatan Admin (Opsional)
                    </label>
                    <textarea name="admin_notes" rows="2"
                        class="w-full px-3 py-2 border border-[#D1D5DB] rounded-lg text-[12px] focus:ring-2 focus:ring-[#15803D] focus:border-transparent"
                        placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>
            </div>

            <div class="flex gap-3 sticky bottom-0 bg-white pt-4 pb-2">
                <button type="button" onclick="closeWithdrawalModal()"
                    class="flex-1 px-4 py-3 border border-[#D1D5DB] text-[#111827] rounded-xl text-[13px] font-semibold hover:bg-[#F3F4F6] transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-[#15803D] text-white rounded-xl text-[13px] font-semibold hover:bg-[#166534] transition">
                    Proses Pencairan
                </button>
            </div>
        </form>
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
                        font: { size: 11 },
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
                    ticks: { font: { size: 11 }, color: '#9CA3AF' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#F3F4F6', drawBorder: false },
                    ticks: { 
                        font: { size: 11 }, 
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

    // Open Withdrawal Modal (UPDATED WITH ADMIN FEE)
    function openWithdrawalModal(id) {
        fetch(`/admin/withdrawals/${id}/details`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modal_mitra_name').textContent = data.store_name;
                document.getElementById('modal_withdrawal_number').textContent = '#WD-' + String(id).padStart(4, '0');
                document.getElementById('modal_bank_name').textContent = data.bank_name || '-';
                document.getElementById('modal_account_number').textContent = data.account_number || '-';
                document.getElementById('modal_account_holder').textContent = data.account_holder || '-';
                
                // NEW: Tampilkan breakdown biaya
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