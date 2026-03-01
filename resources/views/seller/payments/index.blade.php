@extends('layouts.seller')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran Customer')
@section('page-subtitle', 'Monitor pembayaran dari customer untuk orderan di toko kamu')

@section('content')
<div class="max-w-7xl px-2 sm:px-0 space-y-4 md:space-y-6">

    @if(session('success'))
    <div
        class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 md:p-4 rounded-lg mb-4 flex items-center gap-3 shadow-sm">
        <x-heroicon-s-check-circle class="w-5 h-5 shrink-0" />
        <p class="text-xs md:text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border border-gray-100">
        <form method="GET" action="{{ route('seller.payments.index') }}"
            class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 md:gap-4">

            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full lg:w-auto">
                <div class="relative w-full md:w-48">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full pl-3 pr-10 py-2 md:py-2.5 border border-gray-300 rounded-lg text-xs md:text-sm focus:ring-2 focus:ring-green-600 appearance-none bg-white transition cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status')=='paid' ? 'selected' : '' }}>Menunggu Konfirmasi
                        </option>
                        <option value="confirmed" {{ request('status')=='confirmed' ? 'selected' : '' }}>Terkonfirmasi
                        </option>
                        <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                        <x-heroicon-m-chevron-down class="w-4 h-4" />
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <div class="relative flex-1">
                        <div
                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400">
                        </div>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full pl-8 pr-2 py-2 border border-gray-300 rounded-lg text-[11px] md:text-sm focus:ring-2 focus:ring-green-600 transition bg-gray-50 md:bg-white">
                    </div>

                    <span class="text-gray-400 font-bold text-xs">-</span>

                    <div class="relative flex-1">
                        <div
                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400">
                        </div>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full pl-8 pr-2 py-2 border border-gray-300 rounded-lg text-[11px] md:text-sm focus:ring-2 focus:ring-green-600 transition bg-gray-50 md:bg-white">
                    </div>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit"
                        class="flex-1 lg:flex-none px-6 py-2 bg-green-800 text-white rounded-lg font-bold text-xs md:text-sm shadow-md active:scale-95 transition">
                        Filter
                    </button>

                    @if(request('status') || request('date_from') || request('date_to'))
                    <a href="{{ route('seller.payments.index') }}"
                        class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg font-bold text-xs md:text-sm border border-gray-200 flex items-center">
                        Reset
                    </a>
                    @endif
                </div>
            </div>

            <a href="{{ route('seller.payments.export', request()->query()) }}"
                class="w-full lg:w-auto px-4 py-2 border border-green-800 text-green-800 rounded-lg font-bold text-xs md:text-sm flex items-center justify-center gap-2 hover:bg-green-50 transition active:scale-95 shadow-sm">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                <span>Export CSV</span>
            </a>
        </form>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-blue-50 rounded-lg text-blue-600">
                    <x-heroicon-o-banknotes class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase">Total</span>
            </div>
            <p class="text-base md:text-xl font-black text-gray-900 truncate">Rp{{ number_format($totalRevenue, 0, ',',
                '.') }}</p>
            <p class="text-[8px] md:text-[10px] text-gray-400 mt-1 uppercase">Dari order selesai</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-green-50 rounded-lg text-green-600">
                    <x-heroicon-o-check-circle class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase">Confirm</span>
            </div>
            <p class="text-base md:text-xl font-black text-green-600 truncate">Rp{{ number_format($confirmedPayments, 0,
                ',', '.') }}</p>
            <p class="text-[8px] md:text-[10px] text-gray-400 mt-1 uppercase">Terverifikasi</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-yellow-50 rounded-lg text-yellow-600">
                    <x-heroicon-o-clock class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase">Menunggu</span>
            </div>
            <p class="text-base md:text-xl font-black text-yellow-600 truncate">Rp{{ number_format($pendingPayments, 0,
                ',', '.') }}</p>
            <p class="text-[8px] md:text-[10px] text-gray-400 mt-1 uppercase">Perlu Verifikasi</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-red-50 rounded-lg text-red-600">
                    <x-heroicon-o-x-circle class="w-4 h-4 md:w-5 md:h-5" />
                </div>
                <span class="text-[9px] md:text-xs font-bold text-gray-400 uppercase">Ditolak</span>
            </div>
            <p class="text-base md:text-xl font-black text-red-600">{{ $rejectedPayments }}</p>
            <p class="text-[8px] md:text-[10px] text-gray-400 mt-1 uppercase">Total Tolak</p>
        </div>
    </div>

    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="p-4 md:p-5 border-b border-gray-100">
            <h3 class="text-sm md:text-lg font-bold text-gray-900">Riwayat Pembayaran Customer</h3>
            <p class="text-[10px] md:text-sm text-gray-400 mt-0.5">Daftar transaksi pembayaran order toko Anda</p>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left whitespace-nowrap min-w-[900px]">
                <thead class="bg-[#DCFCE7] border-b border-[#BBF7D0]">
                    <tr>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                            Tanggal</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                            ID Order</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider">
                            Customer</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider text-right">
                            Jumlah</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider text-center">
                            Status</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider text-center">
                            Bukti</th>
                        <th
                            class="px-4 py-3 md:px-5 md:py-4 font-bold text-[#15803D] uppercase text-[10px] md:text-[11px] tracking-wider text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white text-xs md:text-sm">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-[#F9FDF7] transition">
                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <p class="font-bold text-gray-800">{{ $payment->created_at->format('d/m/Y') }}</p>
                            <p class="text-[9px] md:text-[11px] text-gray-400 mt-0.5">{{
                                $payment->created_at->format('H:i') }} WIB</p>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <a href="{{ route('seller.orders.show', $payment->order_id) }}"
                                class="text-[#0F4C20] font-mono font-bold hover:underline">
                                #ORD-{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-[10px] shrink-0"
                                    style="background-color: {{ '#' . substr(md5($payment->order->user->name), 0, 6) }}">
                                    {{ strtoupper(substr($payment->order->user->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-800 truncate max-w-[120px]">{{
                                        $payment->order->user->name }}</p>
                                    <p class="text-[9px] text-gray-400 truncate max-w-[120px]">{{
                                        $payment->order->user->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4 text-right">
                            <p class="font-black text-[#0F4C20]">Rp{{ number_format($payment->amount, 0, ',', '.') }}
                            </p>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4 text-center">
                            @php
                            $s = $payment->status;
                            $sClasses = [
                            'pending' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'paid' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'confirmed' => 'bg-green-100 text-green-700 border-green-200',
                            'rejected' => 'bg-red-100 text-red-800 border-red-200'
                            ];
                            $sLabels = ['pending' => 'Blm Bayar', 'paid' => 'Verifikasi', 'confirmed' => 'Confirm',
                            'rejected' => 'Tolak'];
                            @endphp
                            <span
                                class="inline-flex px-2 py-0.5 rounded-full text-[9px] md:text-[10px] font-bold border whitespace-nowrap {{ $sClasses[$s] ?? 'bg-gray-50' }}">
                                {{ $sLabels[$s] ?? ucfirst($s) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4 text-center">
                            @if($payment->payment_proof)
                            <button onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')"
                                class="inline-flex items-center gap-1 text-[10px] md:text-xs font-bold text-green-700 hover:text-green-900 bg-green-50 px-2 py-1 rounded-lg transition border border-green-100">
                                <x-heroicon-o-photo class="w-3.5 h-3.5" /> Lihat
                            </button>
                            @else
                            <span class="text-gray-300 text-[10px] italic">Kosong</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 md:px-5 md:py-4 text-center">
                            <a href="{{ route('seller.payments.show', $payment->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-lg bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm">
                                <x-heroicon-s-eye class="w-3.5 h-3.5 md:w-4 md:h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <x-heroicon-o-document-currency-dollar class="w-12 h-12 mb-3 opacity-20" />
                                <p class="text-sm font-medium">Belum ada data pembayaran</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
        <div class="px-4 py-3 bg-white border-t border-gray-100 overflow-x-auto">
            {{ $payments->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<div id="proofModal"
    class="hidden fixed inset-0 z-[60] flex items-center justify-center p-2 md:p-4 bg-black/80 backdrop-blur-sm transition-all duration-300 opacity-0"
    onclick="closeProofModal()">
    <div class="max-w-2xl w-full max-h-[90vh] relative bg-white rounded-xl shadow-2xl overflow-hidden animate-in zoom-in duration-300"
        onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-3 border-b border-gray-100">
            <h4 class="text-sm font-bold text-gray-900">Bukti Pembayaran</h4>
            <button onclick="closeProofModal()"
                class="p-1.5 bg-gray-100 text-gray-500 rounded-full hover:bg-gray-200 transition">
                <x-heroicon-m-x-mark class="w-5 h-5" />
            </button>
        </div>
        <div class="p-2 bg-gray-50 flex items-center justify-center">
            <img id="proofImage" src="" alt="Bukti Pembayaran"
                class="max-w-full max-h-[70vh] object-contain rounded shadow-sm">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function viewProof(url) {
    const modal = document.getElementById('proofModal');
    const img = document.getElementById('proofImage');
    img.src = url;
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('opacity-100');
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closeProofModal() {
    const modal = document.getElementById('proofModal');
    modal.classList.remove('opacity-100');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
    document.body.style.overflow = 'auto';
}
</script>
@endpush