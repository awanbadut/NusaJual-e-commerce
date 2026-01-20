<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Nusa Belanja</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <div class="pt-24 pb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex items-center gap-2 text-sm font-medium text-[#8B4513]">
            <a href="{{ route('keranjang') }}" class="hover:underline">Keranjang</a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="text-[#0F4C20] font-bold">Checkout</span>
        </div>
    </div>

    <section class="px-4 sm:px-6 lg:px-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative w-full h-[180px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-6 shadow-sm">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('img/pattern-kopi1.png'); background-size: 100%">
                </div>
                <div class="relative z-10 flex flex-col gap-1">
                    <h1 class="text-3xl md:text-4xl font-bold text-[#0F4C20]">Yuk, Selesaikan Pesanan</h1>
                    <p class="text-lg md:text-lg font-medium text-[#8B4513]">
                        Atur detailnya dulu, pembayaran di langkah terakhir.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-24 px-4 sm:px-6 lg:px-8" x-data="checkoutData()">

        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

            <div class="flex-1 flex flex-col gap-6">

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <div class="flex items-center gap-2 text-[#8B4513] font-bold text-lg">
                            <x-heroicon-s-map-pin class="w-5 h-5" />
                            <h3>Alamat Pengiriman</h3>
                        </div>
                        <button class="text-sm font-medium text-gray-500 hover:text-[#0F4C20]">Ubah Alamat</button>
                    </div>

                    <div class="text-gray-700">
                        <div class="flex items-baseline gap-2 mb-1">
                            <span class="font-bold text-lg text-[#2E3B27]">Theresa Lebsack</span>
                            <span class="text-sm text-gray-500 font-medium">(748-511-5598)</span>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Mulberry Street, Adamsbury 27378-5715<br>
                            53144 Swaniawski Key, Huntington Beach 23654
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div
                        class="flex items-center gap-2 text-[#0F4C20] font-bold text-lg border-b border-gray-100 pb-4 mb-4">
                        <x-heroicon-s-shopping-bag class="w-5 h-5" />
                        <h3>Ringkasan Produk</h3>
                    </div>

                    <div class="flex flex-col gap-4">
                        <template x-for="item in items" :key="item.id">
                            <div class="flex gap-4 p-3 bg-[#FAFAFA] border border-gray-100 rounded-lg">
                                <div
                                    class="w-20 h-20 bg-white rounded-lg overflow-hidden shrink-0 border border-gray-200">
                                    <img :src="item.image" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 flex flex-col justify-center">
                                    <span class="text-xs font-bold text-gray-400" x-text="item.category"></span>
                                    <h4 class="text-base font-bold text-[#2E3B27] line-clamp-1" x-text="item.name"></h4>
                                    <span class="text-[#8B4513] font-bold text-sm mt-1"
                                        x-text="item.qty + ' Kg'"></span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-base font-bold text-gray-700"
                                        x-text="formatRupiah(item.price * item.qty)"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div
                        class="flex items-center gap-2 text-[#2E3B27] font-bold text-lg border-b border-gray-100 pb-4 mb-4">
                        <x-heroicon-s-truck class="w-5 h-5" />
                        <h3>Pilih Jasa Kirim</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <template x-for="courier in couriers" :key="courier.id">
                            <div @click="selectedCourier = courier"
                                class="cursor-pointer border rounded-lg p-4 flex flex-col gap-1 transition relative overflow-hidden"
                                :class="selectedCourier.id === courier.id ? 'border-[#0F4C20] bg-green-50 ring-1 ring-[#0F4C20]' : 'border-gray-200 hover:border-gray-300 bg-white'">

                                <span class="font-bold text-[#8B4513]" x-text="courier.name"></span>
                                <span class="text-xs text-gray-500" x-text="'Estimasi ' + courier.etd"></span>
                                <span class="font-bold text-gray-700 mt-2" x-text="formatRupiah(courier.price)"></span>

                                <div x-show="selectedCourier.id === courier.id"
                                    class="absolute top-2 right-2 text-[#0F4C20]">
                                    <x-heroicon-s-check-circle class="w-5 h-5" />
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <div class="w-full lg:w-[380px] shrink-0">
                <div class="bg-white border border-gray-200 rounded-xl shadow-lg sticky top-28 p-6 flex flex-col gap-4">

                    <h3 class="text-lg font-bold text-[#0F4C20] pb-3 border-b border-gray-100 text-center">
                        Yang Akan Kamu Bayar
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Banyak Produk</span>
                            <span class="font-bold text-gray-800" x-text="items.length"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Sub Total</span>
                            <span class="font-bold text-gray-800" x-text="formatRupiah(subTotal)"></span>
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
                            <span class="font-bold text-gray-800" x-text="formatRupiah(selectedCourier.price)"></span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-base font-bold text-gray-700">Estimasi Total</span>
                        <span class="text-xl font-bold text-[#0F4C20]"
                            x-text="formatRupiah(subTotal + 1000 + selectedCourier.price)"></span>
                    </div>

                    <a href="{{route('payment')}}"
                        class="w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 transition shadow-md mt-2">
                        Lakukan Pembayaran
                        <x-heroicon-s-arrow-right class="w-5 h-5" />
                    </a>

                </div>
            </div>

        </div>
    </section>

    <x-footer />

    <script>
        function checkoutData() {
            return {
                items: [
                    { id: 1, name: 'Egestas vehicula', category: 'Kopi', price: 200000, qty: 2, image: 'https://placehold.co/100x100/brown/white?text=Kopi' },
                    { id: 2, name: 'Amet purus', category: 'Kopi', price: 250000, qty: 2, image: 'https://placehold.co/100x100/brown/white?text=Kopi' }
                ],
                couriers: [
                    { id: 1, name: 'JNE Reguler', etd: '5-7 Hari', price: 450000 },
                    { id: 2, name: 'JNT Express', etd: '3-5 Hari', price: 500000 },
                    { id: 3, name: 'Sicepat', etd: '7-14 Hari', price: 200000 },
                    { id: 4, name: 'Sikilat', etd: '7-10 Hari', price: 250000 },
                ],
                selectedCourier: { id: 1, name: 'JNE Reguler', etd: '5-7 Hari', price: 450000 }, // Default JNE

                get subTotal() {
                    return this.items.reduce((total, item) => total + (item.price * item.qty), 0);
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                }
            }
        }
    </script>

</body>

</html>