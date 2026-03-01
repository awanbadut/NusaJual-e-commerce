@extends('layouts.seller')

@section('title', 'Riwayat Penjualan')
@section('page-title', 'Riwayat Penjualan')
@section('page-subtitle', 'Rekam jejak pesanan yang telah selesai diproses')

@section('content')
<div class="max-w-7xl px-2 sm:px-0 space-y-4 md:space-y-6">

    @if(session('success'))
    <div
        class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 md:p-4 rounded-lg mb-4 md:mb-6 flex items-center gap-3 shadow-sm">
        <x-heroicon-s-check-circle class="w-5 h-5 shrink-0" />
        <p class="text-xs md:text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border border-gray-100">
        <form method="GET" action="{{ route('seller.sales.index') }}"
            class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 md:gap-4">

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3 w-full lg:w-auto">
                <div class="flex items-center gap-2 flex-1">
                    <div class="relative flex-1">
                        <input type="date" name="start_date"
                            value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                            class="pl-3 pr-2 py-2 border border-gray-300 rounded-lg text-[11px] md:text-sm focus:ring-2 focus:ring-green-600 w-full transition shadow-sm bg-gray-50 md:bg-white">
                    </div>

                    <span class="text-gray-400 font-bold">-</span>

                    <div class="relative flex-1">
                        <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                            class="pl-3 pr-2 py-2 border border-gray-300 rounded-lg text-[11px] md:text-sm focus:ring-2 focus:ring-green-600 w-full transition shadow-sm bg-gray-50 md:bg-white">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 md:flex-none px-4 md:px-6 py-2 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-[11px] md:text-sm font-bold shadow-md active:scale-95">
                        Filter
                    </button>

                    @if(request('start_date') || request('end_date'))
                    <a href="{{ route('seller.sales.index') }}"
                        class="px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-bold text-[11px] md:text-sm transition flex items-center justify-center border border-gray-200">
                        Reset
                    </a>
                    @endif
                </div>
            </div>

            <a href="{{ route('seller.sales.export', request()->query()) }}"
                class="w-full lg:w-auto px-4 py-2 bg-white border border-green-800 text-green-800 rounded-lg hover:bg-green-50 transition text-[11px] md:text-sm font-bold flex items-center justify-center gap-2 shadow-sm active:scale-95">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                <span>Export CSV</span>
            </a>
        </form>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-1.5 md:gap-2">
                    <div class="p-1.5 bg-green-50 rounded-lg text-green-600">
                        <x-heroicon-o-shopping-bag class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-tight">Pesanan</span>
                </div>
                <span class="text-[9px] font-bold {{ $ordersChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $ordersChange >= 0 ? '+' : '' }}{{ number_format($ordersChange, 1) }}%
                </span>
            </div>
            <p class="text-xl md:text-2xl font-black text-gray-900 mt-2">{{ number_format($totalOrders) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-1.5 md:gap-2">
                    <div class="p-1.5 bg-yellow-50 rounded-lg text-yellow-600">
                        <x-heroicon-o-chart-bar class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-tight">Average</span>
                </div>
            </div>
            <p class="text-base md:text-xl font-black text-gray-900 mt-2 truncate">Rp{{ number_format($avgOrderValue, 0,
                ',', '.') }}</p>
        </div>

        <div class="col-span-2 lg:col-span-1 bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-1.5 md:gap-2">
                    <div class="p-1.5 bg-blue-50 rounded-lg text-blue-600">
                        <x-heroicon-o-currency-dollar class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-tight">Total
                        Pendapatan</span>
                </div>
                <span class="text-[9px] font-bold {{ $revenueChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $revenueChange >= 0 ? '+' : '' }}{{ number_format($revenueChange, 1) }}%
                </span>
            </div>
            <p class="text-xl md:text-2xl font-black text-[#0F4C20] mt-2">Rp{{ number_format($totalRevenue, 0, ',', '.')
                }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <h3 class="text-sm md:text-lg font-bold text-gray-900">Sales Trend <span
                    class="text-[10px] md:text-xs text-gray-400 font-medium ml-1">(7 Hari Terakhir)</span></h3>
        </div>
        <div class="h-56 md:h-64 relative">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border border-gray-100">
        <form method="GET" class="flex flex-col md:flex-row items-center gap-3">
            <input type="hidden" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
            <input type="hidden" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">

            <div class="flex-1 relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor order atau customer..."
                    class="w-full pl-9 md:pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-600 text-xs md:text-sm">
            </div>

            <div class="relative w-full md:w-auto">
                <select name="sort"
                    class="w-full md:w-auto pl-3 pr-8 py-2 border border-gray-300 rounded-lg text-xs md:text-sm appearance-none bg-white cursor-pointer"
                    onchange="this.form.submit()">
                    <option value="">Urutkan Berdasarkan</option>
                    <option value="date_desc" {{ request('sort')=='date_desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="total_desc" {{ request('sort')=='total_desc' ? 'selected' : '' }}>Total Tertinggi
                    </option>
                    <option value="total_asc" {{ request('sort')=='total_asc' ? 'selected' : '' }}>Total Terendah
                    </option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-gray-400">
                    <x-heroicon-m-chevron-down class="w-4 h-4" />
                </div>
            </div>

            <button type="submit"
                class="w-full md:w-auto px-6 py-2 bg-green-800 text-white rounded-lg font-bold text-xs md:text-sm shadow-md active:scale-95 transition">
                Cari
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left whitespace-nowrap min-w-[850px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                            No. Order</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                            Pelanggan</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                            Tanggal</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                            Produk</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                            Total</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white text-xs md:text-sm">
                    @forelse($sales as $order)
                    <tr class="hover:bg-[#F9FDF7] transition">
                        <td class="px-4 py-3 md:px-5 md:py-4 font-mono font-bold text-gray-900">#{{ $order->order_number
                            }}</td>

                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-[10px] shrink-0"
                                    style="background-color: {{ '#' . substr(md5($order->user->name), 0, 6) }}">
                                    {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900 truncate max-w-[120px]">{{ $order->user->name }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 truncate max-w-[120px]">{{ $order->user->email
                                        }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <p class="text-gray-700 font-medium">{{ $order->created_at->format('d/m/Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $order->created_at->format('H:i') }} WIB</p>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <div class="space-y-1 min-w-[150px]">
                                @foreach($order->items->take(1) as $item)
                                <p class="font-medium text-gray-900 truncate text-[11px]">{{ $item->product->name }}</p>
                                @endforeach
                                @if($order->items->count() > 1)
                                <p
                                    class="text-[10px] text-green-600 font-bold bg-green-50 px-1.5 py-0.5 rounded inline-block">
                                    +{{ $order->items->count() - 1 }} produk lainnya</p>
                                @endif
                            </div>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <p class="font-black text-[#0F4C20]">Rp{{ number_format($order->total_amount, 0, ',', '.')
                                }}</p>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4 text-center">
                            <a href="{{ route('seller.sales.show', $order->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-lg bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm">
                                <x-heroicon-s-eye class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                            <div class="flex flex-col items-center">
                                <x-heroicon-o-clipboard-document-list class="w-12 h-12 opacity-20 mb-2" />
                                <p class="text-xs md:text-sm">Belum ada data penjualan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
        <div class="px-4 py-3 bg-white border-t border-gray-50">
            {{ $sales->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Penjualan',
            data: @json($chartData),
            backgroundColor: '#0F4C20',
            borderRadius: 4,
            maxBarThickness: 35,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
            y: { 
                beginAtZero: true, 
                ticks: { 
                    font: { size: 10 },
                    callback: value => 'Rp' + (value / 1000) + 'k' 
                } 
            }
        }
    }
});
</script>
@endpush