@extends('layouts.seller')

@section('title', 'Riwayat Penjualan')
@section('page-title', 'Riwayat Penjualan')
@section('page-subtitle', 'Rekam jejak pesanan yang telah selesai diproses')

@section('content')
<div class="max-w-7xl">

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Date Filter & Export -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <input 
                    type="date" 
                    name="start_date" 
                    value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600">
                
                <span class="text-gray-500">-</span>
                
                <input 
                    type="date" 
                    name="end_date" 
                    value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600">
                
                <button type="submit" class="px-4 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium">
                    Filter
                </button>

                @if(request('start_date') || request('end_date'))
                <a href="{{ route('seller.sales.index') }}" class="px-4 py-2.5 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition text-sm font-medium">
                    Reset
                </a>
                @endif
            </div>

            <a href="{{ route('seller.sales.export', request()->query()) }}" class="px-4 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download CSV
            </a>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Total Pendapatan</span>
                </div>
                <span class="text-xs font-semibold {{ $revenueChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $revenueChange >= 0 ? '+' : '' }}{{ number_format($revenueChange, 1) }}%
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">vs periode sebelumnya</p>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Total Pesanan</span>
                </div>
                <span class="text-xs font-semibold {{ $ordersChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $ordersChange >= 0 ? '+' : '' }}{{ number_format($ordersChange, 1) }}%
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
            <p class="text-xs text-gray-500 mt-1">vs periode sebelumnya</p>
        </div>

        <!-- Average Order Value -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Rata-Rata Order</span>
                </div>
                <span class="text-xs font-semibold {{ $avgChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $avgChange >= 0 ? '+' : '' }}{{ number_format($avgChange, 1) }}%
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">vs periode sebelumnya</p>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Sales Trend (7 Hari Terakhir)</h3>
        </div>
        
        <!-- Chart -->
        <div class="h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex items-center gap-4">
            <!-- Preserve date filters -->
            <input type="hidden" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
            <input type="hidden" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">

            <!-- Search -->
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nomor order atau nama customer..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
            </div>

            <!-- Sort -->
            <select name="sort" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600" onchange="this.form.submit()">
                <option value="">Urutkan Berdasarkan</option>
                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Tanggal Terbaru</option>
                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Tanggal Terlama</option>
                <option value="total_desc" {{ request('sort') == 'total_desc' ? 'selected' : '' }}>Total Tertinggi</option>
                <option value="total_asc" {{ request('sort') == 'total_asc' ? 'selected' : '' }}>Total Terendah</option>
            </select>

            <button type="submit" class="px-4 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium">
                Cari
            </button>
        </form>
    </div>

    <!-- Sales Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-green-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nomor Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Belanja</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($sales as $order)
                    <tr class="hover:bg-gray-50">
                        <!-- Order Number -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                            {{ $order->order_number }}
                        </td>

                        <!-- Customer -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $order->user->email }}</p>
                            </div>
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $order->created_at->format('d/m/Y') }}<br>
                            <span class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</span>
                        </td>

                        <!-- Products -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                @foreach($order->items->take(2) as $item)
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                @endforeach
                                @if($order->items->count() > 2)
                                <p class="text-xs text-green-600 font-semibold">+{{ $order->items->count() - 2 }} produk lainnya</p>
                                @endif
                            </div>
                        </td>

                        <!-- Total -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>

                        <!-- Action -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('seller.orders.show', $order->id) }}" 
                               class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-600 hover:bg-green-700 transition"
                               title="Lihat Detail">
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
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>Belum ada data penjualan untuk periode ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sales->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium">{{ $sales->firstItem() }}</span> sampai 
                    <span class="font-medium">{{ $sales->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $sales->total() }}</span> data
                </p>
                
                {{ $sales->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Chart
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Penjualan',
            data: @json($chartData),
            backgroundColor: [
                '#F59E0B', '#FB923C', '#92400E', '#FB923C', 
                '#78350F', '#F59E0B', '#78350F'
            ],
            borderRadius: 6,
            barThickness: 60,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + (value / 1000) + 'k';
                    }
                },
                grid: {
                    color: '#f3f4f6'
                }
            }
        }
    }
});
</script>
@endpush
