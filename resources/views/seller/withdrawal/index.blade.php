@extends('layouts.seller')

@section('title', 'Pencairan Dana')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Pencairan Dana</h1>
        <p class="text-sm text-gray-600 mt-1">Kelola pencairan dana dari penjualan Anda</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6">
        {{ session('error') }}
    </div>
    @endif

    <!-- Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <p class="text-xs text-gray-500 mb-2">Total Penjualan</p>
            <p class="text-2xl font-bold text-gray-800">
                Rp {{ number_format($totalSales, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <p class="text-xs text-gray-500 mb-2">Dana Tersedia</p>
            <p class="text-2xl font-bold text-green-600">
                Rp {{ number_format($availableBalance, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <p class="text-xs text-gray-500 mb-2">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">
                Rp {{ number_format($pendingWithdrawals, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <p class="text-xs text-gray-500 mb-2">Sudah Ditarik</p>
            <p class="text-2xl font-bold text-gray-600">
                Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Request Withdrawal Form -->
    <div class="bg-white p-6 rounded-xl shadow-sm border mb-8">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Ajukan Pencairan Dana</h2>
        
        <!-- Admin Fee Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Informasi Biaya Admin:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Biaya tetap: <strong>Rp {{ number_format($adminFeeFixed, 0, ',', '.') }}</strong></li>
                        <li>Biaya persentase: <strong>{{ $adminFeePercentage }}%</strong> dari jumlah pencairan</li>
                        <li>Dana yang Anda terima = Jumlah Pencairan - Biaya Admin</li>
                    </ul>
                </div>
            </div>
        </div>
        
        @if($bankAccounts->count() == 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <p class="text-sm text-yellow-700">
                Anda belum menambahkan rekening bank. 
                <a href="{{ route('seller.profile') }}" class="underline font-semibold">Tambahkan rekening</a> terlebih dahulu.
            </p>
        </div>
        @elseif($availableBalance < $minAmount)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <p class="text-sm text-yellow-700">
                Saldo minimal untuk pencairan adalah Rp {{ number_format($minAmount, 0, ',', '.') }}
            </p>
        </div>
        @else
        <form action="{{ route('seller.withdrawals.store') }}" method="POST" id="withdrawalForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rekening Tujuan</label>
                    <select name="bank_account_id" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500" required>
                        <option value="">Pilih Rekening</option>
                        @foreach($bankAccounts as $account)
                        <option value="{{ $account->id }}">
                            {{ $account->bank_name }} - {{ $account->account_number }} ({{ $account->account_name }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Pencairan</label>
                    <input type="number" name="amount" id="withdrawalAmount"
                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500" 
                           placeholder="Minimal Rp {{ number_format($minAmount, 0, ',', '.') }}" 
                           min="{{ $minAmount }}" 
                           max="{{ $availableBalance }}" 
                           required>
                    <p class="text-xs text-gray-500 mt-1">Maksimal: Rp {{ number_format($availableBalance, 0, ',', '.') }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                </div>
            </div>

            <!-- Fee Calculation Preview -->
            <div id="feePreview" class="hidden mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Rincian Pencairan:</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Pencairan:</span>
                        <span class="font-semibold" id="previewAmount">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Admin:</span>
                        <span class="font-semibold text-red-600" id="previewFee">Rp 0</span>
                    </div>
                    <hr class="border-gray-300">
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-800">Dana Yang Diterima:</span>
                        <span class="font-bold text-green-600 text-lg" id="previewReceived">Rp 0</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="mt-6 bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                Ajukan Pencairan
            </button>
        </form>
        @endif
    </div>

    <!-- Withdrawal History -->
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Riwayat Pencairan</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Rekening</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Jumlah</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Biaya Admin</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Diterima</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $withdrawal)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $withdrawal->requested_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 font-mono">#WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3">{{ $withdrawal->bankAccount->bank_name }}</td>
                        <td class="px-4 py-3 font-semibold">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-red-600">Rp {{ number_format($withdrawal->admin_fee, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 font-bold text-green-600">Rp {{ number_format($withdrawal->total_received, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @if($withdrawal->status == 'pending')
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">Pending</span>
                            @elseif($withdrawal->status == 'approved')
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">Disetujui</span>
                            @elseif($withdrawal->status == 'completed')
                            <span class="px-3 py-1 rounded-full bg-green-600 text-white text-xs font-semibold">Selesai</span>
                            @else
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <button class="text-green-600 hover:underline text-xs">Detail</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            Belum ada riwayat pencairan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
        <div class="mt-4">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('withdrawalAmount');
    const feePreview = document.getElementById('feePreview');
    
    const adminFeeFixed = {{ $adminFeeFixed }};
    const adminFeePercentage = {{ $adminFeePercentage }};
    
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            const amount = parseFloat(this.value) || 0;
            
            if (amount >= {{ $minAmount }}) {
                // Calculate fees
                const percentageFee = (amount * adminFeePercentage) / 100;
                const totalAdminFee = adminFeeFixed + percentageFee;
                const totalReceived = amount - totalAdminFee;
                
                // Update preview
                document.getElementById('previewAmount').textContent = 'Rp ' + amount.toLocaleString('id-ID');
                document.getElementById('previewFee').textContent = 'Rp ' + totalAdminFee.toLocaleString('id-ID');
                document.getElementById('previewReceived').textContent = 'Rp ' + totalReceived.toLocaleString('id-ID');
                
                feePreview.classList.remove('hidden');
            } else {
                feePreview.classList.add('hidden');
            }
        });
    }
});
</script>
@endpush
