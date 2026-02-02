<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Nusa Belanja</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] font-sans antialiased text-gray-800">

    <x-navbar />

    <section class="pt-28 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto">

            <div
                class="w-full relative flex flex-col items-center p-2.5 box-border gap-1 text-center font-plus-jakarta-sans">

                <div
                    class="w-[108px] h-[108px] bg-[#0F4C20] rounded-full flex items-center justify-center mb-2 shadow-sm animate-bounce">
                    <x-heroicon-s-check class="w-15 h-15 text-white" />
                </div>

                <div class="self-stretch flex flex-col items-center p-2.5 gap-2.5">
                    <h1
                        class="relative tracking-[-0.6px] leading-tight text-3xl md:text-[40px] font-bold text-[#0F4C20]">
                        Bukti Pembayaran Diterima
                    </h1>

                    <p
                        class="self-stretch relative text-lg md:text-xl tracking-[-0.15px] leading-[20px] text-[#8B4513] text-center font-bold">
                        Pembayaranmu aman. Saat ini sedang dalam proses verifikasi oleh admin.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <section class="pb-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto flex flex-col items-center">

            <div class="w-full bg-white border border-gray-200 rounded-xl shadow-sm p-6 md:p-8 flex flex-col gap-8">

                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-100 pb-6 gap-6">

                    <div class="flex flex-col gap-1">
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-wide">Nomor Pesanan</span>
                        <p class="text-xl font-bold text-[#2E3B27]">{{ $order->order_number }}</p>

                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-wide">Estimasi Tiba</span>
                        <div class="flex items-center gap-2 text-gray-600 font-medium">
                            <x-heroicon-s-calendar class="w-5 h-5 text-[#8B4513]" />
                            <span>
                                {{ $order->created_at->addDays(3)->translatedFormat('d M') }} -
                                {{ $order->created_at->addDays(5)->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

                    <div class="md:col-span-7 flex flex-col gap-6">

                        <div class="flex flex-col gap-4">
                            <h3
                                class="text-lg font-bold text-[#0F4C20] flex items-center gap-2 border-b border-gray-100 pb-2">
                                <x-heroicon-s-shopping-bag class="w-5 h-5" />
                                Ringkasan Produk
                            </h3>

                            <div class="flex flex-col gap-3">
                                @foreach($order->items as $item)
                                <div class="flex gap-4 p-3 bg-[#FAFAFA] border border-gray-100 rounded-lg">
                                    <div
                                        class="w-16 h-16 bg-white rounded-md overflow-hidden shrink-0 border border-gray-200">
                                        @if($item->product->primaryImage)
                                        <img src="{{ asset('storage/'.$item->product->primaryImage->image_path) }}"
                                            class="w-full h-full object-cover">
                                        @else
                                        <img src="https://placehold.co/100x100/brown/white?text={{ substr($item->product->name, 0, 3) }}"
                                            class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-1 flex flex-col justify-center">
                                        <span class="text-xs font-bold text-gray-400">{{ $item->product->category->name
                                            ?? 'Produk' }}</span>
                                        <h4 class="text-base font-bold text-[#2E3B27] line-clamp-1">{{
                                            $item->product->name }}</h4>
                                        <span class="text-[#8B4513] font-bold text-xs mt-0.5">{{ $item->quantity }}
                                            Pcs</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-base font-bold text-gray-600">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 mt-2">
                            <h3
                                class="text-lg font-bold text-[#0F4C20] flex items-center gap-2 border-b border-gray-100 pb-2">
                                <x-heroicon-s-map-pin class="w-5 h-5" />
                                Alamat Pengiriman
                            </h3>

                            <div
                                class="text-gray-600 text-sm leading-relaxed p-3 bg-[#F8F9FA] rounded-lg border border-gray-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-[#2E3B27] text-base">{{ $order->recipient_name }}</span>
                                    <span class="text-gray-500 font-medium">({{ $order->recipient_phone }})</span>
                                </div>
                                <p>{{ $order->shipping_address }}</p>
                                @if($order->notes)
                                <p class="mt-2 text-xs text-gray-400 italic">Catatan: {{ $order->notes }}</p>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="md:col-span-5">
                        <div class="bg-white border border-gray-200 rounded-xl p-5 h-full flex flex-col">
                            <h3
                                class="text-lg font-bold text-[#0F4C20] flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                                <x-heroicon-s-receipt-percent class="w-5 h-5" />
                                Rincian Pembayaran
                            </h3>

                            <div class="space-y-3 text-sm flex-1">
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
                                class="pt-4 border-t border-dashed border-gray-200 mt-4 flex justify-between items-center">
                                <span class="text-base font-bold text-gray-700">Total</span>
                                <span class="text-xl font-bold text-[#0F4C20]">Rp {{ number_format($order->total_amount,
                                    0, ',', '.') }}</span>
                            </div>

                            <div class="mt-4 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('katalog') }}"
                        class="px-6 py-3 rounded-lg border border-[#0F4C20] text-[#0F4C20] font-bold text-sm hover:bg-green-50 transition w-full sm:w-auto text-center">
                        Belanja Lagi
                    </a>

                    <a href="{{ route('profile.orders') }}"
                        class="px-6 py-3 rounded-lg bg-[#0F4C20] text-white font-bold text-sm hover:bg-[#0b3a18] transition w-full sm:w-auto text-center">
                        Lihat Status Pesanan
                    </a>
                </div>

            </div>

        </div>
    </section>

    <x-footer />

</body>

</html>