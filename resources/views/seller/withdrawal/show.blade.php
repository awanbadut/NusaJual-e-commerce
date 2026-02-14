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
            <p class="text-sm text-yellow-700 mt-1">Admin sedang memverifikasi permintaan Anda. Harap tunggu konfirmasi lebih lanjut.</p>
        </div>
    </div>
    @elseif($withdrawal->status == 'approved')
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg mb-6 flex items-start gap-3">
        <x-heroicon-s-check-circle class="w-6 h-6 text-blue-600 shrink-0 mt-0.5" />
        <div>
            <p class="font-semibold text-blue-800">Pencairan Disetujui</p>
            <p class="text-sm text-blue-700 mt-1">Dana sedang diproses untuk transfer ke rekening Anda.</p>
            @if($withdrawal->approved_at)
            <p class="text-xs text-blue-600 mt-1">Disetujui pada: {{ $withdrawal->approved_at->format('d M Y, H:i') }} WIB</p>
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
            <p class="text-xs text-green-600 mt-1">Selesai pada: {{ $withdrawal->completed_at->format('d M Y, H:i') }} WIB</p>
            @endif
        </div>
    </div>
    @elseif($withdrawal->status == 'rejected')
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 flex items-start gap-3">
        <x-heroicon-s-x-circle class="w-6 h-6 text-red-600 shrink-0 mt-0.5" />
        <div>
            <p class="font-semibold text-red-800">Pencairan Ditolak</p>
            <p class="text-sm text-red-700 mt-1">{{ $withdrawal->admin_notes ?? 'Silakan hubungi admin untuk informasi lebih lanjut.' }}</p>
            @if($withdrawal->rejected_at)
            <p class="text-xs text-red-600 mt-1">Ditolak pada: {{ $withdrawal->rejected_at->format('d M Y, H:i') }} WIB</p>
            @endif
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Withdrawal Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-green-700" />
                    Status Pencairan
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</p>
                        <div class="mt-1">
                            @if($withdrawal->status == 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <x-heroicon-s-clock class="w-3 h-3 mr-1" />
                                Pending
                            </span>
                            @elseif($withdrawal->status == 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                <x-heroicon-s-check-circle class="w-3 h-3 mr-1" />
                                Disetujui
                            </span>
                            @elseif($withdrawal->status == 'completed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                <x-heroicon-s-check-badge class="w-3 h-3 mr-1" />
                                Selesai
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                <x-heroicon-s-x-circle class="w-3 h-3 mr-1" />
                                Ditolak
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">ID Pencairan</p>
                        <p class="text-sm font-bold text-gray-900 font-mono mt-1">#WD-{{ str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $withdrawal->requested_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    
                    @if($withdrawal->approved_at)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Disetujui</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $withdrawal->approved_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    @endif
                    
                    @if($withdrawal->completed_at)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Selesai</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $withdrawal->completed_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Bank Account Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-building-library class="w-5 h-5 text-green-700" />
                    Rekening Tujuan
                </h3>
                
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-white rounded-lg shadow-sm">
                            <x-heroicon-s-building-library class="w-5 h-5 text-green-700" />
                        </div>
                        <div>
                            <p class="text-xs text-green-700 font-semibold">Bank</p>
                            <p class="text-sm font-bold text-green-900">{{ $withdrawal->bankAccount->bank_name }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div>
                            <p class="text-xs text-green-700 font-semibold">Nomor Rekening</p>
                            <p class="font-mono font-bold text-green-900">{{ $withdrawal->bankAccount->account_number }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-green-700 font-semibold">Nama Pemilik</p>
                            <p class="font-medium text-green-900">{{ $withdrawal->bankAccount->account_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Amount Details & Notes -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Amount Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-banknotes class="w-5 h-5 text-green-700" />
                    Rincian Pencairan
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Pencairan</p>
                            <p class="text-xs text-gray-500 mt-0.5">Total yang diminta</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600">Biaya Admin</p>
                            <p class="text-xs text-gray-500 mt-0.5">Biaya layanan pencairan</p>
                        </div>
                        <p class="text-lg font-semibold text-red-600">- Rp {{ number_format($withdrawal->admin_fee, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border-2 border-green-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-green-800 mb-0.5">Dana Yang Diterima</p>
                                <p class="text-xs text-green-600">Transfer ke rekening Anda</p>
                            </div>
                            <p class="text-3xl font-bold text-green-700">Rp {{ number_format($withdrawal->total_received, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($withdrawal->notes || $withdrawal->admin_notes)
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-5 h-5 text-green-700" />
                    Catatan
                </h3>
                
                @if($withdrawal->notes)
                <div class="mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Catatan Anda:</p>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $withdrawal->notes }}</p>
                    </div>
                </div>
                @endif
                
                @if($withdrawal->admin_notes)
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Catatan Admin:</p>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <p class="text-sm text-blue-800 leading-relaxed">{{ $withdrawal->admin_notes }}</p>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-clock class="w-5 h-5 text-green-700" />
                    Timeline
                </h3>
                
                <div class="space-y-4">
                    <!-- Requested -->
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <x-heroicon-s-paper-airplane class="w-4 h-4 text-green-700" />
                            </div>
                            @if($withdrawal->approved_at || $withdrawal->completed_at || $withdrawal->rejected_at)
                            <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-4">
                            <p class="font-semibold text-gray-900">Pengajuan Dibuat</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $withdrawal->requested_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    
                    <!-- Approved -->
                    @if($withdrawal->approved_at)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <x-heroicon-s-check-circle class="w-4 h-4 text-blue-700" />
                            </div>
                            @if($withdrawal->completed_at)
                            <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-4">
                            <p class="font-semibold text-gray-900">Disetujui Admin</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $withdrawal->approved_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Completed -->
                    @if($withdrawal->completed_at)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <x-heroicon-s-check-badge class="w-4 h-4 text-green-700" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Transfer Selesai</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $withdrawal->completed_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Rejected -->
                    @if($withdrawal->rejected_at)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                <x-heroicon-s-x-circle class="w-4 h-4 text-red-700" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Pengajuan Ditolak</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $withdrawal->rejected_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="{{ route('seller.withdrawals.index') }}"
                    class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition text-center">
                    Kembali ke Daftar
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
        .max-w-5xl, .max-w-5xl * {
            visibility: visible;
        }
        .max-w-5xl {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        button, a[href*="withdrawals.index"] {
            display: none !important;
        }
    }
</style>
@endpush
