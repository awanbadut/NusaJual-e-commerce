@extends('layouts.seller')

@section('title', 'Detail Pencairan Dana')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="max-w-5xl">
    <!-- Breadcrumb & Back Button -->
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('seller.withdrawals.index') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <nav class="flex text-sm text-gray-600 mb-1">
                <a href="{{ route('seller.withdrawals.index') }}" class="hover:text-green-800">Pencairan Dana</a>
                <span class="mx-2">›</span>
                <span class="text-gray-900 font-medium">Detail Pencairan</span>
            </nav>
            <h1 class="text-3xl font-bold text-green-800">
                Detail Pencairan
                <span class="text-gray-900 font-mono">#WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}</span>
            </h1>
        </div>
    </div>

    <!-- Status Alert -->
    @if($withdrawal->status == 'pending')
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg mb-6 flex items-start gap-3">
        <x-heroicon-s-clock class="w-6 h-6 text-yellow-600 shrink-0 mt-0.5" />
        <div>
            <p class="font-semibold text-yellow-800">Pencairan Sedang Diproses</p>
            <p class="text-sm text-yellow-700 mt-1">Admin sedang memverifikasi permintaan Anda. Harap tunggu konfirmasi
                lebih lanjut.</p>
        </div>
    </div>
    @elseif($withdrawal->status == 'approved')
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg mb-6 flex items-start gap-3">
        <x-heroicon-s-check-circle class="w-6 h-6 text-blue-600 shrink-0 mt-0.5" />
        <div>
            <p class="font-semibold text-blue-800">Pencairan Disetujui</p>
            <p class="text-sm text-blue-700 mt-1">Dana sedang diproses untuk transfer ke rekening Anda.</p>
            @if($withdrawal->approved_at)
            <p class="text-xs text-blue-600 mt-1">Disetujui pada: {{ $withdrawal->approved_at->format('d M Y, H:i') }}
                WIB</p>
            @endif
        </div>
    </div>
    @elseif($withdrawal->status == 'completed')
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6 flex items-start gap-3">
        <x-heroicon-s-check-badge class="w-6 h-6 text-green-600 shrink-0 mt-0.5" />
        <div>
            <p class="font-semibold text-green-800">Pencairan Berhasil</p>
            <p class="text-sm text-green-700 mt-1">Dana telah berhasil ditransfer ke rekening Anda.</p>
            @if($withdrawal->completed_at)
            <p class="text-xs text-green-600 mt-1">Selesai pada: {{ $withdrawal->completed_at->format('d M Y, H:i') }}
                WIB</p>
            @endif
        </div>
    </div>
    @elseif($withdrawal->status == 'rejected')
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 flex items-start gap-3">
        <x-heroicon-s-x-circle class="w-6 h-6 text-red-600 shrink-0 mt-0.5" />
        <div>
            <p class="font-semibold text-red-800">Pencairan Ditolak</p>
            <p class="text-sm text-red-700 mt-1">{{ $withdrawal->admin_notes ?? 'Silakan hubungi admin untuk informasi
                lebih lanjut.' }}</p>
            @if($withdrawal->rejected_at)
            <p class="text-xs text-red-600 mt-1">Ditolak pada: {{ $withdrawal->rejected_at->format('d M Y, H:i') }} WIB
            </p>
            @endif
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-6 h-full">

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 h-fit">
                <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-information-circle class="w-5 h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Status Pencairan</h3>
                </div>

                <div class="space-y-5">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Status Saat Ini
                        </p>
                        <div>
                            @if($withdrawal->status == 'pending')
                            <span
                                class="w-full flex items-center justify-center px-3 py-2 rounded-lg text-sm font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                <x-heroicon-s-clock class="w-4 h-4 mr-2" /> Menunggu Konfirmasi
                            </span>
                            @elseif($withdrawal->status == 'approved')
                            <span
                                class="w-full flex items-center justify-center px-3 py-2 rounded-lg text-sm font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                <x-heroicon-s-check-circle class="w-4 h-4 mr-2" /> Disetujui
                            </span>
                            @elseif($withdrawal->status == 'completed')
                            <span
                                class="w-full flex items-center justify-center px-3 py-2 rounded-lg text-sm font-bold bg-green-50 text-green-700 border border-green-200">
                                <x-heroicon-s-check-badge class="w-4 h-4 mr-2" /> Selesai
                            </span>
                            @else
                            <span
                                class="w-full flex items-center justify-center px-3 py-2 rounded-lg text-sm font-bold bg-red-50 text-red-700 border border-red-200">
                                <x-heroicon-s-x-circle class="w-4 h-4 mr-2" /> Ditolak
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <p class="text-xs text-gray-500">ID Pencairan</p>
                            <p class="text-sm font-bold text-gray-900 font-mono tracking-wide">#WD-{{
                                str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <p class="text-xs text-gray-500">Tanggal Pengajuan</p>
                            <p class="text-sm font-bold text-gray-900">{{ $withdrawal->requested_at->format('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $withdrawal->requested_at->format('H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-700">
                        <x-heroicon-o-building-library class="w-5 h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Rekening Tujuan</h3>
                </div>

                <div
                    class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-5 border border-gray-200 relative overflow-hidden group hover:border-green-300 transition duration-300">
                    <div
                        class="absolute -top-6 -right-6 w-20 h-20 bg-green-100 rounded-full opacity-50 group-hover:scale-110 transition">
                    </div>

                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">BANK TRANSFER</p>

                        <div class="mb-4">
                            <p class="text-lg font-black text-gray-800 tracking-tight">{{
                                $withdrawal->bankAccount->bank_name }}</p>
                            <p class="text-base font-mono font-medium text-gray-600 tracking-wider mt-1">{{
                                $withdrawal->bankAccount->account_number }}</p>
                        </div>

                        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-200/60">
                            <div
                                class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-600 font-bold">
                                {{ substr($withdrawal->bankAccount->account_name, 0, 1) }}
                            </div>
                            <p class="text-sm font-semibold text-gray-700 truncate">{{
                                $withdrawal->bankAccount->account_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-green-50 rounded-lg text-green-700">
                            <x-heroicon-o-banknotes class="w-5 h-5" />
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Rincian Dana</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <div class="flex justify-between items-center p-3 rounded-lg hover:bg-gray-50 transition">
                                <span class="text-sm text-gray-600">Jumlah Diajukan</span>
                                <span class="font-bold text-gray-900">Rp {{ number_format($withdrawal->amount, 0, ',',
                                    '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-lg hover:bg-gray-50 transition">
                                <span class="text-sm text-gray-600 flex items-center gap-1">Biaya Admin
                                    <x-heroicon-o-question-mark-circle class="w-3 h-3 text-gray-400" />
                                </span>
                                <span class="font-bold text-red-500">- Rp {{ number_format($withdrawal->admin_fee, 0,
                                    ',', '.') }}</span>
                            </div>
                        </div>

                        <div
                            class="bg-green-600 rounded-2xl p-4 text-white text-center md:text-right shadow-lg shadow-green-200">
                            <p class="text-green-100 text-sm font-medium mb-1">Total Diterima Bersih</p>
                            <p class="text-3xl font-black tracking-tight">Rp {{
                                number_format($withdrawal->total_received, 0, ',', '.') }}</p>
                            <p
                                class="text-xs text-green-200 mt-2 flex items-center justify-center md:justify-end gap-1">
                                <x-heroicon-s-check-circle class="w-3 h-3" /> Siap ditransfer
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($withdrawal->notes || $withdrawal->admin_notes)
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-5 h-5 text-gray-400" /> Catatan
                </h3>

                <div class="space-y-4">
                    @if($withdrawal->notes)
                    <div>
                        <p
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <x-heroicon-s-user class="w-3 h-3" /> Catatan Anda
                        </p>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-sm text-gray-700 italic">
                            "{{ $withdrawal->notes }}"
                        </div>
                    </div>
                    @endif

                    @if($withdrawal->admin_notes)
                    <div>
                        <p
                            class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <x-heroicon-s-shield-check class="w-3 h-3" /> Pesan Admin
                        </p>
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-sm text-blue-800">
                            {{ $withdrawal->admin_notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-base font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <x-heroicon-o-clock class="w-5 h-5 text-gray-400" /> Riwayat Proses
                </h3>

                <div class="relative pl-2">
                    <div class="flex gap-4 pb-8 relative group">
                        <div class="flex flex-col items-center relative z-10">
                            <div
                                class="w-8 h-8 rounded-full bg-white border-2 border-green-500 flex items-center justify-center shadow-sm">
                                <x-heroicon-s-paper-airplane class="w-3.5 h-3.5 text-green-600" />
                            </div>
                            @if($withdrawal->processed_at || $withdrawal->rejected_at)
                            <div class="w-0.5 h-full bg-green-200 absolute top-8"></div>
                            @endif
                        </div>
                        <div class="pt-1">
                            <p class="font-bold text-gray-900 text-sm">Pengajuan Dibuat</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $withdrawal->requested_at->format('d M Y, H:i')
                                }} WIB</p>
                        </div>
                    </div>

                    @if($withdrawal->processed_at)
                    <div class="flex gap-4 pb-8 relative group">
                        <div class="flex flex-col items-center relative z-10">
                            <div
                                class="w-8 h-8 rounded-full bg-white border-2 border-green-500 flex items-center justify-center shadow-sm">
                                <x-heroicon-s-check class="w-4 h-4 text-green-600" />
                            </div>
                            @if($withdrawal->completed_at)
                            <div class="w-0.5 h-full bg-green-200 absolute top-8"></div>
                            @endif
                        </div>
                        <div class="pt-1">
                            <p class="font-bold text-gray-900 text-sm">Disetujui Admin</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $withdrawal->processed_at->format('d M Y, H:i')
                                }}
                                WIB</p>
                        </div>
                    </div>
                    @endif

                    @if($withdrawal->updated_at)
                    <div class="flex gap-4 relative">
                        <div class="flex flex-col items-center relative z-10">
                            <div
                                class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center shadow-md ring-4 ring-green-50">
                                <x-heroicon-s-check-badge class="w-4 h-4 text-white" />
                            </div>
                        </div>
                        <div class="pt-1">
                            <p class="font-bold text-gray-900 text-sm">Transfer Berhasil</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $withdrawal->updated_at->format('d M Y, H:i')
                                }} WIB</p>
                        </div>
                    </div>
                    @endif

                    @if($withdrawal->rejected_at)
                    <div class="flex gap-4 relative">
                        <div class="flex flex-col items-center relative z-10">
                            <div
                                class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center shadow-md ring-4 ring-red-50">
                                <x-heroicon-s-x-mark class="w-4 h-4 text-white" />
                            </div>
                        </div>
                        <div class="pt-1">
                            <p class="font-bold text-red-700 text-sm">Pengajuan Ditolak</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $withdrawal->rejected_at->format('d M Y, H:i') }}
                                WIB</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('seller.withdrawals.index') }}"
                    class="flex-1 bg-white border border-gray-300 text-gray-700 px-6 py-3.5 rounded-xl font-bold hover:bg-gray-50 hover:border-gray-400 transition text-center text-sm shadow-sm">
                    &larr; Kembali
                </a>

                @if($withdrawal->status == 'completed')
                <button onclick="window.print()"
                    class="flex-1 bg-green-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-800 transition flex items-center justify-center gap-2">
                    <x-heroicon-o-printer class="w-5 h-5" />
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