<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sebagai {{ ucfirst($role) }} - Nusa Belanja</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] min-h-screen flex items-center justify-center p-4">

    <div
        class="bg-white w-full max-w-[572px] rounded-[10px] shadow-lg border border-gray-100 p-8 md:p-12 flex flex-col items-center gap-8">

        <div class="flex items-center gap-3">
            <div class="bg-[#0F4C20] rounded px-3 py-2 text-white font-bold tracking-tight text-label-2">
                Logo
            </div>
            <span class="text-h6 font-bold text-gray-700">Nusa Belanja</span>
        </div>

        <div class="text-center">
            <h1 class="text-h4 font-bold text-black mb-2 tracking-tight">Selamat Datang</h1>
            <p class="text-gray-500 font-medium text-body-1">Pilih Peran Anda untuk Melanjutkan</p>
        </div>

        <div class="w-full bg-[#ECFDF5] p-2.5 rounded-lg flex gap-3">

            <a href="{{ route('login.pembeli') }}"
                class="flex-1 rounded-md p-3 flex flex-col items-center justify-center gap-2 transition cursor-pointer
               {{ $role == 'pembeli' ? 'bg-[#0F4C20] text-white shadow-sm' : 'bg-[#D1FAE5] text-[#475569] hover:bg-[#A7F3D0]' }}">

                @if($role == 'pembeli')
                <x-heroicon-s-shopping-cart class="w-6 h-6" />
                @else
                <x-heroicon-o-shopping-cart class="w-6 h-6" />
                @endif
                <span class="font-bold text-label-2">Pembeli</span>
            </a>

            <a href="{{ route('login.penjual') }}"
                class="flex-1 rounded-md p-3 flex flex-col items-center justify-center gap-2 transition cursor-pointer
               {{ $role == 'penjual' ? 'bg-[#0F4C20] text-white shadow-sm' : 'bg-[#D1FAE5] text-[#475569] hover:bg-[#A7F3D0]' }}">

                @if($role == 'penjual')
                <x-heroicon-s-building-storefront class="w-6 h-6" />
                @else
                <x-heroicon-o-building-storefront class="w-6 h-6" />
                @endif
                <span class="font-bold text-label-2">Penjual</span>
            </a>
        </div>

        <div class="w-full bg-[#FFFFF0] border border-orange-100 rounded-[10px] p-3 flex items-start gap-3">
            <x-heroicon-s-information-circle class="h-5 w-5 text-[#8B4513] shrink-0 mt-0.5" />
            <p class="text-body-3 text-[#8B4513] font-medium leading-tight">
                @if($role == 'pembeli')
                Masuk sebagai pembeli untuk akses cepat ke ribuan produk menari
                @endif
            </p>
        </div>

        <div class="w-full">

            @if($role == 'pembeli')
            <div class="flex flex-col items-center gap-3">
                <button
                    class="w-full bg-[#0F4C20] hover:bg-green-900 text-white font-bold py-4 px-6 rounded-md flex items-center justify-center gap-3 transition shadow-md">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4" />
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853" />
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05" />
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335" />
                    </svg>
                    <span class="text-label-1 font-bold">Lanjutkan Dengan Google</span>
                </button>
                <p class="text-gray-500 text-body-3 font-medium">Tidak perlu password, akses instan dan aman</p>
            </div>
            @endif

        </div>
    </div>
</body>

</html>