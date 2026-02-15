@if ($paginator->hasPages())
<div class="px-6 py-4 border-t border-gray-200 bg-gray-50/30 rounded-b-xl">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

        {{-- INFO TEKS (KIRI) --}}
        <p class="text-[13px] text-gray-600">
            Menampilkan <span class="font-medium text-gray-900">{{ $paginator->firstItem() }}</span> sampai
            <span class="font-medium text-gray-900">{{ $paginator->lastItem() }}</span> dari
            <span class="font-medium text-gray-900">{{ $paginator->total() }}</span> data
        </p>

        {{-- TOMBOL PAGINATION (KANAN) --}}
        <div class="flex flex-wrap gap-1 justify-center">
            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
            <span
                class="px-3 py-1 border border-gray-200 rounded text-sm text-gray-400 cursor-not-allowed bg-white">‹</span>
            @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 bg-white transition shadow-sm">‹</a>
            @endif

            {{-- Loop Nomor Halaman --}}
            @foreach ($elements as $element)
            {{-- "Three Dots" Separator (...) --}}
            @if (is_string($element))
            <span class="px-3 py-1 border border-gray-200 rounded text-sm text-gray-400 bg-gray-50 cursor-default">{{
                $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
            @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            {{-- HALAMAN AKTIF --}}
            <span
                class="px-3 py-1 border border-green-600 bg-green-600 text-white rounded text-sm font-medium shadow-sm cursor-default">{{
                $page }}</span>
            @else
            {{-- HALAMAN BIASA --}}
            <a href="{{ $url }}"
                class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 bg-white transition shadow-sm">{{
                $page }}</a>
            @endif
            @endforeach
            @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 bg-white transition shadow-sm">›</a>
            @else
            <span
                class="px-3 py-1 border border-gray-200 rounded text-sm text-gray-400 cursor-not-allowed bg-white">›</span>
            @endif
        </div>
    </div>
</div>
@endif