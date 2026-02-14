@extends('layouts.seller')

@section('title', 'Riwayat Penjualan')
@section('page-title', 'Riwayat Penjualan')
@section('page-subtitle', 'Rekam jejak pesanan yang telah selesai diproses')

@section('content')
<div class="max-w-7xl">

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
        <x-heroicon-s-check-circle class="w-5 h-5" />
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('seller.sales.index') }}"
            class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">

                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    </div>
                    <input type="date" name="start_date"
                        value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                        class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600 w-full transition shadow-sm">
                </div>

                <span class="text-gray-400 hidden sm:block font-bold">-</span>

                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    </div>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                        class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600 w-full transition shadow-sm">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 sm:flex-none px-6 py-2.5 bg-[#15803D] text-white rounded-lg hover:bg-[#166534] transition text-sm font-semibold shadow-sm active:scale-95">
                        Filter
                    </button>

                    @if(request('start_date') || request('end_date'))
                    <a href="{{ route('seller.sales.index') }}"
                        class="flex-1 sm:flex-none px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-semibold text-center shadow-sm">
                        Reset
                    </a>
                    @endif
                </div>
            </div>

            <a href="{{ route('seller.sales.export', request()->query()) }}"
                class="w-full lg:w-auto px-6 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-semibold flex items-center justify-center gap-2 shadow-sm active:scale-95">
                <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
                <span>Download CSV</span>
            </a>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                        <x-heroicon-o-currency-dollar class="w-5 h-5" />
                    </div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pendapatan</span>
                </div>
                <span
                    class="text-xs font-bold {{ $revenueChange >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded-full">
                    {{ $revenueChange >= 0 ? '+' : '' }}{{ number_format($revenueChange, 1) }}%
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">vs periode sebelumnya</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <x-heroicon-o-shopping-bag class="w-5 h-5" />
                    </div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pesanan</span>
                </div>
                <span
                    class="text-xs font-bold {{ $ordersChange >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded-full">
                    {{ $ordersChange >= 0 ? '+' : '' }}{{ number_format($ordersChange, 1) }}%
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalOrders) }}</p>
            <p class="text-xs text-gray-500 mt-1">vs periode sebelumnya</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-yellow-50 rounded-lg text-yellow-600">
                        <x-heroicon-o-chart-bar class="w-5 h-5" />
                    </div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Rata-Rata Order</span>
                </div>
                <span
                    class="text-xs font-bold {{ $avgChange >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded-full">
                    {{ $avgChange >= 0 ? '+' : '' }}{{ number_format($avgChange, 1) }}%
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">vs periode sebelumnya</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Sales Trend (7 Hari Terakhir)</h3>
        </div>

        <div class="h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-col md:flex-row items-center gap-4">
            <input type="hidden" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
            <input type="hidden" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">

            <div class="flex-1 relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="text-gray-400 w-5 h-5" />
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor order atau nama customer..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
            </div>

            <div class="relative w-full md:w-auto">
                <select name="sort"
                    class="w-full md:w-auto px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none pr-10 cursor-pointer"
                    onchange="this.form.submit()">
                    <option value="">Urutkan Berdasarkan</option>
                    <option value="date_desc" {{ request('sort')=='date_desc' ? 'selected' : '' }}>Tanggal Terbaru
                    </option>
                    <option value="date_asc" {{ request('sort')=='date_asc' ? 'selected' : '' }}>Tanggal Terlama
                    </option>
                    <option value="total_desc" {{ request('sort')=='total_desc' ? 'selected' : '' }}>Total Tertinggi
                    </option>
                    <option value="total_asc" {{ request('sort')=='total_asc' ? 'selected' : '' }}>Total Terendah
                    </option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <x-heroicon-m-chevron-down class="w-5 h-5 text-gray-400" />
                </div>
            </div>

            <button type="submit"
                class="w-full md:w-auto px-6 py-2.5 bg-[#15803D] text-white rounded-lg hover:bg-[#166534] transition text-sm font-semibold shadow-sm">
                Cari
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Nomor Order</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Pelanggan</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Tanggal</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Produk</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Total Belanja</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($sales as $order)
                    <tr class="hover:bg-[#F9FDF7] transition">
                        <td class="px-5 py-4 whitespace-nowrap text-sm font-mono font-bold text-gray-900">
                            {{ $order->order_number }}
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-semibold text-xs shrink-0"
                                    style="background-color: {{ '#' . substr(md5($order->user->name), 0, 6) }}">
                                    {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                                    <p class="text-[11px] text-gray-500">{{ Str::limit($order->user->email, 20) }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap text-[13px] text-gray-700">
                            {{ $order->created_at->format('d/m/Y') }}<br>
                            <span class="text-[11px] text-gray-500">{{ $order->created_at->format('H:i') }} WIB</span>
                        </td>

                        <td class="px-5 py-4">
                            <div class="space-y-1">
                                @foreach($order->items->take(2) as $item)
                                <div>
                                    <p class="text-[13px] font-medium text-gray-900 line-clamp-1">{{
                                        $item->product->name }}</p>
                                    <p class="text-[11px] text-gray-500">{{ $item->quantity }} x Rp {{
                                        number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                @endforeach
                                @if($order->items->count() > 2)
                                <p class="text-[11px] text-green-600 font-semibold">+{{ $order->items->count() - 2 }}
                                    produk lainnya</p>
                                @endif
                            </div>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('seller.sales.show', $order->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm"
                                title="Lihat Detail">
                                <x-heroicon-s-eye class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <x-heroicon-o-clipboard-document-list class="w-12 h-12 text-gray-300 mb-3" />
                                <p class="text-sm font-medium text-gray-900">Belum ada data penjualan</p>
                                <p class="text-xs text-gray-500 mt-1">Data penjualan yang selesai akan muncul di sini
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[13px] text-gray-600">
                    Menampilkan <span class="font-medium text-gray-900">{{ $sales->firstItem() }}</span> sampai
                    <span class="font-medium text-gray-900">{{ $sales->lastItem() }}</span> dari
                    <span class="font-medium text-gray-900">{{ $sales->total() }}</span> data
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