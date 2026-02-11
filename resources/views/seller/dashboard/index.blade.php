@extends('layouts.seller')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Kelola produk, pesanan, dan performa toko Anda dalam satu tempat')

@section('content')
<div class="space-y-6">
    <!-- Top Stats Cards (4 columns) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Pendapatan -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-dark">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Total Pendapatan</h3>
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold mb-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-sm opacity-90">
                @if($revenueGrowth > 0)
                    <span class="text-green-200">↗ {{ $revenueGrowth }}%</span>
                @elseif($revenueGrowth < 0)
                    <span class="text-red-200">↘ {{ abs($revenueGrowth) }}%</span>
                @else
                    <span>→ 0%</span>
                @endif
                <span class="opacity-75">vs bulan lalu</span>
            </p>
        </div>

        <!-- Dana Tersedia -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-dark">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Dana Tersedia</h3>
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold mb-2">Rp {{ number_format($availableBalance, 0, ',', '.') }}</p>
            <a href="{{ route('seller.withdrawals.index') }}" class="text-sm opacity-90 hover:opacity-100 underline">
                Cairkan Dana →
            </a>
        </div>

        <!-- Pesanan Baru -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-dark">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Pesanan Baru</h3>
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold mb-2">{{ $newOrders }}</p>
            <a href="{{ route('seller.orders.index') }}" class="text-sm opacity-90 hover:opacity-100 underline">
                Lihat Pesanan →
            </a>
        </div>

        <!-- Produk Aktif -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-dark">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Produk Aktif</h3>
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <p class="text-3xl font-bold mb-2">{{ $activeProducts }}/{{ $totalProducts }}</p>
            <a href="{{ route('seller.products.index') }}" class="text-sm opacity-90 hover:opacity-100 underline">
                Kelola Produk →
            </a>
        </div>
    </div>

    <!-- Secondary Stats (2 columns) - REMOVED RATING -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
                    <p class="text-xs text-gray-500 mt-1">Sepanjang waktu</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pesanan Diproses</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $processingOrders }}</p>
                    <p class="text-xs text-gray-500 mt-1">Perlu ditindaklanjuti</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Trend Chart -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Tren Penjualan (7 Hari Terakhir)</h3>
                <div class="text-sm text-gray-500">
                    Total: Rp {{ number_format($salesTrend->sum('total'), 0, ',', '.') }}
                </div>
            </div>
            <canvas id="salesChart" height="80"></canvas>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Stok Menipis</h3>
                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full font-semibold">
                    {{ $lowStockProducts->count() }} Produk
                </span>
            </div>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @forelse($lowStockProducts as $product)
                <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="w-12 h-12 bg-gray-200 rounded flex-shrink-0 mr-3 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $product->category->name ?? 'No Category' }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-bold rounded {{ $product->stock <= 5 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $product->stock }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-green-400 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-gray-500">Semua produk stok aman</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Products & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Produk Terlaris</h3>
            <div class="space-y-3">
                @forelse($topProducts as $index => $product)
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">Terjual: {{ $product->total_sold }} unit</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">Rp {{ number_format($product->revenue, 0, ',', '.') }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">Belum ada penjualan</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Pesanan Terbaru</h3>
                <a href="{{ route('seller.orders.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">#{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-500">{{ $order->user->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        @if($order->status == 'pending')
                            <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 font-semibold">Pending</span>
                        @elseif($order->status == 'completed')
                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 font-semibold">Selesai</span>
                        @elseif($order->status == 'cancelled')
                            <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-800 font-semibold">Dibatalkan</span>
                        @else
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-semibold">{{ ucfirst($order->status) }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">Belum ada pesanan</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk chart
    const salesData = @json($salesTrend);
    
    // Format data untuk Chart.js
    const labels = [];
    const data = [];
    const orderCounts = [];
    
    // Generate labels untuk 7 hari terakhir
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        labels.push(dayNames[date.getDay()]);
        
        // Cari data untuk tanggal ini
        const dateStr = date.toISOString().split('T')[0];
        const found = salesData.find(item => item.date === dateStr);
        data.push(found ? parseFloat(found.total) : 0);
        orderCounts.push(found ? parseInt(found.order_count) : 0);
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
                backgroundColor: '#10b981',
                borderRadius: 8,
                maxBarThickness: 60,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        },
                        afterLabel: function(context) {
                            return orderCounts[context.dataIndex] + ' pesanan';
                        }
                    }
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
