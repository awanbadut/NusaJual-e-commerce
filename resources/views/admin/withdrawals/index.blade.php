@extends('layouts.admin')

@section('title', 'Kelola Pencairan Dana')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-[28px] font-bold text-[#15803D] mb-1">Kelola Pencairan Dana</h1>
            <p class="text-[13px] text-[#78716C]">Proses permintaan pencairan dana dari mitra</p>
        </div>
        <a href="{{ route('admin.withdrawals.export', request()->query()) }}"
            class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
            Download CSV
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3">
        <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 flex items-center gap-3">
        <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-xl">
            <p class="text-xs text-yellow-700 font-semibold mb-1">PENDING</p>
            <p class="text-3xl font-bold text-yellow-800">{{ $stats['pending_count'] }}</p>
            <p class="text-sm text-yellow-600 mt-2">
                Total: Rp {{ number_format($stats['total_pending_amount'], 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-xl">
            <p class="text-xs text-blue-700 font-semibold mb-1">DISETUJUI</p>
            <p class="text-3xl font-bold text-blue-800">{{ $stats['approved_count'] }}</p>
        </div>

        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-xl">
            <p class="text-xs text-green-700 font-semibold mb-1">SELESAI</p>
            <p class="text-3xl font-bold text-green-800">{{ $stats['completed_count'] }}</p>
            <p class="text-sm text-green-600 mt-2">
                Total: Rp {{ number_format($stats['total_completed_amount'], 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-xl">
            <p class="text-xs text-red-700 font-semibold mb-1">DITOLAK</p>
            <p class="text-3xl font-bold text-red-800">{{ $stats['rejected_count'] }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border mb-6">
        <form method="GET" action="{{ route('admin.withdrawals.index') }}"
            class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            <div class="w-full">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 h-[42px]">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="w-full">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 h-[42px]">
            </div>

            <div class="w-full">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 h-[42px]">
            </div>

            <div class="w-full">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Toko/ID</label>
                <div class="flex gap-2 w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / ID..."
                        class="flex-1 min-w-0 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 h-[42px]">

                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 h-[42px] whitespace-nowrap transition-colors">
                        Filter
                    </button>
                </div>
            </div>

        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-5 py-4 font-semibold text-[#15803D]">ID</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Tanggal</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Nama Toko</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Rekening</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Jumlah</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Biaya Admin</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Diterima Mitra</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Status</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $withdrawal)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">#WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-semibold">{{ $withdrawal->requested_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $withdrawal->requested_at->format('H:i') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-semibold">{{ $withdrawal->store->store_name }}</div>
                            <div class="text-xs text-gray-500">{{ $withdrawal->store->user->name }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-semibold">{{ $withdrawal->bankAccount->bank_name }}</div>
                            <div class="text-xs text-gray-500">{{ $withdrawal->bankAccount->account_number }}</div>
                            <div class="text-xs text-gray-400">{{ $withdrawal->bankAccount->account_name }}</div>
                        </td>
                        <td class="px-4 py-3 font-semibold">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-red-600 font-semibold">Rp {{ number_format($withdrawal->admin_fee, 0,
                            ',', '.') }}</td>
                        <td class="px-4 py-3 font-bold text-green-600">Rp {{ number_format($withdrawal->total_received,
                            0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @if($withdrawal->status == 'pending')
                            <span
                                class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">Pending</span>
                            @elseif($withdrawal->status == 'approved')
                            <span
                                class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">Disetujui</span>
                            @elseif($withdrawal->status == 'completed')
                            <span
                                class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">Selesai</span>
                            @else
                            <span
                                class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.withdrawals.show', $withdrawal->id) }}"
                                    class="text-blue-600 hover:underline text-xs font-semibold">Detail</a>

                                @if($withdrawal->status == 'pending')
                                <button onclick="openProcessModal({{ $withdrawal->id }})"
                                    class="text-green-600 hover:underline text-xs font-semibold">Proses</button>
                                <button onclick="openRejectModal({{ $withdrawal->id }})"
                                    class="text-red-600 hover:underline text-xs font-semibold">Tolak</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="font-semibold">Belum ada permintaan pencairan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $withdrawals->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<div id="processModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Proses Pencairan Dana</h3>

        <form id="processForm" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer <span
                        class="text-red-500">*</span></label>
                <input type="file" name="withdrawal_proof" accept="image/*" required
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max 2MB)</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Admin (Opsional)</label>
                <textarea name="admin_notes" rows="3"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500"
                    placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeProcessModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">
                    Proses & Transfer
                </button>
            </div>
        </form>
    </div>
</div>

<div id="rejectModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Tolak Pencairan Dana</h3>

        <form id="rejectForm" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan <span
                        class="text-red-500">*</span></label>
                <textarea name="admin_notes" rows="4" required
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500"
                    placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700">
                    Tolak Pencairan
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
    }

    function closeProcessModal() {
        document.getElementById('processModal').classList.add('hidden');
    }

    function openRejectModal(withdrawalId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `/admin/withdrawals/${withdrawalId}/reject`;
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // Close modals when clicking outside
    document.getElementById('processModal').addEventListener('click', function(e) {
        if (e.target === this) closeProcessModal();
    });

    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
</script>
@endpush