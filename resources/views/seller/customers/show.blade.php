@extends('layouts.seller')

@section('title', 'Detail Pelanggan')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="max-w-7xl">
    <!-- Breadcrumb & Back Button -->
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('seller.customers.index') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <nav class="flex text-sm text-gray-600 mb-1">
                <a href="{{ route('seller.customers.index') }}" class="hover:text-green-800">Pelanggan</a>
                <span class="mx-2">›</span>
                <span class="text-gray-900 font-medium">Detail Pelanggan</span>
            </nav>
            <h1 class="text-3xl font-bold text-green-800">Detail Pelanggan <span class="text-gray-900">{{
                    $customer->name }}</span></h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <!-- Avatar -->
                <div class="text-center mb-6">
                    <div class="w-24 h-24 mx-auto rounded-full flex items-center justify-center text-white font-bold text-2xl mb-4"
                        style="background-color: {{ '#' . substr(md5($customer->name), 0, 6) }}">
                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $customer->name }}</h2>
                </div>

                <hr class="my-4">

                <!-- Contact Info -->
                <div class="space-y-4">
                    <!-- Email -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700">Email</p>
                            <p class="text-sm text-gray-900">{{ $customer->email }}</p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700">Telepon</p>
                            <p class="text-sm text-gray-900">{{ $customer->phone ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Member Since -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700">Tanggal Lahir</p>
                            <p class="text-sm text-gray-900">{{ $customer->date_of_birth ? date('d M Y',
                                strtotime($customer->date_of_birth)) : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders History -->
        <div class="lg:col-span-2">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div
                    class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 transition hover:shadow-md flex items-center gap-4">
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600 shrink-0">
                        <x-heroicon-o-shopping-bag class="w-6 h-6" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pesanan</p>
                        <p class="text-xl font-bold text-gray-900 leading-none">{{ $totalOrders }}</p>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 transition hover:shadow-md flex items-center gap-4">
                    <div class="p-3 bg-green-50 rounded-xl text-green-700 shrink-0">
                        <x-heroicon-o-banknotes class="w-6 h-6" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Belanja</p>
                        <p class="text-xl font-bold text-gray-900 leading-none">Rp {{ number_format($totalSpent, 0, ',',
                            '.') }}</p>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 transition hover:shadow-md flex items-center gap-4 sm:col-span-2 lg:col-span-1">
                    <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600 shrink-0">
                        <x-heroicon-o-calculator class="w-6 h-6" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Rata-Rata Order</p>
                        <p class="text-xl font-bold text-gray-900 leading-none">Rp {{ number_format($averageOrder, 0,
                            ',', '.') }}</p>
                    </div>
                </div>
            </div>

           <!-- Orders List Header -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-4 border border-gray-100">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h3 class="text-lg font-bold text-gray-900">Riwayat Pesanan</h3>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="relative flex-1 sm:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="text-gray-400 w-5 h-5" />
                </div>
                <input type="text" id="searchOrder" placeholder="Cari ID pesanan..." value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm transition">
            </div>
            
            <div class="relative">
                <select id="statusFilter"
                    class="w-full sm:w-40 px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm appearance-none bg-white cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="processing" {{ request('status')=='processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            {{-- ✅ TOMBOL RESET --}}
            @if(request('search') || request('status'))
            <button id="resetFilter" type="button"
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 flex items-center justify-center gap-2 transition bg-white">
                <x-heroicon-o-arrow-path class="w-4 h-4 text-gray-500" />
                <span class="font-medium text-gray-700">Reset</span>
            </button>
            @endif
        </div>
    </div>

    <div class="py-5">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-6 py-4 font-bold text-[#15803D] uppercase tracking-wider text-[11px]">ID Pesanan</th>
                        <th class="px-6 py-4 font-bold text-[#15803D] uppercase tracking-wider text-[11px]">Tanggal</th>
                        <th class="px-6 py-4 font-bold text-[#15803D] uppercase tracking-wider text-[11px]">Status</th>
                        <th class="px-6 py-4 font-bold text-[#15803D] uppercase tracking-wider text-[11px]">Total Belanja</th>
                        <th class="px-6 py-4 font-bold text-[#15803D] uppercase tracking-wider text-[11px] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($orders as $order)
                    <tr class="hover:bg-green-50/30 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 font-mono tracking-tighter">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 font-medium">
                            {{ date('d M Y', strtotime($order->created_at)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusClasses = [
                                'completed' => 'bg-green-100 text-green-700 border-green-200',
                                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                            ];
                            $labels = [
                                'completed' => 'Selesai',
                                'pending' => 'Menunggu Pembayaran',
                                'processing' => 'Diproses',
                                'cancelled' => 'Dibatalkan',
                            ];
                            $currentStatus = $order->status;
                            $class = $statusClasses[$currentStatus] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                            $label = $labels[$currentStatus] ?? ucfirst($currentStatus);
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $class }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#0F4C20]">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('seller.orders.show', $order->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white transition-all shadow-sm">
                                <x-heroicon-s-eye class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <x-heroicon-o-document-magnifying-glass class="w-12 h-12 mb-3 opacity-20" />
                                <p class="text-sm italic">
                                    @if(request('search') || request('status'))
                                        Tidak ada pesanan yang sesuai dengan filter
                                    @else
                                        Belum ada riwayat pesanan
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->count() > 0)
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[13px] text-gray-600">
                    Menampilkan <span class="font-medium text-gray-900">{{ $orders->firstItem() }}</span>
                    sampai <span class="font-medium text-gray-900">{{ $orders->lastItem() }}</span>
                    dari <span class="font-medium text-gray-900">{{ $orders->total() }}</span> pesanan
                </p>
                <div class="flex items-center gap-2">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const searchOrder = document.getElementById('searchOrder');
    const statusFilter = document.getElementById('statusFilter');
    const resetButton = document.getElementById('resetFilter');

    function applyFilter() {
        const search = searchOrder.value;
        const status = statusFilter.value;
        
        const params = new URLSearchParams(window.location.search);
        
        // Clear old params
        params.delete('search');
        params.delete('status');
        params.delete('page'); // Reset pagination
        
        // Add new params
        if (search) params.set('search', search);
        if (status) params.set('status', status);
        
        window.location.href = window.location.pathname + '?' + params.toString();
    }

    function resetFilter() {
        window.location.href = window.location.pathname;
    }

    // Debounce search
    let searchTimeout;
    searchOrder.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilter, 500);
    });

    statusFilter.addEventListener('change', applyFilter);
    
    if (resetButton) {
        resetButton.addEventListener('click', resetFilter);
    }
</script>
@endpush
@endsection