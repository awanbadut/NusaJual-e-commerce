<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sebagai {{ ucfirst($role) }} - Nusa Belanja</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] min-h-screen flex items-center justify-center p-4">

    <div
        class="bg-white w-full max-w-[572px] rounded-xl shadow-lg border border-gray-100 p-6 md:p-12 flex flex-col items-center gap-6 md:gap-8">

        <div class="flex items-center justify-center">
            <img src="{{ asset('img/logo/3.jpeg') }}" alt="Icon Nusa Belanja" class="h-8 md:h-10 w-auto object-contain">
        </div>

        <div class="text-center">
            <h1 class="text-2xl md:text-3xl font-bold text-black mb-1 md:mb-2 tracking-tight">Selamat Datang</h1>
            <p class="text-gray-500 font-medium text-xs md:text-sm">Pilih Peran Anda untuk Melanjutkan</p>
        </div>

        <div class="w-full bg-[#ECFDF5] p-1.5 md:p-2.5 rounded-lg flex gap-2 md:gap-3">

            <a href="{{ route('login.pembeli') }}"
                class="flex-1 rounded-md p-2 md:p-3 flex flex-col items-center justify-center gap-1.5 md:gap-2 transition cursor-pointer
                {{ $role == 'pembeli' ? 'bg-[#0F4C20] text-white shadow-sm' : 'bg-[#D1FAE5] text-[#475569] hover:bg-[#A7F3D0]' }}">

                @if($role == 'pembeli')
                <x-heroicon-s-shopping-cart class="w-5 h-5 md:w-6 md:h-6" />
                @else
                <x-heroicon-o-shopping-cart class="w-5 h-5 md:w-6 md:h-6" />
                @endif
                <span class="font-bold text-xs md:text-sm">Pembeli</span>
            </a>

            <a href="{{ route('login.penjual') }}"
                class="flex-1 rounded-md p-2 md:p-3 flex flex-col items-center justify-center gap-1.5 md:gap-2 transition cursor-pointer
                {{ $role == 'penjual' ? 'bg-[#0F4C20] text-white shadow-sm' : 'bg-[#D1FAE5] text-[#475569] hover:bg-[#A7F3D0]' }}">

                @if($role == 'penjual')
                <x-heroicon-s-building-storefront class="w-5 h-5 md:w-6 md:h-6" />
                @else
                <x-heroicon-o-building-storefront class="w-5 h-5 md:w-6 md:h-6" />
                @endif
                <span class="font-bold text-xs md:text-sm">Penjual</span>
            </a>
        </div>

        <div
            class="w-full bg-[#FFFFF0] border border-orange-100 rounded-lg p-2.5 md:p-3 flex items-start gap-2 md:gap-3">
            <x-heroicon-s-information-circle class="h-4 w-4 md:h-5 md:w-5 text-[#8B4513] shrink-0 mt-0.5" />
            <p class="text-[11px] md:text-xs text-[#8B4513] font-medium leading-relaxed">
                @if($role == 'pembeli')
                Masuk sebagai pembeli untuk akses cepat ke ribuan produk menarik
                @endif
            </p>
        </div>

        <div class="w-full">
            @if($role == 'pembeli')
            <div class="flex flex-col items-center gap-2 md:gap-3">
                <a href="{{ route('auth.google')}}"
                    class="w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3 md:py-4 px-4 md:px-6 rounded-lg flex items-center justify-center gap-2.5 md:gap-3 transition shadow-md">
                    <svg class="w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                    <span class="text-sm md:text-base font-bold">Lanjutkan Dengan Google</span>
                </a>
                <p class="text-gray-500 text-[10px] md:text-xs font-medium text-center">Tidak perlu password, akses
                    instan dan aman</p>
            </div>
            @endif
        </div>
    </div>
</body>

</html>