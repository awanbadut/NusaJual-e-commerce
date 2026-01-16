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
                <x-form.label for="store_name" value="Nama Toko" />
                <x-form.input id="store_name" name="store_name" placeholder="Contoh: Jaya Selalu" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-form.label for="owner_name" value="Nama Pemilik" />
                    <x-form.input id="owner_name" name="owner_name" placeholder="Nama Lengkap Anda" required />
                </div>
                <div>
                    <x-form.label for="phone" value="Nomor Telepon" />
                    <x-form.input id="phone" type="number" name="phone" placeholder="0812-xxxx-xxxx" required />
                </div>
            </div>

            <div class="border-b border-gray-200 pb-2 mb-2 mt-4">
                <h3 class="text-[#0F4C20] font-bold text-label-1 uppercase tracking-wide">Alamat Toko</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-form.label for="province" value="Provinsi" />
                    <x-form.select id="province" name="province">
                        <option value="">Pilih Provinsi</option>
                        <option value="Jabar">Jawa Barat</option>
                        <option value="Jateng">Jawa Tengah</option>
                        <option value="Jatim">Jawa Timur</option>
                    </x-form.select>
                </div>
                <div>
                    <x-form.label for="city" value="Kota/Kabupaten" />
                    <x-form.select id="city" name="city">
                        <option value="">Pilih Kota</option>
                    </x-form.select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <x-form.label for="district" value="Kecamatan" />
                    <x-form.select id="district" name="district">
                        <option>Pilih</option>
                    </x-form.select>
                </div>
                <div>
                    <x-form.label for="village" value="Kelurahan" />
                    <x-form.select id="village" name="village">
                        <option>Pilih</option>
                    </x-form.select>
                </div>
                <div>
                    <x-form.label for="postal_code" value="Kode Pos" />
                    <x-form.input id="postal_code" type="number" name="postal_code" placeholder="xxxxx" />
                </div>
            </div>

            <div>
                <x-form.label for="address" value="Alamat Lengkap" />
                <x-form.textarea id="address" name="address" rows="3" placeholder="Nama Jalan, Gedung, No. Rumah...">
                </x-form.textarea>
            </div>

            <div class="border-b border-gray-200 pb-2 mb-2 mt-4">
                <h3 class="text-[#0F4C20] font-bold text-label-1 uppercase tracking-wide">Akun Login</h3>
            </div>

            <div>
                <x-form.label for="email" value="Email" />
                <x-form.input id="email" type="email" name="email" placeholder="nama@email.com" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-form.label for="password" value="Password" />
                    <x-form.input id="password" type="password" name="password" placeholder="********" required />
                </div>
                <div>
                    <x-form.label for="password_confirmation" value="Konfirmasi Password" />
                    <x-form.input id="password_confirmation" type="password" name="password_confirmation"
                        placeholder="********" required />
                </div>
            </div>

            <x-ui.button class="mt-4">
                Daftar Sekarang
                <x-heroicon-m-arrow-right class="h-5 w-5" />
            </x-ui.button>
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