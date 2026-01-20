<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sebagai Penjual - Nusa Belanja</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] min-h-screen flex items-center justify-center p-4 py-10">

    <div
        class="bg-white w-full max-w-4xl rounded-[10px] shadow-lg border border-gray-100 p-8 md:p-12 flex flex-col items-center gap-8 relative">

        <div class="text-center">
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="bg-[#0F4C20] rounded px-3 py-2 text-white font-bold tracking-tight text-sm">Logo</div>
                <span class="text-xl font-bold text-gray-700">Nusa Belanja</span>
            </div>
            <h1 class="text-2xl font-bold text-black mb-2 tracking-tight">Daftar Sebagai Penjual</h1>
            <p class="text-gray-500 font-medium">Bergabung dan mulai kembangkan bisnis Anda</p>
        </div>

        @if($errors->any())
        <div class="w-full bg-red-50 border border-red-200 rounded-lg p-4 mb-2">
            <ul class="list-disc list-inside text-sm text-red-800 space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('seller.register.submit') }}" method="POST" class="w-full flex flex-col gap-5">
            @csrf

            <div class="border-b border-gray-200 pb-2 mb-2">
                <h3 class="text-[#0F4C20] font-bold text-sm uppercase tracking-wide">Informasi Toko</h3>
            </div>

            <div>
                <label for="store_name" class="block text-gray-700 font-medium mb-2">Nama Toko</label>
                <input id="store_name" type="text" name="store_name" value="{{ old('store_name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] @error('store_name') border-red-500 @enderror"
                    placeholder="Contoh: Jaya Selalu" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="owner_name" class="block text-gray-700 font-medium mb-2">Nama Pemilik</label>
                    <input id="owner_name" type="text" name="owner_name" value="{{ old('owner_name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] @error('owner_name') border-red-500 @enderror"
                        placeholder="Nama Lengkap Anda" required />
                </div>
                <div>
                    <label for="phone" class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] @error('phone') border-red-500 @enderror"
                        placeholder="0812-xxxx-xxxx" required />
                </div>
            </div>

            <div class="border-b border-gray-200 pb-2 mb-2 mt-4">
                <h3 class="text-[#0F4C20] font-bold text-sm uppercase tracking-wide">Alamat Toko</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="province" class="block text-gray-700 font-medium mb-2">Provinsi</label>
                    <select id="province" name="province"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] bg-white @error('province') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Provinsi</option>
                        <option value="Riau" {{ old('province')=='Riau' ? 'selected' : '' }}>Riau</option>
                        <option value="Sumatera Barat" {{ old('province')=='Sumatera Barat' ? 'selected' : '' }}>
                            Sumatera Barat</option>
                        <option value="Jawa Barat" {{ old('province')=='Jawa Barat' ? 'selected' : '' }}>Jawa Barat
                        </option>
                        <option value="DKI Jakarta" {{ old('province')=='DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta
                        </option>
                    </select>
                </div>
                <div>
                    <label for="city" class="block text-gray-700 font-medium mb-2">Kota/Kabupaten</label>
                    <select id="city" name="city"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] bg-white @error('city') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Kota</option>
                        <option value="Pekanbaru" {{ old('city')=='Pekanbaru' ? 'selected' : '' }}>Pekanbaru</option>
                        <option value="Padang" {{ old('city')=='Padang' ? 'selected' : '' }}>Padang</option>
                        <option value="Bandung" {{ old('city')=='Bandung' ? 'selected' : '' }}>Bandung</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="district" class="block text-gray-700 font-medium mb-2">Kecamatan</label>
                    <select id="district" name="district"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] bg-white @error('district') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Kecamatan</option>
                        <option value="Tampan" {{ old('district')=='Tampan' ? 'selected' : '' }}>Tampan</option>
                        <option value="Marpoyan Damai" {{ old('district')=='Marpoyan Damai' ? 'selected' : '' }}>
                            Marpoyan Damai</option>
                    </select>
                </div>
                <div>
                    <label for="postal_code" class="block text-gray-700 font-medium mb-2">Kode Pos</label>
                    <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] @error('postal_code') border-red-500 @enderror"
                        placeholder="xxxxx" maxlength="5" required />
                </div>
            </div>

            <div>
                <label for="address" class="block text-gray-700 font-medium mb-2">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] @error('address') border-red-500 @enderror"
                    placeholder="Nama Jalan, Gedung, No. Rumah..." required>{{ old('address') }}</textarea>
            </div>

            <div class="border-b border-gray-200 pb-2 mb-2 mt-4">
                <h3 class="text-[#0F4C20] font-bold text-sm uppercase tracking-wide">Akun Login</h3>
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] @error('email') border-red-500 @enderror"
                    placeholder="nama@email.com" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input id="password" type="password" name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] @error('password') border-red-500 @enderror"
                        placeholder="********" required />
                </div>
                <div>
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Konfirmasi
                        Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20]"
                        placeholder="********" required />
                </div>
            </div>

            <button type="submit"
                class="mt-4 w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3.5 px-6 rounded-lg flex items-center justify-center gap-2 transition shadow-md">
                Daftar Sekarang
                <x-heroicon-m-arrow-right class="h-5 w-5" />
            </button>
        </form>

        <div class="text-center text-sm text-gray-500">
            Sudah Punya Akun?
            <a href="{{ route('seller.login') }}"
                class="text-gray-500 underline decoration-gray-400 hover:text-[#0F4C20] font-semibold">
                Login Disini
            </a>
        </div>

    </div>
</body>

</html>