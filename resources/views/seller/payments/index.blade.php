@extends('layouts.seller')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran Customer')
@section('page-subtitle', 'Monitor pembayaran dari customer untuk orderan di toko kamu')

@section('content')
<div class="max-w-7xl">

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
        <x-heroicon-s-check-circle class="w-5 h-5" />
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('seller.payments.index') }}"
            class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full lg:w-auto">

                <div class="relative w-full md:w-48">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600 appearance-none pr-10 cursor-pointer bg-white transition">
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

                <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full md:w-44">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <x-heroicon-o-calendar class="w-5 h-5" />
                        </div>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600 transition shadow-sm bg-white">
                    </div>

                    <span class="hidden md:block text-gray-400 font-bold">s/d</span>

                    <div class="relative w-full md:w-44">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <x-heroicon-o-calendar class="w-5 h-5" />
                        </div>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600 transition shadow-sm bg-white">
                    </div>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit"
                        class="flex-1 md:flex-none px-6 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-semibold shadow-sm active:scale-95">
                        Filter
                    </button>

                    @if(request('status') || request('date_from') || request('date_to'))
                    <a href="{{ route('seller.payments.index') }}"
                        class="px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm transition flex items-center justify-center gap-1">
                        Reset
                    </a>
                    @endif
                </div>
            </div>

            <a href="{{ route('seller.payments.export', request()->query()) }}"
                class="w-full lg:w-auto px-6 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-semibold flex items-center justify-center gap-2 shadow-sm active:scale-95">
                <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
                <span>Download CSV</span>
            </a>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                        <x-heroicon-o-banknotes class="w-5 h-5" />
                    </div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pendapatan</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Dari order completed</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <x-heroicon-o-check-circle class="w-5 h-5" />
                    </div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dikonfirmasi</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($confirmedPayments, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-500 mt-1">Sudah diverifikasi admin</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-yellow-50 rounded-lg text-yellow-600">
                        <x-heroicon-o-clock class="w-5 h-5" />
                    </div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Menunggu</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-yellow-600 mt-2">Rp {{ number_format($pendingPayments, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Perlu verifikasi admin</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-red-50 rounded-lg text-red-600">
                        <x-heroicon-o-x-circle class="w-5 h-5" />
                    </div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ditolak</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-red-600 mt-2">{{ $rejectedPayments }}</p>
            <p class="text-xs text-gray-500 mt-1">Total pembayaran ditolak</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
        <div class="p-5 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-bold text-gray-900">Riwayat Pembayaran Customer</h3>
            <p class="text-sm text-gray-500 mt-1">Daftar pembayaran yang dilakukan customer untuk order di toko kamu</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Tanggal</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">ID Order</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Customer</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Jumlah</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Status</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Bukti</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-[#F9FDF7] transition">
                        <td class="px-5 py-4 whitespace-nowrap text-gray-700">
                            {{ $payment->created_at->format('d M Y') }}<br>
                            <span class="text-[11px] text-gray-500">{{ $payment->created_at->format('H:i') }} WIB</span>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap text-sm font-mono font-bold text-gray-900">
                            <a href="{{ route('seller.orders.show', $payment->order_id) }}"
                                class="text-[#15803D] hover:underline">
                                #ORD-{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-xs shrink-0"
                                    style="background-color: {{ '#' . substr(md5($payment->order->user->name), 0, 6) }}">
                                    {{ strtoupper(substr($payment->order->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $payment->order->user->name }}</p>
                                    <p class="text-[11px] text-gray-500">{{ Str::limit($payment->order->user->email, 20)
                                        }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($payment->status == 'pending')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                Belum Bayar
                            </span>
                            @elseif($payment->status == 'paid')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                Menunggu Konfirmasi
                            </span>
                            @elseif($payment->status == 'confirmed')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-[#DCFCE7] text-[#166534] border border-[#BBF7D0]">
                                Terkonfirmasi
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-red-100 text-red-800 border border-red-200">
                                Ditolak
                            </span>
                            @endif
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($payment->payment_proof)
                            <button onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')"
                                class="text-[#15803D] font-medium text-[12px] flex items-center gap-1 hover:underline transition">
                                <x-heroicon-o-document-magnifying-glass class="w-4 h-4" />
                                Lihat
                            </button>
                            @else
                            <span class="text-gray-400 text-[11px] italic">Belum upload</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('seller.payments.show', $payment->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#15803D] hover:bg-[#166534] transition text-white shadow-sm"
                                title="Lihat Detail">
                                <x-heroicon-s-eye class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <x-heroicon-o-document-currency-dollar class="w-12 h-12 text-gray-300 mb-3" />
                                <p class="text-sm font-medium text-gray-900">Belum ada data pembayaran</p>
                                <p class="text-xs text-gray-500 mt-1">Riwayat pembayaran akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
        {{ $payments->appends(request()->query())->links() }}
        @endif
    </div>
</div>

<div id="proofModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm transition-opacity duration-300"
    onclick="closeProofModal()">
    <div class="max-w-3xl max-h-[90vh] relative bg-white rounded-lg shadow-2xl overflow-hidden"
        onclick="event.stopPropagation()">
        <button onclick="closeProofModal()"
            class="absolute top-2 right-2 p-1 bg-black/50 text-white rounded-full hover:bg-black/70 transition z-10">
            <x-heroicon-m-x-mark class="w-6 h-6" />
        </button>
        <img id="proofImage" src="" alt="Bukti Pembayaran" class="w-full h-full object-contain max-h-[85vh]">
    </div>
</div>

@endsection

@push('scripts')
<script>
    function viewProof(url) {
    document.getElementById('proofImage').src = url;
    const modal = document.getElementById('proofModal');
    modal.classList.remove('hidden');
    // Simple animation for fade in
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
    }, 300); // Wait for transition
    document.body.style.overflow = 'auto';
}
</script>
@endpush