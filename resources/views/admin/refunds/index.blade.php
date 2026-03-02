@extends('layouts.admin')

@section('title', 'Manajemen Refund - Nusa Belanja Admin')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 md:py-6 space-y-4 md:space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-2 md:mb-6">
        <div>
            <h1 class="text-[28px] font-bold text-[#15803D] mb-0.5 md:mb-1 leading-tight">Manajemen Refund</h1>
            <p class="text-[13px] text-[#78716C]">Kelola permintaan pengembalian dana dari pembeli</p>
        </div>
        <div class="flex gap-2 md:gap-3 w-full md:w-auto">
            {{-- ✅ Badge Menunggu Rekening --}}
            @if($needsBankInfoRefunds->total() > 0)
            <div
                class="flex-1 md:flex-none bg-yellow-50 px-4 py-2.5 md:px-5 md:py-3 rounded-xl md:rounded-2xl shadow-sm border border-yellow-300 flex flex-col justify-center">
                <p class="text-[11px] text-yellow-600 mb-0.5 md:mb-1 leading-tight">Menunggu Rekening</p>
                <p class="text-[24px] font-bold text-yellow-700 leading-none">{{ $needsBankInfoRefunds->total() }}</p>
            </div>
            @endif
            <div
                class="flex-1 md:flex-none bg-white px-4 py-2.5 md:px-5 md:py-3 rounded-xl md:rounded-2xl shadow-sm border border-[#E5E7EB] flex flex-col justify-center">
                <p class="text-[11px] text-[#78716C] mb-0.5 md:mb-1 leading-tight">Total Pending</p>
                <p class="text-[24px] font-bold text-[#DC2626] leading-none">{{ $pendingRefunds->total() }}</p>
            </div>
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div
        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 md:p-4 rounded-xl mb-4 md:mb-6 text-[13px] flex items-center gap-2.5 md:gap-3 shadow-sm">
        <svg class="w-4 h-4 md:w-5 md:h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
    <div
        class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 md:p-4 rounded-xl mb-4 md:mb-6 text-[13px] flex items-center gap-2.5 md:gap-3 shadow-sm">
        <svg class="w-4 h-4 md:w-5 md:h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
    <div
        class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 md:p-4 rounded-xl mb-4 md:mb-6 text-[13px] shadow-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ✅ SECTION 1: Menunggu Info Rekening Buyer (Seller Cancel) --}}
    @if($needsBankInfoRefunds->total() > 0)
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden mb-6 md:mb-8 border border-yellow-300">
        <div
            class="px-4 py-3 md:px-5 md:py-4 bg-yellow-50 border-b border-yellow-200 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h2 class="font-bold text-[16px] text-yellow-800 flex items-center gap-1.5"><span
                        class="text-sm">⚠️</span> Menunggu Info Rekening Pembeli</h2>
                <p class="text-xs text-yellow-600 mt-0.5 leading-relaxed">Pesanan dibatalkan seller — pembeli belum
                    mengisi rekening bank untuk refund</p>
            </div>
            <span
                class="inline-block w-fit px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-[11px] font-bold border border-yellow-300 shadow-sm">
                {{ $needsBankInfoRefunds->total() }} Menunggu
            </span>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-[13px] min-w-[800px]">
                <thead class="bg-yellow-50 border-t border-b border-yellow-200">
                    <tr class="text-left">
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-yellow-800 whitespace-nowrap">No. Refund
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-yellow-800 whitespace-nowrap">No. Order
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-yellow-800 whitespace-nowrap">Pembeli
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-yellow-800 whitespace-nowrap">Jumlah
                            Refund</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-yellow-800 whitespace-nowrap">Alasan
                            Pembatalan</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-yellow-800 whitespace-nowrap">Tanggal
                        </th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-yellow-800 whitespace-nowrap text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-yellow-50/30">
                    @forelse($needsBankInfoRefunds as $refund)
                    <tr class="border-b border-yellow-100 hover:bg-yellow-50 transition">
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-yellow-700 font-bold whitespace-nowrap">{{
                            $refund->refund_number }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#111827] whitespace-nowrap">{{
                            $refund->order->order_number }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <div class="text-[#111827] font-semibold truncate max-w-[150px] md:max-w-[200px]">{{
                                $refund->user->name }}</div>
                            <div class="text-[#6B7280] text-[11px] truncate max-w-[150px] md:max-w-[200px]">{{
                                $refund->user->email }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <div class="text-[#DC2626] font-bold">Rp {{ number_format($refund->refund_amount, 0, ',',
                                '.') }}</div>
                            <div class="text-[#6B7280] text-[10px]">Order: Rp {{ number_format($refund->order_amount, 0,
                                ',', '.') }}</div>
                            <div class="text-[#B91C1C] text-[10px]">Admin 5%: Rp {{ number_format($refund->admin_fee, 0,
                                ',', '.') }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-[#6B7280] text-[11px] max-w-[160px]">
                            <span class="line-clamp-2" title="{{ $refund->cancellation_reason }}">{{
                                $refund->cancellation_reason ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-[#111827] whitespace-nowrap">
                            {{ $refund->requested_at->format('d M Y') }}<br>
                            <span class="text-[10px] text-[#6B7280]">{{ $refund->requested_at->format('H:i') }}</span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 space-y-1.5 min-w-[130px]">
                            <div
                                class="flex items-center justify-center gap-1 px-2 py-1.5 rounded-lg bg-yellow-100 text-yellow-800 text-[11px] font-semibold border border-yellow-300 w-full text-center shadow-sm">
                                ⏳ Menunggu Rekening
                            </div>
                            <button onclick="openRejectModal({{ $refund->id }}, '{{ $refund->refund_number }}')"
                                class="bg-[#DC2626] text-white px-2 py-1.5 rounded-lg hover:bg-[#B91C1C] text-[11px] font-semibold w-full transition shadow-sm active:scale-95">
                                Tolak
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-[#9CA3AF] italic">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($needsBankInfoRefunds->hasPages())
        <div class="px-4 py-3 border-t border-yellow-200">
            {{ $needsBankInfoRefunds->appends(['pending_page' => request('pending_page'), 'processed_page' =>
            request('processed_page')])->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- ✅ SECTION 2: Refund Pending (Siap Diproses) --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden mb-6 md:mb-8 border border-[#E5E7EB]">
        <div
            class="px-4 py-3 md:px-5 md:py-4 bg-white border-b border-[#E5E7EB] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-bold text-[16px] text-[#111827]">Permintaan Refund Pending</h2>
            <span
                class="inline-block w-fit px-3 py-1 rounded-full bg-[#FEF2F2] text-[#DC2626] text-[11px] font-bold border border-[#FCA5A5] shadow-sm">
                {{ $pendingRefunds->total() }} Pending
            </span>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-[13px] min-w-[850px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">No. Refund
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">No. Order
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">Pembeli
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">Rekening
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">Jumlah
                            Refund</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">Tanggal
                            Request</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7]">
                    @forelse($pendingRefunds as $refund)
                    <tr class="border-b border-[#E5E7EB] hover:bg-white transition">
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#DC2626] font-bold whitespace-nowrap">{{
                            $refund->refund_number }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#111827] whitespace-nowrap">{{
                            $refund->order->order_number }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <div class="text-[#111827] font-semibold truncate max-w-[120px] md:max-w-[150px]">{{
                                $refund->user->name }}</div>
                            <div class="text-[#6B7280] text-[11px] truncate max-w-[120px] md:max-w-[150px]">{{
                                $refund->user->email }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <div class="text-[#111827] font-semibold">{{ $refund->bank_name ?? '-' }}</div>
                            <div class="text-[#6B7280] text-[11px] font-mono mt-0.5">{{ $refund->account_number ?? '-'
                                }}</div>
                            <div class="text-[#6B7280] text-[11px] truncate max-w-[120px]">a.n. {{
                                $refund->account_holder ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <div class="text-[#DC2626] font-bold">Rp {{ number_format($refund->refund_amount, 0, ',',
                                '.') }}</div>
                            <div class="text-[#6B7280] text-[10px]">Order: Rp {{ number_format($refund->order_amount, 0,
                                ',', '.') }}</div>
                            <div class="text-[#B91C1C] text-[10px]">Admin 5%: Rp {{ number_format($refund->admin_fee, 0,
                                ',', '.') }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-[#111827] whitespace-nowrap">
                            {{ $refund->requested_at->format('d M Y') }}<br>
                            <span class="text-[10px] text-[#6B7280]">{{ $refund->requested_at->format('H:i') }}</span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 min-w-[100px] space-y-1.5">
                            <button onclick="openProcessModal({{ $refund->id }})"
                                class="bg-[#15803D] text-white px-3 py-1.5 rounded-lg hover:bg-[#166534] text-[11px] font-semibold w-full transition shadow-sm active:scale-95">
                                Proses
                            </button>
                            <button onclick="openRejectModal({{ $refund->id }}, '{{ $refund->refund_number }}')"
                                class="bg-[#DC2626] text-white px-3 py-1.5 rounded-lg hover:bg-[#B91C1C] text-[11px] font-semibold w-full transition shadow-sm active:scale-95">
                                Tolak
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-[#9CA3AF]">
                            <div class="flex flex-col items-center justify-center opacity-40">
                                <svg class="w-10 h-10 md:w-12 md:h-12 mx-auto mb-2" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="font-semibold text-[12px] md:text-[13px] tracking-wide">Tidak ada permintaan
                                    refund pending</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendingRefunds->hasPages())
        <div class="px-4 py-3 border-t border-[#E5E7EB]">
            {{ $pendingRefunds->appends(['needs_bank_page' => request('needs_bank_page'), 'processed_page' =>
            request('processed_page')])->links() }}
        </div>
        @endif
    </div>

    {{-- ✅ SECTION 3: Riwayat Refund --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden border border-[#E5E7EB]">
        <div
            class="px-4 py-3 md:px-5 md:py-4 bg-white border-b border-[#E5E7EB] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-bold text-[16px] text-[#111827]">Riwayat Refund</h2>
            <span
                class="inline-block w-fit px-3 py-1 rounded-full bg-[#F3F4F6] text-[#374151] text-[11px] font-bold border border-[#E5E7EB] shadow-sm">
                {{ $processedRefunds->total() }} Riwayat
            </span>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-[13px] min-w-[750px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">No. Refund
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">No. Order
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">Pembeli
                        </th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-right">
                            Jumlah</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap text-center">
                            Status</th>
                        <th class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] whitespace-nowrap">Diproses
                        </th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-semibold text-[#15803D] text-center whitespace-nowrap">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7]">
                    @forelse($processedRefunds as $refund)
                    <tr class="border-b border-[#E5E7EB] hover:bg-white transition">
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#6B7280] whitespace-nowrap">{{
                            $refund->refund_number }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono text-[#111827] whitespace-nowrap">{{
                            $refund->order->order_number }}</td>
                        <td
                            class="px-4 py-3 md:px-5 md:py-4 text-[#111827] whitespace-nowrap truncate max-w-[120px] md:max-w-[150px]">
                            {{ $refund->user->name }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#111827] whitespace-nowrap text-right">Rp
                            {{ number_format($refund->refund_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            @if($refund->status === 'processed')
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#DCFCE7] text-[#166534] text-[10px] md:text-[11px] font-semibold border border-[#BBF7D0]">
                                ✓ Processed
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#FEF2F2] text-[#991B1B] text-[10px] md:text-[11px] font-semibold border border-[#FECaca]">
                                ✗ Rejected
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-[#6B7280] text-[11px] whitespace-nowrap">
                            {{ $refund->processed_at ? $refund->processed_at->format('d M Y H:i') :
                            ($refund->rejected_at ? $refund->rejected_at->format('d M Y H:i') : '-') }}<br>
                            <span class="text-[#9CA3AF]">oleh {{ $refund->processedBy->name ?? 'Admin' }}</span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            @if($refund->refund_proof)
                            <a href="{{ route('admin.refunds.viewProof', $refund->id) }}" target="_blank"
                                class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white border border-green-200 transition-all shadow-sm text-[10px] md:text-[11px] font-medium active:scale-95">
                                <x-heroicon-s-eye class="w-3.5 h-3.5" />
                                <span class="hidden sm:inline">Lihat Bukti</span>
                                <span class="sm:hidden">Bukti</span>
                            </a>
                            @else
                            <span class="text-[#9CA3AF] text-[11px]">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-[#9CA3AF] italic">
                            Belum ada riwayat refund
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($processedRefunds->hasPages())
        <div class="px-4 py-3 border-t border-[#E5E7EB]">
            {{ $processedRefunds->appends(['needs_bank_page' => request('needs_bank_page'), 'pending_page' =>
            request('pending_page')])->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ✅ Modal Proses Refund --}}
<div id="processModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-2 md:p-4 backdrop-blur-sm">
    <div
        class="bg-white rounded-2xl md:rounded-3xl max-w-md w-full shadow-2xl relative max-h-[95vh] overflow-y-auto animate-in zoom-in-95 duration-200">
        <button type="button" onclick="closeProcessModal()"
            class="absolute top-3 md:top-4 right-3 md:right-4 text-gray-400 hover:text-gray-600 z-10 bg-white rounded-full p-1 shadow-sm">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <div
            class="text-center pt-5 md:pt-6 pb-3 md:pb-4 px-4 md:px-6 border-b border-gray-100 sticky top-0 bg-white/95 backdrop-blur z-10">
            <div
                class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2 md:mb-3 shadow-sm">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                    <path fill-rule="evenodd"
                        d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="text-[16px] md:text-[18px] font-bold text-[#111827]">Proses Refund</h2>
            <p class="text-[10px] md:text-[11px] text-[#6B7280] mt-0.5">Upload bukti transfer ke pembeli</p>
        </div>

        <form id="processForm" action="" method="POST" enctype="multipart/form-data" class="p-4 md:p-6 space-y-4">
            @csrf
            <div id="refundDetails" class="space-y-3 text-[11px] md:text-[12px]"></div>

            <div>
                <label
                    class="block text-[10px] md:text-[11px] font-semibold text-[#111827] mb-1.5 uppercase tracking-wide">
                    Upload Bukti Transfer <span class="text-red-500">*</span>
                </label>
                <div id="upload_area"
                    class="border-2 border-dashed border-[#D1D5DB] rounded-xl p-4 md:p-6 text-center hover:border-[#15803D] transition cursor-pointer bg-gray-50 hover:bg-white"
                    onclick="document.getElementById('refund_proof').click()">
                    <div
                        class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm border border-gray-100">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <p class="text-[10px] md:text-[11px] text-[#111827] font-bold mb-0.5">Klik untuk upload</p>
                    <p class="text-[9px] md:text-[10px] text-[#6B7280]">JPG, PNG (Max 2MB)</p>
                    <input type="file" id="refund_proof" name="refund_proof"
                        accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" required
                        onchange="showFileName(this)">
                </div>
                <p id="file_name"
                    class="text-[9px] md:text-[10px] text-[#15803D] mt-2 hidden flex items-center gap-1 font-semibold">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="truncate"></span>
                </p>
            </div>

            <div>
                <label
                    class="block text-[10px] md:text-[11px] font-semibold text-[#111827] mb-1.5 uppercase tracking-wide">
                    Catatan Admin (Opsional)
                </label>
                <textarea name="admin_notes" rows="2"
                    class="w-full px-3 py-2 border border-[#D1D5DB] rounded-xl text-[11px] md:text-[12px] focus:ring-2 focus:ring-[#15803D] focus:border-transparent bg-gray-50 focus:bg-white transition"
                    placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            <div class="flex gap-2.5 md:gap-3 pt-2 pb-1">
                <button type="button" onclick="closeProcessModal()"
                    class="flex-1 px-3 md:px-4 py-2.5 md:py-3 border border-[#D1D5DB] text-[#111827] rounded-xl text-xs md:text-[13px] font-bold hover:bg-[#F3F4F6] transition shadow-sm active:scale-95">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-3 md:px-4 py-2.5 md:py-3 bg-[#15803D] text-white rounded-xl text-xs md:text-[13px] font-bold hover:bg-[#166534] transition shadow-md active:scale-95">
                    Proses Refund
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ✅ Modal Tolak Refund --}}
<div id="rejectModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
    <div
        class="bg-white rounded-2xl md:rounded-3xl max-w-md w-full shadow-2xl relative p-5 md:p-6 animate-in zoom-in-95 duration-200">
        <button type="button" onclick="closeRejectModal()"
            class="absolute top-3 md:top-4 right-3 md:right-4 text-gray-400 hover:text-red-600 bg-white rounded-full p-1 shadow-sm transition z-10">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <div class="text-center mb-4 md:mb-5">
            <div
                class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2 md:mb-3 shadow-sm">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="text-base md:text-[18px] font-bold text-[#111827]">Tolak Refund?</h2>
            <p id="reject_refund_number" class="text-[10px] md:text-[11px] text-[#6B7280] mt-0.5 font-mono font-bold">
            </p>
        </div>

        <form id="rejectForm" action="" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] md:text-[11px] font-bold text-[#111827] mb-1.5 uppercase tracking-wide">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea name="rejection_reason" rows="3" required
                    class="w-full px-3 md:px-4 py-2 border border-[#D1D5DB] rounded-xl text-[11px] md:text-[12px] focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-gray-50 focus:bg-white transition"
                    placeholder="Jelaskan alasan penolakan refund..."></textarea>
            </div>

            <div class="flex gap-2.5 md:gap-3 pt-2">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 px-3 md:px-4 py-2.5 md:py-3 border border-[#D1D5DB] text-[#111827] rounded-xl text-xs md:text-[13px] font-bold hover:bg-[#F3F4F6] transition shadow-sm active:scale-95">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-3 md:px-4 py-2.5 md:py-3 bg-[#DC2626] text-white rounded-xl text-xs md:text-[13px] font-bold hover:bg-[#B91C1C] transition shadow-md active:scale-95">
                    Tolak Refund
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // JS Script is exactly the same as requested, logic untouched
    function openProcessModal(refundId) {
        fetch(`/admin/refunds/${refundId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'needs_bank_info') {
                    alert('⚠️ Refund ini belum bisa diproses. Pembeli belum mengisi informasi rekening bank.');
                    return;
                }

                document.getElementById('refundDetails').innerHTML = `
                    <div class="bg-[#F9FAFB] px-4 py-2.5 md:py-3 rounded-xl border border-[#E5E7EB]">
                        <p class="text-[9px] md:text-[10px] text-[#6B7280] mb-0.5 uppercase tracking-widest font-bold">Nomor Refund</p>
                        <p class="font-mono text-sm md:text-base font-bold text-[#DC2626]">${data.refund_number}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white p-2 border border-gray-100 rounded-lg shadow-sm">
                            <p class="text-[9px] md:text-[10px] text-[#6B7280] mb-0.5 uppercase font-bold tracking-tight">Nomor Order</p>
                            <p class="font-mono font-bold text-[11px] md:text-sm text-[#111827] truncate">${data.order_number}</p>
                        </div>
                        <div class="bg-white p-2 border border-gray-100 rounded-lg shadow-sm">
                            <p class="text-[9px] md:text-[10px] text-[#6B7280] mb-0.5 uppercase font-bold tracking-tight">Pembeli</p>
                            <p class="font-bold text-[11px] md:text-sm text-[#111827] truncate">${data.user_name}</p>
                        </div>
                    </div>
                    <div class="bg-white p-3 border border-gray-100 rounded-lg shadow-sm">
                        <p class="text-[9px] md:text-[10px] text-[#6B7280] mb-1.5 uppercase font-bold tracking-wide">Rekening Tujuan</p>
                        <p class="font-bold text-[#111827] text-xs md:text-sm">${data.bank_name} - <span class="font-mono">${data.account_number}</span></p>
                        <p class="text-[10px] md:text-[11px] text-[#6B7280] mt-0.5">a.n. <span class="font-semibold text-gray-800">${data.account_holder}</span></p>
                    </div>
                    <hr class="border-dashed border-[#E5E7EB]">
                    <div class="bg-gradient-to-br from-[#15803D] to-[#166534] px-4 py-3.5 md:py-4 rounded-xl text-white shadow-md">
                        <p class="text-[9px] md:text-[10px] text-white/80 mb-1 uppercase font-bold tracking-widest">JUMLAH TRANSFER</p>
                        <p class="text-xl md:text-[22px] font-black">Rp ${data.refund_amount}</p>
                        <p class="text-[9px] md:text-[10px] text-white/70 mt-1.5 flex flex-col sm:flex-row gap-0.5 sm:gap-2">
                            <span>Order: Rp ${data.order_amount}</span> 
                            <span class="hidden sm:inline">|</span> 
                            <span>Admin 5%: Rp ${data.admin_fee}</span>
                        </p>
                    </div>
                    ${data.cancellation_reason !== '-' ? `
                    <div class="bg-[#FEF3C7] p-3 rounded-lg border border-[#FCD34D]">
                        <p class="text-[9px] md:text-[10px] font-black text-[#92400E] mb-1 uppercase tracking-wide">Alasan Pembatalan:</p>
                        <p class="text-[10px] md:text-[11px] text-[#92400E] font-medium leading-relaxed italic">"${data.cancellation_reason}"</p>
                    </div>
                    ` : ''}
                `;

                document.getElementById('processForm').action = `/admin/refunds/${refundId}/process`;
                document.getElementById('processModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            })
            .catch(() => {
                alert('Gagal memuat detail refund. Silakan coba lagi.');
            });
    }

    function closeProcessModal() {
        document.getElementById('processModal').classList.add('hidden');
        document.getElementById('processForm').reset();
        document.getElementById('file_name').classList.add('hidden');
        document.getElementById('refundDetails').innerHTML = '';
        document.body.style.overflow = 'auto';
    }

    function showFileName(input) {
        const fileName = input.files[0]?.name;
        if (fileName) {
            const fileNameDisplay = document.getElementById('file_name');
            fileNameDisplay.querySelector('span').textContent = fileName;
            fileNameDisplay.classList.remove('hidden');
        }
    }

    function openRejectModal(refundId, refundNumber) {
        document.getElementById('reject_refund_number').textContent = refundNumber;
        document.getElementById('rejectForm').action = `/admin/refunds/${refundId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectForm').reset();
        document.body.style.overflow = 'auto';
    }

    // Modal behavior enhancement
    document.getElementById('processModal').addEventListener('click', function(e) { if (e.target === this) closeProcessModal(); });
    document.getElementById('rejectModal').addEventListener('click', function(e) { if (e.target === this) closeRejectModal(); });
</script>
@endpush