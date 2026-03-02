@extends('layouts.admin')

@section('title', 'Kelola Pencairan Dana')

@section('content')
<div class="px-2 sm:px-0 pb-6 md:pb-10">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6 md:mb-8">
        <div>
            <h1 class="text-xl md:text-[28px] font-bold text-[#15803D] mb-0.5 md:mb-1 tracking-tight">Kelola Pencairan
                Dana</h1>
            <p class="text-[10px] md:text-[13px] text-[#78716C] font-medium">Proses permintaan pencairan dana dari mitra
            </p>
        </div>
        <a href="{{ route('admin.withdrawals.export', request()->query()) }}"
            class="bg-green-600 text-white px-4 py-2 md:py-2.5 rounded-lg font-semibold hover:bg-green-700 transition flex items-center justify-center gap-2 text-xs md:text-sm shadow-sm active:scale-95 w-full md:w-auto">
            <x-heroicon-s-arrow-down-tray class="w-4 h-4" />
            Download CSV
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div
        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 md:p-4 rounded-xl mb-4 md:mb-6 flex items-center gap-2.5 md:gap-3 text-[11px] md:text-sm font-medium shadow-sm">
        <x-heroicon-s-check-circle class="w-4 h-4 md:w-6 md:h-6 shrink-0" />
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
    <div
        class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 md:p-4 rounded-xl mb-4 md:mb-6 flex items-center gap-2.5 md:gap-3 text-[11px] md:text-sm font-medium shadow-sm">
        <x-heroicon-s-x-circle class="w-4 h-4 md:w-6 md:h-6 shrink-0" />
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- STATISTICS CARDS (Grid 2 Kolom di Mobile) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mb-6 md:mb-8">
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 md:p-6 rounded-xl shadow-sm">
            <p class="text-[9px] md:text-xs text-yellow-700 font-semibold mb-1 tracking-wider">PENDING</p>
            <p class="text-xl md:text-3xl font-bold text-yellow-800">{{ $stats['pending_count'] }}</p>
            <p class="text-[9px] md:text-sm text-yellow-600 mt-1 md:mt-2 truncate">
                Total: Rp {{ number_format($stats['total_pending_amount'], 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 md:p-6 rounded-xl shadow-sm">
            <p class="text-[9px] md:text-xs text-blue-700 font-semibold mb-1 tracking-wider">DISETUJUI</p>
            <p class="text-xl md:text-3xl font-bold text-blue-800">{{ $stats['approved_count'] }}</p>
        </div>

        <div class="bg-green-50 border-l-4 border-green-500 p-4 md:p-6 rounded-xl shadow-sm">
            <p class="text-[9px] md:text-xs text-green-700 font-semibold mb-1 tracking-wider">SELESAI</p>
            <p class="text-xl md:text-3xl font-bold text-green-800">{{ $stats['completed_count'] }}</p>
            <p class="text-[9px] md:text-sm text-green-600 mt-1 md:mt-2 truncate">
                Total: Rp {{ number_format($stats['total_completed_amount'], 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-red-50 border-l-4 border-red-500 p-4 md:p-6 rounded-xl shadow-sm">
            <p class="text-[9px] md:text-xs text-red-700 font-semibold mb-1 tracking-wider">DITOLAK</p>
            <p class="text-xl md:text-3xl font-bold text-red-800">{{ $stats['rejected_count'] }}</p>
        </div>
    </div>

    {{-- FILTER BOX --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-100 mb-4 md:mb-6">

        {{-- INDIKATOR FILTER AKTIF --}}
        @if(request()->hasAny(['status', 'start_date', 'end_date', 'search']))
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center gap-2 text-[11px] md:text-sm">
            <span class="font-semibold text-gray-700">Filter Aktif:</span>
            <div class="flex flex-wrap gap-1.5 md:gap-2">
                @if(request('status'))
                <span
                    class="inline-flex items-center gap-1 px-2 md:px-3 py-0.5 md:py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">
                    Status: {{ ucfirst(request('status')) }}
                    <a href="{{ route('admin.withdrawals.index', array_filter(request()->except('status'))) }}"
                        class="hover:text-blue-900 transition">
                        <x-heroicon-s-x-mark class="w-3 h-3" />
                    </a>
                </span>
                @endif

                @if(request('start_date'))
                <span
                    class="inline-flex items-center gap-1 px-2 md:px-3 py-0.5 md:py-1 bg-green-100 text-green-700 rounded-full font-semibold">
                    Dari: {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }}
                    <a href="{{ route('admin.withdrawals.index', array_filter(request()->except('start_date'))) }}"
                        class="hover:text-green-900 transition">
                        <x-heroicon-s-x-mark class="w-3 h-3" />
                    </a>
                </span>
                @endif

                @if(request('end_date'))
                <span
                    class="inline-flex items-center gap-1 px-2 md:px-3 py-0.5 md:py-1 bg-green-100 text-green-700 rounded-full font-semibold">
                    Sampai: {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                    <a href="{{ route('admin.withdrawals.index', array_filter(request()->except('end_date'))) }}"
                        class="hover:text-green-900 transition">
                        <x-heroicon-s-x-mark class="w-3 h-3" />
                    </a>
                </span>
                @endif

                @if(request('search'))
                <span
                    class="inline-flex items-center gap-1 px-2 md:px-3 py-0.5 md:py-1 bg-purple-100 text-purple-700 rounded-full font-semibold">
                    Cari: "{{ request('search') }}"
                    <a href="{{ route('admin.withdrawals.index', array_filter(request()->except('search'))) }}"
                        class="hover:text-purple-900 transition">
                        <x-heroicon-s-x-mark class="w-3 h-3" />
                    </a>
                </span>
                @endif
            </div>
        </div>
        @endif

        {{-- FORM FILTER --}}
        <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="space-y-3 md:space-y-4">
            {{-- BARIS 1: INPUT FILTER --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                {{-- STATUS --}}
                <div class="w-full relative">
                    <label class="block text-[11px] md:text-sm font-bold text-gray-700 mb-1 md:mb-2">Status</label>
                    <select name="status"
                        class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 h-[38px] md:h-[42px] text-xs md:text-sm appearance-none bg-white">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Disetujui
                        </option>
                        <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Selesai
                        </option>
                        <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <x-heroicon-m-chevron-down
                        class="w-4 h-4 text-gray-400 absolute right-3 top-[26px] md:top-[34px] pointer-events-none" />
                </div>

                {{-- TANGGAL MULAI --}}
                <div class="w-full">
                    <label class="block text-[11px] md:text-sm font-bold text-gray-700 mb-1 md:mb-2">Tanggal
                        Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 h-[38px] md:h-[42px] text-xs md:text-sm">
                </div>

                {{-- TANGGAL AKHIR --}}
                <div class="w-full">
                    <label class="block text-[11px] md:text-sm font-bold text-gray-700 mb-1 md:mb-2">Tanggal
                        Akhir</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 h-[38px] md:h-[42px] text-xs md:text-sm">
                </div>

                {{-- CARI TOKO/ID --}}
                <div class="w-full">
                    <label class="block text-[11px] md:text-sm font-bold text-gray-700 mb-1 md:mb-2">Cari
                        Toko/ID</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / ID..."
                        class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 h-[38px] md:h-[42px] text-xs md:text-sm">
                </div>
            </div>

            {{-- BARIS 2: TOMBOL AKSI --}}
            <div class="flex gap-2 justify-end pt-2 md:pt-0">
                <a href="{{ route('admin.withdrawals.index') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-bold text-[11px] md:text-sm transition flex items-center justify-center">
                    Reset
                </a>
                <button type="submit"
                    class="px-5 py-2 bg-green-600 text-white rounded-lg font-bold text-[11px] md:text-sm hover:bg-green-700 transition flex items-center gap-1.5 active:scale-95 shadow-sm">
                    <x-heroicon-s-funnel class="w-3.5 h-3.5 md:w-4 md:h-4" />
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left text-xs md:text-[13px] min-w-[800px] md:min-w-[1000px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wide whitespace-nowrap">
                            ID</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wide whitespace-nowrap">
                            Tanggal</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wide whitespace-nowrap">
                            Nama Toko</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wide whitespace-nowrap">
                            Rekening</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wide whitespace-nowrap text-right">
                            Jumlah</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wide whitespace-nowrap text-right">
                            Admin</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wide whitespace-nowrap text-right">
                            Diterima</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wide whitespace-nowrap text-center">
                            Status</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] text-center whitespace-nowrap text-[10px] md:text-[11px] uppercase tracking-wide">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-[#F9FDF7]">
                    @forelse($withdrawals as $withdrawal)
                    <tr class="hover:bg-green-50/40 transition">
                        <td
                            class="px-4 py-3 md:px-5 md:py-4 font-mono font-bold text-[#0F4C20] whitespace-nowrap text-[11px] md:text-xs">
                            #WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <div class="text-[11px] md:text-sm font-bold text-gray-800">{{
                                $withdrawal->requested_at->format('d M Y') }}</div>
                            <div class="text-[9px] md:text-xs text-gray-400 mt-0.5">{{
                                $withdrawal->requested_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <div
                                class="font-bold text-gray-900 text-[11px] md:text-sm truncate max-w-[120px] md:max-w-[150px]">
                                {{ $withdrawal->store->store_name }}</div>
                            <div
                                class="text-[9px] md:text-xs text-gray-500 mt-0.5 truncate max-w-[120px] md:max-w-[150px]">
                                {{ $withdrawal->store->user->name }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 whitespace-nowrap">
                            <div class="text-[11px] md:text-sm font-bold text-gray-800">{{
                                $withdrawal->bankAccount->bank_name }}</div>
                            <div class="text-[9px] md:text-xs font-mono text-gray-500 my-0.5">{{
                                $withdrawal->bankAccount->account_number }}</div>
                            <div
                                class="text-[9px] md:text-[11px] text-gray-400 uppercase truncate max-w-[100px] md:max-w-[120px]">
                                a.n {{ $withdrawal->bankAccount->account_name }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-bold text-gray-700 whitespace-nowrap text-right">
                            Rp{{ number_format($withdrawal->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 font-bold text-red-500 whitespace-nowrap text-right">
                            -Rp{{ number_format($withdrawal->admin_fee, 0, ',', '.') }}
                        </td>
                        <td
                            class="px-4 py-3 md:px-5 md:py-4 font-black text-[#15803D] whitespace-nowrap text-right text-sm">
                            Rp{{ number_format($withdrawal->total_received, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            @php
                            $wStatus = $withdrawal->status;
                            $wClasses = [
                            'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            'approved' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'completed' => 'bg-[#15803D] text-white border-transparent',
                            'rejected' => 'bg-red-50 text-red-700 border-red-200'
                            ];
                            $wLabels = ['pending' => 'Pending', 'approved' => 'Disetujui', 'completed' => 'Selesai',
                            'rejected' => 'Ditolak'];
                            @endphp
                            <span
                                class="inline-flex items-center px-2 py-0.5 md:px-2.5 md:py-1 rounded-full text-[9px] md:text-[10px] font-bold border uppercase tracking-tight {{ $wClasses[$wStatus] ?? 'bg-gray-100' }}">
                                {{ $wLabels[$wStatus] ?? ucfirst($wStatus) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 md:px-5 md:py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5 md:gap-2">
                                <a href="{{ route('admin.withdrawals.show', $withdrawal->id) }}"
                                    class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition shadow-sm"
                                    title="Lihat Detail">
                                    <x-heroicon-s-eye class="w-3.5 h-3.5 md:w-4 md:h-4" />
                                </a>

                                @if($withdrawal->status == 'pending')
                                <button onclick="openProcessModal({{ $withdrawal->id }})"
                                    class="px-2.5 md:px-3 py-1 md:py-1.5 bg-[#15803D] text-white rounded-lg text-[9px] md:text-[11px] font-bold hover:bg-[#166534] transition shadow-sm active:scale-95">
                                    Proses
                                </button>
                                <button onclick="openRejectModal({{ $withdrawal->id }})"
                                    class="px-2.5 md:px-3 py-1 md:py-1.5 bg-red-100 text-red-700 rounded-lg text-[9px] md:text-[11px] font-bold hover:bg-red-200 transition shadow-sm active:scale-95">
                                    Tolak
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center opacity-30">
                                <x-heroicon-s-banknotes class="w-12 h-12 mb-3" />
                                <p class="text-sm font-bold uppercase tracking-widest">Belum ada permintaan pencairan
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($withdrawals->hasPages())
        <div class="px-4 md:px-6 py-3 md:py-4 border-t border-gray-100 bg-white">
            {{ $withdrawals->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL PROSES PENCAIRAN --}}
<div id="processModal"
    class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm transition-opacity">
    <div
        class="bg-white rounded-2xl md:rounded-3xl shadow-2xl max-w-md w-full p-5 md:p-6 animate-in zoom-in-95 duration-200">
        <h3 class="text-base md:text-lg font-black text-gray-900 mb-4 uppercase tracking-tight">Proses Pencairan Dana
        </h3>
        <form id="processForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-[11px] md:text-sm font-bold text-gray-700 mb-1.5">Upload Bukti Transfer
                        <span class="text-red-500">*</span></label>
                    <input type="file" name="withdrawal_proof" accept="image/*" required
                        class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 md:file:py-2 file:px-3 md:file:px-4 file:rounded-lg file:border-0 file:text-[10px] md:file:text-xs file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg p-1.5 md:p-2 bg-gray-50 focus:ring-1 focus:ring-green-500">
                    <p class="text-[9px] md:text-[10px] text-gray-400 mt-1 italic">Format: JPG, PNG (Max 2MB)</p>
                </div>
                <div>
                    <label class="block text-[11px] md:text-sm font-bold text-gray-700 mb-1.5">Catatan Admin
                        (Opsional)</label>
                    <textarea name="admin_notes" rows="2"
                        class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 text-xs md:text-sm bg-gray-50 focus:bg-white transition"
                        placeholder="Pesan untuk mitra..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="button" onclick="closeProcessModal()"
                    class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-xs md:text-sm font-bold hover:bg-gray-50 transition active:scale-95 shadow-sm">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-[#15803D] text-white rounded-xl text-xs md:text-sm font-bold hover:bg-[#166534] transition active:scale-95 shadow-md">
                    Transfer
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TOLAK PENCAIRAN --}}
<div id="rejectModal"
    class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm transition-opacity">
    <div
        class="bg-white rounded-2xl md:rounded-3xl shadow-2xl max-w-md w-full p-5 md:p-6 animate-in zoom-in-95 duration-200">
        <h3 class="text-base md:text-lg font-black text-red-600 mb-4 uppercase tracking-tight flex items-center gap-2">
            <x-heroicon-s-exclamation-triangle class="w-5 h-5 md:w-6 md:h-6" /> Tolak Pencairan
        </h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-[11px] md:text-sm font-bold text-gray-700 mb-1.5">Alasan Penolakan <span
                        class="text-red-500">*</span></label>
                <textarea name="admin_notes" rows="3" required
                    class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-red-500 text-xs md:text-sm bg-gray-50 focus:bg-white transition"
                    placeholder="Jelaskan alasan penolakan secara spesifik..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-xs md:text-sm font-bold hover:bg-gray-50 transition active:scale-95 shadow-sm">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-xl text-xs md:text-sm font-bold hover:bg-red-700 transition active:scale-95 shadow-md">
                    Tolak WD
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openProcessModal(withdrawalId) {
        const modal = document.getElementById('processModal');
        const form = document.getElementById('processForm');
        form.action = `/admin/withdrawals/${withdrawalId}/process`;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeProcessModal() {
        document.getElementById('processModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openRejectModal(withdrawalId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `/admin/withdrawals/${withdrawalId}/reject`;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('processModal').addEventListener('click', function(e) { if (e.target === this) closeProcessModal(); });
    document.getElementById('rejectModal').addEventListener('click', function(e) { if (e.target === this) closeRejectModal(); });
</script>
<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 4px;
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f9fafb;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #d1d5db;
    }
</style>
@endpush