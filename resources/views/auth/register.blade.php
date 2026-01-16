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
                <div class="bg-[#0F4C20] rounded px-3 py-2 text-white font-bold tracking-tight text-label-2">Logo</div>
                <span class="text-h6 font-bold text-gray-700">Nusa Belanja</span>
            </div>
            <h1 class="text-h4 font-bold text-black mb-2 tracking-tight">Daftar Sebagai Penjual</h1>
            <p class="text-gray-500 font-medium text-body-1">Bergabung dan mulai kembangkan bisnis anda</p>
        </div>

        <form action="#" method="POST" class="w-full flex flex-col gap-5">
            @csrf

            <div class="border-b border-gray-200 pb-2 mb-2">
                <h3 class="text-[#0F4C20] font-bold text-label-1 uppercase tracking-wide">Informasi Toko</h3>
            </div>

            <div>
                <x-input-label for="store_name" value="Nama Toko" />
                <x-text-input id="store_name" name="store_name" placeholder="Contoh: Jaya Selalu" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="owner_name" value="Nama Pemilik" />
                    <x-text-input id="owner_name" name="owner_name" placeholder="Nama Lengkap Anda" required />
                </div>
                <div>
                    <x-input-label for="phone" value="Nomor Telepon" />
                    <x-text-input id="phone" type="number" name="phone" placeholder="0812-xxxx-xxxx" required />
                </div>
            </div>

            <div class="border-b border-gray-200 pb-2 mb-2 mt-4">
                <h3 class="text-[#0F4C20] font-bold text-label-1 uppercase tracking-wide">Alamat Toko</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="province" value="Provinsi" />
                    <x-select-input id="province" name="province">
                        <option value="">Pilih Provinsi</option>
                        <option value="Jabar">Jawa Barat</option>
                        <option value="Jateng">Jawa Tengah</option>
                        <option value="Jatim">Jawa Timur</option>
                    </x-select-input>
                </div>
                <div>
                    <x-input-label for="city" value="Kota/Kabupaten" />
                    <x-select-input id="city" name="city">
                        <option value="">Pilih Kota</option>
                    </x-select-input>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <x-input-label for="district" value="Kecamatan" />
                    <x-select-input id="district" name="district">
                        <option>Pilih</option>
                    </x-select-input>
                </div>
                <div>
                    <x-input-label for="village" value="Kelurahan" />
                    <x-select-input id="village" name="village">
                        <option>Pilih</option>
                    </x-select-input>
                </div>
                <div>
                    <x-input-label for="postal_code" value="Kode Pos" />
                    <x-text-input id="postal_code" type="number" name="postal_code" placeholder="xxxxx" />
                </div>
            </div>

            <div>
                <x-input-label for="address" value="Alamat Lengkap" />
                <x-text-area id="address" name="address" rows="3" placeholder="Nama Jalan, Gedung, No. Rumah...">
                </x-text-area>
            </div>

            <div class="border-b border-gray-200 pb-2 mb-2 mt-4">
                <h3 class="text-[#0F4C20] font-bold text-label-1 uppercase tracking-wide">Akun Login</h3>
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" type="email" name="email" placeholder="nama@email.com" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="password" value="Password" />
                    <x-text-input id="password" type="password" name="password" placeholder="********" required />
                </div>
                <div>
                    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                        placeholder="********" required />
                </div>
            </div>

            <x-primary-button class="mt-4">
                Daftar Sekarang
                <x-heroicon-m-arrow-right class="h-5 w-5" />
            </x-primary-button>
        </form>

        <div class="text-center text-body-3 text-gray-500">
            Sudah Punya Akun?
            <a href="{{ route('login', ['role' => 'penjual']) }}"
                class="text-gray-500 underline decoration-gray-400 hover:text-[#0F4C20] font-semibold">
                Login Disini
            </a>
        </div>

    </div>
</body>

</html>