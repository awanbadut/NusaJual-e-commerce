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
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3">
        <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{!! session('success') !!}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 flex items-center gap-3">
        <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span>{!! session('error') !!}</span>
    </div>
    @endif

    <!-- Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- ✅ UPDATED: Total Sales + Shipping --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-xl shadow-lg text-dark">
            <p class="text-xs opacity-80 mb-2">Total Penjualan + Ongkir</p>
            <p class="text-2xl font-bold">
                Rp {{ number_format($totalSales, 0, ',', '.') }}
            </p>
            <p class="text-xs opacity-70 mt-2">Produk + Ongkir (Completed)</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-xl shadow-lg text-dark">
            <p class="text-xs opacity-80 mb-2">Dana Tersedia</p>
            <p class="text-2xl font-bold">
                Rp {{ number_format($withdrawableBalance, 0, ',', '.') }}
            </p>
            <p class="text-xs opacity-70 mt-2">Siap dicairkan</p>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-6 rounded-xl shadow-lg text-dark">
            <p class="text-xs opacity-80 mb-2">Pending</p>
            <p class="text-2xl font-bold">
                Rp {{ number_format($pendingWithdrawals, 0, ',', '.') }}
            </p>
            <p class="text-xs opacity-70 mt-2">Menunggu proses</p>
        </div>

        <div class="bg-gradient-to-br from-gray-500 to-gray-600 p-6 rounded-xl shadow-lg text-dark">
            <p class="text-xs opacity-80 mb-2">Sudah Ditarik</p>
            <p class="text-2xl font-bold">
                Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}
            </p>
            <p class="text-xs opacity-70 mt-2">Total penarikan</p>
        </div>
    </div>

    <!-- Request Withdrawal Form -->
    <div class="bg-white p-6 rounded-xl shadow-sm border mb-8">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
            </svg>
            Ajukan Pencairan Dana
        </h2>
        
        {{-- ✅ UPDATED: Admin Fee Info --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Informasi Biaya Admin:</p>
                    <ul class="space-y-1">
                        <li>• Biaya tetap: <strong>Rp {{ number_format($adminFeeFlat, 0, ',', '.') }}</strong> per pencairan</li>
                        <li>• Dana yang Anda terima = Jumlah Pencairan - Rp {{ number_format($adminFeeFlat, 0, ',', '.') }}</li>
                        <li>• Ongkir sudah termasuk dalam saldo (untuk order completed)</li>
                    </ul>
                </div>
            </div>
        </div>
        
        @if($bankAccounts->count() == 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded flex items-center gap-3">
            <svg class="w-6 h-6 text-yellow-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-yellow-700">
                Anda belum menambahkan rekening bank. 
                <a href="{{ route('seller.profile') }}" class="underline font-semibold hover:text-yellow-900">Tambahkan rekening</a> terlebih dahulu.
            </p>
        </div>
        @elseif($withdrawableBalance < $minAmount)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded flex items-center gap-3">
            <svg class="w-6 h-6 text-yellow-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-yellow-700">
                Saldo minimal untuk pencairan adalah <strong>Rp {{ number_format($minAmount, 0, ',', '.') }}</strong>
            </p>
        </div>
        @else
        <form action="{{ route('seller.withdrawals.store') }}" method="POST" id="withdrawalForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rekening Tujuan <span class="text-red-500">*</span></label>
                    <select name="bank_account_id" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        <option value="">Pilih Rekening</option>
                        @foreach($bankAccounts as $account)
                        <option value="{{ $account->id }}">
                            {{ $account->bank_name }} - {{ $account->account_number }} ({{ $account->account_name }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Pencairan <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="withdrawalAmount"
                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                           placeholder="Minimal Rp {{ number_format($minAmount, 0, ',', '.') }}" 
                           min="{{ $minAmount }}" 
                           max="{{ $withdrawableBalance }}" 
                           required>
                    <p class="text-xs text-gray-500 mt-1">Maksimal: Rp {{ number_format($withdrawableBalance, 0, ',', '.') }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                </div>
            </div>

            {{-- ✅ Fee Calculation Preview --}}
            <div id="feePreview" class="hidden mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Rincian Pencairan:</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Pencairan:</span>
                        <span class="font-semibold" id="previewAmount">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Admin (Flat):</span>
                        <span class="font-semibold text-red-600" id="previewAdminFee">- Rp {{ number_format($adminFeeFlat, 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-gray-300">
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-800">Dana Yang Diterima:</span>
                        <span class="font-bold text-green-600 text-lg" id="previewReceived">Rp 0</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="mt-6 bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition shadow-md hover:shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
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
                        <td class="px-4 py-3">{{ $withdrawal->requested_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 font-mono text-xs">#WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-semibold">{{ $withdrawal->bankAccount->bank_name }}</div>
                            <div class="text-xs text-gray-500">{{ $withdrawal->bankAccount->account_number }}</div>
                        </td>
                        <td class="px-4 py-3 font-semibold">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-red-600 font-semibold">Rp {{ number_format($withdrawal->admin_fee, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 font-bold text-green-600">Rp {{ number_format($withdrawal->total_received, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @if($withdrawal->status == 'pending')
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">Pending</span>
                            @elseif($withdrawal->status == 'approved')
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">Disetujui</span>
                            @elseif($withdrawal->status == 'completed')
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Selesai</span>
                            @else
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('seller.withdrawals.show', $withdrawal->id) }}" class="text-green-600 hover:underline text-xs font-semibold">Detail</a>
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
    
    const adminFeeFlat = {{ $adminFeeFlat }};
    const minAmount = {{ $minAmount }};
    
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            const amount = parseFloat(this.value) || 0;
            
            if (amount >= minAmount) {
                const totalAdminFee = adminFeeFlat;
                const totalReceived = amount - totalAdminFee;
                
                // Update preview
                document.getElementById('previewAmount').textContent = 'Rp ' + amount.toLocaleString('id-ID');
                document.getElementById('previewReceived').textContent = 'Rp ' + Math.max(0, totalReceived).toLocaleString('id-ID');
                
                feePreview.classList.remove('hidden');
            } else {
                feePreview.classList.add('hidden');
            }
        });
    }
});
</script>
@endpush
