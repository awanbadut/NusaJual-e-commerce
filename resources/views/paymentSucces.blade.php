<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Nusa Belanja</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <section class="pt-28 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto">

            <div
                class="w-full relative flex flex-col items-center p-2.5 box-border gap-1 text-center font-plus-jakarta-sans">

                <div
                    class="w-[108px] h-[108px] bg-[#0F4C20] rounded-full flex items-center justify-center mb-2 shadow-sm">
                    <x-heroicon-s-check class="w-15 h-15 text-white" />
                </div>

                <div class="self-stretch flex flex-col items-center p-2.5 gap-2.5">

                    <h1
                        class="relative tracking-[-0.6px] leading-tight text-3xl md:text-[40px] font-bold text-[#0F4C20]">
                        Bukti Pembayaran Diterima
                    </h1>

                    <p
                        class="self-stretch relative text-lg md:text-xl tracking-[-0.15px] leading-[20px] text-[#8B4513] text-center font-bold">
                        Pembayaranmu aman. Saat ini sedang dalam proses verifikasi oleh admin.
                    </p>

                </div>
            </div>

        </div>
    </section>

    <section class="pb-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto flex flex-col items-center">

            <div class="w-full bg-white border border-gray-200 rounded-xl shadow-sm p-6 md:p-8 flex flex-col gap-8">

                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-100 pb-6 gap-6">

                    <div class="flex flex-col gap-1">
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-wide">Nomor Pesanan</span>
                        <p class="text-xl font-bold text-[#2E3B27]">#NB-2034-8526</p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-wide">Estimasi Tiba</span>
                        <div class="flex items-center gap-2 text-gray-600 font-medium">
                            <x-heroicon-s-calendar class="w-5 h-5 text-[#8B4513]" />
                            <span>Senin, 16 Jan - Rabu, 18 Jan</span>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

                    <div class="md:col-span-7 flex flex-col gap-6">

                        <div class="flex flex-col gap-4">
                            <h3
                                class="text-lg font-bold text-[#0F4C20] flex items-center gap-2 border-b border-gray-100 pb-2">
                                <x-heroicon-s-shopping-bag class="w-5 h-5" />
                                Ringkasan Produk
                            </h3>

                            <div class="flex flex-col gap-3">
                                <div class="flex gap-4 p-3 bg-[#FAFAFA] border border-gray-100 rounded-lg">
                                    <div
                                        class="w-16 h-16 bg-white rounded-md overflow-hidden shrink-0 border border-gray-200">
                                        <img src="https://placehold.co/100x100/brown/white?text=Kopi"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 flex flex-col justify-center">
                                        <span class="text-xs font-bold text-gray-400">Kopi</span>
                                        <h4 class="text-base font-bold text-[#2E3B27]">Egestas vehicula</h4>
                                        <span class="text-[#8B4513] font-bold text-xs mt-0.5">2 Kg</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-base font-bold text-gray-600">Rp 400.000</span>
                                    </div>
                                </div>

                                <div class="flex gap-4 p-3 bg-[#FAFAFA] border border-gray-100 rounded-lg">
                                    <div
                                        class="w-16 h-16 bg-white rounded-md overflow-hidden shrink-0 border border-gray-200">
                                        <img src="https://placehold.co/100x100/brown/white?text=Kopi"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 flex flex-col justify-center">
                                        <span class="text-xs font-bold text-gray-400">Kopi</span>
                                        <h4 class="text-base font-bold text-[#2E3B27]">Amet purus</h4>
                                        <span class="text-[#8B4513] font-bold text-xs mt-0.5">2 Kg</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-base font-bold text-gray-600">Rp 500.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 mt-2">
                            <h3
                                class="text-lg font-bold text-[#0F4C20] flex items-center gap-2 border-b border-gray-100 pb-2">
                                <x-heroicon-s-map-pin class="w-5 h-5" />
                                Alamat Pengiriman
                            </h3>

                            <div
                                class="text-gray-600 text-sm leading-relaxed p-3 bg-[#F8F9FA] rounded-lg border border-gray-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-[#2E3B27] text-base">Theresa Lebsack</span>
                                    <span class="text-gray-500 font-medium">(748-511-5598)</span>
                                </div>
                                <p>Mulberry Street, Adamsbury 27378-5715</p>
                                <p>53144 Swaniawski Key, Huntington Beach 23654</p>
                            </div>
                        </div>

                    </div>

                    <div class="md:col-span-5">
                        <div class="bg-white border border-gray-200 rounded-xl p-5 h-full flex flex-col">
                            <h3
                                class="text-lg font-bold text-[#0F4C20] flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                                <x-heroicon-s-receipt-percent class="w-5 h-5" />
                                Rincian Pembayaran
                            </h3>

                            <div class="space-y-3 text-sm flex-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Sub Total</span>
                                    <span class="font-bold text-gray-800">Rp 1.000.000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Layanan</span>
                                    <span class="font-bold text-gray-800">Rp 1.000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Pengiriman</span>
                                    <span class="font-bold text-gray-800">Rp 450.000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Discount</span>
                                    <span class="font-bold text-gray-800">-Rp 0</span>
                                </div>
                            </div>

                            <div
                                class="pt-4 border-t border-dashed border-gray-200 mt-4 flex justify-between items-center">
                                <span class="text-base font-bold text-gray-700">Total</span>
                                <span class="text-xl font-bold text-[#0F4C20]">Rp 1.451.000</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('katalog') }}"
                        class="px-6 py-3 rounded-lg border border-[#0F4C20] text-[#0F4C20] font-bold text-sm hover:bg-green-50 transition w-full sm:w-auto text-center">
                        Belanja Lagi
                    </a>
                    <a href="#"
                        class="px-6 py-3 rounded-lg bg-[#0F4C20] text-white font-bold text-sm hover:bg-[#0b3a18] transition w-full sm:w-auto text-center">
                        Lihat Status Pesanan
                    </a>
                </div>

            </div>

        </div>
    </section>

    <x-footer />

</body>

</html>