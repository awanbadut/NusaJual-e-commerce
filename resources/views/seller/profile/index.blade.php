@extends('layouts.seller')

@section('title', 'Profile')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="max-w-7xl">
    <!-- Back Button & Title -->
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('seller.dashboard') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-green-800">Profile</h1>
            <p class="text-sm text-gray-600">Kelola Informasi toko dan rekening pembayaran anda</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column: Store Info & Address -->
        <div class="space-y-6">
            <!-- Informasi Mitra -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-bold text-green-800 mb-4">Informasi Mitra</h2>
                
                <!-- Store Profile Card -->
                <div class="flex items-center gap-4 mb-6 p-4 bg-green-50 rounded-lg border border-green-100">
                    <!-- Store Logo -->
                    @if($store->store_logo)
                        <img src="{{ asset('storage/' . $store->store_logo) }}" 
                             class="w-20 h-20 object-cover rounded-lg">
                    @else
                        <div class="w-20 h-20 bg-green-200 rounded-lg flex items-center justify-center">
                            <svg class="w-10 h-10 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">Mitra Terdaftar</p>
                        <p class="text-base font-bold text-gray-900">{{ $store->store_name }}</p>
                        <div class="flex items-center gap-1 text-xs text-gray-600 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $store->address ?? 'Jalan Limau Manis City No. 12, Padang' }}</span>
                        </div>
                        <button class="text-xs text-green-800 font-medium mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Edit Logo
                        </button>
                    </div>
                </div>

                <!-- Store Info Form -->
                <form action="{{ route('seller.profile.update-store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-900">Informasi Toko</h3>
                        <button type="button" class="text-green-800 font-medium text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Edit
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Store Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Toko</label>
                            <input 
                                type="text" 
                                name="store_name"
                                value="{{ $store->store_name }}"
                                placeholder="Contoh: Jaya Selalu"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                        </div>

                        <!-- Owner Name & Phone -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pemilik</label>
                                <input 
                                    type="text" 
                                    name="owner_name"
                                    value="{{ $store->owner_name }}"
                                    placeholder="Contoh: Nienow-Heidenreich"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input 
                                    type="text" 
                                    name="phone"
                                    value="{{ $store->phone }}"
                                    placeholder="Contoh: 145-927-232-312"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" class="px-6 py-2.5 bg-green-100 text-gray-700 rounded-lg hover:bg-green-200 transition text-sm font-medium">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Alamat Toko -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form action="{{ route('seller.profile.update-address') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-900">Alamat Toko</h3>
                        <button type="button" class="text-green-800 font-medium text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Edit
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Province & City -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                <select name="province" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                                    <option>Pilih Provinsi</option>
                                    <option selected>Sumatera Barat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kota Kabupaten</label>
                                <select name="city" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                                    <option>Pilih Kabupaten</option>
                                    <option selected>Padang</option>
                                </select>
                            </div>
                        </div>

                        <!-- District & Postal Code -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                                <select name="district" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                                    <option>Pilih Kecamatan</option>
                                    <option selected>Koto Tangah</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                <input 
                                    type="text" 
                                    name="postal_code"
                                    value="25175"
                                    placeholder="5 digit kode pos"
                                    maxlength="5"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                            </div>
                        </div>

                        <!-- Full Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea 
                                name="full_address"
                                rows="4"
                                placeholder="Nama Jalan, gedung, No. rumah, RT/RW dan patokan"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">{{ $store->address }}</textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" class="px-6 py-2.5 bg-green-100 text-gray-700 rounded-lg hover:bg-green-200 transition text-sm font-medium">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Bank Account & Password -->
        <div class="space-y-6">
            <!-- Rekening Mitra -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-green-800">Rekening Mitra</h2>
                    <button class="text-green-800 font-medium text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit
                    </button>
                </div>

                <!-- Warning Message -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4 flex items-start gap-2">
                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-xs text-orange-800">
                        Pastikan data rekening anda benar untuk kelancaran proses pencairan dana
                    </p>
                </div>

                <form action="{{ route('seller.profile.update-bank') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <!-- Bank Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                            <select name="bank_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                                <option>Pilih Bank</option>
                                <option>BCA</option>
                                <option>BNI</option>
                                <option>BRI</option>
                                <option>Mandiri</option>
                            </select>
                        </div>

                        <!-- Account Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input 
                                type="text" 
                                name="account_number"
                                placeholder="Contoh: 145-927-232-312"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                        </div>

                        <!-- Account Holder -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Atas Nam Rekening</label>
                            <input 
                                type="text" 
                                name="account_holder"
                                placeholder="Contoh: Nienow-Heidenreich"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" class="px-6 py-2.5 bg-green-100 text-gray-700 rounded-lg hover:bg-green-200 transition text-sm font-medium">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Keamanan Akun -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-green-800">Keamanan Akun</h2>
                    <button class="text-green-800 font-medium text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Ubah Kata Sandi
                    </button>
                </div>

                <form action="{{ route('seller.profile.change-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input 
                                type="password" 
                                name="password"
                                placeholder="••••••••"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <input 
                                type="password" 
                                name="password_confirmation"
                                placeholder="••••••••"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="px-6 py-2.5 bg-green-800 text-white rounded-lg hover:bg-green-900 transition text-sm font-medium">
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
