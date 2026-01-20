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
                    style="background-image: url('{{ asset('img/pattern-kopi1.png') }}'); background-size: 100%">
                </div>
                <div class="relative z-10 flex flex-col gap-1">
                    <h1 class="text-3xl md:text-4xl font-bold text-[#0F4C20]">Keranjang Belanja</h1>
                    <p class="text-lg md:text-lg font-medium text-[#8B4513]">
                        Cek kembali produk sebelum lanjut ke pembayaran
                    </p>
                </div>
            </div>
        </div>
    </section>

    @php
    $cartData = [];
    foreach($groupedCarts as $storeId => $items) {
    $storeName = $items->first()->product->store->store_name;
    $storeItems = [];

    foreach($items as $item) {
    $storeItems[] = [
    'id' => $item->id, // ID Cart
    'product_id' => $item->product_id,
    'name' => $item->product->name,
    'category' => $item->product->category->name ?? 'Umum',
    'price' => $item->product->price,
    'old_price' => 0, // Bisa disesuaikan jika ada field discount
    'qty' => $item->quantity,
    'image' => $item->product->primaryImage ? asset('storage/'.$item->product->primaryImage->image_path) :
    'https://placehold.co/100x100/brown/white?text='.urlencode($item->product->name),
    'unit' => $item->product->unit,
    'selected' => false // Default tidak terpilih
    ];
    }

    $cartData[] = [
    'id' => $storeId,
    'name' => $storeName,
    'items' => $storeItems
    ];
    }
    @endphp

    <section class="pb-24 px-4 sm:px-6 lg:px-8" x-data="cartData()">

        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

            <div class="flex-1 flex flex-col gap-6">

                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <div class="text-lg font-bold text-gray-700">
                        Total <span x-text="itemCount"></span> Item Siap Checkout
                    </div>

                    <form action="{{ route('keranjang.clear') }}" method="POST" id="clearCartForm">
                        @csrf @method('DELETE')
                        <button type="button" @click="clearCartConfirm()"
                            class="flex items-center gap-2 text-[#0F4C20] font-bold text-sm hover:text-red-600 transition">
                            <x-heroicon-s-trash class="w-5 h-5" />
                            Kosongkan Keranjang
                        </button>
                    </form>
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
                                            <span class="text-xs text-gray-500" x-text="'/' + item.unit"></span>
                                            <span x-show="item.old_price"
                                                class="text-xs text-gray-400 line-through ml-2"
                                                x-text="formatRupiah(item.old_price)"></span>
                                        </div>

                                        <div class="flex items-center justify-between mt-3">
                                            <button @click="deleteItem(item.id)"
                                                class="flex items-center gap-1 text-xs text-gray-400 hover:text-red-500 transition">
                                                <x-heroicon-s-trash class="w-4 h-4" />
                                                Hapus
                                            </button>

                                            <div class="flex items-center border border-gray-300 rounded-lg h-9">
                                                <button @click="updateQty(item.id, item.qty - 1, mIndex, iIndex)"
                                                    class="px-2 text-gray-500 hover:text-[#0F4C20] hover:bg-gray-50 h-full rounded-l-lg"
                                                    :disabled="item.qty <= 1">-</button>

                                                <input type="text"
                                                    class="w-10 text-center text-sm font-bold text-gray-700 border-none focus:ring-0 p-0"
                                                    x-model="item.qty" readonly>

                                                <button @click="updateQty(item.id, item.qty + 1, mIndex, iIndex)"
                                                    class="px-2 text-gray-500 hover:text-[#0F4C20] hover:bg-gray-50 h-full rounded-r-lg">+</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </template>
                        </div>

                    </div>
                </template>

                <div x-show="cart.length === 0" class="text-center py-20 bg-white rounded-xl border border-gray-200">
                    <div class="inline-flex bg-gray-100 p-4 rounded-full mb-3">
                        <x-heroicon-o-shopping-cart class="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-700">Keranjang Masih Kosong</h3>
                    <a href="{{ route('katalog') }}" class="text-[#0F4C20] font-bold text-sm mt-2 hover:underline">Mulai
                        Belanja</a>
                </div>

            </div>

            <div class="w-full lg:w-[380px] shrink-0" x-show="cart.length > 0">
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
                        <span class="text-xl font-bold text-[#0F4C20]"
                            x-text="formatRupiah(subTotal > 0 ? subTotal + 1000 : 0)"></span>
                    </div>

                    <form action="#" method="GET"> <template x-for="mitra in cart">
                            <template x-for="item in mitra.items">
                                <input type="hidden" name="selected_items[]" :value="item.id" x-if="item.selected">
                            </template>
                        </template>

                        <button type="submit"
                            class="w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 transition shadow-md mt-2"
                            :disabled="selectedCount === 0"
                            :class="selectedCount === 0 ? 'opacity-50 cursor-not-allowed' : ''">
                            Siap Checkout
                            <x-heroicon-s-arrow-right class="w-5 h-5" />
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </section>

    <x-footer />

    <script>
        function cartData() {
            return {
                // DATA CART DIAMBIL DARI PHP DI ATAS
                cart: @json($cartData),

                get selectedCount() {
                    let count = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            if(item.selected) count += item.qty;
                        });
                    });
                    return count;
                },

                get itemCount() {
                    let count = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            count += item.qty;
                        });
                    });
                    return count;
                },

                get subTotal() {
                    let total = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            if(item.selected) total += (item.price * item.qty);
                        });
                    });
                    return total;
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                },

                isMitraSelected(mIndex) {
                    return this.cart[mIndex].items.every(item => item.selected);
                },
                
                toggleMitra(mIndex) {
                    let allSelected = this.isMitraSelected(mIndex);
                    this.cart[mIndex].items.forEach(item => item.selected = !allSelected);
                },

                // UPDATE QTY KE SERVER VIA FETCH
                updateQty(id, newQty, mIndex, iIndex) {
                    if (newQty < 1) return;

                    // Update UI dulu biar responsif
                    this.cart[mIndex].items[iIndex].qty = newQty;

                    fetch(`/keranjang/${id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ quantity: newQty })
                    });
                },

                // DELETE ITEM (UI + Form Submit Helper)
                deleteItem(id) {
                    if(confirm('Hapus produk ini dari keranjang?')) {
                        // Buat form hidden dinamis untuk method DELETE
                        let form = document.createElement('form');
                        form.action = `/keranjang/${id}`;
                        form.method = 'POST';
                        
                        let csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        
                        let method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';

                        form.appendChild(csrf);
                        form.appendChild(method);
                        document.body.appendChild(form);
                        form.submit();
                    }
                },

                clearCartConfirm() {
                    if(confirm('Yakin ingin mengosongkan keranjang?')) {
                        document.getElementById('clearCartForm').submit();
                    }
                }
            }
        }
    </script>

</body>

</html>