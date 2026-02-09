@extends('layouts.admin')

@section('title', $store->store_name . ' - Admin Nusa Belanja')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <!-- Header Mitra -->
    <div class="bg-gradient-to-br from-[#D1FAE5] to-[#DCFCE7] p-6 rounded-3xl mb-6 shadow-sm">
        <div class="flex justify-between items-start">
            <div class="flex gap-5 items-start">
                <div class="w-32 h-32 bg-[#A78BFA] rounded-2xl flex items-center justify-center text-white font-bold text-4xl shadow-md overflow-hidden">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($store->store_name, 0, 2)) }}
                    @endif
                </div>
                <div>
                    <h1 class="text-[28px] font-bold text-[#111827] mb-2">Mitra {{ $store->store_name }}</h1>
                    <p class="text-[13px] text-[#78716C] mb-3">Pantau Progress Mitra dan Rekening Bersama Disini</p>
                    
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2 text-[12px] text-[#111827]">
                            <svg class="w-4 h-4 text-[#6B7280]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $store->address }}</span>
                        </div>
                        <div class="flex items-center gap-4 text-[12px]">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-[#6B7280]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                                </svg>
                                <span class="text-[#111827]">{{ $store->products->count() }} Produk</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-[#6B7280]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                                </svg>
                                <span class="text-[#111827]">{{ number_format($totalItemsSold) }}+ Terjual</span>
                            </div>
                        </div>
                        @if($store->bankAccounts->first())
                        <div class="flex items-center gap-2 text-[12px] mt-2">
                            <span class="text-[#78716C]">Nomor Rekening</span>
                            <span class="font-semibold text-[#111827]">{{ $store->bankAccounts->first()->bank_name }}</span>
                            <span class="text-[#111827]">{{ $store->bankAccounts->first()->account_number }}</span>
                            @if($totalBankAccounts > 1)
                            <span class="text-[#15803D] underline text-[11px] cursor-pointer">
                                & {{ $totalBankAccounts - 1 }} others items
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button class="bg-[#15803D] text-white px-4 py-2 rounded-lg hover:bg-[#166534] transition text-[12px] font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Download CSV
                </button>
                @if($store->user && $store->user->instagram)
                <a href="{{ $store->user->instagram }}" target="_blank" class="bg-gradient-to-r from-[#E91E63] to-[#F06292] text-white px-4 py-2 rounded-lg text-[12px] font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    Instagram
                </a>
                @endif
                @if($store->user && $store->user->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $store->user->phone) }}" target="_blank" class="bg-[#25D366] text-white px-4 py-2 rounded-lg text-[12px] font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    Whatsapp
                </a>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-[13px] flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-[13px]">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Metrics Cards -->
    <div class="grid grid-cols-4 gap-5 mb-8">
        <div class="bg-white px-5 py-4 rounded-2xl shadow-sm border border-[#E5E7EB]">
            <p class="text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#EF4444]"></span>
                Total Penjualan
            </p>
            <p class="text-[20px] font-bold text-[#111827] leading-tight mb-1">
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
            <p class="text-[20px] font-bold text-[#111827] leading-tight mb-1">
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
            <p class="text-[20px] font-bold text-[#111827] leading-tight mb-1">
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
            <p class="text-[10px] text-[#78716C] mb-0.5">Dana Teralokasi</p>
            <p class="text-[14px] font-bold text-[#F97316] leading-tight">
                Rp {{ number_format($danaTeralokasi, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white px-6 py-5 rounded-2xl shadow-sm mb-8 border border-[#E5E7EB]">
        <h2 class="font-bold text-[16px] text-[#111827] mb-4">Progress Penjualan</h2>
        <div style="height: 280px;">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

    <!-- Tabel Verifikasi Pembayaran -->
    <div class="bg-white px-6 py-5 rounded-2xl shadow-sm mb-8 border border-[#E5E7EB]">
        <h2 class="font-bold text-[16px] text-[#111827] mb-4">Verifikasi Pembayaran Penjualan</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-[12px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-4 py-3 font-semibold text-[#15803D]">ID Pesanan</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Nama Pembeli</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Jumlah Pembayaran</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Bukti Pembayaran</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Status</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7]">
                    @forelse($pendingPayments as $payment)
                    <tr class="border-b border-[#E5E7EB] hover:bg-white">
                        <td class="px-4 py-3 font-mono text-[#111827]">#NB-{{ str_pad($payment->order->id, 4, '0', STR_PAD_LEFT) }}-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 text-[#111827]">{{ $payment->order->user->name }}</td>
                        <td class="px-4 py-3 font-semibold text-[#111827]">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($payment->payment_proof)
                            <button onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')" 
                                    class="text-[#2563EB] hover:underline inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                Lihat
                            </button>
                            @else
                            <span class="text-[#9CA3AF]">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-[11px] font-semibold">
                                Menunggu Konfirmasi
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="confirmPayment({{ $payment->id }})" 
                                    class="bg-[#FBBF24] text-white px-4 py-1.5 rounded-lg hover:bg-[#F59E0B] text-[11px] font-semibold">
                                Proses
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-[#9CA3AF]">
                            Tidak ada pembayaran yang perlu diverifikasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pendingPayments->hasPages())
        <div class="mt-4 text-center text-[11px] text-[#78716C]">
            Menampilkan <span class="font-semibold">{{ $pendingPayments->firstItem() }}</span> sampai 
            <span class="font-semibold">{{ $pendingPayments->lastItem() }}</span> dari 
            <span class="font-semibold">{{ $pendingPayments->total() }}</span> data
        </div>
        @endif
    </div>

    <!-- Tabel Pencairan Dana -->
    <div class="bg-white px-6 py-5 rounded-2xl shadow-sm mb-8 border border-[#E5E7EB]">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-[16px] text-[#111827]">Permintaan Pencairan Dana</h2>
            <a href="{{ route('admin.withdrawals.index') }}" class="px-4 py-1.5 rounded-lg border border-[#D1D5DB] text-[12px] text-[#111827] hover:bg-[#F3F4F6]">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[12px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Tanggal Request</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">ID Pencairan</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Bank</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Jumlah</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Biaya Admin</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Dana Transfer</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Status</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7]">
                    @forelse($withdrawals as $withdrawal)
                    <tr class="border-b border-[#E5E7EB] hover:bg-white">
                        <td class="px-4 py-3 text-[#111827]">
                            {{ $withdrawal->requested_at->format('d F Y') }}
                        </td>
                        <td class="px-4 py-3 font-mono text-[#111827]">#WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 text-[#111827]">
                            <div class="text-[12px] font-semibold">{{ $withdrawal->bankAccount->bank_name }}</div>
                            <div class="text-[10px] text-[#78716C]">{{ $withdrawal->bankAccount->account_number }}</div>
                        </td>
                        <td class="px-4 py-3 font-semibold text-[#111827]">
                            Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-red-600 font-semibold">
                            -Rp {{ number_format($withdrawal->admin_fee, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 font-bold text-[#15803D]">
                            Rp {{ number_format($withdrawal->total_received, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($withdrawal->status == 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-[11px] font-semibold">
                                Pending
                            </span>
                            @elseif($withdrawal->status == 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#DBEAFE] text-[#1E40AF] text-[11px] font-semibold">
                                Disetujui
                            </span>
                            @elseif($withdrawal->status == 'completed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#15803D] text-white text-[11px] font-semibold">
                                Selesai
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FEE2E2] text-[#991B1B] text-[11px] font-semibold">
                                Ditolak
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($withdrawal->status == 'pending')
                            <button onclick="openWithdrawalModal({{ $withdrawal->id }})" 
                                    class="bg-[#FBBF24] text-white px-4 py-1.5 rounded-lg hover:bg-[#F59E0B] text-[11px] font-semibold">
                                Proses
                            </button>
                            @elseif($withdrawal->status == 'completed' && $withdrawal->withdrawal_proof)
                            <button onclick="viewProof('{{ asset('storage/' . $withdrawal->withdrawal_proof) }}')" 
                                    class="text-[#2563EB] hover:underline text-[11px] inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                Lihat Bukti
                            </button>
                            @else
                            <span class="text-[#9CA3AF] text-[11px]">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-[#9CA3AF]">
                            Belum ada permintaan pencairan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($withdrawals->hasPages())
        <div class="mt-4 text-center text-[11px] text-[#78716C]">
            Menampilkan <span class="font-semibold">{{ $withdrawals->firstItem() }}</span> sampai 
            <span class="font-semibold">{{ $withdrawals->lastItem() }}</span> dari 
            <span class="font-semibold">{{ $withdrawals->total() }}</span> data
        </div>
        @endif
    </div>

    <!-- Laporan Pembayaran -->
    <div class="bg-white px-6 py-5 rounded-2xl shadow-sm mb-8 border border-[#E5E7EB]">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-[16px] text-[#111827]">Laporan Pembayaran</h2>
            <button class="px-4 py-1.5 rounded-lg border border-[#D1D5DB] text-[12px] text-[#111827] hover:bg-[#F3F4F6]">
                Unduh Berdasarkan
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[12px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Tanggal Transaksi</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">ID Pesanan</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Total Transaksi</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Status</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7]">
                    @forelse($confirmedPayments as $payment)
                    <tr class="border-b border-[#E5E7EB] hover:bg-white">
                        <td class="px-4 py-3 text-[#111827]">{{ $payment->confirmed_at ? $payment->confirmed_at->format('d F Y') : $payment->created_at->format('d F Y') }}</td>
                        <td class="px-4 py-3 font-mono text-[#111827]">#NB-{{ str_pad($payment->order->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 font-semibold text-[#111827]">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#15803D] text-white text-[11px] font-semibold">
                                Terkonfirmasi
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button class="text-[#2563EB] hover:underline text-[11px]">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-[#9CA3AF]">
                            Belum ada pembayaran terkonfirmasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($confirmedPayments->hasPages())
        <div class="mt-4 text-center text-[11px] text-[#78716C]">
            Menampilkan <span class="font-semibold">{{ $confirmedPayments->firstItem() }}</span> sampai 
            <span class="font-semibold">{{ $confirmedPayments->lastItem() }}</span> dari 
            <span class="font-semibold">{{ $confirmedPayments->total() }}</span> data
        </div>
        @endif
    </div>

    <!-- Laporan Penjualan -->
    <div class="bg-white px-6 py-5 rounded-2xl shadow-sm border border-[#E5E7EB]">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-[16px] text-[#111827]">Laporan Penjualan</h2>
            <button class="bg-[#15803D] text-white px-4 py-2 rounded-lg text-[12px] font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Download CSV
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[12px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Tanggal Pembelian</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">ID Order</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Jumlah Dana</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Status</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7]">
                    @forelse($completedOrders as $order)
                    <tr class="border-b border-[#E5E7EB] hover:bg-white">
                        <td class="px-4 py-3 text-[#111827]">{{ $order->created_at->format('d F Y') }}</td>
                        <td class="px-4 py-3 font-mono text-[#111827]">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 font-semibold text-[#111827]">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#15803D] text-white text-[11px] font-semibold">
                                Selesai
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button class="inline-flex items-center gap-1 text-[#15803D] hover:underline text-[11px]">
                                Detail
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                    <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-[#9CA3AF]">
                            Belum ada penjualan selesai
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($completedOrders->hasPages())
        <div class="mt-4 text-center text-[11px] text-[#78716C]">
            Menampilkan <span class="font-semibold">{{ $completedOrders->firstItem() }}</span> sampai 
            <span class="font-semibold">{{ $completedOrders->lastItem() }}</span> dari 
            <span class="font-semibold">{{ $completedOrders->total() }}</span> data
        </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Pencairan Dana -->
    <div id="withdrawalModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <!-- Close Button -->
            <button type="button" onclick="closeWithdrawalModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>

            <!-- Modal Header -->
            <div class="text-center pt-6 pb-4 px-6 border-b border-gray-100 sticky top-0 bg-white rounded-t-3xl z-10">
                <div class="w-12 h-12 bg-[#DCFCE7] rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-[#15803D]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-[18px] font-bold text-[#111827]">Konfirmasi Pencairan Dana</h2>
                <p class="text-[11px] text-[#78716C] mt-1">Verifikasi detail pencairan dan unggah bukti transfer</p>
            </div>

            <!-- Modal Body -->
            <form id="withdrawalForm" action="" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <div class="space-y-3 mb-5">
                    <!-- Mitra Name -->
                    <div class="bg-[#F9FAFB] px-4 py-3 rounded-xl">
                        <p class="text-[10px] text-[#78716C] mb-1">Mitra</p>
                        <p class="text-[13px] font-semibold text-[#111827]" id="modal_mitra_name">-</p>
                    </div>

                    <!-- Nomor Pencairan & Bank -->
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

                    <!-- Nomor Rekening & Pemilik -->
                    <div>
                        <p class="text-[10px] text-[#78716C] mb-1">Nomor Rekening</p>
                        <p class="text-[12px] font-mono font-semibold text-[#111827]" id="modal_account_number">-</p>
                        <p class="text-[11px] text-[#78716C] mt-0.5">a.n. <span id="modal_account_holder">-</span></p>
                    </div>

                    <hr class="border-dashed border-[#E5E7EB]">

                    <!-- Total Breakdown -->
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

                    <!-- Tanggal Pencairan -->
                    <div>
                        <p class="text-[10px] text-[#78716C] mb-1">Tanggal Pencairan</p>
                        <p class="text-[12px] font-semibold text-[#111827]" id="modal_withdrawal_date">-</p>
                    </div>

                    <hr class="border-dashed border-[#E5E7EB]">

                    <!-- Upload Bukti Pencairan -->
                    <div>
                        <label class="text-[11px] font-semibold text-[#111827] mb-2 block">
                            Upload Bukti Pencairan <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="withdrawal_proof" name="withdrawal_proof" accept="image/jpeg,image/png,image/jpg" required
                               class="w-full px-4 py-3 border border-[#D1D5DB] rounded-xl text-[12px] focus:ring-2 focus:ring-[#15803D] focus:border-transparent">
                        <p class="text-[10px] text-[#78716C] mt-1">Format: JPG, PNG (Max 2MB)</p>
                    </div>

                    <!-- Admin Notes -->
                    <div>
                        <label class="text-[11px] font-semibold text-[#111827] mb-2 block">
                            Catatan Admin (Opsional)
                        </label>
                        <textarea name="admin_notes" rows="2" class="w-full px-3 py-2 border border-[#D1D5DB] rounded-lg text-[12px] focus:ring-2 focus:ring-[#15803D] focus:border-transparent" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex gap-3 sticky bottom-0 bg-white pt-4 pb-2">
                    <button type="button" onclick="closeWithdrawalModal()" class="flex-1 px-4 py-3 border border-[#D1D5DB] text-[#111827] rounded-xl text-[13px] font-semibold hover:bg-[#F3F4F6] transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-[#15803D] text-white rounded-xl text-[13px] font-semibold hover:bg-[#166534] transition">
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
