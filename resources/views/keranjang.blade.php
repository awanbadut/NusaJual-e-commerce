<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang - Nusa Belanja</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <!-- Hero Section -->
    <section class="pt-28 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto">
            <div class="relative w-full h-[200px] bg-[#F0EFE6] rounded-xl border border-[#496030] overflow-hidden flex flex-col items-center justify-center text-center p-6 shadow-sm">
                <div class="absolute inset-0 opacity-10" style="background-image: url('{{ asset('img/pattern-kopi1.png') }}'); background-size: 100%"></div>
                <div class="relative z-10 flex flex-col gap-1">
                    <h1 class="text-3xl md:text-4xl font-bold text-[#0F4C20]">Keranjang Belanja</h1>
                    <p class="text-lg md:text-lg font-medium text-[#8B4513]">
                        Cek kembali produk sebelum lanjut ke pembayaran
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
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
                'old_price' => 0,
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

    <!-- Main Cart Section -->
    <section class="pb-24 px-4 sm:px-6 lg:px-8" x-data="cartData()">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

            <!-- Left: Cart Items -->
            <div class="flex-1 flex flex-col gap-6">

                <!-- Header -->
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <div class="text-lg font-bold text-gray-700">
                        Total <span x-text="itemCount"></span> Item Siap Checkout
                    </div>

                    <form action="{{ route('keranjang.clear') }}" method="POST" id="clearCartForm">
                        @csrf 
                        @method('DELETE')
                        <button type="button" @click="clearCartConfirm()"
                            class="flex items-center gap-2 text-[#0F4C20] font-bold text-sm hover:text-red-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Kosongkan Keranjang
                        </button>
                    </form>
                </div>

                <!-- Empty State -->
                <div x-show="cart.length === 0" class="text-center py-20 bg-white rounded-xl border border-gray-200">
                    <div class="inline-flex bg-gray-100 p-4 rounded-full mb-3">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Keranjang Masih Kosong</h3>
                    <p class="text-sm text-gray-500 mb-4">Yuk mulai belanja produk lokal dari mitra kami!</p>
                    <a href="{{ route('katalog') }}" 
                       class="inline-block bg-[#0F4C20] text-white px-6 py-3 rounded-lg font-bold hover:bg-[#0b3a18] transition">
                        Mulai Belanja
                    </a>
                </div>

                <!-- Cart Items by Store -->
                <template x-for="(mitra, mIndex) in cart" :key="mitra.id">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                        <!-- Store Header -->
                        <div class="px-6 py-4 border-b border-gray-100 bg-[#FAFAFA] flex items-center gap-3">
                            <input type="checkbox"
                                class="w-5 h-5 rounded border-gray-300 text-[#0F4C20] focus:ring-[#0F4C20] cursor-pointer"
                                :checked="isMitraSelected(mIndex)" 
                                @change="toggleMitra(mIndex)">

                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-[#2E3B27]" x-text="mitra.name"></h3>
                                <p class="text-xs text-[#8B4513] font-medium" x-text="mitra.items.length + ' Produk dari mitra lokal'"></p>
                            </div>
                        </div>

                        <!-- Store Items -->
                        <div class="p-4 flex flex-col gap-4">
                            <template x-for="(item, iIndex) in mitra.items" :key="item.id">
                                <div class="flex flex-col sm:flex-row gap-4 p-4 border border-gray-100 rounded-lg hover:shadow-md transition">

                                    <!-- Left: Checkbox + Image -->
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox"
                                            class="w-5 h-5 mt-1 rounded border-gray-300 text-[#0F4C20] focus:ring-[#0F4C20] cursor-pointer"
                                            x-model="item.selected">

                                        <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden shrink-0 border border-gray-200">
                                            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                                        </div>
                                    </div>

                                    <!-- Right: Product Details -->
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div class="flex justify-between items-start">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-gray-400 uppercase" x-text="item.category"></span>
                                                <h4 class="text-base font-bold text-[#2E3B27] line-clamp-2" x-text="item.name"></h4>
                                            </div>
                                            <span class="text-base font-bold text-[#0F4C20] ml-4" x-text="formatRupiah(item.price * item.qty)"></span>
                                        </div>

                                        <div class="mt-1 flex items-baseline gap-1">
                                            <span class="text-[#8B4513] font-bold text-sm" x-text="formatRupiah(item.price)"></span>
                                            <span class="text-xs text-gray-500" x-text="'/' + item.unit"></span>
                                            <span class="text-xs text-gray-500 ml-2" x-text="'(Stok: ' + item.stock + ')'"></span>
                                        </div>

                                        <!-- Actions: Delete + Quantity -->
                                        <div class="flex items-center justify-between mt-3">
                                            <button @click="deleteItem(item.id)"
                                                class="flex items-center gap-1 text-xs text-gray-400 hover:text-red-500 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>

                                            <!-- Quantity Controls -->
                                            <div class="flex items-center border border-gray-300 rounded-lg h-9">
                                                <button @click="updateQty(item.id, item.qty - 1, mIndex, iIndex)"
                                                    class="px-3 text-gray-500 hover:text-[#0F4C20] hover:bg-gray-50 h-full rounded-l-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                    :disabled="item.qty <= 1">−</button>

                                                <input type="text"
                                                    class="w-12 text-center text-sm font-bold text-gray-700 border-none focus:ring-0 p-0 bg-transparent"
                                                    x-model="item.qty" 
                                                    readonly>

                                                <button @click="updateQty(item.id, item.qty + 1, mIndex, iIndex)"
                                                    class="px-3 text-gray-500 hover:text-[#0F4C20] hover:bg-gray-50 h-full rounded-r-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                    :disabled="item.qty >= item.stock">+</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </template>
                        </div>

                    </div>
                </template>

            </div>

            <!-- Right: Summary -->
            <div class="w-full lg:w-[380px] shrink-0" x-show="cart.length > 0" x-cloak>
                <div class="bg-white border border-gray-200 rounded-xl shadow-lg sticky top-28 p-6 flex flex-col gap-4">

                    <h3 class="text-lg font-bold text-[#0F4C20] pb-3 border-b border-gray-100 text-center">
                        Yang Akan Kamu Bayar
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Total Item</span>
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
                        <span class="text-xl font-bold text-[#0F4C20]" x-text="formatRupiah(subTotal > 0 ? subTotal + 1000 : 0)"></span>
                    </div>

                    <form action="{{ route('checkout.review') }}" method="POST" @submit="handleCheckout">
                        @csrf
                        <input type="hidden" name="selected_items" x-model="selectedItemIds">

                        <button type="submit"
                            class="w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 transition shadow-md mt-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="selectedCount === 0">
                            Siap Checkout
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
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
                cart: @json($cartData),
                updating: false,

                get selectedCount() {
                    let count = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            if (item.selected) {
                                count += parseInt(item.qty);
                            }
                        });
                    });
                    return count;
                },

                get itemCount() {
                    let count = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            count += parseInt(item.qty);
                        });
                    });
                    return count;
                },

                get subTotal() {
                    let total = 0;
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            if (item.selected) {
                                let price = parseFloat(item.price);
                                let qty = parseInt(item.qty);
                                total += (price * qty);
                            }
                        });
                    });
                    return total;
                },

                get selectedItemIds() {
                    let ids = [];
                    this.cart.forEach(mitra => {
                        mitra.items.forEach(item => {
                            if (item.selected) {
                                ids.push(item.id);
                            }
                        });
                    });
                    return ids.join(',');
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                },

                isMitraSelected(mIndex) {
                    return this.cart[mIndex].items.every(item => item.selected);
                },

                toggleMitra(mIndex) {
                    let allSelected = this.isMitraSelected(mIndex);
                    this.cart[mIndex].items.forEach(item => item.selected = !allSelected);
                },

                async updateQty(cartId, newQty, mIndex, iIndex) {
                    newQty = parseInt(newQty);
                    
                    if (newQty < 1) {
                        alert('Quantity minimal 1');
                        return;
                    }

                    const item = this.cart[mIndex].items[iIndex];

                    if (newQty > item.stock) {
                        alert(`Stok tidak mencukupi! Stok tersedia: ${item.stock}`);
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

                        const result = await response.json();

                        if (!response.ok) {
                            item.qty = oldQty;
                            alert(result.message || 'Gagal update quantity');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        item.qty = oldQty;
                        alert('Terjadi kesalahan saat mengupdate quantity');
                    } finally {
                        this.updating = false;
                    }
                },

                deleteItem(id) {
                    if (!confirm('Hapus produk ini dari keranjang?')) return;

                    const form = document.createElement('form');
                    form.action = `/keranjang/${id}`;
                    form.method = 'POST';

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
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
                        alert('Pilih minimal 1 produk untuk checkout');
                        return false;
                    }
                    return true;
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

</body>

</html>
