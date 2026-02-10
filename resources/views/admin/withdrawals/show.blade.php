@extends('layouts.admin')

@section('title', 'Detail Pencairan Dana')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-4xl">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.withdrawals.index') }}" 
           class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pencairan Dana</h1>
            <p class="text-sm text-gray-600 mt-1">#WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        @if($withdrawal->status == 'pending')
        <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-bold">⏳ Menunggu Proses</span>
        @elseif($withdrawal->status == 'approved')
        <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-sm font-bold">✅ Disetujui</span>
        @elseif($withdrawal->status == 'completed')
        <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-bold">✅ Selesai</span>
        @else
        <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-bold">❌ Ditolak</span>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Store Info -->
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                </svg>
                Informasi Toko
            </h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500 text-xs">Nama Toko</p>
                    <p class="font-semibold">{{ $withdrawal->store->store_name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs">Pemilik</p>
                    <p class="font-semibold">{{ $withdrawal->store->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs">Email</p>
                    <p class="font-semibold">{{ $withdrawal->store->user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs">No. HP</p>
                    <p class="font-semibold">{{ $withdrawal->store->user->phone ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Bank Info -->
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                </svg>
                Rekening Tujuan
            </h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500 text-xs">Nama Bank</p>
                    <p class="font-semibold">{{ $withdrawal->bankAccount->bank_name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs">Nomor Rekening</p>
                    <p class="font-semibold font-mono">{{ $withdrawal->bankAccount->account_number }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs">Nama Pemilik</p>
                    <p class="font-semibold">{{ $withdrawal->bankAccount->account_name }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details -->
    <div class="bg-white p-6 rounded-xl shadow-sm border mb-6">
        <h3 class="font-bold text-gray-800 mb-4">Rincian Pencairan</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Tanggal Request:</span>
                <span class="font-semibold">{{ $withdrawal->requested_at->format('d F Y, H:i') }} WIB</span>
            </div>
            <hr>
            <div class="flex justify-between">
                <span class="text-gray-600">Jumlah Pencairan:</span>
                <span class="font-semibold">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Biaya Admin (Flat):</span>
                <span class="font-semibold text-red-600">- Rp {{ number_format($withdrawal->admin_fee, 0, ',', '.') }}</span>
            </div>
            <hr class="border-gray-300">
            <div class="flex justify-between">
                <span class="font-bold text-gray-800">Dana yang Ditransfer:</span>
                <span class="font-bold text-green-600 text-lg">Rp {{ number_format($withdrawal->total_received, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($withdrawal->notes)
        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-xs font-semibold text-blue-800 mb-1">Catatan Mitra:</p>
            <p class="text-sm text-blue-700">{{ $withdrawal->notes }}</p>
        </div>
        @endif

        @if($withdrawal->admin_notes)
        <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
            <p class="text-xs font-semibold text-yellow-800 mb-1">Catatan Admin:</p>
            <p class="text-sm text-yellow-700">{{ $withdrawal->admin_notes }}</p>
        </div>
        @endif

        @if($withdrawal->withdrawal_proof)
        <div class="mt-4">
            <p class="text-sm font-semibold text-gray-700 mb-2">Bukti Transfer:</p>
            <a href="{{ asset('storage/' . $withdrawal->withdrawal_proof) }}" target="_blank">
                <img src="{{ asset('storage/' . $withdrawal->withdrawal_proof) }}" 
                     alt="Bukti Transfer" 
                     class="w-full max-w-md rounded-lg border">
            </a>
        </div>
        @endif
    </div>

    <!-- Actions -->
    @if($withdrawal->status == 'pending')
    <div class="flex gap-4">
        <button onclick="openProcessModal({{ $withdrawal->id }})" 
                class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 transition flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            Proses & Transfer
        </button>
        <button onclick="openRejectModal({{ $withdrawal->id }})" 
                class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-red-700 transition flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
            Tolak
        </button>
    </div>
    @endif
</div>

<!-- Modals (same as index.blade.php) -->
<!-- Process Modal -->
<div id="processModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Proses Pencairan Dana</h3>
        
        <form action="{{ route('admin.withdrawals.process', $withdrawal->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer <span class="text-red-500">*</span></label>
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

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Tolak Pencairan Dana</h3>
        
        <form action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
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
    document.getElementById('processModal').classList.remove('hidden');
}

function closeProcessModal() {
    document.getElementById('processModal').classList.add('hidden');
}

function openRejectModal(withdrawalId) {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

document.getElementById('processModal').addEventListener('click', function(e) {
    if (e.target === this) closeProcessModal();
});

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endpush
