<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Nusa Belanja</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
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
                    style="background-image: url('{{ asset('img/pattern-kopi1.webp') }}'); background-size: 100%">
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

    @php
    // Format Item untuk JS
    $jsItems = $cartItems->map(function($item) {
    return [
    'id' => $item->id,
    'name' => $item->product->name,
    'category' => $item->product->category->name ?? 'Umum',
    'price' => $item->product->price,
    'qty' => $item->quantity,
    'image' => $item->product->primaryImage ? asset('storage/'.$item->product->primaryImage->image_path) :
    'https://placehold.co/100x100?text=No+Image'
    ];
    });

    // String ID Item yang dipilih (untuk dikirim ke controller store)
    $selectedIdsString = $cartItems->pluck('id')->implode(',');
    @endphp

    <section class="pb-24 px-4 sm:px-6 lg:px-8" x-data="checkoutData()">

        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
            @csrf
            <input type="hidden" name="selected_items" value="{{ $selectedIdsString }}">
            <input type="hidden" name="shipping_cost" :value="selectedCourier.price">
            <input type="hidden" name="shipping_courier" :value="selectedCourier.name">

            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

                <div class="flex-1 flex flex-col gap-6">

                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                            <div class="flex items-center gap-2 text-[#8B4513] font-bold text-lg">
                                <x-heroicon-s-map-pin class="w-5 h-5" />
                                <h3>Alamat Pengiriman</h3>
                            </div>
                            <a href="{{ route('profile.address') }}"
                                class="text-sm font-medium text-gray-500 hover:text-[#0F4C20]">
                                {{ $primaryAddress ? 'Ubah Alamat' : 'Tambah Alamat' }}
                            </a>
                        </div>

                        <div class="text-gray-700">
                            @if($primaryAddress)
                            <div class="flex items-baseline gap-2 mb-1">
                                <span class="font-bold text-lg text-[#2E3B27]">{{ $primaryAddress->receiver_name
                                    }}</span>
                                <span class="text-sm text-gray-500 font-medium">({{ $primaryAddress->phone }})</span>
                                @if($primaryAddress->is_primary)
                                <span class="bg-[#563B1F] text-white text-[10px] px-2 py-0.5 rounded-full">Utama</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                {{ $primaryAddress->detail_address }} <br>
                                {{ $primaryAddress->village_name }}, {{ $primaryAddress->district_name }} <br>
                                {{ $primaryAddress->city_name }}, {{ $primaryAddress->province_name }} {{
                                $primaryAddress->postal_code }}
                            </p>

                            <input type="hidden" name="address_id" value="{{ $primaryAddress->id }}">
                            @else
                            <div class="text-center py-4 bg-red-50 rounded-lg border border-red-100">
                                <p class="text-red-600 font-medium mb-2">Kamu belum mengatur alamat pengiriman.</p>
                                <a href="{{ route('profile.address') }}"
                                    class="inline-block bg-[#0F4C20] text-white px-4 py-2 rounded text-sm font-bold">
                                    Atur Alamat Sekarang
                                </a>
                            </div>
                            @endif
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
                                        <h4 class="text-base font-bold text-[#2E3B27] line-clamp-1" x-text="item.name">
                                        </h4>
                                        <span class="text-[#8B4513] font-bold text-sm mt-1"
                                            x-text="item.qty + ' Pcs'"></span>
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
                                <div @click="selectedCourier = courier; document.getElementsByName('shipping_courier')[0].value = courier.name; document.getElementsByName('shipping_cost')[0].value = courier.price;"
                                    class="cursor-pointer border rounded-lg p-4 flex flex-col gap-1 transition relative overflow-hidden"
                                    :class="selectedCourier.id === courier.id ? 'border-[#0F4C20] bg-green-50 ring-1 ring-[#0F4C20]' : 'border-gray-200 hover:border-gray-300 bg-white'">

                                    <span class="font-bold text-[#8B4513]" x-text="courier.name"></span>
                                    <span class="text-xs text-gray-500" x-text="'Estimasi ' + courier.etd"></span>
                                    <span class="font-bold text-gray-700 mt-2"
                                        x-text="formatRupiah(courier.price)"></span>

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
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-lg sticky top-28 p-6 flex flex-col gap-4">

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
                                <span class="text-gray-600 font-medium">Pengiriman</span>
                                <span class="font-bold text-gray-800"
                                    x-text="formatRupiah(selectedCourier.price)"></span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-base font-bold text-gray-700">Estimasi Total</span>
                            <span class="text-xl font-bold text-[#0F4C20]"
                                x-text="formatRupiah(subTotal + 1000 + selectedCourier.price)"></span>
                        </div>

                        <button type="submit"
                            class="w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 transition shadow-md mt-2">
                            Lakukan Pembayaran
                            <x-heroicon-s-arrow-right class="w-5 h-5" />
                        </button>

                    </div>
                </div>

            </div>
        </form>

    </section>

    <x-footer />

    <script>
        function checkoutData() {
        return {
            items: @json($jsItems),
            couriers: @json($couriers),
            // Inisialisasi dengan objek kosong jika tidak ada kurir
            selectedCourier: @json($couriers[0] ?? ['id' => null, 'name' => '', 'price' => 0]), 

            get subTotal() {
                return this.items.reduce((total, item) => total + (item.price * item.qty), 0);
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            },
            
            // Tambahkan fungsi submit untuk memastikan data terisi
            submitForm(e) {
                if (!this.selectedCourier.id) {
                    alert('Silakan pilih jasa pengiriman terlebih dahulu!');
                    e.preventDefault();
                    return;
                }
            }
        }
    }
    </script>

</body>

</html>