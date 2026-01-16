@extends('layouts.seller')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan Kamu')
@section('page-subtitle', 'Pantau pendapatan dan dana yang telah dicairkan')

@section('content')
<div class="max-w-7xl">
    <!-- Action Buttons -->
    <div class="flex items-center justify-between mb-6">
        <!-- Month Filter -->
        <select class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600">
            <option>Bulan Ini</option>
            <option>Bulan Lalu</option>
            <option>3 Bulan Terakhir</option>
        </select>

        <!-- Pengaturan Rekening Button -->
        <a href="#" class="px-4 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Pengaturan Rekening
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Total Pendapatan</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>

        <!-- Disbursed -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Dana Dicairkan</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($disbursedAmount, 0, ',', '.') }}</p>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Dana Belum Cair</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($pendingAmount, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Laporan Pencairan</h3>
                
                <div class="flex items-center gap-3">
                    <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600">
                        <option>Semua Status</option>
                        <option>Diterima</option>
                        <option>Diproses</option>
                        <option>Pending</option>
                    </select>
                    
                    <button class="px-4 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download CSV
                    </button>
                </div>
            </div>
        </div>

        <table class="w-full">
            <thead class="bg-green-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal Pencairan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal Transaksi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah Dana</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bukti Pencairan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <!-- Disbursement Date -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ date('d F Y', strtotime($payment->disbursement_date)) }}
                    </td>

                    <!-- Transaction Date -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ date('d F Y', strtotime($payment->transaction_date)) }}
                    </td>

                    <!-- Amount -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($payment->status == 'disbursed')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white">Diterima</span>
                        @elseif($payment->status == 'processing')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500 text-gray-900">Diproses</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-600 text-white">Pending</span>
                        @endif
                    </td>

                    <!-- Proof -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button class="text-yellow-700 font-medium text-sm flex items-center gap-1 hover:underline">
                            Lihat Bukti
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                        Belum ada data pembayaran
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium">{{ $payments->firstItem() }}</span> sampai 
                    <span class="font-medium">{{ $payments->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $payments->total() }}</span> data
                </p>
                
                <div class="flex gap-2">
                    @if($payments->onFirstPage())
                        <button disabled class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">‹</button>
                    @else
                        <a href="{{ $payments->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">‹</a>
                    @endif

                    @if($payments->hasMorePages())
                        <a href="{{ $payments->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">›</a>
                    @else
                        <button disabled class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">›</button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
