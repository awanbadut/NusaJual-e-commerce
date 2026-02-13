@extends('layouts.admin')

@section('title', 'Profile & Pengaturan - Admin Nusa Belanja')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl sm:text-[28px] font-bold text-[#15803D] mb-1">Pengaturan Akun</h1>
    <p class="text-xs sm:text-[13px] text-[#78716C]">Kelola keamanan akun dan rekening bank untuk operasional</p>
</div>

{{-- Alert Success/Error Global --}}
@if(session('success'))
<div
    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-xs sm:text-[13px] flex items-center gap-3">
    <x-heroicon-s-check-circle class="w-5 h-5" />
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-xs sm:text-[13px]">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- KOLOM KIRI: Profile & Password --}}
    <div class="space-y-6">

        {{-- Card Info Admin --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div
                class="w-16 h-16 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-2xl font-bold">
                {{ strtoupper(substr($admin->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $admin->name }}</h2>
                <p class="text-sm text-gray-500">{{ $admin->email }}</p>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                    Administrator
                </span>
            </div>
        </div>

        {{-- Card Ganti Password --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-2 mb-4 border-b border-gray-100 pb-3">
                <x-heroicon-o-lock-closed class="w-5 h-5 text-gray-500" />
                <h3 class="text-base font-bold text-gray-900">Ganti Password</h3>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    {{-- Password Lama --}}
                    <div x-data="{ show: false }">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="current_password" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm pr-10"
                                placeholder="Masukkan password lama">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <template x-if="!show">
                                    <x-heroicon-o-eye class="w-5 h-5" />
                                </template>
                                <template x-if="show">
                                    <x-heroicon-o-eye-slash class="w-5 h-5" />
                                </template>
                            </button>
                        </div>
                    </div>

                    {{-- Password Baru --}}
                    <div x-data="{ show: false }">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm pr-10"
                                placeholder="Minimal 8 karakter">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <template x-if="!show">
                                    <x-heroicon-o-eye class="w-5 h-5" />
                                </template>
                                <template x-if="show">
                                    <x-heroicon-o-eye-slash class="w-5 h-5" />
                                </template>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div x-data="{ show: false }">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm pr-10"
                                placeholder="Ulangi password baru">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <template x-if="!show">
                                    <x-heroicon-o-eye class="w-5 h-5" />
                                </template>
                                <template x-if="show">
                                    <x-heroicon-o-eye-slash class="w-5 h-5" />
                                </template>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="bg-[#15803D] text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-[#166534] transition shadow-sm flex items-center gap-2">
                        <x-heroicon-s-check class="w-4 h-4" />
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- KOLOM KANAN: Rekening Bank Admin --}}
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-full flex flex-col">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-credit-card class="w-5 h-5 text-gray-500" />
                    <h3 class="text-base font-bold text-gray-900">Rekening Admin (Rekber)</h3>
                </div>
                <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-1 rounded-md font-medium">
                    {{ $bankAccounts->count() }} Rekening
                </span>
            </div>

            <p class="text-xs text-gray-500 mb-6">
                Rekening ini akan ditampilkan kepada pembeli untuk melakukan transfer pembayaran manual.
            </p>

            {{-- List Rekening --}}
            <div class="space-y-3 flex-1">
                @forelse($bankAccounts as $bank)
                <div
                    class="flex items-center justify-between p-4 border border-gray-200 rounded-xl bg-gray-50 hover:bg-white hover:border-green-200 transition group">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-15 h-15 bg-white border border-gray-200 rounded-lg flex items-center justify-center shadow-sm py-3 px-2 shrink-0">
                            @if($bank->logo_url)
                            <img src="{{ $bank->logo_url }}" alt="{{ $bank->bank_name }}"
                                class="h-full w-full object-contain">
                            @else
                            <x-heroicon-s-building-library class="w-5 h-5 text-gray-400" />
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold text-gray-900">{{ $bank->bank_name }}</p>
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-bold {{ $bank->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $bank->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 font-mono">{{ $bank->account_number }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5 uppercase">{{ $bank->account_holder }}</p>
                        </div>
                    </div>

                    {{-- AREA TOMBOL AKSI --}}
                    <div class="flex items-center gap-2">

                        {{-- 1. TOMBOL TOGGLE (Aktifkan/Nonaktifkan) DENGAN ALERT --}}
                        <form action="{{ route('admin.profile.bank.toggle', $bank->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin {{ $bank->is_active ? 'MENONAKTIFKAN' : 'MENGAKTIFKAN' }} rekening {{ $bank->bank_name }}? \n\nCatatan: Mengaktifkan rekening ini akan otomatis menonaktifkan rekening lainnya.');">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="p-2 rounded-lg transition border shadow-sm
                                {{ $bank->is_active 
                                    ? 'bg-white border-gray-200 text-yellow-600 hover:bg-yellow-50 hover:border-yellow-300' 
                                    : 'bg-white border-green-200 text-green-600 hover:bg-green-50 hover:border-green-300' }}"
                                title="{{ $bank->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">

                                @if($bank->is_active)
                                <x-heroicon-o-power class="w-5 h-5" />
                                @else
                                <x-heroicon-o-check-circle class="w-5 h-5" />
                                @endif
                            </button>
                        </form>

                        {{-- 2. TOMBOL DELETE (Hapus Permanen) DENGAN ALERT --}}
                        <form action="{{ route('admin.profile.bank.destroy', $bank->id) }}" method="POST"
                            onsubmit="return confirm('PERINGATAN: Penghapusan bersifat permanen!\n\nApakah Anda yakin ingin menghapus rekening {{ $bank->bank_name }} - {{ $bank->account_number }}?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="p-2 rounded-lg transition border border-red-100 bg-white text-red-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 shadow-sm"
                                title="Hapus Permanen">
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 border-2 border-dashed border-gray-200 rounded-xl">
                    <x-heroicon-o-credit-card class="w-10 h-10 text-gray-300 mx-auto mb-2" />
                    <p class="text-sm text-gray-500">Belum ada rekening terdaftar</p>
                </div>
                @endforelse
            </div>

            {{-- Form Tambah Rekening --}}
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h4 class="text-sm font-bold text-gray-900 mb-4">Tambah Rekening Baru</h4>

                {{-- Banner / Note Peringatan --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <x-heroicon-s-information-circle class="w-5 h-5 text-yellow-600" />
                    </div>
                    <div class="text-xs sm:text-sm text-yellow-800">
                        <p>
                            Pastikan data yang anda masukkan
                            <span class="underline decoration-yellow-500 decoration-2">sesuai</span>
                            dengan yang tertera pada buku tabungan.
                        </p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.bank.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                            <div class="relative">
                                <select name="bank_name" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm appearance-none bg-white cursor-pointer">
                                    <option value="" disabled selected>Pilih Bank</option>
                                    @foreach(['BCA', 'BNI', 'BRI', 'Mandiri', 'BSI', 'CIMB Niaga', 'Danamon', 'Permata',
                                    'Maybank', 'BTN'] as $bank)
                                    <option value="{{ $bank }}">{{ $bank }}</option>
                                    @endforeach
                                </select>
                                {{-- Icon Panah Bawah Custom (Heroicon) --}}
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                    <x-heroicon-m-chevron-down class="w-5 h-5" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                            <input type="text" name="account_number" placeholder="Nomor Rekening" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Atas Nama</label>
                        <div class="flex gap-3">
                            <input type="text" name="account_holder" placeholder="Atas Nama Pemilik" required
                                class="flex-1 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">

                            <button type="submit"
                                class=" bg-[#15803D] text-white font-semibold hover:bg-[#166534] px-5 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                                <x-heroicon-s-plus class="w-4 h-4" />
                                Tambah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection