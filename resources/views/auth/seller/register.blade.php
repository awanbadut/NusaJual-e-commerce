<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sebagai Penjual - NusaJual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f5f5dc] min-h-screen py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8 mx-4">
        <!-- Logo -->
        <div class="flex items-center justify-center mb-6">
            <div class="bg-green-800 text-white px-4 py-2 rounded font-bold mr-2">Logo</div>
            <span class="text-xl font-semibold">Nusa Belanja</span>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Daftar Sebagai Penjual</h1>
            <p class="text-gray-600">Bergabung dan mulai kembangkan bisnis and bersama kami</p>
        </div>

        <!-- Alert Errors -->
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <ul class="list-disc list-inside text-sm text-red-800 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Form Register -->
        <form action="{{ route('seller.register.submit') }}" method="POST">
            @csrf

            <!-- Informasi Toko -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Toko</h2>
                
                <!-- Nama Toko -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nama Toko</label>
                    <input 
                        type="text" 
                        name="store_name" 
                        value="{{ old('store_name') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('store_name') border-red-500 @enderror" 
                        placeholder="Contoh : Jaya Selalu" 
                        required>
                </div>

                <!-- Nama Pemilik & Nomor Telepon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nama Pemilik</label>
                        <input 
                            type="text" 
                            name="owner_name" 
                            value="{{ old('owner_name') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('owner_name') border-red-500 @enderror" 
                            placeholder="Contoh : Nienow-Heidenreich" 
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
                        <input 
                            type="tel" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('phone') border-red-500 @enderror" 
                            placeholder="Contoh : 145-927-232-312" 
                            required>
                    </div>
                </div>
            </div>

            <!-- Alamat Toko -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Alamat Toko</h2>
                
                <!-- Provinsi & Kota -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Provinsi</label>
                        <select 
                            name="province" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('province') border-red-500 @enderror" 
                            required>
                            <option value="">Pilih Provinsi</option>
                            <option value="Riau" {{ old('province') == 'Riau' ? 'selected' : '' }}>Riau</option>
                            <option value="Sumatera Barat" {{ old('province') == 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                            <option value="Jawa Barat" {{ old('province') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                            <option value="DKI Jakarta" {{ old('province') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Kota Kabupaten</label>
                        <select 
                            name="city" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('city') border-red-500 @enderror" 
                            required>
                            <option value="">Pilih Kabupaten</option>
                            <option value="Pekanbaru" {{ old('city') == 'Pekanbaru' ? 'selected' : '' }}>Pekanbaru</option>
                            <option value="Padang" {{ old('city') == 'Padang' ? 'selected' : '' }}>Padang</option>
                            <option value="Bandung" {{ old('city') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                        </select>
                    </div>
                </div>

                <!-- Kecamatan & Kode Pos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Kecamatan</label>
                        <select 
                            name="district" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('district') border-red-500 @enderror" 
                            required>
                            <option value="">Pilih Kecamatan</option>
                            <option value="Tampan" {{ old('district') == 'Tampan' ? 'selected' : '' }}>Tampan</option>
                            <option value="Marpoyan Damai" {{ old('district') == 'Marpoyan Damai' ? 'selected' : '' }}>Marpoyan Damai</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Kode Pos</label>
                        <input 
                            type="text" 
                            name="postal_code" 
                            value="{{ old('postal_code') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('postal_code') border-red-500 @enderror" 
                            placeholder="5 digit" 
                            maxlength="5" 
                            required>
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Alamat Lengkap</label>
                    <textarea 
                        name="address" 
                        rows="3" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('address') border-red-500 @enderror" 
                        placeholder="Nama Jalan, gedung, No. rumah, RT/RW dan patokan" 
                        required>{{ old('address') }}</textarea>
                </div>
            </div>

            <!-- Email & Password -->
            <div class="mb-6">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror" 
                        placeholder="nama@email.com" 
                        required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror" 
                            placeholder="••••••••" 
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Konfirmasi Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" 
                            placeholder="••••••••" 
                            required>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-green-800 text-white py-3 rounded-lg font-semibold hover:bg-green-900 transition flex items-center justify-center">
                Daftar Sekarang →
            </button>
        </form>

        <!-- Login Link -->
        <p class="text-center text-gray-600 mt-4">
            Sudah Punya Akun? 
            <a href="{{ route('seller.login') }}" class="text-green-800 font-semibold hover:underline">
                Login Sekarang
            </a>
        </p>
    </div>
</body>
</html>
