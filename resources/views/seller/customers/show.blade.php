@extends('layouts.seller')

@section('title', 'Detail Pelanggan')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="max-w-7xl px-2 sm:px-0">
    <div class="mb-4 md:mb-6 flex items-center gap-3 md:gap-4">
        <a href="{{ route('seller.customers.index') }}"
            class="text-gray-400 hover:text-gray-900 transition p-1 bg-white rounded-lg border border-gray-100 shadow-sm">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="min-w-0">
            <nav class="flex text-[10px] md:text-sm text-gray-500 mb-0.5">
                <a href="{{ route('seller.customers.index') }}" class="hover:text-green-800">Pelanggan</a>
                <span class="mx-1.5">›</span>
                <span class="text-gray-900 font-medium truncate">Detail</span>
            </nav>
            <h1 class="text-lg md:text-3xl font-bold text-[#0F4C20] truncate">
                Detail <span class="text-gray-900">{{ $customer->name }}</span>
            </h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm p-5 md:p-6 border border-gray-100">
                <div class="text-center mb-5 md:mb-6">
                    <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full flex items-center justify-center text-white font-bold text-xl md:text-2xl mb-3 md:mb-4 shadow-md"
                        style="background-color: {{ '#' . substr(md5($customer->name), 0, 6) }}">
                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                    </div>
                    <h2 class="text-base md:text-xl font-bold text-gray-900 truncate">{{ $customer->name }}</h2>
                    <p class="text-[10px] text-gray-400 font-mono mt-1">ID: #{{ str_pad($customer->id, 4, '0',
                        STR_PAD_LEFT) }}</p>
                </div>

                <div class="border-t border-gray-50 my-4"></div>

                <div class="space-y-3 md:space-y-4">
                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition">
                        <div
                            class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                            <x-heroicon-s-envelope class="w-4 h-4 md:w-5 md:h-5 text-green-700" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Email</p>
                            <p class="text-xs md:text-sm text-gray-900 truncate font-medium">{{ $customer->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition">
                        <div
                            class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                            <x-heroicon-s-phone class="w-4 h-4 md:w-5 md:h-5 text-green-700" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Telepon
                            </p>
                            <p class="text-xs md:text-sm text-gray-900 font-medium">{{ $customer->phone ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition">
                        <div
                            class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                            <x-heroicon-s-calendar class="w-4 h-4 md:w-5 md:h-5 text-green-700" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Tgl Lahir
                            </p>
                            <p class="text-xs md:text-sm text-gray-900 font-medium">{{ $customer->date_of_birth ?
                                date('d M Y', strtotime($customer->date_of_birth)) : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4 md:space-y-6">

            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center gap-3">
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600 shrink-0">
                        <x-heroicon-o-shopping-bag class="w-5 h-5 md:w-6 md:h-6" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase">Pesanan</p>
                        <p class="text-sm md:text-xl font-bold text-gray-900 leading-none">{{ $totalOrders }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center gap-3">
                    <div class="p-2 bg-green-50 rounded-lg text-green-700 shrink-0">
                        <x-heroicon-o-banknotes class="w-5 h-5 md:w-6 md:h-6" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase">Total Belanja</p>
                        <p class="text-sm md:text-xl font-bold text-gray-900 leading-none">Rp {{
                            number_format($totalSpent, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center gap-3 col-span-2 lg:col-span-1">
                    <div class="p-2 bg-yellow-50 rounded-lg text-yellow-600 shrink-0">
                        <x-heroicon-o-calculator class="w-5 h-5 md:w-6 md:h-6" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase">Rata-Rata</p>
                        <p class="text-sm md:text-xl font-bold text-gray-900 leading-none">Rp {{
                            number_format($averageOrder, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div
                    class="p-4 md:p-6 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h3 class="text-sm md:text-lg font-bold text-gray-900">Riwayat Pesanan</h3>

                    <div class="flex flex-col sm:flex-row items-stretch gap-2 md:gap-3">
                        <div class="relative flex-1 sm:w-56">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                            </div>
                            <input type="text" id="searchOrder" placeholder="Cari ID..." value="{{ request('search') }}"
                                class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 text-xs transition">
                        </div>

                        <div class="relative">
                            <select id="statusFilter"
                                class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 text-xs appearance-none bg-white cursor-pointer">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="processing" {{ request('status')=='processing' ? 'selected' : '' }}>
                                    Diproses</option>
                                <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Batal
                                </option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-gray-400">
                                <x-heroicon-m-chevron-down class="w-4 h-4" />
                            </div>
                        </div>

                        @if(request('search') || request('status'))
                        <button id="resetFilter" type="button"
                            class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-200 transition">
                            Reset
                        </button>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left whitespace-nowrap min-w-[600px]">
                        <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                            <tr>
                                <th
                                    class="px-5 py-3 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                                    ID Pesanan</th>
                                <th
                                    class="px-5 py-3 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-5 py-3 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                                    Status</th>
                                <th
                                    class="px-5 py-3 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider text-right">
                                    Total</th>
                                <th
                                    class="px-5 py-3 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider text-center">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 bg-white">
                            @forelse($orders as $order)
                            <tr class="hover:bg-green-50/20 transition text-xs md:text-sm">
                                <td class="px-5 py-3 font-bold text-gray-900 font-mono tracking-tighter">
                                    {{ $order->order_number }}
                                </td>
                                <td class="px-5 py-3 text-gray-500">
                                    {{ date('d/m/Y', strtotime($order->created_at)) }}
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                    $statusClasses = [
                                    'completed' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    ];
                                    $labels = ['completed' => 'Selesai', 'pending' => 'Pending', 'processing' =>
                                    'Proses', 'cancelled' => 'Batal'];
                                    $class = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700';
                                    $label = $labels[$order->status] ?? ucfirst($order->status);
                                    @endphp
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-[9px] md:text-[10px] font-bold {{ $class }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right font-black text-[#0F4C20]">
                                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <a href="{{ route('seller.orders.show', $order->id) }}"
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-[#15803D] text-white hover:bg-[#166534] transition">
                                        <x-heroicon-s-eye class="w-3.5 h-3.5" />
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                    Tidak ada riwayat pesanan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const searchOrder = document.getElementById('searchOrder');
    const statusFilter = document.getElementById('statusFilter');
    const resetButton = document.getElementById('resetFilter');

    function applyFilter() {
        const params = new URLSearchParams(window.location.search);
        if (searchOrder.value) params.set('search', searchOrder.value); else params.delete('search');
        if (statusFilter.value) params.set('status', statusFilter.value); else params.delete('status');
        params.delete('page'); 
        window.location.href = window.location.pathname + '?' + params.toString();
    }

    let searchTimeout;
    searchOrder.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilter, 500);
    });

    statusFilter.addEventListener('change', applyFilter);
    if (resetButton) resetButton.addEventListener('click', () => window.location.href = window.location.pathname);
</script>
@endpush
@endsection