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
            <div class="grid grid-cols-3 gap-4 mb-6">
                <!-- Total Orders -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">Total Pesanan</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                </div>

                <!-- Total Spent -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">Total Belanja</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                </div>

                <!-- Average Order -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">Rata-Rata Order</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($averageOrder, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Orders List Header -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Riwayat Pesanan</h3>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <x-heroicon-o-magnifying-glass class="text-gray-400 w-5 h-5" />
                            </div>
                            <input type="text" placeholder="Cari ID pesanan"
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm w-64">
                        </div>
                        <button
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                        <tr class="text-left">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#15803D]">ID Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#15803D]">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#15803D]">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#15803D]">Total Belanja</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#15803D]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($customer->orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ date('d/m/y', strtotime($order->created_at))
                                }}</td>
                            <td class="px-6 py-4">
                                @if($order->status == 'completed')
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white">Selesai</span>
                                @elseif($order->status == 'pending')
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-600 text-white">Menunggu
                                    Pembayaran</span>
                                @elseif($order->status == 'processing')
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500 text-gray-900">Diproses</span>
                                @else
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{
                                number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('seller.orders.show', $order->id) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-600 hover:bg-yellow-700 transition">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                Belum ada riwayat pesanan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($customer->orders->count() > 0)
                <div class="px-6 py-4 border-t">
                    <p class="text-sm text-gray-600">
                        Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">{{
                            $customer->orders->count() }}</span> dari <span class="font-medium">{{ $totalOrders
                            }}</span> data
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection