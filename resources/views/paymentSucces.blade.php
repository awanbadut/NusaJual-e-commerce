<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Nusa Belanja</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <section class="pt-24 md:pt-28 pb-4 md:pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto">

            <div
                class="w-full relative flex flex-col items-center p-2.5 box-border gap-1 md:gap-2 text-center font-plus-jakarta-sans">

                <div
                    class="w-20 h-20 md:w-[108px] md:h-[108px] bg-[#0F4C20] rounded-full flex items-center justify-center mb-1 md:mb-2 shadow-sm animate-bounce">
                    <x-heroicon-s-check class="w-10 h-10 md:w-14 md:h-14 text-white" />
                </div>

                <div class="self-stretch flex flex-col items-center p-2 gap-1.5 md:gap-2.5">
                    <h1 class="relative tracking-tight leading-tight text-2xl md:text-[40px] font-bold text-[#0F4C20]">
                        Bukti Pembayaran Diterima
                    </h1>

                    <p
                        class="self-stretch relative text-sm md:text-xl tracking-tight leading-snug md:leading-[20px] text-[#8B4513] text-center font-bold px-4 md:px-0">
                        Pembayaranmu aman. Saat ini sedang dalam proses verifikasi oleh admin.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <section class="pb-16 md:pb-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto flex flex-col items-center">

            <div
                class="w-full bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-8 flex flex-col gap-5 md:gap-8">

                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-100 pb-4 md:pb-6 gap-3 md:gap-6">
                    <div class="flex flex-col gap-0.5 md:gap-1">
                        <span class="text-[10px] md:text-sm font-bold text-gray-500 uppercase tracking-wide">Nomor
                            Pesanan</span>
                        <p class="text-base md:text-xl font-bold text-[#2E3B27]">{{ $order->order_number }}</p>
                    </div>

                    <div class="flex flex-col gap-0.5 md:gap-1">
                        <span class="text-[10px] md:text-sm font-bold text-gray-500 uppercase tracking-wide">Estimasi
                            Tiba</span>
                        <div class="flex items-center gap-1.5 md:gap-2 text-gray-600 font-medium text-xs md:text-base">
                            <x-heroicon-s-calendar class="w-4 h-4 md:w-5 md:h-5 text-[#8B4513]" />
                            <span>
                                {{ $order->created_at->addDays(3)->translatedFormat('d M') }} -
                                {{ $order->created_at->addDays(5)->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 md:gap-8">

                    <div class="md:col-span-7 flex flex-col gap-5 md:gap-6">

                        <div class="flex flex-col gap-3 md:gap-4">
                            <h3
                                class="text-base md:text-lg font-bold text-[#0F4C20] flex items-center gap-2 border-b border-gray-100 pb-2">
                                <x-heroicon-s-shopping-bag class="w-4 h-4 md:w-5 md:h-5" />
                                Ringkasan Produk
                            </h3>

                            <div class="flex flex-col gap-2.5 md:gap-3">
                                @foreach($order->items as $item)
                                <div
                                    class="flex gap-3 md:gap-4 p-2.5 md:p-3 bg-[#FAFAFA] border border-gray-100 rounded-lg items-center">
                                    <div
                                        class="w-14 h-14 md:w-16 md:h-16 bg-white rounded-md overflow-hidden shrink-0 border border-gray-200">
                                        @if($item->product->primaryImage)
                                        <img src="{{ asset('storage/'.$item->product->primaryImage->image_path) }}"
                                            class="w-full h-full object-cover">
                                        @else
                                        <img src="https://placehold.co/100x100/brown/white?text={{ substr($item->product->name, 0, 3) }}"
                                            class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-1 flex flex-col justify-center min-w-0">
                                        <span class="text-[9px] md:text-xs font-bold text-gray-400 line-clamp-1">{{
                                            $item->product->category->name ?? 'Produk' }}</span>
                                        <h4
                                            class="text-xs md:text-base font-bold text-[#2E3B27] line-clamp-1 md:line-clamp-2 mt-0.5">
                                            {{ $item->product->name }}</h4>
                                        <span class="text-[#8B4513] font-bold text-[10px] md:text-xs mt-0.5 md:mt-1">{{
                                            $item->quantity }} Pcs</span>
                                    </div>
                                    <div class="flex flex-col items-end shrink-0 pl-2">
                                        <span class="text-sm md:text-base font-bold text-gray-600">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 md:gap-3 mt-1 md:mt-2">
                            <h3
                                class="text-base md:text-lg font-bold text-[#0F4C20] flex items-center gap-2 border-b border-gray-100 pb-2">
                                <x-heroicon-s-map-pin class="w-4 h-4 md:w-5 h-5" />
                                Alamat Pengiriman
                            </h3>

                            <div
                                class="text-gray-600 text-xs md:text-sm leading-relaxed p-3 bg-[#F8F9FA] rounded-lg border border-gray-100">
                                <div class="flex flex-wrap items-baseline gap-1.5 md:gap-2 mb-1">
                                    <span class="font-bold text-[#2E3B27] text-sm md:text-base">{{
                                        $order->recipient_name }}</span>
                                    <span class="text-gray-500 font-medium">({{ $order->recipient_phone }})</span>
                                </div>
                                <p>{{ $order->shipping_address }}</p>
                                @if($order->notes)
                                <p class="mt-2 text-[10px] md:text-xs text-gray-400 italic">Catatan: {{ $order->notes }}
                                </p>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="md:col-span-5">
                        <div class="bg-white border border-gray-200 rounded-xl p-4 md:p-5 h-full flex flex-col">
                            <h3
                                class="text-base md:text-lg font-bold text-[#0F4C20] flex items-center gap-2 border-b border-gray-100 pb-2 md:pb-3 mb-3 md:mb-4">
                                <x-heroicon-s-receipt-percent class="w-4 h-4 md:w-5 md:h-5" />
                                Rincian Tagihan
                            </h3>

                            <div class="space-y-2.5 md:space-y-3 text-xs md:text-sm flex-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Sub Total</span>
                                    <span class="font-bold text-gray-800">Rp {{ number_format($order->sub_total, 0, ',',
                                        '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Layanan</span>
                                    <span class="font-bold text-gray-800">Rp 1.000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Pengiriman</span>
                                    <span class="font-bold text-gray-800">Rp {{ number_format($order->shipping_cost, 0,
                                        ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Discount</span>
                                    <span class="font-bold text-gray-800">-Rp 0</span>
                                </div>
                            </div>

                            <div
                                class="pt-3 md:pt-4 border-t border-dashed border-gray-200 mt-3 md:mt-4 flex justify-between items-center">
                                <span class="text-sm md:text-base font-bold text-gray-700">Total</span>
                                <span class="text-lg md:text-xl font-bold text-[#0F4C20]">Rp {{
                                    number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>

                            <div class="mt-3 md:mt-4 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-[10px] md:text-xs font-bold bg-yellow-100 text-yellow-800 tracking-wide uppercase">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <div
                    class="flex flex-col sm:flex-row items-center justify-center gap-3 md:gap-4 pt-4 md:pt-6 border-t border-gray-100">
                    <a href="{{ route('katalog') }}"
                        class="px-6 py-2.5 md:py-3 rounded-lg border border-[#0F4C20] text-[#0F4C20] font-bold text-xs md:text-sm hover:bg-green-50 transition w-full sm:w-auto text-center">
                        Belanja Lagi
                    </a>

                    <a href="{{ route('profile.orders') }}"
                        class="px-6 py-2.5 md:py-3 rounded-lg bg-[#0F4C20] text-white font-bold text-xs md:text-sm hover:bg-[#0b3a18] transition w-full sm:w-auto text-center shadow-sm">
                        Lihat Status Pesanan
                    </a>
                </div>

            </div>

        </div>
    </section>

    <x-footer />

</body>

</html>