@props(['title' => 'Atur Pilihanmu'])

<aside {{ $attributes->merge(['class' => 'w-full lg:w-[280px] shrink-0 space-y-4']) }}> <div
        class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden sticky top-24">

        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-[#0F4C20]">{{ $title }}</h3>
        </div>

        <div class="p-5 space-y-5">
            {{ $slot }}
        </div>

    </div>
</aside>