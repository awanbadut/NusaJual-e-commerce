@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 
                focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0F4C20] focus:border-transparent 
                placeholder-gray-500 text-gray-900 text-body-2 transition-all'
]) !!}>{{ $slot }}</textarea>