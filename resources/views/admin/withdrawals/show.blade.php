@extends('layouts.admin')

@section('title', 'Detail Pencairan - ' . $withdrawal->store->store_name)

@section('content')
<div>

    {{-- BREADCRUMB & HEADER --}}
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.withdrawals.index') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <nav class="flex text-sm text-gray-600 mb-1">
                <a href="{{ route('admin.mitra.index') }}" class="hover:text-green-800">Mitra</a>
                <span class="mx-2">›</span>
                <a href="{{ route('admin.mitra.show', $withdrawal->store->id) }}" class="hover:text-green-800">{{
                    $withdrawal->store->store_name }}</a>
                <span class="mx-2">›</span>
                <span class="text-gray-900 font-medium">Detail Pencairan</span>
            </nav>
            <h1 class="text-3xl font-bold text-green-800">
                Verifikasi Pencairan
                <span class="text-gray-900 font-mono text-2xl">#WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT)
                    }}</span>
            </h1>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start mb-24">

        {{-- ROW 1: ENTITY INFORMATION (3 Cards Sejajar) --}}

        {{-- CARD 1: INFORMASI TOKO --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex flex-col h-full">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                <div class="p-1.5 bg-gray-100 rounded-lg">
                    <x-heroicon-s-building-storefront class="w-5 h-5 text-gray-600" />
                </div>
                <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wide">Toko</h3>
            </div>
            <div class="space-y-3 flex-1">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 font-bold text-sm">
                        {{ strtoupper(substr($withdrawal->store->store_name, 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="font-bold text-gray-900 truncate">{{ $withdrawal->store->store_name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $withdrawal->store->user->name }}</p>
                    </div>
                </div>
                <div class="pt-2">
                    <p class="text-xs text-gray-500">Kontak</p>
                    <p class="text-sm font-medium text-gray-900">{{ $withdrawal->store->user->email }}</p>
                    <p class="text-sm font-medium text-gray-900">{{ $withdrawal->store->user->phone ?? '-' }}</p>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.mitra.show', $withdrawal->store->id) }}"
                    class="text-xs font-bold text-green-700 hover:text-green-800 flex items-center gap-1">
                    Lihat Profil Toko
                    <x-heroicon-s-arrow-right class="w-3 h-3" />
                </a>
            </div>
        </div>

        {{-- CARD 2: REKENING TUJUAN (Gradient Style) --}}
        <div
            class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl shadow-sm border border-gray-200 flex flex-col h-full relative overflow-hidden group hover:border-green-300 transition duration-300">
            <div
                class="absolute -top-6 -right-6 w-24 h-24 bg-green-100 rounded-full opacity-50 group-hover:scale-110 transition">
            </div>

            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">BANK TRANSFER</p>
                    <div class="mb-4">
                        <p class="text-lg font-black text-gray-800 tracking-tight">{{
                            $withdrawal->bankAccount->bank_name }}</p>
                        <p class="text-base font-mono font-medium text-gray-600 tracking-wider mt-1">{{
                            $withdrawal->bankAccount->account_number }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-4 border-t border-gray-200/60">
                    <div
                        class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-xs text-gray-600 font-bold shadow-sm">
                        {{ substr($withdrawal->bankAccount->account_name, 0, 1) }}
                    </div>
                    <p class="text-sm font-semibold text-gray-700 truncate">{{ $withdrawal->bankAccount->account_name }}
                    </p>
                </div>
            </div>
        </div>

        {{-- CARD 3: TANGGAL PENTING --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex flex-col h-full">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                <div class="p-1.5 bg-blue-100 rounded-lg">
                    <x-heroicon-s-calendar class="w-5 h-5 text-blue-700" />
                </div>
                <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wide">Tanggal</h3>
            </div>
            <div class="space-y-4 flex-1">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase mb-1">Diajukan</p>
                    <p class="text-sm font-bold text-gray-900">{{ $withdrawal->requested_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $withdrawal->requested_at->format('H:i') }} WIB</p>
                </div>

                @if($withdrawal->completed_at)
                <div>
                    <p class="text-xs text-green-600 font-medium uppercase mb-1">Selesai</p>
                    <p class="text-sm font-bold text-green-900">{{ $withdrawal->completed_at->format('d M Y') }}</p>
                    <p class="text-xs text-green-600">{{ $withdrawal->completed_at->format('H:i') }} WIB</p>
                </div>
                @elseif($withdrawal->rejected_at)
                <div>
                    <p class="text-xs text-red-600 font-medium uppercase mb-1">Ditolak</p>
                    <p class="text-sm font-bold text-red-900">{{ $withdrawal->rejected_at->format('d M Y') }}</p>
                    <p class="text-xs text-red-600">{{ $withdrawal->rejected_at->format('H:i') }} WIB</p>
                </div>
                @endif
            </div>
        </div>

        {{-- ROW 2: FINANCIALS (WIDE) & TIMELINE (TALL) --}}

        {{-- CARD 4: RINCIAN DANA & BUKTI (2/3 Width) --}}
        <div class="md:col-span-2 space-y-6">

            {{-- Financial Box --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <div class="p-1.5 bg-green-100 rounded-lg">
                            <x-heroicon-s-banknotes class="w-5 h-5 text-green-700" />
                        </div>
                        Rincian Dana
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 rounded-lg hover:bg-gray-50 transition">
                                <span class="text-sm text-gray-600">Jumlah Diajukan</span>
                                <span class="text-base font-bold text-gray-900">Rp {{ number_format($withdrawal->amount,
                                    0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-lg hover:bg-gray-50 transition">
                                <span class="text-sm text-gray-600">Biaya Admin</span>
                                <span class="text-base font-bold text-red-600">- Rp {{
                                    number_format($withdrawal->admin_fee, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Total Highlight --}}
                        <div
                            class="p-5 bg-[#F0FDF4] rounded-xl border border-[#BBF7D0] flex flex-col justify-center items-center md:items-end text-center md:text-right">
                            <p class="text-green-800 text-sm font-bold mb-1">Total Ditransfer</p>
                            <span class="text-3xl font-extrabold text-[#15803D]">Rp {{
                                number_format($withdrawal->total_received, 0, ',', '.') }}</span>
                            <div
                                class="mt-2 inline-flex items-center gap-1 px-2 py-1 bg-white rounded-md text-xs font-bold text-green-700 shadow-sm">
                                <x-heroicon-s-check-circle class="w-3 h-3" /> Siap Dikirim
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes & Proof Section --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Notes --}}
                @if($withdrawal->notes || $withdrawal->admin_notes)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-s-document-text class="w-5 h-5 text-gray-400" /> Catatan
                    </h3>
                    <div class="space-y-4">
                        @if($withdrawal->notes)
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Mitra</p>
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-sm text-gray-700 italic">
                                "{{ $withdrawal->notes }}"
                            </div>
                        </div>
                        @endif
                        @if($withdrawal->admin_notes)
                        <div>
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-1">Admin</p>
                            <div class="bg-blue-50 p-3 rounded-xl border border-blue-100 text-sm text-blue-800">
                                {{ $withdrawal->admin_notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Proof --}}
                @if($withdrawal->withdrawal_proof)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-s-paper-clip class="w-5 h-5 text-gray-400" /> Bukti Transfer
                    </h3>
                    <div class="relative group mb-3">
                        <img src="{{ asset('storage/' . $withdrawal->withdrawal_proof) }}"
                            class="w-full h-32 object-cover rounded-xl border border-gray-200 cursor-pointer hover:opacity-90 transition"
                            onclick="viewProofModal('{{ asset('storage/' . $withdrawal->withdrawal_proof) }}')">
                        <div
                            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition pointer-events-none">
                            <span class="bg-black/70 text-white px-2 py-1 rounded text-xs font-bold">Zoom</span>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $withdrawal->withdrawal_proof) }}" download
                        class="block w-full text-center bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 px-4 py-2 rounded-lg text-sm font-bold transition">
                        Download
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- CARD 5: TIMELINE (1/3 Width - Tall) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:row-span-2 h-full">
            <div class="flex items-center gap-3 mb-6 pb-3 border-b border-gray-100">
                <div class="p-1.5 bg-orange-100 rounded-lg">
                    <x-heroicon-s-clock class="w-5 h-5 text-orange-700" />
                </div>
                <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wide">Timeline</h3>
            </div>

            <div class="space-y-0">
                {{-- 1. Requested --}}
                <div class="flex gap-4 relative">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-8 h-8 bg-green-50 border-2 border-green-500 rounded-full flex items-center justify-center text-green-600 z-10">
                            <x-heroicon-s-paper-airplane class="w-3.5 h-3.5" />
                        </div>
                        @if($withdrawal->processed_at || $withdrawal->rejected_at)
                        <div class="w-0.5 h-full bg-green-200 absolute top-8"></div>
                        @endif
                    </div>
                    <div class="pb-8">
                        <p class="text-sm font-bold text-gray-900">Pengajuan Dibuat</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $withdrawal->requested_at->format('d F Y, H:i') }}
                            WIB</p>
                    </div>
                </div>

                {{-- 2. Approved --}}
                @if($withdrawal->processed_at)
                <div class="flex gap-4 relative">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-8 h-8 bg-green-50 border-2 border-green-500 rounded-full flex items-center justify-center text-green-600 z-10">
                            <x-heroicon-s-check class="w-3.5 h-3.5" />
                        </div>
                        @if($withdrawal->completed_at)
                        <div class="w-0.5 h-full bg-green-200 absolute top-8"></div>
                        @endif
                    </div>
                    <div class="pb-8">
                        <p class="text-sm font-bold text-gray-900">Disetujui Admin</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $withdrawal->processed_at->format('d F Y, H:i') }}
                            WIB</p>
                    </div>
                </div>
                @endif

                {{-- 3. Completed --}}
                @if($withdrawal->updated_at)
                <div class="flex gap-4 relative">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 bg-[#15803D] rounded-full flex items-center justify-center z-10">
                            <x-heroicon-s-check-badge class="w-4 h-4 text-white" />
                        </div>
                    </div>
                    <div class="pb-8">
                        <p class="text-sm font-bold text-gray-900">Transfer Berhasil</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $withdrawal->updated_at->format('d F Y, H:i') }} WIB
                        </p>
                    </div>
                </div>
                @endif

                {{-- 4. Rejected --}}
                @if($withdrawal->rejected_at)
                <div class="flex gap-4 relative">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-8 h-8 bg-red-100 border-2 border-red-500 rounded-full flex items-center justify-center text-red-600 z-10">
                            <x-heroicon-s-x-mark class="w-4 h-4" />
                        </div>
                    </div>
                    <div class="pb-8">
                        <p class="text-sm font-bold text-red-700">Pengajuan Ditolak</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $withdrawal->rejected_at->format('d F Y, H:i') }} WIB
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ACTION BUTTONS (STICKY BOTTOM) --}}
    @if($withdrawal->status == 'pending')
    <div class="flex gap-4 sticky bottom-6 z-40 mt-6">
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

{{-- MODALS --}}
{{-- 1. Modal Proses --}}
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
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition text-sm">Batal</button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-[#15803D] text-white rounded-xl font-semibold hover:bg-[#166534] transition text-sm shadow-md">Konfirmasi
                    Transfer</button>
            </div>
        </form>
    </div>
</div>

{{-- 2. Modal Tolak --}}
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
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition text-sm">Batal</button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition text-sm shadow-md">Tolak
                    Pengajuan</button>
            </div>
        </form>
    </div>
</div>

{{-- 3. Modal Preview Bukti --}}
<div id="proofModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4 backdrop-blur-sm"
    onclick="closeProofModal()">
    <div class="relative max-w-3xl w-full">
        <button onclick="closeProofModal()"
            class="absolute -top-12 right-0 text-white hover:text-gray-300 flex items-center gap-2 transition">
            <span class="text-sm font-bold">Tutup</span>
            <div class="bg-white/10 rounded-full p-1">
                <x-heroicon-s-x-mark class="w-6 h-6" />
            </div>
        </button>
        <img id="proofImage" src="" alt="Bukti Transfer"
            class="w-full h-auto rounded-2xl shadow-2xl ring-1 ring-white/10">
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openProcessModal() {
        document.getElementById('processModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeProcessModal() {
        document.getElementById('processModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    function viewProofModal(url) {
        document.getElementById('proofImage').src = url;
        document.getElementById('proofModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeProofModal() {
        document.getElementById('proofModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
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
    document.querySelectorAll('[id$="Modal"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    });
</script>
@endpush