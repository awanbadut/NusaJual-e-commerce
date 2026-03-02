@extends('layouts.admin')

@section('title', 'Dashboard Admin - Nusa Belanja')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="container mx-auto px-2 sm:px-6 py-4 sm:py-8 space-y-4 md:space-y-8">

    <div class="mb-2 md:mb-8">
        <h1 class="text-xl md:text-[28px] font-black text-[#15803D] mb-0.5 md:mb-1 tracking-tight">Dashboard</h1>
        <p class="text-[10px] md:text-[13px] text-[#78716C] font-medium">Pantau Pendapatan dan Progress Nusa Belanja</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6">
        <div
            class="bg-white rounded-xl md:rounded-2xl shadow-sm p-4 md:p-6 border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-start justify-between mb-2">
                <div class="min-w-0">
                    <p
                        class="text-[9px] md:text-[12px] font-semibold text-gray-400 uppercase tracking-widest mb-1 truncate">
                        Mitra</p>
                    <p class="text-xl md:text-[32px] font-black text-gray-900 leading-none">
                        {{ number_format($totalMitra) }}
                    </p>
                </div>
                <div
                    class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <x-heroicon-s-building-storefront class="w-4 h-4 md:w-6 md:h-6 text-blue-600" />
                </div>
            </div>
            <p class="text-[9px] md:text-[11px] text-gray-400 mt-2 font-medium">Toko terdaftar aktif</p>
        </div>

        <div
            class="bg-white rounded-xl md:rounded-2xl shadow-sm p-4 md:p-6 border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-start justify-between mb-2">
                <div class="min-w-0">
                    <p
                        class="text-[9px] md:text-[12px] font-semibold text-gray-400 uppercase tracking-widest mb-1 truncate">
                        Pesanan</p>
                    <p class="text-xl md:text-[32px] font-black text-gray-900 leading-none">
                        {{ number_format($totalOrders) }}
                    </p>
                </div>
                <div
                    class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                    <x-heroicon-s-shopping-bag class="w-4 h-4 md:w-6 md:h-6 text-orange-600" />
                </div>
            </div>
            <p class="text-[9px] md:text-[11px] text-gray-400 mt-2 font-medium">Transaksi berhasil</p>
        </div>

        <div
            class="col-span-2 lg:col-span-1 bg-white rounded-xl md:rounded-2xl shadow-sm p-4 md:p-6 border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-start justify-between mb-2">
                <div class="min-w-0">
                    <p class="text-[9px] md:text-[12px] font-semibold text-gray-400 uppercase tracking-widest mb-1">
                        Total
                        Pendapatan</p>
                    <p class="text-xl md:text-[32px] font-black text-[#0F4C20] leading-none truncate">
                        @if($totalSales >= 1000000000)
                        Rp{{ number_format($totalSales / 1000000000, 1) }}M
                        @elseif($totalSales >= 1000000)
                        Rp{{ number_format($totalSales / 1000000, 0) }}jt
                        @else
                        Rp{{ number_format($totalSales, 0, ',', '.') }}
                        @endif
                    </p>
                </div>
                <div
                    class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                    <x-heroicon-s-banknotes class="w-4 h-4 md:w-6 md:h-6 text-green-600" />
                </div>
            </div>
            <p class="text-[9px] md:text-[11px] text-gray-400 mt-2 font-medium">Akumulasi seluruh transaksi</p>
        </div>
    </div>

    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm p-4 md:p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <div>
                <h2 class="text-sm md:text-lg font-bold text-gray-900 tracking-tight">Tren Penjualan</h2>
                <p class="text-[9px] md:text-[12px] text-gray-400 font-medium">Grafik pergerakan produk mitra</p>
            </div>
            <div class="p-1.5 bg-gray-50 rounded-lg text-gray-400">
                <x-heroicon-o-chart-bar class="w-4 h-4 md:w-5 md:h-5" />
            </div>
        </div>
        <div class="h-56 md:h-80 w-full relative">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 md:px-6 py-4 border-b border-gray-100 bg-[#F9FDF7] flex justify-between items-center">
            <h2 class="text-sm md:text-lg font-bold text-gray-900 tracking-tight">Transaksi Terbaru</h2>
            <span
                class="px-2 py-0.5 rounded-full bg-white text-green-700 text-[9px] md:text-[10px] font-black border border-green-100 shadow-sm">
                {{ $recentTransactions->count() }} Data
            </span>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left whitespace-nowrap min-w-[650px] md:min-w-[800px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] text-[10px] md:text-[12px] uppercase tracking-widest">
                            Tanggal</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] text-[10px] md:text-[12px] uppercase tracking-widest">
                            Nama Mitra</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] text-[10px] md:text-[12px] uppercase tracking-widest text-center">
                            Items</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] text-[10px] md:text-[12px] uppercase tracking-widest text-right">
                            Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-xs md:text-sm">
                    @forelse($recentTransactions as $transaction)
                    <tr class="hover:bg-[#F9FDF7] transition duration-150">
                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <p class="font-bold text-gray-900">{{ $transaction->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $transaction->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 md:w-9 md:h-9 rounded-full flex items-center justify-center text-white font-black text-[10px] shrink-0 shadow-sm"
                                    style="background-color: {{ '#' . substr(md5($transaction->store->store_name), 0, 6) }}">
                                    {{ strtoupper(substr($transaction->store->store_name, 0, 2)) }}
                                </div>
                                <span class="font-bold text-gray-800 truncate max-w-[150px] md:max-w-[200px]">{{
                                    $transaction->store->store_name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center">
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-700 text-[10px] font-bold border border-yellow-100">
                                {{ $transaction->items->count() }} Item
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-black text-[#15803D] text-right">
                            Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center justify-center opacity-30">
                                <x-heroicon-o-inbox class="w-12 h-12 mb-2" />
                                <p class="text-sm font-bold uppercase tracking-widest">Belum ada transaksi</p>
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
                        font: { size: window.innerWidth < 640 ? 9 : 11, weight: 'bold' },
                        color: '#78716C',
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 76, 32, 0.9)',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 12 },
                    displayColors: false,
                    callbacks: {
                        label: (context) => context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID') + ' Jt'
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
                    grid: { color: '#F3F4F6', borderDash: [4, 4] },
                    ticks: { 
                        font: { size: 9 }, 
                        color: '#9CA3AF',
                        callback: (value) => 'Rp' + value + 'jt'
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