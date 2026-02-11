@extends('layouts.seller')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Kelola produk, pesanan, dan performa toko Anda dalam satu tempat')

@section('content')
<div class="space-y-6">
    <!-- Top Stats Cards (4 columns) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">

        <div
            class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <x-heroicon-s-banknotes class="w-6 h-6" />
                </div>
            </div>
            <div class="flex items-center text-xs font-medium">
                @if($revenueGrowth > 0)
                <span class="text-green-600 bg-green-50 px-2 py-1 rounded-md flex items-center gap-1">
                    <x-heroicon-s-arrow-trending-up class="w-3 h-3" /> {{ $revenueGrowth }}%
                </span>
                @elseif($revenueGrowth < 0) <span
                    class="text-red-600 bg-red-50 px-2 py-1 rounded-md flex items-center gap-1">
                    <x-heroicon-s-arrow-trending-down class="w-3 h-3" /> {{ abs($revenueGrowth) }}%
                    </span>
                    @else
                    <span class="text-gray-600 bg-gray-100 px-2 py-1 rounded-md">0%</span>
                    @endif
                    <span class="ml-2 text-gray-400">vs bulan lalu</span>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Dana Tersedia</p>
                    <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($availableBalance, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                    <x-heroicon-s-wallet class="w-6 h-6" />
                </div>
            </div>
            <div>
                <a href="{{ route('seller.withdrawals.index') }}"
                    class="text-xs font-semibold text-green-700 hover:text-green-800 inline-flex items-center gap-1">
                    Cairkan Dana
                    <x-heroicon-m-arrow-right class="w-3 h-3" />
                </a>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Pesanan Baru</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $newOrders }}</h3>
                </div>
                <div class="p-3 bg-yellow-50 text-yellow-600 rounded-xl">
                    <x-heroicon-s-shopping-cart class="w-6 h-6" />
                </div>
            </div>
            <div>
                <a href="{{ route('seller.orders.index') }}"
                    class="text-xs font-semibold text-yellow-700 hover:text-yellow-800 inline-flex items-center gap-1">
                    Lihat Pesanan
                    <x-heroicon-m-arrow-right class="w-3 h-3" />
                </a>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Produk Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $activeProducts }} <span
                            class="text-sm font-normal text-gray-400">/ {{ $totalProducts }}</span></h3>
                </div>
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                    <x-heroicon-s-archive-box class="w-6 h-6" />
                </div>
            </div>
            <div>
                <a href="{{ route('seller.products.index') }}"
                    class="text-xs font-semibold text-purple-700 hover:text-purple-800 inline-flex items-center gap-1">
                    Kelola Produk
                    <x-heroicon-m-arrow-right class="w-3 h-3" />
                </a>
            </div>
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
                        <path
                            d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
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
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Tren Penjualan</h3>
                    <p class="text-sm text-gray-500">Performa 7 hari terakhir</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Total Periode Ini</p>
                    <p class="text-lg font-bold text-[#15803D]">Rp {{ number_format($salesTrend->sum('total'), 0, ',',
                        '.') }}</p>
                </div>
            </div>
            <div class="h-72">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Stok Menipis</h3>
                <span class="text-xs bg-red-100 text-red-700 px-2.5 py-1 rounded-full font-bold">
                    {{ $lowStockProducts->count() }} Item
                </span>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 space-y-3 max-h-[300px] custom-scrollbar">
                @forelse($lowStockProducts as $product)
                <div class="flex items-center p-3 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                    <div
                        class="w-10 h-10 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                        @if($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                            class="w-full h-full object-cover">
                        @else
                        <x-heroicon-o-photo class="w-5 h-5 text-gray-400" />
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 ml-3">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $product->category->name ?? 'Umum' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs font-medium text-gray-500">Sisa</span>
                        <span class="text-sm font-bold {{ $product->stock <= 5 ? 'text-red-600' : 'text-yellow-600' }}">
                            {{ $product->stock }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center h-full text-center py-8">
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center mb-3">
                        <x-heroicon-o-check class="w-6 h-6 text-green-600" />
                    </div>
                    <p class="text-sm font-medium text-gray-900">Stok Aman</p>
                    <p class="text-xs text-gray-500">Tidak ada produk dengan stok menipis.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Products & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <x-heroicon-s-trophy class="w-5 h-5 text-yellow-500" /> Produk Terlaris
            </h3>
            <div class="space-y-4">
                @forelse($topProducts as $index => $product)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $index == 0 ? 'bg-yellow-100 text-yellow-700' : ($index == 1 ? 'bg-gray-100 text-gray-700' : ($index == 2 ? 'bg-orange-100 text-orange-800' : 'bg-white text-gray-500 border border-gray-200')) }}">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-gray-900 line-clamp-1 group-hover:text-[#15803D] transition">
                                {{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $product->total_sold }} terjual</p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-gray-900">Rp {{ number_format($product->revenue, 0, ',', '.') }}
                    </p>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">Belum ada data penjualan.</p>
                @endforelse
            </div>
        </div>
        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Pesanan Terbaru</h3>
                <a href="{{ route('seller.orders.index') }}"
                    class="text-sm font-medium text-[#15803D] hover:underline flex items-center gap-1">
                    Lihat Semua
                    <x-heroicon-m-arrow-right class="w-3 h-3" />
                </a>
            </div>
            <div class="overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                        <tr>
                            <th class="px-3 py-2 text-[#15803D]">ID</th>
                            <th class="px-3 py-2 text-[#15803D]">Status</th>
                            <th class="px-3 py-2 text-right text-[#15803D]">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-3 py-3">
                                <span class="font-mono font-medium text-gray-900">#{{ $order->order_number }}</span>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                @if($order->status == 'pending')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($order->status == 'completed')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                @elseif($order->status == 'cancelled')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Batal</span>
                                @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">{{
                                    ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-right font-bold text-gray-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-500">Belum ada pesanan terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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