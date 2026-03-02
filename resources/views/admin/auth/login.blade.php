<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Nusa Belanja</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#F8FCF8] min-h-screen flex items-center justify-center p-4">

    <div
        class="bg-white w-full max-w-[572px] rounded-xl shadow-lg border border-gray-100 p-6 md:p-12 flex flex-col items-center gap-6 md:gap-8">

        <div class="flex items-center justify-center">
            <img src="{{ asset('img/logo/3.jpeg') }}" alt="Icon Nusa Belanja" class="h-8 md:h-10 w-auto object-contain">
        </div>

        <div class="text-center">
            <h1 class="text-2xl md:text-3xl font-bold text-black mb-1 md:mb-2 tracking-tight">Admin Dashboard</h1>
            <p class="text-gray-500 font-medium text-xs md:text-sm">Nusa Belanja Management System</p>
        </div>

        <div
            class="w-full bg-[#FFFFF0] border border-orange-100 rounded-lg p-2.5 md:p-3 flex items-start gap-2 md:gap-3">
            <svg class="h-4 w-4 md:h-5 md:w-5 text-[#8B4513] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd" />
            </svg>
            <p class="text-[11px] md:text-xs text-[#8B4513] font-medium leading-relaxed">
                Masuk sebagai administrator untuk mengelola sistem dan transaksi Nusa Belanja.
            </p>
        </div>

        <form action="{{ route('admin.login.submit') }}" method="POST" class="w-full flex flex-col gap-4 md:gap-5">
            @csrf

            @if($errors->any())
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 flex items-start">
                <span class="text-amber-600 mr-2 text-sm">⚠️</span>
                <div class="text-xs md:text-sm text-amber-800">
                    @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-3 flex items-start">
                <span class="text-green-600 mr-2 text-sm">✓</span>
                <p class="text-xs md:text-sm text-green-800">{{ session('success') }}</p>
            </div>
            @endif

            <div>
                <label class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2.5 md:px-4 md:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] placeholder-gray-400 font-medium @error('email') border-red-500 @enderror"
                    placeholder="admin@nusabelanja.com" required autofocus>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password"
                        class="w-full px-3 py-2.5 md:px-4 md:py-3 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] placeholder-gray-400 font-medium @error('password') border-red-500 @enderror"
                        placeholder="••••••••" required>

                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#0F4C20]">
                        <svg id="eye-icon-off" class="h-4 w-4 md:h-5 md:w-5 hidden" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                        <svg id="eye-icon-on" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember"
                        class="w-4 h-4 text-[#0F4C20] border-gray-300 rounded focus:ring-[#0F4C20]">
                    <span class="ml-2 text-xs md:text-sm text-gray-600 font-medium">Ingat saya</span>
                </label>
            </div>

            <button type="submit"
                class="mt-1 md:mt-2 w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3 md:py-3.5 px-6 rounded-lg flex items-center justify-center gap-2 transition shadow-md text-sm md:text-base active:scale-95">
                Masuk ke Dashboard
                <svg class="h-4 w-4 md:h-5 md:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </button>
        </form>

        <div
            class="text-center text-[11px] md:text-sm text-gray-500 font-medium w-full border-t border-gray-100 pt-6 mt-2">
            <a href="{{ route('home') }}"
                class="text-gray-500 hover:text-[#0F4C20] transition inline-flex items-center gap-1 font-semibold">
                &larr; Kembali ke halaman utama
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