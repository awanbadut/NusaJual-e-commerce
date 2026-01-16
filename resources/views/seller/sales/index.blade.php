@extends('layouts.seller')

@section('title', 'Riwayat Penjualan')
@section('page-title', 'Riwayat Penjualan')
@section('page-subtitle', 'Rekam jejak pesanan yang telah selesai diproses')

@section('content')
<div class="max-w-7xl">
    <!-- Export Button -->
    <div class="flex justify-end mb-6">
        <a href="{{ route('seller.sales.export') }}" class="px-4 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Download CSV
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Total Pendapatan</span>
                </div>
                <span class="text-xs font-semibold {{ $revenueChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $revenueChange >= 0 ? '+' : '' }}{{ number_format($revenueChange, 1) }}%
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">vs bulan sebelumnya</p>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Total Pesanan</span>
                </div>
                <span class="text-xs font-semibold text-green-600">+5.5%</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
            <p class="text-xs text-gray-500 mt-1">vs bulan sebelumnya</p>
        </div>

        <!-- Average Order Value -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Rata-Rata Pendapatan</span>
                </div>
                <span class="text-xs font-semibold text-green-600">+5.5%</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
            <p class="text-xs text-gray-500 mt-1">vs bulan sebelumnya</p>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Sales Trend</h3>
            <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600">
                <option>Mingguan</option>
                <option>Bulanan</option>
                <option>Tahunan</option>
            </select>
        </div>
        
        <!-- Chart -->
        <div class="h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex items-center gap-4">
            <!-- Search -->
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari Pesanan yang terdaftar"
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
            </div>

            <!-- Category Filter -->
            <select name="category" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600" onchange="this.form.submit()">
                <option value="">Kategori</option>
                <option>Kopi</option>
                <option>Teh</option>
            </select>

            <!-- Sort -->
            <select class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600">
                <option>Urutkan Berdasarkan</option>
                <option>Tanggal Terbaru</option>
                <option>Tanggal Terlama</option>
                <option>Total Tertinggi</option>
            </select>
        </form>
    </div>

    <!-- Sales Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-green-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Belanja</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($salesItems as $item)
                <tr class="hover:bg-gray-50">
                    <!-- Order Number -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $item->order->order_number }}
                    </td>

                    <!-- Customer -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $item->order->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->order->user->email }}</p>
                        </div>
                    </td>

                    <!-- Date -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ date('d/m/y', strtotime($item->order_date)) }}
                    </td>

                    <!-- Products -->
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->quantity }} x {{ $item->product->unit }}</p>
                        </div>
                    </td>

                    <!-- Total -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        Rp {{ number_format($item->total, 0, ',', '.') }}
                    </td>

                    <!-- Action -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('seller.orders.show', $item->order_id) }}" 
                           class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-600 hover:bg-yellow-700 transition">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                        Belum ada data penjualan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($salesItems->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium">{{ $salesItems->firstItem() }}</span> sampai 
                    <span class="font-medium">{{ $salesItems->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $salesItems->total() }}</span> data
                </p>
                
                <div class="flex gap-2">
                    @if($salesItems->onFirstPage())
                        <button disabled class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">‹</button>
                    @else
                        <a href="{{ $salesItems->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">‹</a>
                    @endif

                    @if($salesItems->hasMorePages())
                        <a href="{{ $salesItems->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">›</a>
                    @else
                        <button disabled class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">›</button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Sales Chart
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            data: [250000, 180000, 400000, 280000, 650000, 550000, 720000],
            backgroundColor: [
                '#F59E0B', // Yellow
                '#FB923C', // Orange
                '#92400E', // Brown
                '#FB923C', // Orange
                '#78350F', // Dark Brown
                '#F59E0B', // Yellow
                '#78350F', // Dark Brown
            ],
            borderRadius: 4,
            barThickness: 80,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + (value / 1000) + 'k';
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection
