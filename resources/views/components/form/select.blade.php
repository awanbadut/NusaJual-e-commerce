@props(['disabled' => false])

<div class="relative">
    <select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
        'class' => 'w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50
        focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0F4C20] focus:border-transparent
        text-gray-900 text-body-2 transition-all appearance-none cursor-pointer'
        ]) !!}>
        {{ $slot }}
    </select>

    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>
</div>