@extends('layouts.admin')

@section('title', 'Dashboard Admin - Nusa Belanja')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-[28px] font-bold text-[#15803D] mb-1">Dashboard</h1>
        <p class="text-[13px] text-[#78716C]">Pantau Pendapatan dan Progress Nusa Belanja</p>
    </div>

    <!-- Cards Metrics - 100% Real Data -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm px-6 py-5 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[12px] text-[#78716C] mb-2">Total Mitra</p>
                    <p class="text-[32px] font-bold text-[#111827] leading-none">
                        {{ number_format($totalMitra) }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[#E0F2FE] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#0BA95B]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm px-6 py-5 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[12px] text-[#78716C] mb-2">Total Penjualan</p>
                    <p class="text-[32px] font-bold text-[#111827] leading-none">
                        @if($totalSales >= 1000000000)
                            Rp {{ number_format($totalSales / 1000000000, 1) }}M
                        @elseif($totalSales >= 1000000)
                            Rp {{ number_format($totalSales / 1000000, 0) }}jt
                        @else
                            Rp {{ number_format($totalSales, 0, ',', '.') }}
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[#FEF3C7] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#F59E0B]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm px-6 py-5 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[12px] text-[#78716C] mb-2">Total Pesanan</p>
                    <p class="text-[32px] font-bold text-[#111827] leading-none">
                        {{ number_format($totalOrders) }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[#EEF2FF] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#6366F1]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Tren Penjualan - Real Data dari Database -->
    <div class="bg-white rounded-2xl shadow-sm px-6 py-5 mb-8">
        <div class="mb-5">
            <p class="text-[16px] font-bold text-[#111827] mb-1">Tren Penjualan</p>
            <p class="text-[12px] text-[#78716C]">
                Pergerakan penjualan produk mitra dari waktu ke waktu
            </p>
        </div>
        <div style="height: 320px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Tabel Transaksi Terbaru - Real Data -->
    <div class="bg-white rounded-2xl shadow-sm px-6 py-5">
        <p class="text-[16px] font-bold text-[#111827] mb-5">Transaksi Baru Baru Ini</p>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Tanggal</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Nama Mitra</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Banyak Transaksi</th>
                        <th class="px-4 py-3 font-semibold text-[#15803D]">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7]">
                    @forelse($recentTransactions as $transaction)
                    <tr class="border-b border-[#E5E7EB] hover:bg-white">
                        <td class="px-4 py-3 text-[#111827]">
                            {{ $transaction->created_at->format('d F Y') }}
                        </td>
                        <td class="px-4 py-3 text-[#111827] font-medium">
                            {{ $transaction->store->store_name }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FBBF24] text-white text-[11px] font-semibold">
                                {{ $transaction->items->count() }} Transaksi
                            </span>
                        </td>
                        <td class="px-4 py-3 font-bold text-[#111827]">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-[#9CA3AF]">
                            Belum ada transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-center text-[11px] text-[#9CA3AF]">
            Menampilkan <span class="font-semibold">1</span> sampai 
            <span class="font-semibold">{{ $recentTransactions->count() }}</span> dari 
            <span class="font-semibold">{{ $totalOrders }}</span> data
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Data chart dari database (real data)
    const chartData = @json($chartData);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: chartData
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
                        usePointStyle: true,
                        pointStyle: 'rect'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += 'Rp ' + context.parsed.y.toFixed(2) + ' juta';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { 
                        display: false 
                    },
                    ticks: { 
                        font: { size: 11 }, 
                        color: '#9CA3AF' 
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { 
                        color: '#F3F4F6',
                        drawBorder: false
                    },
                    ticks: { 
                        font: { size: 11 }, 
                        color: '#9CA3AF',
                        callback: function(value) {
                            return 'Rp ' + value + ' jt';
                        }
                    }
                }
            },
            barPercentage: 0.8,
            categoryPercentage: 0.9
        }
    });
</script>
@endpush
