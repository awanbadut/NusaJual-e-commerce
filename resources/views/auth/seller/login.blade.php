<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sebagai Penjual - Nusa Belanja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] min-h-screen flex items-center justify-center p-4">

    <div
        class="bg-white w-full max-w-[572px] rounded-[10px] shadow-lg border border-gray-100 p-8 md:p-12 flex flex-col items-center gap-8">

        <div class="flex items-center justify-center">
            <img src="{{ asset('img/logo/3.png') }}" alt="Icon Nusa Belanja" class="h-10 w-auto object-contain">
        </div>

        <div class="text-center">
            <h1 class="text-2xl font-bold text-black mb-2 tracking-tight">Selamat Datang</h1>
            <p class="text-gray-500 font-medium">Pilih Peran Anda untuk Melanjutkan</p>
        </div>

        <div class="w-full bg-[#ECFDF5] p-2.5 rounded-lg flex gap-3">

            <a href="{{ route('login.pembeli') }}"
                class="flex-1 rounded-md p-3 flex flex-col items-center justify-center gap-2 transition cursor-pointer bg-[#D1FAE5] text-[#475569] hover:bg-[#A7F3D0]">
                <x-heroicon-o-shopping-cart class="w-6 h-6" />
                <span class="font-bold text-sm">Pembeli</span>
            </a>

            <a href="{{ route('seller.login') }}"
                class="flex-1 rounded-md p-3 flex flex-col items-center justify-center gap-2 transition cursor-pointer bg-[#0F4C20] text-white shadow-sm">
                <x-heroicon-s-building-storefront class="w-6 h-6" />
                <span class="font-bold text-sm">Penjual</span>
            </a>

        </div>

        <div class="w-full bg-[#FFFFF0] border border-orange-100 rounded-[10px] p-3 flex items-start gap-3">
            <x-heroicon-s-information-circle class="h-5 w-5 text-[#8B4513] shrink-0 mt-0.5" />
            <p class="text-sm text-[#8B4513] font-medium leading-tight">
                Masuk sebagai penjual untuk mengelola toko Anda
            </p>
        </div>

        <form action="{{ route('seller.login.submit') }}" method="POST" class="w-full flex flex-col gap-5">
            @csrf

            @if($errors->any())
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 flex items-start">
                <span class="text-amber-600 mr-2">⚠️</span>
                <div class="text-sm text-amber-800">
                    @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-3 flex items-start">
                <span class="text-green-600 mr-2">✓</span>
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
            @endif

            <div>
                <label class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] placeholder-gray-400 font-medium @error('email') border-red-500 @enderror"
                    placeholder="nama@email.com" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password"
                        class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] placeholder-gray-400 font-medium @error('password') border-red-500 @enderror"
                        placeholder="********" required>

                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#0F4C20]">
                        <svg id="eye-icon-off" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                        <svg id="eye-icon-on" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
            <button type="submit"
                class="mt-2 w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3.5 px-6 rounded-lg flex items-center justify-center gap-2 transition shadow-md">
                Login Sekarang
                <x-heroicon-m-arrow-right class="h-5 w-5" />
            </button>
        </form>

        <div class="text-center text-sm text-gray-500 font-medium">
            Belum Punya Akun?
            <a href="{{ route('seller.register') }}"
                class="text-gray-500 underline decoration-gray-400 hover:text-[#0F4C20] font-semibold">
                Daftar Sekarang
            </a>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const iconOn = document.getElementById('eye-icon-on');
            const iconOff = document.getElementById('eye-icon-off');

            if (input.type === "password") {
                input.type = "text";
                iconOn.classList.add('hidden');
                iconOff.classList.remove('hidden');
            } else {
                input.type = "password";
                iconOn.classList.remove('hidden');
                iconOff.classList.add('hidden');
            }
        }
    </script>
</body>

</html>