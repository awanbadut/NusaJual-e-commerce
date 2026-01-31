@extends('layouts.admin')

@section('title', 'Daftar Mitra - Admin Nusa Belanja')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-[28px] font-bold text-[#15803D] mb-1">Daftar Mitra</h1>
        <p class="text-[13px] text-[#78716C]">Pantau peforma semua mitra kamu diisini</p>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white p-5 rounded-2xl shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.mitra.index') }}" class="flex gap-4">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-[#9CA3AF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari Mitra yang terdaftar" 
                       class="w-full pl-10 pr-4 py-2.5 border border-[#D1D5DB] rounded-lg focus:ring-2 focus:ring-[#15803D] focus:border-transparent text-[13px] text-[#111827]">
            </div>
            <select name="sort" 
                    onchange="this.form.submit()"
                    class="px-4 py-2.5 border border-[#D1D5DB] rounded-lg focus:ring-2 focus:ring-[#15803D] text-[13px] text-[#15803D] font-medium bg-white min-w-[200px]">
                <option value="">Urutkan Berdasarkan</option>
                <option value="sales_desc" {{ request('sort') == 'sales_desc' ? 'selected' : '' }}>Penjualan Tertinggi</option>
                <option value="orders_desc" {{ request('sort') == 'orders_desc' ? 'selected' : '' }}>Pesanan Terbanyak</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
            </select>
        </form>
    </div>

    <!-- Alert jika ada pesan success -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-[13px]">
        {{ session('success') }}
    </div>
    @endif

    <!-- Tabel Mitra -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-[13px]">
            <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                <tr class="text-left">
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Nama Mitra</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Tanggal Masuk</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Alamat</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Banyak Penjualan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Total Penjualan</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-[#F9FDF7]">
                @forelse($stores as $index => $store)
                @php
                    // Array warna untuk avatar
                    $colors = [
                        'bg-[#FEF3C7] text-[#92400E]', // kuning
                        'bg-[#DBEAFE] text-[#1E40AF]', // biru
                        'bg-[#FCE7F3] text-[#9F1239]', // pink
                        'bg-[#E0E7FF] text-[#3730A3]', // indigo
                        'bg-[#FEF3C7] text-[#92400E]', // kuning
                        'bg-[#D1FAE5] text-[#065F46]', // hijau
                    ];
                    // Gunakan index asli dari pagination untuk warna konsisten
                    $colorIndex = (($stores->currentPage() - 1) * $stores->perPage() + $loop->index) % count($colors);
                    $colorClass = $colors[$colorIndex];
                @endphp
                <tr class="border-b border-[#E5E7EB] hover:bg-white transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 {{ $colorClass }} rounded-full flex items-center justify-center font-bold text-[15px]">
                                {{ strtoupper(substr($store->store_name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-[#111827] text-[14px]">{{ $store->store_name }}</p>
                                <p class="text-[11px] text-[#9CA3AF]">ID: MT-{{ str_pad($store->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-[13px] text-[#111827]">
                        {{ $store->created_at->format('d/m/y') }}
                    </td>
                    <td class="px-5 py-4 text-[13px] text-[#111827]">
                        {{ \Illuminate\Support\Str::limit($store->address, 30) }}
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FBBF24] text-white text-[12px] font-semibold">
                            {{ number_format($store->orders_count) }} Penjualan
                        </span>
                    </td>
                    <td class="px-5 py-4 font-bold text-[#111827] text-[14px]">
                        Rp {{ number_format($store->total_sales ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.mitra.show', $store->id) }}" 
                           class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#C2410C] hover:bg-[#9A3412] text-white transition"
                           title="Lihat Detail">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-[#9CA3AF]">
                        @if(request('search'))
                            Tidak ada mitra yang sesuai dengan pencarian "{{ request('search') }}"
                        @else
                            Belum ada mitra terdaftar
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-5 text-center text-[12px] text-[#78716C]">
        @if($stores->total() > 0)
        Menampilkan <span class="font-semibold">{{ $stores->firstItem() }}</span> sampai 
        <span class="font-semibold">{{ $stores->lastItem() }}</span> dari 
        <span class="font-semibold">{{ $stores->total() }}</span> data
        @endif
    </div>

    @if($stores->hasPages())
    <div class="mt-4 flex justify-center gap-2">
        {{-- Previous --}}
        @if ($stores->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg border border-[#D1D5DB] text-[#9CA3AF] text-[12px] cursor-not-allowed">‹</span>
        @else
            <a href="{{ $stores->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#D1D5DB] hover:bg-[#F3F4F6] text-[#111827] text-[12px]">‹</a>
        @endif

        {{-- Page numbers --}}
        @foreach ($stores->getUrlRange(1, $stores->lastPage()) as $page => $url)
            @if ($page == $stores->currentPage())
                <span class="px-3 py-1.5 rounded-lg bg-[#15803D] text-white text-[12px]">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg border border-[#D1D5DB] hover:bg-[#F3F4F6] text-[#111827] text-[12px]">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($stores->hasMorePages())
            <a href="{{ $stores->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#D1D5DB] hover:bg-[#F3F4F6] text-[#111827] text-[12px]">›</a>
        @else
            <span class="px-3 py-1.5 rounded-lg border border-[#D1D5DB] text-[#9CA3AF] text-[12px] cursor-not-allowed">›</span>
        @endif
    </div>
    @endif
@endsection
