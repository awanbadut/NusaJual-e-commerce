<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Nusa Belanja' }}</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <section class="pt-28 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative w-full h-[200px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-6 shadow-sm">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('{{ asset('img/pattern-kopi1.png') }}'); background-size: 100%">
                </div>
                <div class="relative z-10 flex flex-col gap-2">
                    <h1 class="text-3xl md:text-4xl font-bold text-[#0F4C20]">
                        {{ $headerTitle ?? 'Akun Saya' }}
                    </h1>
                    <p class="text-lg font-medium text-[#8B4513]">
                        {{ $headerSubtitle ?? 'Kelola informasi profil dan pesananmu' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8">

            <div class="lg:col-span-1">
                <x-profile-sidebar />
            </div>

            <div class="lg:col-span-3">
                {{ $slot }}
            </div>

        </div>
    </section>

    <x-footer />

</body>

</html>