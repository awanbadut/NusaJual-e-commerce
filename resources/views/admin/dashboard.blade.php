@extends('layouts.admin')

@section('title', 'Dashboard Admin - Nusa Belanja')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-[28px] font-bold text-[#15803D] mb-1">Dashboard</h1>
        <p class="text-xs sm:text-[13px] text-[#78716C]">Pantau Pendapatan dan Progress Nusa Belanja</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-5 sm:p-6 border border-gray-100 flex items-start justify-between">
            <div>
                <p class="text-[10px] sm:text-[12px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Total
                    Mitra</p>
                <p class="text-2xl sm:text-[32px] font-bold text-gray-900 leading-none">
                    {{ number_format($totalMitra) }}
                </p>
                <p class="text-[10px] sm:text-[11px] text-gray-400 mt-2">Toko terdaftar aktif</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <x-heroicon-s-building-storefront class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" />
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 sm:p-6 border border-gray-100 flex items-start justify-between">
            <div>
                <p class="text-[10px] sm:text-[12px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Total
                    Penjualan</p>
                <p class="text-2xl sm:text-[32px] font-bold text-gray-900 leading-none">
                    @if($totalSales >= 1000000000)
                    Rp {{ number_format($totalSales / 1000000000, 1) }}M
                    @elseif($totalSales >= 1000000)
                    Rp {{ number_format($totalSales / 1000000, 0) }}jt
                    @else
                    Rp {{ number_format($totalSales, 0, ',', '.') }}
                    @endif
                </p>
                <p class="text-[10px] sm:text-[11px] text-gray-400 mt-2">Akumulasi pendapatan</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-50 flex items-center justify-center">
                <x-heroicon-s-banknotes class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" />
            </div>
        </div>

        <div
            class="bg-white rounded-2xl shadow-sm p-5 sm:p-6 border border-gray-100 flex items-start justify-between sm:col-span-2 lg:col-span-1">
            <div>
                <p class="text-[10px] sm:text-[12px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Total
                    Pesanan</p>
                <p class="text-2xl sm:text-[32px] font-bold text-gray-900 leading-none">
                    {{ number_format($totalOrders) }}
                </p>
                <p class="text-[10px] sm:text-[11px] text-gray-400 mt-2">Transaksi berhasil</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-orange-50 flex items-center justify-center">
                <x-heroicon-s-shopping-bag class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 sm:p-6 mb-6 sm:mb-8 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-sm sm:text-[16px] font-bold text-gray-900">Tren Penjualan</h2>
                <p class="text-[10px] sm:text-[12px] text-gray-500">Pergerakan penjualan produk mitra dari waktu ke
                    waktu</p>
            </div>
            <div class="p-1.5 sm:p-2 bg-gray-50 rounded-lg">
                <x-heroicon-o-chart-bar class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" />
            </div>
        </div>
        <div class="h-60 sm:h-80 w-full relative">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 sm:px-6 py-4 sm:py-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-sm sm:text-[16px] font-bold text-gray-900">Transaksi Terbaru</h2>
            <span class="px-2 sm:px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] font-bold">
                {{ $recentTransactions->count() }} Terakhir
            </span>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left text-[13px] min-w-[700px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr>
                        <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Tanggal</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Nama Mitra</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Banyak Transaksi</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D] whitespace-nowrap">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($recentTransactions as $transaction)
                    <tr class="hover:bg-[#F9FDF7] transition duration-150">
                        <td class="px-5 py-4 text-gray-900 whitespace-nowrap">
                            <span class="font-medium">{{ $transaction->created_at->format('d F Y') }}</span><br>
                            <span class="text-[11px] text-gray-500">{{ $transaction->created_at->format('H:i') }}
                                WIB</span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-[10px] sm:text-xs">
                                    {{ strtoupper(substr($transaction->store->store_name, 0, 2)) }}
                                </div>
                                <span class="font-medium text-gray-900 text-sm truncate max-w-[150px]">{{
                                    $transaction->store->store_name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-yellow-100 text-yellow-800 text-[11px] font-medium border border-yellow-200">
                                {{ $transaction->items->count() }} Item
                            </span>
                        </td>
                        <td class="px-5 py-4 font-bold text-[#15803D] whitespace-nowrap">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <x-heroicon-o-inbox class="w-10 h-10 sm:w-12 sm:h-12 text-gray-300 mb-3" />
                                <p class="text-sm">Belum ada transaksi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
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
                        font: { size: window.innerWidth < 640 ? 10 : 11 }, // Responsive font size
                        color: '#78716C',
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 10,
                    bodyFont: { size: 12 },
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.parsed.y !== null) {
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID') + ' Jt';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 }, color: '#6B7280' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#F3F4F6', borderDash: [5, 5] },
                    ticks: { 
                        font: { size: 10 }, 
                        color: '#6B7280',
                        callback: function(value) { return value + ' Jt'; }
                    }
                }
            },
            elements: {
                bar: { borderRadius: 4, borderSkipped: false }
            }
        }
    });
</script>
@endpush