@extends('layouts.seller')

@section('title', 'Pencairan Dana')
@section('page-title', 'Pencairan Dana Penjualan')
@section('page-subtitle', 'Kelola pencairan dana dari penjualan Anda')
@section('content')

<div class="max-w-7xl px-2 sm:px-0 space-y-4 md:space-y-6">

    @if(session('success'))
    <div
        class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 md:p-4 rounded-lg mb-4 flex items-center gap-3 shadow-sm">
        <x-heroicon-s-check-circle class="w-5 h-5 shrink-0" />
        <span class="text-xs md:text-sm font-semibold">{!! session('success') !!}</span>
    </div>
    @endif

    @if(session('error'))
    <div
        class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 md:p-4 rounded-lg mb-4 flex items-center gap-3 shadow-sm">
        <x-heroicon-s-x-circle class="w-5 h-5 shrink-0" />
        <span class="text-xs md:text-sm font-semibold">{!! session('error') !!}</span>
    </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-blue-50 rounded-lg text-blue-600 shrink-0">
                    <x-heroicon-o-banknotes class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Total Sales</span>
            </div>
            <p class="text-base md:text-xl font-black text-gray-900 truncate">Rp{{ number_format($totalSales, 0, ',',
                '.') }}</p>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex flex-col justify-between ring-1 ring-green-100">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-green-50 rounded-lg text-green-600 shrink-0">
                    <x-heroicon-o-wallet class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Tersedia</span>
            </div>
            <p class="text-base md:text-xl font-black text-green-600 truncate">Rp{{ number_format($withdrawableBalance,
                0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-yellow-50 rounded-lg text-yellow-600 shrink-0">
                    <x-heroicon-o-clock class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Pending</span>
            </div>
            <p class="text-base md:text-xl font-black text-yellow-600 truncate">Rp{{ number_format($pendingWithdrawals,
                0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-gray-50 rounded-lg text-gray-600 shrink-0">
                    <x-heroicon-o-archive-box-arrow-down class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Ditarik</span>
            </div>
            <p class="text-base md:text-xl font-black text-gray-700 truncate">Rp{{ number_format($totalWithdrawn, 0,
                ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
            <x-heroicon-s-currency-dollar class="w-5 h-5 text-[#0F4C20]" />
            <h2 class="text-sm md:text-lg font-bold text-gray-900">Ajukan Pencairan Dana</h2>
        </div>

        <div class="p-4 md:p-6 lg:p-8">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 md:p-4 mb-5 md:mb-6 flex items-start gap-3">
                <x-heroicon-s-information-circle class="w-4 h-4 md:w-5 md:h-5 text-blue-600 shrink-0 mt-0.5" />
                <div class="text-[10px] md:text-sm text-blue-800 leading-relaxed">
                    <p class="font-bold mb-1">Ketentuan Pencairan:</p>
                    <ul class="space-y-0.5 md:space-y-1">
                        <li>• Biaya admin tetap: <strong>Rp{{ number_format($adminFeeFlat, 0, ',', '.') }}</strong></li>
                        <li>• Saldo minimal penarikan: <strong>Rp{{ number_format($minAmount, 0, ',', '.') }}</strong>
                        </li>
                    </ul>
                </div>
            </div>

            @if($bankAccounts->count() == 0)
            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl flex items-center gap-3">
                <x-heroicon-s-exclamation-triangle class="w-5 h-5 text-yellow-600 shrink-0" />
                <p class="text-xs md:text-sm text-yellow-800 font-medium">
                    Belum ada rekening bank. <a href="{{ route('seller.profile') }}"
                        class="underline font-bold hover:text-yellow-900">Tambah sekarang</a>.
                </p>
            </div>
            @elseif($withdrawableBalance < $minAmount) <div
                class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl flex items-center gap-3 text-yellow-800">
                <x-heroicon-s-exclamation-triangle class="w-5 h-5 text-yellow-600 shrink-0" />
                <p class="text-xs md:text-sm font-medium">
                    Saldo belum mencapai minimal pencairan <strong>Rp{{ number_format($minAmount, 0, ',', '.')
                        }}</strong>.
                </p>
        </div>
        @else
        <form action="{{ route('seller.withdrawals.store') }}" method="POST" id="withdrawalForm"
            class="space-y-4 md:space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label class="block text-xs md:text-sm font-bold text-gray-700 mb-1.5">Rekening Tujuan <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="bank_account_id" required
                            class="w-full px-3 py-2.5 md:px-4 md:py-3 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-600 appearance-none bg-white text-xs md:text-sm">
                            <option value="">Pilih Rekening</option>
                            @foreach($bankAccounts as $account)
                            <option value="{{ $account->id }}">
                                {{ $account->bank_name }} - {{ $account->account_number }} ({{ $account->account_name
                                }})
                            </option>
                            @endforeach
                        </select>
                        <div
                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <x-heroicon-m-chevron-down class="w-4 h-4 md:w-5 md:h-5" />
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs md:text-sm font-bold text-gray-700 mb-1.5">Jumlah Pencairan <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 md:pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-xs md:text-sm font-bold">Rp</span>
                        </div>
                        <input type="text" id="withdrawalAmount" required autocomplete="off"
                            class="w-full pl-8 md:pl-10 pr-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-600 text-xs md:text-sm font-black">
                    </div>
                    <p class="text-[9px] md:text-xs text-gray-400 mt-1.5 italic">
                        Maksimal penarikan: <span class="font-bold text-gray-600">Rp{{
                            number_format($withdrawableBalance, 0, ',', '.') }}</span>
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs md:text-sm font-bold text-gray-700 mb-1.5">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-600 text-xs md:text-sm"
                        placeholder="Tambahkan catatan jika diperlukan"></textarea>
                </div>
            </div>

            <div id="feePreview" class="hidden p-4 md:p-5 bg-[#F9FDF7] rounded-xl border border-green-100 mt-6">
                <h3 class="text-xs md:text-sm font-bold text-green-800 uppercase tracking-widest mb-3">Estimasi
                    Pencairan</h3>
                <div class="space-y-2 text-xs md:text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Jumlah Pengajuan:</span>
                        <span class="font-bold" id="previewAmount">Rp0</span>
                    </div>
                    <div class="flex justify-between text-red-500">
                        <span>Biaya Admin (Flat):</span>
                        <span class="font-bold" id="previewAdminFee">- Rp{{ number_format($adminFeeFlat, 0, ',', '.')
                            }}</span>
                    </div>
                    <div class="pt-2 mt-2 border-t border-dashed border-green-200 flex justify-between items-center">
                        <span class="font-black text-gray-800">Dana Diterima:</span>
                        <span class="font-black text-lg md:text-2xl text-green-600" id="previewReceived">Rp0</span>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="bg-[#0F4C20] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#0b3a18] transition shadow-lg flex items-center justify-center gap-2 w-full md:w-auto active:scale-95">
                <x-heroicon-s-paper-airplane class="w-4 h-4 md:w-5 md:h-5" />
                Ajukan Pencairan
            </button>
        </form>
        @endif
    </div>
</div>

<div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden border border-gray-100">
    <div class="p-4 md:p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm md:text-lg font-bold text-gray-900 uppercase tracking-tight">Riwayat Pencairan</h3>
    </div>

    <div class="overflow-x-auto custom-scrollbar pb-2">
        <table class="w-full text-left whitespace-nowrap min-w-[850px]">
            <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                <tr>
                    <th
                        class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                        Tanggal</th>
                    <th
                        class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                        ID WD</th>
                    <th
                        class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                        Bank Rekening</th>
                    <th
                        class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider text-right">
                        Jumlah</th>
                    <th
                        class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider text-center">
                        Status</th>
                    <th
                        class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider text-center">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white text-xs md:text-sm">
                @forelse($withdrawals as $withdrawal)
                <tr class="hover:bg-[#F9FDF7] transition">
                    <td class="px-4 py-3 md:px-5 md:py-4">
                        <p class="font-bold text-gray-800">{{ $withdrawal->requested_at->format('d/m/Y') }}</p>
                        <p class="text-[10px] text-gray-400">{{ $withdrawal->requested_at->format('H:i') }} WIB</p>
                    </td>
                    <td class="px-4 py-3 md:px-5 md:py-4 font-mono font-bold text-[#0F4C20]">#WD-{{
                        str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-4 py-3 md:px-5 md:py-4">
                        <p class="font-bold text-gray-800 leading-none">{{ $withdrawal->bankAccount->bank_name }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ $withdrawal->bankAccount->account_number }}</p>
                    </td>
                    <td class="px-4 py-3 md:px-5 md:py-4 text-right">
                        <p class="font-black text-gray-900">Rp{{ number_format($withdrawal->amount, 0, ',', '.') }}</p>
                        <p class="text-[9px] text-red-500">Admin Fee: Rp{{ number_format($withdrawal->admin_fee, 0, ',',
                            '.') }}</p>
                    </td>
                    <td class="px-4 py-3 md:px-5 md:py-4 text-center">
                        @php
                        $wStatus = $withdrawal->status;
                        $wClasses = [
                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'approved' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'completed' => 'bg-green-50 text-green-700 border-green-200',
                        'rejected' => 'bg-red-50 text-red-700 border-red-200'
                        ];
                        @endphp
                        <span
                            class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold border whitespace-nowrap {{ $wClasses[$wStatus] ?? 'bg-gray-50' }}">
                            {{ ucfirst($wStatus) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 md:px-5 md:py-4 text-center">
                        <a href="{{ route('seller.withdrawals.show', $withdrawal->id) }}"
                            class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm">
                            <x-heroicon-s-eye class="w-3.5 h-3.5" />
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <div class="flex flex-col items-center">
                            <x-heroicon-o-inbox class="w-12 h-12 opacity-20 mb-2" />
                            <p class="text-xs md:text-sm">Belum ada riwayat pencairan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($withdrawals->hasPages())
    <div class="px-4 py-3 bg-white border-t border-gray-100">
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
    const withdrawalForm = document.getElementById('withdrawalForm');
    
    const adminFeeFlat = {{ $adminFeeFlat }};
    const minAmount = {{ $minAmount }};
    const maxAmount = {{ $withdrawableBalance }};
    
    if (!amountInput) return;
    
    function formatRupiah(angka) {
        let number_string = angka.toString().replace(/[^0-9]/g, '');
        let split = number_string.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }
    
    function updatePreview(amount) {
        if (amount >= minAmount) {
            const totalReceived = Math.max(0, amount - adminFeeFlat);
            document.getElementById('previewAmount').textContent = 'Rp ' + formatRupiah(amount);
            document.getElementById('previewReceived').textContent = 'Rp ' + formatRupiah(totalReceived);
            feePreview.classList.remove('hidden');
        } else {
            feePreview.classList.add('hidden');
        }
    }
    
    let hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'amount';
    amountInput.form.appendChild(hiddenInput);
    
    amountInput.addEventListener('input', function() {
        let numericValue = this.value.replace(/[^0-9]/g, '');
        this.value = formatRupiah(numericValue);
        hiddenInput.value = numericValue;
        updatePreview(parseInt(numericValue) || 0);
    });
    
    withdrawalForm.addEventListener('submit', function(e) {
        const amount = parseInt(hiddenInput.value) || 0;
        if (amount < minAmount || amount > maxAmount) {
            e.preventDefault();
            alert('❌ Jumlah tidak valid. Harap periksa saldo minimal dan maksimal.');
            return false;
        }
    });
});
</script>
@endpush