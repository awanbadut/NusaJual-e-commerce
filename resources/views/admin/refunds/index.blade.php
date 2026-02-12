@extends('layouts.admin')

@section('title', 'Manajemen Refund - Nusa Belanja Admin')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-[28px] font-bold text-[#15803D] mb-1">Manajemen Refund</h1>
            <p class="text-[13px] text-[#78716C]">Kelola permintaan pengembalian dana dari pembeli</p>
        </div>
        <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-[#E5E7EB]">
            <p class="text-[11px] text-[#78716C] mb-1">Total Pending</p>
            <p class="text-[24px] font-bold text-[#DC2626]">{{ $pendingRefunds->total() }}</p>
        </div>
    </div>

    @if(session('success'))
    <div
        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-[13px] flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div
        class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-[13px] flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd" />
        </svg>
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-[13px]">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8 border border-[#E5E7EB]">
        <div class="px-5 py-4 bg-white border-b border-[#E5E7EB] flex justify-between items-center">
            <h2 class="font-bold text-[16px] text-[#111827]">Permintaan Refund Pending</h2>
            <span
                class="px-3 py-1 rounded-full bg-[#FEF2F2] text-[#DC2626] text-[11px] font-bold border border-[#FCA5A5]">
                {{ $pendingRefunds->total() }} Pending
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-5 py-4 font-semibold text-[#15803D]">No. Refund</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">No. Order</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Pembeli</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Rekening</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Jumlah Refund</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Tanggal Request</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7]">
                    @forelse($pendingRefunds as $refund)
                    <tr class="border-b border-[#E5E7EB] hover:bg-white transition">
                        <td class="px-5 py-4 font-mono text-[#DC2626] font-bold">{{ $refund->refund_number }}</td>
                        <td class="px-5 py-4 font-mono text-[#111827]">{{ $refund->order->order_number }}</td>
                        <td class="px-5 py-4">
                            <div class="text-[#111827] font-semibold">{{ $refund->user->name }}</div>
                            <div class="text-[#6B7280] text-[11px]">{{ $refund->user->email }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-[#111827] font-semibold">{{ $refund->bank_name }}</div>
                            <div class="text-[#6B7280] text-[11px] font-mono">{{ $refund->account_number }}</div>
                            <div class="text-[#6B7280] text-[11px]">a.n. {{ $refund->account_holder }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-[#DC2626] font-bold">Rp {{ number_format($refund->refund_amount, 0, ',',
                                '.') }}</div>
                            <div class="text-[#6B7280] text-[10px]">Order: Rp {{ number_format($refund->order_amount, 0,
                                ',', '.') }}</div>
                            <div class="text-[#B91C1C] text-[10px]">Admin 5%: Rp {{ number_format($refund->admin_fee, 0,
                                ',', '.') }}</div>
                        </td>
                        <td class="px-5 py-4 text-[#111827]">
                            {{ $refund->requested_at->format('d M Y') }}<br>
                            <span class="text-[10px] text-[#6B7280]">{{ $refund->requested_at->format('H:i') }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <button onclick="openProcessModal({{ $refund->id }})"
                                class="bg-[#15803D] text-white px-3 py-1.5 rounded-lg hover:bg-[#166534] text-[11px] font-semibold mb-1 w-full transition">
                                Proses
                            </button>
                            <button onclick="openRejectModal({{ $refund->id }}, '{{ $refund->refund_number }}')"
                                class="bg-[#DC2626] text-white px-3 py-1.5 rounded-lg hover:bg-[#B91C1C] text-[11px] font-semibold w-full transition">
                                Tolak
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-[#9CA3AF]">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="font-semibold text-[13px]">Tidak ada permintaan refund pending</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendingRefunds->hasPages())
        <div class="px-5 py-4 border-t border-[#E5E7EB]">
            {{ $pendingRefunds->links() }}
        </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-[#E5E7EB]">
        <div class="px-5 py-4 bg-white border-b border-[#E5E7EB] flex justify-between items-center">
            <h2 class="font-bold text-[16px] text-[#111827]">Riwayat Refund</h2>
            <span
                class="px-3 py-1 rounded-full bg-[#F3F4F6] text-[#374151] text-[11px] font-bold border border-[#E5E7EB]">
                {{ $processedRefunds->total() }} Riwayat
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead class="bg-[#DCFCE7] border-t border-b border-[#BBF7D0]">
                    <tr class="text-left">
                        <th class="px-5 py-4 font-semibold text-[#15803D]">No. Refund</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">No. Order</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Pembeli</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Jumlah</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Status</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Diproses</th>
                        <th class="px-5 py-4 font-semibold text-[#15803D]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F9FDF7]">
                    @forelse($processedRefunds as $refund)
                    <tr class="border-b border-[#E5E7EB] hover:bg-white transition">
                        <td class="px-5 py-4 font-mono text-[#6B7280]">{{ $refund->refund_number }}</td>
                        <td class="px-5 py-4 font-mono text-[#111827]">{{ $refund->order->order_number }}</td>
                        <td class="px-5 py-4 text-[#111827]">{{ $refund->user->name }}</td>
                        <td class="px-5 py-4 font-bold text-[#111827]">Rp {{ number_format($refund->refund_amount, 0,
                            ',', '.') }}</td>
                        <td class="px-5 py-4">
                            @if($refund->status === 'processed')
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-[#DCFCE7] text-[#166534] text-[11px] font-semibold border border-[#BBF7D0]">
                                ✓ Processed
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-[#FEF2F2] text-[#991B1B] text-[11px] font-semibold border border-[#FECaca]">
                                ✗ Rejected
                            </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-[#6B7280] text-[11px]">
                            {{ $refund->processed_at ? $refund->processed_at->format('d M Y H:i') : '-' }}<br>
                            <span class="text-[#9CA3AF]">oleh {{ $refund->processedBy->name ?? 'Admin' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($refund->refund_proof)
                            <a href="{{ route('admin.refunds.viewProof', $refund->id) }}" target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white border border-green-200 transition-all shadow-sm text-[11px] font-medium">
                                <x-heroicon-s-eye class="w-3.5 h-3.5" />
                                Lihat Bukti
                            </a>
                            @else
                            <span class="text-[#9CA3AF] text-[11px]">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-[#9CA3AF]">
                            Belum ada riwayat refund
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($processedRefunds->hasPages())
        <div class="px-5 py-4 border-t border-[#E5E7EB]">
            {{ $processedRefunds->links() }}
        </div>
        @endif
    </div>

</div>

<div id="processModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeProcessModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <div class="text-center pt-6 pb-4 px-6 border-b border-gray-100">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                    <path fill-rule="evenodd"
                        d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="text-[18px] font-bold text-[#111827]">Proses Refund</h2>
            <p class="text-[11px] text-[#6B7280] mt-1">Upload bukti transfer ke pembeli</p>
        </div>

        <form id="processForm" action="" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div id="refundDetails" class="space-y-3 mb-5 text-[12px]">
            </div>

            <div class="mb-4">
                <label class="block text-[11px] font-semibold text-[#111827] mb-2">
                    Upload Bukti Transfer <span class="text-red-500">*</span>
                </label>
                <div id="upload_area"
                    class="border-2 border-dashed border-[#D1D5DB] rounded-xl p-6 text-center hover:border-[#15803D] transition cursor-pointer"
                    onclick="document.getElementById('refund_proof').click()">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <p class="text-[11px] text-[#111827] font-medium mb-1">Klik untuk upload</p>
                    <p class="text-[10px] text-[#6B7280]">JPG, PNG (Max 2MB)</p>
                    <input type="file" id="refund_proof" name="refund_proof" accept="image/jpeg,image/png,image/jpg"
                        class="hidden" required onchange="showFileName(this)">
                </div>
                <p id="file_name" class="text-[10px] text-[#15803D] mt-2 hidden flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span></span>
                </p>
            </div>

            <div class="mb-5">
                <label class="block text-[11px] font-semibold text-[#111827] mb-2">
                    Catatan Admin (Opsional)
                </label>
                <textarea name="admin_notes" rows="2"
                    class="w-full px-3 py-2 border border-[#D1D5DB] rounded-lg text-[12px] focus:ring-2 focus:ring-[#15803D] focus:border-transparent"
                    placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeProcessModal()"
                    class="flex-1 px-4 py-3 border border-[#D1D5DB] text-[#111827] rounded-xl text-[13px] font-semibold hover:bg-[#F3F4F6] transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-[#15803D] text-white rounded-xl text-[13px] font-semibold hover:bg-[#166534] transition">
                    Proses Refund
                </button>
            </div>
        </form>
    </div>
</div>

<div id="rejectModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl relative p-6">
        <button type="button" onclick="closeRejectModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <div class="text-center mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="text-[18px] font-bold text-[#111827]">Tolak Refund?</h2>
            <p id="reject_refund_number" class="text-[11px] text-[#6B7280] mt-1"></p>
        </div>

        <form id="rejectForm" action="" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-[11px] font-semibold text-[#111827] mb-2">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea name="rejection_reason" rows="4" required
                    class="w-full px-3 py-2 border border-[#D1D5DB] rounded-lg text-[12px] focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Jelaskan alasan penolakan refund..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 px-4 py-2.5 border border-[#D1D5DB] text-[#111827] rounded-lg text-[13px] font-semibold hover:bg-[#F3F4F6] transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-[#DC2626] text-white rounded-lg text-[13px] font-semibold hover:bg-[#B91C1C] transition">
                    Ya, Tolak
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Process Modal
    function openProcessModal(refundId) {
        fetch(`/admin/refunds/${refundId}/details`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('refundDetails').innerHTML = `
                <div class="bg-[#F9FAFB] px-4 py-3 rounded-xl border border-[#E5E7EB]">
                    <p class="text-[10px] text-[#6B7280] mb-1">Nomor Refund</p>
                    <p class="font-mono font-semibold text-[#DC2626]">${data.refund_number}</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-[10px] text-[#6B7280] mb-1">Nomor Order</p>
                        <p class="font-mono font-semibold text-[#111827]">${data.order_number}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-[#6B7280] mb-1">Pembeli</p>
                        <p class="font-semibold text-[#111827]">${data.user_name}</p>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] text-[#6B7280] mb-1">Rekening Tujuan</p>
                    <p class="font-semibold text-[#111827]">${data.bank_name} - ${data.account_number}</p>
                    <p class="text-[11px] text-[#6B7280]">a.n. ${data.account_holder}</p>
                </div>
                <hr class="border-dashed border-[#E5E7EB]">
                <div class="bg-gradient-to-br from-[#15803D] to-[#166534] px-4 py-4 rounded-xl text-white shadow-sm">
                    <p class="text-[10px] text-white/80 mb-1">JUMLAH TRANSFER</p>
                    <p class="text-[22px] font-bold">Rp ${data.refund_amount}</p>
                    <p class="text-[10px] text-white/70 mt-1">Order: Rp ${data.order_amount} | Admin 5%: Rp ${data.admin_fee}</p>
                </div>
                ${data.cancellation_reason !== '-' ? `
                <div class="bg-[#FEF3C7] p-3 rounded-lg border border-[#FCD34D]">
                    <p class="text-[10px] font-semibold text-[#92400E] mb-1">Alasan Pembatalan:</p>
                    <p class="text-[11px] text-[#92400E]">${data.cancellation_reason}</p>
                </div>
                ` : ''}
            `;

                document.getElementById('processForm').action = `/admin/refunds/${refundId}/process`;
                document.getElementById('processModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
    }

    function closeProcessModal() {
        document.getElementById('processModal').classList.add('hidden');
        document.getElementById('processForm').reset();
        document.getElementById('file_name').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function showFileName(input) {
        const fileName = input.files[0]?.name;
        if (fileName) {
            const fileNameDisplay = document.getElementById('file_name');
            fileNameDisplay.querySelector('span').textContent = fileName;
            fileNameDisplay.classList.remove('hidden');
        }
    }

    // Reject Modal
    function openRejectModal(refundId, refundNumber) {
        document.getElementById('reject_refund_number').textContent = refundNumber;
        document.getElementById('rejectForm').action = `/admin/refunds/${refundId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectForm').reset();
        document.body.style.overflow = 'auto';
    }
</script>
@endpush