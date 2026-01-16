<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'w-full bg-[#0F4C20] hover:bg-green-900 text-white font-bold py-3.5 rounded-lg flex items-center
    justify-center gap-2 transition-colors text-label-1 shadow-md hover:shadow-lg'
    ]) }}
    >
    {{ $slot }}
</button>