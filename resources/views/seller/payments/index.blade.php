@extends('layouts.seller')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran Customer')
@section('page-subtitle', 'Monitor pembayaran dari customer untuk orderan di toko kamu')

@section('content')
<div class="max-w-7xl">

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex items-center justify-between mb-6">
        <!-- Filter Form -->
        <form method="GET" class="flex items-center gap-3">
            <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600"
                   placeholder="Dari Tanggal">
            
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-600"
                   placeholder="Sampai Tanggal">
            
            <button type="submit" class="px-4 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
                Filter
            </button>

            @if(request('status') || request('date_from') || request('date_to'))
            <a href="{{ route('seller.payments.index') }}" class="px-4 py-2.5 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition text-sm font-medium">
                Reset
            </a>
            @endif
        </form>

        <!-- Export Button -->
        <a href="{{ route('seller.payments.export', request()->query()) }}" class="px-4 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Download CSV
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Total Pendapatan</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Dari order completed</p>
        </div>

        <!-- Confirmed -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Pembayaran Dikonfirmasi</span>
            </div>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($confirmedPayments, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Sudah diverifikasi admin</p>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Menunggu Konfirmasi</span>
            </div>
            <p class="text-2xl font-bold text-yellow-600">Rp {{ number_format($pendingPayments, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Perlu verifikasi admin</p>
        </div>

        <!-- Rejected -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Pembayaran Ditolak</span>
            </div>
            <p class="text-2xl font-bold text-red-600">{{ $rejectedPayments }}</p>
            <p class="text-xs text-gray-500 mt-1">Total ditolak</p>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Riwayat Pembayaran Customer</h3>
            <p class="text-sm text-gray-600 mt-1">Daftar pembayaran yang dilakukan customer untuk order di toko kamu</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-green-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bukti</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <!-- Date -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $payment->created_at->format('d M Y') }}<br>
                            <span class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }}</span>
                        </td>

                        <!-- Order ID -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">
                            <a href="{{ route('seller.orders.show', $payment->order_id) }}" class="text-green-700 hover:underline">
                                #ORD-{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>

                        <!-- Customer -->
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="font-semibold">{{ $payment->order->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $payment->order->user->email }}</div>
                        </td>

                        <!-- Amount -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->status == 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    Belum Bayar
                                </span>
                            @elseif($payment->status == 'paid')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    Menunggu Konfirmasi
                                </span>
                            @elseif($payment->status == 'confirmed')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white">
                                    Terkonfirmasi
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    Ditolak
                                </span>
                            @endif
                        </td>

                        <!-- Proof -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->payment_proof)
                            <button onclick="viewProof('{{ asset('storage/' . $payment->payment_proof) }}')" 
                                    class="text-green-700 font-medium text-sm flex items-center gap-1 hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Lihat
                            </button>
                            @else
                            <span class="text-gray-400 text-sm">Belum upload</span>
                            @endif
                        </td>

                        <!-- Action -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('seller.payments.show', $payment->id) }}" 
                               class="text-green-700 font-medium text-sm hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>Belum ada data pembayaran</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium">{{ $payments->firstItem() }}</span> sampai 
                    <span class="font-medium">{{ $payments->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $payments->total() }}</span> data
                </p>
                
                {{ $payments->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal View Proof -->
<div id="proofModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeProofModal()">
    <div class="max-w-4xl max-h-screen relative">
        <button onclick="closeProofModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
        <img id="proofImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-screen rounded-lg">
    </div>
</div>

@endsection

@push('scripts')
<script>
function viewProof(url) {
    document.getElementById('proofImage').src = url;
    document.getElementById('proofModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeProofModal() {
    document.getElementById('proofModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endpush
