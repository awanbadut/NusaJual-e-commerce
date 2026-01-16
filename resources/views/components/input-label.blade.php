@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-label-2 font-bold text-gray-700 mb-2']) }}>
    {{ $value ?? $slot }}
</label>