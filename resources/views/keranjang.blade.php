<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang - Nusa Belanja</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Memberi ruang di bawah agar konten tidak tertutup sticky bar di mobile */
        @media (max-width: 1024px) {
            body {
                padding-bottom: 90px;
            }
        }
    </style>
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800 relative">

    <x-navbar />

    <section class="pt-24 md:pt-28 pb-4 md:pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto">
            <div
                class="relative w-full h-[120px] md:h-[200px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-4 md:p-6 shadow-sm">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('{{ asset('img/pattern-kopi1.webp') }}'); background-size: cover; background-position: center;">
                </div>
                <div class="relative z-10 flex flex-col gap-0.5 md:gap-1">
                    <h1 class="text-xl md:text-4xl font-bold text-[#0F4C20]">Keranjang Belanja</h1>
                    <p class="text-[11px] md:text-lg font-medium text-[#8B4513]">
                        Cek kembali produk sebelum lanjut ke pembayaran
                    </p>
                </div>
            </div>
        </div>
    </section>

    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
        <div class="bg-green-50 border-l-4 border-green-500 p-3 md:p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 mr-2 md:mr-3" />
                <p class="text-xs md:text-sm font-semibold text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
        <div class="bg-red-50 border-l-4 border-red-500 p-3 md:p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <x-heroicon-s-x-circle class="w-5 h-5 text-red-500 mr-2 md:mr-3" />
                <p class="text-xs md:text-sm font-semibold text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    @php
    $cartData = [];
    foreach($groupedCarts as $storeId => $items) {
    $storeName = $items->first()->product->store->store_name;
    $storeItems = [];

    foreach($items as $item) {
    $storeItems[] = [
    'id' => $item->id,
    'product_id' => $item->product_id,
    'name' => $item->product->name,
    'category' => $item->product->category->name ?? 'Umum',
    'price' => floatval($item->product->price),
    'qty' => $item->quantity,
    'stock' => $item->product->stock,
    'image' => $item->product->primaryImage
    ? asset('storage/'.$item->product->primaryImage->image_path)
    : 'https://placehold.co/100x100/brown/white?text='.urlencode($item->product->name),
    'unit' => $item->product->unit,
    'selected' => false
    ];
    }

    $cartData[] = [
    'id' => $storeId,
    'name' => $storeName,
    'items' => $storeItems
    ];
    }
    @endphp

    <section class="pb-16 md:pb-24 px-4 sm:px-6 lg:px-8" x-data="cartData()">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6 md:gap-8">

            <div class="flex-1 flex flex-col gap-4 md:gap-6">

                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <div class="text-sm md:text-lg font-bold text-gray-700">
                        Total <span x-text="itemCount"></span> Item
                    </div>

                    <form action="{{ route('keranjang.clear') }}" method="POST" id="clearCartForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="clearCartConfirm()"
                            class="flex items-center gap-1 md:gap-2 text-[#0F4C20] font-bold text-xs md:text-sm hover:text-red-600 transition">
                            <x-heroicon-o-trash class="w-4 h-4 md:w-5 md:h-5" />
                            <span class="hidden sm:inline">Kosongkan Keranjang</span>
                            <span class="sm:hidden">Hapus Semua</span>
                        </button>
                    </form>
                </div>

                <div x-show="cart.length === 0"
                    class="text-center py-16 md:py-20 bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="inline-flex bg-gray-100 p-4 rounded-full mb-3">
                        <x-heroicon-o-shopping-cart class="w-10 h-10 md:w-12 md:h-12 text-gray-400" />
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-gray-700 mb-1 md:mb-2">Keranjang Masih Kosong</h3>
                    <p class="text-xs md:text-sm text-gray-500 mb-4">Yuk mulai belanja produk lokal dari mitra kami!</p>
                    <a href="{{ route('katalog') }}"
                        class="inline-block bg-[#0F4C20] text-white px-5 md:px-6 py-2.5 md:py-3 rounded-lg font-bold text-sm hover:bg-[#0b3a18] transition shadow-sm">
                        Mulai Belanja
                    </a>
                </div>

                <template x-for="(mitra, mIndex) in cart" :key="mitra.id">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                        <div
                            class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-100 bg-[#FAFAFA] flex items-center gap-3">
                            <input type="checkbox"
                                class="w-4 h-4 md:w-5 md:h-5 rounded border-gray-300 text-[#0F4C20] focus:ring-[#0F4C20] cursor-pointer"
                                :checked="isMitraSelected(mIndex)" @change="toggleMitra(mIndex)">

                            <div class="flex-1">
                                <h3 class="text-sm md:text-lg font-bold text-[#2E3B27]" x-text="mitra.name"></h3>
                                <p class="text-[10px] md:text-xs text-[#8B4513] font-medium"
                                    x-text="mitra.items.length + ' Produk'"></p>
                            </div>
                        </div>

                        <div class="p-3 md:p-4 flex flex-col gap-3 md:gap-4">
                            <template x-for="(item, iIndex) in mitra.items" :key="item.id">
                                <div
                                    class="flex flex-col sm:flex-row gap-3 p-3 border border-gray-100 rounded-lg hover:shadow-sm transition">

                                    <div class="flex items-start gap-3 w-full">
                                        <input type="checkbox"
                                            class="w-4 h-4 md:w-5 md:h-5 mt-1.5 md:mt-1 rounded border-gray-300 text-[#0F4C20] focus:ring-[#0F4C20] cursor-pointer shrink-0"
                                            x-model="item.selected">

                                        <div
                                            class="w-16 h-16 md:w-24 md:h-24 bg-gray-100 rounded-lg overflow-hidden shrink-0 border border-gray-200">
                                            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                                        </div>

                                        <div class="flex-1 flex flex-col min-w-0">
                                            <span
                                                class="text-[9px] md:text-xs font-bold text-gray-400 uppercase line-clamp-1"
                                                x-text="item.category"></span>
                                            <h4 class="text-xs md:text-base font-bold text-[#2E3B27] line-clamp-2 leading-tight mt-0.5"
                                                x-text="item.name"></h4>

                                            <div class="mt-1 md:mt-2 flex items-baseline gap-1">
                                                <span class="text-[#8B4513] font-bold text-xs md:text-sm"
                                                    x-text="formatRupiah(item.price)"></span>
                                                <span class="text-[9px] md:text-xs text-gray-500"
                                                    x-text="'/' + item.unit"></span>
                                            </div>
                                            <span class="text-[9px] text-gray-400 md:hidden mt-0.5"
                                                x-text="'Sisa: ' + item.stock"></span>
                                        </div>


                                    </div>

                                    <div class="flex flex-col items-end gap-1.5 sm:flex-row sm:items-center sm:gap-6">



                                        {{-- Wrapper Kanan: Harga (Atas) & Quantity (Bawah) --}}
                                        <div class="flex flex-col items-end gap-1.5">

                                            {{-- Harga (Dipindah ke sini agar di atas box) --}}
                                            <span class="text-sm md:text-base font-bold text-[#0F4C20]"
                                                x-text="formatRupiah(item.price * item.qty)">
                                            </span>

                                            {{-- Baris Quantity & Stok --}}
                                            <div class="flex items-center gap-3">
                                                {{-- Tombol Hapus (Tetap di kiri atau sejajar) --}}
                                                <div class="flex items-center gap-2"> {{-- Gunakan gap-2 agar ada
                                                    sedikit spasi yang rapi --}}

                                                    <button @click="deleteItem(item.id)"
                                                        class="flex items-center gap-1 text-[10px] md:text-xs font-bold text-gray-400 hover:text-red-500 transition">
                                                        {{-- HAPUS: mr-auto dan sm:mr-4 dari class di atas --}}
                                                        <x-heroicon-o-trash class="w-4 h-4" />
                                                        <span class="hidden sm:inline">Hapus</span>
                                                    </button>

                                                    <span class="text-xs text-gray-500 hidden md:block"
                                                        x-text="'(Stok: ' + item.stock + ')'"></span>

                                                </div>

                                                <div
                                                    class="flex items-center border border-gray-300 rounded-md md:rounded-lg h-7 md:h-9 bg-white">
                                                    <button @click="updateQty(item.id, item.qty - 1, mIndex, iIndex)"
                                                        class="px-2 md:px-3 text-gray-500 hover:text-[#0F4C20] h-full rounded-l-md transition disabled:opacity-50"
                                                        :disabled="item.qty <= 1">
                                                        <x-heroicon-s-minus class="w-3 h-3 md:w-4 md:h-4" />
                                                    </button>

                                                    <input type="text"
                                                        class="w-8 md:w-12 text-center text-[11px] md:text-sm font-bold text-gray-700 border-none focus:ring-0 p-0 bg-transparent"
                                                        x-model="item.qty" readonly>

                                                    <button @click="updateQty(item.id, item.qty + 1, mIndex, iIndex)"
                                                        class="px-2 md:px-3 text-gray-500 hover:text-[#0F4C20] h-full rounded-r-md transition disabled:opacity-50"
                                                        :disabled="item.qty >= item.stock">
                                                        <x-heroicon-s-plus class="w-3 h-3 md:w-4 md:h-4" />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <div class="hidden lg:block w-[380px] shrink-0" x-show="cart.length > 0" x-cloak>
                <div class="bg-white border border-gray-200 rounded-xl shadow-lg sticky top-28 p-6 flex flex-col gap-4">
                    <h3 class="text-lg font-bold text-[#0F4C20] pb-3 border-b border-gray-100 text-center">
                        Ringkasan Belanja
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Total Item Dipilih</span>
                            <span class="font-bold text-gray-800" x-text="selectedCount"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Sub Total</span>
                            <span class="font-bold text-gray-800" x-text="formatRupiah(subTotal)"></span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex flex-col gap-1">
                        <span class="text-sm font-bold text-gray-700">Total Harga</span>
                        <span class="text-2xl font-bold text-[#0F4C20]" x-text="formatRupiah(subTotal)"></span>
                    </div>

                    <form action="{{ route('checkout.review') }}" method="POST" @submit="handleCheckout">
                        @csrf
                        <input type="hidden" name="selected_items" x-model="selectedItemIds">
                        <button type="submit"
                            class="w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 transition shadow-md mt-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="selectedCount === 0">
                            Checkout Sekarang
                            <x-heroicon-s-arrow-right class="w-5 h-5" />
                        </button>
                    </form>
                </div>
            </div>

            <div x-show="cart.length > 0" x-cloak
                class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-3 flex flex-col gap-2 z-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <form action="{{ route('checkout.review') }}" method="POST" @submit="handleCheckout"
                    class="flex items-center justify-between gap-3 w-full">
                    @csrf
                    <input type="hidden" name="selected_items" x-model="selectedItemIds">

                    <div class="flex flex-col pl-1 flex-1">
                        <div class="flex items-center gap-1 text-[10px] text-gray-500 font-medium leading-none mb-0.5">
                            <span>Subtotal</span>
                            <span class="bg-gray-100 px-1.5 py-0.5 rounded" x-text="selectedCount + ' brg'"></span>
                        </div>
                        <span class="text-lg font-extrabold text-[#0F4C20] leading-tight"
                            x-text="formatRupiah(subTotal)"></span>
                    </div>

                    <button type="submit"
                        class="bg-[#0F4C20] active:bg-[#0b3a18] disabled:bg-gray-400 text-white font-bold rounded-lg h-10 px-6 flex items-center justify-center gap-1.5 shrink-0 transition-transform active:scale-95 shadow-md"
                        :disabled="selectedCount === 0">
                        <span class="text-sm">Checkout</span>
                        <x-heroicon-s-arrow-right class="w-4 h-4" />
                    </button>
                </form>
            </div>

        </div>
    </section>

    <x-footer />

    <script>
        function cartData() {
            return {
                cart: @json($cartData),
                updating: false,

                get selectedCount() {
                    let count = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            if (item.selected) count += parseInt(item.qty);
                        });
                    });
                    return count;
                },

                get itemCount() {
                    let count = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => { count += parseInt(item.qty); });
                    });
                    return count;
                },

                get subTotal() {
                    let total = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            if (item.selected) total += (parseFloat(item.price) * parseInt(item.qty));
                        });
                    });
                    return total;
                },

                get selectedItemIds() {
                    let ids = [];
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            if (item.selected) ids.push(item.id);
                        });
                    });
                    return ids.join(',');
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                },

                isMitraSelected(mIndex) {
                    if(this.cart[mIndex].items.length === 0) return false;
                    return this.cart[mIndex].items.every(item => item.selected);
                },

                toggleMitra(mIndex) {
                    let allSelected = this.isMitraSelected(mIndex);
                    this.cart[mIndex].items.forEach(item => item.selected = !allSelected);
                },

                async updateQty(cartId, newQty, mIndex, iIndex) {
                    newQty = parseInt(newQty);
                    if (newQty < 1) return;

                    const item = this.cart[mIndex].items[iIndex];
                    if (newQty > item.stock) {
                        alert(`Stok tidak mencukupi! Tersedia: ${item.stock}`);
                        return;
                    }

                    if (this.updating) return;
                    this.updating = true;

                    const oldQty = item.qty;
                    item.qty = newQty;

                    try {
                        const response = await fetch(`/keranjang/${cartId}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ quantity: newQty })
                        });

                        if (!response.ok) {
                            const result = await response.json();
                            item.qty = oldQty;
                            alert(result.message || 'Gagal update quantity');
                        }
                    } catch (error) {
                        item.qty = oldQty;
                        alert('Terjadi kesalahan koneksi');
                    } finally {
                        this.updating = false;
                    }
                },

                deleteItem(id) {
                    if (!confirm('Hapus produk ini dari keranjang?')) return;
                    const form = document.createElement('form');
                    form.action = `/keranjang/${id}`;
                    form.method = 'POST';
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                },

                clearCartConfirm() {
                    if (confirm('Yakin ingin mengosongkan keranjang?')) {
                        document.getElementById('clearCartForm').submit();
                    }
                },

                handleCheckout(e) {
                    if (this.selectedCount === 0) {
                        e.preventDefault();
                        alert('Centang minimal 1 produk untuk dicheckout');
                        return false;
                    }
                    return true;
                }
            }
        }
    </script>
</body>

</html>