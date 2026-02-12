@extends('layouts.admin')

@section('title', 'Detail Pencairan Dana')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-5xl">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.withdrawals.index') }}"
            class="p-2 rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5" />
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Pencairan Dana</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="font-mono text-sm text-gray-500">#WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT)
                    }}</span>
                <span class="text-gray-300">•</span>
                <span class="text-sm text-gray-500">{{ $withdrawal->requested_at->format('d F Y, H:i') }} WIB</span>
            </div>
        </div>
        <div class="ml-auto">
            @if($withdrawal->status == 'pending')
            <span
                class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 text-sm font-bold border border-yellow-200 shadow-sm">
                <x-heroicon-s-clock class="w-4 h-4 mr-2" /> Menunggu Proses
            </span>
            @elseif($withdrawal->status == 'approved')
            <span
                class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-800 text-sm font-bold border border-blue-200 shadow-sm">
                <x-heroicon-s-check-circle class="w-4 h-4 mr-2" /> Disetujui
            </span>
            @elseif($withdrawal->status == 'completed')
            <span
                class="inline-flex items-center px-4 py-2 rounded-full bg-[#DCFCE7] text-[#15803D] text-sm font-bold border border-[#BBF7D0] shadow-sm">
                <x-heroicon-s-check-badge class="w-4 h-4 mr-2" /> Selesai
            </span>
            @else
            <span
                class="inline-flex items-center px-4 py-2 rounded-full bg-red-100 text-red-800 text-sm font-bold border border-red-200 shadow-sm">
                <x-heroicon-s-x-circle class="w-4 h-4 mr-2" /> Ditolak
            </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2 pb-3 border-b border-gray-100">
                <div class="p-1.5 bg-green-100 rounded-lg">
                    <x-heroicon-s-building-storefront class="w-5 h-5 text-green-700" />
                </div>
                Informasi Toko
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-medium uppercase">Nama Toko</span>
                    <span class="text-sm font-bold text-gray-900">{{ $withdrawal->store->store_name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-medium uppercase">Pemilik</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $withdrawal->store->user->name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-medium uppercase">Email</span>
                    <span class="text-sm text-gray-900">{{ $withdrawal->store->user->email }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-medium uppercase">No. HP</span>
                    <span class="text-sm text-gray-900">{{ $withdrawal->store->user->phone ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2 pb-3 border-b border-gray-100">
                <div class="p-1.5 bg-blue-100 rounded-lg">
                    <x-heroicon-s-credit-card class="w-5 h-5 text-blue-700" />
                </div>
                Rekening Tujuan
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-medium uppercase">Nama Bank</span>
                    <span class="text-sm font-bold text-gray-900">{{ $withdrawal->bankAccount->bank_name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-medium uppercase">Nomor Rekening</span>
                    <span class="text-sm font-bold font-mono text-gray-900 bg-gray-50 px-2 py-1 rounded">{{
                        $withdrawal->bankAccount->account_number }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-medium uppercase">Atas Nama</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $withdrawal->bankAccount->account_name
                        }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <x-heroicon-s-document-text class="w-5 h-5 text-gray-600" />
                Rincian Pencairan
            </h3>
        </div>

        <div class="p-6">
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Jumlah Pencairan</span>
                    <span class="text-base font-bold text-gray-900">Rp {{ number_format($withdrawal->amount, 0, ',',
                        '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Biaya Admin (Flat)</span>
                    <span class="text-base font-bold text-red-600">- Rp {{ number_format($withdrawal->admin_fee, 0, ',',
                        '.') }}</span>
                </div>

                <div class="border-t border-dashed border-gray-300 my-4"></div>

                <div class="flex justify-between items-center p-4 bg-[#F0FDF4] rounded-xl border border-[#BBF7D0]">
                    <span class="text-sm font-bold text-[#166534]">Total Dana Ditransfer</span>
                    <span class="text-xl font-extrabold text-[#15803D]">Rp {{ number_format($withdrawal->total_received,
                        0, ',', '.') }}</span>
                </div>
            </div>

            @if($withdrawal->notes)
            <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <p class="text-xs font-bold text-blue-800 uppercase tracking-wide mb-1">Catatan Mitra</p>
                <p class="text-sm text-blue-700 italic">"{{ $withdrawal->notes }}"</p>
            </div>
            @endif

            @if($withdrawal->admin_notes)
            <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                <p class="text-xs font-bold text-yellow-800 uppercase tracking-wide mb-1">Catatan Admin</p>
                <p class="text-sm text-yellow-700 italic">"{{ $withdrawal->admin_notes }}"</p>
            </div>
            @endif

            @if($withdrawal->withdrawal_proof)
            <div class="mt-6">
                <p class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <x-heroicon-s-paper-clip class="w-4 h-4 text-gray-500" /> Bukti Transfer
                </p>
                <a href="{{ asset('storage/' . $withdrawal->withdrawal_proof) }}" target="_blank"
                    class="block w-fit group relative">
                    <img src="{{ asset('storage/' . $withdrawal->withdrawal_proof) }}" alt="Bukti Transfer"
                        class="w-full max-w-sm rounded-xl border border-gray-200 shadow-sm group-hover:opacity-90 transition">
                    <div
                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <span
                            class="bg-black/70 text-white px-3 py-1.5 rounded-lg text-xs font-bold backdrop-blur-sm">Klik
                            untuk memperbesar</span>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>

    @if($withdrawal->status == 'pending')
    <div class="flex gap-4 sticky bottom-6 z-40">
        <button onclick="openProcessModal({{ $withdrawal->id }})"
            class="flex-1 bg-[#15803D] text-white px-6 py-3.5 rounded-xl font-bold hover:bg-[#166534] transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
            <x-heroicon-s-check-circle class="w-5 h-5" />
            Proses & Transfer
        </button>
        <button onclick="openRejectModal({{ $withdrawal->id }})"
            class="flex-1 bg-white text-red-600 border border-red-200 px-6 py-3.5 rounded-xl font-bold hover:bg-red-50 hover:border-red-300 transition shadow-sm flex items-center justify-center gap-2">
            <x-heroicon-s-x-circle class="w-5 h-5" />
            Tolak Pengajuan
        </button>
    </div>
    @endif
</div>

<div id="processModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform scale-100 transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900">Proses Pencairan Dana</h3>
            <button onclick="closeProcessModal()" class="text-gray-400 hover:text-gray-600">
                <x-heroicon-m-x-mark class="w-6 h-6" />
            </button>
        </div>

        <form action="{{ route('admin.withdrawals.process', $withdrawal->id) }}" method="POST"
            enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer <span
                        class="text-red-500">*</span></label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-green-500 transition cursor-pointer bg-gray-50 hover:bg-green-50"
                    onclick="document.getElementById('file-upload').click()">
                    <div class="space-y-1 text-center">
                        <x-heroicon-o-arrow-up-tray class="mx-auto h-12 w-12 text-gray-400" />
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="file-upload"
                                class="relative cursor-pointer rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                                <span>Upload file</span>
                                <input id="file-upload" name="withdrawal_proof" type="file" class="sr-only"
                                    accept="image/*" required onchange="previewImage(this)">
                            </label>
                            <p class="pl-1">atau drag & drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                <div id="image-preview" class="hidden mt-4 text-center">
                    <img id="preview-img" src="#" alt="Preview" class="max-h-40 mx-auto rounded-lg border shadow-sm">
                    <p id="file-name" class="text-xs text-gray-500 mt-2"></p>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Admin (Opsional)</label>
                <textarea name="admin_notes" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                    placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeProcessModal()"
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-[#15803D] text-white rounded-xl font-semibold hover:bg-[#166534] transition text-sm shadow-md">
                    Konfirmasi Transfer
                </button>
            </div>
        </form>
    </div>
</div>

<div id="rejectModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform scale-100 transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-red-50">
            <h3 class="text-lg font-bold text-red-900">Tolak Pencairan Dana</h3>
            <button onclick="closeRejectModal()" class="text-red-400 hover:text-red-600">
                <x-heroicon-m-x-mark class="w-6 h-6" />
            </button>
        </div>

        <form action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" method="POST" class="p-6">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan <span
                        class="text-red-500">*</span></label>
                <textarea name="admin_notes" rows="4" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                    placeholder="Jelaskan alasan penolakan secara detail..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition text-sm shadow-md">
                    Tolak Pengajuan
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
    document.body.style.overflow = 'hidden';
}

function closeProcessModal() {
    document.getElementById('processModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openRejectModal(withdrawalId) {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Image Preview Function
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('file-name').textContent = input.files[0].name;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
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