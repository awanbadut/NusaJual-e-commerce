<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Nusa Belanja</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    {{-- <div class="pt-24 pb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex items-center gap-2 text-sm font-medium text-[#8B4513]">
            <a href="{{ route('keranjang') }}" class="hover:underline">Keranjang</a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <a href="#" class="hover:underline">Checkout</a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="text-[#0F4C20] font-bold">Pembayaran</span>
        </div>
    </div> --}}

    <section class="px-4 sm:px-6 lg:px-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative w-full h-[180px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-6 shadow-sm">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('img/pattern-kopi1.png'); background-size: 100%">
                </div>
                <div class="relative z-10 flex flex-col gap-1">
                    <h1 class="text-3xl md:text-4xl font-bold text-[#0F4C20]">Unggah Bukti Pembayaran</h1>
                    <p class="text-lg md:text-lg font-medium text-[#8B4513]">
                        Upload bukti transfer untuk kami verifikasi dan lanjutkan pesananmu.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-24 px-4 sm:px-6 lg:px-8" x-data="paymentData()">

        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

            <div class="flex-1 flex flex-col gap-6">

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                    <div class="px-6 py-4 border-b border-gray-100 bg-white">
                        <div class="flex items-center gap-2 text-[#0F4C20] font-bold text-lg">
                            <x-heroicon-s-credit-card class="w-5 h-5" />
                            <h3>Detail Pembayaran</h3>
                        </div>
                    </div>

                    <div class="p-6 flex flex-col gap-6">

                        <div
                            class="flex flex-col items-center justify-center gap-2 p-4 bg-[#FAFAFA] rounded-lg border border-gray-100">
                            <span class="text-sm font-bold text-[#8B4513] uppercase tracking-wide">Total
                                Pembayaran</span>
                            <span class="text-3xl md:text-4xl font-bold text-gray-800">Rp 9.451.000</span>

                            <div
                                class="flex items-center gap-2 bg-[#FEF9C3] text-[#854D0E] px-3 py-1.5 rounded-full text-xs font-bold mt-1">
                                <x-heroicon-s-clock class="w-4 h-4" />
                                <span>Bayar sebelum 29 Desember 2025, 23:59</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <p class="text-sm font-medium text-gray-600">Silahkan transfer ke rekening berikut :</p>

                            <div
                                class="flex flex-col sm:flex-row items-center gap-4 p-4 bg-[#F8F9FA] rounded-lg border border-gray-200">
                                <div
                                    class="w-20 h-12 bg-white rounded flex items-center justify-center border border-gray-100 shrink-0">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/BANK_BRI_logo.svg"
                                        alt="BRI" class="h-6">
                                </div>

                                <div class="flex-1 text-center sm:text-left">
                                    <div class="flex items-center justify-center sm:justify-start gap-2">
                                        <h2 class="text-xl md:text-2xl font-bold text-gray-800 tracking-wide">
                                            1234-5678-9012-346</h2>

                                        <button @click="copyToClipboard('1234-5678-9012-346')"
                                            class="flex items-center gap-1 text-xs font-bold text-[#0F4C20] hover:bg-green-50 px-2 py-1 rounded transition relative">
                                            <x-heroicon-s-document-duplicate class="w-4 h-4" />
                                            <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">a.n <span
                                            class="font-bold text-gray-700">Nusa Belanja</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <p class="text-sm font-medium text-gray-600">Upload Bukti Transfer :</p>

                            <label
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-[#8B4513] border-dashed rounded-xl cursor-pointer bg-[#FFFCF5] hover:bg-[#FFF8E6] transition group relative">

                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-[#8B4513] bg-opacity-10 flex items-center justify-center mb-3 group-hover:scale-110 transition">
                                        <x-heroicon-s-arrow-up-tray class="w-6 h-6 text-[#ffffff] text-bold" />
                                    </div>

                                    <p class="mb-1 text-sm text-gray-600 font-medium">
                                        <span class="font-bold text-[#8B4513]">Klik untuk Upload</span> atau seret file
                                        kesini
                                    </p>
                                    <p class="text-xs text-gray-400">SVG, PNG, JPG (Max. 2MB)</p>

                                    <p x-show="fileName"
                                        class="mt-4 text-sm font-bold text-[#0F4C20] bg-white px-3 py-1 rounded-full border border-green-200 shadow-sm animate-fade-in">
                                        File: <span x-text="fileName"></span>
                                    </p>
                                </div>

                                <input type="file" class="hidden" @change="handleFileUpload" accept="image/*" />
                            </label>
                        </div>

                        <a href="{{route('success')}}"
                            class="w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-4 rounded-lg flex items-center justify-center gap-2 transition shadow-md text-lg">
                            Siap Checkout
                            <x-heroicon-s-arrow-right class="w-5 h-5" />
                        </a>

                    </div>
                </div>

            </div>

            <div class="w-full lg:w-[380px] shrink-0">
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-lg lg:sticky lg:top-28 p-6 flex flex-col gap-4">

                    <h3 class="text-lg font-bold text-[#0F4C20] pb-3 border-b border-gray-100 text-center">
                        Ringkasan Pesanan
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Banyak Produk</span>
                            <span class="font-bold text-gray-800">3</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Sub Total</span>
                            <span class="font-bold text-gray-800">Rp 1.000.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Layanan</span>
                            <span class="font-bold text-gray-800">Rp 1.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Discount</span>
                            <span class="font-bold text-gray-800">-Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Pengiriman</span>
                            <span class="font-bold text-gray-800">Rp 450.000</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-base font-bold text-gray-700">Total</span>
                        <span class="text-xl font-bold text-[#0F4C20]">Rp 1.451.000</span>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <x-footer />

    <script>
        function paymentData() {
            return {
                fileName: null,
                copied: false,

                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.fileName = file.name;
                    }
                },

                copyToClipboard(text) {
                    navigator.clipboard.writeText(text);
                    this.copied = true;
                    setTimeout(() => {
                        this.copied = false;
                    }, 2000);
                }
            }
        }
    </script>

</body>

</html>