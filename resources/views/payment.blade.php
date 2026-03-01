<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Nusa Belanja</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <div class="pt-24 md:pt-24 pb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex flex-wrap gap-2 text-xs md:text-sm font-medium text-[#8B4513]">
            <a href="{{ route('keranjang') }}" class="hover:underline">Keranjang</a>
            <x-heroicon-s-chevron-right class="w-3 h-3 md:w-4 md:h-4 text-gray-400" />
            <span class="text-gray-400">Checkout</span>
            <x-heroicon-s-chevron-right class="w-3 h-3 md:w-4 md:h-4 text-gray-400" />
            <span class="text-[#0F4C20] font-bold">Pembayaran</span>
        </div>
    </div>

    <section class="px-4 sm:px-6 lg:px-8 mb-4 md:mb-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative w-full h-[100px] md:h-[180px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-4 shadow-sm">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('{{ asset('img/pattern-kopi1.webp') }}'); background-size: cover; background-position: center;">
                </div>
                <div class="relative z-10 flex flex-col gap-0.5 md:gap-1">
                    <h1 class="text-xl md:text-4xl font-bold text-[#0F4C20]">Unggah Bukti Pembayaran</h1>
                    <p class="text-[10px] md:text-lg font-medium text-[#8B4513]">
                        Upload bukti transfer untuk kami verifikasi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-16 md:pb-24 px-4 sm:px-6 lg:px-8" x-data="paymentData()">

        <div class="max-w-7xl mx-auto flex flex-col-reverse lg:flex-row gap-4 md:gap-8">

            <div class="flex-1 flex flex-col gap-4 md:gap-6">

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                    <div class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-100 bg-white">
                        <div class="flex items-center gap-1.5 md:gap-2 text-[#0F4C20] font-bold text-sm md:text-lg">
                            <x-heroicon-s-credit-card class="w-4 h-4 md:w-5 md:h-5" />
                            <h3>Detail Transfer</h3>
                        </div>
                    </div>

                    <div class="p-4 md:p-6 flex flex-col gap-4 md:gap-6">

                        <div
                            class="flex flex-col items-center justify-center gap-1 md:gap-2 p-3 md:p-4 bg-[#FAFAFA] rounded-lg border border-gray-100">
                            <span class="text-[10px] md:text-sm font-bold text-[#8B4513] uppercase tracking-wide">Total
                                Pembayaran</span>
                            <span class="text-2xl md:text-4xl font-bold text-gray-800">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                            <div
                                class="flex items-center gap-1.5 md:gap-2 bg-[#FEF9C3] text-[#854D0E] px-2.5 py-1 md:px-3 md:py-1.5 rounded-full text-[10px] md:text-xs font-bold mt-1">
                                <x-heroicon-s-clock class="w-3 h-3 md:w-4 md:h-4" />
                                <span>Bayar sebelum {{ $order->created_at->addDay()->translatedFormat('d F Y, H:i')
                                    }}</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Silahkan transfer ke rekening
                                berikut:</p>

                            <div
                                class="flex items-start md:items-center gap-3 md:gap-4 p-3 md:p-4 bg-[#F8F9FA] rounded-lg border border-gray-200">

                                <div
                                    class="w-14 h-10 md:w-20 md:h-12 bg-white rounded flex items-center justify-center border border-gray-100 shrink-0 p-1.5 md:p-2 mt-1 md:mt-0">
                                    @if($activeBank->logo_url)
                                    <img src="{{ $activeBank->logo_url }}" alt="{{ $activeBank->bank_name }}"
                                        class="h-full w-full object-contain">
                                    @else
                                    <x-heroicon-s-building-library class="w-4 h-4 md:w-5 md:h-5 text-gray-400" />
                                    @endif
                                </div>

                                <div class="flex-1 flex flex-col text-left min-w-0 gap-1 md:gap-0">

                                    <span
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block md:hidden">
                                        {{ $activeBank->bank_name }}
                                    </span>

                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2
                                            class="text-lg md:text-2xl font-bold text-gray-800 tracking-wide leading-none">
                                            {{ $activeBank->account_number }}
                                        </h2>
                                        <button @click="copyToClipboard('{{ $activeBank->account_number }}')"
                                            class="flex items-center gap-1 text-[10px] md:text-xs font-bold text-[#0F4C20] bg-green-50 hover:bg-green-100 px-2 py-1 rounded transition border border-[#0F4C20]/20">
                                            <x-heroicon-s-document-duplicate class="w-3 h-3 md:w-4 md:h-4" />
                                            <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                                        </button>
                                    </div>

                                    <p class="text-[11px] md:text-sm text-gray-500 font-medium leading-snug">
                                        a.n <span
                                            class="font-bold text-gray-700 line-clamp-1 md:line-clamp-none inline-block align-bottom max-w-[150px] md:max-w-none">{{
                                            $activeBank->account_holder }}</span>
                                        <span class="hidden md:inline text-gray-300 mx-2">|</span>
                                        <span class="hidden md:inline font-bold text-gray-400">{{ $activeBank->bank_name
                                            }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 pt-2 md:pt-4 border-t border-gray-100 mt-4 md:mt-6">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Upload Bukti Transfer :</p>

                            <form action="{{ route('payment.process', $order->id) }}" method="POST"
                                enctype="multipart/form-data" id="paymentForm" x-data="{ isLoading: false }"
                                @submit="isLoading = true"> @csrf

                                <label
                                    class="flex flex-col items-center justify-center w-full h-32 md:h-48 border-2 border-[#8B4513] border-dashed rounded-xl cursor-pointer bg-[#FFFCF5] hover:bg-[#FFF8E6] transition group relative">
                                    <div class="flex flex-col items-center justify-center text-center px-4">
                                        <div
                                            class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-[#8B4513] bg-opacity-10 flex items-center justify-center mb-2 md:mb-3 group-hover:scale-110 transition">
                                            <x-heroicon-s-arrow-up-tray class="w-5 h-5 md:w-6 md:h-6 text-white" />
                                        </div>
                                        <p class="mb-0.5 text-xs md:text-sm text-gray-600 font-medium">
                                            <span class="font-bold text-[#8B4513]">Klik untuk Upload</span>
                                            <span class="hidden sm:inline"> atau seret file kesini</span>
                                        </p>
                                        <p class="text-[10px] md:text-xs text-gray-400">SVG, PNG, JPG (Max. 2MB)</p>

                                        <p x-show="fileName"
                                            class="mt-2 md:mt-4 text-[10px] md:text-sm font-bold text-[#0F4C20] bg-white px-2 py-0.5 md:px-3 md:py-1 rounded-full border border-green-200 shadow-sm animate-fade-in truncate max-w-[200px] md:max-w-xs">
                                            File: <span x-text="fileName"></span>
                                        </p>
                                    </div>
                                    <input type="file" name="payment_proof"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        @change="handleFileUpload" accept="image/*" required />
                                </label>

                                @error('payment_proof')
                                <p
                                    class="text-red-500 text-[10px] md:text-sm mt-2 text-center font-bold bg-red-50 py-1 rounded">
                                    {{ $message }}</p>
                                @enderror

                                <button type="submit" :disabled="isLoading"
                                    :class="isLoading ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#0b3a18] active:scale-95'"
                                    class="w-full bg-[#0F4C20] text-white font-bold py-3 md:py-4 rounded-lg flex items-center justify-center gap-2 transition-all shadow-md text-sm md:text-lg mt-4 md:mt-6">

                                    <span x-show="!isLoading">Konfirmasi Pembayaran</span>
                                    <span x-show="isLoading">Memproses...</span>

                                    <svg x-show="isLoading" class="animate-spin h-4 w-4 md:h-5 md:w-5 text-white ml-2"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <x-heroicon-s-check-circle x-show="!isLoading" class="w-4 h-4 md:w-5 md:h-5" />
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>

            <div class="w-full lg:w-[380px] shrink-0">
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm lg:shadow-lg lg:sticky lg:top-28 p-4 md:p-6 flex flex-col gap-3 md:gap-4">

                    <h3
                        class="text-sm md:text-lg font-bold text-[#0F4C20] pb-2 md:pb-3 border-b border-gray-100 text-center">
                        Ringkasan Pesanan
                    </h3>

                    <div class="space-y-2 md:space-y-3 text-xs md:text-sm">
                        <div class="text-center pb-1 md:pb-2">
                            <span
                                class="text-[10px] md:text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">Invoice:
                                {{ $order->order_number }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Banyak Produk</span>
                            <span class="font-bold text-gray-800">{{ $order->items->count() }} Pcs</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Sub Total</span>
                            <span class="font-bold text-gray-800">Rp {{ number_format($order->sub_total, 0, ',', '.')
                                }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Layanan</span>
                            <span class="font-bold text-gray-800">Rp 1.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Pengiriman</span>
                            <span class="font-bold text-gray-800">Rp {{ number_format($order->shipping_cost, 0, ',',
                                '.') }}</span>
                        </div>
                    </div>

                    <div class="pt-3 md:pt-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-sm md:text-base font-bold text-gray-700">Total Tagihan</span>
                        <span class="text-lg md:text-xl font-bold text-[#0F4C20]">Rp {{
                            number_format($order->total_amount, 0, ',', '.') }}</span>
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