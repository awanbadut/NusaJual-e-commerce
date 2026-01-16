<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Nusa Belanja</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <section class="pt-28 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto">
            <div
                class="relative w-full h-[200px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-6 shadow-sm">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('https://placehold.co/100x100/5c3208/white?text=Pattern'); background-size: 100px;">
                </div>
                <div class="relative z-10 flex flex-col gap-1">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-[#0F4C20]">Keranjang Belanja</h1>
                    <p class="text-base md:text-lg font-medium text-[#8B4513]">
                        Cek kembali produk sebelum lanjut ke pembayaran
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-24 px-4 sm:px-6 lg:px-8" x-data="cartData()">

        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

            <div class="flex-1 flex flex-col gap-6">

                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <div class="text-lg font-bold text-gray-700">
                        Total <span x-text="itemCount"></span> Item Siap Checkout
                    </div>
                    <button @click="clearCart()"
                        class="flex items-center gap-2 text-[#0F4C20] font-bold text-sm hover:text-red-600 transition">
                        <x-heroicon-s-trash class="w-5 h-5" />
                        Kosongkan Keranjang
                    </button>
                </div>

                <template x-for="(mitra, mIndex) in cart" :key="mitra.id">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                        <div class="px-6 py-4 border-b border-gray-100 bg-[#FAFAFA] flex items-center gap-3">
                            <input type="checkbox"
                                class="w-5 h-5 rounded border-gray-300 text-[#0F4C20] focus:ring-[#0F4C20]"
                                :checked="isMitraSelected(mIndex)" @change="toggleMitra(mIndex)">

                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-[#2E3B27]" x-text="mitra.name"></h3>
                                <p class="text-xs text-[#8B4513] font-medium"
                                    x-text="mitra.items.length + ' Produk dari mitra lokal'"></p>
                            </div>
                            <x-heroicon-s-chevron-up class="w-5 h-5 text-gray-400" />
                        </div>

                        <div class="p-4 flex flex-col gap-4">
                            <template x-for="(item, iIndex) in mitra.items" :key="item.id">
                                <div
                                    class="flex flex-col sm:flex-row gap-4 p-4 border border-gray-100 rounded-lg hover:shadow-md transition">

                                    <div class="flex items-start gap-3">
                                        <input type="checkbox"
                                            class="w-5 h-5 mt-1 rounded border-gray-300 text-[#0F4C20] focus:ring-[#0F4C20]"
                                            x-model="item.selected">

                                        <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                            <img :src="item.image" class="w-full h-full object-cover">
                                        </div>
                                    </div>

                                    <div class="flex-1 flex flex-col justify-between">
                                        <div class="flex justify-between items-start">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-gray-400"
                                                    x-text="item.category"></span>
                                                <h4 class="text-base font-bold text-[#2E3B27] line-clamp-1"
                                                    x-text="item.name"></h4>
                                            </div>
                                            <span class="text-sm font-bold text-[#0F4C20]"
                                                x-text="formatRupiah(item.price * item.qty)"></span>
                                        </div>
                                        <div class="mt-1 flex items-baseline gap-1">
                                            <span class="text-[#8B4513] font-bold text-sm"
                                                x-text="formatRupiah(item.price)"></span>
                                            <span class="text-xs text-gray-500">/Kg</span>
                                            <span x-show="item.old_price"
                                                class="text-xs text-gray-400 line-through ml-2"
                                                x-text="formatRupiah(item.old_price)"></span>
                                        </div>

                                        {{-- baris hapus dan quantitay --}}
                                        <div class="flex items-center justify-between">
                                            <button @click="removeItem(mIndex, iIndex)"
                                                class="flex items-center gap-1 text-xs text-gray-400 hover:text-red-500 transition">
                                                <x-heroicon-s-trash class="w-4 h-4" />
                                                Hapus
                                            </button>

                                            <div class="flex items-center border border-gray-300 rounded-lg h-9">
                                                <button @click="if(item.qty > 1) item.qty--"
                                                    class="px-2 text-gray-500 hover:text-[#0F4C20] hover:bg-gray-50 h-full rounded-l-lg">-</button>
                                                <input type="text"
                                                    class="w-10 text-center text-sm font-bold text-gray-700 border-none focus:ring-0 p-0"
                                                    x-model="item.qty" readonly>
                                                <button @click="item.qty++"
                                                    class="px-2 text-gray-500 hover:text-[#0F4C20] hover:bg-gray-50 h-full rounded-r-lg">+</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </template>
                        </div>

                    </div>
                </template>

            </div>

            <div class="w-full lg:w-[380px] shrink-0">
                <div class="bg-white border border-gray-200 rounded-xl shadow-lg sticky top-28 p-6 flex flex-col gap-4">

                    <h3 class="text-lg font-bold text-[#0F4C20] pb-3 border-b border-gray-100 text-center">
                        Yang Akan Kamu Bayar
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Banyak Produk</span>
                            <span class="font-bold text-gray-800" x-text="selectedCount"></span>
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
                            <span class="text-gray-600 font-medium">Pengiriman</span>
                            <span class="text-gray-400 italic font-medium text-xs">Lanjut Untuk Lihat Ongkir</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-base font-bold text-gray-700">Estimasi Total</span>
                        <span class="text-xl font-bold text-[#0F4C20]" x-text="formatRupiah(subTotal + 1000)"></span>
                    </div>

                    <button
                        class="w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 transition shadow-md mt-2">
                        Siap Checkout
                        <x-heroicon-s-arrow-right class="w-5 h-5" />
                    </button>

                </div>
            </div>

        </div>
    </section>

    <x-footer />

    <script>
        function cartData() {
            return {
                cart: [
                    {
                        id: 1,
                        name: 'Mitra Makmur Jaya',
                        items: [
                            { id: 101, name: 'Egestas vehicula', category: 'Kopi', price: 200000, old_price: 0, qty: 2, image: 'https://placehold.co/100x100/brown/white?text=Kopi', selected: true },
                            { id: 102, name: 'Habitasse facilisis', category: 'Sawit', price: 350000, old_price: 399000, qty: 1, image: 'https://placehold.co/100x100/orange/white?text=Sawit', selected: false },
                            { id: 103, name: 'Amet purus', category: 'Kopi', price: 250000, old_price: 300000, qty: 2, image: 'https://placehold.co/100x100/brown/white?text=Kopi', selected: true }
                        ]
                    },
                    {
                        id: 2,
                        name: 'Mitra Tani Sejahtera',
                        items: [
                            { id: 201, name: 'Diam commodo', category: 'Teh', price: 150000, old_price: 0, qty: 2, image: 'https://placehold.co/100x100/green/white?text=Teh', selected: false }
                        ]
                    }
                ],

                // Hitung total item yang dipilih (hanya yang dicentang)
                get selectedCount() {
                    let count = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            if(item.selected) count += item.qty;
                        });
                    });
                    return count;
                },

                // Hitung total item di keranjang (termasuk yang tidak dipilih)
                get itemCount() {
                    let count = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            count += item.qty;
                        });
                    });
                    return count;
                },

                // Hitung Subtotal Harga (hanya yang dicentang)
                get subTotal() {
                    let total = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            if(item.selected) total += (item.price * item.qty);
                        });
                    });
                    return total;
                },

                // Format Rupiah
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                },

                // Logic Checkbox Mitra (Pilih Semua)
                isMitraSelected(mIndex) {
                    return this.cart[mIndex].items.every(item => item.selected);
                },
                toggleMitra(mIndex) {
                    let allSelected = this.isMitraSelected(mIndex);
                    this.cart[mIndex].items.forEach(item => item.selected = !allSelected);
                },

                // Logic Hapus Item
                removeItem(mIndex, iIndex) {
                    this.cart[mIndex].items.splice(iIndex, 1);
                    // Hapus mitra jika item habis
                    if (this.cart[mIndex].items.length === 0) {
                        this.cart.splice(mIndex, 1);
                    }
                },

                // Kosongkan Keranjang
                clearCart() {
                    if(confirm('Yakin ingin mengosongkan keranjang?')) {
                        this.cart = [];
                    }
                }
            }
        }
    </script>

</body>

</html>