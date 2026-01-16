@extends('layouts.seller')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Kelola produk, pesanan, dan performa toko Anda dalam satu tempat')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Pendapatan -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 text-sm font-medium">Total Pendapatan</h3>
                <span class="text-2xl">💰</span>
            </div>
            <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-sm text-green-600 mt-2">↗ 12.5% <span class="text-gray-500">vs bulan lalu</span></p>
        </div>

        <!-- Pesanan Baru -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 text-sm font-medium">Pesanan Baru</h3>
                <span class="text-2xl">🛒</span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $newOrders }}</p>
            <p class="text-sm text-green-600 mt-2">↗ 5.5% <span class="text-gray-500">vs kemarin</span></p>
        </div>

        <!-- Pesanan Diproses -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 text-sm font-medium">Pesanan Diproses</h3>
                <span class="text-2xl">📦</span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $processingOrders }}</p>
            <p class="text-sm text-red-600 mt-2">↘ 5.5% <span class="text-gray-500">vs kemarin</span></p>
        </div>
    </div>

    <!-- Charts & Low Stock -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Trend Chart -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Sales Trend</h3>
                <select class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                    <option>Mingguan</option>
                    <option>Bulanan</option>
                </select>
            </div>
            <canvas id="salesChart" height="80"></canvas>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Stok Menipis</h3>
            <div class="space-y-4">
                @forelse($lowStockProducts as $product)
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gray-200 rounded flex-shrink-0 mr-3"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded {{ $product->stock <= 5 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $product->stock }} {{ $product->unit }} Tersisa
                    </span>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">Semua produk stok aman</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data untuk chart
    const salesData = @json($salesTrend);
    
    // Format data untuk Chart.js
    const labels = [];
    const data = [];
    
    // Generate labels untuk 7 hari terakhir
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        labels.push(dayNames[date.getDay()]);
        
        // Cari data untuk tanggal ini
        const dateStr = date.toISOString().split('T')[0];
        const found = salesData.find(item => item.date === dateStr);
        data.push(found ? parseFloat(found.total) : 0);
    }

    // Buat chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Penjualan (Rp)',
                data: data,
                backgroundColor: [
                    '#FCD34D', // Yellow
                    '#FB923C', // Orange
                    '#92400E', // Brown
                    '#FB923C', // Orange
                    '#92400E', // Brown dark
                    '#FCD34D', // Yellow
                    '#78350F', // Brown darkest
                ],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
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
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
