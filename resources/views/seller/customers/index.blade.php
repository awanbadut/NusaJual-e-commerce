@extends('layouts.seller')

@section('title', 'Manajemen Pelanggan')
@section('page-title', 'Manajemen Pelanggan')
@section('page-subtitle', 'Pantau data pelanggan dan riwayat pembelian mereka')

@section('content')
<div class="space-y-4 md:space-y-6 px-2 sm:px-0">

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-5">
        <div
            class="bg-white p-4 md:px-5 md:py-5 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md flex flex-col justify-between">
            <p
                class="text-[9px] md:text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1 uppercase font-bold tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                Pelanggan
            </p>
            <div class="flex items-center justify-between">
                <p class="text-xl md:text-[24px] font-bold text-[#111827] leading-tight">{{ $customers->total() }}</p>
                <div class="bg-blue-50 p-1.5 md:p-2 rounded-lg md:rounded-xl text-blue-600 shrink-0">
                    <x-heroicon-o-users class="w-4 h-4 md:w-6 md:h-6" />
                </div>
            </div>
        </div>

        <div
            class="bg-white p-4 md:px-5 md:py-5 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md flex flex-col justify-between">
            <p
                class="text-[9px] md:text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1 uppercase font-bold tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Transaksi
            </p>
            <div class="flex items-center justify-between">
                <p class="text-xl md:text-[24px] font-bold text-[#111827] leading-tight">{{
                    $customers->sum('total_orders') }}</p>
                <div class="bg-green-50 p-1.5 md:p-2 rounded-lg md:rounded-xl text-green-600 shrink-0">
                    <x-heroicon-o-shopping-bag class="w-4 h-4 md:w-6 md:h-6" />
                </div>
            </div>
        </div>

        <div
            class="col-span-2 lg:col-span-1 bg-white p-4 md:px-5 md:py-5 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md flex flex-col justify-between">
            <p
                class="text-[9px] md:text-[11px] text-[#78716C] mb-1.5 flex items-center gap-1 uppercase font-bold tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                Total Pendapatan
            </p>
            <div class="flex items-center justify-between">
                <p class="text-xl md:text-[24px] font-bold text-[#111827] leading-tight">Rp {{
                    number_format($customers->sum('total_spent'), 0, ',', '.') }}</p>
                <div class="bg-yellow-50 p-1.5 md:p-2 rounded-lg md:rounded-xl text-yellow-600 shrink-0">
                    <x-heroicon-o-currency-dollar class="w-4 h-4 md:w-6 md:h-6" />
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border border-gray-100">
        <form method="GET" action="{{ route('seller.customers.index') }}"
            class="flex flex-col lg:flex-row gap-3 md:gap-4 items-center">

            <div class="w-full lg:flex-1 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="text-gray-400 w-4 h-4 md:w-5 md:h-5" />
                </div>
                <input type="text" name="search" placeholder="Cari pelanggan..." value="{{ request('search') }}"
                    class="w-full pl-9 md:pl-10 pr-4 py-2 md:py-2.5 text-xs md:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
            </div>

            <div class="w-full lg:w-64 relative">
                <select name="sort" onchange="this.form.submit()"
                    class="w-full pl-3 pr-8 py-2 md:py-2.5 text-xs md:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none bg-white cursor-pointer">
                    <option value="">Urutkan Berdasarkan</option>
                    <option value="total_spent_desc" {{ request('sort')=='total_spent_desc' ? 'selected' : '' }}>Belanja
                        (Terbanyak)</option>
                    <option value="total_spent_asc" {{ request('sort')=='total_spent_asc' ? 'selected' : '' }}>Belanja
                        (Terendah)</option>
                    <option value="total_orders_desc" {{ request('sort')=='total_orders_desc' ? 'selected' : '' }}>
                        Pesanan (Terbanyak)</option>
                    <option value="name_asc" {{ request('sort')=='name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <x-heroicon-m-chevron-down class="w-4 h-4 text-gray-400" />
                </div>
            </div>

            <div class="flex w-full lg:w-auto gap-2">
                <button type="submit"
                    class="flex-1 lg:flex-none px-6 py-2 md:py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-xs md:text-sm font-bold shadow-md active:scale-95">
                    Cari
                </button>
                @if(request('search') || request('sort'))
                <a href="{{ route('seller.customers.index') }}"
                    class="px-4 py-2 md:py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-bold text-xs md:text-sm transition flex items-center justify-center">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left border-collapse min-w-[800px] md:min-w-[1000px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] text-[11px] md:text-[13px] uppercase tracking-wider">
                            Nama Pelanggan</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] text-[11px] md:text-[13px] uppercase tracking-wider">
                            Kontak</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] text-[11px] md:text-[13px] uppercase tracking-wider text-center">
                            Pesanan</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] text-[11px] md:text-[13px] uppercase tracking-wider">
                            Total Belanja</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] text-[11px] md:text-[13px] uppercase tracking-wider text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-[#F9FDF7] transition">
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2.5 md:gap-3">
                                <div class="w-8 h-8 md:w-9 md:h-9 rounded-full flex items-center justify-center text-white font-bold text-[10px] md:text-xs shrink-0 shadow-sm"
                                    style="background-color: {{ '#' . substr(md5($customer->name), 0, 6) }}">
                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs md:text-sm font-bold text-gray-900 truncate">{{ $customer->name }}
                                    </p>
                                    <p class="text-[9px] md:text-[11px] text-gray-400">ID: #{{ str_pad($customer->id, 4,
                                        '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <p class="text-xs md:text-[13px] text-gray-700 leading-none">{{ $customer->email }}</p>
                            <p class="text-[10px] md:text-[12px] text-gray-400 mt-1">{{ $customer->phone ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap text-center">
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] md:text-[11px] font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                {{ $customer->total_orders }} x
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <p class="text-xs md:text-sm font-black text-[#0F4C20]">Rp {{
                                number_format($customer->total_spent ?? 0, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            <a href="{{ route('seller.customers.show', $customer->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-lg bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm"
                                title="Lihat Detail">
                                <x-heroicon-s-eye class="w-3.5 h-3.5 md:w-4 md:h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400 italic">
                                <x-heroicon-o-users class="w-10 h-10 md:w-12 md:h-12 mb-3 opacity-20" />
                                <p class="text-xs md:text-sm">Belum ada data pelanggan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
        <div class="px-4 py-3 bg-white border-t border-gray-100 overflow-x-auto">
            {{ $customers->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection