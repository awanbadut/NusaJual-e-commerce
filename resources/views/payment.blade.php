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

    <div class="pt-24 pb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex items-center gap-2 text-sm font-medium text-[#8B4513]">
            <a href="{{ route('keranjang') }}" class="hover:underline">Keranjang</a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="text-gray-400">Checkout</span>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="text-[#0F4C20] font-bold">Pembayaran</span>
        </div>
    </div>

    <section class="px-4 sm:px-6 lg:px-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative w-full h-[180px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-6 shadow-sm">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('{{ asset('img/pattern-kopi1.png') }}'); background-size: 100%">
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

                            <span class="text-3xl md:text-4xl font-bold text-gray-800">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>

                            <div
                                class="flex items-center gap-2 bg-[#FEF9C3] text-[#854D0E] px-3 py-1.5 rounded-full text-xs font-bold mt-1">
                                <x-heroicon-s-clock class="w-4 h-4" />
                                <span>Bayar sebelum {{ $order->created_at->addDay()->translatedFormat('d F Y, H:i')
                                    }}</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <p class="text-sm font-medium text-gray-600">Silahkan transfer ke rekening berikut :</p>

                            <div
                                class="flex flex-col sm:flex-row items-center gap-4 p-4 bg-[#F8F9FA] rounded-lg border border-gray-200">
                                <div
                                    class="w-20 h-12 bg-white rounded flex items-center justify-center border border-gray-100 shrink-0 p-2">
                                    @if($activeBank->logo_url)
                                    {{-- Jika file SVG ditemukan di folder img/icon/ --}}
                                    <img src="{{ $activeBank->logo_url }}" alt="{{ $activeBank->bank_name }}"
                                        class="h-full w-full object-contain">
                                    @else
                                    {{-- Jika file TIDAK ditemukan, tampilkan Heroicon sebagai fallback --}}
                                    <x-heroicon-s-building-library class="w-5 h-5 text-gray-400" />
                                    @endif

                                </div>

                                <div class="flex-1 text-center sm:text-left">
                                    <div class="flex items-center justify-center sm:justify-start gap-2">
                                        <span
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block sm:hidden">
                                            {{ $activeBank->bank_name }}
                                        </span>

                                        <h2 class="text-xl md:text-2xl font-bold text-gray-800 tracking-wide">
                                            {{ $activeBank->account_number }}
                                        </h2>

                                        <button @click="copyToClipboard('{{ $activeBank->account_number }}')"
                                            class="flex items-center gap-1 text-xs font-bold text-[#0F4C20] hover:bg-green-50 px-2 py-1 rounded transition relative">
                                            <x-heroicon-s-document-duplicate class="w-4 h-4" />
                                            <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">
                                        a.n <span class="font-bold text-gray-700">{{ $activeBank->account_holder
                                            }}</span>
                                        <span class="hidden sm:inline text-gray-300 mx-2">|</span>
                                        <span class="hidden sm:inline font-bold text-gray-400">{{ $activeBank->bank_name
                                            }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <p class="text-sm font-medium text-gray-600">Upload Bukti Transfer :</p>

                            <form action="{{ route('payment.process', $order->id) }}" method="POST"
                                enctype="multipart/form-data" id="paymentForm" x-data="{ isLoading: false }"
                                @submit="isLoading = true"> @csrf

                                <label
                                    class="flex flex-col items-center justify-center w-full h-48 border-2 border-[#8B4513] border-dashed rounded-xl cursor-pointer bg-[#FFFCF5] hover:bg-[#FFF8E6] transition group relative">

                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                        <div
                                            class="w-12 h-12 rounded-full bg-[#8B4513] bg-opacity-10 flex items-center justify-center mb-3 group-hover:scale-110 transition">
                                            <x-heroicon-s-arrow-up-tray class="w-6 h-6 text-white" />
                                        </div>

                                        <p class="mb-1 text-sm text-gray-600 font-medium">
                                            <span class="font-bold text-[#8B4513]">Klik untuk Upload</span> atau seret
                                            file kesini
                                        </p>
                                        <p class="text-xs text-gray-400">SVG, PNG, JPG (Max. 2MB)</p>

                                        <p x-show="fileName"
                                            class="mt-4 text-sm font-bold text-[#0F4C20] bg-white px-3 py-1 rounded-full border border-green-200 shadow-sm animate-fade-in">
                                            File: <span x-text="fileName"></span>
                                        </p>
                                    </div>

                                    <input type="file" name="payment_proof"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        @change="handleFileUpload" accept="image/*" required />
                                </label>

                                @error('payment_proof')
                                <p class="text-red-500 text-sm mt-2 text-center font-bold bg-red-50 py-1 rounded">{{
                                    $message }}</p>
                                @enderror

                                <button type="submit" :disabled="isLoading"
                                    :class="isLoading ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#0b3a18]'"
                                    class="w-full bg-[#0F4C20] text-white font-bold py-4 rounded-lg flex items-center justify-center gap-2 transition shadow-md text-lg mt-6">

                                    <span x-show="!isLoading">Konfirmasi Pembayaran</span>
                                    <span x-show="isLoading">Memproses...</span>

                                    <svg x-show="isLoading" class="animate-spin h-5 w-5 text-white ml-2"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>

                                    <x-heroicon-s-check-circle x-show="!isLoading" class="w-5 h-5" />
                                </button>
                            </form>
                        </div>

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
                        <div class="text-center pb-2">
                            <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">Invoice: {{
                                $order->order_number }}</span>
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

                    <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-base font-bold text-gray-700">Total</span>
                        <span class="text-xl font-bold text-[#0F4C20]">Rp {{ number_format($order->total_amount, 0, ',',
                            '.') }}</span>
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