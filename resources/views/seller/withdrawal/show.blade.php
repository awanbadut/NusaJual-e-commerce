@extends('layouts.seller')

@section('title', 'Detail Pencairan Dana')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-4 sm:mb-6 flex items-start sm:items-center gap-3 sm:gap-4">
        <a href="{{ route('seller.withdrawals.index') }}"
            class="mt-1 sm:mt-0 p-2 -ml-2 text-gray-500 hover:text-gray-900 transition-colors rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <nav class="hidden sm:flex text-xs sm:text-sm text-gray-500 mb-1">
                <a href="{{ route('seller.withdrawals.index') }}"
                    class="hover:text-green-700 font-medium transition">Pencairan Dana</a>
                <span class="mx-2 text-gray-400">›</span>
                <span class="text-gray-900 font-semibold">Detail Pencairan</span>
            </nav>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-800 flex flex-wrap items-center gap-2">
                Detail Pencairan
                <span class="text-sm sm:text-lg text-gray-600 font-mono bg-gray-100 px-2.5 py-1 rounded-md">#WD-{{
                    str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}</span>
            </h1>
        </div>
    </div>

    @if($withdrawal->status == 'pending')
    <div class="bg-yellow-50 border border-yellow-200 p-3 sm:p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
        <x-heroicon-s-clock class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-500 shrink-0 mt-0.5" />
        <div>
            <p class="text-sm sm:text-base font-bold text-yellow-800">Pencairan Sedang Diproses</p>
            <p class="text-xs sm:text-sm text-yellow-700 mt-1 leading-relaxed">Admin sedang memverifikasi permintaan
                Anda. Harap tunggu konfirmasi lebih lanjut.</p>
        </div>
    </div>
    @elseif($withdrawal->status == 'approved')
    <div class="bg-blue-50 border border-blue-200 p-3 sm:p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
        <x-heroicon-s-check-circle class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500 shrink-0 mt-0.5" />
        <div>
            <p class="text-sm sm:text-base font-bold text-blue-800">Pencairan Disetujui</p>
            <p class="text-xs sm:text-sm text-blue-700 mt-1 leading-relaxed">Dana sedang diproses untuk transfer ke
                rekening Anda.</p>
            @if($withdrawal->approved_at)
            <p class="text-[11px] sm:text-xs text-blue-600/80 mt-2 font-medium">Disetujui pada: {{
                $withdrawal->approved_at->format('d M Y, H:i') }} WIB</p>
            @endif
        </div>
    </div>
    @elseif($withdrawal->status == 'completed')
    <div class="bg-green-50 border border-green-200 p-3 sm:p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
        <x-heroicon-s-check-badge class="w-5 h-5 sm:w-6 sm:h-6 text-green-500 shrink-0 mt-0.5" />
        <div>
            <p class="text-sm sm:text-base font-bold text-green-800">Pencairan Berhasil</p>
            <p class="text-xs sm:text-sm text-green-700 mt-1 leading-relaxed">Dana telah berhasil ditransfer ke rekening
                Anda.</p>
            @if($withdrawal->completed_at)
            <p class="text-[11px] sm:text-xs text-green-600/80 mt-2 font-medium">Selesai pada: {{
                $withdrawal->completed_at->format('d M Y, H:i') }} WIB</p>
            @endif
        </div>
    </div>
    @elseif($withdrawal->status == 'rejected')
    <div class="bg-red-50 border border-red-200 p-3 sm:p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
        <x-heroicon-s-x-circle class="w-5 h-5 sm:w-6 sm:h-6 text-red-500 shrink-0 mt-0.5" />
        <div>
            <p class="text-sm sm:text-base font-bold text-red-800">Pencairan Ditolak</p>
            <p class="text-xs sm:text-sm text-red-700 mt-1 leading-relaxed">{{ $withdrawal->admin_notes ?? 'Silakan
                hubungi admin untuk informasi lebih lanjut.' }}</p>
            @if($withdrawal->rejected_at)
            <p class="text-[11px] sm:text-xs text-red-600/80 mt-2 font-medium">Ditolak pada: {{
                $withdrawal->rejected_at->format('d M Y, H:i') }} WIB</p>
            @endif
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 items-start">
        <div class="lg:col-span-1 space-y-4 sm:space-y-6 lg:sticky lg:top-6 h-full">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 h-fit">
                <div class="flex items-center gap-3 mb-4 sm:mb-5 border-b border-gray-50 pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-600">
                        <x-heroicon-o-information-circle class="w-4 h-4 sm:w-5 sm:h-5" />
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900">Status Pencairan</h3>
                </div>

                <div class="space-y-4 sm:space-y-5">
                    <div>
                        <p class="text-[11px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                            Status Saat Ini</p>
                        <div>
                            @if($withdrawal->status == 'pending')
                            <span
                                class="w-full flex items-center justify-center px-3 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-yellow-50 text-yellow-700 border border-yellow-200/50">
                                <x-heroicon-s-clock class="w-4 h-4 mr-2" /> Menunggu Konfirmasi
                            </span>
                            @elseif($withdrawal->status == 'approved')
                            <span
                                class="w-full flex items-center justify-center px-3 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-blue-50 text-blue-700 border border-blue-200/50">
                                <x-heroicon-s-check-circle class="w-4 h-4 mr-2" /> Disetujui
                            </span>
                            @elseif($withdrawal->status == 'completed')
                            <span
                                class="w-full flex items-center justify-center px-3 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-green-50 text-green-700 border border-green-200/50">
                                <x-heroicon-s-check-badge class="w-4 h-4 mr-2" /> Selesai
                            </span>
                            @else
                            <span
                                class="w-full flex items-center justify-center px-3 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-red-50 text-red-700 border border-red-200/50">
                                <x-heroicon-s-x-circle class="w-4 h-4 mr-2" /> Ditolak
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-1 gap-3 sm:gap-4">
                        <div class="bg-gray-50/50 p-3 rounded-xl border border-gray-100">
                            <p class="text-[11px] sm:text-xs text-gray-500 mb-0.5">ID Pencairan</p>
                            <p class="text-xs sm:text-sm font-bold text-gray-900 font-mono tracking-wide truncate">
                                #WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="bg-gray-50/50 p-3 rounded-xl border border-gray-100">
                            <p class="text-[11px] sm:text-xs text-gray-500 mb-0.5">Tgl Pengajuan</p>
                            <p class="text-xs sm:text-sm font-bold text-gray-900">{{
                                $withdrawal->requested_at->format('d M Y') }}</p>
                            <p class="text-[10px] sm:text-xs text-gray-400 mt-0.5">{{
                                $withdrawal->requested_at->format('H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-4 sm:mb-5 border-b border-gray-50 pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-600">
                        <x-heroicon-o-building-library class="w-4 h-4 sm:w-5 sm:h-5" />
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900">Rekening Tujuan</h3>
                </div>

                <div
                    class="bg-gradient-to-br from-gray-50 to-gray-100/80 rounded-xl p-4 sm:p-5 border border-gray-200/60 relative overflow-hidden group hover:border-green-300 transition duration-300">
                    <div
                        class="absolute -top-6 -right-6 w-20 h-20 bg-green-100 rounded-full opacity-40 group-hover:scale-110 transition duration-500">
                    </div>

                    <div class="relative z-10">
                        <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">BANK
                            TRANSFER</p>

                        <div class="mb-4">
                            <p class="text-base sm:text-lg font-black text-gray-800 tracking-tight">{{
                                $withdrawal->bankAccount->bank_name }}</p>
                            <p class="text-sm sm:text-base font-mono font-medium text-gray-600 tracking-wider mt-1">{{
                                $withdrawal->bankAccount->account_number }}</p>
                        </div>

                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-200/50">
                            <div
                                class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[10px] sm:text-xs text-gray-600 font-bold shadow-sm">
                                {{ substr($withdrawal->bankAccount->account_name, 0, 1) }}
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700 truncate">{{
                                $withdrawal->bankAccount->account_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4 sm:space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-4 sm:mb-5">
                        <div class="p-2 bg-green-50 rounded-lg text-green-600">
                            <x-heroicon-o-banknotes class="w-4 h-4 sm:w-5 sm:h-5" />
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900">Rincian Dana</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1 sm:space-y-2">
                            <div
                                class="flex justify-between items-center p-2.5 sm:p-3 rounded-xl hover:bg-gray-50/80 transition">
                                <span class="text-xs sm:text-sm text-gray-600">Jumlah Diajukan</span>
                                <span class="text-sm sm:text-base font-bold text-gray-900">Rp {{
                                    number_format($withdrawal->amount, 0, ',', '.') }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center p-2.5 sm:p-3 rounded-xl hover:bg-gray-50/80 transition">
                                <span class="text-xs sm:text-sm text-gray-600 flex items-center gap-1.5">
                                    Biaya Admin
                                    <x-heroicon-o-question-mark-circle class="w-3.5 h-3.5 text-gray-400" />
                                </span>
                                <span class="text-sm sm:text-base font-bold text-red-500">- Rp {{
                                    number_format($withdrawal->admin_fee, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div
                            class="bg-green-600 rounded-2xl p-4 sm:p-5 text-white text-center md:text-right shadow-md shadow-green-100 mt-2">
                            <p class="text-green-100 text-xs sm:text-sm font-medium mb-1.5">Total Diterima Bersih</p>
                            <p class="text-2xl sm:text-3xl font-black tracking-tight">Rp {{
                                number_format($withdrawal->total_received, 0, ',', '.') }}</p>
                            <p
                                class="text-[11px] sm:text-xs text-green-100 mt-2 sm:mt-3 flex items-center justify-center md:justify-end gap-1.5 font-medium">
                                <x-heroicon-s-check-circle class="w-3.5 h-3.5" /> Siap ditransfer
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($withdrawal->notes || $withdrawal->admin_notes)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-sm sm:text-base font-bold text-gray-900 mb-4 sm:mb-5 flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" /> Catatan
                </h3>

                <div class="space-y-4">
                    @if($withdrawal->notes)
                    <div>
                        <p
                            class="text-[11px] sm:text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <x-heroicon-s-user class="w-3 h-3" /> Catatan Anda
                        </p>
                        <div
                            class="bg-gray-50 p-3.5 sm:p-4 rounded-xl border border-gray-100 text-xs sm:text-sm text-gray-700 italic leading-relaxed">
                            "{{ $withdrawal->notes }}"
                        </div>
                    </div>
                    @endif

                    @if($withdrawal->admin_notes)
                    <div>
                        <p
                            class="text-[11px] sm:text-xs font-bold text-blue-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <x-heroicon-s-shield-check class="w-3 h-3" /> Pesan Admin
                        </p>
                        <div
                            class="bg-blue-50/50 p-3.5 sm:p-4 rounded-xl border border-blue-100 text-xs sm:text-sm text-blue-800 leading-relaxed">
                            {{ $withdrawal->admin_notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-sm sm:text-base font-bold text-gray-900 mb-5 sm:mb-6 flex items-center gap-2">
                    <x-heroicon-o-clock class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" /> Riwayat Proses
                </h3>

                <div class="relative pl-1 sm:pl-2">
                    <div class="flex gap-3 sm:gap-4 pb-6 sm:pb-8 relative group">
                        <div class="flex flex-col items-center relative z-10">
                            <div
                                class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white border-2 border-green-500 flex items-center justify-center shadow-sm">
                                <x-heroicon-s-paper-airplane class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-green-600" />
                            </div>
                            @if($withdrawal->processed_at || $withdrawal->rejected_at)
                            <div class="w-[2px] h-full bg-green-200 absolute top-7 sm:top-8"></div>
                            @endif
                        </div>
                        <div class="pt-1">
                            <p class="font-bold text-gray-900 text-xs sm:text-sm">Pengajuan Dibuat</p>
                            <p class="text-[10px] sm:text-xs text-gray-500 mt-1">{{ $withdrawal->requested_at->format('d
                                M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    @if($withdrawal->processed_at)
                    <div class="flex gap-3 sm:gap-4 pb-6 sm:pb-8 relative group">
                        <div class="flex flex-col items-center relative z-10">
                            <div
                                class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white border-2 border-green-500 flex items-center justify-center shadow-sm">
                                <x-heroicon-s-check class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-600" />
                            </div>
                            @if($withdrawal->completed_at)
                            <div class="w-[2px] h-full bg-green-200 absolute top-7 sm:top-8"></div>
                            @endif
                        </div>
                        <div class="pt-1">
                            <p class="font-bold text-gray-900 text-xs sm:text-sm">Disetujui Admin</p>
                            <p class="text-[10px] sm:text-xs text-gray-500 mt-1">{{ $withdrawal->processed_at->format('d
                                M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    @endif

                    @if($withdrawal->updated_at && $withdrawal->status == 'completed')
                    <div class="flex gap-3 sm:gap-4 relative">
                        <div class="flex flex-col items-center relative z-10">
                            <div
                                class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-green-600 flex items-center justify-center shadow-sm ring-4 ring-green-50">
                                <x-heroicon-s-check-badge class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" />
                            </div>
                        </div>
                        <div class="pt-1">
                            <p class="font-bold text-gray-900 text-xs sm:text-sm">Transfer Berhasil</p>
                            <p class="text-[10px] sm:text-xs text-gray-500 mt-1">{{ $withdrawal->updated_at->format('d M
                                Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    @endif

                    @if($withdrawal->rejected_at)
                    <div class="flex gap-3 sm:gap-4 relative">
                        <div class="flex flex-col items-center relative z-10">
                            <div
                                class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-red-600 flex items-center justify-center shadow-sm ring-4 ring-red-50">
                                <x-heroicon-s-x-mark class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" />
                            </div>
                        </div>
                        <div class="pt-1">
                            <p class="font-bold text-red-700 text-xs sm:text-sm">Pengajuan Ditolak</p>
                            <p class="text-[10px] sm:text-xs text-gray-500 mt-1">{{ $withdrawal->rejected_at->format('d
                                M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-2">
                <a href="{{ route('seller.withdrawals.index') }}"
                    class="w-full sm:flex-1 bg-white border border-gray-200 text-gray-700 px-5 py-3 rounded-xl font-bold hover:bg-gray-50 hover:border-gray-300 transition-all text-center text-sm shadow-sm flex items-center justify-center gap-2">
                    &larr; Kembali
                </a>

                @if($withdrawal->status == 'completed')
                <button onclick="window.print()"
                    class="w-full sm:flex-1 bg-green-700 text-white px-5 py-3 rounded-xl font-bold hover:bg-green-800 transition-all shadow-sm flex items-center justify-center gap-2 text-sm">
                    <x-heroicon-o-printer class="w-4 h-4 sm:w-5 sm:h-5" />
                    Cetak Bukti
                </button>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .max-w-5xl,
        .max-w-5xl * {
            visibility: visible;
        }

        .max-w-5xl {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        button,
        a[href*="withdrawals.index"] {
            display: none !important;
        }
    }
</style>
@endpush