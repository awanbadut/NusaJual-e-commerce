@extends('layouts.seller')

@section('title', 'Pencairan Dana')
@section('page-title', 'Pencairan Dana Penjualan')
@section('page-subtitle', 'Kelola pencairan dana dari penjualan Anda')
@section('content')

<div class="max-w-7xl">

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
        <x-heroicon-s-check-circle class="w-5 h-5" />
        <span>{!! session('success') !!}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center gap-3">
        <x-heroicon-s-x-circle class="w-5 h-5" />
        <span>{!! session('error') !!}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        {{-- Total Sales --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <x-heroicon-o-banknotes class="w-5 h-5" />
                </div>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Penjualan</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Produk + Ongkir (Completed)</p>
        </div>

        {{-- Dana Tersedia --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <x-heroicon-o-wallet class="w-5 h-5" />
                </div>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dana Tersedia</span>
            </div>
            <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($withdrawableBalance, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-500 mt-1">Siap dicairkan</p>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-2 bg-yellow-50 rounded-lg text-yellow-600">
                    <x-heroicon-o-clock class="w-5 h-5" />
                </div>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending</span>
            </div>
            <p class="text-2xl font-bold text-yellow-600 mt-2">Rp {{ number_format($pendingWithdrawals, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-500 mt-1">Menunggu proses</p>
        </div>

        {{-- Sudah Ditarik --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-2 bg-gray-100 rounded-lg text-gray-600">
                    <x-heroicon-o-archive-box-arrow-down class="w-5 h-5" />
                </div>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Sudah Ditarik</span>
            </div>
            <p class="text-2xl font-bold text-gray-700 mt-2">Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total penarikan sukses</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-5 border-b border-gray-200 bg-gray-50 flex items-center gap-2">
            <x-heroicon-s-currency-dollar class="w-5 h-5 text-gray-700" />
            <h2 class="text-lg font-bold text-gray-900">Ajukan Pencairan Dana</h2>
        </div>

        <div class="p-6">
            {{-- Admin Fee Info --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                <x-heroicon-s-information-circle class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Informasi Biaya Admin:</p>
                    <ul class="space-y-1 text-xs">
                        <li>• Biaya tetap: <strong>Rp {{ number_format($adminFeeFlat, 0, ',', '.') }}</strong> per
                            pencairan</li>
                        <li>• Dana yang Anda terima = Jumlah Pencairan - Rp {{ number_format($adminFeeFlat, 0, ',', '.')
                            }}</li>
                        <li>• Ongkir sudah termasuk dalam saldo (untuk order completed)</li>
                    </ul>
                </div>
            </div>

            @if($bankAccounts->count() == 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded flex items-center gap-3">
                <x-heroicon-s-exclamation-triangle class="w-6 h-6 text-yellow-600" />
                <p class="text-sm text-yellow-700">
                    Anda belum menambahkan rekening bank.
                    <a href="{{ route('seller.profile') }}"
                        class="underline font-semibold hover:text-yellow-900">Tambahkan rekening</a> terlebih dahulu.
                </p>
            </div>
            @elseif($withdrawableBalance < $minAmount) <div
                class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded flex items-center gap-3">
                <x-heroicon-s-exclamation-triangle class="w-6 h-6 text-yellow-600" />
                <p class="text-sm text-yellow-700">
                    Saldo minimal untuk pencairan adalah <strong>Rp {{ number_format($minAmount, 0, ',', '.')
                        }}</strong>
                </p>
        </div>
        @else
        <form action="{{ route('seller.withdrawals.store') }}" method="POST" id="withdrawalForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rekening Tujuan <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="bank_account_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 appearance-none pr-10 cursor-pointer text-sm"
                            required>
                            <option value="">Pilih Rekening</option>
                            @foreach($bankAccounts as $account)
                            <option value="{{ $account->id }}">
                                {{ $account->bank_name }} - {{ $account->account_number }} ({{ $account->account_name
                                }})
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <x-heroicon-m-chevron-down class="w-5 h-5 text-gray-400" />
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Pencairan <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm font-semibold">Rp</span>
                        </div>

                        <input type="text" id="withdrawalAmount"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                            placeholder="0" required autocomplete="off">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Minimal: <span class="font-semibold">Rp {{ number_format($minAmount, 0, ',', '.') }}</span> |
                        Maksimal: <span class="font-semibold">Rp {{ number_format($withdrawableBalance, 0, ',', '.')
                            }}</span>
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                        placeholder="Tambahkan catatan jika diperlukan"></textarea>
                </div>
            </div>

            {{-- Fee Calculation Preview --}}
            <div id="feePreview" class="hidden mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Rincian Pencairan:</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Pencairan:</span>
                        <span class="font-semibold" id="previewAmount">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Admin (Flat):</span>
                        <span class="font-semibold text-red-600" id="previewAdminFee">- Rp {{
                            number_format($adminFeeFlat, 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-gray-300">
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-800">Dana Yang Diterima:</span>
                        <span class="font-bold text-green-600 text-lg" id="previewReceived">Rp 0</span>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="mt-6 bg-[#15803D] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#166534] transition shadow-md flex items-center justify-center gap-2 w-full md:w-auto">
                <x-heroicon-s-paper-airplane class="w-5 h-5" />
                Ajukan Pencairan
            </button>
        </form>
        @endif
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
    <div class="p-5 border-b border-gray-200 bg-white">
        <h3 class="text-lg font-bold text-gray-900">Riwayat Pencairan</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-[13px]">
            <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                <tr class="text-left">
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Tanggal</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">ID</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Rekening</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Jumlah</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Biaya Admin</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Diterima</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Status</th>
                    <th class="px-5 py-4 font-semibold text-[#15803D]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($withdrawals as $withdrawal)
                <tr class="hover:bg-[#F9FDF7] transition">
                    <td class="px-5 py-4 whitespace-nowrap text-gray-700">
                        {{ $withdrawal->requested_at->format('d M Y') }}<br>
                        <span class="text-[11px] text-gray-500">{{ $withdrawal->requested_at->format('H:i') }}</span>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap font-mono font-bold text-gray-900 text-xs">
                        #WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $withdrawal->bankAccount->bank_name }}</div>
                        <div class="text-[11px] text-gray-500">{{ $withdrawal->bankAccount->account_number }}</div>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap font-semibold text-gray-900">
                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap text-red-600 font-semibold text-xs">
                        Rp {{ number_format($withdrawal->admin_fee, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap font-bold text-green-600">
                        Rp {{ number_format($withdrawal->total_received, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        @if($withdrawal->status == 'pending')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                            Pending
                        </span>
                        @elseif($withdrawal->status == 'approved')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-100 text-blue-800 border border-blue-200">
                            Disetujui
                        </span>
                        @elseif($withdrawal->status == 'completed')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-[#DCFCE7] text-[#166534] border border-[#BBF7D0]">
                            Selesai
                        </span>
                        @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-red-100 text-red-800 border border-red-200">
                            Ditolak
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <a href="{{ route('seller.withdrawals.show', $withdrawal->id) }}"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm"
                            title="Lihat Detail">
                            <x-heroicon-s-eye class="w-4 h-4" />
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <x-heroicon-o-inbox class="w-12 h-12 text-gray-300 mb-3" />
                            <p class="text-sm font-medium text-gray-900">Belum ada riwayat pencairan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($withdrawals->hasPages())
    {{ $withdrawals->appends([
    'payments_page' => request('payments_page'),
    'confirmed_page' => request('confirmed_page'),
    'orders_page' => request('orders_page')
    ])->links() }}
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
    
    // ✅ FORMAT RUPIAH FUNCTION
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
    
    // ✅ PARSE RUPIAH TO NUMBER
    function parseRupiah(rupiah) {
        return parseInt(rupiah.replace(/\./g, '')) || 0;
    }
    
    // ✅ UPDATE PREVIEW
    function updatePreview(amount) {
        if (amount >= minAmount) {
            const totalAdminFee = adminFeeFlat;
            const totalReceived = Math.max(0, amount - totalAdminFee);
            
            document.getElementById('previewAmount').textContent = 'Rp ' + formatRupiah(amount);
            document.getElementById('previewAdminFee').textContent = '- Rp ' + formatRupiah(adminFeeFlat);
            document.getElementById('previewReceived').textContent = 'Rp ' + formatRupiah(totalReceived);
            
            feePreview.classList.remove('hidden');
        } else {
            feePreview.classList.add('hidden');
        }
    }
    
    // ✅ BUAT HIDDEN INPUT (untuk submit value asli)
    let hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'amount';
    hiddenInput.id = 'hiddenAmount';
    amountInput.form.appendChild(hiddenInput);
    
    // ✅ UBAH INPUT JADI TEXT & HAPUS NAME
    amountInput.removeAttribute('name');
    amountInput.removeAttribute('min');
    amountInput.removeAttribute('max');
    amountInput.setAttribute('type', 'text');
    amountInput.setAttribute('inputmode', 'numeric');
    
    // ✅ EVENT: INPUT (REAL-TIME)
    amountInput.addEventListener('input', function(e) {
        let cursorPosition = this.selectionStart;
        let oldLength = this.value.length;
        
        // Ambil angka saja
        let numericValue = this.value.replace(/[^0-9]/g, '');
        
        // Format ke Rupiah
        let formatted = formatRupiah(numericValue);
        this.value = formatted;
        
        // Set hidden input
        hiddenInput.value = numericValue;
        
        // Restore cursor position (agar tidak lompat ke akhir)
        let newLength = formatted.length;
        let newCursorPosition = cursorPosition + (newLength - oldLength);
        this.setSelectionRange(newCursorPosition, newCursorPosition);
        
        // Update preview
        const amount = parseInt(numericValue) || 0;
        updatePreview(amount);
    });
    
    // ✅ EVENT: KEYDOWN (BLOCK INVALID KEYS)
    amountInput.addEventListener('keydown', function(e) {
        // Allow: backspace, delete, tab, escape, enter
        if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            return;
        }
        
        // Block jika bukan angka
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && 
            (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
    // ✅ EVENT: PASTE (CLEAN & FORMAT)
    amountInput.addEventListener('paste', function(e) {
        e.preventDefault();
        let paste = (e.clipboardData || window.clipboardData).getData('text');
        let numericValue = paste.replace(/[^0-9]/g, '');
        
        this.value = formatRupiah(numericValue);
        hiddenInput.value = numericValue;
        
        updatePreview(parseInt(numericValue) || 0);
    });
    
    // ✅ VALIDATION SEBELUM SUBMIT
    withdrawalForm.addEventListener('submit', function(e) {
        const amount = parseInt(hiddenInput.value) || 0;
        
        if (amount < minAmount) {
            e.preventDefault();
            alert('❌ Jumlah minimal pencairan adalah Rp ' + formatRupiah(minAmount));
            amountInput.focus();
            return false;
        }
        
        if (amount > maxAmount) {
            e.preventDefault();
            alert('❌ Jumlah maksimal pencairan adalah Rp ' + formatRupiah(maxAmount));
            amountInput.focus();
            return false;
        }
        
        if (amount <= 0) {
            e.preventDefault();
            alert('❌ Jumlah pencairan harus lebih dari Rp 0');
            amountInput.focus();
            return false;
        }
        
        return true;
    });
    
    // ✅ SET PLACEHOLDER
    amountInput.placeholder = 'Contoh: 1.000.000';
});
</script>
@endpush